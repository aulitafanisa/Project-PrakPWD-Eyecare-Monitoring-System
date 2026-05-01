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

$id_profile_aktif = $_SESSION['id_profile'];

$query_user = mysqli_query($conn, "SELECT nama_lengkap FROM user WHERE id = $id_user");
$user = mysqli_fetch_assoc($query_user);

$query_aktif = mysqli_query($conn, "SELECT * FROM profiles WHERE id_profile = $id_profile_aktif");
$data_profil_aktif = mysqli_fetch_assoc($query_aktif);

$nama_tampil = $data_profil_aktif ? $data_profil_aktif['nama_profil'] : $user['nama_lengkap'];
$foto_tampil = ($data_profil_aktif && $data_profil_aktif['foto']) ? $data_profil_aktif['foto'] : 'pict1.jpg';

$query = mysqli_query($conn, "SELECT * FROM screentime WHERE id_user = $id_user AND id_profile = $id_profile_aktif ORDER BY waktu_mulai DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Screen Time</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fe;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #10367d 0%, #081d44 100%);
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            left: 0;
        }
        .sidebar.collapsed {
            left: -260px;
        }
        .sidebar h2 { 
            font-weight: 800; 
            text-align: center; 
            margin-bottom: 40px; 
            letter-spacing: 1px; 
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: block;
            transition: 0.3s;
            font-weight: 500;
        }
        .sidebar a.active, .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .main {
            margin-left: 260px;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        .main.expanded {
            margin-left: 0;
        }
        .top-nav {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .nav-left { display: flex; align-items: center; gap: 15px; }
        .brand-text {
            color: #10367d;
            font-weight: 800;
            font-size: 22px;
            margin: 0;
            display: none; 
        }
        .main.expanded .brand-text {
            display: block; 
        }
        .toggle-btn {
            cursor: pointer;
            font-size: 22px;
            color: #10367d;
            background: #f0f4ff;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: 0.2s;
        }
        .user-profile-nav { display: flex; align-items: center; gap: 12px; }
        .user-profile-nav img {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #10367d;
        }
        .content-wrapper {
            padding: 40px;
            flex: 1;
        }
        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(16,54,125,0.08);
            background: white;
            padding: 30px;
            min-height: 70vh;
        }
        .table thead {
            background-color: #10367d;
            color: white;
        }
        .table thead th {
            padding: 15px;
            font-weight: 600;
            border: none;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        .badge-selesai {
            background-color: #d1f5e0;
            color: #1e8e5a;
            padding: 6px 12px;
            border-radius: 8px;
        }
        .badge-berjalan {
            background-color: #fff3cd;
            color: #b78103;
            padding: 6px 12px;
            border-radius: 8px;
        }
        .btn-warning { background-color: #74b4da; border: none; color: #10367d; }
        .btn-danger { background-color: #ff6b6b; border: none; }
        .btn-primary { background-color: #10367d; border: none; }
        footer {
            padding: 20px 40px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>EyeCare</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="cek_mata.php">Cek Mata</a>
        <a href="riwayat.php" class="active">Riwayat</a>
        <a href="profile.php">Profile</a> 
        <a href="logout.php" style="margin-top: 50px; color: #ffbcbc;">Keluar</a>
    </div>

    <div class="main">
        <div class="top-nav">
            <div class="nav-left">
                <div class="toggle-btn" onclick="toggleSidebar()">☰</div>
                <h2 class="brand-text">EyeCare</h2>
            </div>
            <div class="user-profile-nav">
                <span>Halo, <strong><?= htmlspecialchars($nama_tampil) ?></strong></span>
                <img src="assets/<?= $foto_tampil ?>" onerror="this.src='assets/pict1.jpg'">
            </div>
        </div>

        <div class="content-wrapper">
            <div class="card">
                <div class="mb-4">
                    <h3 class="fw-bold" style="color: #10367d;">Riwayat Screen Time</h3>
                    <p class="text-muted">Data penggunaan layar kamu.</p>
                </div>

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
                                    <span class="badge <?= $row['status'] == 'selesai' ? 'badge-selesai' : 'badge-berjalan' ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id'] ?>)">Hapus</button>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 15px;">
                                        <form method="POST">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Edit Durasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-medium">Durasi (menit)</label>
                                                    <input type="number" name="durasi" class="form-control" value="<?= $row['durasi_menit'] ?>" min="1" max="1440" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="submit" name="update" class="btn btn-primary px-4">Simpan</button>
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
                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                    <h5 class="mt-3">Belum ada data</h5>
                    <p class="text-muted">Kamu belum memasukan data</p>
                </div>
                <?php endif; ?>

                <div class="mt-auto">
                    <a href="dashboard.php" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">Kembali</a>
                </div>
            </div>
        </div>

        <footer>
            <div class="text-secondary">© 2026 EyeCare, Inc</div>
            <div class="d-flex gap-3">
                <a href="#" class="text-secondary"><i class="bi bi-instagram fs-5"></i></a>
                <a href="#" class="text-secondary"><i class="bi bi-facebook fs-5"></i></a>
                <a href="#" class="text-secondary"><i class="bi bi-twitter-x fs-5"></i></a>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main').classList.toggle('expanded');
        }
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
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data berhasil dihapus', timer: 1500, showConfirmButton: false });
    </script>
    <?php unset($_SESSION['hapus']); endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>