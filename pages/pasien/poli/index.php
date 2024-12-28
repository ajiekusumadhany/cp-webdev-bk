<?php 
session_start();
require_once '../../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'pasien') {
    header("Location: ../../../"); // Redirect to login page if not logged in
    exit;
}

// Get patient data based on user_id from session
$id_pasien = $_SESSION['user_id'];
$query_pasien = "SELECT * FROM pasien WHERE id = ?";
$stmt_pasien = $mysqli->prepare($query_pasien);
$stmt_pasien->bind_param("i", $id_pasien);
$stmt_pasien->execute();
$result_pasien = $stmt_pasien->get_result();
$row_pasien = $result_pasien->fetch_assoc();

if (!$row_pasien) {
    die("Pasien tidak ditemukan.");
}

// Get poli data
$query_poli = "SELECT * FROM poli";
$result_poli = $mysqli->query($query_poli);

// Get all schedules
$query_jadwal = "SELECT j.*, d.id_poli 
                 FROM jadwal_periksa j 
                 JOIN dokter d ON j.id_dokter = d.id";
$result_jadwal = $mysqli->query($query_jadwal);

// Store all schedules in an array
$jadwal_list = [];
while ($row_jadwal = $result_jadwal->fetch_assoc()) {
    $jadwal_list[] = $row_jadwal;
}

// Process form if submit button is pressed
if (isset($_POST['submit'])) {
    $id_jadwal = $_POST['id_jadwal'];
    $keluhan = $_POST['keluhan'];

    // Calculate queue number based on id_jadwal
    $query_no_antrian = "SELECT MAX(no_antrian) AS max_antrian FROM daftar_poli WHERE id_jadwal = ?";
    $stmt_no_antrian = $mysqli->prepare($query_no_antrian);
    $stmt_no_antrian->bind_param("i", $id_jadwal);
    $stmt_no_antrian->execute();
    $result_no_antrian = $stmt_no_antrian->get_result();
    $row_no_antrian = $result_no_antrian->fetch_assoc();

    // Queue number is max_antrian + 1, if no previous registration, set queue number to 1
    $no_antrian = $row_no_antrian['max_antrian'] ? $row_no_antrian['max_antrian'] + 1 : 1;

    // Insert into daftar_poli table
    $query_insert = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) VALUES (?, ?, ?, ?)";
    $stmt_insert = $mysqli->prepare($query_insert);
    $stmt_insert->bind_param("iisi", $id_pasien, $id_jadwal, $keluhan, $no_antrian);
    
    if ($stmt_insert->execute()) {
        // If successful, display success message
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Pendaftaran berhasil.'];
    } else {
        // If failed, display error message
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal mendaftar: ' . $stmt_insert->error];
    }

    header('Location: ./'); // Redirect to the appropriate page after processing
    exit;
}

// Retrieve registration history
$query_riwayat = "SELECT dp.*, p.nama_poli, d.nama AS dokter_nama, j.hari, j.jam_mulai, j.jam_selesai, pr.tgl_periksa 
                  FROM daftar_poli dp 
                  JOIN jadwal_periksa j ON dp.id_jadwal = j.id 
                  JOIN dokter d ON j.id_dokter = d.id 
                  JOIN poli p ON d.id_poli = p.id 
                  LEFT JOIN periksa pr ON dp.id = pr.id_daftar_poli
                  WHERE dp.id_pasien = ?";
$stmt_riwayat = $mysqli->prepare($query_riwayat);
$stmt_riwayat->bind_param("i", $id_pasien);
$stmt_riwayat->execute();
$result_riwayat = $stmt_riwayat->get_result();

?>

<script>
    // Store schedules in a JavaScript variable
    var jadwalList = <?php echo json_encode($jadwal_list); ?>;

    function updateJadwal() {
        var poliId = document.getElementById("inputPoli").value; // Get the selected poli value
        var jadwalSelect = document.getElementById("inputJadwal"); // Get the jadwal dropdown element

        // Clear the jadwal dropdown
        jadwalSelect.innerHTML = '<option value="">Open this select menu</option>';

        // Filter schedules based on the selected poli
        jadwalList.forEach(function(jadwal) {
            if (jadwal.id_poli == poliId) { 
                var option = document.createElement("option");
                option.value = jadwal.id; 
                option.text = jadwal.hari + ' - ' + jadwal.jam_mulai + ' s/d ' + jadwal.jam_selesai;
                jadwalSelect.appendChild(option); 
            }
        });
    }
