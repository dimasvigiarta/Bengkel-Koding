<?php
// Menyertakan file koneksi database
include('config.php');

// Ambil data mahasiswa dari database
$mahasiswa_result = $conn->query("SELECT * FROM mahasiswa");

// Hapus mahasiswa jika tombol hapus ditekan
if (isset($_GET['hapus_id'])) {
    $hapus_id = $_GET['hapus_id'];
    $delete_query = "DELETE FROM mahasiswa WHERE id = ?";
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $hapus_id);  // "i" untuk integer
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "<div class='alert alert-success'>Mahasiswa berhasil dihapus.</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menghapus mahasiswa.</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="input_mata_kuliah.php">Input Mata Kuliah</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="daftar_mahasiswa.php">Daftar Mahasiswa</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Konten utama halaman -->
    <div class="container mt-4">
        <h2>Daftar Mahasiswa</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>IPK</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $mahasiswa_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['nim'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['ipk'] ?></td>
                        <td><?= $row['sks'] ?></td>
                        <td>
                            <!-- Tombol Hapus -->
                            <a href="daftar_mahasiswa.php?hapus_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')">Hapus</a>
                            <!-- Tombol Lihat (untuk melihat detail, bisa dikembangkan lebih lanjut) -->
                            <a href="lihat_mahasiswa.php?nim=<?= $row['nim'] ?>" class="btn btn-info">Lihat</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy9X0d0t5vpiFgI2XYGAT4SVoDLeJXrQUsz3v8Q4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-5ZfPgtuPrvP10F3hoh3uDK9QG1o0oAtpDz63jcK9BqFVzB0J0+MjJ2hz8yyI4sdM" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0YyFz6F5tq7mOyoATKqvq4CUv4sDkXsS7pjo1rO7v6BfwEhz" crossorigin="anonymous"></script>

</body>
</html>
