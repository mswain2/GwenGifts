-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 15, 2026 at 06:26 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `dbavailabilities`
--

CREATE TABLE `dbavailabilities` (
  `id` int(11) NOT NULL,
  `person_id` varchar(256) NOT NULL,
  `day` varchar(20) NOT NULL,
  `start_time` varchar(10) DEFAULT NULL,
  `end_time` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbavailabilities`
--

INSERT INTO `dbavailabilities` (`id`, `person_id`, `day`, `start_time`, `end_time`) VALUES
(2, 'pleakyyyy', 'Monday', '12am', '4am'),
(3, 'pleakyyyy', 'Wednesday', '6am', '4pm'),
(4, 'pleakyyyy', 'Saturday', '8am', '12pm'),
(5, 'bagel', 'Monday', '12am', '4am'),
(6, 'bagel', 'Wednesday', '6am', '4pm'),
(7, 'bagel', 'Saturday', '8am', '12pm'),
(8, 'pleakerson', 'Monday', '12am', '4am'),
(9, 'pleakerson', 'Wednesday', '6am', '4pm'),
(10, 'pleakerson', 'Saturday', '8am', '12pm'),
(11, 'mickeymau', 'Sunday', '12am', '5am'),
(12, 'mickeymau', 'Tuesday', '5am', '12pm'),
(13, 'pouch', 'Sunday', '12am', '3am'),
(14, 'pleakly', 'Wednesday', '2am', '7am'),
(15, 'pleakly', 'Saturday', '12am', '12pm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbavailabilities`
--
ALTER TABLE `dbavailabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbavailabilities`
--
ALTER TABLE `dbavailabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbavailabilities`
--
ALTER TABLE `dbavailabilities`
  ADD CONSTRAINT `dbavailabilities_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `dbpersons` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
