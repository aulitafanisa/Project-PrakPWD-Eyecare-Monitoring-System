<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
mysqli_query($conn, "SET time_zone = '+07:00'");
$query_user = mysqli_query($conn, "SELECT * FROM user WHERE id = $id_user");
$data_user = mysqli_fetch_assoc($query_user);

$nama_tampil = $data_user['nama_lengkap'];
$foto_tampil = !empty($data_user['foto']) ? $data_user['foto'] : 'pict1.jpg';

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
    mysqli_query($conn, "UPDATE screentime SET durasi_menit = $durasi WHERE id = $id AND id_user = $id_user");
    header("location: riwayat.php");
    exit();
}

$query_screen = mysqli_query($conn, "SELECT * FROM screentime WHERE id_user = $id_user ORDER BY waktu_mulai ASC");
$query_cek = mysqli_query($conn, "SELECT * FROM cek_mata_history WHERE id_user = $id_user ORDER BY tanggal ASC");

$grafik_data = mysqli_query($conn, "
    SELECT DATE_FORMAT(waktu_mulai, '%d %b') as tgl, SUM(durasi_menit) as total 
    FROM screentime 
    WHERE id_user = $id_user AND waktu_mulai >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
    GROUP BY DATE(waktu_mulai)
    ORDER BY waktu_mulai ASC
");

$labels = [];
$totals = [];
while($row = mysqli_fetch_assoc($grafik_data)){
    $labels[] = $row['tgl'];
    $totals[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Riwayat & Statistik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fe;
            display: flex;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #10367d 0%, #081d44 100%);
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
            transition: 0.3s;
            z-index: 1000;
        }
        .sidebar.collapsed {
            margin-left: -260px;
        }
        .sidebar h2 {
            font-weight: 800;
            text-align: center;
            margin-bottom: 40px;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: block;
            transition: 0.3s;
        }
        .sidebar a.active, .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .main {
            margin-left: 260px;
            width: 100%;
            transition: 0.3s;
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
        }
        .user-profile-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-profile-nav img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #10367d;
        }
        .toggle-btn {
            cursor: pointer;
            font-size: 24px;
            color: #10367d;
            margin-right: 20px;
        }
        .content-wrapper {
            padding: 40px;
            flex-grow: 1;
        }
        .card-custom {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(16, 54, 125, 0.05);
            margin-bottom: 30px;
            border: none;
        }
        .text-custom {
            color: #10367d !important;
        }
        footer {
            background: white;
            padding: 20px 40px;
            border-top: 1px solid #eee;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>EyeCare</h2>
        <a href="dashboard.php"><i class="bi bi-house-door me-2"></i> Dashboard</a>
        <a href="cek_mata.php"><i class="bi bi-eye me-2"></i> Cek Mata</a>
        <a href="riwayat.php" class="active"><i class="bi bi-clock-history me-2"></i> Riwayat & Statistik</a>
        <a href="profile.php"><i class="bi bi-person me-2"></i> Profile</a>
        <a href="logout.php" style="margin-top: 50px; color: #ffbcbc;"><i class="bi bi-box-arrow-right me-2"></i> Keluar</a>
    </div>

    <div class="main">
        <div class="top-nav">
            <div class="d-flex align-items-center">
                <i class="bi bi-list toggle-btn" onclick="toggleSidebar()"></i>
                <h4 class="m-0 fw-bold" style="color: #10367d;">Riwayat & Statistik</h4>
            </div>
            <div class="user-profile-nav">
                <span>Halo, <strong><?= htmlspecialchars($nama_tampil) ?></strong></span>
                <img src="assets/<?= $foto_tampil ?>" onerror="this.src='assets/pict1.jpg'">
            </div>
        </div>

        <div class="content-wrapper">
            <div class="card-custom">
                <h5 class="fw-bold mb-3 text-custom">Grafik Penggunaan Layar (7 Hari Terakhir)</h5>
                <canvas id="screenChart" style="max-height: 300px;"></canvas>
            </div>

            <div class="card-custom">
                <h5 class="fw-bold mb-3 text-custom">Riwayat Screen Time</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu Mulai</th>
                                <th>Durasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($query_screen)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d M Y', strtotime($row['waktu_mulai'])); ?></td>
                                <td><?= date('H:i', strtotime($row['waktu_mulai'])); ?></td>
                                <td><?= $row['durasi_menit']; ?> Menit</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="border-radius: 20px;">
                                        <form method="POST">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold text-custom">Edit Durasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Durasi (Menit)</label>
                                                    <input type="number" name="durasi" class="form-control shadow-sm" value="<?= $row['durasi_menit']; ?>" required style="border-radius: 10px;">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="submit" name="update" class="btn btn-primary px-4" style="background: #10367d; border-radius: 10px;">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-custom">
                <h5 class="fw-bold mb-3 text-custom">Riwayat Cek Mata</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Hasil</th>
                                <th>Saran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no_cek = 1;
                            if(mysqli_num_rows($query_cek) > 0):
                                while($row_cek = mysqli_fetch_assoc($query_cek)): 
                                    $badge_color = "bg-secondary";
                                    if($row_cek['kategori'] == "Mata Sehat") $badge_color = "bg-success";
                                    elseif($row_cek['kategori'] == "Mulai Lelah") $badge_color = "bg-warning text-dark";
                                    elseif($row_cek['kategori'] == "Kelelahan Parah") $badge_color = "bg-danger";
                            ?>
                            <tr>
                                <td><?= $no_cek++; ?></td>
                                <td><?= date('d M Y', strtotime($row_cek['tanggal'])); ?></td>
                                <td><span class="badge <?= $badge_color; ?>"><?= $row_cek['kategori']; ?></span></td>
                                <td><small><?= $row_cek['saran']; ?></small></td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat cek mata.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">© 2026 EyeCare, Inc</span>
                <div class="d-flex gap-3">
                    <a href="#" class="text-muted"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-muted"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-muted"><i class="bi bi-twitter-x fs-5"></i></a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main').classList.toggle('expanded');
        }

        const ctx = document.getElementById('screenChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    label: 'Durasi (Menit)',
                    data: <?= json_encode($totals); ?>,
                    backgroundColor: '#10367d',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>