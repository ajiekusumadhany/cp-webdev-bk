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

// Query untuk mengambil data Jadwal Periksa
$query = "SELECT j.*, d.nama AS nama 
          FROM jadwal_periksa j 
          JOIN dokter d ON j.id_dokter = d.id 
          WHERE j.id_dokter = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']); 

// Eksekusi pernyataan
$stmt->execute();

// Ambil hasilnya
$result = $stmt->get_result();
if (!$result) {
    die("Error: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jadwal Periksa | Dashboard</title>

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
            <h1 class="m-0">Jadwal Periksa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Jadwal Periksa</li>
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
    <div class="row">
      <div class="col-6">
        <h3 class="card-title">Daftar Jadwal Periksa</h3>
      </div>
      <div class="col-6">
        <a href="create.php" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Jadwal Periksa</a>
      </div>
    </div>
  </div>
  <div class="card-body">
  <table id="tabel-jadwal-periksa" class="table table-bordered table-striped">
  <thead>
    <tr>
        <th>No</th>
        <th>Nama Dokter</th>
        <th>Hari</th>
        <th>Jam Mulai</th>
        <th>Jam Selesai</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
<?php
$start_number = 1;
while ($row = $result->fetch_assoc()) { 
    echo "<tr>";
    echo "<td>" . $start_number++ . "</td>";
    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
    echo "<td>" . htmlspecialchars($row['hari']) . "</td>"; 
    echo "<td>" . htmlspecialchars($row['jam_mulai']) . "</td>"; 
    echo "<td>" . htmlspecialchars($row['jam_selesai']) . "</td>"; 
   $status = ($row['status'] === 'Y') ? 'Aktif' : 'Tidak Aktif';
   echo "<td>" . htmlspecialchars($status) . "</td>"; // Menampilkan status
    echo "<td>
    <button class='btn btn-sm btn-success' onclick=\"window.location.href='edit.php?id=" . htmlspecialchars($row['id']) . "'\">
        <i class='bi bi-pencil-square'></i> Edit
    </button>
    </td>";
    echo "</tr>";
}
?>
</tbody>
  </table>

  </div>
</div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include '../../../partials/footer.php'?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Js file -->
 <?php include '../../../partials/js.php'?>
</body>
</html>