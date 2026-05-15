EyeCare - Sistem Pemantauan Kesehatan Mata & Screen Time
Project Akhir Praktikum Pemrograman Web & Basis Data
oleh:

Aulita Fanisa Ardila/124250067

Charisya Prameswari Alviantika/124250068

Program ini dibuat untuk membantu pengguna menjaga kesehatan mata dari risiko Computer Vision Syndrome (CVS) akibat paparan radiasi layar (screen time) yang berlebihan, dengan menerapkan sistem pelacakan waktu nyata dan kuesioner skrining kesehatan yang terintegrasi dengan basis data.

Fitur / Menu:

1.Halaman Utama (Landing Page): Informasi awal mengenai fungsionalitas aplikasi dan navigasi utama.

2.Autentikasi Pengguna: Registrasi akun baru dan login aman dengan validasi data instan.

3.Panel Kontrol (Dashboard): Widget statistik penggunaan layar, durasi sesi, dan indikator status beban kerja mata.

4.Sesi Pelacakan Aktif (Tracking Screen Time): Timer digital real-time dengan sistem peringatan warna dinamis (Hijau/Kuning/Merah) berdasarkan ambang batas medis aman.

5.Cek Kesehatan Mata: Kuesioner skrining mandiri untuk mendeteksi tingkat kelelahan mata pengguna.

6.Hasil Analisis & Rekomendasi: Skor indeks kesehatan mata, status klinis, dan instruksi terapi mandiri otomatis (seperti metode 20-20-20).

7.Laporan Riwayat (History Tracking): Grafik batang dinamis (Chart.js) dan tabel kronologis untuk memantau tren penggunaan layar mingguan.

8.Manajemen Profil: Pembaruan data personal (Nama & Email) serta kustomisasi avatar profil secara interaktif.

9.Konfirmasi Keluar (Logout Security): Jendela pop-up pengaman (SweetAlert2) untuk mengakhiri sesi dan mengamankan integritas data medis.

Logic Dasar:

Arsitektur Sistem: Menggunakan arsitektur Full-Stack Web berbasis PHP untuk logika server dan Bootstrap untuk antarmuka pengguna yang responsif.

Manajemen Sesi & Autentikasi: Menggunakan PHP Session untuk mengontrol hak akses halaman dashboard dan mengamankan data pengguna yang sedang aktif.

Sistem Basis Data: Menggunakan MySQL sebagai penyimpanan permanen. Saat sesi pelacakan atau cek kesehatan selesai, data otomatis di-insert ke database dan di-load kembali secara dinamis dalam bentuk grafik dan tabel riwayat.

Kelebihan:

Interaktif & Edukatif: Dilengkapi pengingat aktivitas sehat (instruksi berkedip/istirahat) selama pelacakan aktif.

Visualisasi Data Medis: Memudahkan evaluasi kesehatan jangka panjang lewat grafik tren yang intuitif.
