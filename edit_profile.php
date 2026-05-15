<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = (int)$_SESSION['id'];

$query = mysqli_query($conn, "SELECT * FROM user WHERE id = $id_user");
$data = mysqli_fetch_assoc($query);

$success = false;
$error = "";

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tgl_lahir = $_POST['tgl_lahir'];
    $foto = mysqli_real_escape_string($conn, $_POST['foto']);

    if (trim($nama) == "" || !preg_match("/[a-zA-Z]/", $nama)) {
        $error = "Nama tidak valid!";
    } else {
        $update = mysqli_query($conn, "UPDATE user SET 
            nama_lengkap = '$nama',
            email = '$email',
            tgl_lahir = '$tgl_lahir',
            foto = '$foto'
            WHERE id = $id_user");

        if ($update) {
            $_SESSION['nama_lengkap'] = $nama;
            $_SESSION['foto'] = $foto;
            $success = true;
        } else {
            $error = "Gagal update data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fe;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 100%;
        }
        .card-header {
            background: #10367d;
            padding: 20px;
            text-align: center;
            border-radius: 20px 20px 0 0 !important;
        }
        .card-header h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        .photo-selector {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }
        .photo-option {
            position: relative;
            cursor: pointer;
        }
        .photo-option input {
            position: absolute;
            opacity: 0;
        }
        .photo-preview {
            width: 100%;
            border-radius: 10px;
            border: 2px solid transparent;
            transition: 0.2s;
        }
        .photo-option input:checked + .photo-preview {
            border-color: #10367d;
            transform: scale(1.05);
        }
        .btn-primary {
            background: #10367d;
            border: none;
            padding: 10px;
            border-radius: 10px;
        }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h4>Edit Profil</h4>
    </div>
    <div class="card-body p-4">
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" class="form-control" value="<?= $data['tgl_lahir'] ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label d-block">Pilih Foto</label>
                <div class="photo-selector">
                    <?php for($i=1; $i<=10; $i++): $img = "pict$i.jpg"; ?>
                        <label class="photo-option">
                            <input type="radio" name="foto" value="<?= $img ?>" <?= ($data['foto'] == $img) ? 'checked' : '' ?>>
                            <img src="assets/<?= $img ?>" class="photo-preview" onerror="this.src='assets/pict1.jpg'">
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary w-100">Simpan Perubahan</button>
            <a href="profile.php" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if ($success): ?>
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Profil diperbarui',
        icon: 'success'
    }).then(() => { window.location.href = 'profile.php'; });
</script>
<?php endif; ?>
</body>
</html>