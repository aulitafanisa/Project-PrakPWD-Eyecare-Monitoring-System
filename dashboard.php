<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || trim($_SESSION['id']) === ""){
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
<title>EyeCare - Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #f5f7fb;
}

/* SIDEBAR */
.sidebar {
    position: fixed;
    width: 220px;
    height: 100vh;
    background-color: #10367d;
    color: #ffffff;
    padding: 20px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    color: #ffffff;
    text-decoration: none;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    transition: 0.3s;
}

.sidebar a:hover,
.sidebar a.active {
    background-color: #74b4da;
    color: #10367d;
}

/* MAIN */
.main {
    margin-left: 240px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    padding: 30px;
}

/* HEADER */
.header {
    text-align: center;
    margin-bottom: 20px;
}

.header h1 {
    color: #10367d;
    font-weight: 1000;
}

/* TIMER BOX */
.center-box {
    display: flex;
    justify-content: center;
}

.time-container {
    background: linear-gradient(135deg, #ffffff, #f0f6ff);
    padding: 30px;
    border-radius: 20px;
    width: 320px;
    margin-top: 20px;
    box-shadow: 0 10px 25px rgba(16, 54, 125, 0.15);
    text-align: center;
}

#display-timer {
    font-size: 42px;
    font-weight: 700;
    margin: 20px 0;
    color: #10367d;
    background-color: #eaf4ff;
    padding: 10px;
    border-radius: 10px;
}

/* BUTTON */
.btn-start {
    background-color: #74b4da;
    color: #10367d;
    border-radius: 12px;
}

.btn-stop {
    background-color: #10367d;
    color: white;
    border-radius: 12px;
}

.btn:hover {
    transform: scale(1.05);
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>EyeCare</h2>
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="cek_mata.php">Cek Mata</a>
    <a href="riwayat.php">Riwayat</a>
    <a href="edit_profile.php">Edit Profile</a>
    <a href="logout.php" style="margin-top:50px; color:#ffbcbc;">Keluar</a>
</div>

<div class="main">

    <div class="header">
        <h1>Selamat Datang, <?= htmlspecialchars($nama)?>!</h1>
        <p>Gunakan timer untuk memantau durasi penggunaan layar Anda.</p>
    </div>

    <div class="center-box">
        <div class="time-container">
            <h3>Screen Time Tracker</h3>

            <div id="display-timer">00:00:00</div>

            <button id="startBtn" class="btn btn-start w-100 mb-2" onclick="startTimer()">START</button>

            <form action="hasil.php" method="POST">
                <input type="hidden" name="durasi_detik" id="input_detik" value="0">
                <button type="submit" id="stopBtn" class="btn btn-stop w-100" <?= $sesi ? '' : 'disabled' ?>>
                    SELESAI
                </button>
            </form>
        </div>
    </div>

    <p class="text-center mt-3 text-muted">Klik 'Selesai' untuk melihat hasil.</p>

    <!-- FOOTER (FIXED) -->
    <footer class="mt-auto pt-4 border-top d-flex justify-content-between align-items-center">

        <span class="text-muted">© 2026 EyeCare</span>

        <div>
            <i class="bi bi-instagram me-3"></i>
            <i class="bi bi-facebook me-3"></i>
            <i class="bi bi-twitter-x"></i>
        </div>

    </footer>

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
        (hrs<10?"0"+hrs:hrs)+":"+
        (mins<10?"0"+mins:mins)+":"+
        (secs<10?"0"+secs:secs);

    document.getElementById('input_detik').value = seconds;

    if(seconds > 0){
        document.getElementById('stopBtn').disabled = false;
        document.getElementById('startBtn').disabled = true;
    }
}

function startTimer(isResume=false){
    if(!isResume){
        window.location.href="proses_timer.php?action=start";
        return;
    }

    if(timer) clearInterval(timer);

    timer = setInterval(()=>{
        seconds++;
        updateDisplay();
    },1000);
}

if(isRunning){
    updateDisplay();
    startTimer(true);
}else if(seconds>0){
    updateDisplay();
}
</script>

</body>
</html>