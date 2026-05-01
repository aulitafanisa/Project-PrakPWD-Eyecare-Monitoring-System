<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}
$id_user = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT * FROM profiles WHERE id_user = $id_user");
if (isset($_GET['pilih'])) {
    $_SESSION['id_profile'] = $_GET['pilih'];
    header("location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Pilih Profile</title>
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
            color: white;
            margin: 0;
        }
        .profile-card {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid transparent;
            border-radius: 20px;
            padding: 25px 15px;
            transition: 0.3s;
            text-decoration: none;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 160px;
            margin: 15px;
            backdrop-filter: blur(5px);
        }
        .profile-card:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: white;
            transform: translateY(-10px);
            color: white;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid rgba(255, 255, 255, 0.5);
        }
        .add-btn {
            background-color: #28a745;
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
        }
        .add-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
            color: white;
        }
    </style>
</head>
<body>
    <div class="text-center">
        <h2 class="mb-5 fw-bold">Siapa yang menggunakan layar?</h2>
        <div class="d-flex justify-content-center flex-wrap">
            <?php while($row = mysqli_fetch_assoc($query)): ?>
                <a href="?pilih=<?= $row['id_profile']; ?>" class="profile-card">
                    <img src="assets/<?= $row['foto']; ?>" 
                         alt="Profile" 
                         class="profile-img" 
                         onerror="this.src='assets/pict1.jpg'">
                    <h6 class="fw-bold m-0"><?= htmlspecialchars($row['nama_profil']); ?></h6>
                </a>
            <?php endwhile; ?>
        </div>
        <div class="mt-5">
            <a href="tambah_profile.php" class="add-btn">+ Tambah Profil</a>
            <br><br>
            <a href="logout.php" class="text-white text-decoration-none small opacity-75">Keluar dari Akun</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>