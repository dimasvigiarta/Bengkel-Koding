<?php
// Menyertakan file koneksi database
include('config.php');

// Cek apakah ada NIM yang dikirimkan dalam URL
if (isset($_GET['nim'])) {
    $nim = $_GET['nim']; // Ambil NIM dari URL

    // Query untuk mendapatkan data mahasiswa berdasarkan NIM
    $sql = "SELECT * FROM mahasiswa WHERE nim = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nim); // Bind parameter NIM
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah data mahasiswa ditemukan
        if ($result->num_rows > 0) {
            $mahasiswa = $result->fetch_assoc();  // Ambil data mahasiswa
        } else {
            echo "<div class='alert alert-danger'>Mahasiswa dengan NIM $nim tidak ditemukan.</div>";
            exit;  // Stop eksekusi jika mahasiswa tidak ditemukan
        }
    }
} else {
    echo "<div class='alert alert-danger'>NIM tidak ditemukan dalam URL.</div>";
    exit;  // Stop eksekusi jika NIM tidak ada dalam URL
}

// Ambil daftar mata kuliah
$mata_kuliah_result = $conn->query("SELECT id, nama_mata_kuliah FROM mata_kuliah");

// Cek apakah form disubmit untuk input KRS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahasiswa_id = $_POST['mahasiswa_id'];  // ID mahasiswa
    $mata_kuliah_id = $_POST['mata_kuliah_id']; // ID mata kuliah yang dipilih

    // Pastikan data tidak kosong
    if (!empty($mahasiswa_id) && !empty($mata_kuliah_id)) {
        // Cek apakah mahasiswa sudah terdaftar di KRS untuk mata kuliah ini
        $check_sql = "SELECT * FROM krs WHERE mahasiswa_id = ? AND mata_kuliah_id = ?";
        if ($check_stmt = $conn->prepare($check_sql)) {
            $check_stmt->bind_param("ii", $mahasiswa_id, $mata_kuliah_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            // Jika belum ada data untuk mahasiswa ini dan mata kuliah ini
            if ($check_result->num_rows == 0) {
                // Generate kelompok dan ruangan jika belum ada
                $kelompok = 'A11' . rand(100, 999);
                $ruangan = 'H' . rand(1, 6) . rand(0, 9);

                // Query untuk memasukkan data ke tabel krs
                $sql = "INSERT INTO krs (mahasiswa_id, mata_kuliah_id, kelompok, ruangan) VALUES (?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("iiss", $mahasiswa_id, $mata_kuliah_id, $kelompok, $ruangan); // "iiss" : integer, integer, string, string
                    $stmt->execute();

                    // Cek apakah data berhasil dimasukkan
                    if ($stmt->affected_rows > 0) {
                        echo "<div class='alert alert-success'>Data KRS berhasil dimasukkan.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Gagal memasukkan data KRS.</div>";
                    }
                    $stmt->close();
                }
            } else {
                echo "<div class='alert alert-warning'>Mata kuliah sudah terdaftar untuk mahasiswa ini.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-warning'>Semua field harus diisi.</div>";
    }
}

// Query untuk mendapatkan data KRS mahasiswa berdasarkan NIM
$sql_krs = "SELECT krs.id as krs_id, mata_kuliah.nama_mata_kuliah, mata_kuliah.sks, krs.kelompok, krs.ruangan
            FROM krs
            JOIN mata_kuliah ON krs.mata_kuliah_id = mata_kuliah.id
            JOIN mahasiswa ON krs.mahasiswa_id = mahasiswa.id
            WHERE mahasiswa.nim = ?";
$data_krs = [];
if ($stmt_krs = $conn->prepare($sql_krs)) {
    $stmt_krs->bind_param("s", $nim);
    $stmt_krs->execute();
    $result_krs = $stmt_krs->get_result();

    while ($row = $result_krs->fetch_assoc()) {
        // Jika kelompok dan ruangan kosong, generate nilai baru
        if (empty($row['kelompok'])) {
            $row['kelompok'] = 'A11' . rand(100, 999); // Generate kelompok dengan awalan A11
        }
        if (empty($row['ruangan'])) {
            $row['ruangan'] = 'H' . rand(1, 6) . rand(0, 9); // Generate ruangan dengan awalan H
        }
        $data_krs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input KRS</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="input_mahasiswa.php">Input Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="input_mata_kuliah.php">Input Mata Kuliah</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="input_krs.php">Input KRS</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Input KRS untuk Mahasiswa: <?php echo isset($mahasiswa) ? $mahasiswa['nama'] : ''; ?></h2>

        <!-- Form Input KRS -->
        <form method="POST" action="input_krs.php?nim=<?= $nim ?>">
            <!-- ID Mahasiswa yang tersembunyi untuk dikirim ke database -->
            <input type="hidden" name="mahasiswa_id" value="<?php echo isset($mahasiswa) ? $mahasiswa['id'] : ''; ?>" />

            <div class="form-group">
                <label for="mata_kuliah_id">Pilih Mata Kuliah:</label>
                <select class="form-control" id="mata_kuliah_id" name="mata_kuliah_id" required>
                    <option value="">Pilih Mata Kuliah</option>
                    <?php while ($mata_kuliah = $mata_kuliah_result->fetch_assoc()) : ?>
                        <option value="<?= $mata_kuliah['id'] ?>"><?= $mata_kuliah['nama_mata_kuliah'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah KRS</button>
        </form>

        <!-- Tabel KRS -->
        <div class="mt-5">
            <h3>Daftar Mata Kuliah yang Diambil</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Kelompok</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data_krs)): ?>
                        <?php foreach ($data_krs as $index => $krs): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $krs['nama_mata_kuliah'] ?></td>
                                <td><?= $krs['sks'] ?></td>
                                <td><?= $krs['kelompok'] ?></td>
                                <td><?= $krs['ruangan'] ?></td>
                                <td>
                                    <a href="hapus_krs.php?id=<?= $krs['krs_id'] ?>&nim=<?= $nim ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada mata kuliah yang diambil.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
