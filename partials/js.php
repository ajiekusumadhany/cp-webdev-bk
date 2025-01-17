<?php
// Definisikan base URL
// Mendapatkan URL saat ini
$current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Memecah URL menjadi bagian-bagian
$url_parts = explode('/', $current_url);

// Mengambil nama folder proyek (elemen kedua dalam array)
$project_name = $url_parts[3]; // Indeks 3 adalah elemen keempat (0-indexed)

// Menghasilkan base URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $project_name . '/';
?>
<!-- jQuery -->
<script src="<?php echo $base_url; ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo $base_url; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $base_url; ?>assets/js/adminlte.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js
"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
   $(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>
