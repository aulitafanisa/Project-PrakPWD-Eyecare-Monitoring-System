<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
$id_profile = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($conn, "SELECT * FROM profiles WHERE id_profile = $id_profile AND id_user = $id_user");
$p = mysqli_fetch_assoc($query);

if (!$p) {
    header("location: profile.php");
    exit();
}

if (isset($_POST['submit'])) {
    $nama_baru = mysqli_real_escape_string($conn, $_POST['nama_profil']);
    $tgl_baru = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $foto_baru = mysqli_real_escape_string($conn, $_POST['foto']);

    $update_profile = mysqli_query($conn, "UPDATE profiles SET 
                nama_profil = '$nama_baru', 
                tanggal_lahir = '$tgl_baru', 
                foto = '$foto_baru' 
                WHERE id_profile = $id_profile AND id_user = $id_user");

    $query_cek_utama = mysqli_query($conn, "SELECT nama_lengkap FROM user WHERE id = $id_user");
    $data_user = mysqli_fetch_assoc($query_cek_utama);

    if ($p['nama_profil'] == $data_user['nama_lengkap']) {
        mysqli_query($conn, "UPDATE user SET nama_lengkap = '$nama_baru' WHERE id = $id_user");
    }

    if ($update_profile) {
        $_SESSION['update_success'] = true;
        header("location: profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Edit Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fe;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(16, 54, 125, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 550px;
        }
        .card-header {
            background: linear-gradient(135deg, #10367d, #2a52be);
            padding: 30px;
            text-align: center;
            border: none;
        }
        .card-header h4 { color: white; font-weight: 700; margin: 0; }
        .card-body { padding: 40px; background: white; }
        .form-label { font-weight: 600; color: #1e293b; margin-bottom: 8px; }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #f1f5f9;
            background-color: #f8fafc;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #10367d;
            box-shadow: none;
            background-color: white;
        }
        .photo-selector {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-top: 10px;
        }
        .photo-option-wrapper { position: relative; cursor: pointer; }
        .photo-option-wrapper input { position: absolute; opacity: 0; cursor: pointer; }
        .photo-preview {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 12px;
            object-fit: cover;
            border: 3px solid transparent;
            transition: all 0.2s;
            padding: 2px;
        }
        .photo-option-wrapper:hover .photo-preview { transform: scale(1.05); border-color: #e2e8f0; }
        .photo-option-wrapper input:checked + .photo-preview {
            border-color: #10367d;
            background-color: #f0f4ff;
            transform: scale(1.1);
            z-index: 2;
        }
        .btn-save {
            background: linear-gradient(135deg, #10367d, #2a52be);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
            margin-top: 20px;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 54, 125, 0.3);
            color: white;
        }
        .btn-back {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            transition: 0.3s;
        }
        .btn-back:hover { color: #10367d; }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-header">
            <h4><i class="bi bi-person-gear me-2"></i>Edit Profil</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Nama Profil</label>
                    <input type="text" name="nama_profil" class="form-control" 
                           value="<?= htmlspecialchars($p['nama_profil']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" 
                           value="<?= $p['tanggal_lahir']; ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label d-block">Pilih Avatar</label>
                    <div class="photo-selector">
                        <?php for($i=1; $i<=10; $i++): 
                            $img_name = "pict$i.jpg"; 
                        ?>
                            <label class="photo-option-wrapper">
                                <input type="radio" name="foto" value="<?= $img_name; ?>" 
                                       <?= ($p['foto'] == $img_name) ? 'checked' : ''; ?>>
                                <img src="assets/<?= $img_name; ?>" class="photo-preview" 
                                     onerror="this.src='assets/pict1.jpg'">
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-save w-100">
                    Simpan Perubahan
                </button>
                
                <a href="profile.php" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>