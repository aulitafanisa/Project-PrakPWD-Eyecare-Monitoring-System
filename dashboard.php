<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])){
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];

$query_user = mysqli_query($conn, "SELECT * FROM user WHERE id = $id_user");
$data_user = mysqli_fetch_assoc($query_user);

$nama_tampil = $data_user['nama_lengkap'];
$foto_tampil = !empty($data_user['foto']) ? $data_user['foto'] : 'pict1.jpg';

$cek_sesi = mysqli_query($conn, "SELECT waktu_mulai FROM screentime WHERE id_user = $id_user AND status = 'berjalan' LIMIT 1");
$sesi = mysqli_fetch_assoc($cek_sesi);
$waktu_mulai_js = 0;
if ($sesi) {
    $mulai = strtotime($sesi['waktu_mulai']);
    $sekarang = time();
    $waktu_mulai_js = $sekarang - $mulai;
}

$q_stats = mysqli_query($conn, "SELECT 
    MAX(durasi_menit) as maksimal, 
    AVG(durasi_menit) as rata_rata,
    COUNT(*) as total_sesi 
    FROM screentime WHERE id_user = $id_user AND status = 'selesai'");
$data_stats = mysqli_fetch_assoc($q_stats);

$rata_rata_durasi = $data_stats['rata_rata'] ?? 0;

$q_semua_riwayat = mysqli_query($conn, "SELECT kategori FROM cek_mata_history WHERE id_user = $id_user");
$total_data = mysqli_num_rows($q_semua_riwayat);

if ($total_data > 0) {
    $total_poin = 0;
    while($row = mysqli_fetch_assoc($q_semua_riwayat)) {
        if ($row['kategori'] == 'Mata Sehat') {
            $total_poin += 100;
        } elseif ($row['kategori'] == 'Mulai Lelah') {
            $total_poin += 65;
        } elseif ($row['kategori'] == 'Kelelahan Parah') {
            $total_poin += 30;
        }
    }
    $skor_dasar = round($total_poin / $total_data);
} else {
    $skor_dasar = 100;
}

if ($data_stats['total_sesi'] > 0) {
    $target_sehat = 120; 
    if ($rata_rata_durasi > $target_sehat) {
        $penalti = (($rata_rata_durasi - $target_sehat) / 120) * 50;
        $skor_kesehatan = max(0, round($skor_dasar - $penalti));
    } else {
        $skor_kesehatan = $skor_dasar;
    }
} else {
    $skor_kesehatan = $skor_dasar;
}

if ($skor_kesehatan >= 80) {
    $kondisi_mata = "Sangat Baik";
    $warna_mata = "text-success";
} elseif ($skor_kesehatan >= 50) {
    $kondisi_mata = "Lelah";
    $warna_mata = "text-warning";
} else {
    $kondisi_mata = "Bahaya";
    $warna_mata = "text-danger";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fe;
            display: flex;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #10367d 0%, #081d44 100%);
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
            transition: 0.3s;
            z-index: 1000;
        }

        .sidebar.collapsed {
            margin-left: -260px;
        }

        .sidebar h2 {
            font-weight: 800;
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: block;
            transition: 0.3s;
        }

        .sidebar a.active, 
        .sidebar a:hover {
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

        .user-profile-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-profile-nav img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #10367d;
        }

        .content-wrapper {
            padding: 40px;
            flex: 1;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 25px rgba(16, 54, 125, 0.05);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .timer-card {
            background: white;
            border-radius: 30px;
            padding: 50px;
            margin-top: 50px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(16, 54, 125, 0.08);
        }

        #display-timer {
            font-size: 60px;
            font-weight: 800;
            color: #10367d;
            margin: 20px 0;
        }

        .btn-start {
            background: #10367d;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
        }

        .btn-stop {
            background: transparent;
            border: 2px solid #df1324;
            color: #df1324;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }

        .toggle-btn {
            cursor: pointer;
            font-size: 24px;
            color: #10367d;
            margin-right: 20px;
        }

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
        <a href="dashboard.php" class="active"><i class="bi bi-house-door me-2"></i> Dashboard</a>
        <a href="cek_mata.php"><i class="bi bi-eye me-2"></i> Cek Mata</a>
        <a href="riwayat.php"><i class="bi bi-clock-history me-2"></i> Riwayat</a>
        <a href="profile.php"><i class="bi bi-person me-2"></i> Profile</a>
        <a href="logout.php" style="margin-top: 50px; color: #ffbcbc;"><i class="bi bi-box-arrow-right me-2"></i> Keluar</a>
    </div>

    <div class="main">
        <div class="top-nav">
            <div class="d-flex align-items-center">
                <i class="bi bi-list toggle-btn" onclick="toggleSidebar()"></i>
                <h4 class="m-0 fw-bold" style="color: #10367d;">Dashboard</h4>
            </div>
            <div class="user-profile-nav">
                <span>Halo, <strong><?= htmlspecialchars($nama_tampil) ?></strong></span>
                <img src="assets/<?= $foto_tampil ?>" onerror="this.src='assets/pict1.jpg'">
            </div>
        </div>

        <div class="content-wrapper">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">🛡️</div>
                        <div>
                            <small class="text-muted">Skor Kesehatan</small>
                            <h5 class="mb-0 fw-bold <?= $warna_mata ?>"><?= $skor_kesehatan ?>%</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box bg-success bg-opacity-10 text-success">👁️</div>
                        <div>
                            <small class="text-muted">Kondisi Mata</small>
                            <h5 class="mb-0 fw-bold <?= $warna_mata ?>"><?= $kondisi_mata ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">⏱️</div>
                        <div>
                            <small class="text-muted">Rata-rata Durasi</small>
                            <h5 class="mb-0 fw-bold"><?= round($rata_rata_durasi) ?> Menit</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="timer-card">
                        <h6 class="text-muted fw-bold">WAKTU PENGGUNAAN LAYAR</h6>
                        <div id="display-timer">00:00:00</div>
                        
                        <?php if(!$sesi): ?>
                            <button class="btn-start" onclick="location.href='proses_timer.php?action=start'">MULAI TRACKING</button>
                        <?php endif; ?>
                        
                        <form action="hasil.php" method="POST">
                            <input type="hidden" name="durasi_detik" id="input_detik" value="0">
                            <button type="submit" class="btn-stop" <?= $sesi ? '' : 'disabled' ?>>BERHENTI & SIMPAN</button>
                        </form>
                    </div>
                </div>
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

        let seconds = <?= (int)$waktu_mulai_js ?>;
        let isRunning = <?= $sesi ? 'true' : 'false' ?>;

        function updateDisplay(){
            let hrs = Math.floor(seconds/3600);
            let mins = Math.floor((seconds % 3600)/ 60);
            let secs = seconds % 60;
            const fmt = (n) => n < 10 ? "0"+n : n;
            document.getElementById('display-timer').innerText = `${fmt(hrs)}:${fmt(mins)}:${fmt(secs)}`;
            document.getElementById('input_detik').value = seconds;
        }

        if (isRunning) {
            setInterval(() => {
                seconds++;
                updateDisplay();
            }, 1000);
        }
        updateDisplay();
    </script>
</body>
</html>