</script>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Poli | Dashboard</title>
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
            <h1 class="m-0">Daftar Poli</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

      <div class="row">
        <div class="col-4">
          <!-- Registration poli -->
          <div class="card">
            <h5 class="card-header bg-primary">Daftar Poli</h5>
            <div class="card-body">

              <form action="" method="POST">
                <input type="hidden" value="<?php echo htmlspecialchars($row_pasien['id']); ?>" name="id_pasien">
                <div class="mb-3">
                  <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                  <input type="text" class="form-control" id="no_rm" placeholder="nomor rekam medis" name="no_rm" value="<?php echo htmlspecialchars($row_pasien['no_rm']); ?>" disabled>
                </div>

                <div class="mb-3">
                  <label for="inputPoli" class="form-label">Pilih Poli</label>
                  <select id="inputPoli" class="form-control" name="id_poli" onchange="updateJadwal()">
                    <option value="">Open this select menu</option>
                    <?php while ($row_poli = $result_poli->fetch_assoc()) { ?>
                      <option value="<?php echo $row_poli['id']; ?>">
                        <?php echo htmlspecialchars($row_poli['nama_poli']); ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
                
                <div class="mb-3">
                    <label for="inputJadwal" class="form-label">Pilih Jadwal</label>
                    <select id="inputJadwal" class="form-control" name="id_jadwal">
                        <option value="">Open this select menu</option>
                    </select>
                </div>

                <div class="mb-3">
                  <label for="keluhan" class="form-label">Keluhan</label>
                  <textarea class="form-control" id="keluhan" rows="3" name="keluhan" required></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Daftar</button>
              </form>
              
            </div>
          </div>
          <!-- End registration poli -->
        </div>

        <div class="col-8">
          <!-- Registration poli history -->
          <div class="card">
            <h5 class="card-header bg-primary">Riwayat daftar poli</h5>
            <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Poli</th>
                        <th scope="col">Dokter</th>
                        <th scope="col">Hari</th>
                        <th scope="col">Mulai</th>
                        <th scope="col">Selesai</th>
                        <th scope="col">Antrian</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result_riwayat->num_rows > 0): ?>
                    <?php $no = 1; while ($row_riwayat = $result_riwayat->fetch_assoc()): ?>
                        <tr>
                            <th scope="row"><?php echo $no++; ?></th>
                            <td><?php echo htmlspecialchars($row_riwayat['nama_poli']); ?></td>
                            <td><?php echo htmlspecialchars($row_riwayat['dokter_nama']); ?></td>
                            <td><?php echo htmlspecialchars($row_riwayat['hari']); ?></td>
                            <td><?php echo htmlspecialchars($row_riwayat['jam_mulai']); ?></td>
                            <td><?php echo htmlspecialchars($row_riwayat['jam_selesai']); ?></td>
                            <td><?php echo htmlspecialchars($row_riwayat['no_antrian']); ?></td>
                            <td>
                                <?php if ($row_riwayat['status_periksa'] == 0): ?>
                                    <span class="badge bg-danger">Belum diperiksa</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Sudah diperiksa</span><br>
                                    <span class="badge bg-default text-dark"><i><?php echo htmlspecialchars($row_riwayat['tgl_periksa']); ?></i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row_riwayat['status_periksa'] == 0): ?>
                                    <a href="detail_poli.php?id=<?php echo $row_riwayat['id']; ?>">
                                        <button class="btn btn-info btn-sm">Detail</button>
                                    </a>
                                <?php else: ?>
                                    <a href="detail_poli_periksa.php?id=<?php echo $row_riwayat['id']; ?>">
                                        <button class="btn btn-success btn-sm">Riwayat</button>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
            </div>
          </div>
          <!-- End registration poli history -->
        </div>
      </div>
      </div>
    </section>
  </div> <!-- /.content-wrapper -->

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
  <!-- Footer -->
  <?php include '../../../partials/footer.php'?> 
  <!-- /.footer -->
  <!-- Js file -->
 <?php include '../../../partials/js.php'?>
</div> <!-- ./wrapper -->
</body>
</html>