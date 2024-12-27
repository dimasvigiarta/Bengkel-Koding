<?php
// Menyertakan file koneksi database
include('config.php');

// Cek apakah ada NIM yang dikirimkan
if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    // Query untuk mendapatkan data mahasiswa berdasarkan NIM
    $sql = "SELECT * FROM mahasiswa WHERE nim = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();
        $mahasiswa = $result->fetch_assoc();
    }
}

// Cek apakah form disubmit untuk update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim_baru = $_POST['nim'];
    $nama = $_POST['nama'];
    $ipk = $_POST['ipk'];

    // Update data mahasiswa
    $sql = "UPDATE mahasiswa SET nim = ?, nama = ?, ipk = ? WHERE nim = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssds", $nim_baru, $nama, $ipk, $nim);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<div class='alert alert-success'>Data berhasil diperbarui.</div>";
        } else {
            echo "<div class='alert alert-warning'>Tidak ada perubahan pada data.</div>";
        }
        $stmt->close();
        // Redirect ke halaman input mahasiswa setelah update
        header("Location: input_mahasiswa.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Mahasiswa</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $mahasiswa['nim']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $mahasiswa['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="ipk">IPK:</label>
                <input type="number" step="0.01" min="0" max="4" class="form-control" id="ipk" name="ipk" value="<?php echo $mahasiswa['ipk']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="input_mahasiswa.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
