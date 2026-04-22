<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "eyecare_db";

    $conn = mysqli_connect($host, $user, $password, $db);

    if(!$conn){
        die("Koneksi ke database gagal: ". mysqli_connect_error());
    }
?>