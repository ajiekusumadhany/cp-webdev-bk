<?php
// Definisikan base URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/cp-webdev-bk/';
?>
<?php 
session_start();
require_once 'koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php

// Query untuk mengambil data jumlah poli dan dokter
$query_poli = "SELECT COUNT(*) as jumlah_poli FROM poli";
$query_dokter = "SELECT COUNT(*) as jumlah_dokter FROM dokter";

$result_poli = $mysqli->query($query_poli);
$result_dokter = $mysqli->query($query_dokter);

$data_poli = $result_poli->fetch_assoc();
$data_dokter = $result_dokter->fetch_assoc();

// Menyimpan data dalam variabel
$jumlah_poli = $data_poli['jumlah_poli'];
$jumlah_dokter = $data_dokter['jumlah_dokter'];


?>    

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Temu Janji Poliklinik</title>
    <meta name="description" content="Sistem Temu Janji Poliklinik" />
    <meta name="keywords" content="poliklinik" />

    <!-- Favicons -->
    <link rel="shortcut icon" href="<?php echo $base_url; ?>assets/img/logo.png" type="image/x-icon">


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="assets/medialab/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/medialab/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/medialab/vendor/aos/aos.css" rel="stylesheet" />
    <link href="assets/medialab/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="assets/medialab/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
    <link href="assets/medialab/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

    <!-- Main CSS File -->
    <link href="assets/medialab/css/main.css" rel="stylesheet" />
    <?php include 'partials/stylesheet.php'?>
</head>

<body class="index-page">
    <header id="header" class="header sticky-top">
        <div class="branding d-flex align-items-center">
            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="index.html" class="logo d-flex align-items-center me-auto">
                    <img src="<?php echo $base_url; ?>assets/img/logo.png" alt="Logo Poliklinik" />
                    <h1 class="sitename">Aklinik</h1>
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li>
                            <a href="#hero" class="active">Home<br /></a>
                        </li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#services">Layanan</a></li>
                        <li><a href="#doctors">Dokter</a></li>
                        <li><a href="#contact">Kontak</a></li>
                        <li class="d-xl-none mb-2">
                            <a href="pages/auth/login-pasien.php" class="btn btn-primary login-btn w-100">Login Pasien</a>
                        </li>
                        <li class="d-xl-none mb-2">
                            <a href="pages/auth/login.php" class="btn btn-secondary login-btn w-100">Login Dokter</a>
                        </li>

                        <!-- Add Login buttons here for smaller screens -->
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

                <!-- Show Login buttons only on larger screens -->
                <div class="cta-btns d-none d-xl-flex gap-2">
                    <a class="btn btn-primary" href="pages/auth/login-pasien.php">Login Pasien</a>
                    <a class="btn btn-secondary" href="pages/auth/login.php">Login Dokter</a>
                </div>
            </div>
        </div>
    </header>


    <main class="main">
        <!-- Hero Section -->
        <section id="hero" class="hero section light-background">
            <img src="assets/medialab/img/hero-bg.jpg" alt="" data-aos="fade-in" />

            <div class="container position-relative">
                <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
                    <h2>SELAMAT DATANG DI POLIKLINIK</h2>
                    <p>Layanan Kesehatan dengan Kasih Sayang</p>
                </div>
                <!-- End Welcome -->

                <div class="content row gy-4 justify-content-center align-items-center">
                    <!-- Why Box -->
                    <div class="col-lg-4 d-flex align-items-stretch">
                        <div class="why-box text-center p-4 shadow-sm rounded" data-aos="zoom-out" data-aos-delay="200">
                            <h3>Mengapa Memilih Poliklinik?</h3>
                            <p>
                                Poliklinik menawarkan tim profesional medis yang terampil dan
                                berdedikasi untuk kesehatan Anda, memberikan layanan
                                komprehensif dengan perhatian, kasih sayang, dan keahlian.
                            </p>
                            <div class="text-center mt-3">
                                <a href="#about" class="btn btn-primary">
                                    <span>Baca Selengkapnya</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Why Box -->

                    <!-- Icon Boxes -->
                    <div class="col-lg-8 d-flex flex-column align-items-center">
                        <div class="row gy-4 w-100 text-center">
                            <!-- Dokter -->
                            <div class="col-md-6">
                                <div class="icon-box p-4 shadow-sm rounded" data-aos="zoom-out" data-aos-delay="300">
                                    <i class="fa-solid fa-user-doctor display-4 text-primary mb-3"></i>
                                    <h4 class="fw-bold">Dokter</h4>
                                    <p class="fs-3 mb-0"><?php echo $jumlah_dokter; ?></p>
                                </div>
                            </div>
                            <!-- End Dokter -->

                            <!-- Poli -->
                            <div class="col-md-6">
                                <div class="icon-box p-4 shadow-sm rounded" data-aos="zoom-out" data-aos-delay="400">
                                    <i class="fa-regular fa-hospital display-4 text-primary mb-3"></i>
                                    <h4 class="fw-bold">Poli</h4>
                                    <p class="fs-3 mb-0"><?php echo $jumlah_poli; ?></p>
                                </div>
                            </div>
                            <!-- End Poli -->
                        </div>
                    </div>
                </div>

                <!-- End  Content-->
            </div>
        </section>
        <!-- /Hero Section -->
        <!-- Login Section -->
        <section class="py-5 border-bottom" id="features-login">

