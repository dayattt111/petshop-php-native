-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2025 at 08:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rina212238`
--

-- --------------------------------------------------------

--
-- Table structure for table `aksesoris_212238`
--

CREATE TABLE `aksesoris_212238` (
  `id_212238` varchar(36) NOT NULL,
  `nama_aksesoris_212238` varchar(100) DEFAULT NULL,
  `harga_212238` int(11) DEFAULT NULL,
  `stok_212238` int(11) DEFAULT NULL,
  `foto_212238` varchar(100) DEFAULT NULL,
  `deskripsi_212238` text NOT NULL,
  `gambar_212238` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aksesoris_212238`
--

INSERT INTO `aksesoris_212238` (`id_212238`, `nama_aksesoris_212238`, `harga_212238`, `stok_212238`, `foto_212238`, `deskripsi_212238`, `gambar_212238`) VALUES
('12', '12', 12, 3, NULL, '12', 'download.jpg'),
('13', '13', 13, 0, NULL, '13', 'download (1).jpg'),
('14', '14', 14, 7, NULL, '14', 'download (2).jpg'),
('15', '15', 15, 15, NULL, '15', 'download (3).jpg'),
('16', '16', 16, 7, NULL, '16', 'download (4).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pakan_212238`
--

CREATE TABLE `pakan_212238` (
  `id_212238` varchar(36) NOT NULL,
  `nama_pakan_212238` varchar(100) DEFAULT NULL,
  `harga_212238` int(11) DEFAULT NULL,
  `stok_212238` int(11) DEFAULT NULL,
  `foto_212238` varchar(100) DEFAULT NULL,
  `deskripsi_212238` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pakan_212238`
--

INSERT INTO `pakan_212238` (`id_212238`, `nama_pakan_212238`, `harga_212238`, `stok_212238`, `foto_212238`, `deskripsi_212238`) VALUES
('685054f4520cb', '21', 21, 16, 'download (6).jpg', '21'),
('68505501e6a54', '22', 22, 22, 'download (7).jpg', '22'),
('6850550f049b2', '23', 23, 23, 'download (8).jpg', '23'),
('6850551dc2b7d', '25', 25, 25, 'download (9).jpg', '25');

-- --------------------------------------------------------

--
-- Table structure for table `pemeriksaan_212238`
--

CREATE TABLE `pemeriksaan_212238` (
  `id_212238` varchar(36) NOT NULL,
  `id_user_212238` varchar(36) DEFAULT NULL,
  `keluhan_212238` text DEFAULT NULL,
  `diagnosa_212238` text DEFAULT NULL,
  `tgl_212238` date DEFAULT NULL,
  `status_bayar_212238` enum('belum','sudah') DEFAULT 'belum',
  `metode_bayar_212238` enum('cod','transfer','qris') DEFAULT 'cod',
  `id_dokter_212238` varchar(36) DEFAULT NULL,
  `resep_212238` text DEFAULT NULL,
  `tindakan_212238` text DEFAULT NULL,
  `catatan_212238` text DEFAULT NULL,
  `status_212238` enum('baru','selesai','batal') DEFAULT 'baru',
  `total_212238` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemeriksaan_212238`
--

INSERT INTO `pemeriksaan_212238` (`id_212238`, `id_user_212238`, `keluhan_212238`, `diagnosa_212238`, `tgl_212238`, `status_bayar_212238`, `metode_bayar_212238`, `id_dokter_212238`, `resep_212238`, `tindakan_212238`, `catatan_212238`, `status_212238`, `total_212238`) VALUES
('periksa_6851b471a3859', '22', 'sakit perut dan muntah muntah, suka rontok bulunya', 'sakit perut', '2025-06-17', 'sudah', 'cod', '', 'makan parasatamol', 'mandi setiap pagi', 'jagnan lupa di bersihkan kandangnya', 'selesai', 50000);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_212238`
--

CREATE TABLE `pesanan_212238` (
  `id_212238` varchar(36) NOT NULL,
  `id_user_212238` varchar(36) DEFAULT NULL,
  `jenis_212238` enum('pakan','aksesoris') DEFAULT NULL,
  `id_barang_212238` varchar(36) DEFAULT NULL,
  `jumlah_212238` int(11) DEFAULT NULL,
  `tgl_212238` date DEFAULT NULL,
  `status_bayar_212238` enum('belum','sudah') DEFAULT 'belum',
  `status_pesanan_212238` enum('pending','diproses','dikirim','selesai','batal') DEFAULT 'pending',
  `alamat_212238` text DEFAULT NULL,
  `catatan_212238` text DEFAULT NULL,
  `metode_bayar_212238` enum('cod','transfer','qris') DEFAULT 'cod',
  `total_harga_212238` int(11) DEFAULT 0,
  `waktu_input_212238` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan_212238`
--

INSERT INTO `pesanan_212238` (`id_212238`, `id_user_212238`, `jenis_212238`, `id_barang_212238`, `jumlah_212238`, `tgl_212238`, `status_bayar_212238`, `status_pesanan_212238`, `alamat_212238`, `catatan_212238`, `metode_bayar_212238`, `total_harga_212238`, `waktu_input_212238`) VALUES
('6851b7f452b4d', '22', 'aksesoris', '14', 3, '2025-06-17', 'sudah', 'dikirim', '3232', '232', 'transfer', 42, '2025-06-17 20:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `users_212238`
--

CREATE TABLE `users_212238` (
  `id_212238` varchar(36) NOT NULL,
  `nama_212238` varchar(100) DEFAULT NULL,
  `username_212238` varchar(50) DEFAULT NULL,
  `password_212238` varchar(255) DEFAULT NULL,
  `password_plain_212238` varchar(255) DEFAULT NULL,
  `email_212238` varchar(100) DEFAULT NULL,
  `telepon_212238` varchar(20) DEFAULT NULL,
  `foto_212238` varchar(100) DEFAULT NULL,
  `role_212238` enum('admin','dokter','kasir','user') DEFAULT NULL,
  `spesialis_212238` varchar(100) DEFAULT NULL,
  `no_str_212238` varchar(50) DEFAULT NULL,
  `tanggal_lahir_212238` date DEFAULT NULL,
  `jenis_kelamin_212238` enum('L','P') DEFAULT NULL,
  `jadwal_praktek_212238` text DEFAULT NULL,
  `alamat_212238` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_212238`
--

INSERT INTO `users_212238` (`id_212238`, `nama_212238`, `username_212238`, `password_212238`, `password_plain_212238`, `email_212238`, `telepon_212238`, `foto_212238`, `role_212238`, `spesialis_212238`, `no_str_212238`, `tanggal_lahir_212238`, `jenis_kelamin_212238`, `jadwal_praktek_212238`, `alamat_212238`) VALUES
('', 'Muh Iqra Ramadhan Hidayat', 'iqra', '$2y$10$Px53Oa5LnIBUxyC8ePdf2.0O92bu7ltILACpgxf/F1uyjUmuVojIy', '123', '123@gmail.com', '082296015551', 'download (10).jpg', 'user', NULL, NULL, NULL, NULL, NULL, NULL),
('21', '21', '21', '$2y$10$YpQ8rEaqqISFCxHERDOz3u2A0k0HAILmW.mymAF96R6ZS88UpgK5q', '21', '21@gmail.com', '21', 'WhatsApp Image 2025-05-23 at 14.44.55.jpeg', 'user', NULL, NULL, NULL, NULL, NULL, NULL),
('212223', 'Muh. Iqra Ramadhan Hidayat', 'Macan', '$2y$10$c70dhQg2Hddun7nI5VK/xe7b589oM2qfFKcEczLXRjs9Ij8SYCDUm', '123', '88@gmail.com', '082296015551', 'download (10).jpg', 'user', NULL, NULL, NULL, NULL, NULL, NULL),
('22', '22', '22', '$2y$10$m8iXPNp4xIEUeyK.JVHBP.ULmJaIswEafWyvz9CZGsSnAhNWlQhni', '22', '21@gmail.com', '22', 'WhatsApp Image 2025-05-23 at 14.44.55.jpeg', 'user', NULL, NULL, NULL, NULL, NULL, NULL),
('234', 'iqra', 'qw', '$2y$10$l901GeRFZqnbUC9BuwqQ7.7JYz/Y66yZIa15ORQRkqkC6QLK3JziS', 'qw', '88@gmail.com', '082296015551', 'download (10).jpg', 'kasir', NULL, NULL, '2025-06-02', 'L', NULL, 'JLN. TRANS SULAWESI, DUSUN SUMBER HARAPAN, DESA LABONU, KEC. BASIDONDO, KAB. TOLITOLI'),
('88', '88', '88', '$2y$10$tjsSorzoBsw4CbdpagH8B.QTkQjaWcnznDdmTUjFcj.IQKbWNhvdu', '88', '88@gmail.com', '88', 'WhatsApp Image 2025-05-23 at 14.44.55.jpeg', 'user', NULL, NULL, NULL, NULL, NULL, NULL),
('ADM001', 'Admin Utama', 'admin', 'admin123', 'admin123', 'admin@example.com', '08123456789', 'default.png', 'admin', NULL, NULL, NULL, NULL, NULL, NULL),
('qq', 'muh iqra ramadhan hidayattryryr', '676', '$2y$10$imEib2r8yHx.Y5KgoxLw.eG6DaX4Kkj6PXtWoDNU0b3DXvNBiMPr.', '6766', '88@gmail.com', '082296015551', 'WhatsApp Image 2025-05-23 at 14.44.55.jpeg', 'dokter', 'ee', '34', '2025-06-20', 'L', 'ayam geprek', 'JLN. TRANS SULAWESI, DUSUN SUMBER HARAPAN, DESA LABONU, KEC. BASIDONDO, KAB. TOLITOLI');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aksesoris_212238`
--
ALTER TABLE `aksesoris_212238`
  ADD PRIMARY KEY (`id_212238`);

--
-- Indexes for table `pakan_212238`
--
ALTER TABLE `pakan_212238`
  ADD PRIMARY KEY (`id_212238`);

--
-- Indexes for table `pemeriksaan_212238`
--
ALTER TABLE `pemeriksaan_212238`
  ADD PRIMARY KEY (`id_212238`),
  ADD KEY `fk_pemeriksaan_user` (`id_user_212238`),
  ADD KEY `fk_pemeriksaan_dokter` (`id_dokter_212238`);

--
-- Indexes for table `pesanan_212238`
--
ALTER TABLE `pesanan_212238`
  ADD PRIMARY KEY (`id_212238`),
  ADD KEY `id_user_212238` (`id_user_212238`);

--
-- Indexes for table `users_212238`
--
ALTER TABLE `users_212238`
  ADD PRIMARY KEY (`id_212238`),
  ADD UNIQUE KEY `username_212238` (`username_212238`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pemeriksaan_212238`
--
ALTER TABLE `pemeriksaan_212238`
  ADD CONSTRAINT `fk_pemeriksaan_dokter` FOREIGN KEY (`id_dokter_212238`) REFERENCES `users_212238` (`id_212238`),
  ADD CONSTRAINT `fk_pemeriksaan_user` FOREIGN KEY (`id_user_212238`) REFERENCES `users_212238` (`id_212238`),
  ADD CONSTRAINT `pemeriksaan_212238_ibfk_1` FOREIGN KEY (`id_user_212238`) REFERENCES `users_212238` (`id_212238`);

--
-- Constraints for table `pesanan_212238`
--
ALTER TABLE `pesanan_212238`
  ADD CONSTRAINT `pesanan_212238_ibfk_1` FOREIGN KEY (`id_user_212238`) REFERENCES `users_212238` (`id_212238`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
