-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2018 at 04:24 PM
-- Server version: 5.5.27
-- PHP Version: 7.1.9

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
  `account_level_no` int(11) NOT NULL,
  `isdelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`no`, `username`, `password`, `account_level_no`, `isdelete`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 0),
(2, 'sales', '9ed083b1436e5f40ef984b28255eef18', 2, 0),
(3, 'gudang', '202446dd1d6028084426867365b0c7a1', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_level`
--

CREATE TABLE `account_level` (
  `no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `isdelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_level`
--

INSERT INTO `account_level` (`no`, `name`, `isdelete`) VALUES
(1, 'Admin', 0),
(2, 'Sales', 0),
(3, 'Gudang', 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_level_page`
--

CREATE TABLE `account_level_page` (
  `account_level_no` int(11) NOT NULL,
  `page_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_level_page`
--

INSERT INTO `account_level_page` (`account_level_no`, `page_no`) VALUES
(1, 2),
(1, 3),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 14),
(1, 15),
(1, 16),
(1, 18),
(1, 19),
(1, 21),
(1, 22),
(1, 24),
(1, 25),
(1, 27),
(1, 28),
(2, 12),
(3, 12),
(3, 19);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `no` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `notes` text NOT NULL,
  `isdelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`no`, `name`, `address`, `phone`, `email`, `notes`, `isdelete`) VALUES
(1, 'Lusi', 'Sudirman, Jakarta Pusat', '0812345678, 098765432', 'lusi@lusi.com', 'Pelanggan lama dari tahun 2000', 0),
(2, 'Momo2', 'Sunter2', '021-1234562', 'momo@gamil.com2', 'Pelanggan loyal2', 0),
(3, 'Ali', 'Tanah Abang', '0812345678, 021-1234567', 'ali@gmail.com', '-', 0),
(4, 'Taufan', 'Jl. Pangeran Diponegoro No. 107, Bandung	', '0811112233', '', 'Pengiriman barang umumnya menggunakan jasa pengiriman khusus', 0),
(5, 'Indah', 'Kebun Jeruk Raya', '021-123123, 0251-123456', '', '', 0),
(6, 'Li an', 'Tanah Abang', '1111111', 'lian@gmail.com', '-', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `no` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `name` varchar(500) NOT NULL,
  `item_type_no` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `notes` varchar(500) NOT NULL,
  `isdelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`no`, `code`, `name`, `item_type_no`, `image`, `notes`, `isdelete`) VALUES
