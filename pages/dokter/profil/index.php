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

// Ambil data dokter berdasarkan user_id dari sesi
$id_dokter = $_SESSION['user_id'];
$query = "SELECT * FROM dokter WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_dokter);
$stmt->execute();
$result = $stmt->get_result();
$dokter = $result->fetch_assoc();

if (!$dokter) {
    die("Dokter tidak ditemukan.");
}

// Proses form jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $id_dokter = $_POST['id']; // ID dokter yang akan diupdate
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    // Proses update data ke database
    $sql = "UPDATE dokter SET nama = ?, alamat = ?, no_hp = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        // Mengikat parameter
        $stmt->bind_param("sssi", $nama, $alamat, $no_hp, $id_dokter);
        
        // Eksekusi pernyataan
        $result = $stmt->execute();
        
        // Menentukan pesan sukses atau error
        if ($result) {
            // Update session nama
            $_SESSION['nama'] = $nama; 
            $success_message = 'Data dokter berhasil diperbarui';
        } else {
            $error_message = 'Gagal memperbarui data dokter: ' . $stmt->error;
        }
    } else {
        $error_message = 'Gagal menyiapkan query: ' . $mysqli->error;
    }

    // Menyimpan pesan ke dalam sesi
    if (isset($success_message)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => $success_message];
    } elseif (isset($error_message)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => $error_message];
    }

    header('Location: ./');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Dokter | Dashboard</title>

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
            <h1 class="m-0">Profil Dokter</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="card card-primary">
        <form id="editForm" action="" method="POST">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($dokter['id']); ?>">
          <div class="card-body">
            <div class="form-group">
              <label for="nama">Nama Dokter</label>
              <input type="text" id="nama" name="nama" class ="form-control" value="<?php echo htmlspecialchars($dokter['nama']); ?>" required>
            </div>
            <div class="form-group">
              <label for="alamat">Alamat Dokter</label>
              <input type="text" id="alamat" name="alamat" class="form-control" value="<?php echo htmlspecialchars($dokter['alamat']); ?>" required>
            </div>
            <div class="form-group">
              <label for="no_hp">Telepon Dokter</label>
              <input type="number" id="no_hp" name="no_hp" class="form-control" value="<?php echo htmlspecialchars($dokter['no_hp']); ?>" required>
            </div>
            <div class="d-flex justify-content-center">
              <button type="submit" name="submit" id="submitButton" class="btn btn-primary" disabled>Simpan Perubahan</button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <script>
      const form = document.getElementById('editForm');
      const inputs = form.querySelectorAll('input');

      const checkChanges = () => {
        let changes = false;
        inputs.forEach(input => {
          if (input.defaultValue !== input.value) {
            changes = true;
          }
        });
        return changes;
      };

      const toggleSubmit = () => {
        const submitButton = document.getElementById('submitButton');
        if (checkChanges()) {
          submitButton.disabled = false;
        } else {
          submitButton.disabled = true;
        }
      };

      inputs.forEach(input => {
        input.addEventListener('input', toggleSubmit);
      });
    </script>
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
      });
      ";

      unset($_SESSION['flash_message']);
  }
  ?>
</script>
  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- /.content-wrapper -->
  <?php include '../../../partials/footer.php'?>
    <!-- Js file -->
    <?php include '../../../partials/js.php'?>

</div>
<!-- ./wrapper -->

</body>
</html>