<div class="container px-5 my-5">
    <div class="row gx-5">
        <div class="col-lg mb-5 mb-lg-0 mt-5">
            <div class="feature-login bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-person"></i></div>
            <h2 class="h4 fw-bolder">Registrasi Sebagai Pasien</h2>
            <p>Apabila Anda adalah seorang Pasien, silahkan Registrasi terlebih dahulu untuk melakukan pendaftaran sebagai Pasien!</p>
            <a class="text-decoration-none" href="./pages/auth/register.php">
                Klik Link Berikut
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="col-lg mb-0 mt-lg-5 mt-0">
            <div class="feature-login bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-person"></i></div>
            <h2 class="h4 fw-bolder">Login Sebagai Dokter</h2>
            <p>Apabila Anda adalah seorang Dokter, silahkan Login terlebih dahulu untuk memulai melayani Pasien!</p>
            <a class="text-decoration-none" href="./pages/auth/login.php">
                Klik Link Berikut
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
</section>
        <!-- /Login Section -->
        <!-- About Section -->
        <section id="about" class="about section">
            <div class="container">
                <div class="row gy-4 gx-5">
                    <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="200">
                        <img src="assets/medialab/img/about.jpg" class="img-fluid" alt="" />
                    </div>

                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                        <h3>Tentang Kami</h3>
                        <p>
                            Kami adalah Poliklinik yang berkomitmen untuk menyediakan
                            layanan kesehatan berkualitas bagi seluruh masyarakat. Dengan
                            tim medis yang profesional dan berpengalaman, kami memberikan
                            perawatan yang penuh perhatian dan fokus pada kebutuhan
                            kesehatan pasien.
                        </p>
                        <ul>
                            <li>
                                <i class="fa-solid fa-vial-circle-check"></i>
                                <div>
                                    <h5>Pelayanan Terbaik</h5>
                                    <p>
                                        Kami memberikan pelayanan terbaik dengan tim medis yang berpengalaman dan fasilitas yang lengkap.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <i class="fa-solid fa-pump-medical"></i>
                                <div>
                                    <h5>Fasilitas Modern</h5>
                                    <p>
                                        Fasilitas modern dan canggih untuk mendukung proses diagnosa dan perawatan yang akurat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <i class="fa-solid fa-heart-circle-xmark"></i>
                                <div>
                                    <h5>Perawatan dengan Kasih Sayang</h5>
                                    <p>
                                        Kami memberikan perawatan dengan penuh kasih sayang dan perhatian untuk kenyamanan pasien.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- /About Section -->

        <!-- Services Section -->
        <section id="services" class="services section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Layanan Kami</h2>
                <p>
                    Kami menyediakan berbagai layanan kesehatan untuk memenuhi kebutuhan Anda.
                </p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>Perawatan Jantung</h3>
                            </a>
                            <p>
                                Layanan perawatan jantung dengan teknologi terkini dan tim medis yang berpengalaman.
                            </p>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="fas fa-pills"></i>
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>Apotek</h3>
                            </a>
                            <p>
                                Apotek lengkap dengan berbagai macam obat-obatan untuk kebutuhan kesehatan Anda.
                            </p>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="fas fa-hospital-user"></i>
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>Rawat Inap</h3>
                            </a>
                            <p>
                                Fasilitas rawat inap yang nyaman dan aman untuk pemulihan kesehatan Anda.
                            </p>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="fas fa-dna"></i>
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>Laboratorium</h3>
                            </a>
                            <p>
                                Laboratorium dengan peralatan modern untuk mendukung diagnosa yang akurat.
                            </p>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="fas fa-wheelchair"></i>
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>Rehabilitasi</h3>
                            </a>
                            <p>
                                Layanan rehabilitasi untuk membantu pemulihan pasca operasi atau cedera.
                            </p>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="fas fa-notes-medical"></i>
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>Konsultasi Medis</h3>
                            </a>
                            <p>
                                Konsultasi medis dengan dokter spesialis untuk mendapatkan penanganan yang tepat.
                            </p>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>
                    <!-- End Service Item -->
                </div>
            </div>
        </section>

        <!-- Doctors Section -->
        <section id="doctors" class="doctors section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Dokter Kami</h2>
                <p>
                    Tim dokter profesional dan berpengalaman siap melayani Anda.
                </p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="team-member d-flex align-items-start">
                            <div class="pic">
                                <img src="medialab/img/doctors/doctors-1.jpg" class="img-fluid"
                                    alt="" />
                            </div>
                            <div class="member-info">
                                <h4>Dr. Walter White</h4>
                                <span>Chief Medical Officer</span>
                                <p>
                                    Ahli dalam bidang medis dengan pengalaman lebih dari 20 tahun.
                                </p>
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""> <i class="bi bi-linkedin"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Team Member -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="team-member d-flex align-items-start">
                            <div class="pic">
                                <img src="medialab/img/doctors/doctors-2.jpg" class="img-fluid"
                                    alt="" />
                            </div>
                            <div class="member-info">
                                <h4>Dr. Sarah Jhonson</h4>
                                <span>Anesthesiologist</span>
                                <p>
                                    Spesialis anestesi dengan keahlian dalam penanganan pasien sebelum, selama, dan setelah operasi.
                                </p>
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""> <i class="bi bi-linkedin"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Team Member -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="team-member d-flex align-items-start">
                            <div class="pic">
                                <img src="medialab/img/doctors/doctors-3.jpg" class="img-fluid"
                                    alt="" />
                            </div>
                            <div class="member-info">
                                <h4>Dr. William Anderson</h4>
                                <span>Spesialis Kardiologi</span>
                                <p>
                                    Ahli dalam bidang kardiologi dengan fokus pada kesehatan jantung dan pembuluh darah.
                                </p>
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""> <i class="bi bi-linkedin"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Team Member -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="team-member d-flex align-items-start">
                            <div class="pic">
                                <img src="medialab/img/doctors/doctors-4.jpg" class="img-fluid"
                                    alt="" />
                            </div>
                            <div class="member-info">
                                <h4>Amanda Jepson</h4>
                                <span>Neurosurgeon</span>
                                <p>
                                    Dolorum tempora officiis odit laborum officiis et et
                                    accusamus
                                </p>
                                <div class="social">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""> <i class="bi bi-linkedin"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Team Member -->
                </div>
            </div>
        </section>
        <!-- /Doctors Section -->
        <!-- Faq Section -->
        <section id="faq" class="faq section light-background">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Pertanyaan yang Sering Diajukan</h2>
                <p>
                    Temukan jawaban atas pertanyaan umum yang sering diajukan oleh pelanggan kami.
                </p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                        <div class="faq-container">
                            <div class="faq-item faq-active">
                                <h3>Apa itu Medilab?</h3>
                                <div class="faq-content">
                                    <p>
                                        Medilab adalah klinik kesehatan yang menyediakan berbagai layanan medis dengan dokter-dokter spesialis terbaik.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Bagaimana cara membuat janji dengan dokter?</h3>
                                <div class="faq-content">
                                    <p>
                                        Anda dapat membuat janji dengan dokter melalui website kami atau menghubungi nomor telepon yang tersedia di halaman kontak.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Apakah Medilab menerima asuransi kesehatan?</h3>
                                <div class="faq-content">
                                    <p>
                                        Ya, Medilab bekerja sama dengan berbagai perusahaan asuransi kesehatan untuk memudahkan Anda dalam mendapatkan layanan medis.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Apakah Medilab menyediakan layanan darurat?</h3>
                                <div class="faq-content">
                                    <p>
                                        Medilab menyediakan layanan darurat 24 jam untuk menangani kondisi medis yang memerlukan penanganan segera.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Bagaimana cara mendapatkan hasil lab?</h3>
                                <div class="faq-content">
                                    <p>
                                        Hasil lab dapat diambil langsung di klinik atau dikirim melalui email sesuai dengan permintaan Anda.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Apakah Medilab menyediakan layanan konsultasi online?</h3>
                                <div class="faq-content">
                                    <p>
                                        Ya, Medilab menyediakan layanan konsultasi online untuk memudahkan Anda berkonsultasi dengan dokter tanpa harus datang ke klinik.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>
                            <!-- End Faq item-->
                        </div>
                    </div>
                    <!-- End Faq Column-->
                </div>
            </div>
        </section>
        <!-- /Faq Section -->
        <!-- Testimoni Section -->
        <section id="testimonials" class="testimonials section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 info" data-aos="fade-up" data-aos-delay="100">
                        <h3>Testimoni</h3>
                        <p>
                            Kami tidak bekerja untuk mendapatkan keuntungan dari layanan ini. Kami
                            hanya ingin memberikan yang terbaik untuk Anda. Kami berharap Anda
                            merasa puas dengan layanan kami.
                        </p>
                    </div>

                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
                        <div class="swiper init-swiper">
                            <script type="application/json" class="swiper-config">
                                {
                                    "loop": true,
                                    "speed": 600,
                                    "autoplay": {
                                        "delay": 5000
                                    },
                                    "slidesPerView": "auto",
                                    "pagination": {
                                        "el": ".swiper-pagination",
                                        "type": "bullets",
                                        "clickable": true
                                    }
                                }
                            </script>
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="assets/medialab/img/testimonials/testimonials-1.jpg"
                                                class="testimonial-img flex-shrink-0" alt="" />
                                            <div>
                                                <h3>Saul Goodman</h3>
                                                <h4>Ceo &amp; Pendiri</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Saya sangat puas dengan layanan yang diberikan. Tim medis sangat profesional dan perhatian. Saya merasa sangat diperhatikan dan dirawat dengan baik.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="assets/medialab/img/testimonials/testimonials-2.jpg"
                                                class="testimonial-img flex-shrink-0" alt="" />
                                            <div>
                                                <h3>Sara Wilsson</h3>
                                                <h4>Desainer</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Saya sangat terkesan dengan layanan yang diberikan. Tim medis sangat profesional dan ramah. Saya merasa sangat diperhatikan dan dirawat dengan baik.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="assets/medialab/img/testimonials/testimonials-3.jpg"
                                                class="testimonial-img flex-shrink-0" alt="" />
                                            <div>
                                                <h3>Jena Karlis</h3>
                                                <h4>Pemilik Toko</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Saya sangat puas dengan layanan yang diberikan. Tim medis sangat profesional dan perhatian. Saya merasa sangat diperhatikan dan dirawat dengan baik.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="assets/medialab/img/testimonials/testimonials-4.jpg"
                                                class="testimonial-img flex-shrink-0" alt="" />
                                            <div>
                                                <h3>Matt Brandon</h3>
                                                <h4>Freelancer</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Saya sangat terkesan dengan layanan yang diberikan. Tim medis sangat profesional dan ramah. Saya merasa sangat diperhatikan dan dirawat dengan baik.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="assets/medialab/img/testimonials/testimonials-5.jpg"
                                                class="testimonial-img flex-shrink-0" alt="" />
                                            <div>
                                                <h3>John Larson</h3>
                                                <h4>Pengusaha</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                        class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Saya sangat puas dengan layanan yang diberikan. Tim medis sangat profesional dan perhatian. Saya merasa sangat diperhatikan dan dirawat dengan baik.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>
                                <!-- End testimonial item -->
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Testimoni Section -->

        <!-- Galeri Section -->
        <section id="gallery" class="gallery section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Galeri</h2>
                <p>
                    Lihatlah beberapa momen terbaik kami di galeri ini.
                </p>
            </div>
            <!-- End Section Title -->

            <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-0">
                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="assets/medialab/img/gallery/gallery-1.jpg" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="assets/medialab/img/gallery/gallery-1.jpg" alt=""
                                    class="img-fluid" />
                            </a>
                        </div>
                    </div>
                    <!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="assets/medialab/img/gallery/gallery-2.jpg" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="assets/medialab/img/gallery/gallery-2.jpg" alt=""
                                    class="img-fluid" />
                            </a>
                        </div>
                    </div>
                    <!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="assets/medialab/img/gallery/gallery-3.jpg" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="assets/medialab/img/gallery/gallery-3.jpg" alt=""
                                    class="img-fluid" />
                            </a>
                        </div>
                    </div>
                    <!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="assets/medialab/img/gallery/gallery-4.jpg" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="assets/medialab/img/gallery/gallery-4.jpg" alt=""
                                    class="img-fluid" />
                            </a>
                        </div>
                    </div>
                    <!-- End Gallery Item -->

                </div>
            </div>
        </section>
        <!-- /Galeri Section -->

        <!-- Kontak Section -->
        <section id="contact" class="contact section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kontak</h2>
                <p>
                    Jika Anda memiliki pertanyaan atau membutuhkan informasi lebih lanjut, jangan ragu untuk menghubungi kami.
                </p>
            </div>
            <!-- End Section Title -->

            <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                <iframe style="border: 0; width: 100%; height: 270px"
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus"
                    frameborder="0" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <!-- End Google Maps -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <div class="col-lg-4">
                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Lokasi</h3>
                                <p>A108 Adam Street, New York, NY 535022</p>
                            </div>
                        </div>
                        <!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Hubungi Kami</h3>
                                <p>+1 5589 55488 55</p>
                            </div>
                        </div>
                        <!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>Email Kami</h3>
                                <p>info@example.com</p>
                            </div>
                        </div>
                        <!-- End Info Item -->
                    </div>

                    <div class="col-lg-8">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Nama Anda" required="" />
                                </div>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Email Anda" required="" />
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subjek"
                                        required="" />
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Pesan" required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Memuat</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">
                                        Pesan Anda telah terkirim. Terima kasih!
                                    </div>

                                    <button type="submit">Kirim Pesan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Contact Form -->
                </div>
            </div>
        </section>
        <!-- /Kontak Section -->
    </main>

    <footer id="footer" class="footer light-background">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Medilab</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>A108 Adam Street</p>
                        <p>New York, NY 535022</p>
                        <p class="mt-3">
                            <strong>Telepon:</strong> <span>+1 5589 55488 55</span>
                        </p>
                        <p><strong>Email:</strong> <span>info@example.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Tautan Berguna</h4>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Layanan</a></li>
                        <li><a href="#">Syarat Layanan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Layanan Kami</h4>
                    <ul>
                        <li><a href="#">Desain Web</a></li>
                        <li><a href="#">Pengembangan Web</a></li>
                        <li><a href="#">Manajemen Produk</a></li>
                        <li><a href="#">Pemasaran</a></li>
                        <li><a href="#">Desain Grafis</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Hic solutasetp</h4>
                    <ul>
                        <li><a href="#">Molestiae accusamus iure</a></li>
                        <li><a href="#">Excepturi dignissimos</a></li>
                        <li><a href="#">Suscipit distinctio</a></li>
                        <li><a href="#">Dilecta</a></li>
                        <li><a href="#">Sit quas consectetur</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Nobis illum</h4>
                    <ul>
                        <li><a href="#">Ipsam</a></li>
                        <li><a href="#">Laudantium dolorum</a></li>
                        <li><a href="#">Dinera</a></li>
                        <li><a href="#">Trodelas</a></li>
                        <li><a href="#">Flexo</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>
                © <span>Hak Cipta</span>
                <strong class="px-1 sitename">Poliklinik</strong>
                <span>Semua Hak Dilindungi</span>
            </p>
            <div class="credits">
                Dirancang oleh <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/medialab/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/medialab/vendor/php-email-form/validate.js"></script>
    <script src="assets/medialab/vendor/aos/aos.js"></script>
    <script src="assets/medialab/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/medialab/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/medialab/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/medialab/js/main.js"></script>
</body>

</html>
