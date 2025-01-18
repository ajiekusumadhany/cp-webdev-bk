-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 07:04 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `daftar_poli`
--

CREATE TABLE `daftar_poli` (
  `id` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `keluhan` text NOT NULL,
  `no_antrian` int(11) DEFAULT NULL,
  `status_periksa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_poli`
--

INSERT INTO `daftar_poli` (`id`, `id_pasien`, `id_jadwal`, `keluhan`, `no_antrian`, `status_periksa`) VALUES
(17, 82, 2, 'sakit gigi', 1, 0),
(18, 82, 1, 'hhh', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `detail_periksa`
--

CREATE TABLE `detail_periksa` (
  `id` int(11) NOT NULL,
  `id_periksa` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(50) NOT NULL,
  `id_poli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `alamat`, `no_hp`, `id_poli`) VALUES
(22, 'inidokter', 'semarang', '082241989089', 1),
(25, 'Dr. Stephen Strange', 'Jakarta', '081234567890', 1),
(26, 'Dr. Natasha Romanoff', 'Bandung', '082345678901', 1),
(27, 'Dr. Bruce Banner', 'Bogor', '083456789012', 2),
(28, 'Dr. Peter Venkman', 'Surabaya', '084567890123', 1),
(29, 'Dr. Tony Stark', 'Yogyakarta', '085678901234', 2),
(30, 'Dr. Diana Prince', 'Semarang', '086789012345', 1),
(32, 'Dr. Matt Murdock', 'Malang', '088901234567', 1),
(33, 'Dr. Barry Allen', 'Jakarta', '089012345678', 2),
(34, 'Dr. Wade Wilson', 'Jakarta', '090123456789', 1),
(35, 'Dr. Jessica Jones', 'Jakarta', '091234567890', 2),
(36, 'Dr. Frank Castle', 'Jakarta', '092345678901', 1),
(37, 'Dr. Stone', 'kudus', '81256829034', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_periksa`
--

CREATE TABLE `jadwal_periksa` (
  `id` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_periksa`
--

INSERT INTO `jadwal_periksa` (`id`, `id_dokter`, `hari`, `jam_mulai`, `jam_selesai`, `status`) VALUES
(1, 22, 'Senin', '20:00:00', '23:00:00', 'Y'),
(2, 22, 'Selasa', '21:24:00', '23:26:00', 'Y'),
(4, 22, 'Rabu', '21:30:00', '23:27:00', 'Y'),
(5, 25, 'Selasa', '01:14:00', '02:20:00', 'N'),
(6, 22, 'Jumat', '07:00:00', '09:00:00', 'N'),
(7, 22, 'Rabu', '10:00:00', '12:00:00', 'Y'),
(8, 22, 'Jumat', '09:30:00', '12:30:00', 'N'),
(9, 22, 'Sabtu', '09:30:00', '10:30:00', 'Y'),
(10, 22, 'Sabtu', '15:00:00', '17:00:00', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` text NOT NULL,
  `tgl_konsultasi` datetime NOT NULL DEFAULT current_timestamp(),
  `id_pasien` int(1) NOT NULL,
  `id_dokter` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id`, `subject`, `pertanyaan`, `jawaban`, `tgl_konsultasi`, `id_pasien`, `id_dokter`) VALUES
(2, 'Tesa', 'halo coba?', '', '2025-01-18 10:35:46', 82, 37),
(3, 'Tes Aja', 'halo coba 2?', '', '2025-01-18 10:53:24', 82, 32),
(4, 'aaa', 'hi', 'aavfg', '2025-01-18 11:01:31', 82, 22),
(5, 'halo mau tanya', 'jadi gini...', '', '2025-01-18 12:34:21', 82, 22);

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(50) NOT NULL,
  `kemasan` varchar(35) DEFAULT NULL,
  `harga` int(10) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `kemasan`, `harga`) VALUES
(1, 'Paracetamol', 'Tablet', 5000),
(2, 'Ibuprofen', 'Kapsul', 3000),
(3, 'Aspirin', 'Tablet', 2000),
(4, 'Vitamin C', 'Kapsul', 1000),
(5, 'Antibiotik', 'Kapsul', 8000),
(6, 'Penisilin', 'Kapsul', 6000),
(7, 'Erythromycin', 'Kapsul', 7000),
(9, 'Ciprofloxacin', 'Tablet', 4000),
(10, 'Doxycycline', 'Kapsul', 3000),
(11, 'Metronidazol', 'Tablet', 2000),
(12, 'Kloramfenikol', 'Kapsul', 1000),
(13, 'Sulfadiazin', 'Tablet', 8000),
(14, 'Trimetoprim', 'Kapsul', 6000),
(15, 'Fenobarbital', 'Tablet', 5000),
(16, 'Diazepam', 'Tablet', 4000),
(17, 'Lorazepam', 'Tablet', 3000),
(19, 'Klonazepam', 'Tablet', 1000),
(20, 'Karbamazepin', 'Tablet', 8000),
(21, 'Fenitoin', 'Tablet', 6000),
(22, 'Valproat', 'Tablet', 5000),
(23, 'Lamotrigine', 'Tablet', 4000),
(24, 'Topiramat', 'Tablet', 3000),
(25, 'Levetirasetam', 'Tablet', 2000),
(26, 'Zonisamid', 'Tablet', 1000),
(27, 'Pregabalin', 'Tablet', 8000),
(28, 'Gabapentin', 'Tablet', 6000),
(29, 'Ketoprofen', 'Tablet', 5000),
(30, 'Naproxen', 'Tablet', 4000),
(31, 'Ibuprofen', 'Tablet', 3000),
(33, 'Paracetamol', 'Tablet', 1000),
(34, 'Kodein', 'Tablet', 8000),
(35, 'Morfina', 'Tablet', 6000),
(36, 'Fentanil', 'Tablet', 5000),
(37, 'Oksikodon', 'Tablet', 4000),
(38, 'Hidromorfon', 'Tablet', 3000),
(39, 'Metadon', 'Tablet', 2000),
(40, 'Buprenorfin', 'Tablet', 1000),
(41, 'Nalokson', 'Tablet', 8000),
(42, 'Naltrexon', 'Tablet', 6000),
(43, 'Klorfeniramin', 'Tablet', 5000),
(44, 'Difenhidramin', 'Tablet', 4000),
(45, 'Loratadin', 'Tablet', 3000),
(46, 'Cetirizin', 'Tablet', 2000),
(47, 'Feksifenadin', 'Tablet', 1000),
(48, 'Desloratadin', 'Tablet', 8000),
(49, 'Levokabastin', 'Tablet', 6000),
(50, 'Azelastin', 'Tablet', 5000),
(51, 'Olopatadin', 'Tablet', 4000),
(55, 'obat aja', 'tablet', 9000),
(56, 'obat apa aja', 'obat tes', 9000),
(58, 'yyy', 'tablet', 1000),
(59, 'obat aja', 'tablet', 9000);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_ktp` varchar(255) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  `no_rm` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_ktp`, `no_hp`, `no_rm`) VALUES
(80, 'ajiekusumadhany', 'kudus', '1234567890987890', '082241929544', '202412-001'),
(82, 'inipasien', 'semarang', '9086754890764678', '082241929544', '202412-002'),
(102, 'Alice Wonderland', 'Bogor', '3456789012345678', '083456789012', '202412-003'),
(103, 'Bob Builder', 'Surabaya', '4567890123456789', '084567890123', '202412-004'),
(104, 'Harry Potter', 'Yogyakarta', '5678901234567890', '085678901234', '202412-005'),
(105, 'Katniss Everdeen', 'Semarang', '6789012345678901', '086789012345', '202412-006'),
(106, 'Frodo Baggins', 'Bali', '7890123456789012', '087890123456', '202412-007'),
(107, 'Sherlock Holmes', 'Malang', '8901234567890123', '088901234567', '202412-008'),
(108, 'Wonder Woman', 'Jakarta', '9012345678901234', '089012345678', '202412-009'),
(109, 'Tony Stark', 'Jakarta', '0123456789012345', '090123456789', '202412-010'),
(110, 'Bruce Wayne', 'Jakarta', '1234567890123450', '091234567890', '202412-011'),
(111, 'Peter Parker', 'Jakarta', '2345678901234561', '092345678901', '202412-012'),
(112, 'Clark Kent', 'Jakarta', '3456789012345672', '093456789012', '202412-013'),
(113, 'Daenerys Targaryen', 'Bali', '4567890123456783', '094567890123', '202412-014'),
(114, 'Luke Skywalker', 'Jakarta', '5678901234567894', '095678901234', '202412-015'),
(115, 'Hermione Granger', 'Bandung', '6789012345678905', '096789012345', '202412-016'),
(116, 'Dumbledore', 'Yogyakarta', '7890123456789016', '097890123456', '202412-017'),
(117, 'Katara', 'Semarang', '8901234567890127', '098901234567', '202412-018'),
(118, 'Gollum', 'Bogor', '9012345678901238', '099012345678', '202412-019'),
(119, 'Simba', 'Bali', '0123456789012349', '100123456789', '202412-020'),
(120, 'asdwef', 'Kudus, Jawa Tengah, Indonesia', '1234567856', '082241929544', '202412-021'),
(121, 'rtgterg', 'Kudus, Jawa Tengah, Indonesia', '6756', '082241929544', '202412-022');

-- --------------------------------------------------------

--
-- Table structure for table `periksa`
--

CREATE TABLE `periksa` (
  `id` int(11) NOT NULL,
  `id_daftar_poli` int(11) NOT NULL,
  `tgl_periksa` datetime NOT NULL,
  `catatan` text NOT NULL,
  `biaya_periksa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poli`
--

CREATE TABLE `poli` (
  `id` int(11) NOT NULL,
  `nama_poli` varchar(25) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli`
--

INSERT INTO `poli` (`id`, `nama_poli`, `keterangan`) VALUES
(1, 'Poli Umum', 'Dokter Umum'),
(2, 'Poli Gigi', 'Dokter Gigi'),
(9, 'Poli THT', 'Pelayanan kesehatan untuk telinga, hidung, dan tenggorokan.'),
(10, 'Poli Saraf', 'Pelayanan kesehatan untuk masalah saraf.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_periksa` (`id_periksa`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indexes for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `periksa`
--
ALTER TABLE `periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_daftar_poli` (`id_daftar_poli`);

--
-- Indexes for table `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `periksa`
--
ALTER TABLE `periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD CONSTRAINT `daftar_poli_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`),
  ADD CONSTRAINT `daftar_poli_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_periksa` (`id`);

--
-- Constraints for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD CONSTRAINT `detail_periksa_ibfk_1` FOREIGN KEY (`id_periksa`) REFERENCES `periksa` (`id`),
  ADD CONSTRAINT `detail_periksa_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`);

--
-- Constraints for table `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id`);

--
-- Constraints for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD CONSTRAINT `jadwal_periksa_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`);

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`),
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`);

--
-- Constraints for table `periksa`
--
ALTER TABLE `periksa`
  ADD CONSTRAINT `periksa_ibfk_1` FOREIGN KEY (`id_daftar_poli`) REFERENCES `daftar_poli` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
