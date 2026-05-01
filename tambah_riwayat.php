<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['id_profile'])) {
    header("location: profile.php");
    exit();
}

$id_user = $_SESSION['id'];
$id_profile = $_SESSION['id_profile'];

if (isset($_POST['submit'])) {
    $waktu_mulai = mysqli_real_escape_string($conn, $_POST['waktu_mulai']);
    $durasi = (int)$_POST['durasi_menit'];
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);

    $query = "INSERT INTO screentime (id_user, id_profile, waktu_mulai, durasi_menit, keluhan) 
              VALUES ('$id_user', '$id_profile', '$waktu_mulai', '$durasi', '$keluhan')";
    
    if (mysqli_query($conn, $query)) {
        header("location: riwayat.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EyeCare - Tambah Riwayat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f8f9fa; 
        }
        .navbar { 
            background-color: #10367d; 
        }
        .card { 
            border-radius: 15px; 
            border: none; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        }
        .btn-simpan { 
            background-color: #10367d; 
            color: white; 
            border-radius: 10px; 
            border: none; 
            padding: 10px; 
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark p-3">
    <div class="container"><a class="navbar-brand fw-bold" href="dashboard.php">EyeCare</a></div>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h5 class="fw-bold mb-4">Tambah Riwayat & Keluhan</h5>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi (Menit)</label>
                        <input type="number" name="durasi_menit" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Keluhan Mata</label>
                        <textarea name="keluhan" class="form-control" rows="3" placeholder="Contoh: Mata perih, penglihatan kabur" required></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="submit" class="btn btn-simpan">Simpan Data</button>
                        <a href="riwayat.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>