-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Bulan Mei 2026 pada 21.40
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eyecare_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `profiles`
--

CREATE TABLE `profiles` (
  `id_profile` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_profil` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `foto` varchar(50) DEFAULT 'pict1.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profiles`
--

INSERT INTO `profiles` (`id_profile`, `id_user`, `nama_profil`, `tanggal_lahir`, `foto`) VALUES
(1, 3, 'Charles', '1997-10-16', 'pict7.jpg'),
(2, 4, '   ', '2007-03-14', 'pict1.jpg'),
(4, 3, 'calla', '2020-04-30', 'pict5.jpg'),
(5, 5, 'George Russell', '1998-01-21', 'pict1.jpg'),
(6, 6, 'Max Verstappen', '1995-04-05', 'pict1.jpg'),
(7, 7, 'oscar ', '2004-06-09', 'pict7.jpg'),
(8, 8, 'Marc Marquez', '1989-10-17', 'pict1.jpg'),
(9, 3, 'sza', '2004-06-08', 'pict2.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `screentime`
--

CREATE TABLE `screentime` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_profile` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `durasi_menit` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `status` enum('berjalan','selesai') DEFAULT 'selesai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `screentime`
--

INSERT INTO `screentime` (`id`, `id_user`, `id_profile`, `tanggal`, `durasi_menit`, `kategori`, `waktu_mulai`, `status`) VALUES
(1, 2, NULL, '2026-04-23', 16, '', '2026-04-23 02:55:43', 'selesai'),
(2, 2, NULL, '2026-04-23', 16, '', '2026-04-23 02:55:54', 'selesai'),
(3, 2, NULL, '2026-04-23', 16, '', '2026-04-23 02:56:11', 'selesai'),
(4, 2, NULL, '2026-04-23', 0, '', '2026-04-23 03:13:41', 'selesai'),
(5, 2, NULL, '2026-04-23', 0, '', '2026-04-23 03:17:07', 'selesai'),
(6, 2, NULL, '2026-04-23', 0, '', '2026-04-23 03:18:04', 'selesai'),
(10, 3, 1, '2026-04-23', 50, '', '2026-04-23 03:33:47', 'selesai'),
(12, 3, NULL, '2026-05-01', 50, '', '2026-05-01 14:05:32', 'selesai'),
(13, 3, NULL, '2026-05-01', 1, '', '2026-05-01 18:37:48', 'selesai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama_lengkap`, `username`, `email`, `tgl_lahir`, `password`) VALUES
(2, 'Andrea Kimi Antonelli', 'kimi25', 'kimimercy@gmail.com', '2006-08-25', '11111'),
(3, 'Charles Leclerc', 'banana_leclerc', 'leclerc16@gmail.com', '1997-10-16', '123456'),
(4, '   ', '   ', 'whoknows@gmail.com', '2007-03-14', '00000'),
(5, 'George Russell', 'russel', 'grussel77@gmail.com', '1998-01-21', '77777'),
(6, 'Max Verstappen', 'redb', 'maxv@gmail.com', '1995-04-05', '88888'),
(7, 'oscar ', 'osc', 'oscar@gmail.com', '2004-06-09', '34567'),
(8, 'Marc Marquez', 'Marc', 'marquez@gmail.com', '1989-10-17', '67890');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id_profile`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `screentime`
--
ALTER TABLE `screentime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idUser` (`id_user`),
  ADD KEY `fk_profile` (`id_profile`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usn` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id_profile` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `screentime`
--
ALTER TABLE `screentime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `screentime`
--
ALTER TABLE `screentime`
  ADD CONSTRAINT `fk_idUser` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profile` FOREIGN KEY (`id_profile`) REFERENCES `profiles` (`id_profile`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
