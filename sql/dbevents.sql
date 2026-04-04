-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2026 at 09:26 PM
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
-- Table structure for table `dbevents`
--

CREATE TABLE `dbevents` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `abbr_name` text NOT NULL,
  `type` enum('Retreat','Normal') NOT NULL DEFAULT 'Normal',
  `startDate` char(10) NOT NULL,
  `startTime` char(5) NOT NULL,
  `endTime` char(5) NOT NULL,
  `endDate` char(10) NOT NULL,
  `timezone` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` text DEFAULT NULL,
  `access` enum('Public','Private') NOT NULL DEFAULT 'Public',
  `completed` enum('Y','N') NOT NULL DEFAULT 'N',
  `board_event` tinyint(1) NOT NULL DEFAULT 0,
  `series_id` varchar(32) DEFAULT NULL,
  `recurrence_interval_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbevents`
--

INSERT INTO `dbevents` (`id`, `name`, `abbr_name`, `type`, `startDate`, `startTime`, `endTime`, `endDate`, `timezone`, `description`, `capacity`, `location`, `access`, `completed`, `board_event`, `series_id`, `recurrence_interval_days`) VALUES
(1678, 'Custom Event Test', 'Custom Test', 'Normal', '2026-03-29', '11:12', '12:11', '2026-03-29', '', 'Custom test', 1, '', 'Public', 'N', 0, 'dc2ee3fbdc6bac798ab8c6d1bf8309e8', 3),
(1691, 'Custom \'Event\'', 'Event\'\'', 'Normal', '2026-03-31', '01:43', '03:43', '2026-03-31', '', 'Apostrophes\'ss', 3, '', 'Public', 'N', 0, NULL, 0),
(1730, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-04-30', '11:12', '12:11', '2026-04-30', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1740, 'Board Meeting', 'Meeting', 'Normal', '2026-03-31', '09:02', '10:02', '2026-03-31', '', '', 999, '', 'Public', 'N', 1, NULL, 0),
(1741, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-05-07', '11:12', '12:11', '2026-05-07', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1742, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-05-14', '11:12', '12:11', '2026-05-14', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1743, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-05-21', '11:12', '12:11', '2026-05-21', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1744, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-05-28', '11:12', '12:11', '2026-05-28', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1745, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-06-04', '11:12', '12:11', '2026-06-04', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1746, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-06-11', '11:12', '12:11', '2026-06-11', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1747, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-06-18', '11:12', '12:11', '2026-06-18', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1748, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-06-25', '11:12', '12:11', '2026-06-25', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1749, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-07-02', '11:12', '12:11', '2026-07-02', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1750, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-07-09', '11:12', '12:11', '2026-07-09', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1751, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-07-16', '11:12', '12:11', '2026-07-16', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1752, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-07-23', '11:12', '12:11', '2026-07-23', '', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
(1753, 'Q3 Board Meeting', 'AA', 'Normal', '2026-04-01', '09:58', '11:00', '2026-04-01', '', 'NOotes\nRelated URL: http://localhost/GwenGifts/addBoardMeeting.php\nRelated Documents: Meeting Minutes', 999, 'Zoom', 'Public', 'N', 1, NULL, 0),
(1755, 'Custom \'Event\'', 'Event\'\'', 'Normal', '2026-04-03', '18:09', '19:09', '2026-04-03', 'America/New_York', 'Apostrophes\'ss', 3, '', 'Public', 'N', 0, NULL, 0),
(1756, 'Custom \'Event\'', 'Event\'\'', 'Normal', '2026-04-03', '17:14', '22:14', '2026-04-03', 'America/Chicago', 'Apostrophes\'ss', 3, '', 'Public', 'N', 0, NULL, 0),
(1757, 'Custom Event Test', 'Custom', 'Normal', '2026-04-04', '16:38', '18:38', '2026-04-04', 'America/New_York', 'Here is a description.\r\nWith a next line.', 3, 'IDK', 'Public', 'N', 0, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbevents`
--
ALTER TABLE `dbevents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbevents`
--
ALTER TABLE `dbevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1758;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
