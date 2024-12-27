<?php
// Menyertakan file koneksi database
include('config.php');

// Cek apakah ada NIM yang dikirimkan
if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    // Query untuk menghapus data mahasiswa berdasarkan NIM
    $sql = "DELETE FROM mahasiswa WHERE nim = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nim);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<div class='alert alert-success'>Data berhasil dihapus.</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menghapus data.</div>";
        }
        $stmt->close();
        // Redirect ke halaman input mahasiswa setelah penghapusan
        header("Location: input_mahasiswa.php");
        exit();
    }
}
?>
