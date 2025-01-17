<?php
// Definisikan base URL
// Mendapatkan URL saat ini
$current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Memecah URL menjadi bagian-bagian
$url_parts = explode('/', $current_url);

// Mengambil nama folder proyek (elemen kedua dalam array)
$project_name = $url_parts[3]; // Indeks 3 adalah elemen keempat (0-indexed)

// Menghasilkan base URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $project_name . '/';
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light p-3">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo $base_url; ?>" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo $base_url; ?>#kontak" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="<?php echo $base_url; ?>pages/auth/logout.php" class="dropdown-item">Logout</a>
        </div>
    </li>
    </ul>
  </nav>
