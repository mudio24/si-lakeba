-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2025 at 08:03 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rusak`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` varchar(6) NOT NULL,
  `n_pelapor` varchar(30) NOT NULL,
  `j_pelapor` varchar(30) NOT NULL,
  `d_pelapor` varchar(30) NOT NULL,
  `n_barang` varchar(30) NOT NULL,
  `ket` varchar(100) NOT NULL,
  `foto_bukti` varchar(255) NOT NULL,
  `estimasi_biaya` decimal(15,2) DEFAULT NULL,
  `bukti_invoice` varchar(255) DEFAULT NULL,
  `status` text NOT NULL,
  `ket_petugas` varchar(100) NOT NULL,
  `tgl_lapor` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `n_pelapor`, `j_pelapor`, `d_pelapor`, `n_barang`, `ket`, `foto_bukti`, `estimasi_biaya`, `bukti_invoice`, `status`, `ket_petugas`, `tgl_lapor`) VALUES
('NP0004', 'Pak Levi', 'Pegawai', 'Sekretariat rumah Tangga', 'Printer', 'Error tidak mau print', '', NULL, NULL, 'Selesai diproses', '-', '2025-11-11'),
('NP0006', 'Dio', 'Mahasiswa PKL UMC', 'Bagian Umum', 'Printer ', 'Macet', '', 1000.00, '2067198200_Screenshot__65_.png', 'Sedang diproses', '-', '2025-11-18'),
('NP0007', 'Dio', 'Mahasiswa PKL UMC', 'Bagian Umum', 'Printer Epson L120', 'Tinta tidak keluar', '2006386872_Screenshot 2025-11-18 091134.png', 170000.00, '2146113295_Screenshot 2025-11-18 091706.png', 'Sedang diproses', 'Ganti Catride', '2025-11-18'),
('NP0008', 'Levi', 'Pegawai', 'Bagian Perlengkapan', 'CPU', 'No Display', '1958489790_Screenshot 2025-11-18 092028.png', NULL, NULL, 'Selesai diproses', '-', '2025-11-18'),
('NP0009', 'riski', 'Mahasiswa PKL UMC', 'Bagian Umum', 'Printer ', 'rusak', '64827864_Screenshot__1_.png', 213123.00, NULL, 'Selesai diproses', 'sudah selesai boss', '2025-11-18'),
('NP0010', 'Firman', 'Pegawai', 'Bagian Umum', 'Mouse', 'no respon kursor nya', '102646323_1.jpeg', NULL, NULL, 'Sedang diproses', '-', '2025-11-20'),
('NP0011', 'Firman', 'Pegawai', 'Bagian Umum', 'ac', 'mcet boss', '1633295339_LOGO_UMC.png', NULL, NULL, 'Sedang diajukan', '-', '2025-11-24');

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan_assignment`
--

CREATE TABLE `pengaduan_assignment` (
  `id` int NOT NULL,
  `pengaduan_id` varchar(6) NOT NULL,
  `assigned_to` varchar(16) NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaduan_assignment`
--

INSERT INTO `pengaduan_assignment` (`id`, `pengaduan_id`, `assigned_to`, `assigned_at`) VALUES
(2, 'NP0010', '1', '2025-11-21 14:02:50'),
(3, 'NP0011', '1', '2025-11-25 10:42:49'),
(4, 'NP0006', '220511121', '2025-11-25 10:43:23'),
(5, 'NP0007', '220511121', '2025-11-25 10:43:27'),
(6, 'NP0004', '220511121', '2025-11-25 10:43:33'),
(7, 'NP0008', '220511121', '2025-11-25 10:43:40'),
(8, 'NP0009', '220511121', '2025-11-25 10:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` varchar(16) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `name`, `img`, `status`) VALUES
('1', 'admin', '$2y$10$vx9rULGqEcbI1khsJ2su8eRHIhZlpmvQW5sPZu3jmk471MtfaNqrm', 'A Levi', '1808027207_LOGO UMC.png', 1),
('202081930', 'bbang', '$2y$10$YG6o6FRSOYa2R3lPrdF8H.qrY5d0I2z0mYY5P4WS7gIiRZLMviRMq', 'Bambang', 'default.jpg', 0),
('220511121', 'dio', '$2y$10$JjWqQBe8uFGiVkSnTyLMLeeicVjI8lutSSF70kcsa2gt409kKeX/O', 'Dio Nugraha', '2000195388_WhatsApp Image 2025-10-04 at 13.34.28_059ee331.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengaduan_assignment`
--
ALTER TABLE `pengaduan_assignment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pengaduan` (`pengaduan_id`),
  ADD KEY `fk_pa_user` (`assigned_to`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaduan_assignment`
--
ALTER TABLE `pengaduan_assignment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengaduan_assignment`
--
ALTER TABLE `pengaduan_assignment`
  ADD CONSTRAINT `fk_pa_pengaduan` FOREIGN KEY (`pengaduan_id`) REFERENCES `pengaduan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pa_user` FOREIGN KEY (`assigned_to`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
