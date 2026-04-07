-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 04, 2026 at 09:12 PM
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
-- Table structure for table `dbpersons`
--

CREATE TABLE `dbpersons` (
  `id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` text DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text DEFAULT NULL,
  `street_address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip_code` text DEFAULT NULL,
  `phone1` varchar(12) DEFAULT NULL,
  `over21` enum('true','false') DEFAULT NULL,
  `phone1type` text DEFAULT NULL,
  `emergency_contact_phone` varchar(12) DEFAULT NULL,
  `emergency_contact_phone_type` text DEFAULT NULL,
  `birthday` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `email_prefs` enum('true','false') DEFAULT NULL,
  `emergency_contact_first_name` text DEFAULT NULL,
  `contact_num` varchar(255) DEFAULT 'n/a',
  `emergency_contact_relation` text DEFAULT NULL,
  `contact_method` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `affiliation` varchar(100) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL,
  `emergency_contact_last_name` text DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `t_shirt_size` varchar(10) DEFAULT NULL,
  `computer_access` enum('yes','no') DEFAULT NULL,
  `camera_access` enum('yes','no') DEFAULT NULL,
  `transportation_access` enum('yes','no') DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `about_consent` enum('yes','no') DEFAULT NULL,
  `profile_pic` varchar(512) DEFAULT 'images/usaicon.png',
  `force_password_change` tinyint(1) DEFAULT 0,
  `cpr_training_completion` enum('yes','no') DEFAULT 'no',
  `aed_training_completion` enum('yes','no') DEFAULT 'no',
  `has_disability` enum('yes','no') DEFAULT 'no',
  `disability_specifications` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `over21`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `email_prefs`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `affiliation`, `branch`, `archived`, `emergency_contact_last_name`, `gender`, `t_shirt_size`, `computer_access`, `camera_access`, `transportation_access`, `skills`, `experience`, `about_consent`, `profile_pic`, `force_password_change`, `cpr_training_completion`, `aed_training_completion`, `has_disability`, `disability_specifications`) VALUES
('', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'true', NULL, 'n/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'yes', 'images/usaicon.png', 0, 'no', 'no', 'no', NULL),
INSERT INTO `dbpersons` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `over21`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `email_prefs`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `affiliation`, `branch`, `archived`, `emergency_contact_last_name`, `gender`, `t_shirt_size`, `computer_access`, `camera_access`, `transportation_access`, `skills`, `experience`, `about_consent`, `profile_pic`, `force_password_change`, `cpr_training_completion`, `aed_training_completion`, `has_disability`, `disability_specifications`) VALUES 
('bokchoyy', '2026-03-15', 'Bok', 'Choy', 'Q', 'Q', 'VA', '11111', '1111111111', NULL, 'cellphone', '1111111111', 'cellphone', '2004-08-06', 'g@gmail.com', 'true', 'Q', '', 'Q', '', 'event_manager', 'Active', '', '$2y$10$0c6vmXE1LzwgRGLjSOG2luKQDWKNZxR/gJ7QScZEoO09LE2nHQP6.', '', '', NULL, 'Q', 'Other', 'XXL', 'yes', 'yes', 'yes', 'Q', 'Q', 'yes', 'images/profile_pics/pfp_bokchoyy_1775318114.jpg', '0', 'yes', 'yes', 'yes', 'Wheelchair')
('blueydingo123', NULL, 'Bluey', 'Dingo', '12 Doggo Court', 'Dingo Valley', 'VA', '12345', '555-555-5555', NULL, 'home', '888-888-8888', 'home', '11/11/2025', 'blueythedingo@email.com', NULL, 'Mum', 'n/a', 'Mother', NULL, 'volunteer', 'Active', NULL, 'LoveDingos6', NULL, NULL, 0, 'Dingo', 'Female', 'M', 'yes', 'yes', 'yes', 'q', 'q', 'yes', 'images/usaicon.png', 0, 'no', 'no', 'no', NULL),
('pichu_dude', '2026-03-27', 'Pichu', 'Pikachu', '1 Pallet Town', 'Kanto', 'VA', '11111', '1111111111', NULL, 'cellphone', '1111111111', 'cellphone', '2011-01-01', 'pichu@gmail.com', 'true', 'Raichu', '', 'Mother', '', 'volunteer', 'Active', '', '$2y$10$rMXH8JkFIwgD6OXbHOfImu16UGbnlgyc736srHAOkIMzjO7LMbeDu', '', '', NULL, 'Pikachu', 'Female', 'S', 'yes', 'yes', 'yes', 'Quick Attack', 'Not much', 'yes', 'images/usaicon.png', '0', 'no', 'no', 'no', NULL)
('vmsroot', NULL, 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', NULL, 'N/A', 'N/A', 'N/A', NULL, '', NULL, 'vmsroot', 'N/A', 'N/A', 'email', 'superadmin', 'Active', 'System root user account', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', NULL, NULL, 0, 'vmsroot', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'images/oshawott.jpg', 0, 'no', 'no', 'no', NULL),

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbpersons`
--
ALTER TABLE `dbpersons`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
