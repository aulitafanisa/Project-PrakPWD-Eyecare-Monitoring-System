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

$nama_file_foto = !empty($user['foto']) ? $user['foto'] : 'pict1.jpg';

$query_min_id = mysqli_query($conn, "SELECT MIN(id_profile) as id_utama FROM profiles WHERE id_user = $id_user");
$res_min = mysqli_fetch_assoc($query_min_id);
$id_utama = $res_min['id_utama'];

if (!isset($_SESSION['id_profile']) || $_SESSION['id_profile'] == 0) {
    $_SESSION['id_profile'] = $id_utama;
}

if (isset($_GET['switch'])) {
    $id_target = (int)$_GET['switch'];
    $cek_milik = mysqli_query($conn, "SELECT id_profile FROM profiles WHERE id_profile = $id_target AND id_user = $id_user");
    if (mysqli_num_rows($cek_milik) > 0) {
        $_SESSION['id_profile'] = $id_target;
    }
    header("location: profile.php");
    exit();
}

if (isset($_GET['switch_main'])) {
    $_SESSION['id_profile'] = $id_utama;
    header("location: profile.php");
    exit();
}

if (isset($_GET['hapus_id'])) {
    $id_hapus = (int)$_GET['hapus_id'];
    if ($id_hapus != $id_utama) {
        mysqli_query($conn, "DELETE FROM profiles WHERE id_profile = $id_hapus AND id_user = $id_user");
        if ($_SESSION['id_profile'] == $id_hapus) {
            $_SESSION['id_profile'] = $id_utama;
        }
        $_SESSION['info_hapus'] = "berhasil";
    }
    header("location: profile.php");
    exit();
}

$id_profile_aktif = $_SESSION['id_profile'];
$query_aktif = mysqli_query($conn, "SELECT * FROM profiles WHERE id_profile = $id_profile_aktif AND id_user = $id_user");
$data_profil_aktif = mysqli_fetch_assoc($query_aktif);

