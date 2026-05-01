<?php
session_start();
include 'koneksi.php';
if (isset($_GET['id'])) {
    $id_pro = $_GET['id'];
    $id_user = $_SESSION['id'];
    $cek = mysqli_query($conn, "SELECT * FROM profiles WHERE id_profile = $id_pro AND id_user = $id_user");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM profiles WHERE id_profile = $id_pro");
        if ($_SESSION['id_profile'] == $id_pro) {
            unset($_SESSION['id_profile']);
        }
        header("location: profile.php?status=deleted");
    } else {
        header("location: profile.php?status=error");
    }
}
?>