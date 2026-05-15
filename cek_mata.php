<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
$kategori = "";
$hasil = "";
$warna = "";

if(isset($_POST['submit'])) {
    $total = 0;
    foreach ($_POST['q'] as $jawaban) {
        $total += (int)$jawaban;
    }

    if ($total <= 10) {
        $kategori = "Mata Sehat";
        $warna = "success";
        $hasil = "Kondisi mata kamu masih aman. Pertahankan kebiasaan baik.";
    } elseif ($total <= 18) {
        $kategori = "Mulai Lelah";
        $warna = "warning";
        $hasil = "Mata kamu mulai lelah. Kurangi screen time dan istirahatkan mata.";
    } else {
        $kategori = "Kelelahan Parah";
        $warna = "error";
        $hasil = "Mata kamu sudah sangat lelah. Segera istirahatkan mata 15-30 menit.";
    }

    $tgl_sekarang = date('Y-m-d H:i:s');
    mysqli_query($conn, "INSERT INTO cek_mata_history (id_user, tanggal, kategori, saran) 
                         VALUES ('$id_user', '$tgl_sekarang', '$kategori', '$hasil')");
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cek Mata</title>

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
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(16,54,125,0.15);
}

.card-title {
    font-weight: 700;
    color: #10367d;
}

.form-check {
    padding: 8px 10px;
    border-radius: 10px;
    transition: 0.2s;
}

.form-check:hover {
    background-color: #f1f6ff;
}

.form-check-input:checked {
    background-color: #10367d;
    border-color: #10367d;
}

.btn-primary {
    background: linear-gradient(135deg, #10367d, #74b4da);
    border: none;
    border-radius: 12px;
}
</style>

</head>

<body>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card p-4">
<div class="card-body">

<h4 class="card-title text-center mb-3">Cek Kondisi Mata</h4>
<p class="text-center text-muted mb-4">Jawab sesuai kondisi kamu sekarang.</p>

<form method="POST">

<?php
$pertanyaan = [
    "Mata terasa kering atau perih",
    "Pandangan mulai buram setelah lama menatap layar",
    "Sering berkedip atau mata terasa berat",
    "Mata terasa tegang atau sakit",
    "Sakit kepala setelah melihat layar lama"
];

$opsi = ["Tidak Pernah","Jarang","Kadang","Sering","Selalu"];

foreach ($pertanyaan as $i => $p):
?>

<div class="mb-3">
<label class="form-label fw-semibold"><?= ($i+1) . ". " . $p ?></label>

<?php foreach ($opsi as $index => $text): ?>
<div class="form-check">
    <input class="form-check-input" type="radio"
           name="q[<?= $i ?>]"
           value="<?= $index+1 ?>" required>
    <label class="form-check-label"><?= $text ?></label>
</div>
<?php endforeach; ?>

</div>

<?php endforeach; ?>

<button type="submit" name="submit" class="btn btn-primary w-100">
    Lihat Hasil
</button>

</form>

</div>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($hasil): ?>
<script>
Swal.fire({
    title: "<?= $kategori ?>",
    text: "<?= $hasil ?>",
    icon: "<?= $warna ?>",
    confirmButtonColor: "#10367d"
}).then(() => {
    window.location.href = "dashboard.php";
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