if ($data_profil_aktif) {
    $nama_tampil = $data_profil_aktif['nama_profil'];
    $foto_tampil = $data_profil_aktif['foto'] ? $data_profil_aktif['foto'] : 'pict1.jpg';
} else {
    $nama_tampil = $user['nama_lengkap'];
    $foto_tampil = 'pict1.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color:  #f4f7fe;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            left: 0;
        }

        .sidebar.collapsed {
            left: calc(260px * -1);
        }

        .sidebar h2 { 
            font-weight: 800; 
            text-align: center; 
            margin-bottom: 40px; 
            letter-spacing: 1px; 
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: block;
            transition: 0.3s;
            font-weight: 500;
        }

        .sidebar a.active, .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .main {
            margin-left: 260px;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .nav-left { display: flex; align-items: center; gap: 15px; }
        
        .brand-text {
            color:  #10367d;
            font-weight: 800;
            font-size: 22px;
            margin: 0;
            display: none; 
        }

        .main.expanded .brand-text {
            display: block; 
        }

        .toggle-btn {
            cursor: pointer;
            font-size: 22px;
            color:  #10367d;
            background: #f0f4ff;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: 0.2s;
        }

        .user-profile-nav { display: flex; align-items: center; gap: 12px; }
        .user-profile-nav img {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid  #10367d;
        }

         
        .content-wrapper {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-header {
            font-weight: 600;
            color:  #10367d;
            padding-bottom: 30px;
            padding-top: 30px;
        }

        .card-utama, .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            background-color: white;
            padding: 25px;
        }

        .card-utama { text-align: center; }

        .profile-img {
            width: 200px; height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 30px;
            border: 2px solid #eee;
        }

        .info-label {
            font-weight: 600;
            font-size: 15px;
            color: #000000;
            display: flex;
            justify-content: space-between;
        }

        .info-value { color: #666666; }

        .btn-edit-akun {
            background-color: #ffc107;
            color: #000000;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            padding: 10px 50px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }

        .profile-img-list {
            width: 45px; height: 45px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #eee;
        }
        .btn-use {
            background-color: #e9ecef;
            color:  #10367d;
            font-weight: 600;
            font-size: 12px;
            border-radius: 8px;
            padding: 5px 15px;
            text-decoration: none;
            margin-top: 15px;

        }

        .btn-add {
            background-color:  #10367d;
            color: white;
            font-size: 14px;
            border-radius: 8px;
        }

        .btn-switch {
            background-color: #e9ecef;
            color:  #10367d;
            font-weight: 600;
            font-size: 12px;
            border-radius: 8px;
            padding: 5px 15px;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000000;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            padding: 4px 10px;
            text-decoration: none;
            margin-right: 5px;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            padding: 4px 10px;
            border: none;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #10367d;
            color: #10367d;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #10367d;
            color: white;
            transform: translateX(-5px);
        }

        .badge-active {
            background-color: #28a745;  
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
        }

        .badge-active i {
            font-size: 13px;
        }

        footer {
            padding: 20px 40px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }
    </style>
</head>
<body>

     
    <div class="sidebar">
        <h2>EyeCare</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="cek_mata.php">Cek Mata</a>
        <a href="riwayat.php">Riwayat</a>
        <a href="profile.php" class="active">Profile</a> 
        <a href="logout.php" style="margin-top: 50px; color: #ffbcbc;">Keluar</a>
    </div>

    <div class="main">
        <div class="top-nav">
            <div class="nav-left">
                <div class="toggle-btn" onclick="toggleSidebar()">☰</div>
                <h2 class="brand-text">EyeCare</h2>
            </div>
            <div class="user-profile-nav">
                <span>Halo, <strong><?= htmlspecialchars($nama_tampil) ?></strong></span>
                <img src="assets/<?= $foto_tampil ?>" onerror="this.src='assets/pict1.jpg'">
            </div>
        </div>

        <div class="container mt-4 content-wrapper">
            <div class="profile-header text-center">
                <h3>Profil & Pengguna</h3>
                <p>Kelola profil dan siapa yang sedang menggunakan device</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card-utama">
                        <h5>Profile Utama</h5>
                        <?php if ($_SESSION['id_profile'] == $id_utama): ?>
                            <span class="badge-active mb-3"><i class="bi bi-patch-check-fill"></i> SEDANG AKTIF</span>
                        <?php endif; ?>
                        <hr>
                        <img src="assets/<?= $nama_file_foto ?>" class="profile-img" onerror="this.src='assets/pict1.jpg'">
                        <div class="mx-auto" style="max-width: 400px;">
                            <div class="row mb-2 text-start">
                                <div class="col-5 info-label">Nama Lengkap <span>:</span></div>
                                <div class="col-7 info-value"><?= htmlspecialchars($user['nama_lengkap']); ?></div>
                            </div>
                            <div class="row mb-2 text-start">
                                <div class="col-5 info-label">Username <span>:</span></div>
                                <div class="col-7 info-value"><?= htmlspecialchars($user['username']); ?></div>
                            </div>
                            <div class="row mb-2 text-start">
                                <div class="col-5 info-label">E-mail <span>:</span></div>
                                <div class="col-7 info-value"><?= htmlspecialchars($user['email']); ?></div>
                            </div>
                            <div class="row mb-2 text-start">
                                <div class="col-5 info-label">Tanggal Lahir <span>:</span></div>
                                <div class="col-7 info-value"><?= date('d F Y', strtotime($user['tgl_lahir'])); ?></div>
                            </div>
                            <div class="row mb-2 text-start">
                                <div class="col-5 info-label">Umur <span>:</span></div>
                                <div class="col-7 info-value">
                                    <?php 
                                        $tgl_lahir = new DateTime($user['tgl_lahir']);
                                        $sekarang = new DateTime('today');
                                        echo $tgl_lahir->diff($sekarang)->y . " Tahun";
                                    ?>
                                </div>
                            </div>
                            <a href="edit_user.php" class="btn-edit-akun">Edit Profile Utama</a>
                            <?php if($_SESSION['id_profile'] != $id_utama): ?>
                                <div class="mt-3">
                                    <a href="?switch_main=true" class="btn-switch py-2 px-3">Gunakan Akun Utama</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0">Daftar Pengguna Tambahan</h5>
                            <a href="tambah_profile.php" class="btn btn-add btn-sm">+ Tambah</a>
                        </div>
                        <div class="list-group list-group-flush">
                            <?php 
                            $query_profiles = mysqli_query($conn, "SELECT * FROM profiles WHERE id_user = $id_user AND id_profile != $id_utama");
                            if(mysqli_num_rows($query_profiles) > 0): 
                                while($p = mysqli_fetch_assoc($query_profiles)): 
                                    $tgl = new DateTime($p['tanggal_lahir']);
                                    $umur = $tgl->diff(new DateTime('today'))->y;
                            ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/<?= $p['foto']; ?>" class="profile-img-list" onerror="this.src='assets/pict1.jpg'">
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($p['nama_profil']); ?></h6>
                                            <small class="text-muted">Umur : <?= $umur; ?> Tahun</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="edit_profile.php?id=<?= $p['id_profile']; ?>" class="btn-edit">Edit</a>
                                        <button onclick="confirmHapus(<?= $p['id_profile']; ?>, '<?= addslashes($p['nama_profil']); ?>')" class="btn-delete">Hapus</button>
                                        <div class="ms-3">
                                            <?php if($_SESSION['id_profile'] == $p['id_profile']): ?>
                                                <span class="badge-active">AKTIF</span>
                                            <?php else: ?>
                                                <a href="?switch=<?= $p['id_profile']; ?>" class="btn-switch">Gunakan</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; else: ?>
                                <p class="text-center text-muted py-3">Belum ada pengguna tambahan.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="text-center mt-4 mb-5">
                        <a href="dashboard.php" class="btn btn-outline-primary px-4">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="text-secondary">© 2026 EyeCare, Inc</div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main').classList.toggle('expanded');
        }

        function confirmHapus(id, nama) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Profil " + nama + " bakal hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10367d',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "profile.php?hapus_id=" + id;
                }
            })
        }
    </script>

    <?php if(isset($_SESSION['info_hapus'])): ?>
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Profil sudah dihapus', showConfirmButton: false, timer: 1500 });
    </script>
    <?php unset($_SESSION['info_hapus']); endif; ?>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>