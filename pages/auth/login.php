<?php 
session_start();
require_once '../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | Log in</title>
  <?php include '../../partials/stylesheet.php'?>
  
</head>
<body class="hold-transition login-page bg-light">
<?php
if(isset($_POST['login'])) {
    $nama = $mysqli->real_escape_string($_POST['nama']);
    $alamat = $mysqli->real_escape_string($_POST['alamat']);

    // Cek jika nama dan password adalah "admin"
    if ($nama === 'admin' && $alamat === 'admin') {
        // Mengatur session untuk admin
        $_SESSION['user_id'] = 'admin';
        $_SESSION['nama'] = 'Admin';
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'admin';

        echo "<script>
        console.log('SweetAlert is about to be triggered');

        Swal.fire({
            title: 'Login berhasil!',
            text: 'Selamat datang " . $_SESSION['nama'] . "!',
            icon: 'success',
            iconColor: '#28a745',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../admin';
            }
        });
        </script>";
    } else {

    // Query untuk memeriksa user
    $query = "SELECT * FROM dokter WHERE nama = '$nama'";
    $result = $mysqli->query($query);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password
        if($alamat === $row['alamat']) {
            // Mengatur session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'dokter';    

            echo "<script>
            console.log('SweetAlert is about to be triggered');

            Swal.fire({
                title: 'Login berhasil!',
                text: 'Selamat datang " . $row['nama'] . "!',
                icon: 'success',
                iconColor: '#28a745',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../dokter';
                }
            });
            </script>";
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";

    }
}
}
if (isset($mysqli)) {
    $mysqli->close();
}
?>
<div class="login-box">
<?php 
        // Pesan error login
        if(isset($error)) { 
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>
  <!-- /.login-logo -->
  <div class="card card-outline card-primary shadow rounded-4">
    <div class="card-header text-center">
    <a href="../../" class="h1" style="text-decoration: none;"><b>A</b>clinic</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="nama" class="form-control" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="alamat" class="form-control" placeholder="Password" autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- <p class="mb-0 mt-2">
      <p class="register">Don't have an account? <a class="text-primary" href="register.php">Register</a></p>
      </p> -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- Js file -->
<?php include '../../partials/js.php'?>

</body>
</html>
