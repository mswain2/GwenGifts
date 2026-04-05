-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 07:08 PM
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
-- Database: `gwengiftsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `dbpersonhours`
--

CREATE TABLE `dbpersonhours` (
  `personID` varchar(256) NOT NULL,
  `eventID` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbpersonhours`
--

INSERT INTO `dbpersonhours` (`personID`, `eventID`, `start_time`, `end_time`, `status`) VALUES
('john_doe', 100, '2024-11-23 22:00:00', '2024-11-23 23:00:00', 'pending'),
('john_doe', 100, '2024-11-23 22:00:00', '2024-11-23 23:00:00', 'pending'),
('vmsroot', 186, '2026-02-06 16:13:21', '2026-02-06 16:13:23', 'pending'),
('vmsroot', 186, '2026-02-06 16:13:25', NULL, 'pending'),
('vmsroot', 192, '2026-03-31 22:58:13', '2026-03-31 22:58:28', 'pending'),
('bluehaze', 192, '2026-03-31 23:00:36', '2026-03-31 23:00:38', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 01:30:00', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 23:00:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 23:00:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 23:07:28', '2026-03-31 23:07:31', 'pending'),
('bluehaze', 192, '2026-03-31 23:00:00', '2026-04-01 00:00:00', 'pending'),
('redhaze', 192, '2026-03-31 23:12:43', '2026-03-31 23:12:46', 'pending'),
('redhaze', 192, '2026-03-31 23:00:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 17:00:00', '2026-03-31 19:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 23:00:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 23:00:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 01:30:00', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 01:30:00', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 00:00:00', 'pending'),
('redhaze', 193, '2026-04-01 11:57:43', '2026-04-01 11:57:45', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 00:00:00', 'pending'),
('bluehaze', 192, '2026-03-31 21:30:00', '2026-04-01 00:00:00', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbpersonhours`
--
ALTER TABLE `dbpersonhours`
  ADD KEY `FkpersonID2` (`personID`),
  ADD KEY `FKeventID3` (`eventID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
