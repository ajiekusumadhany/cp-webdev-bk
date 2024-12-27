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
    $id_pasien = $row_pasien['id_pasien'];
} else {
    die("Pasien tidak ditemukan untuk id_daftar_poli: " . htmlspecialchars($id_daftar_poli)); // Debugging
}

// Query untuk mengambil data pemeriksaan berdasarkan id_daftar_poli
$query_periksa = "SELECT id, tgl_periksa, catatan, biaya_periksa FROM periksa WHERE id_daftar_poli = ?";
$stmt_periksa = $mysqli->prepare($query_periksa);
$stmt_periksa->bind_param("i", $id_daftar_poli);
$stmt_periksa->execute();
$result_periksa = $stmt_periksa->get_result();

$data_periksa = null;
if ($result_periksa->num_rows > 0) {
    $data_periksa = $result_periksa->fetch_assoc();
    $id_periksa = $data_periksa['id']; 
    $biaya_periksa = $data_periksa['biaya_periksa']; // Ambil biaya periksa dari data
} else {
    $biaya_periksa = 0; // Jika tidak ada data, set biaya periksa ke 0
}

// Ambil ID obat yang dipilih dari detail_periksa berdasarkan id_periksa
$query_detail = "SELECT id_obat FROM detail_periksa WHERE id_periksa = ?";
$stmt_detail = $mysqli->prepare($query_detail); // Persiapkan statement
$stmt_detail->bind_param("i", $id_periksa); // Bind parameter
$stmt_detail->execute(); // Eksekusi statement
$result_detail = $stmt_detail->get_result(); // Ambil hasil

$selected_obat_ids = [];
while ($row_detail = $result_detail->fetch_assoc()) {
    $selected_obat_ids[] = $row_detail['id_obat'];
}

// Query untuk mengambil semua obat
$query_obat = "SELECT id, nama_obat, harga, kemasan FROM obat"; 
$stmt_obat = $mysqli->prepare($query_obat);
$stmt_obat->execute();
$result_obat = $stmt_obat->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Periksa Pasien | Dashboard</title>

  <?php include '../../../partials/stylesheet.php'?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include '../../../partials/navbar.php'?>
  <?php include '../../../partials/sidebar.php'?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Periksa Pasien</h1>
    
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Periksa Pasien</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Periksa Pasien</h3>
          </div>
          <div class="card-body">
            <form action="" method="POST">
                
              <div class="form-group">
                <label for="nama_pasien">Nama Pasien</label>
                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?php echo htmlspecialchars($nama_pasien); ?>" disabled>
              </div>

              <div class="form-group">
                <label for="tgl_periksa">Tanggal Periksa</label>
                <input type="datetime-local" class="form-control" id="tgl_periksa" name="tgl_periksa" value="<?php echo isset($data_periksa['tgl_periksa']) ? htmlspecialchars($data_periksa['tgl_periksa']) : ''; ?>">
              </div>

              <div class="form-group">
                <label for="catatan">Catatan</label>
                <input type="text" class="form-control" id="catatan" name="catatan" value="<?php echo isset($data_periksa['catatan']) ? htmlspecialchars($data_periksa['catatan']) : ''; ?>">
              </div>
              
              <div class="form-group">
                <label for="obat">Pilih Obat</label>
                <select class="form-control select2" id="obat" name="obat_ids[]" multiple>
                    <?php while ($row_obat = $result_obat->fetch_assoc()): ?>
                        <option value="<?php echo $row_obat['id']; ?>" <?php echo in_array($row_obat['id'], $selected_obat_ids) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row_obat['nama_obat']); ?> - Rp. <?php echo number_format($row_obat['harga'], 0, ',', '.'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="harga">Total Harga Obat</label>
                <input type="text" class="form-control" id="totalBiaya" name="harga" value="Rp. <?php echo number_format(0, 0, ',', '.'); ?>" readonly>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" id="simpan_periksa" name="simpan_periksa">
                  <i class="fa fa-save"></i> Simpan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include '../../../partials/footer.php'?>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php include '../../../partials/js.php'?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Pilih Obat",
        allowClear: true
    });

    // Biaya jasa dokter
    const biayaJasaDokter = 150000; // Rp. 150.000

    // Fungsi untuk menghitung total biaya
    function calculateTotal() {
        let totalObat = 0; // Inisialisasi total obat

        // Hitung total harga obat
        $('#obat').find(':selected').each(function() {
            const hargaText = $(this).text(); // Ambil teks dari opsi
            const harga = parseInt(hargaText.match(/Rp\.\s*(\d+(\.\d+)?)/)[1].replace(/\./g, '')); // Ambil harga dan hapus titik
            totalObat += harga; // Tambahkan harga ke total obat
        });

        // Hitung total biaya periksa
        const totalBiayaPeriksa = totalObat + biayaJasaDokter; // Total biaya = total obat + biaya jasa dokter

        // Format dan tampil kan total harga obat
        $('#totalBiaya').val('Rp. ' + totalBiayaPeriksa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')); // Menampilkan total biaya periksa
    }

    // Hitung total biaya saat halaman dimuat
    calculateTotal();

    // Update total harga saat obat dipilih
    $('#obat').on('change', function() {
        calculateTotal();
    });
});
</script>

</body>
</html>