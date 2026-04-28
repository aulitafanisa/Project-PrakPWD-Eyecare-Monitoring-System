<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EyeCare - Jaga Kesehatan Mata</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            color: #333;
            overflow-x: hidden;
        }

        .navbar {
            background-color: #10367d !important;
            padding: 18px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-link:hover {
            color: white !important;
            opacity: 1;
        }

        .nav-link.active {
            font-weight: 700;
            border-bottom: 2px solid white;
            color: white !important;
        }

        .hero {
            padding: 120px 0;
            background-color: #ffffff;
            position: relative;
        }

        .container-wide {
            max-width: 90%;
            margin: 0 auto;
        }

        .hero-top {
            color: #10367d;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #10367d;
            line-height: 1.1;
            margin-bottom: 25px;
        }

        .hero h1 span {
            color: #74b4da;
            font-style: italic;
        }

        .img-container {
            position: relative;
            display: inline-block;
            z-index: 1;
        }

        .img-container::before {
            content: "";
            position: absolute;
            width: 130%;
            height: 130%;
            background: radial-gradient(circle, rgba(116, 180, 218, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            top: -15%;
            left: -15%;
            z-index: -1;
        }

        .img-blob {
            width: 100%;
            max-width: 520px;
            border-radius: 62% 38% 52% 48% / 53% 45% 55% 47%;
            box-shadow: 0 30px 60px rgba(16, 54, 125, 0.2);
            border: 10px solid rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 2;
        }


        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 20px;
            border-radius: 18px;
            position: absolute;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 10;
            animation: float 4s ease-in-out infinite;
        }

        .card-score {
            bottom: 15%;
            left: -30px;
            border-left: 5px solid #10367d;
        }

        .card-alert {
            top: 15%;
            right: -20px;
            border-left: 5px solid #10367d;
            animation-delay: 1s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .btn-dark-blue {
            background-color: #10367d;
            color: white;
            padding: 14px 35px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-dark-blue:hover {
            background-color: #0a2556;
            transform: translateY(-3px);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid #10367d;
            color: #10367d;
            padding: 14px 35px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-outline-custom:hover {
            background: #10367d;
            color: white;
        }

        .features {
            background-color: #ebebeb;
            padding: 100px 0;
            border-top-left-radius: 80px;
            border-top-right-radius: 80px;
        }

        .card-feature {
            border: none;
            border-radius: 30px;
            padding: 45px;
            transition: 0.4s;
            height: 100%;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .card-feature:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .icon-img {
            width: 70px;
            height: 70px;
            margin-bottom: 30px;
            object-fit: contain;
        }

        .about {
            padding: 120px 0;
        }

        .about-img-wrapper {
            position: relative;
        }

        .about-img {
            border-radius: 40px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-lg-5">
            <a class="navbar-brand d-flex align-items-center" href="#">
                EyeCare
            </a>
            <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#about">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#footer">Kontak</a></li>
                </ul>
                <div class="d-flex gap-3">
                    <a href="login.php" class="btn btn-outline-light px-4 border-2 fw-bold">Login</a>
                    <a href="register.php" class="btn btn-light px-4 fw-bold" style="color: #10367d">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="container-wide">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <p class="hero-top">Pantau • Analisis • Jaga</p>
                    <h1>Jaga Kesehatan <br>Mata Anda <span>Setiap Hari</span></h1>
                    <p class="text-muted fs-5 mb-5 pe-lg-5">EyeCare membantu Anda memantau screen time harian, menganalisis kondisi mata berdasarkan riwayat, serta memberikan solusi cerdas untuk penglihatan yang lebih tajam.</p>

                    <div class="d-flex gap-3">
                        <button class="btn btn-dark-blue shadow-lg">Mulai Sekarang</button>
                        <button class="btn btn-outline-custom">Pelajari Lebih Lanjut</button>
                    </div>

                    <div class="mt-5 d-flex align-items-center gap-2 text-muted small">
                        <span style="color: #74b4da; font-size: 1.2rem;">✔</span>
                        Data kesehatan Anda dienkripsi secara aman dan pribadi.
                    </div>
                </div>

                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <div class="img-container">
                        <div class="glass-card card-score d-none d-md-block">
                            <div class="d-flex align-items-center gap-3">
                                <div style="background: #74b4da; width: 12px; height: 12px; border-radius: 50%;"></div>
                                <div class="text-start">
                                    <p class="mb-0 small text-muted">Eye Comfort</p>
                                    <p class="mb-0 fw-bold" style="color: #10367d;">Optimal</p>
                                </div>
                            </div>
                        </div>

                        <img src="assets/eye.jpg" alt="Eye Health" class="img-blob">

                        <div class="glass-card card-alert d-none d-md-block">
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size: 1.2rem;">🔔</span>
                                <p class="mb-0 small fw-bold" style="color: #10367d;">Waktunya Istirahat!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="features" id="features">
        <div class="container-wide">
            <div class="row mb-5 align-items-end">
                <div class="col-md-7">
                    <p class="text-uppercase fw-bold mb-2" style="color: #10367d; letter-spacing: 2px; font-size: 0.8rem;">Fitur Utama</p>
                    <h2 class="fw-bold display-5" style="color: #10367d;">Teknologi Cerdas Untuk <br>Mata Digital Anda</h2>
                </div>
                <div class="col-md-5 text-md-end">
                    <p class="text-muted">Gunakan berbagai alat kami untuk menjaga produktivitas <br> tanpa mengorbankan kesehatan mata.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-feature">
                        <img src="assets/time-icon.png" class="icon-img" alt="Time Icon">
                        <h4 class="feature-title">Tracking Screen Time</h4>
                        <p class="text-muted small">Monitor seberapa lama Anda menatap layar secara real-time dengan laporan mingguan yang mendalam.</p>
                        <a href="#" class="link-more mt-auto">Mulai Track</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-feature">
                        <img src="assets/eye-icon.png" class="icon-img" alt="Eye Icon">
                        <h4 class="feature-title">Analisis Kesehatan</h4>
                        <p class="text-muted small">Dapatkan skor kesehatan mata instan berdasarkan keluhan harian dan intensitas cahaya layar Anda.</p>
                        <a href="#" class="link-more mt-auto">Cek Sekarang</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-feature">
                        <img src="assets/calender-icon.jpg" class="icon-img" alt="Calendar Icon">
                        <h4 class="feature-title">Riwayat Harian</h4>
                        <p class="text-muted small">Simpan setiap perkembangan kesehatan mata Anda untuk konsultasi medis yang lebih akurat di masa depan.</p>
                        <a href="#" class="link-more mt-auto">Cek History</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container-wide">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="pe-lg-5">
                        <p class="fw-bold small text-uppercase mb-2" style="color: #74b4da;">Tentang EyeCare</p>
                        <h2 class="fw-bold display-6 mb-4" style="color: #10367d;">Kesehatan Mata Adalah <br>Investasi Masa Depan</h2>
                        <p class="text-muted fs-5">Kami mengerti bahwa di dunia modern, layar adalah bagian dari hidup. Misi kami adalah menjadi asisten pribadi yang memastikan mata Anda tetap sehat meski bekerja berjam-jam.</p>

                        <div class="row mt-5">
                            <div class="col-sm-6 mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-3 rounded-circle" style="background: #ebebeb;">🛡</div>
                                    <span class="fw-bold" style="color: #10367d;">Keamanan Data</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-3 rounded-circle" style="background: #ebebeb;">⚡</div>
                                    <span class="fw-bold" style="color: #10367d;">Respon Cepat</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-img-wrapper">
                        <img src="assets/eye2.jpg" class="about-img" alt="About EyeCare">
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>