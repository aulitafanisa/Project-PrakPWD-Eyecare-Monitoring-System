<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
$id_profile_aktif = isset($_SESSION['id_profile']) ? $_SESSION['id_profile'] : 0;

$query_user = mysqli_query($conn, "SELECT nama_lengkap FROM user WHERE id = $id_user");
$user = mysqli_fetch_assoc($query_user);

$query_aktif = mysqli_query($conn, "SELECT * FROM profiles WHERE id_profile = $id_profile_aktif");
$data_profil_aktif = mysqli_fetch_assoc($query_aktif);

$nama_tampil = $data_profil_aktif ? $data_profil_aktif['nama_profil'] : $user['nama_lengkap'];
$foto_tampil = ($data_profil_aktif && $data_profil_aktif['foto']) ? $data_profil_aktif['foto'] : 'pict1.jpg';

$kategori = "";
$hasil = "";
$warna = "";

if (isset($_POST['submit'])) {
    $total = 0;
    foreach ($_POST['q'] as $jawaban) {
        $total += (int)$jawaban;
    }

    if ($total <= 10) {
        $kategori = "Mata Sehat";
        $warna = "success";
        $hasil = "Kondisi mata kamu masih aman. Pertahankan kebiasaan baik.";
    } elseif ($total <= 18) {
        $kategori = "Mulai Lelah";
        $warna = "warning";
        $hasil = "Mata kamu mulai lelah. Kurangi screen time dan istirahatkan mata.";
    } else {
        $kategori = "Kelelahan Parah";
        $warna = "error";
        $hasil = "Mata kamu sudah sangat lelah. Segera istirahatkan mata 15-30 menit.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Cek Kondisi Mata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            left: 0;
        }
        .sidebar.collapsed {
            left: -260px;
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
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        .main.expanded {
            margin-left: 0;
            width: 100%;
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
            color: #10367d;
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
            color: #10367d;
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
            border: 2px solid #10367d;
        }
        .content-wrapper {
            padding: 40px;
            flex: 1;
        }
        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(16,54,125,0.08);
            background: white;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        .form-check {
            padding: 10px 15px 10px 35px;
            border-radius: 10px;
            transition: 0.2s;
            border: 1px solid transparent;
            margin-bottom: 5px;
        }
        .form-check:hover {
            background-color: #f1f6ff;
            border-color: #e0e9ff;
        }
        .form-check-input:checked {
            background-color: #10367d;
            border-color: #10367d;
        }
        .btn-primary {
            background: linear-gradient(135deg, #10367d, #2a52be);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16,54,125,0.2);
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
        <a href="cek_mata.php" class="active">Cek Mata</a>
        <a href="riwayat.php">Riwayat</a>
        <a href="profile.php">Profile</a> 
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

        <div class="content-wrapper">
            <div class="card">
                <div class="text-center mb-4">
                    <h3 class="fw-bold" style="color: #10367d;">Cek Kondisi Mata</h3>
                    <p class="text-muted">Jawab sesuai kondisi kamu sekarang untuk mengetahui tingkat kelelahan mata.</p>
                </div>

                <form method="POST">
                    <?php
                    $pertanyaan = [
                        "Mata terasa kering atau perih",
                        "Pandangan mulai buram setelah lama menatap layar",
                        "Sering berkedip atau mata terasa berat",
                        "Mata terasa tegang atau sakit",
                        "Sakit kepala setelah melihat layar lama"
                    ];
                    $opsi = ["Tidak Pernah", "Jarang", "Kadang", "Sering", "Selalu"];

                    foreach ($pertanyaan as $i => $p):
                    ?>
                    <div class="mb-4 p-3 border-bottom border-light">
                        <label class="form-label fw-bold mb-3" style="color: #334155;">
                            <?= ($i+1) . ". " . $p ?>
                        </label>
                        <div class="row g-2">
                            <?php foreach ($opsi as $index => $text): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="q[<?= $i ?>]" value="<?= $index+1 ?>" id="q<?= $i ?>o<?= $index ?>" required>
                                    <label class="form-check-label" for="q<?= $i ?>o<?= $index ?>"><?= $text ?></label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <button type="submit" name="submit" class="btn btn-primary w-100 mt-3">
                        Lihat Hasil Analisis
                    </button>
                </form>
            </div>
        </div>  

         
        <footer>
            <div class="text-secondary">© 2026 EyeCare, Inc</div>
        </footer>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main').classList.toggle('expanded');
        }
    </script>

    <?php if ($hasil): ?>
    <script>
        Swal.fire({
            title: "<?= $kategori ?>",
            text: "<?= $hasil ?>",
            icon: "<?= $warna ?>",
            confirmButtonColor: "#10367d",
            confirmButtonText: "Kembali ke Dashboard",
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "dashboard.php";
            }
        });
    </script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>