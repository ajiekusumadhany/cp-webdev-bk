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


//hapus data obat
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $delete_query = "DELETE FROM obat WHERE id = ?";
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
    
    header('Location: index.php?page=obat');
    exit;
}

// Cek apakah ada ID yang dikirimkan melalui GET untuk edit
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Ambil ID dari GET

    // Query untuk mengambil data obat berdasarkan ID
    $ambil = mysqli_query($mysqli, "SELECT * FROM obat WHERE id='" . $id . "'");

    // Cek apakah query berhasil
    if ($ambil) {
        $data = mysqli_fetch_assoc($ambil);
        $nama_obat = $data['nama_obat'];
        $kemasan = $data['kemasan'];
        $harga = $data['harga'];
    } else {
        echo "Error: " . mysqli_error($mysqli); 
    }
}

// Proses penyimpanan atau update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST
    $id = $_POST['id'] ?? null;
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];

    // Validasi data
    if (empty($nama_obat) || empty($kemasan) || empty($harga)) {
        $error_message = 'Harap isi semua kolom!';
    } else {
        // Jika ID ada, lakukan update, jika tidak, lakukan insert
        if ($id) {
            // Proses update data ke database
            $sql = "UPDATE obat SET nama_obat = ?, kemasan = ?, harga = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssii", $nama_obat, $kemasan, $harga, $id);
                $result = $stmt->execute();
                $success_message = $result ? 'Data obat berhasil diperbarui' : 'Gagal memperbarui data obat: ' . $mysqli->error;
            } else {
                $error_message = 'Gagal menyiapkan query';
            }
        } else {
            // Proses insert data ke database
            $sql = "INSERT INTO obat (nama_obat, kemasan, harga) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssi", $nama_obat, $kemasan, $harga);
                $result = $stmt->execute();
                $success_message = $result ? 'Data obat berhasil disimpan' : 'Gagal menyimpan data obat: ' . $mysqli->error;
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
    header('Location: index.php?page=obat');
    exit;
}

// Query untuk mengambil data obat
$query = "SELECT * FROM obat ORDER BY nama_obat ASC";
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
  <title>Obat | Dashboard</title>

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
            <h1 class="m-0">Tambah / Edit Obat</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Obat</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

<div class="container container-obat">
    <button class="mb-3 btn btn-primary" onclick="showObatForm('add');"><i class="bi bi-person-plus-fill"></i> Tambah Obat</button>
    
    <div class="card-body table-responsive">
    <table id="myTable" class="table table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Obat</th>
          <th scope="col">Kemasan</th>
          <th scope="col">Harga</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
        <tbody>
    <?php
    $start_number = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $start_number++ . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_obat']) . "</td>";
        echo "<td>" . htmlspecialchars($row['kemasan']) . "</td>";
        echo "<td>Rp. " . htmlspecialchars($row['harga']) . "</td>";
        echo "<td>
       <button class='btn btn-sm btn-success' onclick='showObatForm(\"edit\", " . $row['id'] . ", \"" . addslashes($row['nama_obat'] ?? '') . "\", \"" . addslashes($row['kemasan'] ?? '') . "\", \"" . addslashes($row['harga'] ?? '') . "\")'><i class='bi bi-pencil-square'></i>  Edit</button>
        <button class='btn btn-sm btn-danger' onclick='deleteObat(" . $row['id'] . ")'><i class='bi bi-trash-fill'></i> Hapus</button>
      </td>";
        echo "</tr>";
    }
    ?>
</tbody>
    </table>
</div>
</div>
<script>
function showObatForm(action, id = null, nama_obat = '', kemasan = '', harga = '') {
    const title = action === 'add' ? 'Tambah Data Obat' : 'Edit Data Obat';
    const buttonText = action === 'add' ? 'Simpan' : 'Update';

    if (action === 'edit') {
        const newUrl = `index.php?page=obat&action=edit&id=${id}`;
        window.history.pushState({ id: id }, '', newUrl);
    }

    // Menampilkan SweetAlert dengan formulir
    Swal.fire({
        title: title,
        html: `
            <form id="obatForm" method="POST">
                ${action === 'edit' ? `<input type="hidden" name="id" value="${id}">` : ''}
                <input type="text" id="nama_obat" name="nama_obat" class="swal2-input" value="${nama_obat}" placeholder="Nama Obat" required>
                <input type="text" id="kemasan" name="kemasan" class="swal2-input" value="${kemasan}" placeholder="Kemasan" required>
                <input type="text" id="harga" name="harga" class="swal2-input" value="${harga}" placeholder="Harga" required>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: buttonText,
        cancelButtonText: 'Batal',
        focusConfirm: false,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6B7280',
        preConfirm: () => {
            const form = document.getElementById('obatForm');
            if (!form.checkValidity()) {
                Swal.showValidationMessage('Harap isi semua kolom!');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('obatForm');
            form.action = `index.php?page=obat&action=${action}`;
            form.submit();
        }
    });
}


    function deleteObat(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data obat akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#007bff',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?page=obat&action=delete&id=" + id;
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