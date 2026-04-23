<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])){
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
$nama = $_SESSION['nama_lengkap'];

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

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>EyeCare</h2>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="cek_mata.php">Cek Mata</a>
        <a href="riwayat.php">Riwayat</a>
        <a href="edit_profile.php">Edit Profile</a> 
        <a href="logout.php" style="margin-top: 50px; color: #ffbcbc;">Keluar</a>
    </div>
    <div class="main">
        <div class="header">
            <h1>Selamat Datang, <?= htmlspecialchars($nama)?>!</h1>
            <p>Gunakan timer untuk memantau durasi penggunaan layar Anda.</p>
        </div>
        <div class="time-container">
            <h3>Screen Time Tracker</h3>
            <div id="display-timer">00:00:00</div>
            <div class="btn-group">
                <button id="startBtn" class="btn btn-start" onclick="startTimer()">START</button>
            </div>

            <form action="hasil.php" method="POST">
                <input type="hidden" name="durasi_detik" id="input_detik" value="0">
                <button type="submit" id="stopBtn" class="btn btn-stop" <?= $sesi ? '' : 'disabled' ?>>SELESAI</button>
            </form>
        </div>
        <p style="margin-top: 20px; color: #666;">Klik 'Selesai' untuk melihat hasil.</p>
    </div>
   <script>
        let timer;
        let seconds = <?= (int)$waktu_mulai_js ?>; 
        let isRunning = <?= $sesi ? 'true' : 'false' ?>;

        function updateDisplay(){
            let hrs = Math.floor(seconds/3600);
            let mins = Math.floor((seconds % 3600)/ 60);
            let secs = seconds % 60;
            
            document.getElementById('display-timer').innerText = 
                (hrs < 10 ? "0"+hrs : hrs) + ":" + 
                (mins < 10 ? "0"+mins : mins) + ":" + 
                (secs < 10 ? "0" +secs : secs);
            
            document.getElementById('input_detik').value = seconds;
            
            if(seconds > 0){
                document.getElementById('stopBtn').disabled = false;
                document.getElementById('startBtn').disabled = true;
            }
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
        } else if (seconds > 0) {
            updateDisplay(); 
        }
    </script>
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>