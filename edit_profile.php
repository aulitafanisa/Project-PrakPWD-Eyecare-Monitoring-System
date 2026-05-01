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

if (isset($_POST['update'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tgl_lahir = $_POST['tgl_lahir'];

    mysqli_query($conn, "UPDATE user SET 
        nama_lengkap = '$nama',
        email = '$email',
        tgl_lahir = '$tgl_lahir'
        WHERE id = $id
    ");

    $_SESSION['nama_lengkap'] = $nama;
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #eaf2ff, #f9fbff);
}

.card {
    border: none;
    border-radius: 25px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 20px 50px rgba(16,54,125,0.15);
}

.card-title {
    font-weight: 700;
    color: #10367d;
}

.form-control {
    border-radius: 12px;
    padding: 10px;
    border: 1px solid #e0e6f5;
}

.form-control:focus {
    border-color: #74b4da;
    box-shadow: 0 0 0 0.2rem rgba(116,180,218,0.2);
}

.btn-primary {
    background: linear-gradient(135deg, #10367d, #74b4da);
    border: none;
    border-radius: 12px;
}

.btn-primary:hover {
    transform: scale(1.03);
}

.btn-outline-secondary {
    border-radius: 12px;
}
</style>
</head>

<body>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card p-4">
<div class="card-body">

<h4 class="card-title text-center mb-4">Edit Profile</h4>

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

<button type="submit" name="update" class="btn btn-primary w-100">
Simpan Perubahan
</button>

</form>

<a href="dashboard.php" class="btn btn-outline-secondary w-100 mt-3">
Kembali
</a>

</div>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($success): ?>
<script>
Swal.fire({
    title: 'Berhasil!',
    text: 'Profil berhasil diupdate',
    icon: 'success',
    confirmButtonColor: '#10367d'
}).then(() => {
    window.location.href = 'dashboard.php';
});
</script>
<?php endif; ?>

<div class="container" id="footer">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        
        <div class="col-md-4 d-flex align-items-center">
            <span class="mb-3 mb-md-0 text-body-secondary">© 2026 EyeCare, Inc</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3">
                <a class="text-body-secondary" href="#" aria-label="Instagram">
                    <i class="bi bi-instagram fs-5"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-body-secondary" href="#" aria-label="Facebook">
                    <i class="bi bi-facebook fs-5"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-body-secondary" href="#" aria-label="Twitter">
                    <i class="bi bi-twitter-x fs-5"></i>
                </a>
            </li>
        </ul>
        
    </footer>
</div>

</body>
</html>