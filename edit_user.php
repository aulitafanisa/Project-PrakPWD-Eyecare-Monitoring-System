<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id = $_SESSION['id'];

$query = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");
$data = mysqli_fetch_assoc($query);

$success = false;
$error = "";

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tgl_lahir = $_POST['tgl_lahir'];
    $foto = mysqli_real_escape_string($conn, $_POST['foto']);
    if (trim($nama) == "" || !preg_match("/[a-zA-Z]/", $nama)) {
        $error = "Nama tidak valid! Harus mengandung huruf dan bukan hanya spasi.";
    } else {
        $update = mysqli_query($conn, "UPDATE user SET 
            nama_lengkap = '$nama',
            email = '$email',
            tgl_lahir = '$tgl_lahir',
            foto = '$foto'
            WHERE id = $id
        ");

        if ($update) {
            $_SESSION['nama_lengkap'] = $nama;
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Edit Akun Utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f4f7fe 0%, #e9effd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(16, 54, 125, 0.1);
            max-width: 600px;
            width: 100%;
        }
        .card-header {
            background: linear-gradient(135deg, #10367d, #2a52be);
            padding: 30px;
            text-align: center;
            border-radius: 25px 25px 0 0 !important;
            border: none;
        }
        .card-header h4 { color: white; font-weight: 700; margin: 0; }
        .form-label { font-weight: 600; color: #1e293b; }
        
        .photo-selector {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-top: 10px;
        }
        .photo-option-wrapper { position: relative; cursor: pointer; }
        .photo-option-wrapper input { position: absolute; opacity: 0; }
        .photo-preview {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 12px;
            object-fit: cover;
            border: 3px solid transparent;
            transition: all 0.2s;
        }
        .photo-option-wrapper:hover .photo-preview { transform: scale(1.05); }
        .photo-option-wrapper input:checked + .photo-preview {
            border-color: #10367d;
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(16, 54, 125, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10367d, #2a52be);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
        }
        .btn-back {
            color: #64748b;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-person-circle me-2"></i>Edit Akun Utama</h4>
    </div>
    <div class="card-body p-4">
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" 
                       value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($data['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" class="form-control" 
                       value="<?= $data['tgl_lahir'] ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label d-block">Pilih Avatar Profile Utama</label>
                <div class="photo-selector">
                    <?php for($i=1; $i<=10; $i++): 
                        $img_name = "pict$i.jpg"; 
                    ?>
                        <label class="photo-option-wrapper">
                            <input type="radio" name="foto" value="<?= $img_name; ?>" 
                                   <?= ($data['foto'] == $img_name) ? 'checked' : ''; ?>>
                            <img src="assets/<?= $img_name; ?>" class="photo-preview" 
                                 onerror="this.src='assets/pict1.jpg'">
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <button type="submit" name="update" class="btn btn-primary w-100">Simpan Perubahan</button>
            <a href="profile.php" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Profile</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if ($success): ?>
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Profil Akun Utama berhasil diperbarui',
        icon: 'success',
        confirmButtonColor: '#10367d'
    }).then(() => {
        window.location.href = 'profile.php';
    });
</script>
<?php endif; ?>

</body>
</html>