<?php 
session_start();
require_once '../../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'pasien') {
    header("Location: ../../../"); // Redirect ke halaman login jika tidak login
    exit;
}

// Ambil id dari URL
$id_daftar_poli = $_GET['id'] ?? null; 

if ($id_daftar_poli === null) {
    die("ID pendaftaran poli tidak ditemukan.");
}

// Query untuk mendapatkan detail pendaftaran
$query_detail = "SELECT dp.*, 
                        p.nama_poli, 
                        d.nama AS dokter_nama, 
                        j.hari, 
                        j.jam_mulai, 
                        j.jam_selesai, 
                        pr.id AS id_periksa,
                        pr.tgl_periksa, 
                        pr.catatan, 
                        pr.biaya_periksa 
                 FROM daftar_poli dp 
                 JOIN jadwal_periksa j ON dp.id_jadwal = j.id 
                 JOIN dokter d ON j.id_dokter = d.id 
                 JOIN poli p ON d.id_poli = p.id 
                 LEFT JOIN periksa pr ON dp.id = pr.id_daftar_poli 
                 WHERE dp.id = ?";
$stmt_detail = $mysqli->prepare($query_detail);
$stmt_detail->bind_param("i", $id_daftar_poli);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();

if ($result_detail->num_rows > 0) {
    $row_detail = $result_detail->fetch_assoc();
    
} else {
    die("Detail poli tidak ditemukan.");
}

// Ambil daftar obat berdasarkan id_periksa
$query_obat = "SELECT o.nama_obat 
               FROM detail_periksa dp 
               JOIN obat o ON dp.id_obat = o.id 
               WHERE dp.id_periksa = ?";
$stmt_obat = $mysqli->prepare($query_obat);
$stmt_obat->bind_param("i", $row_detail['id_periksa']); 
$stmt_obat->execute();
$result_obat = $stmt_obat->get_result();

$obat_list = [];
while ($row_obat = $result_obat->fetch_assoc()) {
    $obat_list[] = htmlspecialchars($row_obat['nama_obat']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Poli | Dashboard</title>
  <?php include '../../../partials/stylesheet.php'?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include '../../../partials/navbar.php'?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include '../../../partials/sidebar.php'?>

  <!-- Content Wrapper. Contains page content -->
  <div ```php
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Poli</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
  
        <div class="card">
          <div class="card-header bg-primary">
            <h3 class="card-title">Riwayat Periksa</h3>
          </div>
          <div class="card-body">
            <table class="table table-sm">
              <tr>
                <th>Nama Poli</th>
                <td><?php echo htmlspecialchars($row_detail['nama_poli']); ?></td>
              </tr>
              <tr>
                <th>Nama Dokter</th>
                <td><?php echo htmlspecialchars($row_detail['dokter_nama']); ?></td>
              </tr>
              <tr>
                <th>Hari</th>
                <td><?php echo htmlspecialchars($row_detail['hari']); ?></td>
              </tr>
              <tr>
                <th>Mulai</th>
                <td><?php echo htmlspecialchars($row_detail['jam_mulai']); ?></td>
              </tr>
              <tr>
                <th>Selesai</th>
                <td><?php echo htmlspecialchars($row_detail['jam_selesai']); ?></td>
              </tr>
              <tr>
                <th>Nomor Antrian</th>
                <td><button class="btn btn-success"><?php echo htmlspecialchars($row_detail['no_antrian']); ?></button></td>
              </tr>
            </table>
            <br>
            <div class="card-body bg-light">
              <i>Tgl Periksa: <?php echo htmlspecialchars($row_detail['tgl_periksa']); ?></i><br>
              Catatan: <?php echo htmlspecialchars($row_detail['catatan']); ?><br>
              Daftar Obat Diresepkan: <br>
              <ol>
                <?php if (!empty($obat_list)): ?>
                    <?php foreach ($obat_list as $obat): ?>
                        <li><?php echo htmlspecialchars($obat ?? ''); ?></li> 
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Tidak ada obat yang diresepkan.</li>
                <?php endif; ?>
            </ol>
              <h2><span class='bg-danger text-white p-1'> Biaya Periksa: <?php echo htmlspecialchars($row_detail['biaya_periksa']); ?></span></h2>
              <br><br>
            </div>
          </div>
        </div>

        <a href="./" class="btn btn-primary btn-block">Kembali</a>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div> <!-- /.content-wrapper -->

  <script>
      <?php
  if (isset($_SESSION['flash_message'])) {
      $type = $_SESSION['flash_message']['type'];
      $message = $_SESSION['flash_message']['message'];
      
      $buttonColor = '#3085d6';
      if ($type === 'success') {
          $buttonColor = '#28a745';
      } elseif ($type === 'error') {
          $buttonColor = '#dc3545'; 
      } elseif ($type === 'warning') {
          $buttonColor = '#ffc107'; 
      }
      echo "
      Swal.fire({
          title: '" . ucfirst($type) . "',
          text: '$message',
          icon: '$type',
          confirmButtonColor: '$buttonColor'
      });
      ";

      unset($_SESSION['flash_message']);
  }
  ?>
  </script>
  <!-- Footer -->
  <?php include '../../../partials/footer.php'?> 
  <!-- /.footer -->
  <!-- Js file -->
 <?php include '../../../partials/js.php'?>
</div> <!-- ./wrapper -->
</body>
</html>