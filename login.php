<?php
session_start();
include 'koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' AND `password` = '$password'");
    if(mysqli_num_rows($sql) > 0){
        $user = mysqli_fetch_assoc($sql);
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

        $id_user = $user['id'];
        $query_profil = mysqli_query($conn, "SELECT id_profile FROM profiles WHERE id_user = $id_user");
        $jumlah_profil = mysqli_num_rows($query_profil);

        if ($jumlah_profil == 1) {
            $data_profil = mysqli_fetch_assoc($query_profil);
            $_SESSION['id_profile'] = $data_profil['id_profile'];
            header("location: Landing.php");
        } else {
            header("location: pilih_profile.php");
        }
        exit();
    } else {
        echo"<script>alert('Username atau Password Salah.'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', Sans-serif;
            background: linear-gradient(to bottom, #10367d, #ffffff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 600px;
            max-width: 90%;
        }
        .login-box h1 {
            color: #10367d;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .form {
            text-align: left;
            margin-bottom: 25px;
        }

        .form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-login {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: 'Poppins';
        }

        .btn-login {
            background-color: #10367d;
            color: white;
            border: none;
            padding: 12px;
            width: 35%;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(10, 7, 42, 0.3);
            
        }

        .btn-login:hover {
            background-color: white;
            color: #10367d;
            border-radius: 8px;
        }

        .text-center {
            margin-top: 20px;
            font-size: 14px;
        }

        .text-center a {
            color: #10367d;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>EyeCare</h1>
        <form method="POST" action="">
            <div class= "form mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-login" required>
            </div>
            <div class= "form mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-login" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
        <p class="text-center mt-3 small">Belum punya akun?<a href="register.php" style="color: #10367d; text-decoration:none; font-weight:bold;">Daftar</a></p>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>