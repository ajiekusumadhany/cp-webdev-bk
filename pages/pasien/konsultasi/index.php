<?php 
session_start();
require_once '../../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pengecekan apakah pengguna sudah login, session timeout, dan role-nya admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'pasien') {
    header("Location: ../../../"); // Redirect ke halaman login jika belum login atau bukan admin
    exit;
} 

// Hapus data dokter
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $delete_query = "DELETE FROM konsultasi WHERE id = ?";
    $delete_stmt = $mysqli->prepare($delete_query);
    
    if ($delete_stmt) {
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data berhasil dihapus'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menghapus data: ' . $delete_stmt->error];
        }
        
        $delete_stmt->close();
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menyiapkan query: ' . $mysqli->error];
    }
}

// Proses penyimpanan atau update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $pertanyaan = isset($_POST['pertanyaan']) ? $_POST['pertanyaan'] : '';
    $id_dokter = isset($_POST['id_dokter']) ? $_POST['id_dokter'] : '';
    $id_pasien = $_SESSION['user_id'];

        // Proses penyimpanan data
        if ($id) {
            // Update
            $sql = "UPDATE konsultasi SET subject = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $subject, $id);
        } else {
            if (empty($subject) || empty($pertanyaan) || empty($id_dokter) || empty($id_pasien)) {
                $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Harap isi semua kolom!'];
            }else{
            // Insert
            $sql = "INSERT INTO konsultasi (subject, pertanyaan, id_dokter, id_pasien) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssii", $subject, $pertanyaan, $id_dokter, $id_pasien);
            }
        }

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data berhasil disimpan'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menyimpan data: ' . $mysqli->error];
        }
        $stmt->close();
    
    
    header('Location: ./');
    exit;
}

// Cek apakah ada ID yang dikirimkan melalui GET untuk edit
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Ambil ID dari GET
    // Query untuk mengambil data dokter berdasarkan ID
    $ambil = mysqli_query($mysqli, "SELECT konsultasi.id, dokter.nama, dokter.alamat, dokter.no_hp, poli.nama_poli, dokter.id_poli FROM dokter INNER JOIN poli ON dokter.id_poli = poli.id WHERE dokter.id='" . $id . "'");

    // Cek apakah query berhasil
    if ($ambil) {
        $data = mysqli_fetch_assoc($ambil);
        $id = $data['id'];
        $nama = $data['nama'];
        $alamat = $data['alamat'];
        $no_hp = $data['no_hp'];
        $nama_poli = $data['nama_poli'];        
    } else {
        echo "Error: " . mysqli_error($mysqli); 
    }
}

$query = "SELECT konsultasi.id as id, konsultasi.id_dokter, konsultasi.id_pasien, konsultasi.subject as subject, konsultasi.pertanyaan as pertanyaan, konsultasi.tgl_konsultasi as tgl_konsultasi, konsultasi.jawaban as jawaban, dokter.nama AS nama_dokter from konsultasi
join dokter on konsultasi.id_dokter = dokter.id
join pasien on konsultasi.id_pasien = pasien.id where konsultasi.id_pasien = ?";
$stmt_konsultasi= $mysqli->prepare($query);
$stmt_konsultasi->bind_param("i", $_SESSION['user_id']); 
$stmt_konsultasi->execute();
$result = $stmt_konsultasi->get_result();


// Query untuk mengambil data poli
$query_dokter = "SELECT id, nama FROM dokter";
$result_dokter = $mysqli->query($query_dokter);

if (!$result) {
    die("Error: " . $mysqli->error);
}

?>    
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dokter | Dashboard</title>

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
            <h1 class="m-0">Konsultasi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dokter</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="container-dokter">
<button class="mb-3 btn btn-primary" onclick="showDoctorForm('add');">
<i class="bi bi-person-plus-fill"></i> Tambah
</button>    
<div class="card">
  <div class="card-body table-responsive">
    
    <table id="myTable" class="table table-striped" >
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Tanggal Konsultasi</th>
          <th scope="col">Nama Dokter</th>
          <th scope="col">Subject</th>
          <th scope="col">Pertanyaan</th>
          <th scope="col">Tanggapan</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $start_number = 1; // atau nilai yang sesuai
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $start_number++ . "</td>";
        echo "<td>" . htmlspecialchars($row['tgl_konsultasi']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_dokter']) . "</td>";
        echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
        echo "<td>" . htmlspecialchars($row['pertanyaan']) . "</td>";
        echo "<td>" . htmlspecialchars($row['jawaban']) . "</td>";
        echo "<td>
        <button class='btn btn-sm btn-success' onclick='showDoctorForm(\"edit\", " . $row['id'] . ", \"" . addslashes($row['subject']) . "\", \"" . addslashes($row['pertanyaan']) . "\",\"" . addslashes($row['id_dokter']) . "\")'><i class='bi bi-pencil-square'></i> Edit</button>
        <button class='btn btn-sm btn-danger' onclick='deleteDokter(" . $row['id'] . ")'><i class='bi bi-trash-fill'></i> Hapus</button>
    </td>";
        echo "</tr>";
    }
    ?>
</tbody>
    </table>
   </div>

</div>
<script>
// Fungsi showDoctorForm
function showDoctorForm(action, id = null, subject = '', pertanyaan = '', id_dokter = '') {
    const title = action === 'add' ? 'Tambah Konsultasi' : 'Edit Subject';
    const buttonText = action === 'add' ? 'Simpan' : 'Update';

    if (action === 'edit') {
        const newUrl = `index.php?action=edit&id=${id}`;
        window.history.pushState({ id: id }, '', newUrl);
    }

    // Menampilkan SweetAlert dengan formulir
    Swal.fire({
        title: title,
        html: `
        <form id="doctorForm" method="POST">
            ${action === 'edit' ? `<input type="hidden" name="id" value="${id}">` : ''}
            <input type="text" id="subject" name="subject" class="swal2-input" value="${subject}" placeholder="Subject" required>

            ${action === 'add' ? `<input type="text" id="pertanyaan" name="pertanyaan" class="swal2-input" value="${pertanyaan}" placeholder="Pertanyaan" required>` : ''}
            ${action === 'add' ? `<select id="id_dokter" name="id_dokter" class="swal2-select" required>
                <?php 
                $result_dokter->data_seek(0); // Reset pointer ke awal
                while ($row_dokter = $result_dokter->fetch_assoc()) { ?>
                            <option value="<?php echo $row_dokter['id']; ?>"
                                    ${id_dokter == "<?php echo $row_dokter['id']; ?>" ? 'selected' : ''}>
                                    <?php echo $row_dokter['nama']; ?>
                            </option>
                <?php } ?>
            </select>` : ''}


        </form>
        `,
        showCancelButton: true,
        confirmButtonText: buttonText,
        cancelButtonText: 'Batal',
        focusConfirm: false,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6B7280',
        preConfirm: () => {
            const form = document.getElementById('doctorForm');
            if (!form.checkValidity()) {
                Swal.showValidationMessage('Harap isi semua kolom!');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('doctorForm');
            form.action = `index.php?action=${action}`;
            form.submit();
        }
    });
}


    function deleteDokter(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data dokter akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#007bff',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?action=delete&id=" + id;
        }
    });
}
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
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
 
</body>
</html>
