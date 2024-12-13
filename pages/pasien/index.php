<?php 
session_start();
require_once '../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php

// Pengecekan apakah pengguna sudah login, session timeout, dan role-nya pasien
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'pasien') {
    header("Location: ../../"); // Redirect ke halaman login jika belum login atau bukan pasien
    exit;
} 

// Query untuk mengambil data jumlah poli, dokter, pasien, dan obat
$query_poli = "SELECT COUNT(*) as jumlah_poli FROM poli";
$query_dokter = "SELECT COUNT(*) as jumlah_dokter FROM dokter";
$query_pasien = "SELECT COUNT(*) as jumlah_pasien FROM pasien";
$query_obat = "SELECT COUNT(*) as jumlah_obat FROM obat";

$result_poli = $mysqli->query($query_poli);
$result_dokter = $mysqli->query($query_dokter);
$result_pasien = $mysqli->query($query_pasien);
$result_obat = $mysqli->query($query_obat);

$data_poli = $result_poli->fetch_assoc();
$data_dokter = $result_dokter->fetch_assoc();
$data_pasien = $result_pasien->fetch_assoc();
$data_obat = $result_obat->fetch_assoc();

// Menyimpan data dalam variabel
$jumlah_poli = $data_poli['jumlah_poli'];
$jumlah_dokter = $data_dokter['jumlah_dokter'];
$jumlah_pasien = $data_pasien['jumlah_pasien'];
$jumlah_obat = $data_obat['jumlah_obat'];


?>    
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aclinic | Dashboard</title>

  <?php include '../../partials/stylesheet.php'?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../dist/img/logo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include '../../partials/navbar.php'?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include '../../partials/sidebar.php'?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $jumlah_poli; ?></h3>

                <p>Poli</p>
              </div>
              <div class="icon">
                <i class="fas fa-hospital"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $jumlah_dokter; ?></h3>
                <p>Dokter</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-md"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $jumlah_pasien; ?></h3>
                <p>Pasien</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-injured"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $jumlah_obat; ?></h3>

                <p>Obat</p>
              </div>
              <div class="icon">
                <i class="fas fa-pills"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include '../../partials/footer.php'?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Js file -->
 <?php include '../../partials/js.php'?>
</body>
</html>
