<!-- nota.php -->
<?php
// Include the file containing the functions
include_once("koneksi.php");

// Fetch information for the invoice
$periksaId = $_GET['id_invoice'];
$periksaInfo = getPeriksaInfo($periksaId);
$selectedObats = explode(",", $periksaInfo['id_obat']);
$selectedObatsInfo = getSelectedObatsInfo($selectedObats);
$jasaDokter = 150000; // Default jasa dokter

// Fetch patient information
$patientId = $periksaInfo['id_pasien'];
$patientInfo = getPatientInfo($patientId);

// Fetch doctor information
$doctorId = $periksaInfo['id_dokter'];
$doctorInfo = getDoctorInfo($doctorId);

// Calculate total invoice
$totalInvoice = $jasaDokter;
foreach ($selectedObatsInfo as $obat) {
    $totalInvoice += $obat['harga'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota</title>
    <!-- Include Bootstrap CSS -->
    <!-- Bootstrap Offline -->
    <link rel="stylesheet" href="assets/css/bootstrap.css"> 
    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
       body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: black; /* Mengganti warna teks menjadi hitam */
        }

        p {
            margin-bottom: 8px;
            color: black; /* Mengganti warna teks menjadi hitam */
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 4px;
            color: black; /* Mengganti warna teks menjadi hitam */
        }

        strong {
            color: black; /* Mengganti warna teks menjadi hitam */
        }
    </style>
</head>
</head>
<body>
    <div class="container">
        <h2>Nota Pembayaran</h2>

        <!-- Display invoice details -->
        <p><strong>1. Nomor Periksa:</strong> <?php echo isset($periksaInfo['id']) ? $periksaInfo['id'] : ''; ?></p>
        <p><strong>2. Tanggal Periksa:</strong> <?php echo isset($periksaInfo['tgl_periksa']) ? $periksaInfo['tgl_periksa'] : ''; ?></p>

        <!-- Patient Information -->
        <p><strong>3. Pasien:</strong></h4>
        <p>Nama Pasien: <?php echo $patientInfo['nama']; ?></p>
        <p>Alamat: <?php echo $patientInfo['alamat']; ?></p>
        <p>Nomer HP: <?php echo $patientInfo['no_hp']; ?></p>

        <!-- Doctor Information -->
        <p><strong>5. Dokter:</strong></p>
        <p>Nama Dokter: <?php echo $doctorInfo['nama']; ?></p>
        <p>Alamat: <?php echo $doctorInfo['alamat']; ?></p>
        <p>Nomer HP: <?php echo $doctorInfo['no_hp']; ?></p>

        <p><strong>5. Jasa Dokter:</strong> Rp <?php echo number_format($jasaDokter, 0, ',', '.'); ?></p>

        <p><strong>6. Daftar Obat:</strong></p>
        <ul>
            <?php foreach ($selectedObatsInfo as $obat): ?>
                <li><?php echo $obat['nama_obat'] . ' - ' . $obat['kemasan'] . ' - Rp ' . number_format($obat['harga'], 0, ',', '.'); ?></li>
            <?php endforeach; ?>
        </ul>

        <p><strong>7. Total Harga:</strong> Rp <?php echo number_format($totalInvoice, 0, ',', '.'); ?></p>

        <!-- Include Bootstrap JS -->
        <!-- jQuery and Bootstrap JS (Online) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-yQfxKTAz0JdEjzW5jJeXVEJfZ7pIxgsrxCKfVcZzYUAfxPm8zp+Qu3t2k5t8lWVg" crossorigin="anonymous"></script>
    </div>
</body>
</html>
