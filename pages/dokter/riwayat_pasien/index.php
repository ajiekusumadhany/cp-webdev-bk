<?php 
session_start();
require_once '../../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pengecekan apakah pengguna sudah login, session timeout, dan role-nya dokter
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'dokter') {
    header("Location: ../../../"); // Redirect ke halaman login jika belum login atau bukan dokter
    exit;
} 

// Ambil data pasien dari database
$query = "
    SELECT pasien.id as idPasien, pasien.nama as namaPasien, pasien.alamat, pasien.no_ktp, pasien.no_hp, pasien.no_rm 
    FROM pasien"; 

$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Pasien | Dashboard</title>

  <?php include '../../../partials/stylesheet.php'?>
  <!-- Pastikan jQuery dan Bootstrap dimuat -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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
            <h1 class="m-0">Riwayat Pasien</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Riwayat Pasien</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Riwayat Pasien</h3>
          </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Pasien</th>
                  <th>Alamat</th>
                  <th>No. KTP</th>
                  <th>No. Telepon</th>
                  <th>No. RM</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1; // Inisialisasi nomor urut
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$no}</td>";
                    echo "<td>{$row['namaPasien']}</td>";
                    echo "<td>{$row['alamat']}</td>";
                    echo "<td>{$row['no_ktp']}</td>";
                    echo "<td>{$row['no_hp']}</td>";
                    echo "<td>{$row['no_rm']}</td>";
                    echo "<td>
                            <button data-toggle='modal' data-target='#detailModal{$row['idPasien']}' class='btn btn-info btn-sm'>
                              <i class='fa fa-eye'></i> Detail Riwayat Periksa
                            </button>
                          </td>";
                    echo "</tr>";
                    $no++;
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

   <!-- Modal Detail Riwayat Periksa start here -->
<?php 
// Reset pointer ke awal hasil query untuk mengambil data riwayat periksa
mysqli_data_seek($result, 0);
while ($row = mysqli_fetch_assoc($result)): 
    $idPasien = $row['idPasien'];
    $riwayat_query = "
        SELECT 
            periksa.tgl_periksa, 
            pasien.nama AS namaPasien, 
            dokter.nama AS namaDokter, 
            daftar_poli.keluhan, 
            periksa.catatan,
            GROUP_CONCAT(obat.nama_obat) AS namaObat,
            periksa.biaya_periksa 
        FROM 
            periksa 
        INNER JOIN 
            daftar_poli ON periksa.id_daftar_poli = daftar_poli.id 
        INNER JOIN 
            detail_periksa ON periksa.id = detail_periksa.id_periksa 
        INNER JOIN 
            obat ON detail_periksa.id_obat = obat.id 
        INNER JOIN 
            jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id 
        INNER JOIN 
            dokter ON jadwal_periksa.id_dokter = dokter.id 
        INNER JOIN 
            pasien ON daftar_poli.id_pasien = pasien.id 
        WHERE 
            pasien.id = '$idPasien' 
        GROUP BY 
            periksa.id";

    $riwayat_result = mysqli_query($mysqli, $riwayat_query);
?>
<div class="modal fade" id="detailModal<?php echo $idPasien; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Riwayat <?php echo $row['namaPasien']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (mysqli_num_rows($riwayat_result) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Periksa</th>
                                <th>Nama Pasien</th>
                                <th>Nama Dokter</th>
                                <th>Keluhan</th>
                                <th>Catatan</th>
                                <th>Obat</th>
                                <th>Total Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $nomor = 1; while ($riwayat_row = mysqli_fetch_assoc($riwayat_result)): ?>
                                <tr>
                                    <td><?php echo $nomor++; ?></td>
                                    <td><?php echo $riwayat_row['tgl_periksa']; ?></td>
                                    <td><?php echo $riwayat_row['namaPasien']; ?></td>
                                    <td><?php echo $riwayat_row['namaDokter']; ?></td>
                                    <td><?php echo $riwayat_row['keluhan']; ?></td>
                                    <td><?php echo $riwayat_row['catatan']; ?></td>
                                    <td><?php echo $riwayat_row['namaObat']; ?></td>
                                    <td><?php echo "Rp" . number_format($riwayat_row['biaya_periksa'], 0, ',', '.'); ?></td>
                                    </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h5>Tidak Ditemukan Riwayat Periksa</h5>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>
<!-- Modal Detail Riwayat Periksa ends here -->

  </div>
  <!-- /.control-sidebar -->
  <!-- /.content-wrapper -->
  <?php include '../../../partials/footer.php'?>
    <!-- Js file -->
    <?php include '../../../partials/js.php'?>

</div>
<!-- ./wrapper -->

</body>
</html>