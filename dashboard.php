<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])){
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];

if (!isset($_SESSION['id_profile'])) {
    $q_awal = mysqli_query($conn, "SELECT id_profile FROM profiles WHERE id_user = $id_user LIMIT 1");
    $p_awal = mysqli_fetch_assoc($q_awal);
    $_SESSION['id_profile'] = $p_awal ? $p_awal['id_profile'] : 0;
}

$id_profile_aktif = $_SESSION['id_profile'];
$query_aktif = mysqli_query($conn, "SELECT * FROM profiles WHERE id_profile = $id_profile_aktif");
$data_profil = mysqli_fetch_assoc($query_aktif);

$nama_tampil = $data_profil ? $data_profil['nama_profil'] : $_SESSION['nama_lengkap'];
$foto_tampil = ($data_profil && $data_profil['foto']) ? $data_profil['foto'] : 'pict1.jpg';

$cek_sesi = mysqli_query($conn,"SELECT waktu_mulai FROM screentime WHERE id_user = $id_user AND status = 'berjalan' LIMIT 1");
$sesi = mysqli_fetch_assoc($cek_sesi);
$waktu_mulai_js = 0;
if ($sesi) {
    $mulai = strtotime($sesi['waktu_mulai']);
    $sekarang = time();
    $waktu_mulai_js = $sekarang - $mulai;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Dashboard</title>
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
        .sidebar h2 { font-weight: 800; text-align: center; margin-bottom: 40px; letter-spacing: 1px; }
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
        .toggle-btn:hover { background: #e3ebff; }
        .user-profile-nav { display: flex; align-items: center; gap: 12px; }
        .user-profile-nav img {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid  #10367d;
        }
        .content-wrapper { padding: 40px; max-width: 1200px; margin: 0 auto; }
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 25px rgba(16, 54, 125, 0.05);
            border: 1px solid rgba(16, 54, 125, 0.03);
            margin-bottom: 20px;
        }
        .icon-box {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .bg-blue { background: #e3f2fd; color: #10367d; }
        .bg-green { background: #e8f5e9; color: #2e7d32; }
        .bg-orange { background: #fff3e0; color: #ef6c00; }
        .timer-card {
            background: white;
            border-radius: 30px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(16, 54, 125, 0.08);
            border: 1px solid rgba(16, 54, 125, 0.05);
        }
        #display-timer {
            font-size: 80px;
            font-weight: 800;
            color:  #10367d;
            letter-spacing: -2px;
            margin: 15px 0;
            font-variant-numeric: tabular-nums;
        }
        .timer-status {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-running { background: #e3f2fd; color: #1976d2; }
        .status-stopped { background: #f5f5f5; color: #757575; }
        .btn-start {
            background:  #10367d;
            border: none; padding: 18px;
            border-radius: 15px; font-weight: 700;
            color: white; width: 100%;
            transition: 0.3s;
        }
        .btn-start:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(16,54,125,0.2); }
        .btn-stop {
            background: transparent;
            border: 2px solid #ff4757; color: #ff4757;
            padding: 12px; border-radius: 15px;
            font-weight: 700; width: 100%;
            margin-top: 15px; transition: 0.3s;
        }
        .btn-stop:hover:not(:disabled) { background: #ff4757; color: white; }
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
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="cek_mata.php">Cek Mata</a>
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
            <div class="header mb-4">
                <h1 style="color: #10367d; font-weight: 800; margin-bottom: 5px;">Overview</h1>
                <p class="text-muted">Pantau aktivitas penggunaan layar Anda secara real-time.</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box bg-blue">👁️</div>
                        <div>
                            <div style="font-size: 0.85rem; color: #6c757d;">Kondisi Mata</div>
                            <div class="fw-bold">Normal</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box bg-green">⏱️</div>
                        <div>
                            <div style="font-size: 0.85rem; color: #6c757d;">Sesi Terlama</div>
                            <div class="fw-bold">45 Menit</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box bg-orange">🛡️</div>
                        <div>
                            <div style="font-size: 0.85rem; color: #6c757d;">Skor Kesehatan</div>
                            <div class="fw-bold">92/100</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="timer-card">
                        <div class="timer-status <?= $sesi ? 'status-running' : 'status-stopped' ?> mb-3">
                            <?= $sesi ? '● SENSOR AKTIF' : '○ SENSOR NONAKTIF' ?>
                        </div>
                        <h5 class="text-muted mb-0">WAKTU PENGGUNAAN</h5>
                        <div id="display-timer">00:00:00</div>
                        
                        <div class="row justify-content-center">
                            <div class="col-md-7">
                                <?php if(!$sesi): ?>
                                <button id="startBtn" class="btn-start" onclick="startTimer()">
                                    MULAI TRACKING
                                </button>
                                <?php endif; ?>
                                
                                <form action="hasil.php" method="POST">
                                    <input type="hidden" name="durasi_detik" id="input_detik" value="0">
                                    <button type="submit" id="stopBtn" class="btn-stop" <?= $sesi ? '' : 'disabled' ?>>
                                        SELESAI & SIMPAN
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
                 <div class="text-secondary">© 2026 EyeCare, Inc</div>
                 <div class="d-flex gap-3">
                    <a href="#" class="text-secondary"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-secondary"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-secondary"><i class="bi bi-twitter-x fs-5"></i></a>
                </div>
             </footer>
    </div>

    <script>
        let timer;
        let seconds = <?= (int)$waktu_mulai_js ?>; 
        let isRunning = <?= $sesi ? 'true' : 'false' ?>;

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main').classList.toggle('expanded');
        }

        function updateDisplay(){
            let hrs = Math.floor(seconds/3600);
            let mins = Math.floor((seconds % 3600)/ 60);
            let secs = seconds % 60;
            const fmt = (n) => n < 10 ? "0"+n : n;
            document.getElementById('display-timer').innerText = `${fmt(hrs)}:${fmt(mins)}:${fmt(secs)}`;
            document.getElementById('input_detik').value = seconds;
            if(seconds > 0) document.getElementById('stopBtn').disabled = false;
        }

        function startTimer(isResume = false){
            if(!isResume){
                window.location.href = "proses_timer.php?action=start";
                return;
            }
            if (timer) clearInterval(timer);
            timer = setInterval(() => {
                seconds++;
                updateDisplay();
            }, 1000);
        }

        if (isRunning) {
            updateDisplay();
            startTimer(true); 
        }
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>