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

// Ambil ID dari URL
$id_jadwal = $_GET['id'] ?? null;

if ($id_jadwal) {
    // Ambil data jadwal periksa berdasarkan ID
    $query = "SELECT * FROM jadwal_periksa WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id_jadwal);
    $stmt->execute();
    $result = $stmt->get_result();
    $jadwal = $result->fetch_assoc();

    if (!$jadwal) {
        die("Jadwal tidak ditemukan.");
    }
}

// Proses form jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $id_jadwal = $_POST['id_jadwal']; // ID jadwal yang akan diupdate
    $id_dokter = $_SESSION['user_id']; // Menggunakan user_id dari sesi
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $status = $_POST['aktif']; // Mengambil status dari radio button

    // Proses update data ke database
    $sql = "UPDATE jadwal_periksa SET id_dokter = ?, hari = ?, jam_mulai = ?, jam_selesai = ?, status = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        // Mengikat parameter
        $stmt->bind_param("issssi", $id_dokter, $hari, $jam_mulai, $jam_selesai, $status, $id_jadwal);
        
        // Eksekusi pernyataan
        $result = $stmt->execute();
        
        // Menentukan pesan sukses atau error
        $success_message = $result ? 'Jadwal periksa berhasil diperbarui' : 'Gagal memperbarui jadwal periksa: ' . $stmt->error;
    } else {
        $error_message = 'Gagal menyiapkan query: ' . $mysqli->error;
    }

    // Menyimpan pesan ke dalam sesi
    if (isset($success_message)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => $success_message];
    } elseif (isset($error_message)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => $error_message];
    }

    header('Location: ./'); // Redirect ke halaman yang sesuai setelah proses
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Jadwal Periksa | Dashboard</title>

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
            <h1 class="m-0">Edit Jadwal Periksa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Jadwal Periksa</li>
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
    <h3 class="card-title">Edit Jadwal Periksa</h3>
  </div>
  <div class="card-body">
    <form action="" id="editJadwal" method="POST">
      <input type="hidden" name="id_jadwal" value="<?php echo htmlspecialchars($jadwal['id']); ?>">
      <div class="form-group">
        <label for="hari">Hari</label>
        <input type="text" name="hari" id="hari" class="form-control" value="<?php echo htmlspecialchars($jadwal['hari']); ?>" required>
      </div>
      <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?php echo htmlspecialchars($jadwal['jam_mulai']); ?>" required>
      </div>
      <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?php echo htmlspecialchars($jadwal['jam_selesai']); ?>" required>
      </div>
      <div class="form-group">
        <label for="aktif">Status</label>
        <div class="form-check">
          <input type="radio" id="aktif1" class="form-check-input" name="aktif" value="Y" <?php echo ($jadwal['status'] === 'Y') ? 'checked' : ''; ?>>
          <label for="aktif1" class="form-check-label">Aktif</label>
        </div>
        <div class="form-check">
          <input type="radio" id="tidak-aktif" class="form-check-input" name="aktif" value="N" <?php echo ($jadwal['status'] === 'N') ? 'checked' : ''; ?>>
          <label for="tidak-aktif" class="form-check-label">Tidak Aktif</label>
        </div>
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" name="submit" id="submitButton" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

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