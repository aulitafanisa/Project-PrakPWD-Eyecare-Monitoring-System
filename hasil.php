<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
mysqli_query($conn, "SET time_zone = '+07:00'");
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
                     WHERE id_user = $id_user AND status = 'berjalan'
                     ORDER BY waktu_mulai DESC LIMIT 1";
    mysqli_query($conn, $query_update);
} else {
    mysqli_query($conn, "DELETE FROM screentime WHERE id_user = $id_user AND status = 'berjalan'");
}

$batas_ideal = 120; 
$batas_maksimal = 240;

if ($durasi_menit <= $batas_ideal) {
    $persentase = 100;
} else {
    $pengurangan = (($durasi_menit - $batas_ideal) / ($batas_maksimal - $batas_ideal)) * 100;
    $persentase = max(0, round(100 - $pengurangan));
}

if ($durasi_menit <= 60) {
    $kondisi_mata = "Sangat Baik";
    $warna_status = "text-success";
    $saran = "Mata Anda masih segar. Tetap jaga pola istirahat yang teratur.";
} elseif ($durasi_menit <= 120) {
    $kondisi_mata = "Normal";
    $warna_status = "text-primary";
    $saran = "Sudah cukup lama di depan layar. Cobalah melihat ke kejauhan sejenak.";
} elseif ($durasi_menit <= 240) {
    $kondisi_mata = "Lelah";
    $warna_status = "text-warning";
    $saran = "Mata mulai tegang. Segera istirahatkan mata Anda sekitar 15 menit.";
} else {
    $kondisi_mata = "Sangat Lelah";
    $warna_status = "text-danger";
    $saran = "Peringatan! Anda sudah melewati batas aman 4 jam. Segera berhenti menggunakan layar.";
}
$query_history = "INSERT INTO cek_mata_history (id_user, tanggal, kategori, saran) 
                  VALUES ('$id_user', NOW(), '$kondisi_mata', '$saran')";
mysqli_query($conn, $query_history);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EyeCare - Analisis Sesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7fe; }
        .result-card {
            background: #fff; padding: 40px; border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05); margin-top: 50px;
            text-align: center; border: 1px solid #eef1f6;
        }
        .duration-circle {
            width: 200px; height: 200px; border-radius: 50%;
            border: 8px solid #eaf4ff; display: flex; align-items: center;
            justify-content: center; margin: 20px auto; flex-direction: column;
            background: #fff; box-shadow: inset 0 0 15px rgba(0,0,0,0.02);
        }
        .score-badge {
            background: #10367d; color: white; padding: 5px 15px;
            border-radius: 20px; font-size: 0.9rem; margin-bottom: 10px;
        }
        .btn-main { background: #10367d; color: white; border-radius: 12px; padding: 12px 25px; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-main:hover { background: #081d44; color: white; transform: translateY(-2px); }
        .alert-custom { background: #f8fbff; border-left: 5px solid #10367d; border-radius: 15px; padding: 20px; text-align: left; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 result-card">
            <span class="score-badge">Skor Sesi: <?= $persentase ?>%</span>
            <h2 class="fw-bold" style="color: #10367d;">Analisis Layar</h2>
            <div class="duration-circle">
                <small class="text-muted">DURASI</small>
                <h3 class="fw-bold mb-0" style="color: #10367d;"><?= $format_waktu ?></h3>
            </div>
            <h4 class="<?= $warna_status ?> fw-bold mt-3"><?= $kondisi_mata ?></h4>
            <div class="alert-custom mt-4">
                <h6 class="fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Rekomendasi Tindakan:</h6>
                <p class="mb-0 text-muted"><?= $saran ?></p>
            </div>
            <div class="mt-4 d-flex gap-2 justify-content-center">
                <a href="dashboard.php" class="btn-main">Selesai</a>
                <a href="riwayat.php" class="btn btn-outline-secondary px-4" style="border-radius: 12px; padding: 11px;">Riwayat</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>