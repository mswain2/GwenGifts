-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2026 at 07:23 PM
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
-- Table structure for table `dbtraining_materials`
--

CREATE TABLE `dbtraining_materials` (
  `id` int(11) NOT NULL,
  `eventID` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_by` varchar(50) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbtraining_materials`
--

INSERT INTO `dbtraining_materials` (`id`, `eventID`, `title`, `description`, `file_name`, `file_path`, `file_type`, `uploaded_by`, `uploaded_at`, `is_active`) VALUES
(4, '249', 'Test Doc #1', 'PDF training document', 'TEST DOC pdf.pdf', 'training_docs/1773532503_TEST DOC pdf.pdf', 'application/pdf', 'vmsroot', '2026-03-14 19:55:03', 1),
(5, '249', 'Test Doc #2', 'docx training document', 'TEST DOC docx.docx', 'training_docs/1773532538_TEST DOC docx.docx', 'application/vnd.openxmlformats-officedocument.word', 'vmsroot', '2026-03-14 19:55:38', 1),
(6, '249', 'Test Doc #3', 'txt training document', 'TEST DOC txt.txt', 'training_docs/1773532575_TEST DOC txt.txt', 'text/plain', 'vmsroot', '2026-03-14 19:56:15', 1),
(7, '249', 'Test Doc #4', 'Powerpoint training document', 'TEST PRES pptx.pptx', 'training_docs/1773532607_TEST PRES pptx.pptx', 'application/vnd.openxmlformats-officedocument.pres', 'vmsroot', '2026-03-14 19:56:47', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbtraining_materials`
--
ALTER TABLE `dbtraining_materials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbtraining_materials`
--
ALTER TABLE `dbtraining_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
