-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2026 at 02:43 PM
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
-- Table structure for table `dbeventcomments`
--

CREATE TABLE `dbeventcomments` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `event_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbeventcomments`
--

INSERT INTO `dbeventcomments` (`id`, `user_id`, `event_id`, `comment`, `uploaded_at`) VALUES
(14, 'vmsroot', 1757, 'Had a great time!', '2026-04-03 18:10:06'),
(15, 'bluey', 1756, 'Here is a volunteer comment!', '2026-04-03 18:12:41'),
(16, 'bluey', 1757, 'I also had a great time! Wonderful event!', '2026-04-03 18:13:11'),
(17, 'vmsroot', 1757, 'Here is a comment!', '2026-04-04 08:41:40'),
(18, 'vmsroot', 1757, 'I\'m trying to get over ten comments', '2026-04-04 08:41:50'),
(19, 'vmsroot', 1757, 'Here is another one!', '2026-04-04 08:41:56'),
(20, 'vmsroot', 1757, 'Event comments are so cool.', '2026-04-04 08:42:04'),
(21, 'vmsroot', 1757, 'Dallas is a cute beagle', '2026-04-04 08:42:10'),
(22, 'vmsroot', 1757, 'I\'m tired.', '2026-04-04 08:42:15'),
(23, 'vmsroot', 1757, 'Only a few weeks left!', '2026-04-04 08:42:27'),
(24, 'vmsroot', 1757, 'Please say this is 11.', '2026-04-04 08:42:36'),
(25, 'vmsroot', 1757, 'It was in fact not the eleventh one', '2026-04-04 08:42:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbeventcomments`
--
ALTER TABLE `dbeventcomments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbeventcomments`
--
ALTER TABLE `dbeventcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
