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

    // Query untuk mendapatkan daftar mata kuliah yang diambil
    $sql_mk = "SELECT mk.nama_mata_kuliah, mk.sks
               FROM krs k
               INNER JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
               INNER JOIN mahasiswa m ON k.mahasiswa_id = m.id
               WHERE m.nim = ?";
    if ($stmt_mk = $conn->prepare($sql_mk)) {
        $stmt_mk->bind_param("s", $nim);
        $stmt_mk->execute();
        $mata_kuliah_result = $stmt_mk->get_result();
    }
} else {
    echo "<div class='alert alert-danger'>NIM tidak ditemukan.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Mahasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Detail Mahasiswa</h2>
        <table class="table table-bordered">
            <tr>
                <th>NIM</th>
                <td><?php echo $mahasiswa['nim']; ?></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><?php echo $mahasiswa['nama']; ?></td>
            </tr>
            <tr>
                <th>IPK</th>
                <td><?php echo $mahasiswa['ipk']; ?></td>
            </tr>
            <tr>
                <th>SKS</th>
                <td><?php echo $mahasiswa['sks']; ?></td>
            </tr>
        </table>

        <!-- Daftar Mata Kuliah -->
        <h4>Mata Kuliah yang Diambil</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($mata_kuliah_result->num_rows > 0) {
                    $no = 1;
                    while ($row = $mata_kuliah_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['nama_mata_kuliah'] . "</td>";
                        echo "<td>" . $row['sks'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>Belum ada mata kuliah yang diambil.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Tombol untuk mencetak PDF -->
        <button class="btn btn-success no-print" onclick="window.print()">Cetak sebagai PDF</button>
        <a href="input_mahasiswa.php" class="btn btn-secondary no-print">Kembali</a>
    </div>
</body>
</html>
