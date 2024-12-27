<?php 
session_start();
require_once '../../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'pasien') {
    header("Location: ../../../"); // Redirect to login page if not logged in
    exit;
}

// Ambil id dari URL
$id_daftar_poli = $_GET['id'];

// Query untuk mengambil detail pendaftaran poli
$query_detail = "SELECT dp.*, p.nama_poli, d.nama AS dokter_nama, j.hari, j.jam_mulai, j.jam_selesai 
                 FROM daftar_poli dp 
                 JOIN jadwal_periksa j ON dp.id_jadwal = j.id 
                 JOIN dokter d ON j.id_dokter = d.id 
                 JOIN poli p ON d.id_poli = p.id 
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Poli | Dashboard</title>
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
                <h3 class="card-title">Detail Poli</h3>
            </div>
            <div class="card-body">
                <center>
                    <h5>Nama Poli</h5>
                    <p><?php echo htmlspecialchars($row_detail['nama_poli']); ?></p>
                    <hr>

                    <h5>Nama Dokter</h5>
                    <p><?php echo htmlspecialchars($row_detail['dokter_nama']); ?></p>
                    <hr>

                    <h5>Hari</h5>
                    <p><?php echo htmlspecialchars($row_detail['hari']); ?></p>
                    <hr>

                    <h5>Mulai</h5>
                    <p><?php echo htmlspecialchars($row_detail['jam_mulai']); ?></p>
                    <hr>

                    <h5>Selesai</h5>
                    <p><?php echo htmlspecialchars($row_detail['jam_selesai']); ?></p>
                    <hr>

                    <h5>Nomor Antrian</h5>
                    <button class="btn btn-success"><?php echo htmlspecialchars($row_detail['no_antrian']); ?></button>
                    <hr>
                </center>
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