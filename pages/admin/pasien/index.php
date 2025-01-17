<?php 
session_start();
require_once '../../../koneksi/koneksi.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pengecekan apakah pengguna sudah login, session timeout, dan role-nya admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../"); // Redirect ke halaman login jika belum login atau bukan admin
    exit;
} 


//hapus data pasien
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $delete_query = "DELETE FROM pasien WHERE id = ?";
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
    
    header('Location: index.php?page=pasien');
    exit;
}

// Cek apakah ada ID yang dikirimkan melalui GET untuk edit
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Ambil ID dari GET

    // Query untuk mengambil data pasien berdasarkan ID
    $ambil = mysqli_query($mysqli, "SELECT * FROM pasien WHERE id='" . $id . "'");

    // Cek apakah query berhasil
    if ($ambil) {
        $data = mysqli_fetch_assoc($ambil);
        $nama = $data['nama'];
        $alamat = $data['alamat'];
        $no_hp = $data['no_hp'];
        $no_ktp = $data['no_ktp'];
        $no_rm = $data['no_rm'];
    } else {
        echo "Error: " . mysqli_error($mysqli); 
    }
}

// Proses penyimpanan atau update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $no_ktp = $_POST['no_ktp'];
    $no_rm = $_POST['no_rm'] ?? null;

        // Jika ID ada, lakukan update, jika tidak, lakukan insert
        if ($id) {
            // Cek apakah no_ktp sudah terdaftar di database selain pasien yang sedang diupdate
            $query = "SELECT * FROM pasien WHERE no_ktp = ? AND id != ?";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("si", $no_ktp, $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $error_message = "Pasien dengan No KTP ini sudah terdaftar.";
                } else {
                    // Proses update data ke database
                    $sql = "UPDATE pasien SET nama = ?, alamat = ?, no_hp = ?, no_ktp = ?, no_rm = ? WHERE id = ?";
                    $stmt = $mysqli->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("sssssi", $nama, $alamat, $no_hp, $no_ktp, $no_rm, $id);
                        $result = $stmt->execute();
                        $success_message = $result ? 'Data pasien berhasil diperbarui' : 'Gagal memperbarui data pasien: ' . $mysqli->error;
                    } else {
                        $error_message = 'Gagal menyiapkan query';
                    }
                }
            } else {
                $error_message = 'Gagal menyiapkan query';
            }
        } else {
            // Cek apakah pasien sudah terdaftar berdasarkan no KTP
            $query = "SELECT * FROM pasien WHERE no_ktp = '$no_ktp'";
            $result = mysqli_query($mysqli, $query);

            if (mysqli_num_rows($result) > 0) {
                $error_message = "Pasien dengan No KTP ini sudah terdaftar.";
            } else {
                // Proses insert data ke database
                $sql = "INSERT INTO pasien (nama, alamat, no_hp, no_ktp, no_rm) VALUES (?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("sssss", $nama, $alamat, $no_hp, $no_ktp, $no_rm);
                    $result = $stmt->execute();
                    $success_message = $result ? 'Data pasien berhasil disimpan' : 'Gagal menyimpan data pasien: ' . $mysqli->error;
                } else {
                    $error_message = 'Gagal menyiapkan query';
                }
            }
        }
    }
    if (isset($success_message)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => $success_message];
    } elseif (isset($error_message)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => $error_message];
    }

// Query untuk mengambil data pasien
$query = "SELECT * FROM pasien ORDER BY nama ASC";
$result = $mysqli->query($query);

if (!$result) {
    die("Error: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pasien | Dashboard</title>

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
            <h1 class="m-0">Tambah / Edit Pasien</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Pasien</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

<div class="container container-pasien">
    <button class="mb-3 btn btn-primary" onclick="showPasienForm('add');"><i class="bi bi-person-plus-fill"></i> Tambah Pasien</button>
    
<div class="card">
  <div class="card-body table-responsive">
    
    <table id="myTable" class="table table-striped" >
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama</th>
          <th scope="col">Alamat</th>
          <th scope="col">No. Ktp</th>
          <th scope="col">No. Hp</th>
          <th scope="col">Poli</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
        <tbody>
    <?php
    $start_number = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $start_number++ . "</td>";
        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
        echo "<td>" . htmlspecialchars($row['no_ktp']) . "</td>";
        echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
        echo "<td>" . htmlspecialchars($row['no_rm'] ?? '') . "</td>";
        echo "<td>
       <button class='btn btn-sm btn-success' onclick='showPasienForm(\"edit\", " . $row['id'] . ", \"" . addslashes($row['nama'] ?? '') . "\", \"" . addslashes($row['alamat'] ?? '') . "\", \"" . addslashes($row['no_hp'] ?? '') . "\", \"" . addslashes($row['no_ktp'] ?? '') . "\", \"" . addslashes($row['no_rm'] ?? '') . "\")'><i class='bi bi-pencil-square'></i>  Edit</button>
        <button class='btn btn-sm btn-danger' onclick='deletePasien(" . $row['id'] . ")'><i class='bi bi-trash-fill'></i> Hapus</button>
      </td>";
        echo "</tr>";
    }
    ?>
</tbody>
    </table>
</div>
</div>
<script>
function showPasienForm(action, id = null, nama = '', alamat = '', no_hp = '', no_ktp = '', no_rm = '') {
    const title = action === 'add' ? 'Tambah Data Pasien' : 'Edit Data Pasien';
    const buttonText = action === 'add' ? 'Simpan' : 'Update';

    if (action === 'edit') {
        const newUrl = `index.php?page=pasien&action=edit&id=${id}`;
        window.history.pushState({ id: id }, '', newUrl);
    }

    // Menampilkan SweetAlert dengan formulir
    Swal.fire({
        title: title,
        html: `
            <form id="pasienForm" method="POST">
                ${action === 'edit' ? `<input type="hidden" name="id" value="${id}">` : ''}
                <input type="text" id="nama" name="nama" class="swal2-input" value="${nama}" placeholder="Nama Lengkap" required>
                <input type="text" id="alamat" name="alamat" class="swal2-input" value="${alamat}" placeholder="Alamat" required>
                <input type="text" id="no_ktp" name="no_ktp" class="swal2-input" value="${no_ktp}" placeholder="No. KTP" required>
                <input type="text" id="no_hp" name="no_hp" class="swal2-input" value="${no_hp}" placeholder="No. HP" required>
                <?php
                {
                    $current_year_month = date('Ym');
                    $query_count = "SELECT COUNT(*) as total FROM pasien WHERE no_rm LIKE '$current_year_month%'";
                    $result_count = $mysqli->query($query_count);
                    $row_count = $result_count->fetch_assoc();
                    $total_pasien = $row_count['total'] + 1;
                    $no_rm = $current_year_month . '-' . str_pad($total_pasien, 3, '0', STR_PAD_LEFT);
                }
                ?>
                <input type="text" id="no_rm" name="no_rm" class="swal2-input" value="<?php echo $no_rm; ?>" placeholder="No. RM" readonly>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: buttonText,
        cancelButtonText: 'Batal',
        focusConfirm: false,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6B7280',
        preConfirm: () => {
            const form = document.getElementById('pasienForm');
            if (!form.checkValidity()) {
                Swal.showValidationMessage('Harap isi semua kolom!');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('pasienForm');
            form.action = `index.php?page=pasien&action=${action}`;
            form.submit();
        }
    });
}


    function deletePasien(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pasien akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#007bff',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?page=pasien&action=delete&id=" + id;
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