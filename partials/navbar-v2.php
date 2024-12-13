<nav class="navbar navbar-expand-lg position-fixed ">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="dist/img/logo.png" alt="Logo" width="30" height="30">
            <span class="m-0 ms-2 fw-bold text-primary">Aclinic</span>
        </a>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3 fw-semibold">
                <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?>
                  <a class="btn btn-primary" href="#features-login">Login</a>
                <?php else : ?>
                <li class="nav-item">
                    <?php if ($_SESSION['role'] == 'admin') : ?>
                        <a class="btn btn-primary" href="./pages/admin/">Dashboard</a>
                    <?php elseif ($_SESSION['role'] == 'dokter') : ?>
                        <a class="btn btn-primary" href="./pages/dokter/">Dashboard</a>
                    <?php elseif ($_SESSION['role'] == 'pasien') : ?>
                        <a class="btn btn-primary" href="./pages/pasien/">Dashboard</a>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>