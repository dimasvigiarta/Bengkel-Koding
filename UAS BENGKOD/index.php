<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
            background-image: url('image/udinus.jpeg'); /* Gambar latar belakang */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
            opacity: 10; /* Menambahkan transparansi */
        }
        header {
            background: linear-gradient(90deg, #007bff, #6610f2);
            color: white;
            padding: 10px;
            text-align: center;
            width: 100%;
            opacity: 0.8;
        }
        header h1 {
            font-size: 2rem;
            font-weight: bold;
        }
        main {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .feature-card {
            border-radius: 10px;
            transition: transform 0.3s ease;
            width: 300px;
            opacity: 0.7;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 7px 0;
            width: 100%;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <header>
        <h1>KRS ONLINE</h1>
        <p>Kartu Rencana Studi Online Universitas Dian Nuswantoro</p>
    </header>

    <main>
        <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4">
            <div class="card feature-card shadow-sm p-3">
                <img src="image/mhs.png" class="card-img-top rounded-circle mx-auto mt-3" alt="Mahasiswa" style="width: 100px; height: 100px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Data Mahasiswa</h5>
                    <p class="card-text">Akses dan kelola informasi mahasiswa.</p>
                    <a href="daftar_mahasiswa.php" class="btn btn-primary">Mahasiswa</a>
                </div>
            </div>

            <div class="card feature-card shadow-sm p-3">
                <img src="image/buku.png" class="card-img-top rounded-circle mx-auto mt-3" alt="Mata Kuliah" style="width: 100px; height: 100px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Mata Kuliah</h5> 
                    <p class="card-text">Kelola jadwal dan informasi mata kuliah.</p>
                    <a href="input_mata_kuliah.php" class="btn btn-primary">Mata Kuliah</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; Dimas Indra Vigiarta 2024</p>
    </footer>
</body>
</html>
