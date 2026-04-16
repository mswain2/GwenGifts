-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 08, 2026 at 03:36 PM
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
(68, 'blueydingo123', 'Sunday', '1pm', '2pm'),
(69, 'blueydingo123', 'Thursday', '3pm', '4pm');

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
(56, 744, 'turkeybird123', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ', 0);

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
(66, 'blueydingo123', 'german', 'beginner', 'fluent', 'fluent', 'intermediate'),
(67, 'blueydingo123', 'dingo', 'beginner', 'beginner', 'beginner', 'beginner');

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
(783, 'vmsroot', 'vmsroot', 'deletethis123 has been added as a volunteer', 'New volunteer account has been created', '2026-03-31-22:40', 0, 0),
(784, 'vmsroot', 'vmsroot', 'janedoe1 has been added as a volunteer', 'New volunteer account has been created', '2026-03-31-23:41', 0, 0),
(785, 'vmsroot', 'turkeybird123', 'You are now signed up for \\\'Sup Ya\\\'ll & Pals?!', 'Thank you for signing up for \\\'Sup Ya\\\'ll & Pals?!', '2026-04-01-12:04', 0, 0);

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
('vmsroot', 186, '2026-02-06 16:13:25', NULL, 'pending');

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
('acarmich@mail.umw.edu', '2025-12-01', 'John', 'Doe', NULL, 'Fredericksburg', 'VA', NULL, '5555555555', 'true', '', '', '', '', 'acarmich@mail.umw.edu', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$1CDYmdifcx5rfR80Ui8WLuM2ldqc4DTJiFbK1JMSLycE/0lLKPJUS', 'Family', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('ameyer3', '2025-03-26', 'Aidan', 'Meyer', '1541 Surry Hill Court', 'Charlottesville', 'VA', '22901', '4344222910', NULL, 'home', '4344222910', 'home', '2003-08-17', 'aidanmeyer32@gmail.com', NULL, 'Aidan', 'n/a', 'Father', NULL, 'volunteer', 'Active', NULL, '$2y$10$0R5pX4uTxS0JZ4rc7dGprOK4c/d1NEs0rnnaEmnW4sz8JIQVyNdBC', NULL, NULL, 0, 'Meyer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('armyuser', '2025-11-30', 'Army', 'Active Duty', NULL, 'FXBG', 'VA', NULL, '3243242342', 'true', '', '', '', '', 'example@example.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$kdxwMq.xaGsYvl8gY/8l3.xwu9ABEhWernkR6kmro9QtNvvEjqPFi', 'Active duty', 'Army', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('blueydingo123', '2026-03-13', 'Bluey', 'Dingo', '12 Doggo Court', 'Dingo Valley', 'VA', '12345', '1111111111', NULL, 'cellphone', '8888888888', 'cellphone', '2000-11-11', 'blueythedingo@email.com', 'true', 'Mum', '', 'Mother', '', 'event_manager', 'Active', 'Dingos can now volunteer as of March 12, 2026 WHOA YEAH DINGOS LOVE DINGOS. More notes.', '$2y$10$fdekTq9y4mzcNmJrh/83pek8z5Tk7AyxCCKSKyKVmBse9O96cfsOW', '', '', NULL, 'Dingo', 'Female', 'M', 'yes', 'yes', 'no', 'Having fun, football', 'Retail', NULL, 0.00, 0, 'images/profile_pics/pfp_blueydingo123_1775358098.png', 'no', 'no', 'no', ''),
('BobVolunteer', '2025-04-29', 'Bob', 'SPCA', '123 Dog Ave', 'Dogville', 'VA', '54321', '9806761234', NULL, 'home', '1234567788', 'home', '2020-03-03', 'fred54321@gmail.com', NULL, 'Luke', 'n/a', 'Bff', NULL, 'volunteer', 'Active', NULL, '$2y$10$4wUwAW0yoizxi5UFy1/OZu.yfYY7rzUsuYcZCdvfplLj95r7OknvG', NULL, NULL, 0, 'Blair', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Britorsk', '2026-02-05', 'Brian', 'Prelle', NULL, 'KING GEORGE', 'VA', NULL, '5402076085', 'true', '', '', '', '', 'brian2@prelle.net', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$q9wFQJ/guFjlUnR7IfJt/.MRf5bDfK8FxebznfRt644twzYepM/bC', 'Family', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('deletethis123', '2026-03-31', 'Delete', 'This', 'Nah', 'This', 'VA', '11223', '3332221111', NULL, 'work', '3332221110', 'cellphone', '2000-03-24', 'dis.fake@email.com', 'true', 'Weird', '', 'IDK', '', 'volunteer', 'Active', '', '$2y$10$yxIFiUHtAaDrsROUAf4CDORaAxDra70VQEWnfWP/JGVr9Q67I5c2q', '', '', NULL, 'This', 'Other', 'XL', 'no', 'no', 'no', '', '', 'yes', 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('exampleuser', '2025-10-20', 'example', 'user', '', 'test', 'VA', '', '2344564645', NULL, '', '', '', '', 'example@test.com', NULL, '', 'n/a', '', NULL, 'volunteer', 'Active', NULL, '$2y$10$J0NgBjoyg9F6YMyy/qQpv.f94OLM2r19sY80BZMhMdcl38SN5vdre', NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('fakename', '2025-12-10', 'fake', 'name', NULL, 'realtown', 'VA', NULL, '5555555555', 'true', '', '', '', '', 'fakeemail@email.email.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$4h8ImkaTyMprwU3SzWrWx./NBI7yClMoqCkEbYJuA1/9cb0tSlUI.', 'Civilian', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('firstName', '2025-12-10', 'firstName', 'lastName', NULL, 'homeTown', 'TX', NULL, '5555555555', 'true', '', '', '', '', 'realemail@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$og/aLBzrg195Qph9d2M/DuX2DIPhP.0sVT3vtu/WUpGCse8B.k71m', 'Civilian', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('gabriel', '2026-02-02', 'Gabriel', 'Courtney', NULL, 'King George', 'VA', NULL, '5404295285', 'true', '', '', '', '', 'gabrielcourtney04@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$4uvfLFyFy9Ui1i8Q1r0MWuFRGYfgvVh4.iUtvXksfVJm4pZpxxtSq', 'Active duty', 'Space Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('janedoe1', '2026-03-31', 'Jane', 'Doe', '4 Zallery Rd', 'Stafford', 'VA', '22554', '4444444444', NULL, 'cellphone', '3333333333', 'cellphone', '2002-06-07', 'janedoe@gmail.com', 'true', 'John', '', 'Brother', '', 'volunteer', 'Active', '', '$2y$10$Uh2aZ9qnux9yIIWtY9azH.H3ZGDxx9eVm8IL.2nLtsDsRXmgMV54m', '', '', NULL, 'Doe', 'Female', 'L', 'yes', 'yes', 'no', 'Event planning', 'Industry', 'yes', 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('japper', '2026-02-02', 'Jennifer', 'Polack', NULL, 'Fredericksburg', 'VA', NULL, '5406541318', 'true', '', '', '', '', 'jenniferpolack@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$mJzI.UGPGUmYgo7HxTamkeKlsmajzLwXM6su4NdxuHYHZXIGnb0xm', 'Family', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Jlipinsk', '2025-12-03', 'Jake', 'Lipinski', NULL, 'Williamsburg', 'VA', NULL, '7577903325', 'true', '', '', '', '', 'jlipinsk@mail.umw.edu', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$qz33T0Sq760IITyYajCYOeWlHR/7sRJH.U609EUkF3R5zRiWWddkG', 'Civilian', 'Army', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('johnDoe123', '2026-02-07', 'John', 'Doe', NULL, 'Fredericksburg', 'VA', NULL, '2345678910', 'true', '', '', '', '', 'test@email.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$LTVIuLeSZ4ferdNOe0JdTedaFHqFuEOAz7HDCQuZ4PG9kZrRJc7xS', 'Active duty', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('lukeg', '2025-04-29', 'Luke', 'Gibson', '22 N Ave', 'Fredericksburg', 'VA', '22401', '1234567890', NULL, 'cellphone', '1234567890', 'cellphone', '2025-04-28', 'volunteer@volunteer.com', NULL, 'NoName', 'n/a', 'Brother', NULL, 'volunteer', 'Active', NULL, '$2y$10$KsNVJYhvO5D287GpKYsIPuci9FnL.Eng9R6lBpaetu2Y0yVJ7Uuiq', NULL, NULL, 0, 'YesName', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('maddiev', '2025-04-28', 'maddie', 'van buren', '123 Blue st', 'fred', 'VA', '12343', '1234567890', NULL, 'cellphone', '1234567819', 'cellphone', '2003-05-17', 'mvanbure@mail.umw.edu', NULL, 'mommy', 'n/a', 'mom', NULL, 'volunteer', 'Active', NULL, '$2y$10$0mv3.e6gjqoIg.HfT5qVXOsI.Ca5E93DAy8BnT124W1PvMDxpfoxy', NULL, NULL, 0, 'van buren', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('michael_smith', '2025-03-16', 'Michael', 'Smith', '789 Pine Street', 'Charlottesville', 'VA', '22903', '4345559876', NULL, 'mobile', '4345553322', 'work', '1995-08-22', 'michaelsmith@email.com', NULL, 'Sarah', '4345553322', 'Sister', 'email', 'volunteer', 'Active', '', '$2y$10$XYZ789xyz456LMN123DEF', NULL, NULL, 0, 'Smith', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('michellevb', '2025-04-29', 'Michelle', 'Van Buren', '1234 Red St', 'Freddy', 'VA', '22401', '1234567890', NULL, 'cellphone', '0987654321', 'cellphone', '1980-08-18', 'michelle.vb@gmail.com', NULL, 'Madison', 'n/a', 'daughter', NULL, 'volunteer', 'Active', NULL, '$2y$10$bkqOWUdIJoSa6kZoRo5KH.cerZkBQf74RYsponUUgefJxNc8ExppK', NULL, NULL, 0, 'Van Buren', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('navyspouse', '2025-11-30', 'Navy', 'Spouse', NULL, 'FXBG', 'VA', NULL, '3543534543', 'true', '', '', '', '', 'example@example.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$nqoIFq4ru0k1wLkg0E/rfupwez.x1Gg6ldEuKgC.jIQemgCEuDzkG', 'Family', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('olivia', '2026-02-04', 'Olivia', 'Blue', NULL, 'Fredericksburg', 'VA', NULL, '1112223333', 'false', '', '', '', '', 'oliviablue@gmail.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$ew4nuUYBtx6.CbNBezMTYuAQGaxMJgxIs4I3uIx05Sb7SqxKHJO2S', 'Family', 'Marine Corp', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test_acc', '2025-04-29', 'test', 'test', 'test', 'test', 'VA', '22405', '5555555555', NULL, 'cellphone', '5555555555', 'cellphone', '2003-03-03', 'test@gmail.com', NULL, 'test', 'n/a', 't', NULL, 'volunteer', 'Active', NULL, '$2y$10$kpVA41EXvoJyv896uDBEF.fHCPmSlkVSaXjHojBl7DqbRnEm//kxy', NULL, NULL, 0, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test_person', '2025-10-26', 'Testina', 'Tester', NULL, 'Testville', 'VA', NULL, '5555555555', 'true', 'mobile', NULL, NULL, '1980-08-18', 'testing@gmail.com', 'false', NULL, 'n/a', NULL, NULL, 'volunteer', NULL, NULL, '$2y$10$blAQaBgCChBv5qRtBFVVAe1m6gIfwPf/wJ8HxzLFTYiY3aWpvaW8e', 'civilian', 'Army', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test_persona', '2025-10-28', 'Testana', 'Tester', NULL, 'Testinaville', 'VA', NULL, '5555555555', 'true', NULL, NULL, NULL, NULL, 'testerana@gmail.com', 'true', NULL, 'n/a', NULL, NULL, 'volunteer', NULL, NULL, '$2y$10$s90qlNAJE9EbgLhZbhG5vO4IGSM.PIbK3Ve9IvpfoicMwXbFEXQFi', 'active', 'air_force', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('test@volunteer1.com', '2025-02-10', 'Alex', 'Johnson', '456 Oak Ave', 'Fredericksburg', 'VA', '22401', '5405559876', NULL, 'cellphone', '5405551111', NULL, '1995-03-15', 'test@volunteer1.com', NULL, 'Sarah', 'n/a', 'Mother', NULL, 'volunteer', 'Active', NULL, '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa', NULL, NULL, NULL, 'Johnson', 'Male', 'L', 'yes', 'yes', 'yes', NULL, NULL, NULL, 0.00, 1, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('tester4', '2025-12-01', 'tester', 'testing', NULL, 'Fredericksburg', 'VA', NULL, '5405405405', 'true', '', '', '', '', 'tester@gmail.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$nILE/qxdpSvIgROc1uQEV.MyflEdG0IuNLQQ1c1u54MSEYKlg2LC2', 'Active duty', 'Space Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('testing123', '2025-10-26', 'Test', 'User', NULL, 'City', 'VA', NULL, '', 'true', NULL, NULL, NULL, NULL, 'example@email.com', 'true', NULL, 'n/a', NULL, NULL, 'volunteer', NULL, NULL, '$2y$10$XbXkJUMSAGo9m1/GZQ3faebtJWbPMZYm/AeTA3jpDCaxZBNnMclxC', 'civ', 'marine_corp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('testytesty', '2026-03-08', 'hieric', 'mcgowan', NULL, '22', 'AK', NULL, '5408426399', NULL, 'cellphone', '5408426399', 'cellphone', '2026-03-07', 'q@gmail.com', 'true', '1', '', '11', '', 'volunteer', '', '', '$2y$10$IdejuUFgJuawe9ZVcIuRhePQXViN.wQv05WVIZYy3pLIfkuZ9TSAy', '', '', NULL, '1', 'Other', 'XXL', 'yes', 'yes', 'yes', 'q', 'q', 'yes', 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('toaster', '2025-12-08', 'toast', 'er', NULL, 'Fredericksburg', 'VA', NULL, '5405405405', 'true', '', '', '', '', 'toaster@gmail.com', 'false', '', '', '', '', 'volunteer', '', '', '$2y$10$VzLJcSjn/WFh0jeI9iFAw.McczukN4ovZuzg9vgtKFlXL3i/O9oOq', 'Civilian', 'Navy', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('turkeybird123', '2026-03-15', 'Turkey', 'Bird', '33 Oven Court', 'Flocking City', 'VA', '98765', '3333333333', NULL, 'cellphone', '3303303330', 'home', '2020-03-12', 'turkeythebird@email.com', 'true', 'Dad', '', 'Father', '', 'board_member', 'Active', 'TURKEYYYYY', '$2y$10$go2TVlMDVu3VzSXFXqKiT.NWg1HPQ2nZtE3iHeP1xlTaYhO6hinIG', '', '', NULL, 'Bird', 'Female', 'S', 'no', 'no', 'yes', 'Having fun, being an ass', 'Culinary, taster', 'yes', 0.00, 0, 'images/profile_pics/pfp_turkeybird123_1775357190.png', 'no', 'no', 'no', ''),
('vmsroot', 'N/A', 'vmsroot', '', NULL, 'N/A', 'VA', NULL, '', NULL, 'N/A', '', '', 'N/A', 'vmsroot', 'false', '', 'N/A', '', '', 'admin', 'N/A', 'N/A', '$2y$10$DokO.38InJwE5SoMOtL1kuw8HhBXq.mNX3/RLLv2rTQL5LH1Pq15.', '', '', NULL, '', '', '', 'no', 'no', 'no', '', '', 'no', 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Volunteer25', '2025-04-30', 'Volley', 'McTear', '123 Dog St', 'Dogville', 'VA', '56748', '9887765543', NULL, 'home', '6565651122', 'home', '2025-04-29', 'volly@gmail.com', NULL, 'Holly', 'n/a', 'Besty', NULL, 'volunteer', 'Active', NULL, '$2y$10$45gKdbjW78pNKX/5ROtb7eU9OykSCsP/QCyTAvqBtord4J7V3Ywga', NULL, NULL, 0, 'McTear', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL),
('Welp', '2025-12-04', 'Jake', 'Lipinski', NULL, 'Apple', 'VA', NULL, '7577903325', 'true', '', '', '', '', 'mcdonalds@happymeal.com', 'true', '', '', '', '', 'volunteer', '', '', '$2y$10$LvWD62DJ6pwlVGnWenQkneWCFINzgbHgzyvaBdiLn72/WwM4wo7Iy', 'Active duty', 'Air Force', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 'images/usaicon.png', 'no', 'no', 'no', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dbscheduledemails`
--

CREATE TABLE `dbscheduledemails` (
  `id` int(11) NOT NULL,
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

INSERT INTO `dbscheduledemails` (`id`, `userID`, `recipientID`, `subject`, `body`, `scheduledSend`, `sent`, `created`) VALUES
(17, 'vmsroot', 'All Whiskey Valor Members', 'Scheduling an Email!', 'This is a scheduled email', '2025-12-04', 0, '2025-12-02 21:46:39'),
(18, 'vmsroot', 'Jake Lipinski', 'Scheduled email to myself', 'Please work', '2025-12-04', 0, '2025-12-04 19:21:48'),
(19, 'vmsroot', 'Evan Darnell', 'TEST SCHEDULE', 'This email will be sent on the morning of the selected send date.\r\n\r\nNote for me if this sends though, you made it', '2025-12-26', 0, '2025-12-09 15:56:46'),
(20, 'vmsroot', 'jlipinsk@mail.umw.edu', 'Yippee', 'Work', '2025-12-10', 0, '2025-12-10 07:13:15'),
(21, 'vmsroot', 'Jlipinsk', 'adfasf', 'ahg', '2025-12-10', 1, '2025-12-10 08:46:45'),
(22, 'vmsroot', 'Jlipinsk', 'Work Please!', 'Test', '2025-12-10', 1, '2025-12-10 10:02:09'),
(23, 'vmsroot', 'acarmich@mail.umw.edu', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(24, 'vmsroot', 'ameyer3', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(25, 'vmsroot', 'armyuser', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(26, 'vmsroot', 'BobVolunteer', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(27, 'vmsroot', 'edarnell', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(28, 'vmsroot', 'exampleuser', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(29, 'vmsroot', 'Jlipinsk', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(30, 'vmsroot', 'lukeg', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(31, 'vmsroot', 'maddiev', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(32, 'vmsroot', 'michael_smith', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(33, 'vmsroot', 'michellevb', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(34, 'vmsroot', 'navyspouse', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(35, 'vmsroot', 'test_acc', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(36, 'vmsroot', 'test_person', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(37, 'vmsroot', 'test_persona', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(38, 'vmsroot', 'tester4', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(39, 'vmsroot', 'testing123', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(40, 'vmsroot', 'toaster', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(41, 'vmsroot', 'Volunteer25', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(42, 'vmsroot', 'Welp', 'Test for Software Engineering Scheduled Emails', 'This is the full test for Scheduled Emails. By tomorrow\'s presentation this should have sent out in an email!', '2025-12-10', 1, '2025-12-10 10:04:36'),
(43, 'vmsroot', 'acarmich@mail.umw.edu', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(44, 'vmsroot', 'ameyer3', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(45, 'vmsroot', 'armyuser', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(46, 'vmsroot', 'BobVolunteer', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(47, 'vmsroot', 'Britorsk', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(48, 'vmsroot', 'exampleuser', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(49, 'vmsroot', 'fakename', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(50, 'vmsroot', 'firstName', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(51, 'vmsroot', 'gabriel', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(52, 'vmsroot', 'japper', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(53, 'vmsroot', 'Jlipinsk', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(54, 'vmsroot', 'lukeg', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(55, 'vmsroot', 'maddiev', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(56, 'vmsroot', 'michael_smith', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(57, 'vmsroot', 'michellevb', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(58, 'vmsroot', 'navyspouse', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(59, 'vmsroot', 'olivia', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(60, 'vmsroot', 'test_acc', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(61, 'vmsroot', 'test_person', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(62, 'vmsroot', 'test_persona', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(63, 'vmsroot', 'tester4', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(64, 'vmsroot', 'testing123', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(65, 'vmsroot', 'toaster', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(66, 'vmsroot', 'Volunteer25', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05'),
(67, 'vmsroot', 'Welp', 'the spungle', 'THIS ISN\'T SPAM', '2026-02-07', 1, '2026-02-06 21:28:05');

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
(34, 'vmsroot', '2025-09-10', '11:36:05', NULL, NULL, NULL);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbavailabilities`
--
ALTER TABLE `dbavailabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `dbevents`
--
ALTER TABLE `dbevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1758;

--
-- AUTO_INCREMENT for table `dblanguages`
--
ALTER TABLE `dblanguages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `dbLog`
--
ALTER TABLE `dbLog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbmessages`
--
ALTER TABLE `dbmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=786;

--
-- AUTO_INCREMENT for table `dbscheduledemails`
--
ALTER TABLE `dbscheduledemails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `dbshifts`
--
ALTER TABLE `dbshifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
