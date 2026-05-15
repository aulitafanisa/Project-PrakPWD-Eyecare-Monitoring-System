<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = (int)$_SESSION['id'];

$query_user = mysqli_query($conn, "SELECT * FROM user WHERE id = $id_user");
$user = mysqli_fetch_assoc($query_user);

$nama_tampil = $user['nama_lengkap'];
$foto_tampil = !empty($user['foto']) ? $user['foto'] : 'pict1.jpg';

$tanggal_lahir = new DateTime($user['tgl_lahir']);
$hari_ini = new DateTime('today');
$umur = $hari_ini->diff($tanggal_lahir)->y;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fe;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #10367d 0%, #081d44 100%);
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
            z-index: 1000;
            transition: 0.3s;
        }

        .sidebar.collapsed {
            margin-left: -260px;
        }

        .sidebar h2 { font-weight: 800; text-align: center; margin-bottom: 40px; }
        .sidebar a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: block;
            transition: 0.3s;
        }
        .sidebar a.active, .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .main { 
            margin-left: 260px; 
            width: 100%; 
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main.expanded {
            margin-left: 0;
        }

        .top-nav {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .toggle-btn {
            cursor: pointer;
            font-size: 24px;
            color: #10367d;
            margin-right: 20px;
        }

        .user-profile-nav { display: flex; align-items: center; gap: 12px; }
        .user-profile-nav img {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #10367d;
        }

        .content-wrapper { padding: 50px 20px; max-width: 800px; margin: 0 auto; flex: 1; width: 100%; }

        .card-profile {
            background: white;
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(16, 54, 125, 0.08);
            padding: 40px;
            position: relative;
            text-align: center;
        }

        .profile-img-container {
            position: relative;
            display: inline-block;
            margin-bottom: 25px;
        }

        .profile-img-big {
            width: 160px; height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #f0f4ff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .info-container {
            background-color: #f8fbff;
            border-radius: 20px;
            padding: 25px;
            margin-top: 20px;
        }

        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eef2f7;
            text-align: left;
        }

        .info-row:last-child { border-bottom: none; }

        .info-label {
            font-weight: 600;
            color: #10367d;
            font-size: 0.95rem;
        }

        .info-value {
            color: #5a6a85;
            font-weight: 500;
        }

        .btn-edit-profile {
            background: linear-gradient(135deg, #10367d, #2563eb);
            color: white;
            font-weight: 600;
            padding: 14px 40px;
            border-radius: 14px;
            text-decoration: none;
            display: inline-block;
            margin-top: 30px;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 10px 20px rgba(16, 54, 125, 0.2);
        }

        .btn-edit-profile:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(16, 54, 125, 0.3);
        }

        .brand-text { color: #10367d; font-weight: 800; font-size: 1.5rem; }

        footer {
            background: white;
            padding: 20px 40px;
            border-top: 1px solid #eee;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>EyeCare</h2>
        <a href="dashboard.php"><i class="bi bi-house-door me-2"></i> Dashboard</a>
        <a href="cek_mata.php"><i class="bi bi-eye me-2"></i> Cek Mata</a>
        <a href="riwayat.php"><i class="bi bi-clock-history me-2"></i> Riwayat</a>
        <a href="profile.php" class="active"><i class="bi bi-person me-2"></i> Profile</a> 
        <a href="logout.php" style="margin-top: 50px; color: #ffbcbc;"><i class="bi bi-box-arrow-right me-2"></i> Keluar</a>
    </div>

    <div class="main">
        <div class="top-nav">
            <div class="d-flex align-items-center">
                <i class="bi bi-list toggle-btn" onclick="toggleSidebar()"></i>
                <h4 class="m-0 fw-bold" style="color: #10367d;">Profile</h4>
            </div>
            <div class="user-profile-nav">
                <span>Halo, <strong><?= htmlspecialchars($nama_tampil) ?></strong></span>
                <img src="assets/<?= $foto_tampil ?>" onerror="this.src='assets/pict1.jpg'">
            </div>
        </div>

        <div class="content-wrapper">
            <div class="text-center mb-5">
                <h2 class="brand-text">Informasi Profil</h2>
                <p class="text-muted">Kelola data diri Anda dalam satu tempat</p>
            </div>

            <div class="card-profile">
                <div class="profile-img-container">
                    <img src="assets/<?= $foto_tampil ?>" class="profile-img-big" onerror="this.src='assets/pict1.jpg'">
                </div>

                <div class="info-container">
                    <div class="row info-row mx-0">
                        <div class="col-5 info-label px-0"><i class="bi bi-person-fill me-2"></i>Nama Lengkap</div>
                        <div class="col-7 info-value px-0 text-end"><?= htmlspecialchars($user['nama_lengkap']); ?></div>
                    </div>
                    <div class="row info-row mx-0">
                        <div class="col-5 info-label px-0"><i class="bi bi-at me-2"></i>Username</div>
                        <div class="col-7 info-value px-0 text-end"><?= htmlspecialchars($user['username']); ?></div>
                    </div>
                    <div class="row info-row mx-0">
                        <div class="col-5 info-label px-0"><i class="bi bi-envelope-fill me-2"></i>Email</div>
                        <div class="col-7 info-value px-0 text-end"><?= htmlspecialchars($user['email']); ?></div>
                    </div>
                    <div class="row info-row mx-0">
                        <div class="col-5 info-label px-0"><i class="bi bi-calendar-event-fill me-2"></i>Tanggal Lahir</div>
                        <div class="col-7 info-value px-0 text-end"><?= date('d F Y', strtotime($user['tgl_lahir'])); ?></div>
                    </div>
                    <div class="row info-row mx-0">
                        <div class="col-5 info-label px-0"><i class="bi bi-hourglass-split me-2"></i>Umur Saat Ini</div>
                        <div class="col-7 info-value px-0 text-end"><?= $umur ?> Tahun</div>
                    </div>
                </div>

                <a href="edit_profile.php" class="btn-edit-profile">
                    <i class="bi bi-pencil-square me-2"></i> Edit Profil Saya
                </a>
            </div>

            <div class="text-center mt-5">
                <a href="dashboard.php" class="text-decoration-none text-muted">
                    <i class="bi bi-chevron-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>

        <footer>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">© 2026 EyeCare, Inc</span>
                <div class="d-flex gap-3">
                    <i class="bi bi-instagram text-muted fs-5"></i>
                    <i class="bi bi-facebook text-muted fs-5"></i>
                    <i class="bi bi-twitter-x text-muted fs-5"></i>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main').classList.toggle('expanded');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>