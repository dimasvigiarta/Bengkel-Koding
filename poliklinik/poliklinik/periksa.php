<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("koneksi.php");
if (!isset($_SESSION['username'])) {
    header("Location: LoginUser.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, 
    initial-scale=1.0">

    <!-- Bootstrap offline -->

    <link rel="stylesheet" href="assets/css/bootstrap.css"> 

    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">   

</head>
<body>
<div class="container">
<hr>    

<form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
    <?php
    $id_pasien = '';
    $nama_dokter = '';
    $tgl_periksa = '';
    $catatan = '';
    $nama_obat ='';
    
    if (isset($_GET['id'])) {
        $ambil = mysqli_query($mysqli, "SELECT * FROM periksa 
        WHERE periksa.id='" . $_GET['id'] . "'");
        
        while ($row = mysqli_fetch_array($ambil)) {
            $id_pasien = $row['id_pasien'];
            $id_dokter = $row['id_dokter'];
            $tgl_periksa = $row['tgl_periksa'];
            $catatan = $row['catatan'];
            $id_obat = $row['id_obat'];
        }
    ?>
    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
    <?php
    }
    ?>

    <div class="form-group mx-sm-3 mb-2">
        <label for="inputPasien" class="form-label fw-bold">
            Pasien
        </label>
        <select class="form-control" name="id_pasien">
            <?php
            $selected = '';
            $pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
            while ($data = mysqli_fetch_array($pasien)) {
                if ($data['id'] == $id_pasien) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
            ?>
                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>

    <div class="form-group mx-sm-3 mb-2">
        <label for="inputDokter" class="form-label fw-bold">
            Dokter
        </label>
        <select class="form-control" name="id_dokter">
            <?php
            $selected = '';
            $dokter = mysqli_query($mysqli, "SELECT * FROM dokter");
            while ($data = mysqli_fetch_array($dokter)) {
                if ($data['id'] == $id_dokter) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
            ?>
                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputTgl_periksa" class="form-label fw-bold">
            Tanggal Periksa
        </label>
        <input type="datetime-local" class="form-control" name="tgl_periksa" id="inputTgl_periksa" placeholder="tgl_periksa" value="<?php echo date('Y-m-d\TH:i', strtotime($tgl_periksa)); ?>">
    </div>

    <div class="form-group mx-sm-3 mb-2">
        <label for="inputCatatan" class="form-label fw-bold">
            Catatan
        </label>
        <textarea class="form-control" name="catatan" id="inputCatatan" placeholder="Catatan"><?php echo $catatan; ?></textarea>
    </div>
    <!-- Modify the "Obat" field -->
    <!-- Replace the existing "Obat" form group with checkboxes -->
    <!-- Update the "Obat" form group with checkboxes -->
    <div class="form-group mx-sm-3 mb-2">
    <label for="inputObat" class="form-label fw-bold">
        Obat
    </label>
    <div class="checkbox-options">
        <?php
        $obat_query = mysqli_query($mysqli, "SELECT * FROM obat");
        $counter = 1;
        while ($obat_data = mysqli_fetch_array($obat_query)) {
            $checked = isset($_POST['obat']) && in_array($obat_data['id'], $_POST['obat']) ? 'checked' : '';
        ?>
            <label class="checkbox-label">
                <input type="checkbox" name="obat[]" value="<?php echo $obat_data['id'] ?>" <?php echo isset($checked) ? $checked : ''; ?>>
                <?php echo $counter++ . ' - ' . $obat_data['nama_obat'] . ' - ' . $obat_data['kemasan'] . ' - Rp ' . number_format($obat_data['harga'], 0, ',', '.') ?>
            </label><br>
        <?php
        }
        ?>
    </div>
</div>





<!-- JavaScript to initialize Select2 -->
<script>
    $(document).ready(function () {
        $(".js-example-basic-multiple").select2();
    });
</script>

    <div class="form-group">
        <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
    </div>

</form>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Pasien</th>
            <th scope="col">Dokter</th>
            <th scope="col">Tanggal Periksa</th>
            <th scope="col">Catatan</th>
            <th scope="col">Id_Obat</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    
    <tbody>
        <?php
        $result = mysqli_query(
            $mysqli, "SELECT pr.*, d.nama as 'nama_dokter', p.nama as 'nama_pasien', o.nama_obat, o.kemasan, o.harga
            FROM periksa pr
            LEFT JOIN dokter d ON (pr.id_dokter = d.id)
            LEFT JOIN pasien p ON (pr.id_pasien = p.id)
            LEFT JOIN obat o ON (pr.id_obat = o.id) -- Add this line to join the obat table
            ORDER BY pr.tgl_periksa DESC;
            "
        );
        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <th scope="row"><?php echo $no++ ?></th>
                <td><?php echo $data['nama_pasien'] ?></td>
                <td><?php echo $data['nama_dokter'] ?></td>
                <td><?php echo $data['tgl_periksa'] ?></td>
                <td><?php echo $data['catatan'] ?></td>
                <td><?php echo $data['id_obat'] ?></td>
                <td>
                    <a class="btn btn-success rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>">Ubah</a>
                    <a class="btn btn-danger rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                    <a class="btn btn-primary rounded-pill px-3" href="nota.php?page=periksa&id_invoice=<?php echo $data['id'] ?>">Nota</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
if (isset($_POST['simpan'])) {
    // ... existing code ...

    // Handle multiple medications selected via checkboxes
    $selected_obat = isset($_POST['obat']) ? implode(',', $_POST['obat']) : '';
    if (isset($_POST['id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE periksa SET 
                                        id_pasien = '" . $_POST['id_pasien'] . "',
                                        id_dokter = '" . $_POST['id_dokter'] . "',
                                        tgl_periksa = '" . $_POST['tgl_periksa'] . "',
                                        catatan = '" . $_POST['catatan'] . "',
                                        id_obat = '" . $selected_obat . "'
                                        WHERE
                                        id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO periksa(id_pasien, id_dokter, tgl_periksa, catatan, id_obat) 
                                        VALUES ( 
                                            '" . $_POST['id_pasien'] . "',
                                            '" . $_POST['id_dokter'] . "',
                                            '" . $_POST['tgl_periksa'] . "',
                                            '" . $_POST['catatan'] . "',
                                            '" . $selected_obat . "'
                                            )");
    }

    // ... existing code ...


    echo "<script> 
            document.location='index.php?page=periksa';
            </script>";
}

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM periksa WHERE id = '" . $_GET['id'] . "'");
    }
    echo "<script> 
            document.location='index.php?page=periksa';
            </script>";
}

?>
<?php
if (isset($_GET['id_invoice'])) {
    $periksaId = $_GET['id_invoice'];
    $periksaInfo = getPeriksaInfo($periksaId);
    $selectedObats = explode(",", $periksaInfo['id_obat']);
    $selectedObatsInfo = getSelectedObatsInfo($selectedObats);
    $jasaDokter = 150000; // Default jasa dokter

    // Calculate total invoice
    $totalInvoice = $jasaDokter;
    foreach ($selectedObatsInfo as $obat) {
        $totalInvoice += $obat['harga'];
    }

    // Display Invoice
    include_once("nota.php");
} else {
    // Display Periksa Form
    // ... (Your existing periksa.php code)
}
?>
</body>
</html>