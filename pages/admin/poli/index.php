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


//hapus data poli
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $delete_query = "DELETE FROM poli WHERE id = ?";
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
    
    header('Location: index.php?page=poli');
    exit;
}

// Cek apakah ada ID yang dikirimkan melalui GET untuk edit
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Ambil ID dari GET

    // Query untuk mengambil data poli berdasarkan ID
    $ambil = mysqli_query($mysqli, "SELECT * FROM poli WHERE id='" . $id . "'");

    // Cek apakah query berhasil
    if ($ambil) {
        $data = mysqli_fetch_assoc($ambil);
        $nama_poli = $data['nama_poli'];
        $keterangan = $data['keterangan'];
    } else {
        echo "Error: " . mysqli_error($mysqli); 
    }
}

// Proses penyimpanan atau update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST
    $id = $_POST['id'] ?? null;
    $nama_poli = $_POST['nama_poli'];
    $keterangan = $_POST['keterangan'];

    // Validasi data
    if (empty($nama_poli) || empty($keterangan)) {
        $error_message = 'Harap isi semua kolom!';
    } else {
        // Jika ID ada, lakukan update, jika tidak, lakukan insert
        if ($id) {
            // Proses update data ke database
            $sql = "UPDATE poli SET nama_poli = ?, keterangan = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssi", $nama_poli, $keterangan, $id);
                $result = $stmt->execute();
                $success_message = $result ? 'Data poli berhasil diperbarui' : 'Gagal memperbarui data poli: ' . $mysqli->error;
            } else {
                $error_message = 'Gagal menyiapkan query';
            }
        } else {
            // Proses insert data ke database
            $sql = "INSERT INTO poli (nama_poli, keterangan) VALUES (?, ?)";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ss", $nama_poli, $keterangan);
                $result = $stmt->execute();
                $success_message = $result ? 'Data poli berhasil disimpan' : 'Gagal menyimpan data poli: ' . $mysqli->error;
            } else {
                $error_message = 'Gagal menyiapkan query';
            }
        }
    }
    if (isset($success_message)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => $success_message];
    } elseif (isset($error_message)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => $error_message];
    }
    header('Location: index.php?page=poli');
    exit;
}

// Query untuk mengambil data poli
$query = "SELECT * FROM poli ORDER BY nama_poli ASC";
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
  <title>Poli | Dashboard</title>

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
            <h1 class="m-0">Tambah / Edit Poli</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Poli</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

<div class="container container-poli">
    <button class="mb-3 btn btn-primary" onclick="showPoliForm('add');"><i class="bi bi-person-plus-fill"></i> Tambah Poli</button>
    
    <div class="card-body table-responsive">
    <table id="example1" class="table table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Poli</th>
          <th scope="col">Keterangan</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
        <tbody>
    <?php
    $start_number = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $start_number++ . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_poli']) . "</td>";
        echo "<td>" . htmlspecialchars($row['keterangan']) . "</td>";
        echo "<td>
       <button class='btn btn-sm btn-success' onclick='showPoliForm(\"edit\", " . $row['id'] . ", \"" . addslashes($row['nama_poli'] ?? '') . "\", \"" . addslashes($row['keterangan'] ?? '') . "\")'><i class='bi bi-pencil-square'></i>  Edit</button>
        <button class='btn btn-sm btn-danger' onclick='deletePoli(" . $row['id'] . ")'><i class='bi bi-trash-fill'></i> Hapus</button>
      </td>";
        echo "</tr>";
    }
    ?>
</tbody>
    </table>
</div>
</div>
<script>
function showPoliForm(action, id = null, nama_poli = '', keterangan = '') {
    const title = action === 'add' ? 'Tambah Data Poli' : 'Edit Data Poli';
    const buttonText = action === 'add' ? 'Simpan' : 'Update';

    if (action === 'edit') {
        const newUrl = `index.php?page=poli&action=edit&id=${id}`;
        window.history.pushState({ id: id }, '', newUrl);
    }

    // Menampilkan SweetAlert dengan formulir
    Swal.fire({
        title: title,
        html: `
            <form id="poliForm" method="POST">
                ${action === 'edit' ? `<input type="hidden" name="id" value="${id}">` : ''}
                <input type="text" id="nama_poli" name="nama_poli" class="swal2-input" value="${nama_poli}" placeholder="Nama Poli" required>
                <input type="text" id="keterangan" name="keterangan" class="swal2-input" value="${keterangan}" placeholder="Keterangan" required>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: buttonText,
        cancelButtonText: 'Batal',
        focusConfirm: false,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6B7280',
        preConfirm: () => {
            const form = document.getElementById('poliForm');
            if (!form.checkValidity()) {
                Swal.showValidationMessage('Harap isi semua kolom!');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('poliForm');
            form.action = `index.php?page=poli&action=${action}`;
            form.submit();
        }
    });
}


    function deletePoli(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data poli akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#007bff',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?page=poli&action=delete&id=" + id;
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