<?php
include('config.php');

if (isset($_GET['id']) && isset($_GET['nim'])) {
    $id = $_GET['id'];
    $nim = $_GET['nim'];

    $sql = "DELETE FROM krs WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Mata kuliah berhasil dihapus.'); window.location='input_krs.php?nim=$nim';</script>";
        } else {
            echo "<script>alert('Gagal menghapus mata kuliah.'); window.location='input_krs.php?nim=$nim';</script>";
        }
        $stmt->close();
    }
} else {
    echo "<script>alert('ID tidak ditemukan.'); window.location='input_krs.php';</script>";
}
?>
