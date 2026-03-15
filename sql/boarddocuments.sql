-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 01:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gwengiftsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `boarddocuments`
--

CREATE TABLE `boarddocuments` (
  `id` int(11) NOT NULL,
  `doc_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boarddocuments`
--

INSERT INTO `boarddocuments` (`id`, `doc_name`, `file_path`, `uploaded_by`, `uploaded_at`) VALUES
(1, 'Meeting Minutes', 'board_docs/1772821036_PSC 10_6_2025 Meeting minutes.docx', 0, '2026-03-06 18:17:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boarddocuments`
--
ALTER TABLE `boarddocuments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boarddocuments`
--
ALTER TABLE `boarddocuments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE boarddocuments
  ADD COLUMN clearance_level ENUM('public', 'volunteer', 'manager', 'board_member', 'admin', 'superadmin') NOT NULL DEFAULT 'public' AFTER uploaded_at,
  ADD COLUMN deleted TINYINT(1) NOT NULL DEFAULT 0 AFTER clearance_level,
  ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER deleted,
  ADD COLUMN deleted_by VARCHAR(255) NULL DEFAULT NULL AFTER deleted_at;