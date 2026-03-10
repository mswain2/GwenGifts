-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 09, 2026 at 12:59 AM
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
  `about_consent` enum('yes','no') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `over21`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `email_prefs`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `affiliation`, `branch`, `archived`, `emergency_contact_last_name`, `gender`, `t_shirt_size`, `computer_access`, `camera_access`, `transportation_access`, `skills`, `experience`, `about_consent`) VALUES
('acarmich@mail.umw.edu', '2025-12-01', 'John', 'Doe', NULL, 'Fredericksburg', 'VA', NULL, '5555555555', 'true', '', '', '', '', 'acarmich@mail.umw.edu', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$1CDYmdifcx5rfR80Ui8WLuM2ldqc4DTJiFbK1JMSLycE/0lLKPJUS', 'Family', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('ameyer3', '2025-03-26', 'Aidan', 'Meyer', '1541 Surry Hill Court', 'Charlottesville', 'VA', '22901', '4344222910', NULL, 'home', '4344222910', 'home', '2003-08-17', 'aidanmeyer32@gmail.com', NULL, 'Aidan', 'n/a', 'Father', NULL, 'volunteer', 'Active', NULL, '$2y$10$0R5pX4uTxS0JZ4rc7dGprOK4c/d1NEs0rnnaEmnW4sz8JIQVyNdBC', NULL, NULL, 0, 'Meyer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('armyuser', '2025-11-30', 'Army', 'Active Duty', NULL, 'FXBG', 'VA', NULL, '3243242342', 'true', '', '', '', '', 'example@example.com', 'false', '', '', '', '', '', '', '', '$2y$10$kdxwMq.xaGsYvl8gY/8l3.xwu9ABEhWernkR6kmro9QtNvvEjqPFi', 'Active duty', 'Army', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BobVolunteer', '2025-04-29', 'Bob', 'SPCA', '123 Dog Ave', 'Dogville', 'VA', '54321', '9806761234', NULL, 'home', '1234567788', 'home', '2020-03-03', 'fred54321@gmail.com', NULL, 'Luke', 'n/a', 'Bff', NULL, 'volunteer', 'Active', NULL, '$2y$10$4wUwAW0yoizxi5UFy1/OZu.yfYY7rzUsuYcZCdvfplLj95r7OknvG', NULL, NULL, 0, 'Blair', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('Britorsk', '2026-02-05', 'Brian', 'Prelle', NULL, 'KING GEORGE', 'VA', NULL, '5402076085', 'true', '', '', '', '', 'brian2@prelle.net', 'false', '', '', '', '', '', '', '', '$2y$10$q9wFQJ/guFjlUnR7IfJt/.MRf5bDfK8FxebznfRt644twzYepM/bC', 'Family', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('exampleuser', '2025-10-20', 'example', 'user', '', 'test', 'VA', '', '2344564645', NULL, '', '', '', '', 'example@test.com', NULL, '', 'n/a', '', NULL, 'v', 'Active', NULL, '$2y$10$J0NgBjoyg9F6YMyy/qQpv.f94OLM2r19sY80BZMhMdcl38SN5vdre', NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('fakename', '2025-12-10', 'fake', 'name', NULL, 'realtown', 'VA', NULL, '5555555555', 'true', '', '', '', '', 'fakeemail@email.email.com', 'true', '', '', '', '', '', '', '', '$2y$10$4h8ImkaTyMprwU3SzWrWx./NBI7yClMoqCkEbYJuA1/9cb0tSlUI.', 'Civilian', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('firstName', '2025-12-10', 'firstName', 'lastName', NULL, 'homeTown', 'TX', NULL, '5555555555', 'true', '', '', '', '', 'realemail@gmail.com', 'true', '', '', '', '', '', '', '', '$2y$10$og/aLBzrg195Qph9d2M/DuX2DIPhP.0sVT3vtu/WUpGCse8B.k71m', 'Civilian', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('gabriel', '2026-02-02', 'Gabriel', 'Courtney', NULL, 'King George', 'VA', NULL, '5404295285', 'true', '', '', '', '', 'gabrielcourtney04@gmail.com', 'true', '', '', '', '', '', '', '', '$2y$10$4uvfLFyFy9Ui1i8Q1r0MWuFRGYfgvVh4.iUtvXksfVJm4pZpxxtSq', 'Active duty', 'Space Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('japper', '2026-02-02', 'Jennifer', 'Polack', NULL, 'Fredericksburg', 'VA', NULL, '5406541318', 'true', '', '', '', '', 'jenniferpolack@gmail.com', 'true', '', '', '', '', '', '', '', '$2y$10$mJzI.UGPGUmYgo7HxTamkeKlsmajzLwXM6su4NdxuHYHZXIGnb0xm', 'Family', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('Jlipinsk', '2025-12-03', 'Jake', 'Lipinski', NULL, 'Williamsburg', 'VA', NULL, '7577903325', 'true', '', '', '', '', 'jlipinsk@mail.umw.edu', 'true', '', '', '', '', '', '', '', '$2y$10$qz33T0Sq760IITyYajCYOeWlHR/7sRJH.U609EUkF3R5zRiWWddkG', 'Civilian', 'Army', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('johnDoe123', '2026-02-07', 'John', 'Doe', NULL, 'Fredericksburg', 'VA', NULL, '2345678910', 'true', '', '', '', '', 'test@email.com', 'false', '', '', '', '', '', '', '', '$2y$10$LTVIuLeSZ4ferdNOe0JdTedaFHqFuEOAz7HDCQuZ4PG9kZrRJc7xS', 'Active duty', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('lukeg', '2025-04-29', 'Luke', 'Gibson', '22 N Ave', 'Fredericksburg', 'VA', '22401', '1234567890', NULL, 'cellphone', '1234567890', 'cellphone', '2025-04-28', 'volunteer@volunteer.com', NULL, 'NoName', 'n/a', 'Brother', NULL, 'volunteer', 'Active', NULL, '$2y$10$KsNVJYhvO5D287GpKYsIPuci9FnL.Eng9R6lBpaetu2Y0yVJ7Uuiq', NULL, NULL, 0, 'YesName', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('maddiev', '2025-04-28', 'maddie', 'van buren', '123 Blue st', 'fred', 'VA', '12343', '1234567890', NULL, 'cellphone', '1234567819', 'cellphone', '2003-05-17', 'mvanbure@mail.umw.edu', NULL, 'mommy', 'n/a', 'mom', NULL, 'volunteer', 'Active', NULL, '$2y$10$0mv3.e6gjqoIg.HfT5qVXOsI.Ca5E93DAy8BnT124W1PvMDxpfoxy', NULL, NULL, 0, 'van buren', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('michael_smith', '2025-03-16', 'Michael', 'Smith', '789 Pine Street', 'Charlottesville', 'VA', '22903', '4345559876', NULL, 'mobile', '4345553322', 'work', '1995-08-22', 'michaelsmith@email.com', NULL, 'Sarah', '4345553322', 'Sister', 'email', 'volunteer', 'Active', '', '$2y$10$XYZ789xyz456LMN123DEF', NULL, NULL, 0, 'Smith', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('michellevb', '2025-04-29', 'Michelle', 'Van Buren', '1234 Red St', 'Freddy', 'VA', '22401', '1234567890', NULL, 'cellphone', '0987654321', 'cellphone', '1980-08-18', 'michelle.vb@gmail.com', NULL, 'Madison', 'n/a', 'daughter', NULL, 'volunteer', 'Active', NULL, '$2y$10$bkqOWUdIJoSa6kZoRo5KH.cerZkBQf74RYsponUUgefJxNc8ExppK', NULL, NULL, 0, 'Van Buren', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('navyspouse', '2025-11-30', 'Navy', 'Spouse', NULL, 'FXBG', 'VA', NULL, '3543534543', 'true', '', '', '', '', 'example@example.com', 'false', '', '', '', '', '', '', '', '$2y$10$nqoIFq4ru0k1wLkg0E/rfupwez.x1Gg6ldEuKgC.jIQemgCEuDzkG', 'Family', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('olivia', '2026-02-04', 'Olivia', 'Blue', NULL, 'Fredericksburg', 'VA', NULL, '1112223333', 'false', '', '', '', '', 'oliviablue@gmail.com', 'false', '', '', '', '', '', '', '', '$2y$10$ew4nuUYBtx6.CbNBezMTYuAQGaxMJgxIs4I3uIx05Sb7SqxKHJO2S', 'Family', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('test_acc', '2025-04-29', 'test', 'test', 'test', 'test', 'VA', '22405', '5555555555', NULL, 'cellphone', '5555555555', 'cellphone', '2003-03-03', 'test@gmail.com', NULL, 'test', 'n/a', 't', NULL, 'volunteer', 'Active', NULL, '$2y$10$kpVA41EXvoJyv896uDBEF.fHCPmSlkVSaXjHojBl7DqbRnEm//kxy', NULL, NULL, 0, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('test_person', '2025-10-26', 'Testina', 'Tester', NULL, 'Testville', 'VA', NULL, '5555555555', 'true', 'mobile', NULL, NULL, '1980-08-18', 'testing@gmail.com', 'false', NULL, 'n/a', NULL, NULL, NULL, NULL, NULL, '$2y$10$blAQaBgCChBv5qRtBFVVAe1m6gIfwPf/wJ8HxzLFTYiY3aWpvaW8e', 'civilian', 'Army', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('test_persona', '2025-10-28', 'Testana', 'Tester', NULL, 'Testinaville', 'VA', NULL, '5555555555', 'true', NULL, NULL, NULL, NULL, 'testerana@gmail.com', 'true', NULL, 'n/a', NULL, NULL, NULL, NULL, NULL, '$2y$10$s90qlNAJE9EbgLhZbhG5vO4IGSM.PIbK3Ve9IvpfoicMwXbFEXQFi', 'active', 'air_force', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('tester4', '2025-12-01', 'tester', 'testing', NULL, 'Fredericksburg', 'VA', NULL, '5405405405', 'true', '', '', '', '', 'tester@gmail.com', 'true', '', '', '', '', '', '', '', '$2y$10$nILE/qxdpSvIgROc1uQEV.MyflEdG0IuNLQQ1c1u54MSEYKlg2LC2', 'Active duty', 'Space Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('testing123', '2025-10-26', 'Test', 'User', NULL, 'City', 'VA', NULL, '', 'true', NULL, NULL, NULL, NULL, 'example@email.com', 'true', NULL, 'n/a', NULL, NULL, NULL, NULL, NULL, '$2y$10$XbXkJUMSAGo9m1/GZQ3faebtJWbPMZYm/AeTA3jpDCaxZBNnMclxC', 'civ', 'marine_corp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('testytesty', '2026-03-08', 'hieric', 'mcgowan', NULL, '22', 'AK', NULL, '5408426399', NULL, 'cellphone', '5408426399', 'cellphone', '2026-03-07', 'q@gmail.com', 'true', '1', '', '11', '', '', '', '', '$2y$10$IdejuUFgJuawe9ZVcIuRhePQXViN.wQv05WVIZYy3pLIfkuZ9TSAy', '', '', NULL, '1', 'Other', 'XXL', 'yes', 'yes', 'yes', 'q', 'q', 'yes'),
('toaster', '2025-12-08', 'toast', 'er', NULL, 'Fredericksburg', 'VA', NULL, '5405405405', 'true', '', '', '', '', 'toaster@gmail.com', 'false', '', '', '', '', '', '', '', '$2y$10$VzLJcSjn/WFh0jeI9iFAw.McczukN4ovZuzg9vgtKFlXL3i/O9oOq', 'Civilian', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('vmsroot', NULL, 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', NULL, 'N/A', 'N/A', 'N/A', NULL, '', NULL, 'vmsroot', 'N/A', 'N/A', 'email', 'superadmin', 'Active', 'System root user account', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', NULL, NULL, 0, 'vmsroot', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('Volunteer25', '2025-04-30', 'Volley', 'McTear', '123 Dog St', 'Dogville', 'VA', '56748', '9887765543', NULL, 'home', '6565651122', 'home', '2025-04-29', 'volly@gmail.com', NULL, 'Holly', 'n/a', 'Besty', NULL, 'volunteer', 'Active', NULL, '$2y$10$45gKdbjW78pNKX/5ROtb7eU9OykSCsP/QCyTAvqBtord4J7V3Ywga', NULL, NULL, 0, 'McTear', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('Welp', '2025-12-04', 'Jake', 'Lipinski', NULL, 'Apple', 'VA', NULL, '7577903325', 'true', '', '', '', '', 'mcdonalds@happymeal.com', 'true', '', '', '', '', '', '', '', '$2y$10$LvWD62DJ6pwlVGnWenQkneWCFINzgbHgzyvaBdiLn72/WwM4wo7Iy', 'Active duty', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
