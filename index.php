<?php
// Langkah pertama melakukan koneksi ke database phpMyAdmin
$servername = "localhost"; // Nama server database
$username = "root"; // Username untuk koneksi
$password = ""; // Password untuk koneksi
$dbname = "mahasiswa"; // Nama database yang akan digunakan

// Menghubungkan ke database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // Jika gagal, tampilkan pesan kesalahan
}

// Menambahkan kegiatan baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $isi = $_POST['isi']; // Mengambil data kegiatan dari input
    $tgl_awal = $_POST['tgl_awal']; // Mengambil tanggal awal
    $tgl_akhir = $_POST['tgl_akhir']; // Mengambil tanggal akhir
    $status = 0; // Set status 0 sebagai 'Belum'

    // Untuk menyimpan data kegiatan ke dattabase
    $sql = "INSERT INTO mahasiswa (isi, tgl_awal, tgl_akhir, status) 
            VALUES ('$isi', '$tgl_awal', '$tgl_akhir', '$status')";

    // Mengeksekusi query
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php"); // Jika berhasil, redirect ke index.php
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn); // Jika gagal, menampilkan pesan kesalahan
    }
}

// Untuk mengubah kegiatan
$row = null; // Inisiallisasi variabel
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = $_GET['id']; // Mengambil ID dari URL
    $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id"); // Mengambil data kegiatan berdasarkan ID
    $row = mysqli_fetch_assoc($result); // Menyimpan data kegiatan ke variabel $row
}

// Untuk memperbarui kegiatan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $id = $_POST['id']; // Mengambil ID kegiatan yang akan diperbarui
    $isi = $_POST['isi']; // Mengambil data kegiatan yang diperbarui
    $tgl_awal = $_POST['tgl_awal']; // Mengambil tanggal awal yang diperbarui
    $tgl_akhir = $_POST['tgl_akhir']; // Mengambil tanggal akhir yang diperbarui

    // Query untuk memperbarui data kegiatan
    $sql = "UPDATE mahasiswa SET isi='$isi', tgl_awal='$tgl_awal', tgl_akhir='$tgl_akhir' WHERE id=$id";
    
    // Eksekusi query
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php"); // Jika berhasil, redirect ke index.php
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn); // Jika gagal, tampilkan pesan kesalahan
    }
}

// Mengubah status kegiatan
if (isset($_GET['action']) && $_GET['action'] == 'update_status') {
    $id = $_GET['id']; // Mengambil ID dari URL

    // Mengambil status saat ini
    $result = mysqli_query($conn, "SELECT status FROM mahasiswa WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    $current_status = $row['status']; // Menyimpan status saat ini

    // Mengubah status (0 menjadi 1 atau sebaliknya)
    $new_status = $current_status == 1 ? 0 : 1;

    // Query untuk memperbarui status
    $sql = "UPDATE mahasiswa SET status=$new_status WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php"); // Jika berhasil, redirect ke index.php
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn); // Jika gagal, tampilkan pesan kesalahan
    }
}

// Menghapus kegiatan
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id']; // Mengambil ID dari URL

    // Query untuk menghapus kegiatan
    $sql = "DELETE FROM mahasiswa WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php"); // Jika berhasil, redirect ke index.php
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn); // Jika gagal, tampilkan pesan kesalahan
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>To-Do List</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <style>
        body {
            background-color: #f8f9fa; 
        }
        .container {
            background-color: #ffffff; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px; 
        }
        table th, table td {
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container mt-5"> 
        <h2 class="text-center">To-Do List</h2> 

        <form action="index.php" method="POST" class="mb-4">
            <input type="hidden" name="id" value="<?php echo isset($row) ? $row['id'] : ''; ?>">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <input type="text" name="isi" class="form-control" placeholder="Kegiatan" required value="<?php echo isset($row) ? htmlspecialchars($row['isi']) : ''; ?>"> 
                </div>
                <div class="col-md-3">
                    <input type="date" name="tgl_awal" class="form-control" required value="<?php echo isset($row) ? htmlspecialchars($row['tgl_awal']) : ''; ?>"> 

                </div>
                <div class="col-md-3">
                    <input type="date" name="tgl_akhir" class="form-control" required value="<?php echo isset($row) ? htmlspecialchars($row['tgl_akhir']) : ''; ?>"> 
                </div>
                <div class="col-md-2">
                    <button type="submit" name="<?php echo isset($row) ? 'update_task' : 'add_task'; ?>" class="btn btn-primary w-100"> 
                        <?php echo isset($row) ? 'Perbarui' : 'Simpan'; ?>
                    </button>
                </div>
            </div>
        </form>

        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Kegiatan</th>
                    <th>Tanggal Awal</th>
                    <th>Tanggal Akhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Menampilkan semua data kegiatan
                $result = mysqli_query($conn, "SELECT * FROM mahasiswa");
                while ($row = mysqli_fetch_assoc($result)) { // Mengambil setiap baris data kegiatan
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['isi']) . "</td>"; // Menampilkan kegiatan
                    echo "<td>" . htmlspecialchars($row['tgl_awal']) . "</td>"; // Menampilkan tanggal awal
                    echo "<td>" . htmlspecialchars($row['tgl_akhir']) . "</td>"; // Menampilkan tanggal akhir
                    echo "<td>";
                    // Untuk mengubah status
                    echo "<a href='index.php?action=update_status&id=" . $row['id'] . "' class='btn " . ($row['status'] == 1 ? 'btn-success' : 'btn-warning') . " btn-sm'>" . ($row['status'] == 1 ? 'Selesai' : 'Belum') . "</a>"; 
                    echo "</td>";
                    echo "<td>
                            <a href='index.php?action=edit&id=" . $row['id'] . "' class='btn btn-info btn-sm'>Edit</a> 
                            <a href='index.php?action=delete&id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Hapus</a> 
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
