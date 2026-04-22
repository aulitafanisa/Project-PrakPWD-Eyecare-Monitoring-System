<?php
include 'koneksi.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $tgl = $_POST['tgl_lahir'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn, "SELECT * FROM user WHERE username ='$username' OR email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Username atau Email sudah digunakan.'); window.location.href='register.php';</script>";
    }else{
        $sql = "INSERT INTO user (nama_lengkap,username, email, tanggal_lahir, password) VALUES ('$nama', '$username', '$email', '$tgl', '$password')";
        $query = mysqli_query($conn, $sql);
        if($query){
            echo "<script>alert('Akun EyeCare berhasil dibuat');window.location.href='login.php';</script>";
        }else{
            echo "<script>alert('Terjadi kesalahan saat mendaftar');window.location.href='register.php'</script>";
        }
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style></style>
</head>
<body>
    <div class="box">
        <h2>Eyecare</h2>
        <p class="text-center">Buat akun baru</p>
        <form method="POST">
            <div class="mb-2">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-input" required>
            </div>
             <div class="mb-2">
                <label>Username</label>
                <input type="text" name="username" class="form-input" required>
            </div>
             <div class="mb-2">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-input" required>
            </div>
            <div class="mb-2">
                <label>E-mail</label>
                <input type="email" name="email" class="form-input" required>
            </div>
             <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="btn btn-custom">Daftar</button>
        </form>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>