-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2026 at 10:34 PM
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
  `description` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` text DEFAULT NULL,
  `access` enum('Public','Private') NOT NULL DEFAULT 'Public',
  `completed` enum('Y','N') NOT NULL DEFAULT 'N',
  `series_id` varchar(32) DEFAULT NULL,
  `recurrence_interval_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbevents`
--

INSERT INTO `dbevents` (`id`, `name`, `abbr_name`, `type`, `startDate`, `startTime`, `endTime`, `endDate`, `description`, `capacity`, `location`, `access`, `completed`, `series_id`, `recurrence_interval_days`) VALUES
(378, 'Daily', 'Daily Event', 'Normal', '2026-03-21', '05:16', '07:16', '2026-03-21', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(379, 'Daily', 'Daily Event', 'Normal', '2026-03-22', '05:16', '07:16', '2026-03-22', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(380, 'Daily', 'Daily Event', 'Normal', '2026-03-23', '05:16', '07:16', '2026-03-23', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(381, 'Daily', 'Daily Event', 'Normal', '2026-03-24', '05:16', '07:16', '2026-03-24', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(382, 'Daily', 'Daily Event', 'Normal', '2026-03-25', '05:16', '07:16', '2026-03-25', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(383, 'Daily', 'Daily Event', 'Normal', '2026-03-26', '05:16', '07:16', '2026-03-26', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(384, 'Daily', 'Daily Event', 'Normal', '2026-03-27', '05:16', '07:16', '2026-03-27', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(385, 'Daily', 'Daily Event', 'Normal', '2026-03-28', '05:16', '07:16', '2026-03-28', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(386, 'Daily', 'Daily Event', 'Normal', '2026-03-29', '05:16', '07:16', '2026-03-29', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(387, 'Daily', 'Daily Event', 'Normal', '2026-03-30', '05:16', '07:16', '2026-03-30', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(388, 'Daily', 'Daily Event', 'Normal', '2026-03-31', '05:16', '07:16', '2026-03-31', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(389, 'Daily', 'Daily Event', 'Normal', '2026-04-01', '05:16', '07:16', '2026-04-01', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(390, 'Daily', 'Daily Event', 'Normal', '2026-04-02', '05:16', '07:16', '2026-04-02', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(391, 'Daily', 'Daily Event', 'Normal', '2026-04-03', '05:16', '07:16', '2026-04-03', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(392, 'Daily', 'Daily Event', 'Normal', '2026-04-04', '05:16', '07:16', '2026-04-04', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(393, 'Daily', 'Daily Event', 'Normal', '2026-04-05', '05:16', '07:16', '2026-04-05', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(394, 'Daily', 'Daily Event', 'Normal', '2026-04-06', '05:16', '07:16', '2026-04-06', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(395, 'Daily', 'Daily Event', 'Normal', '2026-04-07', '05:16', '07:16', '2026-04-07', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(396, 'Daily', 'Daily Event', 'Normal', '2026-04-08', '05:16', '07:16', '2026-04-08', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(397, 'Daily', 'Daily Event', 'Normal', '2026-04-09', '05:16', '07:16', '2026-04-09', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(398, 'Daily', 'Daily Event', 'Normal', '2026-04-10', '05:16', '07:16', '2026-04-10', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(399, 'Daily', 'Daily Event', 'Normal', '2026-04-11', '05:16', '07:16', '2026-04-11', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(400, 'Daily', 'Daily Event', 'Normal', '2026-04-12', '05:16', '07:16', '2026-04-12', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(401, 'Daily', 'Daily Event', 'Normal', '2026-04-13', '05:16', '07:16', '2026-04-13', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(402, 'Daily', 'Daily Event', 'Normal', '2026-04-14', '05:16', '07:16', '2026-04-14', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(403, 'Daily', 'Daily Event', 'Normal', '2026-04-15', '05:16', '07:16', '2026-04-15', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(404, 'Daily', 'Daily Event', 'Normal', '2026-04-16', '05:16', '07:16', '2026-04-16', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(405, 'Daily', 'Daily Event', 'Normal', '2026-04-17', '05:16', '07:16', '2026-04-17', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(406, 'Daily', 'Daily Event', 'Normal', '2026-04-18', '05:16', '07:16', '2026-04-18', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(407, 'Daily', 'Daily Event', 'Normal', '2026-04-19', '05:16', '07:16', '2026-04-19', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(408, 'Daily', 'Daily Event', 'Normal', '2026-04-20', '05:16', '07:16', '2026-04-20', 'early morning event', 3, '', 'Public', 'N', '7e9d2d99630ac43b76157b36b23d2d23', 1),
(409, 'Weekly', 'Weekly Event', 'Normal', '2026-03-21', '18:17', '19:17', '2026-03-21', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(410, 'Weekly', 'Weekly Event', 'Normal', '2026-03-28', '18:17', '19:17', '2026-03-28', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(411, 'Weekly', 'Weekly Event', 'Normal', '2026-04-04', '18:17', '19:17', '2026-04-04', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(412, 'Weekly', 'Weekly Event', 'Normal', '2026-04-11', '18:17', '19:17', '2026-04-11', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(413, 'Weekly', 'Weekly Event', 'Normal', '2026-04-18', '18:17', '19:17', '2026-04-18', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(414, 'Weekly', 'Weekly Event', 'Normal', '2026-04-25', '18:17', '19:17', '2026-04-25', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(415, 'Weekly', 'Weekly Event', 'Normal', '2026-05-02', '18:17', '19:17', '2026-05-02', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(416, 'Weekly', 'Weekly Event', 'Normal', '2026-05-09', '18:17', '19:17', '2026-05-09', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(417, 'Weekly', 'Weekly Event', 'Normal', '2026-05-16', '18:17', '19:17', '2026-05-16', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(418, 'Weekly', 'Weekly Event', 'Normal', '2026-05-23', '18:17', '19:17', '2026-05-23', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(419, 'Weekly', 'Weekly Event', 'Normal', '2026-05-30', '18:17', '19:17', '2026-05-30', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(420, 'Weekly', 'Weekly Event', 'Normal', '2026-06-06', '18:17', '19:17', '2026-06-06', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(421, 'Weekly', 'Weekly Event', 'Normal', '2026-06-13', '18:17', '19:17', '2026-06-13', 'weekly meeting', 3, '', 'Public', 'N', '87772a0d8e0ad040ee83ca4ef96c4559', 7),
(422, 'Monthly', 'Monthly Event', 'Normal', '2026-03-21', '20:18', '22:18', '2026-03-21', 'Monthly event', 3, '', 'Public', 'N', '0f97d29239bdbef73b309456adfa90c9', 30),
(423, 'Monthly', 'Monthly Event', 'Normal', '2026-04-21', '20:18', '22:18', '2026-04-21', 'Monthly event', 3, '', 'Public', 'N', '0f97d29239bdbef73b309456adfa90c9', 30),
(424, 'Monthly', 'Monthly Event', 'Normal', '2026-05-21', '20:18', '22:18', '2026-05-21', 'Monthly event', 3, '', 'Public', 'N', '0f97d29239bdbef73b309456adfa90c9', 30),
(425, 'Monthly', 'Monthly Event', 'Normal', '2026-06-21', '20:18', '22:18', '2026-06-21', 'Monthly event', 3, '', 'Public', 'N', '0f97d29239bdbef73b309456adfa90c9', 30),
(426, 'Monthly', 'Monthly Event', 'Normal', '2026-07-21', '20:18', '22:18', '2026-07-21', 'Monthly event', 3, '', 'Public', 'N', '0f97d29239bdbef73b309456adfa90c9', 30),
(427, 'Monthly', 'Monthly Event', 'Normal', '2026-08-21', '20:18', '22:18', '2026-08-21', 'Monthly event', 3, '', 'Public', 'N', '0f97d29239bdbef73b309456adfa90c9', 30),
(428, 'Monthly', 'Monthly Event', 'Normal', '2026-09-21', '20:18', '22:18', '2026-09-21', 'Monthly event', 3, '', 'Public', 'N', '0f97d29239bdbef73b309456adfa90c9', 30),
(429, 'Custom', 'Custom Event', 'Normal', '2026-03-23', '18:18', '19:18', '2026-03-23', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(430, 'Custom', 'Custom Event', 'Normal', '2026-03-26', '18:18', '19:18', '2026-03-26', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(431, 'Custom', 'Custom Event', 'Normal', '2026-03-29', '18:18', '19:18', '2026-03-29', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(432, 'Custom', 'Custom Event', 'Normal', '2026-04-01', '18:18', '19:18', '2026-04-01', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(433, 'Custom', 'Custom Event', 'Normal', '2026-04-04', '18:18', '19:18', '2026-04-04', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(434, 'Custom', 'Custom Event', 'Normal', '2026-04-07', '18:18', '19:18', '2026-04-07', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(435, 'Custom', 'Custom Event', 'Normal', '2026-04-10', '18:18', '19:18', '2026-04-10', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(436, 'Custom', 'Custom Event', 'Normal', '2026-04-13', '18:18', '19:18', '2026-04-13', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(437, 'Custom', 'Custom Event', 'Normal', '2026-04-16', '18:18', '19:18', '2026-04-16', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(438, 'Custom', 'Custom Event', 'Normal', '2026-04-19', '18:18', '19:18', '2026-04-19', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(439, 'Custom', 'Custom Event', 'Normal', '2026-04-22', '18:18', '19:18', '2026-04-22', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(440, 'Custom', 'Custom Event', 'Normal', '2026-04-25', '18:18', '19:18', '2026-04-25', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3),
(441, 'Custom', 'Custom Event', 'Normal', '2026-04-28', '18:18', '19:18', '2026-04-28', 'Custom event', 3, '', 'Public', 'N', '62ae330df4ee25ee0241dd7c95e5d630', 3);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=586;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