(1, 'P00001', 'Piyama Merah', 2, 'img/barang/piama.jpg', '', 0),
(12, 'test', 'test', 1, 'img/barang/test_piama.jpg', 'test', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_detail`
--

CREATE TABLE `item_detail` (
  `item_no` int(11) NOT NULL,
  `item_unit_no` int(11) NOT NULL,
  `price` double NOT NULL,
  `stock` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_detail`
--

INSERT INTO `item_detail` (`item_no`, `item_unit_no`, `price`, `stock`) VALUES
(1, 1, 100000, 12.5),
(1, 2, 115000, 0),
(12, 1, 30000, 0),
(12, 2, 20000, 4),
(12, 3, 10000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_detail_stock`
--

CREATE TABLE `item_detail_stock` (
  `event` datetime NOT NULL,
  `item_no` int(11) NOT NULL,
  `item_unit_no` int(11) NOT NULL,
  `stock` double NOT NULL,
  `adjustment` tinyint(1) NOT NULL DEFAULT '0',
  `sales` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_detail_stock`
--

INSERT INTO `item_detail_stock` (`event`, `item_no`, `item_unit_no`, `stock`, `adjustment`, `sales`) VALUES
('2018-03-10 14:27:11', 1, 1, 10.5, 0, 0),
('2018-03-10 14:27:24', 12, 2, 5, 0, 0),
('2018-03-10 14:27:38', 1, 1, 2, 0, 0),
('2018-03-10 14:47:21', 1, 1, -0.5, 1, 0),
('2018-03-10 22:12:45', 1, 1, -2, 0, 1),
('2018-03-10 22:12:45', 12, 2, -3, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item_type`
--

CREATE TABLE `item_type` (
  `no` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `isdelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_type`
--

INSERT INTO `item_type` (`no`, `name`, `isdelete`) VALUES
(1, 'Setelan Piyama Bordir', 0),
(2, 'Setelan Piyama Kancing', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_unit`
--

CREATE TABLE `item_unit` (
  `no` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `isdelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_unit`
--

INSERT INTO `item_unit` (`no`, `name`, `isdelete`) VALUES
(1, 'S-M-L', 0),
(2, '4-8', 0),
(3, '10-14', 0),
(4, '16-20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `event` datetime NOT NULL,
  `log_category_no` int(11) NOT NULL,
  `account_no` int(11) NOT NULL DEFAULT '0',
  `page_no` int(11) NOT NULL DEFAULT '0',
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`event`, `log_category_no`, `account_no`, `page_no`, `description`) VALUES
('2018-03-04 15:01:05', 1, 1, 12, 'Login Berhasil'),
('2018-03-04 15:01:06', 1, 1, 12, 'Login Berhasil'),
('2018-03-04 15:03:05', 1, 1, 12, 'Login Berhasil'),
('2018-03-04 15:03:05', 1, 1, 12, 'Logout Berhasil'),
('2018-03-04 15:03:33', 1, 1, 12, 'Login Berhasil'),
('2018-03-04 15:03:39', 1, 1, 12, 'Logout Berhasil'),
('2018-03-04 15:13:24', 1, 0, 0, 'Login Gagal dengan username test dan password test'),
('2018-03-04 15:13:33', 1, 1, 12, 'Login Berhasil'),
('2018-03-04 15:14:00', 1, 1, 0, 'Logout Berhasil'),
('2018-03-04 15:14:13', 1, 1, 12, 'Login Berhasil'),
('2018-03-07 20:09:44', 2, 1, 18, 'test : 12, 3'),
('2018-03-07 20:09:57', 3, 1, 18, ', 12 = Existing, 2 = Add, 5 = Add, 3 = Delete'),
('2018-03-07 20:10:01', 4, 1, 18, 'test'),
('2018-03-07 20:14:07', 2, 1, 18, 'test : 12, 3'),
('2018-03-07 20:14:16', 3, 1, 18, 'test, 12 = Existing, 2 = Add, 5 = Add, 3 = Delete'),
('2018-03-07 20:14:21', 4, 1, 18, 'test'),
('2018-03-07 21:33:59', 2, 1, 18, 'test : 12, 3'),
('2018-03-07 21:34:06', 4, 1, 18, 'test'),
('2018-03-07 21:44:14', 3, 1, 18, 'Admin, 12 = Existing, 2 = Existing, 3 = Existing, 5 = Add, 6 = Add, 7 = Add, 8 = Add, 9 = Add, 10 = Add, 11 = Add, 14 = Add, 15 = Add, 16 = Add, 18 = Add, 19 = Add, 30 = Add, 31 = Add, 21 = Add, 22 = Add, 24 = Add, 25 = Add, 27 = Add, 28 = Add'),
('2018-03-07 21:53:04', 1, 1, 0, 'Logout Berhasil'),
('2018-03-07 21:53:08', 1, 3, 12, 'Login Berhasil'),
('2018-03-07 21:57:09', 1, 3, 0, 'Logout Berhasil'),
('2018-03-07 21:57:13', 1, 1, 12, 'Login Berhasil'),
('2018-03-07 22:03:37', 2, 1, 18, 'aaa : '),
('2018-03-07 22:04:51', 3, 1, 18, 'aaa, 12 = Add'),
('2018-03-07 22:05:48', 3, 1, 18, 'aaa, 2 = Add, 3 = Add, 12 = Delete'),
('2018-03-07 22:07:10', 4, 1, 18, 'aaa'),
('2018-03-07 22:24:12', 3, 1, 18, 'Gudang, 12 = Existing, 19 = Add'),
('2018-03-07 22:24:17', 1, 1, 0, 'Logout Berhasil'),
('2018-03-07 22:24:21', 1, 3, 12, 'Login Berhasil'),
('2018-03-07 22:38:25', 1, 3, 0, 'Logout Berhasil'),
('2018-03-07 22:38:29', 1, 1, 12, 'Login Berhasil'),
('2018-03-08 22:14:44', 3, 1, 18, 'gudang2 : 1'),
('2018-03-08 22:16:57', 3, 1, 18, 'gudang2 : 1'),
('2018-03-08 22:17:17', 3, 1, 18, 'gudang : 3 - Change Password'),
('2018-03-08 22:17:47', 3, 1, 18, 'gudang : 3 - Change Password'),
('2018-03-08 22:21:33', 2, 1, 21, 'test : 2'),
('2018-03-08 22:21:46', 4, 1, 21, 'test'),
('2018-03-08 22:22:19', 2, 1, 21, 'test : 2'),
('2018-03-08 22:22:34', 3, 1, 18, 'Sales, 12 = Add'),
('2018-03-08 22:22:38', 1, 1, 0, 'Logout Berhasil'),
('2018-03-08 22:22:41', 1, 5, 12, 'Login Berhasil'),
('2018-03-08 22:32:44', 1, 5, 0, 'Logout Berhasil'),
('2018-03-08 22:32:48', 1, 1, 12, 'Login Berhasil'),
('2018-03-08 22:33:09', 3, 1, 21, 'test2 : 1 - Change Password'),
('2018-03-08 22:33:20', 1, 1, 0, 'Logout Berhasil'),
('2018-03-08 22:33:24', 1, 5, 12, 'Login Berhasil'),
('2018-03-08 22:33:27', 1, 5, 0, 'Logout Berhasil'),
('2018-03-08 22:33:31', 1, 1, 12, 'Login Berhasil'),
('2018-03-08 22:33:39', 4, 1, 21, 'test2'),
('2018-03-08 23:27:59', 2, 1, 24, ''),
('2018-03-08 23:29:12', 4, 1, 24, 'test'),
('2018-03-08 23:29:24', 2, 1, 24, 'test'),
('2018-03-08 23:29:45', 3, 1, 24, 'test2'),
('2018-03-08 23:29:58', 4, 1, 24, 'test2'),
('2018-03-08 23:41:31', 2, 1, 27, 'test'),
('2018-03-08 23:42:28', 3, 1, 27, 'test2'),
('2018-03-08 23:42:34', 4, 1, 27, 'test2'),
('2018-03-09 04:36:19', 1, 1, 12, 'Login Berhasil'),
('2018-03-09 04:38:18', 3, 1, 24, 'Setelan Piyama Bordirr'),
('2018-03-09 06:53:44', 1, 1, 0, 'Logout Berhasil'),
('2018-03-09 06:53:47', 1, 1, 12, 'Login Berhasil'),
('2018-03-09 06:55:14', 1, 1, 0, 'Logout Berhasil'),
('2018-03-09 06:55:17', 1, 1, 12, 'Login Berhasil'),
('2018-03-09 07:55:25', 4, 1, 2, 'Momo'),
('2018-03-09 08:24:50', 3, 1, 24, 'Momo;Sunter;021-123456;momo@gmail.com;Pelanggan loyal - Edit Pelanggan'),
('2018-03-09 08:30:55', 3, 1, 24, 'Momo1;Sunter1;021-1234561;momo1@gmil.com;Pelanggan loyal1 - Edit Pelanggan'),
('2018-03-09 08:33:29', 3, 1, 24, 'Momo2;Sunter2;021-1234562;momo@gamil.com2;Pelanggan loyal2 - Edit Pelanggan'),
('2018-03-09 08:33:59', 4, 1, 2, 'Momo2'),
('2018-03-09 08:35:47', 2, 1, 24, 'Li an;Tanah Abang;1111111;lian@gmail.com;- - Tambah Pelanggan'),
('2018-03-09 15:19:12', 2, 1, 18, 'test : 12, 3'),
('2018-03-09 15:19:18', 4, 1, 18, 'test'),
('2018-03-09 15:19:38', 2, 1, 21, 'test : 1'),
('2018-03-09 15:19:44', 4, 1, 21, 'test'),
('2018-03-09 15:19:58', 1, 1, 0, 'Logout Berhasil'),
('2018-03-09 15:20:04', 1, 0, 0, 'Login Gagal dengan username test dan password test'),
('2018-03-09 15:20:08', 1, 1, 12, 'Login Berhasil'),
('2018-03-09 15:21:18', 2, 1, 24, 'test'),
('2018-03-09 15:21:24', 4, 1, 24, 'test'),
('2018-03-09 15:21:33', 2, 1, 27, 'test'),
('2018-03-09 15:21:38', 4, 1, 27, 'test'),
('2018-03-09 15:35:25', 2, 1, 24, 'test'),
('2018-03-09 15:35:43', 2, 1, 24, 'test2'),
('2018-03-09 15:35:48', 4, 1, 24, 'test2'),
('2018-03-09 15:35:52', 4, 1, 24, 'test'),
('2018-03-09 15:37:54', 2, 1, 27, 'test'),
('2018-03-09 15:38:37', 2, 1, 27, 'test2'),
('2018-03-09 15:40:40', 2, 1, 21, 'test : 1'),
('2018-03-09 15:40:55', 2, 1, 21, 'test2 : 1'),
('2018-03-09 15:42:41', 2, 1, 18, 'test : 12, 3'),
('2018-03-09 15:42:54', 2, 1, 18, 'test2 : 12, 2'),
('2018-03-10 10:56:38', 2, 1, 5, '11,test;test;1;piama.jpg;test3-5000 = Tambah, 4-10000 = Tambah, 2-15000 = Tambah'),
('2018-03-10 10:58:03', 3, 1, 5, '11, test, 4-10000 = Existing, 2-20000 = Edit, 1-25000 = Tambah, 3-5000 = Hapus'),
('2018-03-10 11:17:27', 4, 1, 5, '11, test'),
('2018-03-10 13:06:20', 2, 1, 5, '12,test;test;1;piama.jpg;test3-10000 = Tambah, 2-20000 = Tambah, 1-30000 = Tambah'),
('2018-03-10 13:19:57', 2, 1, 24, ''),
('2018-03-10 14:09:02', 2, 1, 8, '1 - 1 : 10'),
('2018-03-10 14:18:29', 2, 1, 8, '1 - 1 : 10'),
('2018-03-10 14:19:05', 2, 1, 8, '1 - 1 : 5.25'),
('2018-03-10 14:19:26', 2, 1, 8, '12 - 3 : 2'),
('2018-03-10 14:27:11', 2, 1, 8, '1 - 1 : 10.5'),
('2018-03-10 14:27:24', 2, 1, 8, '12 - 2 : 5'),
('2018-03-10 14:27:38', 2, 1, 8, '1 - 1 : 2'),
('2018-03-10 14:47:21', 2, 1, 11, '1 - 1 : -0.5'),
('2018-03-10 22:06:05', 2, 1, 14, '1;2;290000;2018-03-31;test'),
('2018-03-10 22:12:46', 2, 1, 14, '4;3;248500;2018-03-31;test'),
('2018-03-11 14:42:22', 2, 1, 15, ';;;;ok2'),
('2018-03-11 14:43:15', 2, 1, 15, ';;;;ok3');

-- --------------------------------------------------------

--
-- Table structure for table `log_category`
--

CREATE TABLE `log_category` (
  `no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_category`
--

INSERT INTO `log_category` (`no`, `name`) VALUES
(3, 'Edit'),
(4, 'Hapus'),
(1, 'Login'),
(2, 'Tambah');

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
(20, 3, 'fa-address-book-o', NULL, NULL, 'User / Karyawan', NULL, 7),
(21, 3, NULL, 'userkaryawan-manage', 'userkaryawan-manage.php', '+ Tambah User / Karyawan', 20, 1),
(22, 3, NULL, 'userkaryawan-lihat', 'userkaryawan-lihat.php', 'Lihat User / Karyawan', 20, 2),
(23, 3, 'fa-check-square-o', NULL, NULL, 'Jenis Barang', NULL, 8),
(24, 3, NULL, 'jenisbarang-manage', 'jenisbarang-manage.php', '+ Jenis Barang', 23, 1),
(25, 3, NULL, 'jenisbarang-lihat', 'jenisbarang-lihat.php', 'Lihat Jenis Barang', 23, 2),
(26, 3, 'fa-arrows-h', NULL, NULL, 'Jenis Ukuran', NULL, 9),
(27, 3, NULL, 'jenisukuran-manage', 'jenisukuran-manage.php', '+ Jenis Ukuran', 26, 1),
(28, 3, NULL, 'jenisukuran-lihat', 'jenisukuran-lihat.php', 'Lihat Jenis Ukuran', 26, 2);

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

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `sales_no` int(11) NOT NULL,
  `event` datetime NOT NULL,
  `payment_type_no` int(11) NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`sales_no`, `event`, `payment_type_no`, `total`) VALUES
(4, '2018-03-11 00:00:00', 1, 100000),
(4, '2018-03-11 00:00:00', 2, 50000),
(4, '2018-03-11 00:00:00', 4, 60000);

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE `payment_type` (
  `no` int(11) NOT NULL,
  `name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`no`, `name`) VALUES
(1, 'Cash'),
(2, 'Transfer'),
(3, 'Kartu Debit'),
(4, 'Kartu Kredit');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `no` int(11) NOT NULL,
  `event` datetime NOT NULL,
  `customer_no` int(11) NOT NULL,
  `total` double NOT NULL,
  `deadline` datetime NOT NULL,
  `notes` varchar(500) NOT NULL,
  `outstanding` double NOT NULL,
  `payment_notes` varchar(500) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`no`, `event`, `customer_no`, `total`, `deadline`, `notes`, `outstanding`, `payment_notes`) VALUES
(1, '2018-03-10 00:00:00', 2, 1000000, '2018-03-31 00:00:00', 'test', 1000000, ''),
(4, '2018-03-10 00:00:00', 3, 248500, '2018-03-31 00:00:00', 'test', 38500, 'ok3');

-- --------------------------------------------------------

--
-- Table structure for table `sales_detail`
--

CREATE TABLE `sales_detail` (
  `sales_no` int(11) NOT NULL,
  `item_no` int(11) NOT NULL,
  `item_unit_no` int(11) NOT NULL,
  `qty` double NOT NULL,
  `price` double NOT NULL,
  `discount` double NOT NULL,
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_detail`
--

INSERT INTO `sales_detail` (`sales_no`, `item_no`, `item_unit_no`, `qty`, `price`, `discount`, `subtotal`) VALUES
(4, 1, 1, 2, 100000, 5000, 190000),
(4, 12, 2, 3, 20000, 500, 58500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `account_level`
--
ALTER TABLE `account_level`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `account_level_page`
--
ALTER TABLE `account_level_page`
  ADD PRIMARY KEY (`account_level_no`,`page_no`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `item_detail`
--
ALTER TABLE `item_detail`
  ADD PRIMARY KEY (`item_no`,`item_unit_no`);

--
-- Indexes for table `item_detail_stock`
--
ALTER TABLE `item_detail_stock`
  ADD PRIMARY KEY (`event`,`item_no`,`item_unit_no`);

--
-- Indexes for table `item_type`
--
ALTER TABLE `item_type`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `item_unit`
--
ALTER TABLE `item_unit`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`event`,`log_category_no`,`account_no`,`page_no`,`description`);

--
-- Indexes for table `log_category`
--
ALTER TABLE `log_category`
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
-- Indexes for table `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `sales_detail`
--
ALTER TABLE `sales_detail`
  ADD PRIMARY KEY (`sales_no`,`item_no`,`item_unit_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `account_level`
--
ALTER TABLE `account_level`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `item_type`
--
ALTER TABLE `item_type`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `item_unit`
--
ALTER TABLE `item_unit`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `log_category`
--
ALTER TABLE `log_category`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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

--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
