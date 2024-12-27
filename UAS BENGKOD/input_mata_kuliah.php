<?php
// Menyertakan file koneksi database
include('config.php');

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mata_kuliah = trim($_POST['nama_mata_kuliah']);  // Ambil nilai nama mata kuliah dari form
    $sks = $_POST['sks'];    // Ambil nilai SKS dari form

    // Pastikan semua data terisi
    if (!empty($nama_mata_kuliah) && !empty($sks)) {
        $sql = "INSERT INTO mata_kuliah (nama_mata_kuliah, sks) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $nama_mata_kuliah, $sks);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                echo "<div class='alert alert-success'>Data mata kuliah berhasil dimasukkan.</div>";
            } else {
                echo "<div class='alert alert-danger'>Gagal memasukkan data.</div>";
            }
            $stmt->close();
        }
    } else {
        echo "<div class='alert alert-warning'>Nama mata kuliah dan SKS harus diisi.</div>";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Mata Kuliah</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Bengkel Koding</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="input_mahasiswa.php">Input Mahasiswa</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="input_mata_kuliah.php">Input Mata Kuliah</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Konten utama halaman -->
    <div class="container mt-4">
        <h2>Input Mata Kuliah</h2>
        <form method="POST" action="input_mata_kuliah.php">
    <div class="form-group">
        <label for="nama_mata_kuliah">Nama Mata Kuliah:</label>
        <input type="text" class="form-control" id="nama_mata_kuliah" name="nama_mata_kuliah" required>
    </div>
    <div class="form-group">
        <label for="sks">SKS:</label>
        <input type="number" class="form-control" id="sks" name="sks" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

    </div>

    <!-- JS Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy9X0d0t5vpiFgI2XYGAT4SVoDLeJXrQUsz3v8Q4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-5ZfPgtuPrvP10F3hoh3uDK9QG1o0oAtpDz63jcK9BqFVzB0J0+MjJ2hz8yyI4sdM" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0YyFz6F5tq7mOyoATKqvq4CUv4sDkXsS7pjo1rO7v6BfwEhz" crossorigin="anonymous"></script>

</body>
</html>
