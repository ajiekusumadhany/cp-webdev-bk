<?php 
$databaseHost = 'localhost';
$databaseName = 'db_klinik';
$databaseUsername = 'root';
$databasePassword = '';

$mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$mysqli) {
    die("Koneksi gagal: " . mysqli_connect_error());
}