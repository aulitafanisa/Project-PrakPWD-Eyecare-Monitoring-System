<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM screentime WHERE id = $id AND id_user = $id_user");

    $_SESSION['hapus'] = "berhasil";
    header("location: riwayat.php");
    exit();
}

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $durasi = (int)$_POST['durasi'];

    if ($durasi < 1 || $durasi > 1440) {
        echo "<script>alert('Durasi harus 1 - 1440 menit!'); window.location='riwayat.php';</script>";
        exit();
    }

    mysqli_query($conn, "UPDATE screentime SET durasi_menit = $durasi WHERE id = $id AND id_user = $id_user");

    header("location: riwayat.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM screentime WHERE id_user = $id_user ORDER BY waktu_mulai DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Riwayat</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #eef4ff, #f9fbff);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    flex: 1;
}

.card {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(16,54,125,0.1);
    min-height: 70vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

h3 {
    font-weight: 600;
    color: #10367d;
}

.table thead {
    background-color: #10367d;
    color: white;
}

.table tbody tr:hover {
    background-color: #f1f6ff;
}

.badge-selesai {
    background-color: #d1f5e0;
    color: #1e8e5a;
}

.badge-berjalan {
    background-color: #fff3cd;
    color: #b78103;
}

.btn-warning {
    background-color: #74b4da;
    border: none;
    color: #10367d;
}

.btn-danger {
    background-color: #ff6b6b;
    border: none;
}

.btn-primary {
    background-color: #10367d;
    border: none;
}

.modal-content {
    border-radius: 15px;
}
</style>

</head>
<body>

<div class="container py-5">
<div class="card shadow">
<div class="card-body">

<h3 class="mb-1">Riwayat Screen Time</h3>
<p class="text-muted mb-4">Data penggunaan layar kamu.</p>

<?php if(mysqli_num_rows($query) > 0): ?>

<div class="table-responsive">
<table class="table table-hover text-center align-middle">
<thead>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Durasi</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php 
$no = 1;
while($row = mysqli_fetch_assoc($query)):
$tanggal = date('d M Y H:i', strtotime($row['waktu_mulai']));
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $tanggal ?></td>
<td><?= $row['durasi_menit'] ?> menit</td>

<td>
<?php if($row['status'] == 'selesai'): ?>
<span class="badge badge-selesai">Selesai</span>
<?php else: ?>
<span class="badge badge-berjalan">Berjalan</span>
<?php endif; ?>
</td>

<td>
<button class="btn btn-warning btn-sm" 
data-bs-toggle="modal" 
data-bs-target="#editModal<?= $row['id'] ?>">
Edit
</button>

<button class="btn btn-danger btn-sm" 
onclick="confirmDelete(<?= $row['id'] ?>)">
Hapus
</button>
</td>

</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST">
<div class="modal-header">
<h5 class="modal-title">Edit Durasi</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<input type="hidden" name="id" value="<?= $row['id'] ?>">

<label>Durasi (menit)</label>
<input type="number" 
       name="durasi" 
       class="form-control"
       value="<?= $row['durasi_menit'] ?>"
       min="1"
       max="1440"
       required>
</div>

<div class="modal-footer">
<button type="submit" name="update" class="btn btn-primary">Simpan</button>
</div>

</form>

</div>
</div>
</div>

<?php endwhile; ?>

</tbody>
</table>
</div>

<?php else: ?>
<div class="text-center py-5">
<h5>Belum ada data</h5>
<p class="text-muted">Kamu belum memasukan data</p>
</div>
<?php endif; ?>

<a href="dashboard.php" class="btn btn-primary mt-3">Kembali</a>

</div>
</div>
</div>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin mau hapus?',
        text: "Data akan hilang permanen",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10367d',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?hapus=' + id;
        }
    });
}
</script>

<?php if(isset($_SESSION['hapus'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Data berhasil dihapus',
    timer: 1500,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['hapus']); endif; ?>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>