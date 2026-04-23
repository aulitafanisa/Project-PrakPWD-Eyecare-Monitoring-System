<?php
session_start();
include 'koneksi.php';

$id_user = $_SESSION['id'];
$action = $_GET['action'];
$now = date('Y-m-d H:i:s');

if ($action == 'start'){
    mysqli_query($conn, "INSERT INTO screentime (id_user, tanggal, waktu_mulai, `status`, durasi_menit) VALUES ($id_user, CURDATE(), '$now', 'berjalan', 0)");
    header("location: dashboard.php");
}
?>