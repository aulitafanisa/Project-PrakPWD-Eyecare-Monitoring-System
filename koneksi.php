<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "eyecare_db";    

    $conn = new mysqli($hostname, $username, $password, $database);

    date_default_timezone_set('Asia/Jakarta');
    mysqli_query($conn, "SET time_zone = '+07:00'");

    if($conn->connect_error){
        die("Koneksi gagal: ". $conn->connect_error);
    }
?>