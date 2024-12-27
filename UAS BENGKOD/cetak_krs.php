<?php
require 'vendor/autoload.php'; // Library MPDF untuk PDF

$mpdf = new \Mpdf\Mpdf();
$mahasiswa_id = $_GET['mahasiswa_id'];

// Ambil data mahasiswa
$mahasiswa = $conn->query("SELECT * FROM mahasiswa WHERE id = $mahasiswa_id")->fetch_assoc();

// Ambil mata kuliah
$result = $conn->query("SELECT nama_matkul FROM krs 
                        JOIN mata_kuliah ON krs.mata_kuliah_id = mata_kuliah.id 
                        WHERE krs.mahasiswa_id = $mahasiswa_id");
$matkul_list = '';
while ($row = $result->fetch_assoc()) {
    $matkul_list .= "<li>{$row['nama_matkul']}</li>";
}

$html = "
<h1>Kartu Rencana Studi</h1>
<p>Nama: {$mahasiswa['nama']}</p>
<p>NIM: {$mahasiswa['nim']}</p>
<p>IPK: {$mahasiswa['ipk']}</p>
<h3>Mata Kuliah:</h3>
<ul>$matkul_list</ul>
";

$mpdf->WriteHTML($html);
$mpdf->Output();
?>
