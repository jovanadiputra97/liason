-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2018 at 08:32 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ta_liason`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `no` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `account_level_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`no`, `username`, `password`, `account_level_no`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1),
(2, 'sales', '9ed083b1436e5f40ef984b28255eef18', 2),
(3, 'gudang', '202446dd1d6028084426867365b0c7a1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `account_level`
--

CREATE TABLE `account_level` (
  `no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_level`
--

INSERT INTO `account_level` (`no`, `name`) VALUES
(1, 'Admin'),
(3, 'Gudang'),
(2, 'Sales');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `no` int(11) NOT NULL,
  `page_group_no` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `alias` varchar(50) DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `sortno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`no`, `page_group_no`, `icon`, `alias`, `filename`, `name`, `parent`, `sortno`) VALUES
(1, 1, 'fa-id-card-o', NULL, NULL, 'Pelanggan', NULL, 2),
(2, 1, NULL, 'pelanggan-manage', 'pelanggan-manage.php', '+ Tambah Pelanggan', 1, 1),
(3, 1, NULL, 'pelanggan-lihat', 'pelanggan-lihat.php', 'Lihat Pelanggan', 1, 2),
(4, 1, 'fa-archive', NULL, NULL, 'Stok Barang', NULL, 3),
(5, 1, NULL, 'barang-manage', 'barang-manage.php', '+ Tambah Barang', 4, 1),
(6, 1, NULL, 'barang-lihat', 'barang-lihat.php', 'Lihat Barang', 4, 2),
(7, 1, NULL, 'historihargabarang-lihat', 'historihargabarang-lihat.php', 'Lihat History Harga Barang', 4, 3),
(8, 1, NULL, 'stok-manage', 'stok-manage.php', '+ Tambah Stok', 4, 4),
(9, 1, NULL, 'stok-lihat', 'stok-lihat.php', 'Lihat Stok Saat Ini', 4, 5),
(10, 1, NULL, 'historistok-lihat', 'historistok-lihat.php', 'Lihat History Stok', 4, 6),
(11, 1, NULL, 'penyesuaianstok-manage', 'penyesuaianstok-manage.php', 'Penyesuaian Stok', 4, 7),
(12, 1, 'icon-home', 'dashboard', 'dashboard.php', 'Home', NULL, 1),
(13, 1, 'fa-money', NULL, NULL, 'Faktur Penjualan', NULL, 4),
(14, 1, NULL, 'faktur-manage', 'faktur-manage.php', '+ Buat Faktur', 13, 1),
(15, 1, NULL, 'faktur-lihat', 'faktur-lihat.php', 'Lihat Faktur & Pembayaran', 13, 2),
(16, 2, 'fa-print', 'multipleprint', 'multipleprint.php', 'Multiple Invoice & Surat Jalan', NULL, 5),
(17, 3, 'fa-unlock-alt', NULL, NULL, 'Jenis Akses', NULL, 6),
(18, 3, NULL, 'jenisakses-manage', 'jenisakses-manage.php', '+ Tambah Jenis Akses', 17, 1),
(19, 3, NULL, 'jenisakses-lihat', 'jenisakses-lihat.php', 'Lihat Jenis Akses', 17, 2),
(20, 3, 'fa-address-book-o', NULL, NULL, 'User / Karyawan', NULL, 8),
(21, 3, NULL, 'userkaryawan-manage', 'userkaryawan-manage.php', '+ Tambah User / Karyawan', 20, 1),
(22, 3, NULL, 'userkaryawan-lihat', 'userkaryawan-lihat.php', 'Lihat User / Karyawan', 20, 2),
(23, 3, 'fa-check-square-o', NULL, NULL, 'Jenis Barang', NULL, 9),
(24, 3, NULL, 'jenisbarang-manage', 'jenisbarang-manage.php', '+ Jenis Barang', 23, 1),
(25, 3, NULL, 'jenisbarang-lihat', 'jenisbarang-lihat.php', 'Lihat Jenis Barang', 23, 2),
(26, 3, 'fa-arrows-h', NULL, NULL, 'Jenis Ukuran', NULL, 10),
(27, 3, NULL, 'jenisukuran-manage', 'jenisukuran-manage.php', '+ Jenis Ukuran', 26, 1),
(28, 3, NULL, 'jenisukuran-lihat', 'jenisukuran-lihat.php', 'Lihat Jenis Ukuran', 26, 2),
(29, 3, 'fa-users', NULL, NULL, 'Kategori User', NULL, 7),
(30, 3, NULL, 'kategoriuser-manage', 'kategoriuser-manage.php', '+ Tambah Kategori User', 29, 1),
(31, 3, NULL, 'kategoriuser-lihat', 'kategoriuser-lihat.php', 'Lihat Kategori User', 29, 2);

-- --------------------------------------------------------

--
-- Table structure for table `page_group`
--

CREATE TABLE `page_group` (
  `no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sortno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `page_group`
--

INSERT INTO `page_group` (`no`, `name`, `sortno`) VALUES
(1, 'MAIN', 1),
(2, 'CETAK', 2),
(3, 'MASTERDATA', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `account_level`
--
ALTER TABLE `account_level`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `alias` (`alias`);

--
-- Indexes for table `page_group`
--
ALTER TABLE `page_group`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `account_level`
--
ALTER TABLE `account_level`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `page_group`
--
ALTER TABLE `page_group`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
