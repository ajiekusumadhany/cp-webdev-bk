<?php 
session_start();
require_once '../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];

    // Cek apakah pasien sudah terdaftar berdasarkan no KTP
    $query = "SELECT * FROM pasien WHERE no_ktp = '$no_ktp'";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Pasien dengan No KTP ini sudah terdaftar.";
    } else {
        $current_year_month = date('Ym');
        $query_count = "SELECT COUNT(*) as total FROM pasien WHERE no_rm LIKE '$current_year_month%'";
        $result_count = $mysqli->query($query_count);
        $row_count = $result_count->fetch_assoc();
        $total_pasien = $row_count['total'] + 1;
        $no_rm = $current_year_month . '-' . str_pad($total_pasien, 3, '0', STR_PAD_LEFT);

        // Insert data pasien baru
        $query_insert = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm')";
        if (mysqli_query($mysqli, $query_insert)) {
            $success = "Pendaftaran berhasil. No RM Anda adalah $no_rm.";
        } else {
            $error = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | Register</title>
  <?php include '../../partials/stylesheet.php'?>
</head>
<body class="hold-transition login-page bg-light">
<div class="login-box">
<?php 
        // Pesan error atau sukses
        if(isset($error)) { 
            echo "<div class='alert alert-danger'>$error</div>";
        } elseif(isset($success)) {
            echo "<div class='alert alert-success'>$success</div>";
        }
        ?>
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../" class="h1" style="text-decoration: none;"><b>A</b>clinic</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new account</p>

      <!-- nama -->
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" required placeholder="Nama" name="nama" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <!-- alamat -->
        <div class="input-group mb-3">
          <input type="text" class="form-control" required placeholder="Alamat" name="alamat" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fa fa-map-marker"></span>
            </div>
          </div>
        </div>
        <!-- no ktp -->
        <div class="input-group mb-3">
          <input type="number" class="form-control" required placeholder="No. Ktp" name="no_ktp" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fa fa-address-book"></span>
            </div>
          </div>
        </div>
        <!-- no hp -->
        <div class="input-group mb-3">
          <input type="number" class="form-control" required placeholder="No. Hp" name="no_hp" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone-square"></span>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" required name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="row">
        <div class="col-12">
          <a href="../../pages/auth/login-pasien.php">Already have an account?</a>
        </div>
      </div>

    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- Js file -->
<!-- jQuery -->
<?php include '../../partials/js.php'?>
</body>
</html>
