<?php
include 'koneksi.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nama = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $tgl = $_POST['tgl_lahir'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if($nama === "" || $username === "" || $password === "" || $email === ""){
        echo "<script>alert('Pendaftaran gagal: Semua kolom harus diisi dan tidak boleh hanya spasi!'); window.history.back();</script>";
        exit();
    }

    if (preg_match('/\s/', $username)) {
        echo "<script>alert('Pendaftaran gagal: Username tidak boleh mengandung spasi!'); window.history.back();</script>";
        exit();
    }

    $cek = mysqli_query($conn, "SELECT * FROM user WHERE username ='$username' OR email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Username atau Email sudah digunakan.'); window.location.href='register.php';</script>";
    }else{
        $sql = "INSERT INTO user (nama_lengkap, email, tgl_lahir, username, `password`) VALUES ('$nama', '$email', '$tgl', '$username', '$password')";
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
    <style>
         body {
            margin: 0;
            font-family: 'Poppins', Sans-serif;
            background: linear-gradient(to bottom, #10367d, #ffffff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding:20px;
        }

        .box {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            width: 600px;
            max-width: 90%;
        }

        .box h2 {
            color: #10367d;
            text-align: center;
            font-weight: bold;
        }

        .form {
            margin-bottom: 12px;
        }

        .form label {
            text-align: left;
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn {
            background-color: #10367d;
            color: white;
            border: none;
            padding: 12px;
            width: 50%;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(10, 7, 42, 0.3);
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>EyeCare</h2>
        <p class="text-center">Buat akun baru</p>
        <form method="POST" action="">
            <div class="form mb-2">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-input" required>
            </div>
             <div class="form mb-2">
                <label>Username</label>
                <input type="text" name="username" class="form-input" required>
            </div>
             <div class="form mb-2">
                <label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" max="<?php echo date('Y-m-d'); ?>" class="form-input" required>
            </div>
            <div class="form mb-2">
                <label>E-mail</label>
                <input type="email" name="email" class="form-input" required>
            </div>
             <div class="form mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="btn btn-custom">Daftar</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>