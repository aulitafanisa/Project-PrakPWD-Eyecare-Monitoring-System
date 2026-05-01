<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}
$id_user = $_SESSION['id'];
$durasi_detik = isset($_POST['durasi_detik']) ? (int)$_POST['durasi_detik'] : 0;
$durasi_menit = ceil($durasi_detik / 60); 
$jam    = floor($durasi_detik / 3600);
$menit  = floor(($durasi_detik % 3600) / 60);
$detik  = $durasi_detik % 60;
$format_waktu = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);

if ($durasi_detik > 2) {
    $query_update = "UPDATE screentime SET 
                     durasi_menit = $durasi_menit, 
                     status = 'selesai' 
                     WHERE id_user = $id_user AND status = 'berjalan'";
    mysqli_query($conn, $query_update);
} else {
    //ini kalo durasi dibawah 2 detik (in case kepencet) ga akan masuk database
    mysqli_query($conn, "DELETE FROM screentime WHERE id_user = $id_user AND status = 'berjalan'");
}

$query_user = mysqli_query($conn, "SELECT tgl_lahir FROM user WHERE id = $id_user");
$data_user = mysqli_fetch_assoc($query_user);
$tgl_lahir = new DateTime($data_user['tgl_lahir']);
$sekarang = new DateTime();
$umur = $sekarang->diff($tgl_lahir)->y;

//(referensi: Kompas Tekno & KMU)
$batas_aman = 120; 
$kategori_umur = "Dewasa";
$saran_kesehatan = "Gunakan metode 20-20-20: Tiap 20 menit, lihat benda sejauh 20 kaki selama 20 detik.";

if ($umur < 2) {
    $batas_aman = 5;
    $kategori_umur = "Balita (Di bawah 2 tahun)";
    $saran_kesehatan = "Sangat tidak disarankan terpapar layar selain untuk video call keluarga.";
} elseif ($umur >= 2 && $umur <= 5) {
    $batas_aman = 60;
    $kategori_umur = "Anak-anak (2-5 tahun)";
    $saran_kesehatan = "Maksimal 1 jam per hari. Pastikan konten bersifat edukatif.";
}
if ($durasi_menit <= $batas_aman) {
    $warna_status = "text-success";
    $pesan = "Kondisi Aman! Durasi Anda masih di bawah batas maksimal $kategori_umur.";
} else {
    $warna_status = "text-danger";
    $pesan = "Peringatan! Anda telah melebihi batas $batas_aman menit untuk $kategori_umur.";
    $saran_kesehatan = "<b>Mata Anda lelah!</b> Segera istirahatkan mata minimal 15 menit dan lihat objek hijau di luar ruangan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EyeCare - Hasil Analisis</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #eef4ff, #f8fbff);
}

.result-card {
    background: #ffffff;
    padding: 45px;
    border-radius: 25px;
    box-shadow: 0 15px 40px rgba(16, 54, 125, 0.15);
    margin-top: 60px;
    text-align: center;
    border: 2px solid #e3ecff;
    transition: 0.3s;
}

.duration-display {
    display: inline-block;
    padding: 18px 45px;
    border-radius: 50px;
    font-size: 2.7rem;
    font-weight: 700;
    color: #10367d;
    margin: 25px auto;
    background: #eaf4ff;
    border: 3px solid #74b4da;
    letter-spacing: 2px;
}

.text-success {
    color: #2ecc71 !important;
    font-weight: 600;
}

.text-danger {
    color: #e74c3c !important;
    font-weight: 600;
}

.alert-info {
    background-color: #eaf4ff;
    border: none;
    border-radius: 15px;
    color: #10367d;
}

.btn-primary {
    background-color: #10367d;
    border: none;
    border-radius: 12px;
    transition: 0.3s;
}

.btn-primary:hover {
    background-color: #74b4da;
    color: #10367d;
}

.btn-outline-secondary {
    border-radius: 12px;
}
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 result-card">
            <h2 class="mb-2">Hasil Monitoring Mata</h2>
            <p class="text-muted">Kategori: <?= $kategori_umur ?></p>
            <hr>
            
            <p class="mb-1">Total Durasi Penggunaan Layar:</p>
            <div class="duration-display">
                <?= $format_waktu ?>
            </div>

            <h3 class="<?= $warna_status ?> mt-4"><?= $pesan ?></h3>
            
            <div class="alert alert-info mt-4 text-start">
                <strong>Saran Kesehatan:</strong><br>
                <?= $saran_kesehatan ?>
            </div>

            <p class="text-muted small mt-3">Sumber: Kompas Tekno & Klinik Mata Utama (KMU)</p>

            <div class="mt-4">
                <a href="dashboard.php" class="btn btn-primary px-4 py-2">Kembali ke Dashboard</a>
                <a href="riwayat.php" class="btn btn-outline-secondary px-4 py-2">Lihat Riwayat</a>
            </div>
        </div>
    </div>
</div>

<div class="container" id="footer">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        
        <div class="col-md-4 d-flex align-items-center">
            <span class="mb-3 mb-md-0 text-body-secondary">© 2026 EyeCare, Inc</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3">
                <a class="text-body-secondary" href="#" aria-label="Instagram">
                    <i class="bi bi-instagram fs-5"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-body-secondary" href="#" aria-label="Facebook">
                    <i class="bi bi-facebook fs-5"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-body-secondary" href="#" aria-label="Twitter">
                    <i class="bi bi-twitter-x fs-5"></i>
                </a>
            </li>
        </ul>
        
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>