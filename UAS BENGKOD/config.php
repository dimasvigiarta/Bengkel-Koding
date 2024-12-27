<?php
$host = 'localhost';  // Nama host
$user = 'root';       // Username MySQL Anda
$password = '';       // Password MySQL Anda (kosong jika default)
$dbname = 'bengkel_koding'; // Nama database yang telah Anda buat

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
