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
$id_daftar_poli = $_GET['id'];

// Query untuk mengambil nama pasien berdasarkan id_daftar_poli
$query_pasien = "SELECT p.nama, dp.id_pasien 
                 FROM daftar_poli dp 
                 JOIN pasien p ON dp.id_pasien = p.id 
                 WHERE dp.id = ?";
$stmt_pasien = $mysqli->prepare($query_pasien);
$stmt_pasien->bind_param("i", $id_daftar_poli);
$stmt_pasien->execute();
$result_pasien = $stmt_pasien->get_result();

if ($result_pasien->num_rows > 0) {
    $row_pasien = $result_pasien->fetch_assoc();
    $nama_pasien = $row_pasien['nama'];
} else {
    die("Pasien tidak ditemukan.");
}

// Query untuk mengambil data obat
$query_obat = "SELECT id, nama_obat, harga, kemasan FROM obat"; // Pastikan kolom kemasan ada di tabel obat
$stmt_obat = $mysqli->prepare($query_obat);
$stmt_obat->execute();
$result_obat = $stmt_obat->get_result();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_periksa'])) {
  $tgl_periksa = $_POST['tgl_periksa'];
  $catatan = $_POST['catatan'];
  $biaya_periksa = $_POST['harga'];
  $biaya_periksa = (int) str_replace(['Rp.', '.'], '', $biaya_periksa);
  
  // Ambil ID obat yang dipilih
  $obat_ids = isset($_POST['obat_ids']) ? $_POST['obat_ids'] : [];

  // Insert data ke tabel periksa
  $query_insert = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) VALUES (?, ?, ?, ?)";
  $stmt_insert = $mysqli->prepare($query_insert);
  $stmt_insert->bind_param("issi", $id_daftar_poli, $tgl_periksa, $catatan, $biaya_periksa);

  if ($stmt_insert->execute()) {
      // Ambil ID pemeriksaan yang baru saja disimpan
      $id_periksa = $stmt_insert->insert_id;

      // Insert data ke tabel detail_periksa untuk setiap obat yang dipilih
      $query_detail_insert = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES (?, ?)";
      $stmt_detail_insert = $mysqli->prepare($query_detail_insert);
      
      foreach ($obat_ids as $id_obat) {
          $stmt_detail_insert->bind_param("ii", $id_periksa, $id_obat);
          $stmt_detail_insert->execute();
      }

      // Update status_periksa di tabel daftar_poli
      $query_update = "UPDATE daftar_poli SET status_periksa = 1 WHERE id = ?";
      $stmt_update = $mysqli->prepare($query_update);
      $stmt_update->bind_param("i", $id_daftar_poli);
      $stmt_update->execute();

      $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data pemeriksaan berhasil disimpan.'];
      header("Location: ./");
      exit;
  } else {
      $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menyimpan data: ' . $stmt_insert->error];
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Periksa Pasien | Dashboard</title>

  <?php include '../../../partials/stylesheet.php'?>
  <!-- Include Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
            <h1 class="m-0">Periksa Pasien</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Periksa Pasien</li>
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
            <h3 class="card-title">Periksa Pasien</h3>
          </div>
          <div class="card-body">
            <form action="" method="POST">
              <!-- Kolom input untuk menambahkan data -->
              <div class="form-group">
                <label for="nama_pasien">Nama Pasien</label>
                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?php echo htmlspecialchars($nama_pasien); ?>" disabled>
              </div>

              <div class="form-group">
                <label for="tgl_periksa">Tanggal Periksa</label>
                <input type="datetime-local" class="form-control" id="tgl_periksa" name="tgl_periksa">
              </div>

              <div class="form-group">
                <label for="catatan">Catatan</label>
                <input type="text" class="form-control" id="catatan" name="catatan">
              </ ```php
              </div>

              <div class="form-group mt-3">
                <label for="obat">Pilih Obat</label>
                <select id="obat" name="obat_ids[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                  <?php while ($row_obat = $result_obat->fetch_assoc()): ?>
                    <option value="<?php echo $row_obat['id']; ?>">
                      <?php echo htmlspecialchars($row_obat['nama_obat']) . ' - ' . htmlspecialchars($row_obat['kemasan']) . ' - Rp.' . number_format($row_obat['harga'], 0, ',', '.'); ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="total_harga">Total Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" readonly>
              </div>

              <!-- Tombol untuk mengirim form -->
              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" id="simpan_periksa" name="simpan_periksa">
                  <i class="fa fa-save"></i> Simpan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

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

<!-- Include jQuery and Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Pilih Obat",
        allowClear: true
    });

// Update total harga saat obat dipilih
$('#obat').on('change', function() {
    let total = 0;
    $(this).find(':selected').each(function() {
        // Ambil harga dari teks opsi
        const hargaText = $(this).text(); // Ambil teks dari opsi
        const harga = parseInt(hargaText.match(/Rp\.(\d+(\.\d+)?)/)[1].replace(/\./g, '')); // Ambil harga dan hapus titik
        total += harga; // Tambahkan harga ke total
    });
    $('#harga').val('Rp.' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')); // Format total harga
});
});
</script>

</body>
</html>