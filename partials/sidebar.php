
  <aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="<?php echo $base_url; ?>" class="brand-link" style="text-decoration: none;">
      <img src="<?php echo $base_url; ?>assets/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><b>Aclinic</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $base_url; ?>assets/img/avatar2.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" style="text-decoration: none;"><b><?php echo $_SESSION['nama']; ?></b></a>
        </div>
      </div>
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php if ($_SESSION['role'] == 'admin') : ?>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/admin/" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
                <span class="right badge badge-success">Admin</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/admin/dokter" class="nav-link">
              <i class="nav-icon fas fa-user-md"></i>
              <p>
                Dokter
                <span class="right badge badge-success">Admin</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/admin/pasien" class="nav-link">
              <i class="nav-icon fas fa-user-injured"></i>
              <p>
                Pasien
                <span class="right badge badge-success">Admin</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/admin/poli" class="nav-link">
              <i class="nav-icon fas fa-hospital"></i>
              <p>
                Poli
                <span class="right badge badge-success">Admin</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/admin/obat" class="nav-link">
              <i class="nav-icon fas fa-pills"></i>
              <p>
                Obat
                <span class="right badge badge-success">Admin</span>
              </p>
            </a>
          </li>
          <?php elseif ($_SESSION['role'] == 'dokter') : ?>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/dokter/" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
                <span class="right badge badge-danger">Dokter</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/dokter/jadwal_periksa" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Jadwal Periksa
                <span class="right badge badge-danger">Dokter</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/dokter/memeriksa_pasien" class="nav-link">
              <i class="nav-icon fas fa-user-md"></i>
              <p>
                Memeriksa Pasien
                <span class="right badge badge-danger">Dokter</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/dokter/riwayat_pasien" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Riwayat Pasien
                <span class="right badge badge-danger">Dokter</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/dokter/konsultasi" class="nav-link">
            <i class="nav-icon fas fa-solid fa-comment-medical"></i>

              <p>
                Konsultasi
                <span class="right badge badge-danger">Dokter</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/dokter/profil" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profil
                <span class="right badge badge-danger">Dokter</span>
              </p>
            </a>
          </li>
          <?php elseif ($_SESSION['role'] == 'pasien') : ?>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/pasien/" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
                <span class="right badge badge-primary">Pasien</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/pasien/poli" class="nav-link">
              <i class="nav-icon fas fa-hospital"></i>
              <p>
                Poli
                <span class="right badge badge-primary">Pasien</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>pages/pasien/konsultasi" class="nav-link">
            <i class="nav-icon fas fa-solid fa-comment-medical"></i>
              <p>
                Konsultasi
                <span class="right badge badge-primary">Pasien</span>
              </p>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>