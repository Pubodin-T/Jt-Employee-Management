-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 04:48 AM
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
-- Database: `companyx`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `role`, `created_at`, `is_active`) VALUES
(1, '212187521', '212187521', 1, '2024-09-23 13:41:08', 1),
(2, '819071006', '819071006', 1, '2024-09-23 13:41:42', 1),
(3, '819071020', '819071020', 2, '2024-09-23 13:42:02', 1),
(4, '819071021', '819071021', 1, '2024-09-23 13:42:29', 1),
(5, '819071023', '819071023', 1, '2024-09-23 13:42:29', 1),
(6, '819071028', '819071028', 1, '2024-09-23 13:46:10', 1),
(7, '819071T000002', '819071T000002', 1, '2024-09-23 13:46:10', 1),
(8, '819071T000003', '819071T000003', 1, '2024-09-23 13:46:10', 1),
(9, '819071T000006', '819071T000006', 1, '2024-09-23 13:46:10', 1),
(10, '819071T000010', '819071T000010', 1, '2024-09-23 13:46:10', 1),
(11, '819071T000014', '819071T000014', 1, '2024-09-23 13:46:10', 1),
(12, '819071T000015', '819071T000015', 1, '2024-09-23 13:46:10', 1),
(13, '819071T000018', '819071T000018', 1, '2024-09-23 13:46:10', 1),
(14, '819071T000019', '819071T000019', 1, '2024-09-23 13:46:10', 1),
(15, '819071T000020', '819071T000020', 1, '2024-09-23 13:46:10', 1),
(16, '819071T000024', '819071T000024', 1, '2024-09-23 13:47:07', 1),
(17, 'admin', 'admin', 2, '2024-09-23 13:47:07', 2),
(18, '1111111', '1111111', 1, '2024-09-23 14:28:18', 1),
(19, '12154521', '12154521', 1, '2024-09-30 21:27:13', 1),
(20, '12154521', '12154521', 1, '2024-09-30 21:27:32', 1),
(21, '12154521', '12154521', 1, '2024-09-30 21:30:54', 1),
(22, '56231241', '12154521', 1, '2024-09-30 21:31:27', 1),
(23, '15123124', '15123124', 1, '2024-09-30 21:34:31', 1),
(24, '15123124', '15123124', 1, '2024-09-30 21:37:02', 1),
(25, '4512121', '4512121', 1, '2024-09-30 21:37:35', 1),
(26, '4512121', '$2y$10$Gxi2vV7vTZRoWeSXFtnCrONM12/Pw7EBciEc6GygK5V', 1, '2024-09-30 21:39:15', 1),
(27, '31231552', '$2y$10$X57yBlp5W7tFO4xtiFGDieuiatKz5X6MWMm2LTLoYgl', 1, '2024-09-30 21:40:15', 1),
(28, 'test123', '$2y$10$T.GZNwWXdkXWH4YsenjrQeVtU0Euo7gxCjX3Xoba0TW', 1, '2024-10-11 06:12:28', 1),
(29, 'test1233', 'test1233', 1, '2024-10-11 07:06:42', 1),
(30, 'tayakon', 'tayakon', 1, '2024-10-11 07:55:52', 1),
(31, 'tayakon', '$2y$10$ytWGM4cMQowzG6OQdwX.IO.jyNf8K4JwOifMP6zR83p', 1, '2024-10-13 14:33:15', 1),
(32, 'tayakon', '$2y$10$tXnP9NOgU6hzGE2UaW8s8eC/4U3IzfjhtRS3Q5GXe/Z', 1, '2024-10-13 14:33:21', 1),
(33, 'arm', 'arm', 1, '2024-10-13 14:39:06', 1),
(34, 'arm1', 'arm1', 1, '2024-10-13 15:20:24', 1),
(35, 'aaa', 'aaa', 1, '2024-10-13 15:24:26', 1),
(36, 'bbb', 'bbb', 1, '2024-10-13 15:28:32', 1),
(37, 'ccc', 'ccc', 1, '2024-10-14 03:31:15', 1),
(38, 'abc', 'abc', 1, '2024-10-14 03:42:00', 1),
(39, 'abcd', 'abcd', 1, '2024-10-14 03:43:23', 1),
(40, 'abcde', 'abcde', 1, '2024-10-14 03:48:02', 1),
(41, 'abcdef', 'abcdef', 1, '2024-10-14 03:51:05', 1),
(43, 'asd', '$2y$10$bstvGnKIIDAFiCvRAJuwT.VjWdJL.O7Eke3gyRj1..U', 1, '2024-10-14 04:00:23', 1),
(45, 'asdd', 'asdd', 1, '2024-10-14 04:05:28', 1),
(46, 'vvv', 'vvv', 1, '2024-10-14 04:08:52', 1),
(47, 'nnn', 'nnn', 1, '2024-10-14 04:10:27', 1),
(48, 'nnn', 'nnn', 1, '2024-10-14 04:11:23', 1),
(49, 'nnn', 'nnn', 1, '2024-10-14 04:11:31', 1),
(50, 'nnn1', 'nnn1', 1, '2024-10-14 04:13:54', 1),
(51, 'tapalad15', 'tapalad15', 1, '2024-10-14 04:19:16', 1),
(52, 'tapalad15', 'tapalad15', 1, '2024-10-14 04:20:20', 1),
(53, 'tapalad15', 'tapalad15', 1, '2024-10-14 04:23:19', 1),
(54, 'tapalad155', 'tapalad155', 1, '2024-10-14 04:23:52', 1),
(55, 'tapalad155', 'tapalad155', 1, '2024-10-14 04:28:37', 1),
(56, 'tapalad155', 'tapalad155', 1, '2024-10-14 04:30:26', 1),
(57, 'ggggg', 'ggggg', 1, '2024-10-14 04:35:14', 1),
(58, 'ggggg', 'ggggg', 1, '2024-10-14 04:36:13', 1),
(59, 'ggggg', 'ggggg', 1, '2024-10-14 04:36:23', 1),
(60, 'gggggff', 'gggggff', 1, '2024-10-14 04:38:15', 1),
(61, 'gggggfff', 'gggggfff', 1, '2024-10-14 04:39:29', 1),
(62, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:43:51', 1),
(63, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:49:25', 1),
(64, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:50:04', 1),
(65, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:51:24', 1),
(66, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:51:45', 1),
(67, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:52:44', 1),
(68, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:55:08', 1),
(69, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 04:56:11', 1),
(70, 'gggggfffff', 'gggggfffff', 1, '2024-10-14 12:18:22', 1),
(71, 'tayakon11', 'tayakon11', 1, '2024-10-14 12:20:33', 1),
(72, '11248', '12345', 1, '2024-10-14 13:02:01', 1),
(73, '151515', '151515', 1, '2024-10-14 21:03:39', 1),
(74, '819071T37', '819071T37', 1, '2024-10-15 21:17:24', 1),
(75, '812485455', '812485455', 1, '2024-10-16 04:29:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` varchar(50) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_position` varchar(100) NOT NULL,
  `employee_age` int(11) DEFAULT NULL,
  `employee_dob` date DEFAULT NULL,
  `employee_phone` varchar(255) DEFAULT NULL,
  `employee_email` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `vacation_days_taken` int(11) DEFAULT 0,
  `sick_leave_remaining` int(11) DEFAULT 30,
  `personal_leave_remaining` int(11) DEFAULT 3,
  `vacation_leave_remaining` int(11) DEFAULT 6,
  `special_leave_remaining` int(11) DEFAULT 5,
  `role` varchar(50) DEFAULT NULL,
  `contact` text DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `status` enum('1','0') DEFAULT '1',
  `vacation_days_accumulated` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `employee_name`, `employee_position`, `employee_age`, `employee_dob`, `employee_phone`, `employee_email`, `photo_path`, `vacation_days_taken`, `sick_leave_remaining`, `personal_leave_remaining`, `vacation_leave_remaining`, `special_leave_remaining`, `role`, `contact`, `account_id`, `status`, `vacation_days_accumulated`) VALUES
('09876555', 'Nattapong Chaiyachak', 'วิ่งงาน', 22, '2001-05-15', '09443433', 'asdsa@das.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 43, '1', 0),
('1111111', 'Pranee ninchmnan', 'วิ่งงาน', 26, '1997-02-23', '0923267447', 'Pranee11248@gmail.com', '263b03a8ec8b34ea74b20e42dea0b0b7-cutout.png', 6, 30, 3, 6, 5, '1', NULL, 18, '1', 0),
('11248', 'wanisa ninchmnan', 'วิ่งงาน', 24, '2000-09-14', '951134871', 'wanisa@gmail.com', 'uploads/IMG_670d169a0f6601.45271672.jpg', 0, 30, 3, 6, 5, '1', NULL, 72, '1', 0),
('12154521', 'Kritsada Srisai', 'พาร์ทไทม์', 24, '2000-06-16', '0958452311', 'narank112@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 19, '1', 0),
('123321', 'tayakon tapalad', 'พาร์ทไทม์', 22, '2002-03-11', '9443433', 'tapalad15@das.com', NULL, 0, 26, 3, 5, 5, '1', NULL, 71, '1', 0),
('15123124', 'yamapee hahaha', 'วิ่งงาน', 24, '2000-10-05', '0953264411', 'yamapee@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 23, '1', 0),
('151515', 'Wichai Wongwong', 'พาร์ทไทม์', 24, '2000-06-15', '931317471', 'Wichai@gmail.com', 'uploads/IMG_670d8753c67c10.34381328.jpg', 0, 30, 3, 6, 5, '1', NULL, 73, '1', 0),
('212187521', 'Sankeaw Ninchmnan', 'วิ่งงาน', 24, '2000-09-18', '0611051569', 'Sankeaw11248@gmail.com', 'uploads/IMG_6707e00f1a1c24.57797985.png', 6, 30, 3, 6, 5, '1', NULL, 1, '1', 0),
('5555556677255', 'Pimchanok Phaithoon', 'วิ่งงาน', 22, '2004-09-24', '9443433', 'tapalad15@das.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 70, '1', 0),
('56231241', 'yanisa eiei', 'พาร์ทไทม์', 24, '2000-06-16', '04121213', 'yanisa112@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 22, '1', 0),
('812485455', 'wannicha hahaha', 'วิ่งงาน', 20, '2006-02-16', '953264411', 'wannicha@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 75, '1', 0),
('819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 24, '0000-00-00', '0923267447', 'Siriwat@gmail.com', 'icon.jpg', 0, 30, 3, 6, 5, '1', NULL, 2, '1', 0),
('819071020', 'Wudthiphad Wiriyasahwangkul', 'เจ้าของกิจการ', 30, '1990-08-30', '0952564811', 'wudthiphad@gmail.com', NULL, 0, 30, 3, 6, 5, 'เจ้าของกิจการ', NULL, 3, '1', 0),
('819071021', 'Sonphet Phakdee', 'วิ่งงาน', 25, '1990-05-21', '0215742431', 'sonphet@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 4, '1', 0),
('819071023', 'Saphat Woraphap', 'ผู้จัดการ', 25, '1999-04-23', '0956231251', 'Saphat@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 5, '1', 0),
('819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 30, '1993-05-10', '0123456789', 'jakkree@example.com', 'icon.jpg', 0, 30, 3, 6, 5, '1', NULL, 6, '1', 0),
('819071T000002', 'Sutthikan Thaenphakwaen', 'วิ่งงาน', 25, '1999-06-12', '0623157812', 'Sutthikan@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 7, '1', 0),
('819071T000003', 'Kittamat Phonthong', 'วิ่งงาน', 25, '1999-07-13', '0654218712', 'Kittamat@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 8, '1', 0),
('819071T000006', 'Peerapan Sudtiwhong', 'วิ่งงาน', 25, '1990-07-25', '0952312471', 'Peerapan@gamil.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 9, '1', 0),
('819071T000010', 'Mongkhonchai Buasing', 'วิ่งงาน', 26, '1998-08-01', '0952326715', 'Mongkhonchai@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 10, '1', 0),
('819071T000014', 'Rattaphol Tiwongsa', 'วิ่งงาน', 26, '1998-07-03', '0545121247', 'Rattahol@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 11, '1', 0),
('819071T000015', 'Noontawat Thaokrathok', 'รองหัวหน้าบ้านเกาะ', 28, '1996-05-01', '0923234151', 'Noontawat@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 12, '1', 0),
('819071T000018', 'Oatthakon Aintharaphon', 'วิ่งงาน', 30, '1990-02-02', '0263587411', 'Oatthakan@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 13, '1', 0),
('819071T000019', 'Suppaset Suwanprapak', 'วิ่งงาน', 28, '1996-07-01', '0923231271', 'Suppaset@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 14, '1', 0),
('819071T000020', 'Bancha Chanjaroen', 'วิ่งงาน', 30, '1990-08-30', '0953231871', 'Bancha@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 15, '1', 0),
('819071T000024', 'Jennarong Thabsang', 'วิ่งงาน', 34, '1986-08-30', '0656232712', 'Jennarong@gmail.com', NULL, 0, 30, 3, 6, 5, '1', NULL, 16, '1', 0),
('819071T37', 'yanisa namjai', 'พาร์ทไทม์', 24, '2000-09-16', '953231571', 'yanisa11248@gmail.com', NULL, 0, 26, 1, 2, 3, '1', NULL, 74, '1', 0),
('admin', 'admin', '', 28, '1996-05-25', '0561262311', NULL, NULL, 0, 26, 3, 6, 5, '2', NULL, 17, '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_approvals`
--

CREATE TABLE `leave_approvals` (
  `leave_approvals_id` int(11) NOT NULL,
  `leave_request_id` int(11) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT NULL,
  `approval_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `employee_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `employee_id` varchar(50) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `leave_days_remaining` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_conditions`
--

CREATE TABLE `leave_conditions` (
  `id` int(11) NOT NULL,
  `leave_type` enum('ลากิจ','ลาป่วย','ลาพักร้อน','ลากรณีพิเศษ') NOT NULL,
  `min_days` int(11) DEFAULT 0,
  `requires_medical_certificate` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `leave_requests_id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `employee_position` varchar(100) NOT NULL,
  `leave_type_id` int(11) DEFAULT NULL,
  `reason` text NOT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('0','1','2') DEFAULT '0',
  `contact` varchar(10) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `submit_date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`leave_requests_id`, `employee_id`, `employee_name`, `employee_position`, `leave_type_id`, `reason`, `certificate`, `created_at`, `status`, `contact`, `start_date`, `end_date`, `submit_date`) VALUES
(3, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 2, '', '', '2024-08-28 21:59:28', '2', NULL, NULL, NULL, NULL),
(4, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, '', '', '2024-08-28 22:04:35', '2', NULL, NULL, NULL, NULL),
(5, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, '', '', '2024-08-28 22:21:57', '2', NULL, NULL, NULL, NULL),
(6, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'ธุระ', NULL, '2024-08-28 22:21:57', '2', NULL, NULL, NULL, NULL),
(7, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 3, '', '', '2024-08-28 22:24:31', '2', NULL, NULL, NULL, NULL),
(8, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 3, '', NULL, '2024-08-28 22:24:31', '2', NULL, NULL, NULL, NULL),
(9, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 3, '', '', '2024-08-28 22:37:58', '2', NULL, NULL, NULL, NULL),
(10, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 3, '', NULL, '2024-08-28 22:37:58', '2', NULL, NULL, NULL, NULL),
(11, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 3, '', '', '2024-08-28 22:38:26', '2', NULL, NULL, NULL, NULL),
(12, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 3, '', NULL, '2024-08-28 22:38:26', '2', NULL, NULL, NULL, NULL),
(18, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 08:29:10', '2', NULL, NULL, NULL, NULL),
(19, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 1, '', NULL, '2024-08-29 08:29:29', '2', NULL, NULL, NULL, NULL),
(20, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 08:37:28', '2', NULL, NULL, NULL, NULL),
(21, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 22:13:07', '2', NULL, NULL, NULL, NULL),
(22, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 22:20:28', '2', NULL, NULL, NULL, NULL),
(23, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 22:31:46', '2', NULL, NULL, NULL, NULL),
(24, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 22:32:03', '2', NULL, NULL, NULL, NULL),
(25, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 22:33:27', '2', NULL, NULL, NULL, NULL),
(26, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 22:33:38', '2', NULL, NULL, NULL, NULL),
(27, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 1, '', NULL, '2024-08-29 22:33:51', '2', NULL, NULL, NULL, NULL),
(28, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 2, '', NULL, '2024-08-29 22:38:51', '2', NULL, NULL, NULL, NULL),
(29, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 1, '', NULL, '2024-08-29 22:54:41', '2', NULL, NULL, NULL, NULL),
(30, '819071006', 'Siriwat Puangsuk', 'หัวหน้าบ้านเกาะ', 1, '', NULL, '2024-08-29 22:54:52', '2', NULL, NULL, NULL, NULL),
(31, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 2, '', NULL, '2024-08-30 00:55:20', '2', NULL, NULL, NULL, NULL),
(32, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, '', NULL, '2024-08-30 01:06:03', '2', NULL, NULL, NULL, NULL),
(33, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, '', NULL, '2024-08-30 01:19:20', '2', NULL, NULL, NULL, NULL),
(34, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 2, '', NULL, '2024-08-30 01:50:34', '2', NULL, NULL, NULL, NULL),
(37, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, '', NULL, '2024-08-30 02:03:15', '2', NULL, NULL, NULL, NULL),
(40, '212187521', 'Sankeaw Ninchmnan', 'วิ่งงาน', 1, '', NULL, '2024-09-08 08:57:35', '2', NULL, NULL, NULL, NULL),
(42, '212187521', 'Sankeaw Ninchmnan', 'วิ่งงาน', 1, 'ธุระ', NULL, '2024-09-08 18:09:01', '2', NULL, NULL, NULL, NULL),
(44, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'test', NULL, '2024-09-08 18:13:04', '2', NULL, NULL, NULL, NULL),
(45, '819071028', '', '', 1, 'test', '', '2024-09-08 18:18:40', '2', NULL, NULL, NULL, NULL),
(46, '819071028', '', '', 1, 'test', '', '2024-09-08 18:19:08', '2', NULL, NULL, NULL, NULL),
(47, '819071028', '', '', 1, 'testt', '', '2024-09-08 18:19:27', '2', NULL, NULL, NULL, NULL),
(48, '819071028', '', '', 1, 'testt', '', '2024-09-08 18:23:01', '2', NULL, NULL, NULL, NULL),
(49, '819071028', '', '', 1, 'test', '', '2024-09-08 18:23:18', '2', NULL, NULL, NULL, NULL),
(50, '819071028', '', '', 1, 'test', '', '2024-09-08 18:25:24', '2', NULL, NULL, NULL, NULL),
(51, '819071028', '', '', 1, 'ddd', '', '2024-09-08 18:25:37', '2', NULL, NULL, NULL, NULL),
(52, '819071028', '', '', 1, 'ddd', '', '2024-09-08 18:27:41', '2', NULL, NULL, NULL, NULL),
(53, '819071028', '', '', 1, 'xxx', '', '2024-09-08 18:27:53', '2', NULL, NULL, NULL, NULL),
(54, '819071028', '', '', 1, 'xxx', '', '2024-09-08 18:29:43', '2', NULL, NULL, NULL, NULL),
(55, '819071028', '', '', 1, 'sfs', '', '2024-09-08 18:29:52', '2', NULL, NULL, NULL, NULL),
(56, '819071028', '', '', 1, 'sss', '', '2024-09-08 18:33:30', '2', NULL, NULL, NULL, NULL),
(57, '819071028', '', '', 1, 'xff', '', '2024-09-08 18:36:28', '2', NULL, NULL, NULL, NULL),
(58, '819071028', '', '', 1, 'fff', '', '2024-09-08 18:40:13', '2', NULL, NULL, NULL, NULL),
(59, '819071028', '', '', 1, 'fff', '', '2024-09-08 18:41:47', '2', NULL, NULL, NULL, NULL),
(60, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'fff', NULL, '2024-09-08 18:41:47', '2', NULL, NULL, NULL, NULL),
(61, '819071028', '', '', 1, 'fff', '', '2024-09-08 18:41:50', '2', NULL, NULL, NULL, NULL),
(62, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'fff', NULL, '2024-09-08 18:41:50', '2', NULL, NULL, NULL, NULL),
(63, '819071028', '', '', 1, 'dsds', '', '2024-09-08 18:42:00', '2', NULL, NULL, NULL, NULL),
(64, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'dsds', NULL, '2024-09-08 18:42:00', '2', NULL, NULL, NULL, NULL),
(65, '819071028', '', '', 1, 'dsds', '', '2024-09-08 18:43:21', '2', NULL, NULL, NULL, NULL),
(66, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'dsds', NULL, '2024-09-08 18:43:21', '2', NULL, NULL, NULL, NULL),
(67, '819071028', '', '', 1, 'dsds', '', '2024-09-08 18:43:47', '2', NULL, NULL, NULL, NULL),
(68, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'dsds', NULL, '2024-09-08 18:43:47', '2', NULL, NULL, NULL, NULL),
(69, '819071028', '', '', 1, 'xx', '', '2024-09-08 18:43:55', '2', NULL, NULL, NULL, NULL),
(71, '819071028', '', '', 1, 'xx', '', '2024-09-08 18:46:18', '2', NULL, NULL, NULL, NULL),
(72, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'xx', NULL, '2024-09-08 18:46:18', '2', NULL, NULL, NULL, NULL),
(73, '819071028', '', '', 1, 'xx', '', '2024-09-08 18:50:39', '2', NULL, NULL, NULL, NULL),
(74, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'xx', NULL, '2024-09-08 18:50:39', '2', NULL, NULL, NULL, NULL),
(75, '819071028', '', '', 1, 'xx', '', '2024-09-08 18:50:50', '2', NULL, NULL, NULL, NULL),
(76, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'xx', NULL, '2024-09-08 18:50:50', '2', NULL, NULL, NULL, NULL),
(77, '819071028', '', '', 1, 'test', '', '2024-09-08 18:51:29', '2', NULL, NULL, NULL, NULL),
(78, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'test', NULL, '2024-09-08 18:51:29', '2', NULL, NULL, NULL, NULL),
(80, '819071028', 'Jakkree Prajuabklang', 'หัวหน้าบ้านบุ', 1, 'test', NULL, '2024-09-08 18:55:01', '2', NULL, NULL, NULL, NULL),
(106, '212187521', 'Sankeaw Ninchmnan', '', 1, 'ป่วย', '', '2024-09-08 19:28:51', '2', NULL, NULL, NULL, NULL),
(107, '212187521', 'Sankeaw Ninchmnan', '', 1, 'ธุระ', '', '2024-09-08 19:45:10', '2', NULL, NULL, NULL, NULL),
(112, '212187521', 'Sankeaw Ninchmnan', '', 1, 'ทำธุระส่วนตัว', '', '2024-09-23 14:03:52', '1', '0611051569', '2024-09-24 00:00:00', '2024-09-25 00:00:00', NULL),
(114, '212187521', 'Sankeaw Ninchmnan', '', 2, 'ป่วยหนัก', '', '2024-09-30 20:20:06', '1', '0611051569', '2024-10-02 00:00:00', '2024-10-03 00:00:00', '2024-10-01'),
(115, '212187521', 'Sankeaw Ninchmnan', '', 3, 'พักร้อนกับครอบครัว', '', '2024-10-03 15:40:14', '1', '0611051569', '2024-10-04 00:00:00', '2024-10-08 00:00:00', '2024-10-03'),
(129, '11248', 'wanisa ninchmnan', '', 2, 'ป่วย', 'news-216-1 (1).jpg', '2024-10-14 13:04:44', '1', '0931221551', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(131, '11248', 'wanisa ninchmnan', '', 2, 'ป่วยหนักมาก', 'news-216-1 (1).jpg', '2024-10-14 13:39:23', '1', '0611051569', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(132, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 14:35:26', '2', '0931221551', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(133, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 14:49:41', '2', '0611051569', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(134, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 14:49:55', '2', '0931221551', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(138, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 15:16:45', '2', '0611051569', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(139, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 16:22:48', '2', '0611051569', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(140, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 16:23:31', '2', '0611051569', '2024-10-13 00:00:00', '2024-10-13 00:00:00', '2024-10-14'),
(141, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 16:29:27', '2', '0611051569', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(142, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-14 16:32:39', '2', '0611051569', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(143, '11248', 'wanisa ninchmnan', '', 2, 'ป่วยยยยย', 'news-216-1 (1).jpg', '2024-10-14 16:33:06', '1', '0611051569', '2024-10-12 00:00:00', '2024-10-13 00:00:00', '2024-10-14'),
(144, '11248', 'wanisa ninchmnan', '', 2, 'ป่วยยยยยยยยยย', 'news-216-1 (1).jpg', '2024-10-14 16:47:21', '1', '0931221551', '2024-10-13 00:00:00', '2024-10-12 00:00:00', '2024-10-14'),
(145, '11248', 'wanisa ninchmnan', '', 2, 'ลาป่วย', 'news-216-1 (1).jpg', '2024-10-14 16:51:14', '1', '0931221551', '2024-10-13 00:00:00', '2024-10-14 00:00:00', '2024-10-14'),
(146, '11248', 'wanisa ninchmnan', '', 2, 'ป่วย', 'news-216-1 (1).jpg', '2024-10-14 16:51:50', '1', '0611051569', '2024-10-12 00:00:00', '2024-10-13 00:00:00', '2024-10-14'),
(147, '15123124', 'yamapee hahaha', '', 2, 'ป่วยหนัก', 'news-216-1 (1).jpg', '2024-10-14 19:42:33', '1', '0542124541', '2024-10-14 00:00:00', '2024-10-15 00:00:00', '2024-10-15'),
(149, '11248', 'wanisa ninchmnan', '', 3, '', NULL, '2024-10-15 17:25:45', '1', '0611051569', '2024-10-16 00:00:00', '2024-10-19 00:00:00', '2024-10-16'),
(150, '11248', 'wanisa ninchmnan', '', 3, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 17:29:22', '2', '0611051569', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(151, '11248', 'wanisa ninchmnan', '', 3, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 17:30:33', '2', '0931221551', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(152, '11248', 'wanisa ninchmnan', '', 3, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 17:31:15', '2', '', '2024-10-23 00:00:00', '2024-10-24 00:00:00', '2024-10-16'),
(153, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 18:26:09', '2', '0931221551', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(154, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 18:29:02', '2', '0611051569', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(155, '11248', 'wanisa ninchmnan', '', 4, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-15 18:29:42', '2', '0923267447', '2024-10-16 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(156, '11248', 'wanisa ninchmnan', '', 4, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-15 18:40:02', '2', '0923267447', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(157, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-15 19:03:36', '2', '0931221551', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(158, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 19:27:38', '2', '0611051569', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(159, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 19:29:45', '2', '0611051569', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(160, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 19:31:37', '2', '0611051569', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(161, '11248', 'wanisa ninchmnan', '', 3, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 19:32:17', '2', '0931221551', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(162, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-15 19:35:13', '2', '0611051569', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(163, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 19:37:37', '2', '0931221551', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(164, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 19:40:17', '2', '0542124541', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(165, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 19:42:49', '2', '0611051569', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(183, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-15 21:03:17', '2', '0611051569', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(188, '11248', 'wanisa ninchmnan', '', 1, 'พนักงานไม่เพียงพอ', NULL, '2024-10-15 21:25:17', '2', '0931221551', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(189, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-15 21:26:56', '2', '0931221551', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(204, '812485455', 'wannicha hahaha', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 04:29:42', '2', '611051569', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(205, '812485455', 'wannicha hahaha', '', 1, '', '', '2024-10-16 04:40:28', '1', '611051569', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(206, '812485455', 'wannicha hahaha', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 04:41:08', '2', '611051569', '2024-10-16 00:00:00', '2024-10-17 00:00:00', '2024-10-16'),
(207, '812485455', 'wannicha hahaha', '', 2, 'ติดต่อหัวหน้า', 'news-216-1 (1).jpg', '2024-10-16 04:42:28', '2', '611051569', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(208, 'admin', 'admin', '', 2, 'testtest', '3f97be20974f78f33a7aee5baf98a493.jpg', '2024-10-16 14:47:06', '0', '931221551', '2024-11-01 00:00:00', '2024-11-02 00:00:00', '2024-10-16'),
(209, 'admin', 'admin', '', 2, 'testtest', '3f97be20974f78f33a7aee5baf98a493.jpg', '2024-10-16 14:56:31', '0', '931221551', '2024-11-01 00:00:00', '2024-11-02 00:00:00', '2024-10-16'),
(210, '11248', 'wanisa ninchmnan', '', 1, 'ทำธุระส่วนตัว', '', '2024-10-16 15:12:10', '2', '611051569', '2024-10-20 00:00:00', '2024-10-21 00:00:00', '2024-10-16'),
(211, '11248', 'wanisa ninchmnan', '', 1, '', '', '2024-10-16 15:27:57', '2', '611051569', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(212, '11248', 'wanisa ninchmnan', '', 1, '', '', '2024-10-16 15:31:38', '2', '611051569', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-16'),
(213, '11248', 'wanisa ninchmnan', '', 2, 'ป่วยหนัก', 'news-216-1 (1).jpg', '2024-10-16 15:34:12', '2', '611051569', '2024-10-11 00:00:00', '2024-10-12 00:00:00', '2024-10-16'),
(214, '11248', 'wanisa ninchmnan', '', 2, 'ติดต่อหัวหน้า', 'news-216-1 (1).jpg', '2024-10-16 15:44:30', '2', '611051569', '2024-10-14 00:00:00', '2024-10-15 00:00:00', '2024-10-16'),
(215, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-16 15:51:35', '2', '611051569', '2024-10-14 00:00:00', '2024-10-15 00:00:00', '2024-10-16'),
(216, '11248', 'wanisa ninchmnan', '', 2, 'ติดต่อหัวหน้า', 'news-216-1 (1).jpg', '2024-10-16 15:52:50', '2', '611051569', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(217, '11248', 'wanisa ninchmnan', '', 2, 'พนักงานไม่เพียงพอ', 'news-216-1 (1).jpg', '2024-10-16 16:00:28', '2', '611051569', '2024-10-15 00:00:00', '2024-10-16 00:00:00', '2024-10-16'),
(218, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:11:03', '2', '92', '2024-10-17 00:00:00', '2024-10-26 00:00:00', '2024-10-17'),
(219, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:11:20', '2', '123', '2024-10-17 00:00:00', '2024-10-19 00:00:00', '2024-10-17'),
(220, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:12:29', '2', '123', '2024-10-21 00:00:00', '2024-10-24 00:00:00', '2024-10-17'),
(221, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:13:03', '2', '123', '2024-10-17 00:00:00', '2024-10-26 00:00:00', '2024-10-17'),
(222, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:13:14', '2', '123', '2024-10-25 00:00:00', '2024-10-26 00:00:00', '2024-10-17'),
(223, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:25:23', '2', '123', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-17'),
(224, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:27:20', '2', '123', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-17'),
(225, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:27:39', '2', '123', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-17'),
(226, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 17:28:11', '2', '123', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-17'),
(227, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:11:10', '2', '123', '2024-10-17 00:00:00', '2024-10-18 00:00:00', '2024-10-17'),
(228, '123321', 'tayakon tapalad', '', 4, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:14:36', '2', '123', '2024-10-21 00:00:00', '2024-10-25 00:00:00', '2024-10-17'),
(229, '123321', 'tayakon tapalad', '', 3, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:16:44', '2', '123', '2024-10-17 00:00:00', '2024-10-21 00:00:00', '2024-10-17'),
(230, '123321', 'tayakon tapalad', '', 3, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:17:14', '2', '1', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17'),
(231, '123321', 'tayakon tapalad', '', 4, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:23:41', '2', '123', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17'),
(232, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:24:35', '2', '123', '2024-10-17 00:00:00', '2024-10-31 00:00:00', '2024-10-17'),
(233, '123321', 'tayakon tapalad', '', 2, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:28:34', '2', '123', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17'),
(234, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:29:44', '2', '123', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17'),
(235, '123321', 'tayakon tapalad', '', 2, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:30:06', '2', '213', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17'),
(236, '123321', 'tayakon tapalad', '', 1, 'พนักงานไม่เพียงพอ', '', '2024-10-16 18:31:21', '2', '123', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17'),
(237, '123321', 'tayakon tapalad', '', 1, '', '', '2024-10-16 18:52:22', '1', '123', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17'),
(238, '123321', 'tayakon tapalad', '', 2, '123', '', '2024-10-16 18:56:49', '1', '123', '2024-10-17 00:00:00', '2024-10-21 00:00:00', '2024-10-17'),
(239, '123321', 'tayakon tapalad', '', 2, 'พนักงานไม่เพียงพอ', '', '2024-10-16 19:02:57', '2', '91', '2024-10-18 00:00:00', '2024-10-19 00:00:00', '2024-10-17'),
(240, '123321', 'tayakon tapalad', '', 2, 'ง่วงอ่าา', '', '2024-10-16 20:39:35', '1', '91', '2024-10-17 00:00:00', '2024-10-20 00:00:00', '2024-10-17'),
(241, '123321', 'tayakon tapalad', '', 3, '', '', '2024-10-16 20:42:19', '1', '123', '2024-10-17 00:00:00', '2024-10-17 00:00:00', '2024-10-17');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `leave_type_id` int(11) NOT NULL,
  `leave_type_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`leave_type_id`, `leave_type_name`) VALUES
(1, 'ลากิจ'),
(2, 'ลาป่วย'),
(3, 'ลาพักร้อน'),
(4, 'ลากรณีพิเศษ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `fk_account_id` (`account_id`);

--
-- Indexes for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD PRIMARY KEY (`leave_approvals_id`),
  ADD KEY `leave_approvals_ibfk_1` (`leave_request_id`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`employee_id`,`leave_type_id`),
  ADD KEY `leave_type_id` (`leave_type_id`);

--
-- Indexes for table `leave_conditions`
--
ALTER TABLE `leave_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`leave_requests_id`),
  ADD KEY `fk_leave_type` (`leave_type_id`),
  ADD KEY `fk_employee` (`employee_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`leave_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  MODIFY `leave_approvals_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leave_conditions`
--
ALTER TABLE `leave_conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `leave_requests_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `leave_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `fk_account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD CONSTRAINT `fk_leave_request` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`leave_requests_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leave_approvals_ibfk_1` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`leave_requests_id`);

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `leave_balances_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`leave_type_id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_leave_type` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`leave_type_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
