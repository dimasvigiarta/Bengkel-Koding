<?php
//melakukan Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mahasiswa";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//untuk menambahkan kegiatan baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $isi = $_POST['isi'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    //set status 0 sebagai (Belum)
    $status = 0;

    $sql = "INSERT INTO mahasiswa (isi, tgl_awal, tgl_akhir, status) 
            VALUES ('$isi', '$tgl_awal', '$tgl_akhir', '$status')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

//untuk mengubah kegiatan
$row = null; //inisialisasi
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
}

//untuk update kegiatan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $id = $_POST['id'];
    $isi = $_POST['isi'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];

    $sql = "UPDATE mahasiswa SET isi='$isi', tgl_awal='$tgl_awal', tgl_akhir='$tgl_akhir' WHERE id=$id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

//ubah status kegiatan
if (isset($_GET['action']) && $_GET['action'] == 'update_status') {
    $id = $_GET['id'];

    //status saat ini
    $result = mysqli_query($conn, "SELECT status FROM mahasiswa WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    $current_status = $row['status'];

    //ubah status 
    $new_status = $current_status == 1 ? 0 : 1;

    $sql = "UPDATE mahasiswa SET status=$new_status WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

//untuk menghapus kegiatan
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];

    $sql = "DELETE FROM mahasiswa WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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

        <!--Form Untuk Menambah atau Edit Kegiatan-->
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

        <!--Tabel To-Do List-->
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
                //menampilkan semua data kegiatan
                $result = mysqli_query($conn, "SELECT * FROM mahasiswa");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['isi']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tgl_awal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tgl_akhir']) . "</td>";
                    echo "<td>";
                    //untuk mengubah status
                    echo "<a href='index.php?action=update_status&id=" . $row['id'] . "' class='btn " . ($row['status'] == 1 ? 'btn-success' : 'btn-warning') . " btn-sm'>" . ($row['status'] == 1 ? 'Selesai' : 'Belum') . "</a>";
                    echo "</td>";
                    echo "<td>
                            <a href='index.php?action=edit&id=" . $row['id'] . "' class='btn btn-info btn-sm'>Ubah</a>
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
