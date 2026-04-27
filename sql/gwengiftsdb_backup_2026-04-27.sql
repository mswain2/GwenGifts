-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2026 at 06:40 AM
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
-- Table structure for table `boarddocuments`
--

CREATE TABLE `boarddocuments` (
  `id` int(11) NOT NULL,
  `doc_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `clearance_level` enum('volunteer','event_manager','board_member','admin','superadmin') NOT NULL DEFAULT 'volunteer',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boarddocuments`
--

INSERT INTO `boarddocuments` (`id`, `doc_name`, `file_path`, `uploaded_by`, `uploaded_at`, `clearance_level`, `deleted`, `deleted_at`, `deleted_by`) VALUES
(3, 'ANOTHER TXT', 'board_docs/1773674109_z_words.txt', 'vmsroot', '2026-03-16 15:15:09', 'superadmin', 1, '2026-03-24 00:30:27', 'vmsroot'),
(6, 'Minutes 3/20/25', 'board_docs/1774439780_quizScores.txt', 'vmsroot', '2026-03-25 11:56:20', 'admin', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dbapplications`
--

CREATE TABLE `dbapplications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(256) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` enum('Approved','Denied','Pending') NOT NULL DEFAULT 'Pending',
  `flagged` tinyint(1) NOT NULL DEFAULT 0,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbapplications`
--

INSERT INTO `dbapplications` (`id`, `user_id`, `event_id`, `status`, `flagged`, `note`) VALUES
(1, 'test_person', 118, 'Denied', 0, 'TEST'),
(2, 'test_acc', 121, 'Denied', 0, 'DENIED'),
(3, 'test_persona', 126, 'Approved', 0, ''),
(4, 'navyspouse', 178, 'Denied', 0, 'Example denial message'),
(5, 'vmsroot', 173, 'Approved', 0, ''),
(6, 'vmsroot', 173, 'Approved', 0, ''),
(7, 'edarnell', 180, 'Denied', 1, 'DENY');

-- --------------------------------------------------------

--
-- Table structure for table `dbapplication_comments`
--

CREATE TABLE `dbapplication_comments` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `user_id` varchar(256) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbarchived_volunteers`
--

CREATE TABLE `dbarchived_volunteers` (
  `id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` text DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text DEFAULT NULL,
  `street_address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` text DEFAULT NULL,
  `zip_code` text DEFAULT NULL,
  `phone1` varchar(12) NOT NULL,
  `phone1type` text DEFAULT NULL,
  `emergency_contact_phone` varchar(12) DEFAULT NULL,
  `emergency_contact_phone_type` text DEFAULT NULL,
  `birthday` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `emergency_contact_first_name` text NOT NULL,
  `contact_num` varchar(12) NOT NULL,
  `emergency_contact_relation` text NOT NULL,
  `contact_method` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `skills` text NOT NULL,
  `interests` text NOT NULL,
  `archived_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `emergency_contact_last_name` text NOT NULL,
  `is_new_volunteer` tinyint(1) NOT NULL DEFAULT 1,
  `is_community_service_volunteer` tinyint(1) NOT NULL DEFAULT 0,
  `total_hours_volunteered` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dbarchived_volunteers`
--

INSERT INTO `dbarchived_volunteers` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `skills`, `interests`, `archived_date`, `emergency_contact_last_name`, `is_new_volunteer`, `is_community_service_volunteer`, `total_hours_volunteered`) VALUES
('stephen_davies', '2022-05-10', 'Stephen', 'Davies', '456 Maple Avenue', 'Fredericksburg', 'VA', '22401', '5405557890', 'mobile', '5405551111', 'home', '1988-11-02', 'stephendavies@email.com', 'Robert', '5405551111', 'Father', 'phone', 'volunteer', 'Inactive', 'Archived due to relocation', '$2y$10$ABC789xyz456LMN123DEF', 'Music, Painting', 'Event Coordination', '2025-03-18 16:56:44', 'Davies', 0, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `dbattendance`
--

CREATE TABLE `dbattendance` (
  `id` int(11) NOT NULL,
  `eventId` int(11) NOT NULL,
  `userId` varchar(256) NOT NULL,
  `loggedById` varchar(256) DEFAULT NULL,
  `attended` tinyint(1) NOT NULL DEFAULT 0,
  `attendanceNote` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbattendance`
--

INSERT INTO `dbattendance` (`id`, `eventId`, `userId`, `loggedById`, `attended`, `attendanceNote`) VALUES
(1, 9001, 'vol_alice', 'bokchoyy', 1, NULL),
(2, 9001, 'vol_bob', 'bokchoyy', 1, NULL),
(3, 9001, 'vol_carol', 'bokchoyy', 1, NULL),
(4, 9001, 'vol_dan', 'bokchoyy', 0, 'No-show, no notice'),
(5, 9001, 'vol_emma', 'bokchoyy', 1, NULL),
(6, 9001, 'vol_karen', 'bokchoyy', 1, NULL),
(7, 9001, 'vol_leo', 'bokchoyy', 0, 'No-show'),
(8, 9001, 'vol_mia', 'bokchoyy', 1, NULL),
(9, 9002, 'vol_alice', 'bokchoyy', 1, NULL),
(10, 9002, 'vol_bob', 'bokchoyy', 1, NULL),
(11, 9002, 'vol_carol', 'bokchoyy', 0, 'Called in sick'),
(12, 9002, 'vol_emma', 'bokchoyy', 1, NULL),
(13, 9002, 'vol_frank', 'bokchoyy', 1, NULL),
(14, 9002, 'vol_karen', 'bokchoyy', 0, 'No-show'),
(15, 9002, 'vol_mia', 'bokchoyy', 1, NULL),
(16, 9003, 'vol_alice', 'bokchoyy', 1, NULL),
(17, 9003, 'vol_bob', 'bokchoyy', 1, NULL),
(18, 9003, 'vol_carol', 'bokchoyy', 1, NULL),
(19, 9003, 'vol_dan', 'bokchoyy', 1, NULL),
(20, 9003, 'vol_emma', 'bokchoyy', 1, NULL),
(21, 9003, 'vol_frank', 'bokchoyy', 1, NULL),
(22, 9003, 'vol_grace', 'bokchoyy', 1, NULL),
(23, 9003, 'vol_karen', 'bokchoyy', 0, 'No-show'),
(24, 9003, 'vol_leo', 'bokchoyy', 0, 'No-show'),
(25, 9004, 'vol_alice', 'bokchoyy', 1, NULL),
(26, 9004, 'vol_carol', 'bokchoyy', 1, NULL),
(27, 9004, 'vol_emma', 'bokchoyy', 1, NULL),
(28, 9004, 'vol_grace', 'bokchoyy', 1, NULL),
(29, 9004, 'vol_hank', 'bokchoyy', 1, NULL),
(30, 9004, 'vol_mia', 'bokchoyy', 0, 'Cancelled last minute'),
(31, 9005, 'vol_alice', 'bokchoyy', 1, NULL),
(32, 9005, 'vol_bob', 'bokchoyy', 1, NULL),
(33, 9005, 'vol_carol', 'bokchoyy', 1, NULL),
(34, 9005, 'vol_dan', 'bokchoyy', 1, NULL),
(35, 9005, 'vol_frank', 'bokchoyy', 1, NULL),
(36, 9005, 'vol_grace', 'bokchoyy', 1, NULL),
(37, 9005, 'vol_hank', 'bokchoyy', 0, 'Injury'),
(38, 9005, 'vol_iris', 'bokchoyy', 1, NULL),
(39, 9005, 'vol_jack', 'bokchoyy', 1, NULL),
(40, 9005, 'vol_leo', 'bokchoyy', 0, 'No-show'),
(41, 9006, 'vol_alice', 'bokchoyy', 1, NULL),
(42, 9006, 'vol_emma', 'bokchoyy', 1, NULL),
(43, 9006, 'vol_grace', 'bokchoyy', 1, NULL),
(44, 9006, 'vol_iris', 'bokchoyy', 1, NULL),
(45, 9006, 'vol_jack', 'bokchoyy', 0, 'Schedule conflict'),
(46, 9009, 'janedoe1', 'blueydingo123', 1, ''),
(47, 9009, 'turkeybird123', 'blueydingo123', 0, ''),
(49, 9009, 'blueydingo123', 'blueydingo123', 1, ''),
(50, 9011, 'janedoe1', 'blueydingo123', 1, '');

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
(66, 'turkeybird123', 'Sunday', '6pm', '7pm'),
(67, 'turkeybird123', 'Monday', '6pm', '7pm'),
(86, 'blueydingo123', 'Sunday', '1pm', '2pm'),
(87, 'blueydingo123', 'Thursday', '3pm', '4pm');

-- --------------------------------------------------------

--
-- Table structure for table `dbdiscussions`
--

CREATE TABLE `dbdiscussions` (
  `author_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `edited_by` varchar(256) DEFAULT NULL,
  `edited_at` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbdiscussions`
--

INSERT INTO `dbdiscussions` (`author_id`, `title`, `body`, `time`, `category`, `edited_by`, `edited_at`) VALUES
('blueydingo123', 'From vmsroot this is board', 'bored board bored. Edited.', '2026-03-20-21:29', 'board', 'vmsroot', '2026-03-23-20:34'),
('vmsroot', 'Another Late Night Talk', 'See where this goes...', '2026-03-20-21:27', 'general', NULL, NULL),
('vmsroot', 'Another Test Discussion', 'Yeah. But edited. Hmm.', '2026-03-20-20:27', 'general', 'vmsroot', '2026-03-20-20:27'),
('vmsroot', 'Apostrophe\'s Discussion', 'YEAH IT\'S CRAZY.', '2026-03-28-18:18', 'general', NULL, NULL),
('vmsroot', 'Board Discussionssss', 'Many plurals.', '2026-03-20-21:28', 'board', NULL, NULL),
('vmsroot', 'From vmsroot', 'Hi', '2026-03-20-21:22', 'general', NULL, NULL),
('vmsroot', 'Late Night Talks with My Blanket', 'Hi', '2026-03-20-21:27', 'board', NULL, NULL),
('vmsroot', 'My Discussion', 'Hi there. But edited.', '2026-03-20-21:15', 'board', 'vmsroot', '2026-03-20-21:15'),
('vmsroot', 'Normal Discussion', 'Hmm', '2026-03-20-21:27', 'general', NULL, NULL),
('vmsroot', 'Temp Discussion', 'Words', '2026-03-20-20:46', 'board', NULL, NULL),
('vmsroot', 'test', 'this is test', '2025-04-30-10:13', 'general', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dbdrafts`
--

CREATE TABLE `dbdrafts` (
  `draftID` int(11) NOT NULL,
  `userID` varchar(256) NOT NULL,
  `recipientID` varchar(256) NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `scheduledSend` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `dbeventmedia`
--

CREATE TABLE `dbeventmedia` (
  `id` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `url` text NOT NULL,
  `format` text NOT NULL,
  `description` text NOT NULL,
  `uploaded_by` varchar(50) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbeventpersons`
--

CREATE TABLE `dbeventpersons` (
  `id` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `userID` varchar(256) NOT NULL,
  `notes` text DEFAULT NULL,
  `attended` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbeventpersons`
--

INSERT INTO `dbeventpersons` (`id`, `eventID`, `userID`, `notes`, `attended`) VALUES
(7, 0, 'EvanTester', 'v', 0),
(8, 0, 'EvanTester', 'p', 0),
(9, 0, 'tester4', 'v', 0),
(10, 0, 'acarmich@mail.umw.edu', 'v', 0),
(11, 0, 'armyuser', 'p', 0),
(12, 0, 'armyuser', 'p', 0),
(13, 0, 'edarnell', 'p', 0),
(14, 0, 'EvanTester', 'p', 0),
(15, 0, 'toaster', 'v', 0),
(16, 0, 'edarnell', 'p', 0),
(17, 0, 'toaster', 'p', 0),
(18, 0, 'toaster', 'Skills: dancin | Dietary restrictions:  | Disabilities: n/a | Materials: good vibes', 0),
(19, 0, 'toaster', 'Skills: dancin | Dietary restrictions:  | Disabilities: n/a | Materials: good vibes', 0),
(20, 0, 'toaster', 'v', 0),
(21, 0, 'toaster', 'v', 0),
(22, 12, 'vmsroot', 'p', 0),
(24, 165, 'edarnell', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(26, 177, 'armyuser', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(29, 129, 'test_persona', '', 0),
(30, 129, 'test_persona', '', 0),
(31, 128, 'vmsroot', 'Skills: dancin | Dietary restrictions:  | Disabilities: n/a | Materials: good vibes', 0),
(32, 164, 'vmsroot', 'Skills: dancin | Dietary restrictions:  | Disabilities: n/a | Materials: good vibes', 0),
(33, 165, 'vmsroot', 'Skills: dancin | Dietary restrictions:  | Disabilities: n/a | Materials: good vibes', 0),
(34, 174, 'toaster', 'Skills: dancin | Dietary restrictions:  | Disabilities: n/a | Materials: good vibes', 0),
(35, 165, 'fakename', 'Skills: allergies | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(36, 184, 'edarnell', 'Skills: 11 | Dietary restrictions:  | Disabilities: 22 | Materials: 33', 0),
(37, 178, 'edarnell', 'Skills: Skills | Dietary restrictions:  | Disabilities: Alergies | Materials: Nope', 0),
(38, 186, 'amongustest', 'Skills: sus | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(39, 186, 'vmsroot', 'Skills: among us | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(40, 249, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(41, 250, 'vmsroot', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(42, 252, 'vmsroot', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(43, 263, 'vmsroot', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(44, 253, 'vmsroot', '', 0),
(45, 254, 'vmsroot', '', 0),
(46, 248, 'vmsroot', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(47, 587, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(48, 588, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(49, 644, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(50, 379, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(51, 659, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(52, 430, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(53, 674, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(54, 756, 'blueydingo123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(55, 784, 'turkeybird123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(56, 744, 'turkeybird123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0),
(57, 9001, 'vol_alice', '', 1),
(58, 9001, 'vol_bob', '', 1),
(59, 9001, 'vol_carol', '', 1),
(60, 9001, 'vol_dan', '', 0),
(61, 9001, 'vol_emma', '', 1),
(62, 9001, 'vol_karen', '', 1),
(63, 9001, 'vol_leo', '', 0),
(64, 9001, 'vol_mia', '', 1),
(65, 9002, 'vol_alice', '', 1),
(66, 9002, 'vol_bob', '', 1),
(67, 9002, 'vol_carol', '', 0),
(68, 9002, 'vol_emma', '', 1),
(69, 9002, 'vol_frank', '', 1),
(70, 9002, 'vol_karen', '', 0),
(71, 9002, 'vol_mia', '', 1),
(72, 9003, 'vol_alice', '', 1),
(73, 9003, 'vol_bob', '', 1),
(74, 9003, 'vol_carol', '', 1),
(75, 9003, 'vol_dan', '', 1),
(76, 9003, 'vol_emma', '', 1),
(77, 9003, 'vol_frank', '', 1),
(78, 9003, 'vol_grace', '', 1),
(79, 9003, 'vol_karen', '', 0),
(80, 9003, 'vol_leo', '', 0),
(81, 9004, 'vol_alice', '', 1),
(82, 9004, 'vol_carol', '', 1),
(83, 9004, 'vol_emma', '', 1),
(84, 9004, 'vol_grace', '', 1),
(85, 9004, 'vol_hank', '', 1),
(86, 9004, 'vol_mia', '', 0),
(87, 9005, 'vol_alice', '', 1),
(88, 9005, 'vol_bob', '', 1),
(89, 9005, 'vol_carol', '', 1),
(90, 9005, 'vol_dan', '', 1),
(91, 9005, 'vol_frank', '', 1),
(92, 9005, 'vol_grace', '', 1),
(93, 9005, 'vol_hank', '', 0),
(94, 9005, 'vol_iris', '', 1),
(95, 9005, 'vol_jack', '', 1),
(96, 9005, 'vol_leo', '', 0),
(97, 9006, 'vol_alice', '', 1),
(98, 9006, 'vol_emma', '', 1),
(99, 9006, 'vol_grace', '', 1),
(100, 9006, 'vol_iris', '', 1),
(101, 9006, 'vol_jack', '', 0),
(102, 1730, 'word', '', 0),
(104, 9007, 'blueydingo123', '', 0),
(105, 1730, 'janedoe1', '', 0),
(106, 1730, 'blueydingo123', '', 0),
(107, 1741, 'blueydingo123', '', 0),
(108, 1741, 'janedoe1', '', 0),
(109, 1742, 'janedoe1', '', 0),
(110, 1743, 'janedoe1', '', 0),
(111, 9008, 'blueydingo123', '', 0),
(113, 9009, 'janedoe1', '', 0),
(118, 9009, 'turkeybird123', '', 0),
(120, 9009, 'blueydingo123', '', 0),
(122, 9011, 'janedoe1', '', 0),
(123, 9011, 'blueydingo123', '', 0);

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
(1730, 'Custom Event Test', 'Custom Testing', 'Normal', '2026-04-30', '13:12', '14:11', '2026-04-30', 'America/New_York', 'Custom test', 1, '', 'Public', 'N', 0, '3e626c510da2e549eb67690ca762aa74', 7),
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
(1757, 'Custom Event Test', 'Custom', 'Normal', '2026-04-04', '16:38', '18:38', '2026-04-04', 'America/New_York', 'Here is a description.\r\nWith a next line.', 3, 'IDK', 'Public', 'N', 0, NULL, 0),
(9001, 'Fall Cleanup Day', 'Fall Cleanup', 'Normal', '2025-10-18', '09:00', '13:00', '2025-10-18', 'America/New_York', 'Community park cleanup', 20, 'City Park', 'Public', 'Y', 0, NULL, 0),
(9002, 'Holiday Gift Wrapping', 'Gift Wrap', 'Normal', '2025-12-06', '10:00', '15:00', '2025-12-06', 'America/New_York', 'Wrap gifts for families', 15, 'Community Center', 'Public', 'Y', 0, NULL, 0),
(9003, 'Winter Food Drive', 'Food Drive', 'Normal', '2026-01-17', '08:00', '12:00', '2026-01-17', 'America/New_York', 'Collect and sort food donations', 25, 'Food Bank', 'Public', 'Y', 0, NULL, 0),
(9004, 'Valentine Card Making', 'Cards', 'Normal', '2026-02-08', '13:00', '16:00', '2026-02-08', 'America/New_York', 'Make cards for nursing homes', 12, 'Library', 'Public', 'Y', 0, NULL, 0),
(9005, 'Spring Trail Restoration', 'Trail Work', 'Normal', '2026-03-14', '08:00', '14:00', '2026-03-14', 'America/New_York', 'Repair and mark hiking trails', 18, 'State Park', 'Public', 'N', 0, NULL, 0),
(9006, 'Literacy Tutoring', 'Tutoring', 'Normal', '2026-03-28', '15:00', '18:00', '2026-03-28', 'America/New_York', 'After-school reading tutoring', 10, 'Elementary School', 'Public', 'N', 0, NULL, 0),
(9007, 'Checking the Date', 'Date', 'Normal', '2026-04-18', '22:00', '23:00', '2026-04-18', 'America/New_York', 'Hi', 12, '', 'Public', 'N', 0, NULL, 0),
(9008, 'Temporary', 'Temp', 'Normal', '2026-04-17', '15:30', '16:30', '2026-04-17', 'America/New_York', 'Desc', 5, '', 'Public', 'N', 0, NULL, 0),
(9009, 'Test Log Attendees', 'TLA', 'Normal', '2026-04-26', '08:00', '09:00', '2026-04-26', 'America/New_York', 'The event was created at 4:00 am but is set to start at 8:00 am.', 20, '', 'Public', 'N', 0, NULL, 0),
(9010, 'Test Meeting', 'TM', 'Normal', '2026-04-26', '08:00', '09:00', '2026-04-26', 'America/New_York', '', 999, '', 'Public', 'N', 1, NULL, 0),
(9011, 'Test Today', 'TT', 'Normal', '2026-04-27', '07:00', '08:00', '2026-04-27', 'America/New_York', 'Hello', 5, 'UMW', 'Public', 'N', 0, NULL, 0),
(9012, 'midnight', 'midnight', 'Normal', '2026-04-26', '23:50', '23:55', '2026-04-26', 'America/New_York', 'Hi', 40, '', 'Public', 'N', 0, NULL, 0),
(9013, 'Midnight Meet', 'MM', 'Normal', '2026-04-27', '00:00', '12:01', '2026-04-27', 'America/New_York', 'Hi', 30, '', 'Public', 'N', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbgroups`
--

CREATE TABLE `dbgroups` (
  `group_name` varchar(255) NOT NULL,
  `color_level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbgroups`
--

INSERT INTO `dbgroups` (`group_name`, `color_level`) VALUES
('cool guys', 'green'),
('test', 'green');

-- --------------------------------------------------------

--
-- Table structure for table `dblanguages`
--

CREATE TABLE `dblanguages` (
  `id` int(11) NOT NULL,
  `person_id` varchar(256) NOT NULL,
  `language` varchar(50) NOT NULL,
  `speaking` varchar(20) DEFAULT NULL,
  `listening` varchar(20) DEFAULT NULL,
  `reading` varchar(20) DEFAULT NULL,
  `writing` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dblanguages`
--

INSERT INTO `dblanguages` (`id`, `person_id`, `language`, `speaking`, `listening`, `reading`, `writing`) VALUES
(54, 'deletethis123', 'english', 'beginner', 'beginner', 'beginner', 'beginner'),
(55, 'janedoe1', 'english', 'beginner', 'beginner', 'beginner', 'beginner'),
(64, 'turkeybird123', 'english', 'fluent', 'fluent', 'beginner', 'intermediate'),
(65, 'turkeybird123', 'turkish', 'beginner', 'beginner', 'beginner', 'beginner'),
(84, 'blueydingo123', 'german', 'beginner', 'fluent', 'fluent', 'intermediate'),
(85, 'blueydingo123', 'dingo', 'beginner', 'beginner', 'beginner', 'beginner');

-- --------------------------------------------------------

--
-- Table structure for table `dbLog`
--

CREATE TABLE `dbLog` (
  `id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `message` text NOT NULL,
  `venue` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbmessages`
--

CREATE TABLE `dbmessages` (
  `id` int(11) NOT NULL,
  `senderID` varchar(256) NOT NULL,
  `recipientID` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `time` varchar(16) NOT NULL,
  `wasRead` tinyint(1) NOT NULL DEFAULT 0,
  `prioritylevel` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbmessages`
--

INSERT INTO `dbmessages` (`id`, `senderID`, `recipientID`, `title`, `body`, `time`, `wasRead`, `prioritylevel`) VALUES
(27, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:35', 0, 0),
(28, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:35', 0, 0),
(29, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:35', 0, 0),
(30, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:35', 0, 0),
(32, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:35', 0, 0),
(34, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:35', 0, 0),
(36, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:36', 0, 0),
(37, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:36', 0, 0),
(38, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:36', 0, 0),
(39, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:36', 0, 0),
(41, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:36', 0, 0),
(43, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:36', 0, 0),
(45, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:38', 0, 0),
(46, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:38', 0, 0),
(47, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:38', 0, 0),
(48, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:38', 0, 0),
(50, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:38', 0, 0),
(52, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-15:38', 0, 0),
(54, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-16:47', 0, 0),
(55, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-16:47', 0, 0),
(56, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-16:47', 0, 0),
(57, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-16:47', 0, 0),
(59, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-16:47', 0, 0),
(61, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-16:47', 0, 0),
(63, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:48', 0, 0),
(64, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:48', 0, 0),
(65, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:48', 0, 0),
(66, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:48', 0, 0),
(68, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:48', 0, 0),
(70, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:48', 0, 0),
(72, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:50', 0, 0),
(73, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:50', 0, 0),
(74, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:50', 0, 0),
(75, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:50', 0, 0),
(77, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:50', 0, 0),
(79, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:50', 0, 0),
(81, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:52', 0, 0),
(82, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:52', 0, 0),
(83, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:52', 0, 0),
(84, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:52', 0, 0),
(86, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:52', 0, 0),
(88, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:52', 0, 0),
(90, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:53', 0, 0),
(91, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:53', 0, 0),
(92, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:53', 0, 0),
(93, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:53', 0, 0),
(95, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:53', 0, 0),
(97, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:53', 0, 0),
(99, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(100, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(101, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(102, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(104, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(106, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(108, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(109, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(110, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(111, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(113, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(115, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-19:55', 0, 0),
(117, 'vmsroot', 'BobVolunteer', 'You have been added to a group. View under Groups page.', 'You have been added to a', '2025-04-29-19:58', 0, 0),
(119, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(120, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(121, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(122, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(124, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(126, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(128, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(129, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(130, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(131, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(133, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(135, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(137, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(138, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(139, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(140, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(142, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(144, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:01', 0, 0),
(152, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(153, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(154, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(155, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(157, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(159, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(161, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(162, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(163, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(164, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(166, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(168, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(170, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(171, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(172, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(173, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(175, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(177, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(179, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(180, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(181, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(182, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(184, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(186, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:03', 0, 0),
(188, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(189, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(190, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(191, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(193, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(195, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(197, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(198, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(199, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(200, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(202, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(204, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:06', 0, 0),
(206, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(207, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(208, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(209, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(211, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(213, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(215, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(216, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(217, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(218, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(220, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(222, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(224, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(225, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(226, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(227, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(229, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(231, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:08', 0, 0),
(233, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(234, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(235, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(236, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(238, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(240, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(242, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(243, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(244, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(245, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(247, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(249, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:09', 0, 0),
(251, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(252, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(253, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(254, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(256, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(258, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(260, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(261, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(262, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(263, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(265, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(267, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(269, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(270, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(271, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(272, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(274, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(276, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(278, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(279, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(280, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(281, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(283, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(285, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:13', 0, 0),
(288, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:54', 0, 0),
(289, 'vmsroot', 'jane_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:54', 0, 0),
(290, 'vmsroot', 'john_doe', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:54', 0, 0),
(291, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:54', 0, 0),
(292, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:54', 0, 0),
(293, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:54', 0, 0),
(295, 'vmsroot', 'volunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-20:54', 0, 0),
(300, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:21', 0, 0),
(301, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:21', 0, 0),
(302, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:21', 0, 0),
(303, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:21', 0, 0),
(309, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:30', 1, 0),
(310, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:30', 0, 0),
(311, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:30', 0, 0),
(312, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:30', 0, 0),
(313, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:30', 0, 0),
(315, 'vmsroot', 'Volunteer1', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:30', 0, 0),
(318, 'vmsroot', 'ameyer3', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-21:31', 1, 0),
(319, 'vmsroot', 'maddiev', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-21:31', 0, 0),
(323, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:43', 1, 0),
(324, 'vmsroot', 'ameyer32', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:43', 0, 0),
(325, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:43', 0, 0),
(326, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:43', 0, 0),
(327, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:43', 0, 0),
(328, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:43', 0, 0),
(330, 'vmsroot', 'Volunteer1', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:43', 0, 0),
(332, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:44', 1, 0),
(333, 'vmsroot', 'ameyer32', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:44', 0, 0),
(334, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:44', 0, 0),
(335, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:44', 0, 0),
(336, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:44', 0, 0),
(337, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:44', 0, 0),
(339, 'vmsroot', 'Volunteer1', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:44', 0, 0),
(340, 'vmsroot', 'ameyer32', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-21:45', 0, 0),
(343, 'vmsroot', 'ameyer123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 0, 0),
(344, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 1, 0),
(345, 'vmsroot', 'ameyer32', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 0, 0),
(346, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 0, 0),
(347, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 0, 0),
(348, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 0, 0),
(349, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 0, 0),
(351, 'vmsroot', 'Volunteer1', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:50', 0, 0),
(352, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:52', 1, 0),
(353, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:52', 0, 0),
(354, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:52', 0, 0),
(355, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:52', 0, 0),
(356, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-21:52', 0, 0),
(358, 'vmsroot', 'BobVolunteer', 'You have been added to a group. View under Groups page.', 'You have been added to DAWGS', '2025-04-29-21:52', 0, 0),
(360, 'vmsroot', 'lukeg', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-21:53', 0, 0),
(361, 'vmsroot', 'maddiev', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-21:53', 0, 0),
(364, 'vmsroot', 'ameyer3', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-22:00', 1, 0),
(370, 'vmsroot', 'michellevb', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-22:07', 0, 0),
(372, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 1, 0),
(373, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(374, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(375, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(376, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(377, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(381, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 1, 0),
(382, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(383, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(384, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(385, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(386, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-29-22:20', 0, 0),
(388, 'vmsroot', 'ameyer3', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-22:21', 1, 0),
(389, 'vmsroot', 'maddiev', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-22:22', 0, 0),
(392, 'vmsroot', 'test_acc', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-29-23:44', 0, 0),
(394, 'vmsroot', 'BobVolunteer', 'You have been added to a group. View under Groups page.', 'You have been added to t', '2025-04-30-08:16', 0, 0),
(395, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-30-10:13', 1, 0),
(396, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-30-10:13', 0, 0),
(397, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-30-10:13', 0, 0),
(398, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-30-10:13', 0, 0),
(399, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-30-10:13', 0, 0),
(400, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-30-10:13', 0, 0),
(401, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-04-30-10:13', 0, 0),
(405, 'vmsroot', 'ameyer3', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-30-10:15', 1, 0),
(406, 'vmsroot', 'BobVolunteer', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-30-10:15', 0, 0),
(407, 'vmsroot', 'lukeg', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-30-10:15', 0, 0),
(409, 'vmsroot', 'Volunteer25', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-30-10:21', 1, 0),
(412, 'vmsroot', 'lukeg', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-04-30-13:13', 0, 0),
(414, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 1, 0),
(415, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 0, 0),
(416, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 0, 0),
(417, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 0, 0),
(418, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 0, 0),
(419, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 0, 0),
(420, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 0, 0),
(422, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2025-05-01-11:32', 0, 0),
(423, 'vmsroot', 'maddiev', 'You have been added to a group. View under Groups page.', 'You have been added to test', '2025-05-01-11:32', 0, 0),
(427, 'vmsroot', 'vmsroot', 'You have been added to a group. View under Groups page.', 'You have been added to cool guys', '2025-09-10-11:35', 1, 0),
(428, 'vmsroot', 'vmsroot', 'vmsroot has replied to test. View under discussions page.', 'A user has replied to a discussion.', '2025-09-10-11:40', 1, 0),
(429, 'vmsroot', 'vmsroot', 'test_person has been added as a volunteer', 'New volunteer account has been created', '2025-10-26-22:59', 1, 0),
(430, 'vmsroot', 'vmsroot', 'test_persona has been added as a volunteer', 'New volunteer account has been created', '2025-10-28-13:53', 1, 0),
(431, 'vmsroot', 'test_person', 'You are now signed up for Ethan&#039;s Birthday Party!', 'Thank you for signing up for Ethan&#039;s Birthday Party!', '2025-10-29-12:21', 0, 0),
(432, 'vmsroot', 'vmsroot', 'armyuser has been added as a volunteer', 'New volunteer account has been created', '2025-11-30-14:33', 1, 0),
(433, 'vmsroot', 'vmsroot', 'navyspouse has been added as a volunteer', 'New volunteer account has been created', '2025-11-30-14:36', 1, 0),
(434, 'vmsroot', 'vmsroot', 'EvanTester has been added as a volunteer', 'New volunteer account has been created', '2025-12-01-10:38', 1, 0),
(435, 'vmsroot', 'vmsroot', 'tester4 has been added as a volunteer', 'New volunteer account has been created', '2025-12-01-11:51', 1, 0),
(436, 'vmsroot', 'vmsroot', 'acarmich@mail.umw.edu has been added as a volunteer', 'New volunteer account has been created', '2025-12-01-12:05', 1, 0),
(437, 'vmsroot', 'vmsroot', 'Jlipinsk has been added as a volunteer', 'New volunteer account has been created', '2025-12-03-18:05', 1, 0),
(438, 'vmsroot', 'vmsroot', 'edarnell has been added as a volunteer', 'New volunteer account has been created', '2025-12-03-21:56', 1, 0),
(439, 'vmsroot', 'vmsroot', 'Welp has been added as a volunteer', 'New volunteer account has been created', '2025-12-04-22:14', 1, 0),
(440, 'vmsroot', 'vmsroot', 'toaster has been added as a volunteer', 'New volunteer account has been created', '2025-12-08-16:08', 1, 0),
(441, 'vmsroot', 'vmsroot', 'toaster2 has been added as a volunteer', 'New volunteer account has been created', '2025-12-09-21:40', 1, 0),
(442, 'vmsroot', 'edarnell', 'You are now signed up for The Rat God!', 'Thank you for signing up for The Rat God!', '2025-12-10-07:30', 0, 0),
(443, 'vmsroot', 'edarnell', 'Your request to sign up for DRY RUN Retreat has been sent to an admin.', 'Your request to sign up for DRY RUN Retreat will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-07:40', 0, 0),
(444, 'vmsroot', 'edarnell', 'You are now signed up for yello :D!', 'Thank you for signing up for yello :D!', '2025-12-10-07:40', 0, 0),
(445, 'vmsroot', 'edarnell', 'You are now signed up for party :)!', 'Thank you for signing up for party :)!', '2025-12-10-07:41', 0, 0),
(446, 'vmsroot', 'vmsroot', 'Your request to sign up for Evan Darnell has been sent to an admin.', 'Your request to sign up for Evan Darnell will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-09:04', 1, 0),
(447, 'vmsroot', 'armyuser', 'Your request to sign up for 7-day Retreat has been sent to an admin.', 'Your request to sign up for 7-day Retreat will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-09:24', 0, 0),
(448, 'vmsroot', 'armyuser', 'You are now signed up for 12/18/2025!', 'Thank you for signing up for 12/18/2025!', '2025-12-10-10:05', 0, 0),
(449, 'vmsroot', 'armyuser', 'Your request to sign up for DRY RUN Retreat has been sent to an admin.', 'Your request to sign up for DRY RUN Retreat will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-10:30', 0, 0),
(450, 'vmsroot', 'navyspouse', 'Your request to sign up for DRY RUN Retreat has been sent to an admin.', 'Your request to sign up for DRY RUN Retreat will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-10:40', 0, 0),
(451, 'vmsroot', 'navyspouse', 'Your request to sign up for CMON RETREAT has been sent to an admin.', 'Your request to sign up for CMON RETREAT will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-10:41', 0, 0),
(452, 'vmsroot', 'vmsroot', 'Your request to sign up for Retreat has been sent to an admin.', 'Your request to sign up for Retreat will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-10:56', 1, 0),
(453, 'vmsroot', 'vmsroot', 'fakename has been added as a volunteer', 'New volunteer account has been created', '2025-12-10-11:25', 1, 0),
(454, 'vmsroot', 'fakename', 'You are now signed up for The Rat God!', 'Thank you for signing up for The Rat God!', '2025-12-10-11:37', 0, 0),
(455, 'vmsroot', 'fakename', 'You are now signed up for Test event before Dryrun!', 'Thank you for signing up for Test event before Dryrun!', '2025-12-10-11:55', 0, 0),
(456, 'vmsroot', 'vmsroot', 'You are now signed up for Meet n&#039; Greet!', 'Thank you for signing up for Meet n&#039; Greet!', '2025-12-10-11:59', 1, 0),
(457, 'vmsroot', 'vmsroot', 'You are now signed up for Testing!', 'Thank you for signing up for Testing!', '2025-12-10-11:59', 1, 0),
(458, 'vmsroot', 'vmsroot', 'You are now signed up for yello :D!', 'Thank you for signing up for yello :D!', '2025-12-10-12:00', 1, 0),
(459, 'vmsroot', 'toaster', 'You are now signed up for The Rat God!', 'Thank you for signing up for The Rat God!', '2025-12-10-12:01', 1, 0),
(460, 'vmsroot', 'vmsroot', 'Your request to sign up for Retreat has been sent to an admin.', 'Your request to sign up for Retreat will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-12:16', 1, 0),
(461, 'vmsroot', 'vmsroot', 'firstName has been added as a volunteer', 'New volunteer account has been created', '2025-12-10-13:22', 1, 0),
(462, 'vmsroot', 'fakename', 'You are now signed up for yello :D!', 'Thank you for signing up for yello :D!', '2025-12-10-13:24', 0, 0),
(463, 'vmsroot', 'edarnell', 'You are now signed up for Evan test!', 'Thank you for signing up for Evan test!', '2025-12-10-19:41', 0, 0),
(464, 'vmsroot', 'edarnell', 'Your request to sign up for Evan Darnell has been sent to an admin.', 'Your request to sign up for Evan Darnell will be reviewed by an admin shortly. You will get another notification when you are approved or denied.', '2025-12-10-19:42', 0, 0),
(465, 'vmsroot', 'edarnell', 'You are now signed up for CMON RETREAT!', 'Thank you for signing up for CMON RETREAT!', '2025-12-10-19:42', 0, 0),
(466, 'vmsroot', 'vmsroot', 'japper has been added as a volunteer', 'New volunteer account has been created', '2026-02-02-09:12', 1, 0),
(467, 'vmsroot', 'vmsroot', 'gabriel has been added as a volunteer', 'New volunteer account has been created', '2026-02-02-14:45', 1, 0),
(468, 'vmsroot', 'vmsroot', 'olivia has been added as a volunteer', 'New volunteer account has been created', '2026-02-04-13:19', 1, 0),
(469, 'vmsroot', 'vmsroot', 'Britorsk has been added as a volunteer', 'New volunteer account has been created', '2026-02-05-13:32', 1, 0),
(470, 'vmsroot', 'vmsroot', 'You are now signed up for Whiskey Tasting!', 'Thank you for signing up for Whiskey Tasting!', '2026-02-06-16:11', 1, 0),
(471, 'vmsroot', 'vmsroot', 'You are now signed up for Whiskey Tasting!', 'Thank you for signing up for Whiskey Tasting!', '2026-02-06-16:12', 1, 0),
(472, 'vmsroot', 'vmsroot', 'johnDoe123 has been added as a volunteer', 'New volunteer account has been created', '2026-02-07-20:46', 1, 0),
(473, 'vmsroot', 'vmsroot', 'blueydingo123 has been added as a volunteer', 'New volunteer account has been created', '2026-03-08-22:09', 1, 0),
(474, 'vmsroot', 'vmsroot', 'blueydingo123 has been added as a volunteer', 'New volunteer account has been created', '2026-03-09-17:14', 1, 0),
(475, 'vmsroot', 'vmsroot', 'turkeybird123 has been added as a volunteer', 'New volunteer account has been created', '2026-03-15-13:06', 1, 0),
(476, 'vmsroot', 'blueydingo123', 'You are now signed up for 2nd Quarter Board Review!', 'Thank you for signing up for 2nd Quarter Board Review!', '2026-03-15-14:33', 1, 0),
(477, 'vmsroot', 'vmsroot', 'vmsroot has replied to Why Am I Here?. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:10', 1, 0),
(478, 'vmsroot', 'vmsroot', 'vmsroot has replied to Why Am I Here?. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:10', 1, 0),
(479, 'vmsroot', 'vmsroot', 'vmsroot has replied to Why Am I Here?. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:10', 1, 0),
(480, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(481, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(482, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(483, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 1, 0),
(484, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(485, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(486, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(487, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(488, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(489, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(490, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(491, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(492, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(493, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(494, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(495, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(496, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(497, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(498, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(499, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(500, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(501, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(502, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(503, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(504, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(505, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(506, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 1, 0),
(507, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 1, 0),
(508, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(509, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-19-23:12', 0, 0),
(510, 'vmsroot', 'vmsroot', 'vmsroot has replied to Another Test Entry. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:13', 1, 0),
(511, 'vmsroot', 'vmsroot', 'vmsroot has replied to Another Test Entry. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:13', 1, 0),
(512, 'vmsroot', 'vmsroot', 'vmsroot has replied to Another Test Entry. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:13', 1, 0),
(513, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to test. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:17', 1, 0);
INSERT INTO `dbmessages` (`id`, `senderID`, `recipientID`, `title`, `body`, `time`, `wasRead`, `prioritylevel`) VALUES
(514, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to test. View under discussions page.', 'A user has replied to a discussion.', '2026-03-19-23:17', 1, 0),
(515, 'vmsroot', 'vmsroot', 'You are now signed up for Sample Event!', 'Thank you for signing up for Sample Event!', '2026-03-20-11:57', 1, 0),
(516, 'vmsroot', 'vmsroot', 'You are now signed up for Dragon Show!', 'Thank you for signing up for Dragon Show!', '2026-03-20-12:08', 1, 0),
(517, 'vmsroot', 'vmsroot', 'You are now signed up for Private Event!', 'Thank you for signing up for Private Event!', '2026-03-20-12:11', 1, 0),
(518, 'vmsroot', 'vmsroot', 'You are now signed up for 2 events!', 'Thank you for signing up for: Dragon Show 2, Sample Event Yippee', '2026-03-20-12:11', 1, 0),
(519, 'vmsroot', 'vmsroot', 'You are now signed up for Fredericksburg Nationals Baseball Game!', 'Thank you for signing up for Fredericksburg Nationals Baseball Game!', '2026-03-20-12:15', 1, 0),
(520, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to test. View under discussions page.', 'A user has replied to a discussion.', '2026-03-20-20:18', 1, 0),
(521, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to test. View under discussions page.', 'A user has replied to a discussion.', '2026-03-20-20:19', 1, 0),
(522, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(523, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(524, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(525, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 1, 0),
(526, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(527, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(528, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(529, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(530, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(531, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(532, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(533, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(534, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(535, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(536, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(537, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(538, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(539, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(540, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(541, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(542, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(543, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(544, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(545, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(546, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(547, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(548, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 1, 0),
(549, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 1, 0),
(550, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(551, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:23', 0, 0),
(552, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to What Do You Think of Bluey?. View under discussions page.', 'A user has replied to a discussion.', '2026-03-20-20:24', 1, 0),
(553, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to What Do You Think of Bluey?. View under discussions page.', 'A user has replied to a discussion.', '2026-03-20-20:24', 1, 0),
(554, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to What Do You Think of Bluey?. View under discussions page.', 'A user has replied to a discussion.', '2026-03-20-20:24', 1, 0),
(555, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(556, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(557, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(558, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 1, 0),
(559, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(560, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(561, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(562, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(563, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(564, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(565, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(566, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(567, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(568, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(569, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(570, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(571, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(572, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(573, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(574, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(575, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(576, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(577, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(578, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(579, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(580, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(581, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 1, 0),
(582, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 1, 0),
(583, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(584, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-20:27', 0, 0),
(585, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(586, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(587, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(588, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 1, 0),
(589, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(590, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(591, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(592, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(593, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(594, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(595, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(596, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(597, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(598, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(599, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(600, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(601, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(602, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(603, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(604, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(605, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(606, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(607, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(608, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(609, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(610, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(611, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 1, 0),
(612, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 1, 0),
(613, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(614, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:19', 0, 0),
(615, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(616, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(617, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(618, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 1, 0),
(619, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(620, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(621, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(622, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(623, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(624, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(625, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(626, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(627, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(628, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(629, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(630, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(631, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(632, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(633, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(634, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(635, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(636, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(637, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(638, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(639, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(640, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(641, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 1, 0),
(642, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 1, 0),
(643, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(644, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:22', 0, 0),
(645, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(646, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(647, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(648, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 1, 0),
(649, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(650, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(651, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(652, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(653, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(654, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(655, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(656, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(657, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(658, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(659, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(660, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(661, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(662, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(663, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(664, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(665, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(666, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(667, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(668, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(669, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(670, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(671, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 1, 0),
(672, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 1, 0),
(673, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(674, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(675, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(676, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(677, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(678, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 1, 0),
(679, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(680, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(681, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(682, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(683, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(684, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(685, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(686, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(687, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(688, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(689, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(690, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(691, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(692, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(693, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(694, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(695, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(696, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(697, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(698, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(699, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(700, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(701, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 1, 0),
(702, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 1, 0),
(703, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(704, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:27', 0, 0),
(705, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(706, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(707, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(708, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 1, 0),
(709, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(710, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(711, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(712, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(713, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(714, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(715, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(716, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(717, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(718, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(719, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(720, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(721, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(722, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(723, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(724, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(725, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(726, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(727, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(728, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(729, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(730, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(731, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 1, 0),
(732, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 1, 0),
(733, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(734, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-20-21:29', 0, 0),
(735, 'vmsroot', 'vmsroot', 'blueydingo123 has been archived.', 'This user has been archived. For reinstatement, navigate to volunteer search and select Archive, then modify the field to Active', '2026-03-20-22:12', 1, 0),
(736, 'vmsroot', 'blueydingo123', 'You are now signed up for Octopus Day!', 'Thank you for signing up for Octopus Day!', '2026-03-20-23:00', 1, 0),
(737, 'vmsroot', 'blueydingo123', 'You are now signed up for Horse Derby!', 'Thank you for signing up for Horse Derby!', '2026-03-20-23:00', 1, 0),
(738, 'vmsroot', 'blueydingo123', 'You are now signed up for Hiccup&amp;amp;amp;amp;#039;s Day!', 'Thank you for signing up for Hiccup&amp;amp;amp;amp;#039;s Day!', '2026-03-22-12:10', 1, 0),
(739, 'vmsroot', 'blueydingo123', 'You are now signed up for Daily!', 'Thank you for signing up for Daily!', '2026-03-22-12:11', 1, 0),
(740, 'vmsroot', 'vmsroot', 'blueydingo123 has been archived.', 'This user has been archived. For reinstatement, navigate to volunteer search and select Archive, then modify the field to Active', '2026-03-22-21:34', 1, 0),
(741, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Event!', 'Thank you for signing up for Test Event!', '2026-03-23-19:49', 1, 0),
(742, 'vmsroot', 'blueydingo123', 'You are now signed up for Custom!', 'Thank you for signing up for Custom!', '2026-03-23-19:49', 1, 0),
(743, 'vmsroot', 'blueydingo123', 'You are now signed up for Tester!', 'Thank you for signing up for Tester!', '2026-03-23-19:49', 1, 0),
(744, 'vmsroot', 'vmsroot', 'blueydingo123 has been archived.', 'This user has been archived. For reinstatement, navigate to volunteer search and select Archive, then modify the field to Active', '2026-03-23-20:08', 1, 0),
(745, 'vmsroot', 'vmsroot', 'vmsroot has replied to Bluey Discussion. View under discussions page.', 'A user has replied to a discussion.', '2026-03-23-20:10', 1, 0),
(746, 'vmsroot', 'vmsroot', 'blueydingo123 has replied to Another Late Night Talk. View under discussions page.', 'A user has replied to a discussion.', '2026-03-23-20:12', 1, 0),
(747, 'vmsroot', 'vmsroot', 'blueydingo123 has been archived.', 'This user has been archived. For reinstatement, navigate to volunteer search and select Archive, then modify the field to Active', '2026-03-23-20:32', 1, 0),
(748, 'vmsroot', 'vmsroot', 'vmsroot has replied to From vmsroot this is board. View under discussions page.', 'A user has replied to a discussion.', '2026-03-23-20:34', 1, 0),
(749, 'vmsroot', 'vmsroot', 'blueydingo123 has been archived.', 'This user has been archived. For reinstatement, navigate to volunteer search and select Archive, then modify the field to Active', '2026-03-23-21:01', 1, 0),
(750, 'vmsroot', 'vmsroot', 'blueydingo123 has been archived.', 'This user has been archived. For reinstatement, navigate to volunteer search and select Archive, then modify the field to Active', '2026-03-25-08:06', 1, 0),
(751, 'vmsroot', 'acarmich@mail.umw.edu', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(752, 'vmsroot', 'ameyer3', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(753, 'vmsroot', 'armyuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(754, 'vmsroot', 'blueydingo123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 1, 0),
(755, 'vmsroot', 'BobVolunteer', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(756, 'vmsroot', 'Britorsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(757, 'vmsroot', 'exampleuser', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(758, 'vmsroot', 'fakename', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(759, 'vmsroot', 'firstName', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(760, 'vmsroot', 'gabriel', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(761, 'vmsroot', 'japper', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(762, 'vmsroot', 'Jlipinsk', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(763, 'vmsroot', 'johnDoe123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(764, 'vmsroot', 'lukeg', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(765, 'vmsroot', 'maddiev', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(766, 'vmsroot', 'michael_smith', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(767, 'vmsroot', 'michellevb', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(768, 'vmsroot', 'navyspouse', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(769, 'vmsroot', 'olivia', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(770, 'vmsroot', 'test_acc', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(771, 'vmsroot', 'test_person', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(772, 'vmsroot', 'test_persona', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(773, 'vmsroot', 'tester4', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(774, 'vmsroot', 'testing123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(775, 'vmsroot', 'testytesty', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(776, 'vmsroot', 'toaster', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(777, 'vmsroot', 'turkeybird123', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 1, 0),
(778, 'vmsroot', 'vmsroot', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 1, 0),
(779, 'vmsroot', 'Volunteer25', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(780, 'vmsroot', 'Welp', 'A new discussion has been created. View under discussions page.', 'New Discussion', '2026-03-28-18:18', 0, 0),
(781, 'vmsroot', 'blueydingo123', 'You are now signed up for Sat Night Weekly!', 'Thank you for signing up for Sat Night Weekly!', '2026-03-28-18:25', 1, 0),
(782, 'vmsroot', 'turkeybird123', 'You are now signed up for Board Meeting!', 'Thank you for signing up for Board Meeting!', '2026-03-30-09:26', 1, 0),
(783, 'vmsroot', 'vmsroot', 'deletethis123 has been added as a volunteer', 'New volunteer account has been created', '2026-03-31-22:40', 1, 0),
(784, 'vmsroot', 'vmsroot', 'janedoe1 has been added as a volunteer', 'New volunteer account has been created', '2026-03-31-23:41', 1, 0),
(785, 'vmsroot', 'turkeybird123', 'You are now signed up for \\\'Sup Ya\\\'ll & Pals?!', 'Thank you for signing up for \\\'Sup Ya\\\'ll & Pals?!', '2026-04-01-12:04', 1, 0),
(786, 'vmsroot', 'turkeybird123', 'You are now signed up for Custom Event Test!', 'Thank you for signing up for Custom Event Test!', '2026-04-10-14:16', 1, 0),
(787, 'vmsroot', 'janedoe1', 'You are now signed up for Checking the Date!', 'Thank you for signing up for Checking the Date!', '2026-04-11-19:52', 1, 0),
(788, 'vmsroot', 'blueydingo123', 'You are now signed up for Checking the Date!', 'Thank you for signing up for Checking the Date!', '2026-04-11-20:02', 1, 0),
(789, 'vmsroot', 'janedoe1', 'You are now signed up for Custom Event Test!', 'Thank you for signing up for Custom Event Test!', '2026-04-11-20:03', 1, 0),
(790, 'vmsroot', 'blueydingo123', 'You are now signed up for Custom Event Test!', 'Thank you for signing up for Custom Event Test!', '2026-04-11-23:03', 1, 0),
(791, 'vmsroot', 'blueydingo123', 'You are now signed up for Custom Event Test!', 'Thank you for signing up for Custom Event Test!', '2026-04-11-23:05', 1, 0),
(792, 'vmsroot', 'janedoe1', 'You are now signed up for Custom Event Test!', 'Thank you for signing up for Custom Event Test!', '2026-04-11-23:06', 1, 0),
(793, 'vmsroot', 'janedoe1', 'You are now signed up for Custom Event Test!', 'Thank you for signing up for Custom Event Test!', '2026-04-12-00:16', 1, 0),
(794, 'vmsroot', 'janedoe1', 'You are now signed up for Custom Event Test!', 'Thank you for signing up for Custom Event Test!', '2026-04-12-00:16', 1, 0),
(795, 'vmsroot', 'blueydingo123', 'You are now signed up for Temporary!', 'Thank you for signing up for Temporary!', '2026-04-17-15:37', 1, 0),
(796, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-03:58', 1, 0),
(797, 'vmsroot', 'janedoe1', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-03:58', 1, 0),
(798, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-03:59', 1, 0),
(799, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-04:09', 1, 0),
(800, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-04:16', 1, 0),
(801, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-04:16', 1, 0),
(802, 'vmsroot', 'turkeybird123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-04:18', 1, 0),
(803, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-04:24', 1, 0),
(804, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Log Attendees!', 'Thank you for signing up for Test Log Attendees!', '2026-04-26-04:25', 1, 0),
(805, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Today!', 'Thank you for signing up for Test Today!', '2026-04-26-13:21', 0, 0),
(806, 'vmsroot', 'janedoe1', 'You are now signed up for Test Today!', 'Thank you for signing up for Test Today!', '2026-04-26-13:22', 0, 0),
(807, 'vmsroot', 'blueydingo123', 'You are now signed up for Test Today!', 'Thank you for signing up for Test Today!', '2026-04-26-13:26', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbpendingsignups`
--

CREATE TABLE `dbpendingsignups` (
  `username` varchar(25) NOT NULL,
  `eventname` varchar(100) NOT NULL,
  `notes` varchar(100) NOT NULL,
  `attended` tinyint(1) NOT NULL DEFAULT 0,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbpendingsignups`
--

INSERT INTO `dbpendingsignups` (`username`, `eventname`, `notes`, `attended`, `role`) VALUES
('vmsroot', '108', 'Skills: non | Dietary restrictions: ojnjo | Disabilities: jonoj | Materials: knock', 0, ''),
('vmsroot', '101', 'Skills: rvwav | Dietary restrictions: varv | Disabilities: var | Materials: arv', 0, ''),
('vmsroot', '108', 'Skills: non | Dietary restrictions: ojnjo | Disabilities: jonoj | Materials: knock', 0, ''),
('vmsroot', '101', 'Skills: rvwav | Dietary restrictions: varv | Disabilities: var | Materials: arv', 0, ''),
('john_doe', '118', '', 0, ''),
('ameyer123', '126', '', 0, ''),
('test_persona', '129', '', 0, ''),
('test_persona', '129', '', 0, ''),
('edarnell', '176', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0, 'p'),
('edarnell', '180', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0, 'p'),
('armyuser', '181', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0, 'p'),
('armyuser', '176', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0, 'p'),
('navyspouse', '176', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0, 'p');

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
('vol_alice', 9001, '2025-10-18 17:00:00', '2025-10-18 21:00:00', 'approved'),
('vol_bob', 9001, '2025-10-18 17:00:00', '2025-10-18 20:30:00', 'approved'),
('vol_carol', 9001, '2025-10-18 17:30:00', '2025-10-18 21:00:00', 'approved'),
('vol_emma', 9001, '2025-10-18 17:00:00', '2025-10-18 19:00:00', 'approved'),
('vol_karen', 9001, '2025-10-18 17:00:00', '2025-10-18 21:00:00', 'approved'),
('vol_mia', 9001, '2025-10-18 18:00:00', '2025-10-18 21:00:00', 'approved'),
('vol_alice', 9002, '2025-12-06 20:00:00', '2025-12-07 01:00:00', 'approved'),
('vol_bob', 9002, '2025-12-06 20:00:00', '2025-12-06 23:00:00', 'approved'),
('vol_emma', 9002, '2025-12-06 20:00:00', '2025-12-07 00:00:00', 'approved'),
('vol_frank', 9002, '2025-12-06 21:00:00', '2025-12-07 01:00:00', 'approved'),
('vol_mia', 9002, '2025-12-06 20:00:00', '2025-12-06 22:30:00', 'approved'),
('vol_alice', 9003, '2026-01-17 18:00:00', '2026-01-17 22:00:00', 'approved'),
('vol_bob', 9003, '2026-01-17 18:00:00', '2026-01-17 22:00:00', 'approved'),
('vol_carol', 9003, '2026-01-17 18:00:00', '2026-01-17 21:00:00', 'approved'),
('vol_dan', 9003, '2026-01-17 18:00:00', '2026-01-17 20:30:00', 'approved'),
('vol_emma', 9003, '2026-01-17 18:00:00', '2026-01-17 22:00:00', 'approved'),
('vol_frank', 9003, '2026-01-17 18:30:00', '2026-01-17 22:00:00', 'approved'),
('vol_grace', 9003, '2026-01-17 19:00:00', '2026-01-17 22:00:00', 'approved'),
('vol_alice', 9004, '2026-02-08 23:00:00', '2026-02-09 02:00:00', 'approved'),
('vol_carol', 9004, '2026-02-08 23:00:00', '2026-02-09 01:30:00', 'approved'),
('vol_emma', 9004, '2026-02-08 23:00:00', '2026-02-09 02:00:00', 'approved'),
('vol_grace', 9004, '2026-02-08 23:30:00', '2026-02-09 02:00:00', 'approved'),
('vol_hank', 9004, '2026-02-08 23:00:00', '2026-02-09 01:00:00', 'approved'),
('vol_alice', 9005, '2026-03-14 16:00:00', '2026-03-14 22:00:00', 'approved'),
('vol_bob', 9005, '2026-03-14 16:00:00', '2026-03-14 21:00:00', 'approved'),
('vol_carol', 9005, '2026-03-14 16:00:00', '2026-03-14 20:00:00', 'approved'),
('vol_dan', 9005, '2026-03-14 16:00:00', '2026-03-14 19:00:00', 'approved'),
('vol_frank', 9005, '2026-03-14 17:00:00', '2026-03-14 22:00:00', 'approved'),
('vol_grace', 9005, '2026-03-14 16:00:00', '2026-03-14 21:00:00', 'approved'),
('vol_iris', 9005, '2026-03-14 16:00:00', '2026-03-14 22:00:00', 'approved'),
('vol_jack', 9005, '2026-03-14 16:00:00', '2026-03-14 20:30:00', 'approved'),
('vol_alice', 9006, '2026-03-28 23:00:00', '2026-03-29 02:00:00', 'approved'),
('vol_emma', 9006, '2026-03-28 23:00:00', '2026-03-29 01:30:00', 'approved'),
('vol_grace', 9006, '2026-03-28 23:00:00', '2026-03-29 02:00:00', 'approved'),
('vol_iris', 9006, '2026-03-28 23:00:00', '2026-03-29 01:00:00', 'approved'),
('blueydingo123', 9008, '2026-04-17 19:30:00', '2026-04-17 20:30:00', 'pending'),
('blueydingo123', 9009, '2026-04-26 07:59:15', NULL, 'pending'),
('janedoe1', 0, '2026-04-26 08:38:41', '2026-04-29 08:38:41', 'approved');

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
  `email` varchar(255) NOT NULL,
  `email_prefs` enum('true','false') DEFAULT NULL,
  `emergency_contact_first_name` text DEFAULT NULL,
  `contact_num` varchar(255) DEFAULT 'n/a',
  `emergency_contact_relation` text DEFAULT NULL,
  `contact_method` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
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
  `total_hours_volunteered` decimal(8,2) DEFAULT 0.00,
  `force_password_change` tinyint(1) DEFAULT 0,
  `profile_pic` varchar(512) DEFAULT 'images/usaicon.png',
  `cpr_training_completion` enum('yes','no') DEFAULT 'no',
  `aed_training_completion` enum('yes','no') DEFAULT 'no',
  `has_disability` enum('yes','no') DEFAULT 'no',
  `disability_specifications` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `over21`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `email_prefs`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `affiliation`, `branch`, `archived`, `emergency_contact_last_name`, `gender`, `t_shirt_size`, `computer_access`, `camera_access`, `transportation_access`, `skills`, `experience`, `about_consent`, `total_hours_volunteered`, `force_password_change`, `profile_pic`, `cpr_training_completion`, `aed_training_completion`, `has_disability`, `disability_specifications`) VALUES
('1myvicente@gmail.com', '2025-12-20', 'Marianna', 'Vicente', '82 Sanctuary Ln', 'Stafford', 'VA', '22554', '7576354084', NULL, 'home', NULL, NULL, '2009-09-11', '1myvicente@gmail.com', NULL, 'Donnett', '75763040', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Vicente', 'Female', 'S', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('229313@carmax.com', '2024-10-08', 'Caitlin', 'Cutshall', '113 Waverly Drive', 'Ruther Glen', 'VA', '22546', '5406423056', NULL, 'cellphone', NULL, NULL, '1996-05-30', '229313@carmax.com', NULL, 'Justin', '54064230', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Cutshall', 'Female', 'S', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('271041@carmax.com', '2025-03-10', 'Deloris', 'Clark', '133 New Providence Dr', 'Ruther Glen', 'VA', '22546', '8043109350', NULL, 'cellphone', NULL, NULL, '1966-09-23', '271041@carmax.com', NULL, 'Willie', '90422673', 'Friend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Clark', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('272281@carmax.com', '2024-12-19', 'Sheila', 'Chewning', '10400 Southpoint Parkway', 'Fredericksburg', 'VA', '22407', '5407107781', NULL, 'work', NULL, NULL, '1967-05-18', '272281@carmax.com', NULL, 'Amanda', '8049944404', 'Daughter', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Samuels', 'Female', 'XXL', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('280226@carmax.com', '2025-05-19', 'Mark', 'Cobbins', '10400 Southpoint Pkwy', 'Fredericksburg', 'VA', '22407', '5407107781', NULL, 'work', NULL, NULL, '1974-10-24', '280226@carmax.com', NULL, 'Susan', '54068473', 'Mom', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Clark', 'Male', 'XXL', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('a.jt.53@hotmail.com', '2024-07-18', 'Alexander', 'Thomson', '13000 Platoon Drive', 'Spotsylvania', 'VA', '22551', '7169301058', NULL, 'cellphone', NULL, NULL, '1981-04-10', 'a.jt.53@hotmail.com', NULL, 'Shannon', '70398019', 'Partner', NULL, 'volunteer', 'Inactive', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Frick', 'Male', 'XL', 'yes', 'yes', 'yes', 'CPR Certified, ALS and BLS Instructor, can get things down from high shelves, and lift heavy things with great ease.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('abc@gmail.com', '2024-05-08', 'Test', 'GGF', '111 ABC St.', 'FXBG', 'VA', '22401', '5555555555', NULL, 'cellphone', NULL, NULL, '1994-01-01', 'abc@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', '', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('acarmich@mail.umw.edu', '2025-12-01', 'John', 'Doe', NULL, 'Fredericksburg', 'VA', NULL, '5555555555', 'true', '', '', '', '', 'acarmich@mail.umw.edu', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$1CDYmdifcx5rfR80Ui8WLuM2ldqc4DTJiFbK1JMSLycE/0lLKPJUS', 'Family', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('adrianahernandez335@yahoo.com', '2024-07-24', 'Adriana', 'Hernandez', '1300 Walker dr', 'Fredericksburg', 'VA', '22485', '5406236172', NULL, 'cellphone', NULL, NULL, '2000-03-13', 'adrianahernandez335@yahoo.com', NULL, 'Jonathan', '5407609292', 'Brother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'M', 'yes', 'yes', 'yes', 'Bi-lingual, muti-tasking.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('adscaff12@yahoo.com', '2024-05-29', 'Dawn', 'Scaff', '57 Wellspring Drive', 'Fredericksburg', 'VA', '22405', '4135365390', NULL, 'cellphone', NULL, NULL, '1969-05-12', 'adscaff12@yahoo.com', NULL, 'Amber', '4133136329', 'Daughter', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Scaff', 'Female', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('agd7891@gmail.com', '2025-09-19', 'Amanda', 'Dodd', '2083 poplar road', 'Stafford', 'VA', '22556', '5404467399', NULL, 'cellphone', NULL, NULL, '1991-07-08', 'agd7891@gmail.com', NULL, 'Paul', '54031895', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dodd', 'Female', 'L', 'yes', 'no', 'yes', 'Fluent in American Sign Language', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('agm85669@gmail.com', '2025-02-15', 'Alexandra', 'Granados Melendez', '101 Mews CT', 'Stafford', 'VA', '22556', '5718351509', NULL, 'cellphone', NULL, NULL, '2008-02-08', 'agm85669@gmail.com', NULL, 'Ronel Granados', '57139711', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Melendez', 'Female', 'L', 'no', 'no', 'yes', 'I’m very good when it comes to working with children and I’m able to speak Spanish.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('aidanp2019@outlook.com', '2023-06-28', 'Aidan', 'Poteet', '41 Miracle Valley Ln', 'Fredericksburg', 'VA', '22405', '5404983352', NULL, 'cellphone', NULL, NULL, '2000-11-03', 'aidanp2019@outlook.com', NULL, 'Angela', '5405381319', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'poteet', 'Male', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('aimee.kline226@gmail.com', '2023-05-27', 'Aimee', 'Kline', '5061 Charbelee Drive', 'Charles City', 'VA', '23030', '5406214346', NULL, 'cellphone', NULL, NULL, '1993-02-08', 'aimee.kline226@gmail.com', NULL, 'Jared', '5406142117', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Kline', 'Female', 'L', 'yes', 'yes', 'no', 'Administration tasks, some French, some basic Japanese. Negotiations', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('allysing@yahoo.com', '2025-08-11', 'Allison', 'Graves', '12000 Wood Pond Ct', 'FREDERICKSBRG', 'VA', '22407', '5402731774', NULL, 'cellphone', NULL, NULL, '1969-02-10', 'allysing@yahoo.com', NULL, 'Dianna G', '54090378', 'Sister', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'aves', 'Female', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('alwaysin.4jesus@gmail.com', '2025-05-14', 'Maria', 'Winston', '4383 Hunsberger Drive Apt A', 'Warrenton', 'VA', '20187', '5403167619', NULL, 'cellphone', NULL, NULL, '1975-11-19', 'alwaysin.4jesus@gmail.com', NULL, 'Douglas', '54031676', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Winston', 'Female', 'XXL', 'yes', 'no', 'yes', 'I\'m a nurse.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('amanda@fahass.org', '2023-09-22', 'Amanda', 'Strawn', '31 Crescent Valley Dr', 'Fredericksburg', 'VA', '22405', '5403791739', NULL, 'cellphone', NULL, NULL, '1990-08-11', 'amanda@fahass.org', NULL, 'Tyler', '5408455156', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Strawn', 'Female', 'XL', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ameliathib825@gmail.com', '2024-11-22', 'Amelia', 'Thibodeau', '1834 Grayland St Apt 5', 'Blacksburg', 'VA', '24060', '5402201718', NULL, 'cellphone', NULL, NULL, '2005-08-25', 'ameliathib825@gmail.com', NULL, 'Carrie', '9785015033', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Jones', 'Female', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ameyer3', '2025-03-26', 'Aidan', 'Meyer', '1541 Surry Hill Court', 'Charlottesville', 'VA', '22901', '4344222910', NULL, 'home', '4344222910', 'home', '2003-08-17', 'aidanmeyer32@gmail.com', NULL, 'Aidan', 'n/a', 'Father', NULL, 'volunteer', 'Active', NULL, '$2y$10$0R5pX4uTxS0JZ4rc7dGprOK4c/d1NEs0rnnaEmnW4sz8JIQVyNdBC', NULL, NULL, 0, 'Meyer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('amponglois12@gmail.com', '2026-01-13', 'Lois', 'Ampong', '30 Rocky Way dr', 'stafford', 'VA', '22554', '5714781892', NULL, 'home', NULL, NULL, '2009-02-12', 'amponglois12@gmail.com', NULL, 'samuel', '85724732', 'dad', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'ampong', 'Female', 'S', 'yes', 'no', 'yes', 'When it comes to skills, I believe I’ve learned through experiences and can say that I’m a very sympathetic person, I’m great at multitasking and solving problems. I also know how to speak another language called twi, the native  tongue in Ghana.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('amursurf226@yahoo.com', '2023-05-09', 'Jared', 'Kline', '5061 Charbelee Drive', 'Charles City', 'VA', '23030', '5406142117', NULL, 'cellphone', NULL, NULL, '1992-01-26', 'amursurf226@yahoo.com', NULL, 'Aimee', '5406214346', 'Wife', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Kline', 'Male', 'XL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('amy.sauro@icloud.com', '2024-12-14', 'Amy', 'Sauro', '5100 Attain Centre Dr., Apt 211', 'Fredericksburg', 'VA', '22407', '9412230413', NULL, 'cellphone', NULL, NULL, '1969-09-24', 'amy.sauro@icloud.com', NULL, 'Tina', '54029576', 'Sister', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Jackson', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('amyanderson75@gmail.com', '2025-09-19', 'Amy', 'Anderson', '8610 Brighton Ct.', 'Fredericksburg', 'VA', '22408', '5408457803', NULL, 'cellphone', NULL, NULL, '2025-04-09', 'amyanderson75@gmail.com', NULL, 'Robert', '54084578', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Anderson', 'Female', 'XL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('amymanderson75@gmail.com', '2025-10-06', 'Amy', 'Anderson', '8610 Brighton Ct.', 'Fredericksburg', 'VA', '22408', '5408457803', NULL, 'cellphone', NULL, NULL, '1975-04-09', 'amymanderson75@gmail.com', NULL, 'Robert', '5408450000', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Anderson', 'Female', 'XL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('anbarrett@outlook.com', '2023-10-24', 'Abby', 'Barrett', '95 carriage hill dr', 'Fredericksburg', 'VA', '22405', '8179098636', NULL, 'cellphone', NULL, NULL, '2001-03-22', 'anbarrett@outlook.com', NULL, 'Robert', '8179098581', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Barrett', 'Female', 'M', 'yes', 'no', 'yes', 'Data analysis', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('andersonkaylab@scps.net', '2025-10-04', 'Kayla', 'Anderson', '709 Clint Lane', 'Fredericksburg', 'VA', '22405', '8262715946', NULL, 'cellphone', NULL, NULL, '2009-07-28', 'andersonkaylab@scps.net', NULL, 'Katie', '5409192878', 'Mom', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Anderson', 'Female', 'S', 'no', 'no', 'yes', 'CPR Certified\nWorks well with kids\nCommunication problem-solving \nCritical thinking \nOrganization\nEmotional intelligence\nConflict resolution\nClose attention to detail\nAssistance\nMultitasking\nProductive', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('angie@cegresults.com', '2023-05-31', 'Angie', 'Sullivan', '11106 Parkview Drive', 'Fredericksburg', 'VA', '22408', '5408402007', NULL, 'cellphone', NULL, NULL, '1971-07-14', 'angie@cegresults.com', NULL, 'Angie', '5408425482', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Sullivan', 'Female', 'M', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('angrisham07@gmail.com', '2025-10-06', 'Alanna', 'Grisham', '51 Fleetwood Farm ln', 'Fredericksburg', 'VA', '22405', '7039810560', NULL, 'cellphone', NULL, NULL, '2007-12-12', 'angrisham07@gmail.com', NULL, 'Amber', '71438143', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Grisham', 'Female', 'S', 'yes', 'yes', 'yes', 'CPR, First Aid, and Basic Life Support certified (Red Cross and American Heart Association), I know how to operate a camera, I have lots of experience in sales (Girl scout booths, volunteering for PTO and other fundraisers), I have great communication skills and can speak a mild amount of Spanish, I know how to operate basic computer softwares, I am physically fit and can lift/ carry objects, and can learn quickly.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('annabellewheelis10@gmail.com', '2024-08-01', 'Annabelle', 'Wheelis', '75A Boundry Drive', 'Stafford', 'VA', '22556', '5403407177', NULL, 'cellphone', NULL, NULL, '2008-10-09', 'annabellewheelis10@gmail.com', NULL, 'April', '54045502', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Wheelis', 'Female', 'XXL', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('annettasheriff03@gmail.com', '2023-06-29', 'Annetta', 'Sheriff', '9225 Split Oak Dr', 'Fredericksburg', 'VA', '22407', '5402572924', NULL, 'cellphone', NULL, NULL, '2003-12-29', 'annettasheriff03@gmail.com', NULL, 'Wade', '5406429743', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'W.Sheriff', 'Female', 'L', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('antonellahowells@gmail.com', '2025-09-13', 'Antonella', 'Howells', '70 basalt drive', 'Fredericksburg', 'VA', '22406', '5406900123', NULL, 'cellphone', NULL, NULL, '2008-11-02', 'antonellahowells@gmail.com', NULL, 'Silvia', '70394360', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Howells', 'Female', 'S', 'no', 'no', 'yes', 'Bilingual English and spanisg', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('armyuser', '2025-11-30', 'Army', 'Active Duty', NULL, 'FXBG', 'VA', NULL, '3243242342', 'true', '', '', '', '', 'example@example.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$kdxwMq.xaGsYvl8gY/8l3.xwu9ABEhWernkR6kmro9QtNvvEjqPFi', 'Active duty', 'Army', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('averylane873@gmail.com', '2025-12-02', 'Avery', 'Lane', '2 Joyce St', 'Stafford', 'VA', '22556', '5715380327', NULL, 'cellphone', NULL, NULL, '2008-05-08', 'averylane873@gmail.com', NULL, 'Jim', '20249490', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lane', 'Female', 'S', 'yes', 'no', 'yes', '-Public Speaking\n- Professional Communication\n- Conflict Resolution and De-escalation', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('baileyweatherby24@yahoo.com', '2024-09-02', 'Bailey', 'Weatherby', '303 Walnut Drive', 'Stafford', 'VA', '22405', '5402203699', NULL, 'cellphone', NULL, NULL, '2002-09-30', 'baileyweatherby24@yahoo.com', NULL, 'Jennifer', '54060415', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Weatherby', 'Female', 'M', 'yes', 'yes', 'yes', 'Communication Skills, Teamwork, Strong Work ethic, and Time Management', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('bautistaguadalupey@scps.net', '2025-09-28', 'Yoselin', 'Bautista', '1642, Mountain View Rd', 'Stafford', 'VA', '22554', '5402261773', NULL, 'home', NULL, NULL, '2011-02-17', 'bautistaguadalupey@scps.net', NULL, 'Marisol', '57123360', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Perez', 'Female', 'S', 'no', 'no', 'yes', 'I also speak Spanish.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('beehive7@msn.com', '2025-05-08', 'Lynne', 'Beiswanger', '304 Battleship Cove', 'Stafford', 'VA', '22554', '5408505388', NULL, 'cellphone', NULL, NULL, '1959-03-30', 'beehive7@msn.com', NULL, 'John', '5498400000', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Beiswanger', 'Female', 'S', 'yes', 'yes', 'yes', 'Current Stafford County Substitute Teacher, 25 years in school system here', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('behackett@outlook.com', '2024-02-01', 'Bethanie', 'Hackett', '1701 College Avenue, UMW Box 1565', 'Fredericksburg', 'VA', '22401', '9072051035', NULL, 'cellphone', NULL, NULL, '2005-08-01', 'behackett@outlook.com', NULL, 'Kristine', '9072444925', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Hackett', 'Female', 'M', 'yes', 'no', 'no', 'Computer science skills - coding in Java, C, Python', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('beiswatj@gmail.com', '2025-05-08', 'Tyler', 'Beiswanger', '304 Battleship cove', 'Stafford', 'VA', '22554', '5408401253', NULL, 'cellphone', NULL, NULL, '1993-11-08', 'beiswatj@gmail.com', NULL, 'John', '54084012', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Beiswanger', 'Male', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('bgdru614@gmail.com', '2025-05-01', 'Beth', 'Druvenga', '1603 Hudgins Farm Circle', 'Fredericksburg', 'VA', '22408', '7122600671', NULL, 'cellphone', NULL, NULL, '1990-06-14', 'bgdru614@gmail.com', NULL, 'Becky', '30135771', 'Friend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Bove', 'Female', 'L', 'yes', 'no', 'yes', 'HSI CPR/AED/FA instructor, Certified Athletic Trainer,', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('biddulph.katie@icloud.com', '2024-11-18', 'Katerine', 'Biddulph', '1010 Hillcrest Terr', 'Fredericksburg', 'VA', '22405', '5402733058', NULL, 'cellphone', NULL, NULL, '2007-07-09', 'biddulph.katie@icloud.com', NULL, 'Joanne', '73273578', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'biddulph', 'Female', 'XL', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('blueydingo123', '2026-03-13', 'Bluey', 'Dingo', '12 Doggo Court', 'Dingo Valley', 'VA', '12345', '1111111111', NULL, 'cellphone', '8888888888', 'cellphone', '2000-11-11', 'blueythedingo@email.com', 'true', 'Mum', '', 'Mother', '', 'event_manager', 'Active', 'Dingos can now volunteer as of March 12, 2026 WHOA YEAH DINGOS LOVE DINGOS. More notes.', '$2y$10$fdekTq9y4mzcNmJrh/83pek8z5Tk7AyxCCKSKyKVmBse9O96cfsOW', '', '', NULL, 'Dingo', 'Female', 'M', 'yes', 'yes', 'no', 'Having fun, football', 'Retail', NULL, 1.00, 0, 'images/profile_pics/pfp_blueydingo123_1776567937.jpeg', 'no', 'no', 'no', ''),
('BobVolunteer', '2025-04-29', 'Bob', 'SPCA', '123 Dog Ave', 'Dogville', 'VA', '54321', '9806761234', NULL, 'home', '1234567788', 'home', '2020-03-03', 'fred54321@gmail.com', NULL, 'Luke', 'n/a', 'Bff', NULL, 'volunteer', 'Active', NULL, '$2y$10$4wUwAW0yoizxi5UFy1/OZu.yfYY7rzUsuYcZCdvfplLj95r7OknvG', NULL, NULL, 0, 'Blair', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Britorsk', '2026-02-05', 'Brian', 'Prelle', NULL, 'KING GEORGE', 'VA', NULL, '5402076085', 'true', '', '', '', '', 'brian2@prelle.net', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$q9wFQJ/guFjlUnR7IfJt/.MRf5bDfK8FxebznfRt644twzYepM/bC', 'Family', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('brittany.mcbride@carystreetpartners.com', '2023-09-21', 'BRITTANY', 'MCBRIDE', '1609 Charles St.', 'Fredericksburg', 'VA', '22401', '8142821067', NULL, 'cellphone', NULL, NULL, '1996-08-28', 'brittany.mcbride@carystreetpartners.com', NULL, 'Theo', '7039195138', 'Partner', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Doughty', 'Female', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('brooke@vitalityvs.com', '2025-04-14', 'Brooke', 'Fairchild', '111 Tallwood Trl', 'Locust Grove', 'VA', '22508', '5404555899', NULL, 'cellphone', NULL, NULL, '1998-09-04', 'brooke@vitalityvs.com', NULL, 'Regina', '5406450000', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'byrd', 'Female', 'S', 'yes', 'no', 'yes', 'Registered nurse - CPR certified: ACLS, BLS, PALS, TNCC \nIV skills!', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('bsilk@cox.net', '2023-09-04', 'Bob', 'Silkensen', '60 Ivy Creek Ln', 'Fredericksburg', 'VA', '22405', '5402730097', NULL, 'cellphone', NULL, NULL, '1948-10-14', 'bsilk@cox.net', NULL, 'Chris', '5402735845', 'Wife', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Silkensen', 'Male', 'XXL', 'yes', 'no', 'yes', 'Good with computers. I play golf. I once worked at Meadows Farm Golf Course.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('bsisco1@duck.com', '2024-11-12', 'Brandon', 'Sisco', '1900 Charles street', 'Fredricksburg', 'VA', '22401', '8045727218', NULL, 'cellphone', NULL, NULL, '1995-09-27', 'bsisco1@duck.com', NULL, 'Tiffany', '6145728196', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Steel', 'Male', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('bspatt04@gmail.com', '2024-09-17', 'Brenda', 'Dixon', '75 Grand Garden Ln', 'Stafford', 'VA', '22556', '5712135904', NULL, 'cellphone', NULL, NULL, '1984-12-17', 'bspatt04@gmail.com', NULL, 'Albert', '57121359', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Doxon', 'Female', 'L', 'yes', 'no', 'no', 'I speak English well and able to serve and speak to customers. I am also able to clean and help with anything that you need help with', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('c_durtche@yahoo.com', '2024-12-13', 'Cheralyn', 'Gates', '2321 Magnolia Ln', 'King George', 'VA', '22485', '8062207436', NULL, 'cellphone', NULL, NULL, '1990-09-30', 'c_durtche@yahoo.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'S', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('camila1010fuentesa@gmail.com', '2024-10-20', 'Camila', 'A. fuentes', '105 bel air pl', 'Fredericksburg', 'VA', '22405', '5402268967', NULL, 'cellphone', NULL, NULL, '2003-10-10', 'camila1010fuentesa@gmail.com', NULL, 'Richard', '54027364', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Cronin', 'Female', 'XL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('candacegolda@gmail.com', '2024-03-13', 'Candy', 'Patten', '30319 Stonewall Dr', 'Locust Grove', 'VA', '22508', '7143174701', NULL, 'cellphone', NULL, NULL, '1974-05-02', 'candacegolda@gmail.com', NULL, 'Thomas W Patten', '71492993', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Jr.', 'Female', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('carlypleines@msn.com', '2024-08-14', 'Carly', 'Pleines', '605 Woodford Street', 'Fredericksburg', 'VA', '22401', '6178723335', NULL, 'cellphone', NULL, NULL, '1990-09-26', 'carlypleines@msn.com', NULL, 'Jeff', '5408451234', 'Partner', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Valiani', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('carmax@carmax.com', '2025-04-16', 'CarMax', 'CarMax', 'Carmax', 'Fredericksburg', 'VA', '22401', '6145728196', NULL, 'cellphone', NULL, NULL, '2025-04-16', 'carmax@carmax.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', '', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('carriejones0923@gmail.com', '2024-11-18', 'Carrie', 'Jones', '14374 Dahlgren Rd', 'King George', 'VA', '22485', '9785015033', NULL, 'cellphone', NULL, NULL, '1980-01-12', 'carriejones0923@gmail.com', NULL, 'William', '75728943', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Jones', 'Female', 'S', 'yes', 'yes', 'yes', 'Professional photographer', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('cbtaylor2345@gmail.com', '2025-09-19', 'CJ', 'Taylor', '5708 Cambridge Dr.', 'Fredericksburg', 'VA', '22407', '5409077142', NULL, 'cellphone', NULL, NULL, '1984-11-04', 'cbtaylor2345@gmail.com', NULL, 'Katina', '54037649', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Taylor', 'Male', 'XL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('cgates2833@gmail.com', '2024-12-13', 'Cheralyn', 'Gates', '2321 Magnolia Ln', 'King George', 'VA', '22485', '8062207436', NULL, 'cellphone', NULL, NULL, '1990-09-30', 'cgates2833@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'S', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('courtney@premierathleticsfitness.com', '2025-05-12', 'Courtney', 'Dorsey', '136 Boxelder Drive', 'Stafford', 'VA', '22554', '9713406583', NULL, 'cellphone', NULL, NULL, '1987-11-26', 'courtney@premierathleticsfitness.com', NULL, 'Micheal', '50338174', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dorsey', 'Female', 'M', 'yes', 'yes', 'yes', 'I am a gym owner and personal trainer if that could come in handy. I love talking to people and am a communications major. I also am pretty good at social media and taking pictures!', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('csilk@cox.net', '2023-09-08', 'Chris', 'Silkensen', '60 Ivy Creek Lane', 'Fredericksburg', 'VA', '22405', '5402735845', NULL, 'cellphone', NULL, NULL, '1952-07-29', 'csilk@cox.net', NULL, 'Bob', '5402730097', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Silkensen', 'Female', 'XL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('darbykconerly@aol.com', '2025-06-15', 'Darby', 'Conerly', '2 Angus Cir', 'Fredericksburg', 'VA', '22405', '5402072993', NULL, 'cellphone', NULL, NULL, '2005-04-14', 'darbykconerly@aol.com', NULL, 'Kelly', '54020754', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Conerly', 'Female', 'M', 'yes', 'yes', 'yes', 'I am a college student home for the summer. I am a political science major with a concentration in public service and policy with a minor in communications. I currently am interning at a campaign consulting phone firm in DC. I am cpr/first aid certified. I am a college athlete in pretty good shape and can do manual labor. I am experienced in event organization and researching as well. I am the VP of programming events for my sorority.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('david.ruizrivera1@gmail.com', '2024-05-14', 'David', 'Ruiz-Rivera', '8417 Chiswell Ct', 'Fredricksburg', 'VA', '22551', '5404790175', NULL, 'cellphone', NULL, NULL, '2003-07-22', 'david.ruizrivera1@gmail.com', NULL, 'Jorge', '54076060', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ruiz', 'Male', 'M', 'yes', 'yes', 'yes', 'I am bilingual in Spanish!', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('dcortez1235@gmail.com', '2026-05-01', 'Daniela', 'Cortez Rivera', '6 Kinross Dr', 'Stafford', 'VA', '22554', '7044304562', NULL, 'cellphone', NULL, NULL, '2009-04-23', 'dcortez1235@gmail.com', NULL, 'Ana Rivera de', '70432024', 'Mom', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Cortez', 'Female', 'S', 'no', 'no', 'yes', 'I am a problem solver, know how to be a team player, I’m helpful, I have good communication skills, I know to work in a team.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('deletethis123', '2026-03-31', 'Delete', 'This', 'Nah', 'This', 'VA', '11223', '3332221111', NULL, 'work', '3332221110', 'cellphone', '2000-03-24', 'dis.fake@email.com', 'true', 'Weird', '', 'IDK', '', 'volunteer', 'Active', '', '$2y$10$yxIFiUHtAaDrsROUAf4CDORaAxDra70VQEWnfWP/JGVr9Q67I5c2q', '', '', NULL, 'This', 'Other', 'XL', 'no', 'no', 'no', '', '', 'yes', 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('deloris_clark@carmax.com', '2024-12-03', 'Deloris', 'Clark', '10400 Southpoint Pkwy', 'Fredericksburg', 'VA', '22407', '5407107781', NULL, 'work', NULL, NULL, '1966-09-23', 'deloris_clark@carmax.com', NULL, 'Willie', '90422673', 'Friend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Clark', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('dgparishnurse@hotmail.com', '2023-09-01', 'Denise', 'Gates', '11351 Savannah Dr', 'Fredericksburg', 'VA', '22407', '4238381581', NULL, 'cellphone', NULL, NULL, '1958-09-30', 'dgparishnurse@hotmail.com', NULL, 'Timothy', '4238381022', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Gates', 'Female', 'XXL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('dierkelly@yahoo.com', '2024-08-15', 'Kelly', 'Dierberger', '7202 Cattail Court', 'Fredericksburg', 'VA', '22407', '7579686146', NULL, 'cellphone', NULL, NULL, '1984-12-14', 'dierkelly@yahoo.com', NULL, 'Cameron', '7572681882', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dierberger', 'Female', 'S', 'yes', 'yes', 'yes', '-Current reigning United States of America’s Mrs. Virginia\n-Photographer\n-Writer\n-Health Coach\n-Spokesmodel/communicator', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('digmankathleen@gmail.com', '2024-05-23', 'Kathy', 'Digman', '7319 Warwick Plantation Lane', 'Spotsylvania', 'VA', '22551', '5409036465', NULL, 'cellphone', NULL, NULL, '1962-06-23', 'digmankathleen@gmail.com', NULL, 'Patrick', '54062121', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Digman', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('dkeffer0710@gmail.com', '2023-11-16', 'Dayton', 'Keffer', '1788 Welltown Rd.', 'Clear Brook', 'VA', '22624', '5403035478', NULL, 'cellphone', NULL, NULL, '2002-07-10', 'dkeffer0710@gmail.com', NULL, 'Tonya', '5408778300', 'Mother', NULL, 'volunteer', 'Inactive', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Keffer', 'Female', 'L', 'yes', 'no', 'yes', 'Communication, Social Media, WordPress, Adobe', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('donnalawlor53@gmail.com', '2025-09-28', 'Donna', 'Lawlor', '33clarion dr', 'Fredericksburg', 'VA', '22505', '5402734343', NULL, 'cellphone', NULL, NULL, '1959-07-11', 'donnalawlor53@gmail.com', NULL, 'Dan', '54053556', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'lawlor', 'Female', 'L', 'yes', 'yes', 'yes', 'I am a CPR / first aid instructor for Stafford County Public schools transportation department', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('drax809@gmail.com', '2024-06-10', 'William', 'Murphy', '200 W 31st St', 'Norfolk', 'VA', '23504', '7032698156', NULL, 'cellphone', NULL, NULL, '2000-08-24', 'drax809@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Male', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('dre1074@gmail.com', '2023-05-16', 'Dre', 'Lipscomb', '7 Braddock Dr.', 'Fredericksburg', 'VA', '22405', '5712370203', NULL, 'cellphone', NULL, NULL, '1974-10-07', 'dre1074@gmail.com', NULL, 'Mary', '5407103604', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lipscomb', 'Male', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ebony89633@gmail.com', '2025-01-03', 'Ebony', 'Thompson', '10300 Heather Greens Circle', 'Spotsylvania', 'VA', '22553', '7068335208', NULL, 'cellphone', NULL, NULL, '1984-11-21', 'ebony89633@gmail.com', NULL, 'Natalie', '21076329', 'Friend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'XXL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('egeorge@linkbank.com', '2024-09-11', 'Eric', 'George', '17281 Library blvd', 'Ruther glen', 'VA', '22546', '2407286868', NULL, 'cellphone', NULL, NULL, '1988-03-19', 'egeorge@linkbank.com', NULL, 'Becky', '24043195', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'George', 'Male', 'M', 'yes', 'no', 'yes', 'All types of set up and tear down for events.\nCustomer service\nCoordinating volunteers \nProblem solving', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('eisenmann521@gmail.com', '2025-01-13', 'Maria', 'Eisenmann', '8 Hawthorne Court', 'Stafford', 'VA', '22554', '5408414484', NULL, 'cellphone', NULL, NULL, '1970-08-13', 'eisenmann521@gmail.com', NULL, 'Paul', '54060297', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Eisenmann', 'Female', 'M', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('emely0812ramos@gmail.com', '2025-06-25', 'Emely', 'Ramos Diaz', '305 Falmouth Dr', 'Ruther Glen', 'VA', '22546', '7033985403', NULL, 'cellphone', NULL, NULL, '2006-08-12', 'emely0812ramos@gmail.com', NULL, 'Christian', '57191016', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Hahn', 'Female', 'M', 'yes', 'no', 'no', 'I’m bilingual with strong communication, teamwork, and interpersonal skills gained through clinic, hospice, and patient care experience.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('emw.118828@gmail.com', '2025-03-15', 'Em', 'Welker', '1011 Coastal Avenue', 'Stafford', 'VA', '22554', '4406452991', NULL, 'cellphone', NULL, NULL, '2008-08-28', 'emw.118828@gmail.com', NULL, 'Amanda', '75750982', 'Mom', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Nagel', 'Female', 'L', 'yes', 'yes', 'yes', '--NOTE: I\'m still a high school student, getting to Fredericksburg is hard for me sometimes, I don\'t know when i can start volunteering.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ericayamilex@yahoo.com', '2025-05-26', 'Erica', 'Aguilar', '10106 colechester st', 'Fredericksburg', 'VA', '22408', '5404556269', NULL, 'cellphone', NULL, NULL, '1990-04-12', 'ericayamilex@yahoo.com', NULL, 'Jonathan', '54085037', 'Partner', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('erin.lewis.homes@gmail.com', '2024-11-18', 'erin', 'lewis', '127 lake shore drive', 'Fredericksburg', 'VA', '22405', '5406811632', NULL, 'cellphone', NULL, NULL, '1977-10-12', 'erin.lewis.homes@gmail.com', NULL, 'Veronica Gutierrez', '54068116', 'friend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Gutierrez', 'Female', 'M', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('erinm@gwynethsgift.org', '2023-06-07', 'Erin', 'Matuczinski', '77 Timberidge Dr', 'Fredericksburg', 'VA', '22406', '7573741296', NULL, 'cellphone', NULL, NULL, '2001-04-24', 'ematuczinski@yahoo.com', NULL, 'Kelly', '7572870684', 'Mother', NULL, 'volunteer', 'Active', 'Administrative', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Matuczinski', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('erod4@comcast.net', '2023-11-01', 'Edward', 'Rodriguez', '812 Bright St', 'Fredericksburg', 'VA', '22401', '5402075522', NULL, 'cellphone', NULL, NULL, '1970-04-13', 'erod4@comcast.net', NULL, 'Adrienne', '5402267285', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ohle-Rodriguez', 'Male', 'L', 'yes', 'yes', 'yes', 'I’ve been a nurse over 20 years, in the medical profession over 30. I’ve taught CPR previously. I currently deal with the public in a cardiac rehabilitation.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('evarileypowell@gmail.com', '2025-08-20', 'Eva', 'Powell', '10414 Piney Branch Road', 'Spotsylvania', 'VA', '22553', '5403791119', NULL, 'cellphone', NULL, NULL, '2003-03-27', 'evarileypowell@gmail.com', NULL, 'Kevin', '54029507', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Powell', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('exampleuser', '2025-10-20', 'example', 'user', '', 'test', 'VA', '', '2344564645', NULL, '', '', '', '', 'example@test.com', NULL, '', 'n/a', '', NULL, 'volunteer', 'Active', NULL, '$2y$10$J0NgBjoyg9F6YMyy/qQpv.f94OLM2r19sY80BZMhMdcl38SN5vdre', NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('faith@faithmading.com', '2026-01-15', 'Faith', 'Madding', '2217 Princess Anne St', 'Fredericksburg', 'VA', '22401', '5555555555', NULL, 'cellphone', NULL, NULL, '2026-01-15', 'faith@faithmading.com', NULL, '', '', 'tiffany@gwynethsgift.org', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', '', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('fakename', '2025-12-10', 'fake', 'name', NULL, 'realtown', 'VA', NULL, '5555555555', 'true', '', '', '', '', 'fakeemail@email.email.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$4h8ImkaTyMprwU3SzWrWx./NBI7yClMoqCkEbYJuA1/9cb0tSlUI.', 'Civilian', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ferrerprincesscass@gmail.com', '2025-01-21', 'Princess Cassandra', 'Ferrer', '53 Bertram Blvd', 'Stafford', 'VA', '22556', '5404269754', NULL, 'cellphone', NULL, NULL, '2005-06-01', 'ferrerprincesscass@gmail.com', NULL, 'May Anne', '54042697', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ferrer', 'Female', 'XL', 'yes', 'yes', 'yes', 'Proficient in reading and listening to Tagalog, Strong customer service skills with the ability to connect and collaborate with individuals from diverse backgrounds, adept at acquiring new knowledge and adapting to dynamic environments.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('firstName', '2025-12-10', 'firstName', 'lastName', NULL, 'homeTown', 'TX', NULL, '5555555555', 'true', '', '', '', '', 'realemail@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$og/aLBzrg195Qph9d2M/DuX2DIPhP.0sVT3vtu/WUpGCse8B.k71m', 'Civilian', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('gabriel', '2026-02-02', 'Gabriel', 'Courtney', NULL, 'King George', 'VA', NULL, '5404295285', 'true', '', '', '', '', 'gabrielcourtney04@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$4uvfLFyFy9Ui1i8Q1r0MWuFRGYfgvVh4.iUtvXksfVJm4pZpxxtSq', 'Active duty', 'Space Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('gennygymnast03@gmail.com', '2024-11-30', 'genesis', 'cano', '49 glacier way', 'stafford', 'VA', '22554', '5407839126', NULL, 'cellphone', NULL, NULL, '2002-04-25', 'gennygymnast03@gmail.com', NULL, 'jorge', '54028860', 'father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'cano', 'Female', 'M', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('glare-cliched.3s@icloud.com', '2025-09-16', 'Geniah', 'Baez', 'Falmouth', 'Fredericksburg', 'VA', '22405', '5715864706', NULL, 'cellphone', NULL, NULL, '1986-05-25', 'glare-cliched.3s@icloud.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'M', 'yes', 'yes', 'yes', 'Project Management \nQuality Assurance \nIT\nConcessions\nCustomer Service \nPresentations \nPublic speaking \nTime Management/Scheduling', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('gorenflojn@gmail.com', '2023-09-09', 'Jalinne', 'Gorenflo', '13298 Leafcrest Lane Apt 103B', 'Fairfax', 'VA', '22033', '7034019937', NULL, 'cellphone', NULL, NULL, '1997-03-22', 'gorenflojn@gmail.com', NULL, 'Nicole', '7034025903', 'Cousin', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Narciso', 'Female', 'L', 'yes', 'yes', 'yes', 'BLS certified from the American Heart Association', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('grantgabriellag@scps.net', '2025-12-08', 'Gabriella', 'Grant', '125 Cleremont Dr', 'Fredericksburg', 'VA', '22405', '9103154186', NULL, 'cellphone', NULL, NULL, '2009-07-27', 'grantgabriellag@scps.net', NULL, 'Garfield', '9103154156', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Grant', 'Female', 'M', 'yes', 'yes', 'yes', 'above-average knowledge in medical terminology \nCPR certified', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('grovestaylor@student.atiumw.org', '2025-09-08', 'Taylor', 'Groves', '15044 Rural Acres Dr', 'Woodford', 'VA', '22580', '8049798498', NULL, 'cellphone', NULL, NULL, '2010-04-07', 'grovestaylor@student.atiumw.org', NULL, 'Chrystene', '8043666721', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Groves', 'Female', 'XL', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('guzmanjulio498@icloud.com', '2024-11-18', 'Julio', 'Guzman', '1010 Hillcrest Terrace', 'Fredericksburg', 'VA', '22405', '5408095760', NULL, 'cellphone', NULL, NULL, '2005-05-04', 'guzmanjulio498@icloud.com', NULL, 'Joanne', '73273578', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'biddulph', 'Male', 'L', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('idaliasegovia@aol.com', '2025-03-14', 'Idalia (Lala)', 'Segovia', '307 Westminster Ln', 'Stafford', 'VA', '22556', '5407574137', NULL, 'cellphone', NULL, NULL, '2000-04-05', 'idaliasegovia@aol.com', NULL, 'Cecilia', '70362768', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jadehkerey@gmail.com', '2023-06-26', 'Jadeh', 'Durham', '10 Donna Dale drive', 'Fredericksburg', 'VA', '22405', '7039868433', NULL, 'cellphone', NULL, NULL, '1997-06-29', 'jadehkerey@gmail.com', NULL, 'David', '5714080081', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Durham', 'Female', 'M', 'yes', 'yes', 'yes', 'First aid, cpr/aed certified for healthcare professionals', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jalynnwalden05@gmail.com', '2025-05-31', 'Jalynn', 'Walden', '1418 hudgins farm circle', 'Fredericksburg', 'VA', '22048', '5404290179', NULL, 'cellphone', NULL, NULL, '2008-05-01', 'jalynnwalden05@gmail.com', NULL, 'Titania', '54042901', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'George', 'Female', 'S', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('janedoe1', '2026-03-31', 'Jane', 'Doe', '4 Zallery Rd', 'Stafford', 'VA', '22554', '4444444444', NULL, 'cellphone', '3333333333', 'cellphone', '2002-06-07', 'janedoe@gmail.com', 'true', 'John', '', 'Brother', '', 'volunteer', 'Active', '', '$2y$10$Uh2aZ9qnux9yIIWtY9azH.H3ZGDxx9eVm8IL.2nLtsDsRXmgMV54m', '', '', NULL, 'Doe', 'Female', 'L', 'yes', 'yes', 'no', 'Event planning', 'Industry', 'yes', 72.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('japper', '2026-02-02', 'Jennifer', 'Polack', NULL, 'Fredericksburg', 'VA', NULL, '5406541318', 'true', '', '', '', '', 'jenniferpolack@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$mJzI.UGPGUmYgo7HxTamkeKlsmajzLwXM6su4NdxuHYHZXIGnb0xm', 'Family', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jasminenduenas@gmail.com', '2024-11-14', 'Jasmine', 'Duenas', '146 Longwood Drive', 'Stafford', 'VA', '22556', '5406028631', NULL, 'cellphone', NULL, NULL, '1999-01-08', 'jasminenduenas@gmail.com', NULL, 'Leilani', '54044391', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Duenas', 'Female', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL);
INSERT INTO `dbpersons` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `over21`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `email_prefs`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `affiliation`, `branch`, `archived`, `emergency_contact_last_name`, `gender`, `t_shirt_size`, `computer_access`, `camera_access`, `transportation_access`, `skills`, `experience`, `about_consent`, `total_hours_volunteered`, `force_password_change`, `profile_pic`, `cpr_training_completion`, `aed_training_completion`, `has_disability`, `disability_specifications`) VALUES
('jdistefano@redco504.org', '2025-04-06', 'Joe', 'DiStefano', '11734 Robin Woods Cir', 'Spotsylvania', 'VA', '22551', '5402266131', NULL, 'cellphone', NULL, NULL, '1971-03-16', 'jdistefano@redco504.org', NULL, 'Liane', '5402266131', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'DiStefano', 'Male', 'XL', 'yes', 'yes', 'yes', 'Cash handling, light to heavy lifting, leading, organizing, set-up, clean-up. Basically, whatever is needed. My schedule can be more flexible than I noted here.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jennifer.mannone@encompasshealth.com', '2025-02-19', 'Jennifer', 'Mannone', '10411 wisteria dr', 'Fredericksburg', 'VA', '22408', '5408419586', NULL, 'cellphone', NULL, NULL, '1978-09-24', 'jennifer.mannone@encompasshealth.com', NULL, 'Gabrielle', '54062132', 'Daughter', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Mannone', 'Female', 'L', 'yes', 'yes', 'yes', 'I am a nurse by trade and currently work as a rehab liason with marketing skills. I love to lend a hend to thos in need and enjoy setting up events and not afraid to get my hands dirty with the clean up as well.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Jlipinsk', '2025-12-03', 'Jake', 'Lipinski', NULL, 'Williamsburg', 'VA', NULL, '7577903325', 'true', '', '', '', '', 'jlipinsk@mail.umw.edu', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$qz33T0Sq760IITyYajCYOeWlHR/7sRJH.U609EUkF3R5zRiWWddkG', 'Civilian', 'Army', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jmanfrephotos@gmail.com', '2023-09-22', 'Jennifer', 'Manfre', '24258 Oak Meadow Lane', 'Fredericksburg', 'VA', '22407', '5404797047', NULL, 'cellphone', NULL, NULL, '1975-02-23', 'jmanfrephotos@gmail.com', NULL, 'Shane', '5404554227', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Manfre', 'Female', 'S', 'no', 'yes', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jmckline2000@yahoo.com', '2024-08-11', 'Jeanne', 'Kline', '226 Rolling Hills Road', 'Ruckersville', 'VA', '23294', '4342188751', NULL, 'cellphone', NULL, NULL, '1957-07-08', 'jmckline2000@yahoo.com', NULL, 'Jared', '54061421', 'Son', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Kline', 'Female', 'M', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('joann@gwynethsgift.org', '2023-05-02', 'Jo Ann', 'Dinwoodie', '3 cavern court', 'fredericksburg', 'VA', '22406', '7606817063', NULL, 'cellphone', NULL, NULL, '1971-06-12', 'joann@gwynethsgift.org', NULL, 'James', '7606817130', 'Spouse', NULL, 'volunteer', 'Inactive', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dinwoodie', 'Female', 'S', 'yes', 'yes', 'yes', 'Marketing', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jocelyngmay@gmail.com', '2025-02-11', 'Jocelyn', 'Garcia', '67 Brookesmill Ln', 'Stafford', 'VA', '22554', '5404266651', NULL, 'cellphone', NULL, NULL, '2008-05-20', 'jocelyngmay@gmail.com', NULL, 'Marjorie', '7039868337', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Quiroz', 'Female', 'S', 'no', 'no', 'yes', 'I have experience in public speaking and I can speak spanish.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('joel@gwynethsgift.org', '2023-05-01', 'Joel', 'Griffin', '2217 Princess Anne St', 'Fredericksburg', 'VA', '22401', '5624002637', NULL, 'cellphone', NULL, NULL, '1974-03-21', 'joel@gwynethsgift.org', NULL, 'Jennifer', '5624002637', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Griffin', 'Male', 'XL', 'yes', 'yes', 'yes', 'Public Speaking', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('joey@jfiore.com', '2023-07-16', 'Joey', 'Fiore', '12 Earley Court', 'Stafford', 'VA', '22554', '5406592276', NULL, 'home', NULL, NULL, '2005-04-23', 'joey@jfiore.com', NULL, 'Jeff', '7035938595', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Fiore', 'Male', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('john.beiswanger@gmail.com', '2025-04-01', 'John', 'Beiswanger', '304 Battleship Cove', 'Stafford', 'VA', '22554', '5408401253', NULL, 'cellphone', NULL, NULL, '1960-03-09', 'john.beiswanger@gmail.com', NULL, 'Lynne', '5408500000', 'spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Beiswanger', 'Male', 'L', 'yes', 'yes', 'yes', 'USMC 20 years, Defense contracting 20 years.  Former owner of CrossFit Stafford and the Embassy Cigar Lounge.  Current Deacon for Building and Grounds at Pillar Church of Stafford.  Proficient in MS tools.  Can read some Spanish.  Willing to do anything needed.  Flexible schedule so can help when not otherwise committed and commit to support on Tuesdays', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('johnDoe123', '2026-02-07', 'John', 'Doe', NULL, 'Fredericksburg', 'VA', NULL, '2345678910', 'true', '', '', '', '', 'test@email.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$LTVIuLeSZ4ferdNOe0JdTedaFHqFuEOAz7HDCQuZ4PG9kZrRJc7xS', 'Active duty', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('johnrthompson836@gmail.com', '2024-02-08', 'John', 'Thompson', '11710 Rutherford dr', 'Frederiskburg', 'VA', '22407', '2409419857', NULL, 'cellphone', NULL, NULL, '1991-09-09', 'johnrthompson836@gmail.com', NULL, 'Katrina', '30135116', 'Wife', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Male', 'L', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('jooley@mail.umw.edu', '2026-01-16', 'Jessica', 'Ooley', '7045 Tanglewood Road', 'Spotsylvania', 'VA', '22551', '5406039676', NULL, 'cellphone', NULL, NULL, '2005-08-31', 'jooley@mail.umw.edu', NULL, 'Jennifer', '54080925', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ooley', 'Female', 'L', 'yes', 'no', 'yes', 'Detail-oriented, fast learner', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('juliababa39@gmail.com', '2025-09-11', 'Julia', 'Baba', '10 St Roberts Dr', 'Stafford', 'VA', '22556', '5713518351', NULL, 'cellphone', NULL, NULL, '2009-01-06', 'juliababa39@gmail.com', NULL, 'Regine', '20224778', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Baba', 'Female', 'S', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('juljacquez@gmail.com', '2023-09-01', 'Julie', 'Jacquez', '402 Alder Dr', 'Stafford', 'VA', '22554', '5713347977', NULL, 'cellphone', NULL, NULL, '1975-05-10', 'juljacquez@gmail.com', NULL, 'Richard', '5712284980', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Jacquez', 'Female', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('k.kilroy26@gmail.com', '2024-05-23', 'Kaylie', 'Kilroy', '1315 Rappahannock Ave.', 'Fredericksburg', 'VA', '22401', '9045408288', NULL, 'cellphone', NULL, NULL, '2002-07-10', 'k.kilroy26@gmail.com', NULL, 'David', '54083196', 'Boyfriend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dye', 'Female', 'S', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kamarahr@vcu.edu', '2023-05-03', 'Haroun', 'Kamara', '15 Scottsdale drive', 'Fredericksburg', 'VA', '22405', '7035087955', NULL, 'cellphone', NULL, NULL, '1995-06-14', 'kamarahr@vcu.edu', NULL, 'Zainab', '7035087955', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'tarawallie', 'Male', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('karasg943@gmail.com', '2025-04-03', 'Kara', 'Szutenbach-Gallo', '444 Liberty Boulevard', 'Locust Grove', 'VA', '22508', '5712632981', NULL, 'cellphone', NULL, NULL, '1994-03-11', 'karasg943@gmail.com', NULL, 'Joshua', '57147764', 'Boyfriend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Nieves', 'Female', 'M', 'yes', 'no', 'yes', 'I type quickly, I\'m good with people, I\'m organized, I\'ve done concession stands in high school, I worked in fast food for a long time, I\'m not fluent by any means but I know a little bit of Spanish to be able to help with basic needs and I know basic American Sign Language', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kate.ehrle@caskgov.com', '2024-09-01', 'Kate', 'Ehrle', '30 Ironwood Rd', 'Fredericksburg', 'VA', '22405', '7033040562', NULL, 'cellphone', NULL, NULL, '1975-01-14', 'kate.ehrle@caskgov.com', NULL, 'Rich', '54090447', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ehrle', 'Female', 'L', 'no', 'no', 'yes', 'Would like to volunteer (and offer Cask company volunteers ) to help coordinate and set up whiskey business event. Availability will depend on specific day and schedule', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kathryndurhamkmd1@gmail.com', '2023-11-14', 'Kathryn', 'Durham', '13103 trapp drive', 'Spotsylvania', 'VA', '22551', '5402201344', NULL, 'cellphone', NULL, NULL, '2002-03-27', 'kathryndurhamkmd1@gmail.com', NULL, 'Ciara', '5408091309', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Herndon', 'Female', 'XL', 'yes', 'no', 'yes', 'Intern :)', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kathyb@gotrpiedmont.org', '2026-01-16', 'Kathy', 'Butler', 'PO Box 245', 'Warrenton', 'VA', '20188', '5402964687', NULL, 'work', NULL, NULL, '1974-09-12', 'kathyb@gotrpiedmont.org', NULL, 'Rhett', '57134690', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Butler', 'Female', 'L', 'yes', 'yes', 'yes', 'I am interested in possible helping with the mom prom.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kdickinson540@gmail.com', '2024-08-29', 'Katherine', 'Dickinson', '417 Crosman Court', 'Purcellville', 'VA', '20132', '5715286029', NULL, 'cellphone', NULL, NULL, '2005-05-30', 'kdickinson540@gmail.com', NULL, 'Kristina', '24042390', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dickinson', 'Female', 'M', 'yes', 'no', 'yes', 'Registered Virginia and Nationally registered EMT\nAlso schedule is flexible :)', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kdobyns@gwynethsgift.org', '2023-05-24', 'Kyle', 'Dobyns', '10603 Hamilton\'s Crossing Drive', 'Fredericksburg', 'VA', '22408', '8287775953', NULL, 'cellphone', NULL, NULL, '1987-06-11', 'kdobyns@gwynethsgift.org', NULL, 'Lisa', '5402731200', 'Spouse', NULL, 'admin', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dobyns', 'Male', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kevin@acesbluebc.com', '2024-10-20', 'Kevin', 'Powell', '10414 Piney Branch Rd.', 'Spotsylvania', 'VA', '22553', '5402950778', NULL, 'cellphone', NULL, NULL, '1970-02-23', 'kevin@acesbluebc.com', NULL, 'Christy', '54062393', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ferreira', 'Male', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kgt0323@gmail.com', '2025-04-22', 'Kathleen', 'Gannon Tye', '9104 Horner Ct', 'Fairfax', 'VA', '22031', '7039676763', NULL, 'cellphone', NULL, NULL, '1954-03-23', 'kgt0323@gmail.com', NULL, 'Alan', '7033041111', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Tye', 'Female', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kirapohwala@gmail.com', '2025-12-29', 'Kira', 'Pohwala', '3 Saint Vincent Court', 'Stafford', 'VA', '22556', '5404464155', NULL, 'cellphone', NULL, NULL, '2009-08-01', 'kirapohwala@gmail.com', NULL, 'Conchita', '5164043374', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Pohwala', 'Female', 'S', 'yes', 'yes', 'yes', 'Proficient in Spanish', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kjtj02@gmail.com', '2024-06-20', 'Kris', 'Tuebner', '11301 Beauclaire Blvd.', 'Fredericksburg', 'VA', '22408', '5407358389', NULL, 'cellphone', NULL, NULL, '2005-02-02', 'kjtj02@gmail.com', NULL, 'Erin', '54037915', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Tuebner', 'Male', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kkilroyfit@gmail.com', '2024-05-23', 'Kaylie', 'Kilroy', '1315 Rappahannock Ave', 'Fredericksburg', 'VA', '24401', '9045408288', NULL, 'cellphone', NULL, NULL, '2002-07-10', 'kkilroyfit@gmail.com', NULL, 'David', '54083196', 'Boyfriend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dye', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kmatuczinski@hotmail.com', '2024-06-17', 'Kelly', 'Matuczinski', '77 Timberidge Dr', 'Fredericksburg', 'VA', '22406', '7572870684', NULL, 'cellphone', NULL, NULL, '1972-03-28', 'kmatuczinski@hotmail.com', NULL, 'Greg', '75750829', 'spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Matuczinski', 'Male', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('kpronzo1@gmail.com', '2026-01-13', 'Kate', 'Ronzoni', '1 Captains Walk', 'East Setauket', 'NY', '11733', '6313727722', NULL, 'cellphone', NULL, NULL, '2007-06-10', 'kpronzo1@gmail.com', NULL, 'Christine', '63170738', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Mirabella', 'Female', 'L', 'yes', 'no', 'yes', 'I am very good at listening to directions and understanding the job asked of me.  I am interested in the medical field so I feel that this would be a good opportunity to further my skills in this field.  I am currently studying Biomedical Sciences.  I had straight A\'s in college while taking several AP and honors classes.  I have a hardworking mindset and attitude.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lachemoi105@duck.com', '2025-09-19', 'Ray', 'Daniels', '6011 Sunlight Mountain Rd.', 'Spotsylvania', 'VA', '22553', '5716062023', NULL, 'cellphone', NULL, NULL, '1999-08-01', 'lachemoi105@duck.com', NULL, 'Rita', '41057056', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Daniels', 'Male', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ladyduncan82@gmail.com', '2025-01-14', 'Sarah', 'Duncan', '10928 Stacy Run # 10928', 'Fredericksbrg', 'VA', '22408', '3013517159', NULL, 'cellphone', NULL, NULL, '1982-07-04', 'ladyduncan82@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'S', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('langstonf@yahoo.com', '2025-05-16', 'Laythan', 'Fairchild', '10127 New Scotland Drive', 'Fredericksburg', 'VA', '22408', '5403791835', NULL, 'cellphone', NULL, NULL, '2002-02-03', 'langstonf@yahoo.com', NULL, 'Lansgton', '54037918', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Fairchild', 'Male', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lblochowicz@gmail.com', '2025-02-01', 'Leah', 'Blochowicz', '1023 julian dr', 'Fredericksburg', 'VA', '22405', '5404550661', NULL, 'cellphone', NULL, NULL, '1974-12-04', 'lblochowicz@gmail.com', NULL, 'Joseph', '54045506', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'blochowicz', 'Female', 'L', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lgould@vapartnersbank.com', '2023-06-23', 'Lindsay', 'Gould', '1109 Prince Edward Street', 'Fredericksburg', 'VA', '22401', '8046909277', NULL, 'cellphone', NULL, NULL, '1966-05-08', 'lgould@vapartnersbank.com', NULL, 'Jim', '8043979550', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Gould', 'Female', 'L', 'yes', 'yes', 'yes', 'Event management experience', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lilysanford06@gmail.com', '2024-07-11', 'Lily', 'Sanford', '124 Whitetail Way', 'Fredericksburg', 'VA', '22406', '2404166581', NULL, 'cellphone', NULL, NULL, '2006-01-19', 'lilysanford06@gmail.com', NULL, 'Lauren', '24030018', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Isenberg', 'Female', 'S', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lisaedurham@gmail.com', '2023-09-21', 'Lisa', 'Durham', '615 Fauquier St', 'Fredericksburg', 'VA', '22401', '5408507831', NULL, 'cellphone', NULL, NULL, '1968-03-08', 'lisaedurham@gmail.com', NULL, 'David', '7034704769', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Durham', 'Female', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lmorgan@linkbank.com', '2025-09-08', 'Lindsay', 'Morgan', '410 William Street', 'Fredericksburg', 'VA', '22401', '5403763824', NULL, 'work', NULL, NULL, '1988-05-11', 'lmorgan@linkbank.com', NULL, 'Sam', '54045518', 'partner', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Brann', 'Female', 'S', 'yes', 'no', 'yes', 'IT', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lopezsn.usmc@gmail.com', '2025-03-01', 'Sara', 'Lopez', '17789 Osprey Harbor Ln', 'Dumfries', 'VA', '22026', '6267332134', NULL, 'cellphone', NULL, NULL, '1979-12-18', 'lopezsn.usmc@gmail.com', NULL, 'Tony', '80872414', 'Boyfriend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lopez', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lukeg', '2025-04-29', 'Luke', 'Gibson', '22 N Ave', 'Fredericksburg', 'VA', '22401', '1234567890', NULL, 'cellphone', '1234567890', 'cellphone', '2025-04-28', 'volunteer@volunteer.com', NULL, 'NoName', 'n/a', 'Brother', NULL, 'volunteer', 'Active', NULL, '$2y$10$KsNVJYhvO5D287GpKYsIPuci9FnL.Eng9R6lBpaetu2Y0yVJ7Uuiq', NULL, NULL, 0, 'YesName', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lvenable@linkbank.com', '2024-09-12', 'Lakeisha', 'Venable', '9605 Coventry Creek Drive', 'Fredericksburg', 'VA', '22408', '7036790585', NULL, 'cellphone', NULL, NULL, '1977-11-30', 'lvenable@linkbank.com', NULL, 'Paula', '5407521715', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Broemson', 'Female', 'L', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ly.cindy27@gmail.com', '2023-07-14', 'Cindy', 'Ly', '2106 Elmhurst Ave.', 'Fredericksburg', 'VA', '22401', '5713329662', NULL, 'cellphone', NULL, NULL, '1999-07-27', 'ly.cindy27@gmail.com', NULL, 'Hoang', '7035894967', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ly', 'Female', 'S', 'yes', 'yes', 'yes', 'Analytical, writing, cooking, and communication', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lynellekapinos@msn.com', '2025-10-19', 'Lynelle', 'Kapinos', '20 Chandler Ct', 'Fredericksburg', 'VA', '22405', '3035172792', NULL, 'cellphone', NULL, NULL, '1966-02-09', 'lynellekapinos@msn.com', NULL, 'Jim', '54064591', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Kapinos', 'Female', 'XXL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('m410s1234@gmail.com', '2023-08-31', 'Ginnie', 'Branscome', '1400 Washington Ave', 'Fredericksburg', 'VA', '22401', '5407606216', NULL, 'cellphone', NULL, NULL, '1952-01-15', 'm410s1234@gmail.com', NULL, 'James', '5407603292', 'husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Branscome', 'Female', 'L', 'yes', 'no', 'yes', 'flexible skills and dates', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('maddiev', '2025-04-28', 'maddie', 'van buren', '123 Blue st', 'fred', 'VA', '12343', '1234567890', NULL, 'cellphone', '1234567819', 'cellphone', '2003-05-17', 'mvanbure@mail.umw.edu', NULL, 'mommy', 'n/a', 'mom', NULL, 'volunteer', 'Active', NULL, '$2y$10$0mv3.e6gjqoIg.HfT5qVXOsI.Ca5E93DAy8BnT124W1PvMDxpfoxy', NULL, NULL, 0, 'van buren', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('marjoriequiroz@yahoo.com', '2025-03-04', 'Janice', 'Garcia', '67 Brookesmill Ln', 'Stafford', 'VA', '22554', '5407838460', NULL, 'cellphone', NULL, NULL, '2010-08-24', 'marjoriequiroz@yahoo.com', NULL, 'Marjorie', '70398683', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Quiroz', 'Female', 'S', 'no', 'no', 'yes', 'Leadership skills, communication, teamwork, time management, public speaking.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('mary@kibbey.com', '2024-06-20', 'Mary', 'Kibbey', '4303 Murven Park Lane', 'Fredericksburg', 'VA', '22408', '5404200187', NULL, 'cellphone', NULL, NULL, '1960-05-07', 'mary@kibbey.com', NULL, 'Don', '5408980000', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Kibbey', 'Female', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('marytokar1@gmail.com', '2023-05-16', 'Mary', 'Lipscomb', '7 Braddock Dr.', 'Fredericksburg', 'VA', '22405', '5407103604', NULL, 'cellphone', NULL, NULL, '1981-02-16', 'marytokar1@gmail.com', NULL, 'Dre', '5712370203', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lipscomb', 'Female', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('matskig@gmail.com', '2023-09-01', 'Greg', 'Matuczinski', '77 Timberidge Drive', 'Fredericksburg', 'VA', '22406', '7575082991', NULL, 'cellphone', NULL, NULL, '1969-11-01', 'matskig@gmail.com', NULL, 'Kelly', '7572870684', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Matuczinski', 'Male', 'L', 'yes', 'no', 'yes', 'Time listed is a placeholder only as it varies - had to enter something to sign up', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('melgarvanesa736@gmail.com', '2025-11-26', 'Etelinda', 'Melgar', '34 Lawhorn Rd', 'Stafford', 'VA', '22554', '5408738968', NULL, 'cellphone', NULL, NULL, '2006-07-28', 'melgarvanesa736@gmail.com', NULL, 'Mártir', '54031025', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Melgar', 'Female', 'S', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('mgordon322@gmail.com', '2026-01-02', 'Mary', 'Gordon', '6918 Fern Ln', 'Annandale', 'VA', '22003', '7039652075', NULL, 'cellphone', NULL, NULL, '1982-03-22', 'mgordon322@gmail.com', NULL, 'Matt', '21924218', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Costakis', '', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('michael_smith', '2025-03-16', 'Michael', 'Smith', '789 Pine Street', 'Charlottesville', 'VA', '22903', '4345559876', NULL, 'mobile', '4345553322', 'work', '1995-08-22', 'michaelsmith@email.com', NULL, 'Sarah', '4345553322', 'Sister', 'email', 'volunteer', 'Active', '', '$2y$10$XYZ789xyz456LMN123DEF', NULL, NULL, 0, 'Smith', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('michellevb', '2025-04-29', 'Michelle', 'Van Buren', '1234 Red St', 'Freddy', 'VA', '22401', '1234567890', NULL, 'cellphone', '0987654321', 'cellphone', '1980-08-18', 'michelle.vb@gmail.com', NULL, 'Madison', 'n/a', 'daughter', NULL, 'volunteer', 'Active', NULL, '$2y$10$bkqOWUdIJoSa6kZoRo5KH.cerZkBQf74RYsponUUgefJxNc8ExppK', NULL, NULL, 0, 'Van Buren', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('mlanderson0301@gmail.com', '2024-11-18', 'Michelle', 'Anderson', '10611 Heather Greens Cir', 'Spotsylvania', 'VA', '22553', '7039692162', NULL, 'cellphone', NULL, NULL, '1968-03-01', 'mlanderson0301@gmail.com', NULL, 'Anthony', '70386224', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Anderson', 'Female', 'XXL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('morrismakenna09@gmail.com', '2025-09-07', 'Makenna', 'Morris', '175 Still Water Ln', 'Fredericksburg', 'VA', '22406', '5406429105', NULL, 'cellphone', NULL, NULL, '2009-07-28', 'morrismakenna09@gmail.com', NULL, 'Stacey', '54064291', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Morris', 'Female', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('mossfamx4@gmail.com', '2024-04-29', 'Deborah', 'Moss', '11295 Shamrock Lane', 'King George', 'VA', '22485', '5409039389', NULL, 'cellphone', NULL, NULL, '1962-12-21', 'mossfamx4@gmail.com', NULL, 'Sherman', '54042925', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Moss', 'Female', 'L', 'yes', 'yes', 'yes', 'computer, food preparation and organization, activity support', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('mrs.emily.aylor@gmail.com', '2025-02-18', 'Emily', 'Aylor', '40 Hunting Creek Lane', 'Stafford', 'VA', '22556', '2026798012', NULL, 'cellphone', NULL, NULL, '1988-12-28', 'mrs.emily.aylor@gmail.com', NULL, 'Stephen', '20254980', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Aylor', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('myrianarodriguezb@gmail.com', '2026-01-12', 'Myriana', 'Rodriguez Bonilla', '475 Potomac Run rd', 'Fredrickburg', 'VA', '22405', '5404550133', NULL, 'cellphone', NULL, NULL, '2008-06-01', 'myrianarodriguezb@gmail.com', NULL, 'Myrianel', '8042523499', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Bonilla', 'Female', 'S', 'yes', 'no', 'no', 'Fluent Spanish speaker(bilingual), artist/painting, organization skills\n\n(Schedule can vary, I may be available in the afternoons if asked ahead of time to coordinate a ride)', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('naaheywood22@gmail.com', '2025-09-12', 'Naa', 'Heywood', '16 St Marks Ct', 'Stafford', 'VA', '22556', '4349067990', NULL, 'cellphone', NULL, NULL, '2008-10-04', 'naaheywood22@gmail.com', NULL, 'Maureen', '57147772', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Akordor', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('naftel@cox.net', '2024-11-30', 'Nora', 'Aftel', '1010 Hillcrest Terrace', 'Fredericksburg', 'VA', '22405', '5408094420', NULL, 'cellphone', NULL, NULL, '1972-07-23', 'naftel@cox.net', NULL, 'Robert', '54080944', 'spouse', NULL, 'volunteer', 'Inactive', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Aftel', 'Female', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('nam.ballard@gmail.com', '2024-10-22', 'Amber', 'Ballard', '32 Blair Road', 'Fredericksburg', 'VA', '22405', '5406047803', NULL, 'cellphone', NULL, NULL, '2007-02-26', 'nam.ballard@gmail.com', NULL, 'Tiffaney', '5408451193', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ballard', 'Female', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('nancy.dongweck@target.com', '2024-06-20', 'Nancy', 'Dongweck', '6241 Courthouse Rd.', 'Spotsylvania', 'VA', '22551', '4349413994', NULL, 'cellphone', NULL, NULL, '1957-03-03', 'nancy.dongweck@target.com', NULL, 'Tiffany', '6145728196', 'friend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'L', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('nancylynnrp@gmail.com', '2024-06-09', 'Nancy', 'Pattillo', '1027 Portugal Drive', 'Stafford', 'VA', '22554', '9123122748', NULL, 'cellphone', NULL, NULL, '1983-09-25', 'nancylynnrp@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('natesellsva@gmail.com', '2023-09-22', 'Nate', 'Ferguson', '201 Heritage Commons Drive', 'Fredericksburg', 'VA', '22405', '5713101921', NULL, 'cellphone', NULL, NULL, '1979-02-09', 'natesellsva@gmail.com', NULL, 'Faranda', '5404290703', 'Sister', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ferguson', 'Male', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('neelylewis@hotmail.com', '2023-05-16', 'Neely', 'Lewis', '11268 Tulip Ln', 'King George', 'VA', '22485', '5407500513', NULL, 'cellphone', NULL, NULL, '1978-07-01', 'neelylewis@hotmail.com', NULL, 'Molly', '5407068262', 'Daughter', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Watson', 'Female', 'XL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('nevershoutkris@icloud.com', '2024-06-03', 'Kristyn', 'Gonzalez', '6803 Silverbrook Dr', 'Spotsylvania Courthouse', 'VA', '22553', '5716660240', NULL, 'cellphone', NULL, NULL, '1996-08-28', 'nevershoutkris@icloud.com', NULL, 'Kristyn', '', '', NULL, 'volunteer', 'Inactive', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Gonzalez', 'Female', 'M', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ngoode1985@gmail.com', '2025-04-12', 'Nicole', 'Goode', '5 Heron Dr. Apt 104', 'Fredericksburg', 'VA', '22406', '2406821576', NULL, 'cellphone', NULL, NULL, '1985-10-29', 'ngoode1985@gmail.com', NULL, 'Eric', '5715527326', 'Boyfriend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Kornegay', 'Female', 'XXL', 'yes', 'no', 'yes', 'I am good with people, I am computer savvy. I am volunteering for the Mom Prom on behalf of LinkBank.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('nivin@spaviacentralpark.com', '2023-11-11', 'Nivin', 'Elgohary', '115 Affirmed Dr', 'Stafford', 'VA', '22556', '2028050961', NULL, 'cellphone', NULL, NULL, '1967-06-17', 'nivin@spaviacentralpark.com', NULL, 'Mark', '5404980093', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Slupek', 'Female', 'L', 'yes', 'yes', 'yes', 'I speak Arabic, I have 30 years of commercial lending experience, I am certified massage therapist and a business owner of a luxury day spa in FXBG.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('npage@linkbank.com', '2025-02-28', 'Nelson', 'Page', '410 Williams St', 'Fredericksburg', 'VA', '22401', '5713778753', NULL, 'cellphone', NULL, NULL, '1987-03-16', 'npage@linkbank.com', NULL, 'Jasmine', '7577591647', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Page', 'Male', 'XL', 'yes', 'yes', 'yes', 'Finance, Cash Handling, Sales, Computers, Tech-Enthusiast, Social Media, Marketing, Web Development, Communications, Graphic Design, Public Speaking, Software Development, and more.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ntisti95@aol.com', '2025-02-24', 'Kyle', 'Ierardi', '10703 Cedar Post Lane', 'Spotsylvania', 'VA', '22553', '9419937116', NULL, 'cellphone', NULL, NULL, '1964-06-06', 'ntisti95@aol.com', NULL, 'Joe', '9414000000', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ierardi', 'Female', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('nzhzhanecs@gmail.com', '2025-09-03', 'Ninghao', 'Zhan', '129 Autumn Dr', 'Stafford', 'VA', '22556', '2029997705', NULL, 'cellphone', NULL, NULL, '2009-04-05', 'nzhzhanecs@gmail.com', NULL, 'Jingku', '20290893', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Zhan', 'Male', 'M', 'yes', 'no', 'no', 'Proficiency in Mandarin and certificate of completion in Psychological First Aid from Johns Hopkins University', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('nzhzhanecs@icloud.com', '2025-09-05', 'Ninghao', 'Zhan', '129 Autumn Dr', 'Stafford', 'VA', '22556', '2029997705', NULL, 'cellphone', NULL, NULL, '2009-04-05', 'nzhzhanecs@icloud.com', NULL, 'Jingku', '20290893', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Zhan', 'Male', 'M', 'yes', 'no', 'no', 'Proficiency in Mandarin and Certificate of Completion in Psychological First Aid provided by Johns Hopkins University', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('obxnutj1@gmail.com', '2024-03-25', 'jody', 'wilken', '602 Camden Drive', 'Fredericksburg', 'VA', '22405', '5408424730', NULL, 'cellphone', NULL, NULL, '1959-12-29', 'obxnutj1@gmail.com', NULL, 'jonathan', '54053872', 'spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'wilken', 'Female', 'M', 'yes', 'no', 'yes', 'I spent 21 years as a flight attendant and am great with people. Currently the full-time garden manager at Gari Melchers Home and Studio, but I am retiring next year and will have much more free time to volunteer. Currently, I\'m only available to help on weekends as my work schedule is 8-5 Monday-Friday.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('olivia', '2026-02-04', 'Olivia', 'Blue', NULL, 'Fredericksburg', 'VA', NULL, '1112223333', 'false', '', '', '', '', 'oliviablue@gmail.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$ew4nuUYBtx6.CbNBezMTYuAQGaxMJgxIs4I3uIx05Sb7SqxKHJO2S', 'Family', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('oumizoomie08@gmail.com', '2025-02-15', 'Oumi', 'Dieng', '604 BenNeuis Pl', 'Fredericksburg', 'VA', '22405', '5409078834', NULL, 'cellphone', NULL, NULL, '2008-10-30', 'oumizoomie08@gmail.com', NULL, 'Veronique', '54022086', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dieng', 'Female', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('p.morris@live.com', '2024-06-06', 'Patti', 'Morris', '1104 College Ave', 'Fredericksburg', 'VA', '22401', '8135281244', NULL, 'cellphone', NULL, NULL, '1968-07-03', 'p.morris@live.com', NULL, 'Todd', '81399210', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Sheffer', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('pb@gmail.com', '2025-08-22', 'Pure', 'Barre', '1900 Charles Street', 'Fredricksburg', 'VA', '22401', '6145728196', NULL, 'cellphone', NULL, NULL, '2000-08-22', 'pb@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', '', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('pennierose@cox.net', '2023-09-03', 'Pennie', 'Rose', '606 pelham street', 'fredericksburg', 'VA', '22401', '7035956372', NULL, 'cellphone', NULL, NULL, '1964-01-10', 'pennierose@cox.net', NULL, 'AJ', '7034081881', 'Housemate', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Rose', 'Female', 'L', 'yes', 'no', 'yes', 'You can train me to do just about anything you need me to do except sing and dance. I’ve got crew management skills, bartending and will help set up and tear down at events.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('planetofthekids@yahoo.com', '2024-09-05', 'Rhonda', 'Fried', '425 William Street, Unit 401', 'Fredericksburg', 'VA', '22401', '5408466184', NULL, 'cellphone', NULL, NULL, '2023-09-23', 'planetofthekids@yahoo.com', NULL, 'Adam', '54029577', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Fried', 'Female', 'XXL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('polack@umw.edu', '2023-04-26', 'Jennifer', 'Polack', 'N/A', 'N/A', 'AL', '0', '0', NULL, 'cellphone', NULL, NULL, '2023-04-26', 'polack@umw.edu', NULL, 'N/A', '0', 'N/A', NULL, 'superadmin', 'Inactive', 'Dr. Polack account to help manage web app. Do not alter!', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('quiann.sunn@gmail.com', '2025-01-29', 'Quiann', 'Sun', '1108 William St', 'Fredericksburg', 'VA', '22401', '5086153046', NULL, 'cellphone', NULL, NULL, '2006-12-11', 'quiann.sunn@gmail.com', NULL, 'Anthony De', '57139886', 'Boyfriend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Leon', 'Female', 'M', 'yes', 'yes', 'no', 'I have basic conversational skills in Mandarin and Spanish. I am also trained and certified in Basic Life Skills (CPR, AED) from the ARC', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('r.adam.calloway@gmail.com', '2025-09-19', 'Adam', 'Calloway', '1608 Franklin St', 'Fredericksburg', 'VA', '22401', '5408454982', NULL, 'cellphone', NULL, NULL, '1988-02-08', 'r.adam.calloway@gmail.com', NULL, 'Gary', '5403730000', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Calloway', 'Male', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rachelmaejohnson13@gmail.com', '2025-09-16', 'Rachel', 'Johnson', '126 Wellington Lakes Dr Apt 112', 'Fredericksburg', 'VA', '22401', '7635168942', NULL, 'cellphone', NULL, NULL, '1996-08-28', 'rachelmaejohnson13@gmail.com', NULL, 'Alice', '20360646', 'Roommate', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Bauer', 'Other', 'M', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('radantoniobt10@gmail.com', '2024-07-25', 'Bryce', 'Townes', '5207 Elk Creek Circle', 'Fredericksburg', 'VA', '22407', '5715528177', NULL, 'cellphone', NULL, NULL, '2003-07-15', 'radantoniobt10@gmail.com', NULL, 'Ashonte', '57123533', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Woolen', 'Male', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rafael.hernandez@mwhc.com', '2025-01-18', 'Rafael', 'Hernandez', '129 lakeshore drive', 'Fredericksburg', 'VA', '22405', '5402206082', NULL, 'cellphone', NULL, NULL, '1960-07-25', 'rafael.hernandez@mwhc.com', NULL, 'Rafa', '54073545', 'Son', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'hernandez', 'Male', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('railynw10@gmail.com', '2025-09-08', 'Railyn', 'Washington', '106 Zoe way', 'Stafford', 'VA', '22554', '3215057125', NULL, 'cellphone', NULL, NULL, '2008-03-10', 'railynw10@gmail.com', NULL, 'LaNeika', '40726705', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Washington', 'Female', 'M', 'yes', 'no', 'yes', 'People skills, organizational skills, time management skills', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rayyanaokyere440@gmail.com', '2025-02-15', 'Rayyana', 'Okyere', '14 Shadowbrook Ln', 'Fredericksburg', 'VA', '22406', '5713209764', NULL, 'cellphone', NULL, NULL, '2007-11-06', 'rayyanaokyere440@gmail.com', NULL, 'Ama', '57127709', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Nartey', 'Female', 'S', 'no', 'no', 'yes', 'I have compassion, communication, organizational, and people skills. Furthermore, I am taking french 4 right now and understand Twi.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('reachamog@gmail.com', '2024-04-30', 'Amog', 'Prasanna', '2115 Cowan Blvd', 'Fredericksburg', 'VA', '22401', '3017687936', NULL, 'cellphone', NULL, NULL, '1997-03-23', 'reachamog@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Male', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rhoads114@gmail.com', '2024-05-23', 'Allie', 'Rhoads', '5608 Steeplechase Dr., Apt. B', 'Fredericksburg', 'VA', '22407', '7039869709', NULL, 'cellphone', NULL, NULL, '1999-09-17', 'rhoads114@gmail.com', NULL, 'Alfred Derricott', '80457282', 'Boyfriend', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Jr.', 'Female', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rlloyd2@mail.umw.edu', '2024-06-20', 'Rayna', 'Lloyd', '35988 Turkey Roost Rd', 'Middleburg', 'VA', '20117', '5713455699', NULL, 'cellphone', NULL, NULL, '2002-06-10', 'rlloyd2@mail.umw.edu', NULL, 'Brian', '57127131', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lloyd', 'Female', 'S', 'yes', 'yes', 'yes', 'I have my CPR certification. I have helped teach a CPR class with the CPR coordinator to demonstrate how to properly give CPR at a doctor\'s office. I went through two years of nursing school and obtained 72 hours during clinical in the hospital taking care of patients. I\'m very good with people and putting a smile on their faces. I have problem-solving skills, critical thinking skills., and active listening skills, etc. I am always motivated, and I dedicate my life to helping others. I am currently at the University of Mary Washington and plan to become an occupational therapist in the future.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rmgoodwin03@gmail.com', '2024-05-23', 'Ryan', 'Goodwin', '2530 Cornell Dr', 'Fredericksburg', 'VA', '22408', '5404558176', NULL, 'cellphone', NULL, NULL, '2003-09-25', 'rmgoodwin03@gmail.com', NULL, 'Clarissa', '5408410000', 'Mom', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Price', 'Male', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rnsullivan1@gmail.com', '2025-11-08', 'Rachel', 'Sullivan', '8817 Henly Court', 'Fredericksburg', 'VA', '22408', '5409071576', NULL, 'cellphone', NULL, NULL, '1999-10-15', 'rnsullivan1@gmail.com', NULL, 'Timothy', '54090703', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Sullivan', 'Female', 'XL', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('robyn.schlichtingmorriron@gmail.com', '2025-02-28', 'Robyn', 'Morrison', '1824 Sag Harbor Ln', 'Fredericksbrg', 'VA', '22401', '7578183701', NULL, 'cellphone', NULL, NULL, '1980-07-05', 'robyn.schlichtingmorriron@gmail.com', NULL, 'James', '57132002', 'Partner', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Miller', 'Female', 'L', 'yes', 'yes', 'yes', 'Bartending, event manageme, administration', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rodasun@hotmail.com', '2025-05-19', 'Mercedes', 'Rosa', '115 Hidden Brook Dr', 'Fredericksburg', 'VA', '22405', '9103786919', NULL, 'cellphone', NULL, NULL, '1989-08-25', 'rodasun@hotmail.com', NULL, 'John', '9105542437', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Rosa', 'Female', 'M', 'no', 'no', 'yes', 'I am fluent in Spanish', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rsalazar@linkbank.com', '2025-01-22', 'Aracxy Roxy', 'Salazar', '10604 Crestwood Drive', 'Spotsylvania', 'VA', '22553', '5408507395', NULL, 'cellphone', NULL, NULL, '1981-02-21', 'rsalazar@linkbank.com', NULL, 'Onofre', '5408505872', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Salazar', 'Female', 'L', 'yes', 'no', 'yes', 'Fluent in Spanish', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('rwarren@mail.umw.edu', '2024-03-12', 'Ryan', 'Warren', '2234 Van Buren Court', 'Falls Church', 'VA', '22043', '5714470808', NULL, 'cellphone', NULL, NULL, '2001-12-15', 'rwarren@mail.umw.edu', NULL, '', '', '', NULL, 'superadmin', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', '', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL);
INSERT INTO `dbpersons` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `over21`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `email_prefs`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `affiliation`, `branch`, `archived`, `emergency_contact_last_name`, `gender`, `t_shirt_size`, `computer_access`, `camera_access`, `transportation_access`, `skills`, `experience`, `about_consent`, `total_hours_volunteered`, `force_password_change`, `profile_pic`, `cpr_training_completion`, `aed_training_completion`, `has_disability`, `disability_specifications`) VALUES
('ryglahl@gmail.com', '2024-09-09', 'Ryan', 'Lawrence', '1701 College Avenue', 'Fredericksburg', 'VA', '22401', '5713282875', NULL, 'cellphone', NULL, NULL, '2004-11-25', 'ryglahl@gmail.com', NULL, 'Jason', '7039633489', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lawrence', 'Male', 'M', 'yes', 'no', 'no', 'I have experience programming in java.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('s3ruse@gmail.com', '2024-03-11', 'Stacy', 'Gibbons', '460 Ferdinand Day Dr', 'Alexandria', 'VA', '22304', '2693305964', NULL, 'cellphone', NULL, NULL, '1985-08-30', 's3ruse@gmail.com', NULL, 'Hanley', '81383356', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Gibbons', 'Female', 'M', 'yes', 'yes', 'yes', 'Highly organized, great personal skills, crafty, willing to do the small things to get stuff done.  I have varying availability because I work full time and have a 4 year old, but happy to help out when I can.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('saleemsamar@scps.net', '2025-11-16', 'Samar', 'Saleem', '3 darden', 'Stafford', 'VA', '22554', '5715130866', NULL, 'cellphone', NULL, NULL, '2010-06-15', 'saleemsamar@scps.net', NULL, 'Roya', '70358501', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Hamid', 'Female', 'M', 'yes', 'no', 'yes', 'Fluent in Farsi', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sales@evruso.com', '2023-05-09', 'Evan', 'Ruchelman', '12 Westie Way, Apt 302', 'Fredericksburg', 'VA', '22554', '5409033763', NULL, 'cellphone', NULL, NULL, '1966-10-01', 'sales@evruso.com', NULL, 'Sami', '5404243377', 'Daughter', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Ruchelman', 'Male', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('samir.patel@firstcitizens.com', '2023-08-30', 'Samir', 'Patel', '9300 Wood Creek Circle', 'Fredericksburg', 'VA', '22407', '5408482430', NULL, 'cellphone', NULL, NULL, '1983-01-08', 'samir.patel@firstcitizens.com', NULL, 'Ramesh', '7573108885', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Patel', 'Male', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sandrasteelenp@gmail.com', '2023-08-06', 'Sandi', 'Steele', '5711 Olde Hartley Way', 'Glen Allen', 'VA', '23060', '8043044375', NULL, 'cellphone', NULL, NULL, '1959-09-02', 'sandrasteelenp@gmail.com', NULL, 'Chad', '8042395626', 'Son', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Glasheen', 'Female', 'XL', 'no', 'no', 'no', 'Medical Professional , Nutrition, Personal Trainer,', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sarahannehurst@gmail.com', '2025-04-30', 'Sarah', 'Hurst', '415 pelham street', 'Fredericksburg', 'VA', '22401', '5403057244', NULL, 'cellphone', NULL, NULL, '1987-08-03', 'sarahannehurst@gmail.com', NULL, 'John', '1111111111', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lanzone', 'Male', 'S', 'yes', 'yes', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sdsmith0613@yahoo.com', '2024-11-30', 'Shereica', 'Smith', '505 Howison Avenue', 'Fredericksburg', 'VA', '22401', '5402873234', NULL, 'cellphone', NULL, NULL, '1973-03-13', 'sdsmith0613@yahoo.com', NULL, 'Arthur', '54081863', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Smith', 'Female', 'XXL', 'yes', 'yes', 'yes', 'Notary', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sfrick@gwynethsgift.org', '2024-07-11', 'Shannon', 'Frick', '13000 Platoon Drive', 'Spotsylvania', 'VA', '22551', '7039801996', NULL, 'cellphone', NULL, NULL, '1984-02-27', 'sfrick@gwynethsgift.org', NULL, 'Alexander', '71693010', 'Partner', NULL, 'superadmin', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Thomson', 'Female', 'L', 'yes', 'yes', 'yes', 'ALS and BLS instruction, and sheer awesomeness.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('shaeg2342@gmail.com', '2025-04-12', 'Shae', 'Germuska', '1900 Charles Street', 'Fredericksburg', 'VA', '22401', '6146237842', NULL, 'cellphone', NULL, NULL, '1996-02-23', 'shaeg2342@gmail.com', NULL, 'Shani', '6148323843', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Germuska', 'Female', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sheila.nazari2@gmail.com', '2023-08-31', 'Sheila', 'Nazari', '4540 Westhall Dr NW', 'Washington', 'WA', '20007', '4159875130', NULL, 'cellphone', NULL, NULL, '1970-09-12', 'sheila.nazari2@gmail.com', NULL, 'Soheil', '4159393141', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'M', 'yes', 'no', 'yes', 'Administrative\nManagement \nAll skills related to motherhood \nA little Farsi', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('showerswinona@gmail.com', '2025-03-01', 'Winona', 'Showers', '20 Ferguson drive', 'Stafford', 'VA', '22554', '5403615094', NULL, 'work', NULL, NULL, '2007-03-21', 'showerswinona@gmail.com', NULL, 'Crispina ', '54036151', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'showers', 'Female', 'S', 'no', 'no', 'no', 'French -intermediate', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('shurst@cbeva.com', '2025-04-12', 'Sarah', 'Hurst', '415 Pelham st', 'Fredericksburg', 'VA', '22401', '5403057244', NULL, 'cellphone', NULL, NULL, '1987-08-03', 'shurst@cbeva.com', NULL, 'John', '5403057244', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lanzone', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('simangotanatswat@scps.net', '2025-10-18', 'Tanatswa', 'Simango', '100 Lancelot Lane', 'Fredericksburg', 'VA', '22046', '8262462444', NULL, 'cellphone', NULL, NULL, '2011-05-31', 'simangotanatswat@scps.net', NULL, 'Reuben', '82623283', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Simango', 'Female', 'L', 'no', 'no', 'yes', 'I want to learn more about CPR and other things', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('siochainsiobhan@gmail.com', '2025-01-24', 'Siobhan', 'Woodburn', '22 Pensacola St', 'Fredericksburg', 'VA', '22406', '5406046185', NULL, 'cellphone', NULL, NULL, '1963-03-15', 'siochainsiobhan@gmail.com', NULL, 'John', '54090700', 'Son', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Woodburn', 'Female', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sistercak@yahoo.com', '2023-05-09', 'Carolyn', 'Johnson', '15170 Holleyside Drive', 'Dumfries', 'VA', '22025', '5407356529', NULL, 'cellphone', NULL, NULL, '1993-07-22', 'sistercak@yahoo.com', NULL, 'Andrew', '7034088724', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Johnson', 'Female', 'M', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sofia2nura@gmail.com', '2024-04-22', 'Sofia', 'Nunura', '6 Charter Gate dr', 'Fredericksburg', 'VA', '22406', '5716190346', NULL, 'cellphone', NULL, NULL, '2007-08-03', 'sofia2nura@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'XXL', 'yes', 'yes', 'yes', 'Hello, I am Sofia Nunura, a student at Stafford High School. I am interested in volunteering or working with the Healthy Generations Agency. \nI have been volunteering with the Fred Regional Food bank for almost three years, the Fred Park and Rec for two, and many other organizations such as the Massad YMCA, Mica Ministires, Brisben Center, and many more! I am also fluent in Spanish and moderate in Latin and French. I have experience in caretaking, accounting and leadership. I run two clubs at my school! I am interested in majoring in psychology so I have taken various courses on psycholgy and sociology, \nI am available seven days a week. Anytime after two on week days and anytime on weekends. I am passionate about community service and giving back to my community.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sskennepohl@gmail.com', '2025-05-08', 'Samantha', 'Kennepohl', '304 Battleship cove', 'Stafford', 'VA', '22554', '5408401253', NULL, 'cellphone', NULL, NULL, '1995-12-23', 'sskennepohl@gmail.com', NULL, 'John', '54084012', 'FIL', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Beiswanger', 'Female', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sszczepa@mail.umw.edu', '2023-09-10', 'Sofia', 'Szczepankiewicz', '1301 college ave', 'Fredericksburg', 'VA', '22401', '7575599034', NULL, 'cellphone', NULL, NULL, '2004-09-09', 'sszczepa@mail.umw.edu', NULL, 'Claire', '7579852782', 'Roomate', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Bergren', 'Female', 'M', 'yes', 'no', 'no', 'Teamwork, Cooperation, Customer Service, Leadership, Problem solving, Scholarship, Management, Patience, Conflict resolution, Attention to detail, Empathy, First aid.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('stmanfre@gmail.com', '2023-09-14', 'Shane', 'Manfre', '24258 Oak Meadow Lane', 'Fredericksburg', 'VA', '22407', '5404554227', NULL, 'cellphone', NULL, NULL, '2018-01-18', 'stmanfre@gmail.com', NULL, 'Jennifer', '5404797047', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Manfre', 'Male', 'XL', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('stuff8458@gmail.com', '2023-09-16', 'Dawn Elizabeth', 'Schwarting', '10 Countryside Dr', 'FREDERICKSBRG', 'VA', '22406', '4349816577', NULL, 'cellphone', NULL, NULL, '1973-02-27', 'stuff8458@gmail.com', NULL, 'Aurie', '4349819577', 'Husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Schwarting', 'Female', 'M', 'yes', 'yes', 'yes', 'I am a Project Manager proficient in project management methodologies including Agile and Scrum. I am fairly good with a lot of computer-based organization such as spreadsheets, Canva, and social media.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('sullivan.angiek@gmail.com', '2023-05-07', 'Angie', 'Sullivan', '11106 Parkview Drive', 'Fredericksburg', 'VA', '22408', '5408402007', NULL, 'cellphone', NULL, NULL, '1971-07-14', 'sullivan.angiek@gmail.com', NULL, 'Eric', '5408425482', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Sullivan', 'Female', 'M', 'yes', 'yes', 'yes', 'Availability varies, days and times selected are an example', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tamiarachellegilliard@gmail.com', '2025-12-10', 'Tamia', 'Gilliard', '104 Antietam Dr', 'Locust Grove', 'VA', '22508', '2022714724', NULL, 'cellphone', NULL, NULL, '1983-11-06', 'tamiarachellegilliard@gmail.com', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Female', 'M', 'yes', 'no', 'yes', 'I\'ve been an American Red Cross CPR/AED trainer for 10 years.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tammyinva72@gmail.com', '2025-02-10', 'Tammy', 'Freeman', '3100 Murrells Way Apt B-02', 'Fredericksburg', 'VA', '22401', '4433565375', NULL, 'cellphone', NULL, NULL, '1972-04-15', 'tammyinva72@gmail.com', NULL, 'Kimberly', '44365589', 'Sister', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Wolf', 'Female', 'XXL', 'yes', 'yes', 'yes', 'Organization, customer service. Professional office skills', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tbanks@healthygenerations.org', '2024-11-12', 'TaMara', 'Banks', '460 Lendall Ln', 'Fredericksburg', 'VA', '22405', '5404199638', NULL, 'work', NULL, NULL, '1972-08-28', 'tbanks@healthygenerations.org', NULL, 'Chandler', '54049807', 'Daughter', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Hines', 'Female', 'XXL', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tcstabisz@gmail.com', '2023-08-28', 'Chelsea', 'Tabisz', '3 Masters Dr', 'Stafford', 'VA', '22554', '6185306076', NULL, 'cellphone', NULL, NULL, '1984-07-20', 'tcstabisz@gmail.com', NULL, 'Shelley', '7035981892', 'Mom', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Waite', 'Female', 'S', 'yes', 'no', 'yes', 'Singing, people skills, love of GGF ????', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test_acc', '2025-04-29', 'test', 'test', 'test', 'test', 'VA', '22405', '5555555555', NULL, 'cellphone', '5555555555', 'cellphone', '2003-03-03', 'test@gmail.com', NULL, 'test', 'n/a', 't', NULL, 'volunteer', 'Active', NULL, '$2y$10$kpVA41EXvoJyv896uDBEF.fHCPmSlkVSaXjHojBl7DqbRnEm//kxy', NULL, NULL, 0, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test_person', '2025-10-26', 'Testina', 'Tester', NULL, 'Testville', 'VA', NULL, '5555555555', 'true', 'mobile', NULL, NULL, '1980-08-18', 'testing@gmail.com', 'false', NULL, 'n/a', NULL, NULL, 'volunteer', NULL, NULL, '$2y$10$blAQaBgCChBv5qRtBFVVAe1m6gIfwPf/wJ8HxzLFTYiY3aWpvaW8e', 'civilian', 'Army', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test_persona', '2025-10-28', 'Testana', 'Tester', NULL, 'Testinaville', 'VA', NULL, '5555555555', 'true', NULL, NULL, NULL, NULL, 'testerana@gmail.com', 'true', NULL, 'n/a', NULL, NULL, 'volunteer', NULL, NULL, '$2y$10$s90qlNAJE9EbgLhZbhG5vO4IGSM.PIbK3Ve9IvpfoicMwXbFEXQFi', 'active', 'air_force', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer1.com', '2025-02-10', 'Alex', 'Johnson', '456 Oak Ave', 'Fredericksburg', 'VA', '22401', '5405559876', NULL, 'cellphone', '5405551111', NULL, '1995-03-15', 'test@volunteer1.com', NULL, 'Sarah', 'n/a', 'Mother', NULL, 'volunteer', 'Active', NULL, '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Johnson', 'Male', 'L', 'yes', 'yes', 'yes', NULL, NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer11.com', '2025-01-10', 'Alice', 'Johnson', '100 Oak Street', 'Fredericksburg', 'VA', '22401', '5405551011', NULL, 'cellphone', NULL, NULL, '1990-03-15', 'test@volunteer11.com', NULL, 'Bob', '5405551012', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Johnson', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer12.com', '2025-02-14', 'Brian', 'Williams', '200 Maple Ave', 'Stafford', 'VA', '22554', '5405551013', NULL, 'cellphone', NULL, NULL, '1985-07-22', 'test@volunteer12.com', NULL, 'Sarah', '5405551014', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Williams', 'Male', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer13.com', '2025-03-05', 'Carmen', 'Garcia', '300 Pine Road', 'Spotsylvania', 'VA', '22551', '5405551015', NULL, 'cellphone', NULL, NULL, '1992-11-08', 'test@volunteer13.com', NULL, 'Luis', '5405551016', 'Brother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Garcia', 'Female', 'L', 'no', 'yes', 'yes', 'Bilingual Spanish', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer14.com', '2025-04-18', 'David', 'Lee', '400 Elm Court', 'Fredericksburg', 'VA', '22405', '5405551017', NULL, 'cellphone', NULL, NULL, '1988-01-30', 'test@volunteer14.com', NULL, 'Amy', '5405551018', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lee', 'Male', 'XL', 'yes', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer15.com', '2025-05-22', 'Emily', 'Brown', '500 Cedar Lane', 'King George', 'VA', '22485', '5405551019', NULL, 'cellphone', NULL, NULL, '1995-06-12', 'test@volunteer15.com', NULL, 'Mike', '5405551020', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Brown', 'Female', 'S', 'yes', 'yes', 'yes', 'CPR certified', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer16.com', '2025-06-10', 'Frank', 'Davis', '600 Birch Drive', 'Fredericksburg', 'VA', '22407', '5405551021', NULL, 'cellphone', NULL, NULL, '1978-09-25', 'test@volunteer16.com', NULL, 'Linda', '5405551022', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Davis', 'Male', 'XXL', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer17.com', '2025-07-03', 'Grace', 'Martinez', '700 Walnut Street', 'Stafford', 'VA', '22554', '5405551023', NULL, 'cellphone', NULL, NULL, '2000-04-17', 'test@volunteer17.com', NULL, 'Rosa', '5405551024', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Martinez', 'Female', 'M', 'yes', 'no', 'yes', 'Event planning', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer18.com', '2025-08-15', 'Henry', 'Taylor', '800 Spruce Ave', 'Fredericksburg', 'VA', '22401', '5405551025', NULL, 'home', NULL, NULL, '1982-12-03', 'test@volunteer18.com', NULL, 'Karen', '5405551026', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Taylor', 'Male', 'L', 'no', 'yes', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer19.com', '2025-09-20', 'Isabel', 'Anderson', '900 Hickory Blvd', 'Spotsylvania', 'VA', '22551', '5405551027', NULL, 'cellphone', NULL, NULL, '1997-02-28', 'test@volunteer19.com', NULL, 'Tom', '5405551028', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Anderson', 'Female', 'XL', 'yes', 'yes', 'yes', 'First aid training', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer20.com', '2025-10-05', 'Jack', 'Wilson', '1000 Aspen Way', 'Fredericksburg', 'VA', '22408', '5405551029', NULL, 'cellphone', NULL, NULL, '1993-08-14', 'test@volunteer20.com', NULL, 'Diane', '5405551030', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Wilson', 'Male', 'M', 'yes', 'no', 'yes', 'Customer service', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tester4', '2025-12-01', 'tester', 'testing', NULL, 'Fredericksburg', 'VA', NULL, '5405405405', 'true', '', '', '', '', 'tester@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$nILE/qxdpSvIgROc1uQEV.MyflEdG0IuNLQQ1c1u54MSEYKlg2LC2', 'Active duty', 'Space Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('testing123', '2025-10-26', 'Test', 'User', NULL, 'City', 'VA', NULL, '', 'true', NULL, NULL, NULL, NULL, 'example@email.com', 'true', NULL, 'n/a', NULL, NULL, 'volunteer', NULL, NULL, '$2y$10$XbXkJUMSAGo9m1/GZQ3faebtJWbPMZYm/AeTA3jpDCaxZBNnMclxC', 'civ', 'marine_corp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('testytesty', '2026-03-08', 'hieric', 'mcgowan', NULL, '22', 'AK', NULL, '5408426399', NULL, 'cellphone', '5408426399', 'cellphone', '2026-03-07', 'q@gmail.com', 'true', '1', '', '11', '', 'volunteer', '', '', '$2y$10$IdejuUFgJuawe9ZVcIuRhePQXViN.wQv05WVIZYy3pLIfkuZ9TSAy', '', '', NULL, '1', 'Other', 'XXL', 'yes', 'yes', 'yes', 'q', 'q', 'yes', 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('texascampos@yahoo.com', '2023-07-07', 'Jennifer', 'CHESTNUT', '176 ellington drive', 'Fredericksburg', 'VA', '22405', '5406451098', NULL, 'cellphone', NULL, NULL, '1975-09-03', 'texascampos@yahoo.com', NULL, 'Philip', '5406450353', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Chestnut', 'Female', 'XL', 'no', 'no', 'yes', 'Retired flight attendant...I can deal with a lot of situations  ????', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('thehines@gmail.com', '2023-08-01', 'Stephanie', 'Hine', '315 Cooper Street', 'Spotsylvania', 'VA', '22551', '5402204104', NULL, 'cellphone', NULL, NULL, '1980-03-20', 'thehines@gmail.com', NULL, 'Lyman', '5404196188', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Hine', 'Female', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tiffany@gwynethsgift.org', '2024-11-05', 'Tiffany', 'Steel', '1900 Charles Street', 'Fredericksburg', 'VA', '22401', '7033819995', NULL, 'work', NULL, NULL, '1996-06-27', 'tiffany@gwynethsgift.org', NULL, 'Brandon', '80457272', 'Fiance', NULL, 'superadmin', 'Active', 'Administrative', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Sisco', 'Female', 'L', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tjax999@gmail.com', '2024-11-18', 'tina', 'jackson', '812 college avenue', 'fredericksburg', 'VA', '22401', '5402957639', NULL, 'cellphone', NULL, NULL, '1965-09-09', 'tjax999@gmail.com', NULL, 'dewey', '54062371', 'spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'reynolds', 'Female', 'S', 'no', 'yes', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tjwalding1988@yahoo.com', '2024-02-16', 'TJ', 'Walding', '18 Vanburgh Court', 'Stafford', 'VA', '22554', '5407602232', NULL, 'cellphone', NULL, NULL, '1965-11-05', 'tjwalding1988@yahoo.com', NULL, 'Kent', '54076022', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Walding', 'Female', 'M', 'yes', 'yes', 'yes', 'I’m an accountant.  But a good fundraiser.  I’m outgoing.  I can work sign in tables, work a room like nobody’s business.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tmiller267@gmail.com', '2025-09-22', 'Tiffany', 'Miller', '7109 Finch lane', 'Fredericksburg', 'VA', '22407', '5409036370', NULL, 'cellphone', NULL, NULL, '1991-01-23', 'tmiller267@gmail.com', NULL, 'Kendra', '54064515', 'Sister', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Edwards', 'Female', 'M', 'yes', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tmwhite0466@gmail.com', '2025-08-21', 'Tamara', 'White', '6604 Broad Creek Overlook', 'Fredericksburg', 'VA', '22407', '5408509002', NULL, 'cellphone', NULL, NULL, '2001-06-29', 'tmwhite0466@gmail.com', NULL, 'Rick', '54020705', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'White', 'Female', 'XL', 'yes', 'yes', 'yes', 'Communication skills\n\nExperience working with children and youth', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('toaster', '2025-12-08', 'toast', 'er', NULL, 'Fredericksburg', 'VA', NULL, '5405405405', 'true', '', '', '', '', 'toaster@gmail.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$VzLJcSjn/WFh0jeI9iFAw.McczukN4ovZuzg9vgtKFlXL3i/O9oOq', 'Civilian', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('todd@storagecollections.com', '2024-06-03', 'Todd', 'Sheffer', '1104 College Avenue', 'Fredericksburg', 'VA', '22401', '8139921031', NULL, 'cellphone', NULL, NULL, '1961-01-02', 'todd@storagecollections.com', NULL, 'Patricia', '81352812', 'Wife', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Morris', 'Male', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tsamuels2676@gmail.com', '2024-11-21', 'Teresa', 'Samuels', '10917 Taney Dr', 'Fredericksburg', 'VA', '22407', '5409192212', NULL, 'cellphone', NULL, NULL, '1976-08-26', 'tsamuels2676@gmail.com', NULL, 'Estelle', '5406563140', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Samuels', 'Female', 'L', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('turkeybird123', '2026-03-15', 'Turkey', 'Bird', '33 Oven Court', 'Flocking City', 'VA', '98765', '3333333333', NULL, 'cellphone', '3303303330', 'home', '2020-03-12', 'turkeythebird@email.com', 'true', 'Dad', '', 'Father', '', 'board_member', 'Active', 'TURKEYYYYY', '$2y$10$go2TVlMDVu3VzSXFXqKiT.NWg1HPQ2nZtE3iHeP1xlTaYhO6hinIG', '', '', NULL, 'Bird', 'Female', 'S', 'no', 'no', 'yes', 'Having fun, being an ass', 'Culinary, taster', 'yes', 0.00, 0, 'images/profile_pics/pfp_turkeybird123_1775357190.png', 'no', 'no', 'no', ''),
('unayza.aziz@icloud.com', '2025-02-21', 'unayza', 'aziz', '19 Brentsmill Dr', 'Stafford', 'VA', '22554', '7038351517', NULL, 'cellphone', NULL, NULL, '2008-09-08', 'unayza.aziz@icloud.com', NULL, 'aziz', '20236113', 'father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'ahmed', 'Female', 'S', 'no', 'no', 'yes', 'I am able to speak a moderate amount of Urdu, am a fast learner and love learning new things. I also enjoy being able to help others.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vanessa.dover@verizon.net', '2025-06-09', 'Vanessa', 'Dover', '15 Ridge Hollow Drive', 'Fredericksburg', 'VA', '22405', '5407103044', NULL, 'cellphone', NULL, NULL, '1972-09-29', 'vanessa.dover@verizon.net', NULL, 'Tom', '54062147', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Dover', 'Female', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vanessashantel2026@gmail.com', '2024-11-30', 'Vanessa', 'Samuels', 'Taney Dr & Taney Dr', 'Fredericksburg', 'VA', '22407', '5409192219', NULL, 'cellphone', NULL, NULL, '2007-11-06', 'vanessashantel2026@gmail.com', NULL, 'Teresa', '54091922', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Samuels', 'Female', 'XXL', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vencesdb@gmail.com', '2025-12-21', 'Daniel', 'Vences', '12 Ridge Rd', 'Stafford', 'VA', '22556', '5715441155', NULL, 'cellphone', NULL, NULL, '2008-11-13', 'vencesdb@gmail.com', NULL, 'Cristian', '70381451', 'Brother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Davila', 'Male', 'XL', 'yes', 'yes', 'yes', 'fluent in spanish', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('veosss@gmail.com', '2023-06-08', 'John', 'Tokar', '1020 Hillcrest Terrace', 'Fredericksburg', 'VA', '22405', '5408464174', NULL, 'cellphone', NULL, NULL, '1947-10-14', 'veosss@gmail.com', NULL, 'Mary', '5407103604', 'Daughter', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Lipscomb', 'Male', 'L', 'no', 'no', 'no', 'Barbara Tokar, spouse will also volunteer. Birthdate:  03/12/1947.  Phone number:  540-538-2893', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('veronica@gwynethsgift.org', '2023-05-01', 'Veronica', 'Gutierrez', '122 Lake Shore Drive', 'Los Angeles', 'VA', '22405', '5624002637', NULL, 'cellphone', NULL, NULL, '1978-03-26', 'veronica@gwynethsgift.org', NULL, 'John', '5624002650', 'Husband', NULL, 'superadmin', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Gutierrez', 'Female', 'S', 'yes', 'yes', 'yes', 'Spanish Fluency, Advanced Computer Skills, event planning.', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('victoriaannmt@gmail.com', '2024-04-24', 'victoria', 'crolet', '11451 james madison pkwy', 'king george', 'VA', '22485', '5406043505', NULL, 'cellphone', NULL, NULL, '1989-04-20', 'victoriaannmt@gmail.com', NULL, 'julien', '54083460', 'husband', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'crolet', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vmsroot', 'N/A', 'vmsroot', '', NULL, 'N/A', 'VA', NULL, '', NULL, 'N/A', '', '', 'N/A', 'vmsroot', 'false', '', 'N/A', '', '', 'admin', 'N/A', 'N/A', '$2y$10$DokO.38InJwE5SoMOtL1kuw8HhBXq.mNX3/RLLv2rTQL5LH1Pq15.', '', '', NULL, '', '', '', 'no', 'no', 'no', '', '', 'no', 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vmsroot@gmail.com', '2024-04-24', 'admin', 'admin', '111 admin st', 'admin', 'VA', '11111', '5555555555', NULL, 'cellphone', NULL, NULL, '2024-04-24', 'vmsroot@gmail.com', NULL, '', '', '', NULL, 'superadmin', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Other', 'S', 'no', 'no', 'no', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_alice', '2025-10-05', 'Alice', 'Johnson', NULL, NULL, 'VA', '22401', '5551110001', NULL, NULL, NULL, NULL, NULL, 'alice@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_bob', '2025-10-12', 'Bob', 'Martinez', NULL, NULL, 'VA', '22401', '5551110002', NULL, NULL, NULL, NULL, NULL, 'bob@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15.50, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_carol', '2025-11-01', 'Carol', 'Davis', NULL, NULL, 'VA', '22402', '5551110003', NULL, NULL, NULL, NULL, NULL, 'carol@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_dan', '2025-11-20', 'Dan', 'Wilson', NULL, NULL, 'VA', '22402', '5551110004', NULL, NULL, NULL, NULL, NULL, 'dan@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.50, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_emma', '2025-12-10', 'Emma', 'Brown', NULL, NULL, 'VA', '22401', '5551110005', NULL, NULL, NULL, NULL, NULL, 'emma@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15.50, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_frank', '2026-01-08', 'Frank', 'Taylor', NULL, NULL, 'VA', '22403', '5551110006', NULL, NULL, NULL, NULL, NULL, 'frank@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12.50, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_grace', '2026-01-22', 'Grace', 'Anderson', NULL, NULL, 'VA', '22401', '5551110007', NULL, NULL, NULL, NULL, NULL, 'grace@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13.50, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_hank', '2026-02-14', 'Hank', 'Thomas', NULL, NULL, 'VA', '22402', '5551110008', NULL, NULL, NULL, NULL, NULL, 'hank@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_iris', '2026-03-01', 'Iris', 'Jackson', NULL, NULL, 'VA', '22403', '5551110009', NULL, NULL, NULL, NULL, NULL, 'iris@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_jack', '2026-03-20', 'Jack', 'White', NULL, NULL, 'VA', '22401', '5551110010', NULL, NULL, NULL, NULL, NULL, 'jack@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Active', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4.50, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_karen', '2025-10-15', 'Karen', 'Lee', NULL, NULL, 'VA', '22401', '5551110011', NULL, NULL, NULL, NULL, NULL, 'karen@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Inactive', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_leo', '2025-11-05', 'Leo', 'Harris', NULL, NULL, 'VA', '22402', '5551110012', NULL, NULL, NULL, NULL, NULL, 'leo@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Inactive', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('vol_mia', '2025-12-20', 'Mia', 'Clark', NULL, NULL, 'VA', '22403', '5551110013', NULL, NULL, NULL, NULL, NULL, 'mia@test.com', NULL, NULL, 'n/a', NULL, NULL, 'volunteer', 'Inactive', NULL, '$2y$10$DUMMY', NULL, NULL, 0, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.50, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Volunteer25', '2025-04-30', 'Volley', 'McTear', '123 Dog St', 'Dogville', 'VA', '56748', '9887765543', NULL, 'home', '6565651122', 'home', '2025-04-29', 'volly@gmail.com', NULL, 'Holly', 'n/a', 'Besty', NULL, 'volunteer', 'Active', NULL, '$2y$10$45gKdbjW78pNKX/5ROtb7eU9OykSCsP/QCyTAvqBtord4J7V3Ywga', NULL, NULL, 0, 'McTear', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('waldmanassociates@hotmail.com', '2024-11-14', 'Barry', 'Waldman', '1300 Thornton Street', 'Fredericksburg', 'VA', '22401', '5402076363', NULL, 'cellphone', NULL, NULL, '1973-04-25', 'waldmanassociates@hotmail.com', NULL, 'Nancy', '54041250', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Gendel', 'Male', 'XL', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('waldmanlex12@gmail.com', '2024-11-14', 'Alexander', 'Waldman', '8120 Harrison Dr', 'King George', 'VA', '22485', '5408508772', NULL, 'cellphone', NULL, NULL, '2007-08-31', 'waldmanlex12@gmail.com', NULL, 'Barry', '54020763', 'Father', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Waldman', 'Male', 'L', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('wangkat18@gmail.com', '2023-05-30', 'Katherine', 'Wang', '8 Colemans mill drive', 'Fredericksburg', 'VA', '22405', '5716453939', NULL, 'cellphone', NULL, NULL, '2004-12-27', 'wangkat18@gmail.com', NULL, 'Jeanne', '5718882636', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Chou', 'Female', 'S', 'yes', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Welp', '2025-12-04', 'Jake', 'Lipinski', NULL, 'Apple', 'VA', NULL, '7577903325', 'true', '', '', '', '', 'mcdonalds@happymeal.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$LvWD62DJ6pwlVGnWenQkneWCFINzgbHgzyvaBdiLn72/WwM4wo7Iy', 'Active duty', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('willajsm@aol.com', '2025-03-23', 'Erin', 'Plossl', '1312 Hudgins Farm Circle', 'Fredericksburg', 'VA', '22408', '5409407690', NULL, 'cellphone', NULL, NULL, '1977-08-07', 'willajsm@aol.com', NULL, 'John', '54032292', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Plossl', 'Female', 'L', 'no', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('willajsmom@aol.com', '2025-04-10', 'Erin', 'Plossl', '1312 Hudgins Farm Circle', 'Fredericksburg', 'VA', '22408', '5409407690', NULL, 'cellphone', NULL, NULL, '1977-08-20', 'willajsmom@aol.com', NULL, 'John', '54032292', 'Spouse', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Plossl', 'Female', 'L', 'no', 'yes', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('william.stremer23@gmail.com', '2025-09-28', 'Jennifer', 'Arevalo', '240 Overlook Ct', 'Falmouth', 'VA', '22405', '5715647518', NULL, 'cellphone', NULL, NULL, '2011-08-11', 'william.stremer23@gmail.com', NULL, 'Lisbeth', '70356567', 'Mother', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'bernal', 'Female', 'M', 'no', 'no', 'yes', '', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('zmachuga15@elmira.edu', '2025-05-01', 'Zac', 'Machuga', '3120 crossroads station blvd', 'Fredericksburg', 'VA', '22408', '7124710953', NULL, 'cellphone', NULL, NULL, '1993-09-07', 'zmachuga15@elmira.edu', NULL, '', '', '', NULL, 'volunteer', 'Active', '', '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, '', 'Male', 'XXL', 'yes', 'no', 'yes', 'Food preparation and service', NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dbscheduledemails`
--

CREATE TABLE `dbscheduledemails` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL DEFAULT 0,
  `userID` varchar(256) NOT NULL,
  `recipientID` varchar(256) NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `scheduledSend` date NOT NULL,
  `sent` tinyint(1) DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbscheduledemails`
--

INSERT INTO `dbscheduledemails` (`id`, `event_id`, `userID`, `recipientID`, `subject`, `body`, `scheduledSend`, `sent`, `created`) VALUES
(117, 9011, 'blueydingo123', 'blueydingo123', 'Gwyneth\'s Gift Event Reminder: Test Today', 'This is a reminder to attend  Test Today from 7:00 AM to 8:00 AM today!', '2026-04-27', 0, '2026-04-26 17:21:59'),
(118, 9011, 'janedoe1', 'janedoe1', 'Gwyneth\'s Gift Event Reminder: Test Today', 'This is a reminder to attend  Test Today from 7:00 AM to 8:00 AM today!', '2026-04-27', 0, '2026-04-26 17:22:38'),
(119, 9011, 'blueydingo123', 'blueydingo123', 'Gwyneth\'s Gift Event Reminder: Test Today', 'This is a reminder to attend  Test Today from 7:00 AM to 8:00 AM today!', '2026-04-27', 0, '2026-04-26 17:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `dbshifts`
--

CREATE TABLE `dbshifts` (
  `shift_id` int(11) NOT NULL,
  `person_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time DEFAULT NULL,
  `totalHours` decimal(5,2) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbshifts`
--

INSERT INTO `dbshifts` (`shift_id`, `person_id`, `date`, `startTime`, `endTime`, `totalHours`, `description`) VALUES
(14, 'maddiev', '2025-04-29', '20:22:29', '00:30:40', 0.13, 'a'),
(15, 'ameyer3', '2025-04-29', '20:24:27', '00:30:36', 0.10, 'a'),
(16, 'jane_doe', '2025-04-29', '20:26:29', '00:30:40', 0.07, 'a'),
(17, 'ameyer3', '2025-04-29', '20:31:30', '00:32:09', 0.00, 'a'),
(18, 'jane_doe', '2025-04-29', '20:31:31', '00:32:09', 0.00, 'a'),
(19, 'ameyer3', '2025-04-29', '20:32:14', '00:32:39', 0.00, 'hello'),
(20, 'ameyer3', '2025-04-29', '21:25:49', '01:26:17', 0.00, 'hello'),
(21, 'ameyer32', '2025-04-29', '21:35:01', '01:35:25', 0.00, 'hello'),
(22, 'ameyer123', '2025-04-29', '21:48:53', '01:49:13', 0.00, 'hello'),
(23, 'ameyer3', '2025-04-29', '21:56:37', '01:56:54', 0.00, 'hello'),
(24, 'ameyer3', '2025-04-29', '22:03:00', '02:03:18', 0.00, 'hello'),
(25, 'michellevb', '2025-04-29', '22:08:04', '02:08:36', 0.00, 'yay'),
(26, 'ameyer3', '2025-04-29', '22:24:27', '02:24:43', 0.00, 'hello'),
(27, 'test_acc', '2025-04-29', '23:44:58', '23:45:40', -23.99, 'test'),
(28, 'BobVolunteer', '2025-04-30', '08:14:55', '12:15:09', 0.00, 'good job'),
(29, 'BobVolunteer', '2025-04-30', '08:15:29', NULL, NULL, NULL),
(30, 'Volunteer25', '2025-04-30', '10:21:39', '14:22:09', 0.00, 'test'),
(31, 'ameyer3', '2025-05-01', '11:37:23', '15:37:49', 0.00, 'hello'),
(32, 'lukeg', '2025-07-09', '10:57:46', '10:57:57', 0.00, 'Laundry'),
(33, 'lukeg', '2025-07-09', '11:04:46', NULL, NULL, NULL),
(34, 'vmsroot', '2025-09-10', '11:36:05', NULL, NULL, NULL),
(35, 'vol_alice', '2025-10-18', '09:00:00', '13:00:00', 4.00, 'Fall Cleanup Day'),
(36, 'vol_bob', '2025-10-18', '09:00:00', '12:30:00', 3.50, 'Fall Cleanup Day'),
(37, 'vol_carol', '2025-10-18', '09:30:00', '13:00:00', 3.50, 'Fall Cleanup Day'),
(38, 'vol_emma', '2025-10-18', '09:00:00', '11:00:00', 2.00, 'Fall Cleanup Day'),
(39, 'vol_karen', '2025-10-18', '09:00:00', '13:00:00', 4.00, 'Fall Cleanup Day'),
(40, 'vol_mia', '2025-10-18', '10:00:00', '13:00:00', 3.00, 'Fall Cleanup Day'),
(41, 'vol_alice', '2025-12-06', '10:00:00', '15:00:00', 5.00, 'Holiday Gift Wrapping'),
(42, 'vol_bob', '2025-12-06', '10:00:00', '13:00:00', 3.00, 'Holiday Gift Wrapping'),
(43, 'vol_emma', '2025-12-06', '10:00:00', '14:00:00', 4.00, 'Holiday Gift Wrapping'),
(44, 'vol_frank', '2025-12-06', '11:00:00', '15:00:00', 4.00, 'Holiday Gift Wrapping'),
(45, 'vol_mia', '2025-12-06', '10:00:00', '12:30:00', 2.50, 'Holiday Gift Wrapping'),
(46, 'vol_alice', '2026-01-17', '08:00:00', '12:00:00', 4.00, 'Winter Food Drive'),
(47, 'vol_bob', '2026-01-17', '08:00:00', '12:00:00', 4.00, 'Winter Food Drive'),
(48, 'vol_carol', '2026-01-17', '08:00:00', '11:00:00', 3.00, 'Winter Food Drive'),
(49, 'vol_dan', '2026-01-17', '08:00:00', '10:30:00', 2.50, 'Winter Food Drive'),
(50, 'vol_emma', '2026-01-17', '08:00:00', '12:00:00', 4.00, 'Winter Food Drive'),
(51, 'vol_frank', '2026-01-17', '08:30:00', '12:00:00', 3.50, 'Winter Food Drive'),
(52, 'vol_grace', '2026-01-17', '09:00:00', '12:00:00', 3.00, 'Winter Food Drive'),
(53, 'vol_alice', '2026-02-08', '13:00:00', '16:00:00', 3.00, 'Valentine Card Making'),
(54, 'vol_carol', '2026-02-08', '13:00:00', '15:30:00', 2.50, 'Valentine Card Making'),
(55, 'vol_emma', '2026-02-08', '13:00:00', '16:00:00', 3.00, 'Valentine Card Making'),
(56, 'vol_grace', '2026-02-08', '13:30:00', '16:00:00', 2.50, 'Valentine Card Making'),
(57, 'vol_hank', '2026-02-08', '13:00:00', '15:00:00', 2.00, 'Valentine Card Making'),
(58, 'vol_alice', '2026-03-14', '08:00:00', '14:00:00', 6.00, 'Spring Trail Restoration'),
(59, 'vol_bob', '2026-03-14', '08:00:00', '13:00:00', 5.00, 'Spring Trail Restoration'),
(60, 'vol_carol', '2026-03-14', '08:00:00', '12:00:00', 4.00, 'Spring Trail Restoration'),
(61, 'vol_dan', '2026-03-14', '08:00:00', '11:00:00', 3.00, 'Spring Trail Restoration'),
(62, 'vol_frank', '2026-03-14', '09:00:00', '14:00:00', 5.00, 'Spring Trail Restoration'),
(63, 'vol_grace', '2026-03-14', '08:00:00', '13:00:00', 5.00, 'Spring Trail Restoration'),
(64, 'vol_iris', '2026-03-14', '08:00:00', '14:00:00', 6.00, 'Spring Trail Restoration'),
(65, 'vol_jack', '2026-03-14', '08:00:00', '12:30:00', 4.50, 'Spring Trail Restoration'),
(66, 'vol_alice', '2026-03-28', '15:00:00', '18:00:00', 3.00, 'Literacy Tutoring'),
(67, 'vol_emma', '2026-03-28', '15:00:00', '17:30:00', 2.50, 'Literacy Tutoring'),
(68, 'vol_grace', '2026-03-28', '15:00:00', '18:00:00', 3.00, 'Literacy Tutoring'),
(69, 'vol_iris', '2026-03-28', '15:00:00', '17:00:00', 2.00, 'Literacy Tutoring');

-- --------------------------------------------------------

--
-- Table structure for table `dbsuggestions`
--

CREATE TABLE `dbsuggestions` (
  `id` int(11) NOT NULL,
  `user_id` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `dbsuggestions`
--

INSERT INTO `dbsuggestions` (`id`, `user_id`, `title`, `body`, `created_at`) VALUES
(1, 'edarnell', 'TEST SUGGESTION', 'This suggestion is a test', '2025-12-08 15:25:28'),
(2, 'edarnell', 'Suggestion here', 'SUGGESTING THIS', '2025-12-09 10:39:51'),
(3, 'edarnell', 'Suggestion REAL', 'This is a good idea', '2025-12-09 10:41:14'),
(4, 'edarnell', 'THIS is the REAL suggestion', 'Suggesting some really cool things', '2025-12-09 10:49:55'),
(5, 'vmsroot', 'This is a test for styling', 'This is a styling test.', '2025-12-09 14:48:27'),
(6, 'vmsroot', 'This is a test for styling', 'This is a styling test.', '2025-12-09 14:48:45'),
(7, 'vmsroot', 'This is a test for styling', 'This is a styling test.', '2025-12-09 14:48:50'),
(8, 'fakename', 'AAAAA', 'mAKE THIS WORK', '2025-12-10 11:43:42'),
(9, 'fakename', 'A suggestion asefs', 'sasf', '2025-12-10 13:25:18'),
(10, 'edarnell', 'Test Suggestion 12-10', 'TEST SUGGESTION BODY TEXT HERE', '2025-12-10 19:41:56');

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
(4, '249', 'Test Doc #1', 'PDF training material', 'TEST DOC pdf.pdf', 'training_docs/1773532503_TEST DOC pdf.pdf', 'application/pdf', 'vmsroot', '2026-03-14 19:55:03', 1),
(5, '249', 'Test Doc #2', 'docx training material', 'TEST DOC docx.docx', 'training_docs/1773532538_TEST DOC docx.docx', 'application/vnd.openxmlformats-officedocument.word', 'vmsroot', '2026-03-14 19:55:38', 1),
(6, '249', 'Test Doc #3', 'txt training document', 'TEST DOC txt.txt', 'training_docs/1773532575_TEST DOC txt.txt', 'text/plain', 'vmsroot', '2026-03-14 19:56:15', 1),
(7, '249', 'Test Doc #4', 'Powerpoint training document', 'TEST PRES pptx.pptx', 'training_docs/1773532607_TEST PRES pptx.pptx', 'application/vnd.openxmlformats-officedocument.pres', 'vmsroot', '2026-03-14 19:56:47', 1),
(9, '379', 'SAMPLE TXT', '', 'z_animals.txt', 'training_docs/1774057836_z_animals.txt', 'text/plain', 'vmsroot', '2026-03-20 21:50:36', 1),
(10, '587', 'JUST A TXT', '', 'z_animals.txt', 'training_docs/1774061873_z_animals.txt', 'text/plain', 'vmsroot', '2026-03-20 22:57:53', 1),
(11, '587', 'JUST ANOTHER TXT', '', 'z_words.txt', 'training_docs/1774061886_z_words.txt', 'text/plain', 'vmsroot', '2026-03-20 22:58:06', 1),
(12, '657', 'Apostrophe Notes', '', 'z_words.txt', 'training_docs/1774307892_z_words.txt', 'text/plain', 'vmsroot', '2026-03-23 19:18:12', 1),
(13, '657', 'More Apostrophe Notes', '', 'z_animals.txt', 'training_docs/1774307904_z_animals.txt', 'text/plain', 'vmsroot', '2026-03-23 19:18:24', 1),
(14, '659', 'Test Event Materials', '', 'z_animals.txt', 'training_docs/1774307941_z_animals.txt', 'text/plain', 'vmsroot', '2026-03-23 19:19:01', 1),
(15, '429', 'Custom Event Materials', '', 'z_words.txt', 'training_docs/1774307955_z_words.txt', 'text/plain', 'vmsroot', '2026-03-23 19:19:15', 1),
(16, '674', 'Test Materials', '', 'z_words.txt', 'training_docs/1774307970_z_words.txt', 'text/plain', 'vmsroot', '2026-03-23 19:19:30', 1),
(17, '661', 'Weekly Materials', '', 'z_animals.txt', 'training_docs/1774307987_z_animals.txt', 'text/plain', 'vmsroot', '2026-03-23 19:19:47', 1),
(18, '587', 'Stat Notes', '', 'DATA_352_stat_tests.pdf', 'training_docs/1774439650_DATA_352_stat_tests.pdf', 'application/pdf', 'vmsroot', '2026-03-25 07:54:10', 1),
(19, '587', 'Zhang Paper', '', 'zhang_paper.pdf', 'training_docs/1774439708_zhang_paper.pdf', 'application/pdf', 'vmsroot', '2026-03-25 07:55:08', 1),
(20, '587', 'HMM TXT', '', 'nums.txt', 'training_docs/1774439742_nums.txt', 'text/plain', 'vmsroot', '2026-03-25 07:55:42', 1),
(21, '744', 'Test Material', '', 'README.txt', 'training_docs/1775059467_README.txt', 'text/plain', 'turkeybird123', '2026-04-01 12:04:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_replies`
--

CREATE TABLE `discussion_replies` (
  `reply_id` int(11) NOT NULL,
  `user_reply_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discussion_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_reply_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) DEFAULT 'general',
  `edited_by` varchar(256) DEFAULT NULL,
  `edited_at` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discussion_replies`
--

INSERT INTO `discussion_replies` (`reply_id`, `user_reply_id`, `author_id`, `discussion_title`, `reply_body`, `parent_reply_id`, `created_at`, `category`, `edited_by`, `edited_at`) VALUES
(12, 'Volunteer25', 'Volunteer25', 'test', 'great idea!', '9', '2025-04-30-10:24', 'general', NULL, NULL),
(13, 'vmsroot', 'vmsroot', 'test', 'test', NULL, '2025-05-01-11:31', 'general', NULL, NULL),
(14, 'ameyer3', 'ameyer3', 'test', 'hello. Edited by vmsroot', '13', '2025-05-01-11:38', 'general', 'vmsroot', '2026-03-20-20:28'),
(15, 'ameyer3', 'vmsroot', 'test', 'hello', NULL, '2025-05-01-11:38', 'general', NULL, NULL),
(16, 'vmsroot', 'vmsroot', 'test', 'testt', NULL, '2025-09-10-11:40', 'general', NULL, NULL),
(23, 'blueydingo123', 'blueydingo123', 'test', 'hello again but edited', '13', '2026-03-19-23:17', 'general', 'blueydingo123', '2026-03-20-20:18'),
(24, 'blueydingo123', 'vmsroot', 'test', 'yo but edited', NULL, '2026-03-19-23:17', 'general', 'blueydingo123', '2026-03-20-20:18'),
(25, 'blueydingo123', 'vmsroot', 'test', 'Another Bluey reply but edited', NULL, '2026-03-20-20:18', 'general', 'blueydingo123', '2026-03-20-20:18'),
(31, 'vmsroot', 'vmsroot', 'Another Test Discussion', 'Reply to me.', NULL, '2026-03-20-20:28', 'general', NULL, NULL),
(32, 'vmsroot', 'vmsroot', 'Another Test Discussion', 'Sure, let\'s talk. Sorry, edited.', '31', '2026-03-20-20:28', 'general', 'vmsroot', '2026-03-20-20:28'),
(33, 'vmsroot', 'vmsroot', 'Another Test Discussion', 'vmsroot as board_member temporarily enters the convo', NULL, '2026-03-20-20:46', 'general', NULL, NULL),
(34, 'vmsroot', 'vmsroot', 'My Discussion', 'Yay. But edited.', NULL, '2026-03-20-21:15', 'board', 'vmsroot', '2026-03-20-21:15'),
(39, 'blueydingo123', 'vmsroot', 'Another Test Discussion', 'From event manager Bluey.', NULL, '2026-03-20-21:20', 'general', NULL, NULL),
(41, 'blueydingo123', 'vmsroot', 'Another Late Night Talk', 'Hello', NULL, '2026-03-23-20:12', 'general', NULL, NULL),
(42, 'vmsroot', 'blueydingo123', 'From vmsroot this is board', 'Hi', NULL, '2026-03-23-20:34', 'board', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_hours_snapshot`
--

CREATE TABLE `monthly_hours_snapshot` (
  `id` int(11) NOT NULL,
  `person_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `month_year` date DEFAULT NULL,
  `hours` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_hours_snapshot`
--

INSERT INTO `monthly_hours_snapshot` (`id`, `person_id`, `month_year`, `hours`) VALUES
(36, 'ameyer3', '2025-03-15', 77),
(37, 'jane_doe', '2025-03-15', 0),
(38, 'john_doe', '2025-03-15', 0),
(39, 'michael_smith', '2025-03-15', 0),
(40, 'vmsroot', '2025-03-15', 0),
(57, 'ameyer3', '2025-04-01', 96),
(58, 'jane_doe', '2025-04-01', 3),
(59, 'john_doe', '2025-04-01', 6),
(60, 'michael_smith', '2025-04-01', 8),
(61, 'vmsroot', '2025-04-01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `user_id` varchar(255) NOT NULL,
  `group_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`user_id`, `group_name`) VALUES
('ameyer3', 'test'),
('BobVolunteer', 'test'),
('vmsroot', 'cool guys');

-- --------------------------------------------------------

--
-- Table structure for table `user_verified_ids`
--

CREATE TABLE `user_verified_ids` (
  `record_id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `id_type` varchar(50) NOT NULL,
  `approved_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `user_verified_ids`
--

INSERT INTO `user_verified_ids` (`record_id`, `user_id`, `id_type`, `approved_at`) VALUES
(1, 'edarnell', 'DL', '2025-12-08 20:28:26'),
(2, 'edarnell', 'Military', '2025-12-09 15:51:37'),
(3, 'fakename', 'Military', '2025-12-10 16:43:24'),
(4, 'fakename', 'DL', '2025-12-10 18:28:47'),
(5, 'fakename', 'Passport', '2025-12-10 18:28:50'),
(6, 'edarnell', 'Other', '2025-12-11 00:44:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boarddocuments`
--
ALTER TABLE `boarddocuments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbapplications`
--
ALTER TABLE `dbapplications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbarchived_volunteers`
--
ALTER TABLE `dbarchived_volunteers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbattendance`
--
ALTER TABLE `dbattendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbavailabilities`
--
ALTER TABLE `dbavailabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `dbdiscussions`
--
ALTER TABLE `dbdiscussions`
  ADD PRIMARY KEY (`author_id`,`title`,`category`);

--
-- Indexes for table `dbdrafts`
--
ALTER TABLE `dbdrafts`
  ADD PRIMARY KEY (`draftID`);

--
-- Indexes for table `dbeventcomments`
--
ALTER TABLE `dbeventcomments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbeventmedia`
--
ALTER TABLE `dbeventmedia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbeventpersons`
--
ALTER TABLE `dbeventpersons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKeventID` (`eventID`),
  ADD KEY `FKpersonID` (`userID`);

--
-- Indexes for table `dbevents`
--
ALTER TABLE `dbevents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbgroups`
--
ALTER TABLE `dbgroups`
  ADD PRIMARY KEY (`group_name`);

--
-- Indexes for table `dblanguages`
--
ALTER TABLE `dblanguages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `dbLog`
--
ALTER TABLE `dbLog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbmessages`
--
ALTER TABLE `dbmessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbpersonhours`
--
ALTER TABLE `dbpersonhours`
  ADD KEY `FkpersonID2` (`personID`),
  ADD KEY `FKeventID3` (`eventID`);

--
-- Indexes for table `dbpersons`
--
ALTER TABLE `dbpersons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `dbscheduledemails`
--
ALTER TABLE `dbscheduledemails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbshifts`
--
ALTER TABLE `dbshifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `dbsuggestions`
--
ALTER TABLE `dbsuggestions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbtraining_materials`
--
ALTER TABLE `dbtraining_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussion_replies`
--
ALTER TABLE `discussion_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `fk_author` (`author_id`),
  ADD KEY `fk_user` (`user_reply_id`),
  ADD KEY `fk_parent` (`parent_reply_id`);

--
-- Indexes for table `monthly_hours_snapshot`
--
ALTER TABLE `monthly_hours_snapshot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`user_id`,`group_name`);

--
-- Indexes for table `user_verified_ids`
--
ALTER TABLE `user_verified_ids`
  ADD PRIMARY KEY (`record_id`),
  ADD UNIQUE KEY `unique_user_id_type` (`user_id`,`id_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boarddocuments`
--
ALTER TABLE `boarddocuments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dbapplications`
--
ALTER TABLE `dbapplications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dbattendance`
--
ALTER TABLE `dbattendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `dbavailabilities`
--
ALTER TABLE `dbavailabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `dbdrafts`
--
ALTER TABLE `dbdrafts`
  MODIFY `draftID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dbeventcomments`
--
ALTER TABLE `dbeventcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `dbeventmedia`
--
ALTER TABLE `dbeventmedia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dbeventpersons`
--
ALTER TABLE `dbeventpersons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `dbevents`
--
ALTER TABLE `dbevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9014;

--
-- AUTO_INCREMENT for table `dblanguages`
--
ALTER TABLE `dblanguages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `dbLog`
--
ALTER TABLE `dbLog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbmessages`
--
ALTER TABLE `dbmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=808;

--
-- AUTO_INCREMENT for table `dbscheduledemails`
--
ALTER TABLE `dbscheduledemails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `dbshifts`
--
ALTER TABLE `dbshifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `dbsuggestions`
--
ALTER TABLE `dbsuggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dbtraining_materials`
--
ALTER TABLE `dbtraining_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `discussion_replies`
--
ALTER TABLE `discussion_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `monthly_hours_snapshot`
--
ALTER TABLE `monthly_hours_snapshot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `user_verified_ids`
--
ALTER TABLE `user_verified_ids`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbavailabilities`
--
ALTER TABLE `dbavailabilities`
  ADD CONSTRAINT `dbavailabilities_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `dbpersons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dblanguages`
--
ALTER TABLE `dblanguages`
  ADD CONSTRAINT `dblanguages_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `dbpersons` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
