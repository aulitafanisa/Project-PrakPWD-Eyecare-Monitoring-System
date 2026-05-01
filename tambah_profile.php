<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}
$id_user = $_SESSION['id'];
$cek_jumlah = mysqli_query($conn, "SELECT id_profile FROM profiles WHERE id_user = $id_user");
$jumlah_saat_ini = mysqli_num_rows($cek_jumlah);
$maksimal_profil = 5; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($jumlah_saat_ini >= $maksimal_profil) {
        echo "<script>alert('Batas maksimal profil tercapai ($maksimal_profil profil).'); window.location.href='profile.php';</script>";
    } else {
        $nama_profil = $_POST['nama_profil'];
        $tgl_lahir = $_POST['tgl_lahir'];
        $random_num = rand(1, 10);
        $foto_random = "pict" . $random_num . ".jpg";
        $sql = "INSERT INTO profiles (id_user, nama_profil, tanggal_lahir, foto) VALUES ('$id_user', '$nama_profil', '$tgl_lahir', '$foto_random')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Profil berhasil ditambahkan!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Gagal menambah profil');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Tambah Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #10367d, #ffffff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .box {
            background-color: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .btn-save {
            background-color: #10367d;
            color: white;
            width: 100%;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="box">
        <h4 class="text-center mb-4">Tambah Profil</h4>
        <?php if ($jumlah_saat_ini >= $maksimal_profil): ?>
            <div class="alert alert-danger text-center">Batas profil sudah penuh!</div>
            <a href="profile.php" class="btn btn-secondary w-100">Kembali</a>
        <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Profil</label>
                    <input type="text" name="nama_profil" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-save">Simpan</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
