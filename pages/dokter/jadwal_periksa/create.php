<?php 
session_start();
require_once '../../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pengecekan apakah pengguna sudah login, session timeout, dan role-nya admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'dokter') {
    header("Location: ../../../"); // Redirect ke halaman login jika belum login atau bukan admin
    exit;
} 

// Proses form jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $id_dokter = $_SESSION['user_id']; // Menggunakan user_id dari sesion
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    $query_ambil_jadwal = "select hari from jadwal_periksa where id_dokter ='$id_dokter' and hari = '$hari'";
    $stmt_ambil_jadwal= $mysqli->prepare($query_ambil_jadwal);
    $stmt_ambil_jadwal->execute();
    $cek_jadwal = $stmt_ambil_jadwal->get_result();

    if ($cek_jadwal->num_rows < 1){

    // Proses insert data ke database
    $sql = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai, status) VALUES (?, ?, ?, ?, 'N')";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        // Mengikat parameter
        $stmt->bind_param("isss", $id_dokter, $hari, $jam_mulai, $jam_selesai);
        
        // Eksekusi pernyataan
        $result = $stmt->execute();
        
        // Menentukan pesan sukses atau error
        $success_message = $result ? 'Jadwal periksa berhasil disimpan' : 'Gagal menyimpan jadwal periksa: ' . $stmt->error;
    } else {
        $error_message = 'Gagal menyiapkan query: ' . $mysqli->error;
    }
}else{
    $error_message = 'Sudah ada jadwal praktek di hari: ' .$hari . $mysqli->error;
}

    // Menyimpan pesan ke dalam sesi
    if (isset($success_message)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => $success_message];
    } elseif (isset($error_message)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => $error_message];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Jadwal Periksa | Dashboard</title>

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
            <h1 class="m-0">Tambah Jadwal Periksa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Tambah Jadwal Periksa</li>
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
                <h3 class="card-title">Tambah Jadwal Periksa</h3>
            </div>
            <div class="card-body">
                <form action="" id="tambahJadwal" method="POST">
                    <input type="hidden" name="id_dokter" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select name="hari" id="hari" class="form-control" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
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
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './';
            }
        });
        ";

        unset($_SESSION['flash_message']);
    }
    ?>
</script>


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