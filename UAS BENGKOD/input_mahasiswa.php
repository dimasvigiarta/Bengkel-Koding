<?php
// Menyertakan file koneksi database
include('config.php');

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $ipk = $_POST['ipk'];

    // Cek apakah NIM sudah ada di database
    $sql_check = "SELECT * FROM mahasiswa WHERE nim = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("s", $nim);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo "<div class='alert alert-danger'>NIM sudah terdaftar. Silakan coba NIM lain.</div>";
        } else {
            // Menghitung SKS berdasarkan IPK
            $sks = 0;
            if ($ipk >= 3.0) {
                $sks = 24;
            } elseif ($ipk >= 2.9) {
                $sks = 20;
            } elseif ($ipk >= 2.0) {
                $sks = 12;
            } else {
                $sks = 6;
            }

            // Pastikan semua data terisi
            if (!empty($nim) && !empty($nama) && !empty($ipk)) {
                // Query untuk memasukkan data ke tabel mahasiswa
                $sql = "INSERT INTO mahasiswa (nim, nama, ipk, sks) VALUES (?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssdi", $nim, $nama, $ipk, $sks);
                    $stmt->execute();

                    // Cek apakah data berhasil dimasukkan
                    if ($stmt->affected_rows > 0) {
                        echo "<div class='alert alert-success'>Data berhasil dimasukkan.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Gagal memasukkan data.</div>";
                    }
                    $stmt->close();
                }
            } else {
                echo "<div class='alert alert-warning'>Semua field harus diisi.</div>";
            }
        }
        $stmt_check->close();
    }
}

// Query untuk mengambil data dari tabel mahasiswa dan mata kuliah yang dipilih
$sql = "SELECT mahasiswa.nim, mahasiswa.nama, mahasiswa.ipk, mahasiswa.sks, 
        GROUP_CONCAT(mata_kuliah.nama_mata_kuliah SEPARATOR ', ') as mata_kuliah
        FROM mahasiswa
        LEFT JOIN krs ON mahasiswa.id = krs.mahasiswa_id
        LEFT JOIN mata_kuliah ON krs.mata_kuliah_id = mata_kuliah.id
        GROUP BY mahasiswa.nim";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Mahasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Bengkel Koding</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="input_mahasiswa.php">Input Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="input_mata_kuliah.php">Input Mata Kuliah</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Input Mahasiswa</h2>
        <form method="POST" action="input_mahasiswa.php">
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" class="form-control" id="nim" name="nim" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="ipk">IPK:</label>
                <input type="number" class="form-control" step="0.01" min="0" max="4" id="ipk" name="ipk" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

<!-- Tabel Mahasiswa -->
<h2 class="mt-5">Daftar Mahasiswa</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>IPK</th>
            <th>SKS</th>
            <th>Mata Kuliah</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output data per row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['nim'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['ipk'] . "</td>";
                echo "<td>" . $row['sks'] . "</td>";
                echo "<td>" . $row['mata_kuliah'] . "</td>";
                echo "<td>
                    <a href='input_krs.php?nim=" . $row['nim'] . "' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='hapus_mahasiswa.php?nim=" . $row['nim'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                    <a href='lihat_mahasiswa.php?nim=" . $row['nim'] . "' class='btn btn-info btn-sm'>Lihat</a>
                  </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>Tidak ada data mahasiswa.</td></tr>";
        }
        ?>
    </tbody>
</table>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
