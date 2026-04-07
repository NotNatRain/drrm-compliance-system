-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2026 at 07:47 AM
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
-- Database: `drrmcompliance`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(32) DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `module` varchar(64) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `role`, `activity`, `school_id`, `school_name`, `module`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'Updated room: Room-010', 11, 'Iram I Elementary School', 'fire_safety', 'Okay kayo room', '2026-03-11 23:55:06', '2026-03-11 23:55:06'),
(2, 1, 'admin', 'Logged incident: Earthquake at Dos Trios School', NULL, 'Dos Trios School', 'incident_checklist', 'Magnitude of 3.2 earthquake', '2026-03-11 23:56:47', '2026-03-11 23:56:47'),
(3, 1, 'admin', 'Registered family: Louise Santiago', 11, 'Iram I Elementary School', 'typhoon_flood', NULL, '2026-03-11 23:58:47', '2026-03-11 23:58:47'),
(8, 1, 'admin', 'Created incident status: Fire Drilling', NULL, NULL, 'incident_checklist', NULL, '2026-03-12 00:04:20', '2026-03-12 00:04:20'),
(9, 1, 'admin', 'Logged incident: Incident at Deped SDO department', NULL, 'Deped SDO department', 'incident_checklist', 'To practice personnel', '2026-03-12 00:05:29', '2026-03-12 00:05:29'),
(42, 1, 'admin', 'Updated checklist item: Incident Verification Completed (Completed)', NULL, NULL, 'incident_checklist', NULL, '2026-03-15 18:26:07', '2026-03-15 18:26:07'),
(43, 1, 'admin', 'Added checklist item: Go to Jackson and inspect fire extinguisher there?', NULL, NULL, 'incident_checklist', NULL, '2026-03-15 18:26:38', '2026-03-15 18:26:38'),
(44, 1, 'admin', 'Updated checklist item: Go to Jackson and inspect fire extinguisher there? (Completed)', NULL, NULL, 'incident_checklist', NULL, '2026-03-15 18:26:40', '2026-03-15 18:26:40'),
(45, 1, 'admin', 'Deleted checklist item: School Head Confirmation Received', NULL, NULL, 'incident_checklist', NULL, '2026-03-15 22:41:36', '2026-03-15 22:41:36'),
(46, 1, 'admin', 'Updated checklist item: Incident Verification Completed (Uncompleted)', NULL, NULL, 'incident_checklist', NULL, '2026-03-15 22:41:43', '2026-03-15 22:41:43'),
(47, 1, 'admin', 'Updated evacuation center: 107121', 11, 'Iram I Elementary School', 'typhoon_flood', 'Status: full', '2026-03-15 22:44:18', '2026-03-15 22:44:18'),
(48, 1, 'admin', 'Created evacuation center: ', 18, 'Nellie E. Brown Elementary School', 'typhoon_flood', NULL, '2026-03-15 22:45:41', '2026-03-15 22:45:41'),
(49, 1, 'admin', 'Logged incident: Fire at Unknown', NULL, 'Unknown', 'incident_checklist', 'To be encode later', '2026-03-15 23:57:37', '2026-03-15 23:57:37'),
(50, 1, 'admin', 'Updated checklist item: Daily Monitoring Report Submitted (Completed)', NULL, NULL, 'incident_checklist', NULL, '2026-03-15 23:59:41', '2026-03-15 23:59:41'),
(59, 1, 'admin', 'Deleted checklist item: Incident Verification Completed', NULL, NULL, 'incident_checklist', NULL, '2026-03-16 16:48:27', '2026-03-16 16:48:27'),
(60, 1, 'admin', 'Deleted checklist item: Go to Jackson and inspect fire extinguisher there?', NULL, NULL, 'incident_checklist', NULL, '2026-03-16 16:48:30', '2026-03-16 16:48:30'),
(61, 1, 'admin', 'Deleted checklist item: Victim Assistance Log Updated', NULL, NULL, 'incident_checklist', NULL, '2026-03-16 16:48:33', '2026-03-16 16:48:33'),
(62, 1, 'admin', 'Added checklist item: Encode and put all info that has happened this week', NULL, NULL, 'incident_checklist', NULL, '2026-03-16 16:48:59', '2026-03-16 16:48:59'),
(63, 1, 'admin', 'Updated checklist item: Encode and put all info that has happened this week (Completed)', NULL, NULL, 'incident_checklist', NULL, '2026-03-16 16:49:01', '2026-03-16 16:49:01'),
(64, 1, 'admin', 'Added checklist item: Other task (outdoor)', NULL, NULL, 'incident_checklist', NULL, '2026-03-17 16:50:17', '2026-03-17 16:50:17'),
(65, 1, 'admin', 'Updated checklist item: Other task (outdoor) (Completed)', NULL, NULL, 'incident_checklist', NULL, '2026-03-17 16:50:18', '2026-03-17 16:50:18'),
(66, 1, 'admin', 'Updated checklist item: Daily Monitoring Report Submitted (Completed)', NULL, NULL, 'incident_checklist', NULL, '2026-03-17 16:50:20', '2026-03-17 16:50:20'),
(79, 1, 'admin', 'Registered family: Katadingan Reyes', 18, 'Nellie E. Brown Elementary School', 'typhoon_flood', NULL, '2026-03-16 18:00:43', '2026-03-16 18:00:43'),
(80, 1, 'admin', 'Logged incident: Incident at All School', NULL, 'All School', 'incident_checklist', 'Eid\'l Fitr day', '2026-03-16 18:02:22', '2026-03-16 18:02:22'),
(91, 1, 'admin', 'Uploaded Custom Evacuation Map', 11, 'Iram I Elementary School', 'fire_safety', NULL, '2026-03-17 03:51:31', '2026-03-17 03:51:31'),
(92, 1, 'admin', 'Uploaded Custom Evacuation Map', 11, 'Iram I Elementary School', 'fire_safety', NULL, '2026-03-17 03:51:42', '2026-03-17 03:51:42'),
(141, 1, 'admin', 'Updated inspection: Fire', 11, 'Iram I Elementary School', 'fire_safety', 'Repaint directional Arrows\r\nPut up exit signages\r\nInc. I.D for personnel & Students', '2026-03-17 07:39:56', '2026-03-17 07:39:56'),
(142, 1, 'admin', 'Removed building: 014', 11, 'Iram I Elementary School', 'fire_safety', 'To be removed, building doesn\'t exist at first place', '2026-03-17 07:51:17', '2026-03-17 07:51:17'),
(143, 1, 'admin', 'Updated evacuation map layout', 11, 'Iram I Elementary School', 'fire_safety', NULL, '2026-03-18 00:29:55', '2026-03-18 00:29:55'),
(144, 1, 'admin', 'Updated evacuation map layout', 11, 'Iram I Elementary School', 'fire_safety', NULL, '2026-03-18 00:51:05', '2026-03-18 00:51:05'),
(145, 1, 'admin', 'Updated evacuation map layout', 11, 'Iram I Elementary School', 'fire_safety', NULL, '2026-03-18 00:51:25', '2026-03-18 00:51:25'),
(148, 1, 'admin', 'Updated alarm: 01', 18, 'Nellie E. Brown Elementary School', 'fire_safety', 'Okay bell', '2026-03-18 01:07:17', '2026-03-18 01:07:17'),
(149, 1, 'admin', 'Created building: 099', 18, 'Nellie E. Brown Elementary School', 'fire_safety', NULL, '2026-03-18 01:36:22', '2026-03-18 01:36:22'),
(150, 1, 'admin', 'Removed building: 099', 18, 'Nellie E. Brown Elementary School', 'fire_safety', 'Testing building', '2026-03-18 01:37:05', '2026-03-18 01:37:05'),
(152, 1, 'admin', 'Created backup: fire-safety-backup-20260318_101442.json', NULL, NULL, 'fire_safety', NULL, '2026-03-18 02:14:42', '2026-03-18 02:14:42'),
(153, 1, 'admin', 'Updated configuration (alarm_type): Bell', NULL, NULL, 'fire_safety', NULL, '2026-03-18 02:34:46', '2026-03-18 02:34:46'),
(154, 1, 'admin', 'Logged incident: Accidents at Unknown', NULL, 'Unknown', 'incident_checklist', 'Saksakan', '2026-03-18 08:45:08', '2026-03-18 08:45:08'),
(155, 1, 'admin', 'Updated incident: Unknown', NULL, NULL, 'incident_checklist', NULL, '2026-03-19 00:22:41', '2026-03-19 00:22:41'),
(156, 1, 'admin', 'Logged incident: Earthquake at Amelia Heights ES', NULL, 'Amelia Heights ES', 'incident_checklist', 'Rat Mark', '2026-03-19 00:24:57', '2026-03-19 00:24:57'),
(157, 1, 'admin', 'Deleted incident: Earthquake at Amelia Heights ES', NULL, NULL, 'incident_checklist', NULL, '2026-03-19 00:25:28', '2026-03-19 00:25:28'),
(158, 1, 'admin', 'Logged incident: Fire at OLONGAPO CITY NATIONAL HIGH SCHOOL', NULL, 'OLONGAPO CITY NATIONAL HIGH SCHOOL', 'incident_checklist', 'One room affected Location Admin Building Room 207, Cause of Fire Electrical (Broken Oscillating Fan), Approximately 1230H On scene City DRRMO, BFP, PNP,  and SDO DRRM Focal Person. Fire out 1245H as per officer Erwin Magaway and Officer Lising.', '2026-03-19 00:33:45', '2026-03-19 00:33:45'),
(159, 1, 'admin', 'Deleted incident: Accidents at Unknown', NULL, NULL, 'incident_checklist', NULL, '2026-03-19 00:33:58', '2026-03-19 00:33:58'),
(160, 1, 'admin', 'Logged incident: Others at Gordon Heights National High School', NULL, 'Gordon Heights National High School', 'incident_checklist', 'Stabbing incident outside school premises involving outsider and 2 GHNHS Students. Outsider was rushed to the nearest hospital for treatment. Later parents of involve d students and outsider set Barangay Meeting to settle the concerned issue.', '2026-03-19 00:41:18', '2026-03-19 00:41:18'),
(162, 1, 'admin', 'Created configuration (safety_feature): Dry Stand Pipe', NULL, NULL, 'fire_safety', NULL, '2026-03-19 02:18:25', '2026-03-19 02:18:25'),
(240, 1, 'admin', 'Added checklist item: Go to elementary school', NULL, NULL, 'incident_checklist', NULL, '2026-03-19 06:04:33', '2026-03-19 06:04:33'),
(242, 1, 'admin', 'Created configuration (safety_feature): Axe', NULL, NULL, 'fire_safety', NULL, '2026-03-19 06:32:17', '2026-03-19 06:32:17'),
(254, 1, 'admin', 'Created evacuation center: ', 11, 'Iram I Elementary School', 'typhoon_flood', NULL, '2026-03-23 01:13:43', '2026-03-23 01:13:43'),
(256, 7, 'contributor', 'Logged incident: Violence/Conflict at Integrated School at the Mabini High School', NULL, 'Integrated School at the Mabini High School', 'incident_checklist', 'This Love', '2026-03-23 01:44:08', '2026-03-23 01:44:08'),
(257, 1, 'admin', 'Logged compliance: Fire Drilling at Integrated School at the Mabini High School', NULL, 'Integrated School at the Mabini High School', 'incident_checklist', 'oks', '2026-03-23 15:43:12', '2026-03-23 15:43:12'),
(258, 1, 'admin', 'Updated building: 011', NULL, NULL, 'fire_safety', NULL, '2026-03-29 01:12:37', '2026-03-29 01:12:37'),
(259, 1, 'admin', 'Updated alarm: ALRM-003', NULL, NULL, 'fire_safety', 'Loudst bell that can cover two more buildings', '2026-03-29 01:12:59', '2026-03-29 01:12:59'),
(260, 1, 'admin', 'Updated alarm: ALRM-003', NULL, NULL, 'fire_safety', 'Loudst bell that can cover two more buildings', '2026-03-29 01:13:06', '2026-03-29 01:13:06'),
(261, 1, 'admin', 'Removed alarm: ALRM-003', NULL, NULL, 'fire_safety', 'To be re-assign yet again', '2026-03-29 01:14:05', '2026-03-29 01:14:05'),
(262, 1, 'admin', 'Updated building: 011', NULL, NULL, 'fire_safety', 'there isn\'t 2nd floor', '2026-03-29 02:26:32', '2026-03-29 02:26:32'),
(263, 1, 'admin', 'Updated building: 011', NULL, NULL, 'fire_safety', 'there isn\'t room', '2026-03-29 02:27:04', '2026-03-29 02:27:04'),
(264, 1, 'admin', 'Updated extinguisher: EXT-0012', NULL, NULL, 'fire_safety', 'new cod', '2026-03-29 02:30:29', '2026-03-29 02:30:29'),
(265, 1, 'admin', 'Updated extinguisher: EXT-001', NULL, NULL, 'fire_safety', 'Reverted code back', '2026-03-29 02:30:42', '2026-03-29 02:30:42'),
(266, 1, 'admin', 'Updated evacuation plan: PLAN AB', NULL, NULL, 'fire_safety', 'Make sure to guide pwd, senior and pregnant if you see one, don\'t push each other, if you\'re in front immediately go outside so that it\'ll not traffic', '2026-03-29 02:32:51', '2026-03-29 02:32:51'),
(267, 1, 'admin', 'Updated evacuation plan: PLAN A', NULL, NULL, 'fire_safety', 'Make sure to guide pwd, senior and pregnant if you see one, don\'t push each other, if you\'re in front immediately go outside so that it\'ll not traffic', '2026-03-29 02:32:59', '2026-03-29 02:32:59'),
(268, 1, 'admin', 'Created building: Scie', NULL, NULL, 'fire_safety', 'large emmote building', '2026-03-30 01:06:44', '2026-03-30 01:06:44'),
(269, 1, 'admin', 'Created alarm: ALRM-003', NULL, NULL, 'fire_safety', 'Okay bell very functional', '2026-03-30 01:07:57', '2026-03-30 01:07:57'),
(270, 1, 'admin', 'Updated alarm: ALRM-001', NULL, NULL, 'fire_safety', 'To be installed', '2026-03-30 01:10:44', '2026-03-30 01:10:44'),
(271, 1, 'admin', 'Created alarm: ALRM-0012', NULL, NULL, 'fire_safety', 'Okay alarm', '2026-03-30 01:29:55', '2026-03-30 01:29:55'),
(272, 1, 'admin', 'Created room: amande', 1, 'Iram I Elementary School', 'fire_safety', 'okay', '2026-03-30 06:24:28', '2026-03-30 06:24:28'),
(273, 1, 'admin', 'Created extinguisher: FRCT-0011', 1, 'Iram I Elementary School', 'fire_safety', 'okay', '2026-03-30 06:25:12', '2026-03-30 06:25:12'),
(274, 1, 'admin', 'Registered school for Comprehensive School Safety (from directory): OLONGAPO CITY NATIONAL HIGH SCHOOL', 30, 'OLONGAPO CITY NATIONAL HIGH SCHOOL', 'comprehensive_safety', NULL, '2026-04-06 08:27:27', '2026-04-06 08:27:27'),
(275, 1, 'admin', 'Created building: 01', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 00:23:11', '2026-04-07 00:23:11'),
(276, 1, 'admin', 'Created alarm: 01', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 00:28:41', '2026-04-07 00:28:41'),
(277, 1, 'admin', 'Created building: 02', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 00:29:06', '2026-04-07 00:29:06'),
(278, 1, 'admin', 'Updated alarm: 01', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 00:29:24', '2026-04-07 00:29:24'),
(279, 1, 'admin', 'Updated alarm: 01', 9, 'New Cabalan Elementary School', 'fire_safety', 'okay', '2026-04-07 00:29:52', '2026-04-07 00:29:52'),
(280, 1, 'admin', 'Created room: Classroom Grade 1', 9, 'New Cabalan Elementary School', 'fire_safety', 'Okay roo', '2026-04-07 00:42:39', '2026-04-07 00:42:39'),
(281, 1, 'admin', 'Created extinguisher: 01', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 00:43:43', '2026-04-07 00:43:43'),
(282, 1, 'admin', 'Created extinguisher: 02', 9, 'New Cabalan Elementary School', 'fire_safety', 'Okay Extinguisher', '2026-04-07 00:44:05', '2026-04-07 00:44:05'),
(283, 1, 'admin', 'Created room: Oks', 9, 'New Cabalan Elementary School', 'fire_safety', 'Okay', '2026-04-07 00:44:30', '2026-04-07 00:44:30'),
(284, 1, 'admin', 'Created room: Classroom Grade 2', 9, 'New Cabalan Elementary School', 'fire_safety', 'Okay', '2026-04-07 00:45:26', '2026-04-07 00:45:26'),
(285, 1, 'admin', 'Updated building: 01', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 00:45:51', '2026-04-07 00:45:51'),
(286, 1, 'admin', 'Created extinguisher: 03', 9, 'New Cabalan Elementary School', 'fire_safety', 'Okay', '2026-04-07 00:46:27', '2026-04-07 00:46:27'),
(287, 1, 'admin', 'Created extinguisher: 04', 9, 'New Cabalan Elementary School', 'fire_safety', 'Okay', '2026-04-07 00:47:06', '2026-04-07 00:47:06'),
(288, 1, 'admin', 'Created extinguisher: 022', 9, 'New Cabalan Elementary School', 'fire_safety', 'Oks', '2026-04-07 00:47:38', '2026-04-07 00:47:38'),
(289, 1, 'admin', 'Transferred extinguisher: 022 to 02', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 00:48:18', '2026-04-07 00:48:18'),
(290, 1, 'admin', 'Created extinguisher: 06', 9, 'New Cabalan Elementary School', 'fire_safety', 'Oks', '2026-04-07 00:49:47', '2026-04-07 00:49:47'),
(291, 1, 'admin', 'Removed extinguisher: 022', 9, 'New Cabalan Elementary School', 'fire_safety', 'Didn\'t exist', '2026-04-07 00:50:39', '2026-04-07 00:50:39'),
(292, 1, 'admin', 'Updated evacuation map layout', 9, 'New Cabalan Elementary School', 'fire_safety', NULL, '2026-04-07 01:09:01', '2026-04-07 01:09:01');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `what` varchar(255) NOT NULL,
  `when` datetime NOT NULL,
  `where` varchar(255) NOT NULL,
  `why` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `what`, `when`, `where`, `why`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Diwata', '2026-02-20 09:00:00', 'Subic Gymn', 'Diwata Adventure (Dance Evolution)\" is a stage play featured by the Hiraya Theater Production for the School Year 2025–2026.', 'announcements/gaZ3Wo3TEDN4L8VxiXKnhxPfQx4DUwzdPnOorwHP.jpg', 1, '2026-02-11 18:34:19', '2026-02-11 18:34:19'),
(2, 'Fire Drill', '2026-02-16 22:00:00', 'Banicain School', 'School Safety Measure Practices in drilling', 'announcements/42djBvTKA9wnTI2pSw5aKVSv2igbscrb1yYLAgeZ.jpg', 1, '2026-02-11 20:03:51', '2026-02-11 20:03:51');

-- --------------------------------------------------------

--
-- Table structure for table `cmpr_schl_sfty_assessments`
--

CREATE TABLE `cmpr_schl_sfty_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `date_visited` date NOT NULL,
  `assessed_by` varchar(255) DEFAULT NULL,
  `total_score` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cmpr_schl_sfty_assessments`
--

INSERT INTO `cmpr_schl_sfty_assessments` (`id`, `school_id`, `date_visited`, `assessed_by`, `total_score`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-04-07', 'Adan Kristopher B. Dumpit', 5.00, 'completed', '2026-04-06 07:26:59', '2026-04-07 03:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `cmpr_schl_sfty_assessment_items`
--

CREATE TABLE `cmpr_schl_sfty_assessment_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assessment_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `criteria` text NOT NULL,
  `is_compliant` tinyint(1) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cmpr_schl_sfty_assessment_items`
--

INSERT INTO `cmpr_schl_sfty_assessment_items` (`id`, `assessment_id`, `category`, `criteria`, `is_compliant`, `points`, `remarks`, `created_at`, `updated_at`) VALUES
(20, 1, 'Enabling Environment', 'Adopted/Localized policies relating to DRRM/CCA/EIE on education/school safety', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(21, 1, 'Enabling Environment', 'Formed School DRRM Team, with a focal person and consisting of personnel from different offices', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(22, 1, 'Enabling Environment', 'Has a comprehensive School DRRM Plan, which includes CCA and EIE measures', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(23, 1, 'Enabling Environment', 'School budget supports regular DRRM activities', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(24, 1, 'Enabling Environment', 'Conducted student-led school watching and hazard mapping', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(25, 1, 'Enabling Environment', 'Incorporated results of student-led school watching and hazard mapping in the SIP', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(26, 1, 'Enabling Environment', 'Data collection and consolidation of programs and activities on DRRM', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(27, 1, 'Safe Learning Facilities', 'School building/classroom components are according to DepEd/National Building Code', 1, 1, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(28, 1, 'Safe Learning Facilities', 'School conducted risk assessment of buildings', 1, 1, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(29, 1, 'Safe Learning Facilities', 'School has taken appropriate action with respect to unsafe school buildings', 1, 1, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(30, 1, 'Safe Learning Facilities', 'Undertaken regular inspection and repair of minor classroom damages', 1, 1, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(31, 1, 'Safe Learning Facilities', 'School heads and teachers have training on psychosocial support', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(32, 1, 'Safe Learning Facilities', 'Classrooms have usually 2 doors that swing out', 1, 1, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(33, 1, 'Safe Learning Facilities', 'Wide corridors for easy movement', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(34, 1, 'Disaster Risk Management', 'School has a contingency plan for various hazards', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(35, 1, 'Disaster Risk Management', '95% of students and personnel participated in drills', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(36, 1, 'Disaster Risk Management', 'School has a functional early warning system', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(37, 1, 'Disaster Risk Management', 'School has available emergency kits/equipment', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10'),
(38, 1, 'Disaster Risk Management', 'School has a trained emergency response team', 0, 0, NULL, '2026-04-07 03:33:10', '2026-04-07 03:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `cmpr_schl_sfty_facilities`
--

CREATE TABLE `cmpr_schl_sfty_facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `condition` varchar(255) NOT NULL DEFAULT 'good',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cmpr_schl_sfty_students`
--

CREATE TABLE `cmpr_schl_sfty_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `student_lrn` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `grade_level` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cmpr_schl_sfty_student_pathways`
--

CREATE TABLE `cmpr_schl_sfty_student_pathways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `pathway_score` int(11) NOT NULL,
  `observation_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `firesafety_alarm_systems`
--

CREATE TABLE `firesafety_alarm_systems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unified_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `building_id` bigint(20) UNSIGNED DEFAULT NULL,
  `floor_id` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `alarm_type` varchar(255) NOT NULL,
  `status` enum('functional','broken','missing','not_installed','jammed','under_repair','online','offline','system_error','under_maintenance','decommissioned','active','maintenance') NOT NULL,
  `last_test` date DEFAULT NULL,
  `next_test_due` date DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `installation_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anchor_building_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `firesafety_alarm_systems`
--

INSERT INTO `firesafety_alarm_systems` (`id`, `unified_school_id`, `building_id`, `floor_id`, `code`, `location`, `alarm_type`, `status`, `last_test`, `next_test_due`, `manufacturer`, `installation_date`, `notes`, `created_at`, `updated_at`, `anchor_building_id`) VALUES
(12, 1, NULL, NULL, 'ALRM-001', 'All Floors - At the center of the 3rd floor', 'Bell', 'active', '2026-02-18', '2026-02-19', NULL, '2026-02-11', 'To be installed', '2026-02-11 00:41:50', '2026-03-30 01:10:44', NULL),
(13, 1, NULL, 'all', 'ALRM-002', 'All Floors - 3rd floor at the stairway', 'Bell', 'active', '2026-02-13', '2026-02-19', NULL, '2026-02-11', 'Great Bell', '2026-02-11 00:43:29', '2026-02-12 18:22:58', NULL),
(15, 1, NULL, 'all', 'ALRM-004', 'All Floors - At the center of hallway', 'Bell', 'active', '2026-02-13', '2026-02-19', NULL, '2026-01-07', 'Okay', '2026-02-12 18:49:45', '2026-02-12 18:52:30', NULL),
(19, 2, 31, 'all', 'ALRM-001', 'Main Lobby', 'Mechanical', 'active', '2026-02-16', '2026-02-20', NULL, '2026-02-18', 'Okay', '2026-02-18 19:26:40', '2026-02-18 19:26:40', NULL),
(20, 7, 32, '2', '001', 'Second Floor Front of Principals Office', 'Digital', 'active', '2026-02-19', '2026-03-25', 'Ilopop', NULL, 'Identified manufacturer', '2026-03-01 18:23:59', '2026-03-11 17:18:40', NULL),
(21, 7, 32, '1', '02', '2nd Floor near stairway', 'Bell', 'functional', '2026-02-19', '2026-03-16', NULL, NULL, NULL, '2026-03-01 18:25:07', '2026-03-01 18:25:07', NULL),
(22, 7, 33, '2', '03', 'Middle Room', 'Bell', 'functional', '2026-02-19', '2026-03-16', NULL, NULL, NULL, '2026-03-01 18:31:04', '2026-03-01 18:31:04', NULL),
(23, 7, 33, '1', '04', 'Middle Room', 'Bell', 'functional', '2026-02-19', '2026-03-16', NULL, NULL, NULL, '2026-03-01 18:32:10', '2026-03-01 18:32:10', NULL),
(24, 1, 27, 'all', 'alrm-0011', 'All Floors - At the center of the 3rd floor', 'Bell', 'functional', '2026-02-10', '2026-03-10', NULL, NULL, NULL, '2026-03-02 19:39:19', '2026-03-02 19:39:19', NULL),
(25, 8, 36, '2', '01', 'Between Room 8 and 9 and possibly 7', 'Bell', 'active', '2026-03-03', '2026-04-03', NULL, NULL, 'Okay bell', '2026-03-03 00:27:07', '2026-03-18 01:07:17', NULL),
(26, 8, 44, '1', '002', 'Room 3', 'Mechanical', 'active', '2026-03-03', '2026-04-03', NULL, NULL, NULL, '2026-03-03 00:27:59', '2026-03-03 00:28:38', NULL),
(27, 8, 47, '2', '003', 'Room 5', 'Bell', 'broken', '2026-03-03', '2026-04-03', NULL, NULL, NULL, '2026-03-03 00:29:28', '2026-03-03 00:29:28', NULL),
(28, 10, 49, '1', '01', 'Between Faculty Room and Classroom (Room 2)', 'Bell', 'broken', '2026-03-04', '2026-04-04', NULL, NULL, 'Need to purchase pull down switch, coordinate with engineering team and test asap.', '2026-03-04 22:18:14', '2026-03-04 22:18:14', NULL),
(29, 10, 52, 'all', '02', 'Near stairway at First Floor', 'Digital', 'functional', '2026-03-04', '2026-04-04', NULL, NULL, NULL, '2026-03-05 00:04:24', '2026-03-05 00:04:24', NULL),
(30, 10, 55, '1', '03', 'Between Room 3 and Room 4', 'Bell', 'functional', '2026-03-04', '2026-04-04', NULL, NULL, NULL, '2026-03-05 00:22:11', '2026-03-05 00:22:11', NULL),
(31, 4, 57, NULL, 'AlRM-001', 'great britain', 'Bell', 'active', '2026-03-11', '2026-03-26', NULL, NULL, 'Okay Alarm', '2026-03-10 22:24:23', '2026-03-10 23:05:03', NULL),
(32, 4, 58, NULL, 'ALRM-002', 'okay?', 'Mechanical', 'active', '2026-03-01', '2026-03-23', NULL, NULL, 'Good bell, no more issues', '2026-03-10 22:25:30', '2026-03-10 23:06:03', NULL),
(33, 3, 51, NULL, '11', 'Not Specified', 'Bell', 'broken', '2026-03-17', '2026-02-11', NULL, NULL, 'Alarm not functional (Fire Bells)', '2026-03-16 17:40:26', '2026-03-16 17:40:29', NULL),
(34, 3, 63, NULL, '01', 'Not Specified', 'Bell', 'active', '2026-01-08', '2026-01-14', NULL, NULL, 'Okay bell', '2026-03-17 07:05:25', '2026-03-17 07:05:25', NULL),
(35, 11, 68, '1', 'ALARM-01', 'In between the two classrooms', 'Bell', 'active', '2026-03-19', '2026-04-19', NULL, NULL, 'Working, Covering now', '2026-03-19 02:28:43', '2026-03-19 06:33:39', NULL),
(36, 11, 68, '2', 'ALARM-02', 'In between the two classrooms', 'Bell', 'active', '2026-03-19', '2026-04-19', NULL, NULL, 'Covering', '2026-03-19 02:31:10', '2026-03-19 06:47:34', NULL),
(37, 11, 68, '3', 'ALARM-03', 'In between two classrooms', 'Bell', 'active', '2026-03-19', '2026-04-19', NULL, NULL, NULL, '2026-03-19 02:32:10', '2026-03-19 02:32:35', NULL),
(39, 11, 73, NULL, 'ALRM-004', 'Covered Court Area', 'Mechanical', 'active', '2026-03-19', '2026-04-19', NULL, NULL, NULL, '2026-03-19 03:31:12', '2026-03-19 03:31:12', NULL),
(41, 1, 17, NULL, 'ALRM-003', 'Near', 'Bell', 'active', '2026-02-02', '2026-03-16', NULL, NULL, 'Okay bell very functional', '2026-03-30 01:07:57', '2026-03-30 01:07:57', NULL),
(42, 1, 74, NULL, 'ALRM-0012', '1st Floor near stairway', 'Bell', 'active', '2026-03-21', '2026-04-11', NULL, NULL, 'Okay alarm', '2026-03-30 01:29:55', '2026-03-30 01:29:55', NULL),
(43, 9, 75, NULL, '01', 'Not Specified', 'Bell', 'active', '2026-04-01', '2026-04-16', NULL, NULL, 'okay', '2026-04-07 00:28:41', '2026-04-07 00:29:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `firesafety_buildings`
--

CREATE TABLE `firesafety_buildings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unified_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `building_no` varchar(255) NOT NULL,
  `building_name` varchar(255) DEFAULT NULL,
  `floors` int(11) NOT NULL,
  `max_floors` int(11) NOT NULL DEFAULT 1,
  `rooms` int(11) NOT NULL,
  `max_rooms` int(11) NOT NULL DEFAULT 1,
  `required_extinguishers` int(11) NOT NULL DEFAULT 0,
  `year_constructed` int(11) DEFAULT NULL,
  `last_renovation` int(11) DEFAULT NULL,
  `emergency_exits` int(11) DEFAULT NULL,
  `building_type` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `safety_score` int(11) NOT NULL DEFAULT 0 COMMENT 'Building compliance score 0-100',
  `compliance_status` enum('Incomplete','Warning','Passed','Perfect') NOT NULL DEFAULT 'Incomplete' COMMENT 'Building compliance status',
  `compliance_reason` text DEFAULT NULL COMMENT 'Compliance calculation breakdown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `firesafety_buildings`
--

INSERT INTO `firesafety_buildings` (`id`, `unified_school_id`, `building_no`, `building_name`, `floors`, `max_floors`, `rooms`, `max_rooms`, `required_extinguishers`, `year_constructed`, `last_renovation`, `emergency_exits`, `building_type`, `description`, `features`, `created_at`, `updated_at`, `safety_score`, `compliance_status`, `compliance_reason`) VALUES
(17, 1, '01', '011', 1, 1, 4, 2, 5, 1995, 1996, 0, 'School Building', 'Madrasah Room, same as bldg.2', NULL, '2026-02-10 17:05:29', '2026-03-29 02:27:04', 0, 'Incomplete', NULL),
(19, 1, '02', '02', 1, 1, 3, 3, 2, 2003, 2010, 4, 'School Building', 'Single Doors, swing in grills', 'two_stairways', '2026-02-11 00:38:31', '2026-03-29 02:32:51', 95, 'Passed', NULL),
(20, 1, '03', '03', 1, 1, 2, 2, 0, 2013, 2018, 4, 'School Building', 'Single Door, Grills', NULL, '2026-02-12 18:29:24', '2026-03-29 02:32:51', 95, 'Passed', NULL),
(21, 1, '04', '04', 1, 1, 2, 2, 1, 2014, 2020, 7, 'School Building', 'Single Door , (1) canlan', NULL, '2026-02-12 18:41:24', '2026-02-12 18:41:24', 0, 'Incomplete', NULL),
(22, 1, '05', '05', 1, 1, 1, 1, 1, 2020, 2024, 6, 'School Building', 'Newest Building', NULL, '2026-02-12 18:44:00', '2026-02-12 18:44:00', 0, 'Incomplete', NULL),
(23, 1, '06', '06', 1, 1, 1, 1, 0, 2010, 2023, 4, 'School Building', NULL, NULL, '2026-02-12 18:46:47', '2026-02-12 18:46:47', 0, 'Incomplete', NULL),
(24, 1, '07', '07', 1, 1, 1, 1, 1, 2017, 2023, 2, 'School Building', NULL, NULL, '2026-02-12 18:47:51', '2026-02-12 18:47:51', 0, 'Incomplete', NULL),
(25, 1, '08', '08', 1, 1, 3, 2, 1, 2017, 2021, 2, 'School Building', 'Lightest Yellow Building', NULL, '2026-02-12 18:54:02', '2026-03-29 02:32:51', 95, 'Passed', NULL),
(26, 1, '09', '09', 1, 1, 2, 2, 1, NULL, NULL, 2, 'School Building', NULL, NULL, '2026-02-12 18:57:18', '2026-03-29 02:32:51', 95, 'Passed', NULL),
(27, 1, '10', '10', 1, 1, 3, 3, 3, 2010, 2023, 1, 'School Building', '1 Feeding Room', NULL, '2026-02-12 19:00:37', '2026-03-29 02:32:51', 95, 'Passed', NULL),
(28, 1, '11', '11', 1, 1, 2, 2, 2, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-02-12 19:05:30', '2026-03-29 02:32:51', 95, 'Passed', NULL),
(31, 2, '01', NULL, 1, 1, 1, 1, 1, NULL, NULL, 2, 'School Building', NULL, NULL, '2026-02-18 19:25:38', '2026-03-12 00:32:59', 95, 'Passed', NULL),
(32, 7, '001', '01', 2, 2, 6, 6, 3, NULL, NULL, 1, 'Administration', 'Bites the dust', 'sprinklers', '2026-03-01 18:18:34', '2026-03-11 17:23:29', 0, 'Incomplete', NULL),
(33, 7, '02', '02', 2, 2, 6, 6, 4, NULL, NULL, 1, 'School Building', NULL, NULL, '2026-03-01 18:26:48', '2026-03-01 18:26:48', 0, 'Incomplete', NULL),
(34, 7, '03', '03', 1, 1, 2, 2, 1, NULL, NULL, 0, 'Administration', NULL, NULL, '2026-03-01 18:28:36', '2026-03-01 18:28:36', 0, 'Incomplete', NULL),
(35, 7, '04', '04', 1, 1, 2, 2, 1, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-03-01 18:29:35', '2026-03-01 18:29:35', 0, 'Incomplete', NULL),
(36, 8, '01', NULL, 2, 2, 11, 6, 2, 1992, NULL, 2, 'School Building', NULL, 'exit_signs', '2026-03-02 22:56:45', '2026-03-02 23:16:49', 0, 'Incomplete', NULL),
(37, 8, '02', NULL, 1, 1, 3, 3, 2, 1979, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:06:36', '2026-03-02 23:14:07', 0, 'Incomplete', NULL),
(38, 8, '03', NULL, 1, 1, 3, 3, 2, 1980, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:10:46', '2026-03-02 23:17:35', 0, 'Incomplete', NULL),
(39, 8, '04', NULL, 1, 1, 3, 3, 3, 1992, NULL, 0, 'Administration', NULL, NULL, '2026-03-02 23:13:25', '2026-03-02 23:18:10', 0, 'Incomplete', NULL),
(40, 8, '05', NULL, 1, 1, 1, 1, 1, 1987, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:16:09', '2026-03-02 23:18:42', 0, 'Incomplete', NULL),
(41, 8, '06', NULL, 1, 1, 1, 1, 1, 1998, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:19:43', '2026-03-02 23:19:43', 0, 'Incomplete', NULL),
(42, 8, '07', NULL, 1, 1, 3, 3, 2, 1979, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:21:37', '2026-03-02 23:21:37', 0, 'Incomplete', NULL),
(43, 8, '08', NULL, 1, 1, 1, 1, 1, 1998, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:22:38', '2026-03-02 23:22:38', 0, 'Incomplete', NULL),
(44, 8, '09', NULL, 1, 1, 3, 3, 2, 1976, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:23:24', '2026-03-02 23:23:24', 0, 'Incomplete', NULL),
(45, 8, '10', NULL, 1, 1, 2, 2, 1, 2016, NULL, 0, 'School Building', NULL, NULL, '2026-03-02 23:24:16', '2026-03-02 23:24:16', 0, 'Incomplete', NULL),
(46, 8, '11', NULL, 2, 2, 4, 4, 2, 1998, NULL, 1, 'School Building', NULL, NULL, '2026-03-02 23:25:32', '2026-03-02 23:25:32', 0, 'Incomplete', NULL),
(47, 8, '12', NULL, 2, 2, 6, 6, 4, 1991, NULL, 1, 'School Building', NULL, NULL, '2026-03-02 23:26:31', '2026-03-02 23:26:31', 0, 'Incomplete', NULL),
(48, 6, '01', NULL, 1, 1, 4, 3, 2, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-03-03 22:50:34', '2026-03-17 02:34:15', 0, 'Incomplete', NULL),
(49, 10, '001', NULL, 1, 1, 2, 2, 2, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-03-04 22:14:03', '2026-03-12 01:05:57', 0, 'Incomplete', NULL),
(50, 3, '01', NULL, 1, 1, 1, 1, 1, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-03-04 22:15:30', '2026-03-04 22:15:30', 0, 'Incomplete', NULL),
(51, 3, '02', NULL, 2, 2, 4, 4, 2, NULL, NULL, 2, 'School Building', NULL, NULL, '2026-03-04 22:16:41', '2026-03-04 22:16:41', 0, 'Incomplete', NULL),
(52, 10, '002', NULL, 3, 3, 6, 6, 7, NULL, NULL, 1, 'Administration', NULL, NULL, '2026-03-04 22:19:44', '2026-03-04 22:19:44', 0, 'Incomplete', NULL),
(53, 3, '03', NULL, 1, 1, 1, 1, 1, NULL, NULL, 0, 'Administration', 'Unused Clinic', NULL, '2026-03-04 22:20:52', '2026-03-17 06:06:32', 0, 'Incomplete', NULL),
(54, 10, '003', '3', 1, 1, 2, 2, 2, NULL, NULL, 0, 'School Building', 'HE and Classroom', NULL, '2026-03-04 23:21:45', '2026-03-12 00:41:28', 0, 'Incomplete', NULL),
(55, 10, '004', NULL, 1, 1, 4, 4, 2, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-03-04 23:24:08', '2026-03-04 23:24:08', 0, 'Incomplete', NULL),
(56, 10, '005', NULL, 1, 1, 2, 2, 2, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-03-04 23:26:44', '2026-03-04 23:26:44', 0, 'Incomplete', NULL),
(57, 4, '01', 'BLDG -001', 1, 1, 3, 3, 2, 2003, 2021, NULL, 'School Building', 'Sturdy and good building', 'fire_doors,two_stairways', '2026-03-10 18:40:06', '2026-03-10 22:34:03', 0, 'Incomplete', NULL),
(58, 4, '02', 'Okay room', 2, 1, 6, 6, 4, 2009, 2022, 2, 'School Building', 'Building enough enough to handle drilling', NULL, '2026-03-10 18:51:49', '2026-03-16 17:25:10', 0, 'Incomplete', NULL),
(59, 4, '03', 'Sturdiest buildy', 1, 1, 4, 4, 2, NULL, NULL, NULL, 'School Building', NULL, NULL, '2026-03-10 18:52:33', '2026-03-10 18:52:33', 0, 'Incomplete', NULL),
(60, 5, '01', 'colored building', 1, 1, 1, 1, 1, NULL, NULL, NULL, 'School Building', '<script>Alert(\"www\");</script>', 'exit_signs', '2026-03-10 22:41:08', '2026-03-10 22:41:55', 0, 'Incomplete', NULL),
(61, 3, '04', NULL, 1, 1, 3, 3, 1, NULL, NULL, NULL, 'School Building', 'Single Door', NULL, '2026-03-17 06:07:36', '2026-03-17 06:08:42', 0, 'Incomplete', NULL),
(62, 3, '05', NULL, 1, 1, 3, 3, 1, NULL, NULL, NULL, 'School Building', 'Single Door', NULL, '2026-03-17 06:18:10', '2026-03-17 06:18:10', 0, 'Incomplete', NULL),
(63, 3, '06', NULL, 1, 1, 3, 3, 2, NULL, NULL, NULL, 'Administration', '(1) Canteen', NULL, '2026-03-17 06:20:14', '2026-03-17 06:24:32', 0, 'Incomplete', NULL),
(64, 3, '07', NULL, 1, 1, 2, 2, 1, NULL, NULL, NULL, 'School Building', 'Single Door', NULL, '2026-03-17 06:23:03', '2026-03-17 06:23:59', 0, 'Incomplete', NULL),
(65, 3, '08', NULL, 1, 1, 2, 2, 1, NULL, NULL, NULL, 'School Building', 'Single Door', NULL, '2026-03-17 06:23:44', '2026-03-17 06:23:44', 0, 'Incomplete', NULL),
(66, 3, '09', 'Fil-Chi', 1, 1, 2, 2, 1, NULL, NULL, NULL, 'Laboratory', 'Single door, not yet turned over', NULL, '2026-03-17 06:25:42', '2026-03-17 07:02:48', 0, 'Incomplete', NULL),
(68, 11, '01', '01', 3, 3, 6, 6, 4, NULL, NULL, 2, 'School Building', NULL, 'Dry Stand Pipe', '2026-03-19 02:19:06', '2026-03-19 06:29:17', 0, 'Incomplete', NULL),
(69, 11, '02', 'School Canteen', 1, 1, 1, 1, 1, NULL, NULL, NULL, 'Canteen', NULL, NULL, '2026-03-19 02:27:15', '2026-03-19 02:27:15', 0, 'Incomplete', NULL),
(70, 11, '03', 'HE Laboratory', 1, 1, 1, 1, 1, NULL, NULL, NULL, 'Laboratory', NULL, NULL, '2026-03-19 02:39:16', '2026-03-19 02:39:16', 0, 'Incomplete', NULL),
(71, 11, '04', NULL, 1, 1, 1, 1, 1, NULL, NULL, NULL, 'School Building', NULL, NULL, '2026-03-19 02:43:56', '2026-03-19 02:43:56', 0, 'Incomplete', NULL),
(72, 11, '05', NULL, 2, 2, 12, 12, 6, NULL, NULL, 2, 'School Building', NULL, NULL, '2026-03-19 02:46:46', '2026-03-19 02:46:46', 0, 'Incomplete', NULL),
(73, 11, '06', NULL, 2, 2, 14, 13, 6, NULL, NULL, 2, 'School Building', NULL, NULL, '2026-03-19 03:05:25', '2026-03-19 03:25:57', 0, 'Incomplete', NULL),
(74, 1, '044', 'Scie', 2, 2, 3, 3, 2, 1957, 1958, 2, 'School Building', 'large emmote building', 'axe', '2026-03-30 01:06:44', '2026-03-30 01:06:44', 0, 'Incomplete', NULL),
(75, 9, '01', NULL, 2, 2, 2, 2, 3, NULL, NULL, 2, 'School Building', NULL, NULL, '2026-04-07 00:23:11', '2026-04-07 00:45:51', 0, 'Incomplete', NULL),
(76, 9, '02', NULL, 1, 1, 1, 1, 2, NULL, NULL, 0, 'School Building', NULL, NULL, '2026-04-07 00:29:06', '2026-04-07 00:29:06', 0, 'Incomplete', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `firesafety_evacuationplans`
--

CREATE TABLE `firesafety_evacuationplans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unified_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `building_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plan_no` varchar(255) NOT NULL,
  `exits` text DEFAULT NULL,
  `routes` text DEFAULT NULL,
  `primary_route` text DEFAULT NULL,
  `secondary_route` text DEFAULT NULL,
  `safety_features_installed` text DEFAULT NULL,
  `areas` text DEFAULT NULL,
  `primary_assembly_area` varchar(255) DEFAULT NULL,
  `secondary_assembly_area` varchar(255) DEFAULT NULL,
  `assembly_capacity` int(11) DEFAULT NULL,
  `emergency_contacts` text DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `map_data` longtext DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `firesafety_evacuationplans`
--

INSERT INTO `firesafety_evacuationplans` (`id`, `school_id`, `unified_school_id`, `building_id`, `plan_no`, `exits`, `routes`, `primary_route`, `secondary_route`, `safety_features_installed`, `areas`, `primary_assembly_area`, `secondary_assembly_area`, `assembly_capacity`, `emergency_contacts`, `special_instructions`, `map_data`, `status`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 11, 1, NULL, 'PLAN A', '0', '1', NULL, NULL, NULL, '1', 'Main Gate Open Grounds', NULL, 300, '0960544677 Reto Jebanya', 'Make sure to guide pwd, senior and pregnant if you see one, don\'t push each other, if you\'re in front immediately go outside so that it\'ll not traffic', NULL, 'active', '2026-02-26 23:35:56', '2026-02-26 23:35:56', '2026-03-29 02:32:59'),
(2, 14, 4, NULL, 'PLAN A', '0', '1', NULL, NULL, NULL, '2', 'Main Gate Open Grounds', 'Near the second gate', 600, '099999692749 - Dolores Umbina', 'Guide students safely, priority seniors, pwd & pregnant women', NULL, 'active', '2026-03-10 22:28:48', '2026-03-10 22:28:48', '2026-03-10 22:28:48'),
(3, 13, 3, NULL, 'Plan A', '0', '1', NULL, NULL, NULL, '2', 'Okay', 'Okay kayo', 480, 'Printinciao 09006767', 'Do no let your selves to be stucked on a building updated', NULL, 'active', '2026-03-11 19:30:56', '2026-03-11 19:30:56', '2026-03-11 19:31:17'),
(4, 13, 3, 50, 'Pwede na', '0', '2', 'Okay', 'O', 'No safety features recorded', NULL, NULL, NULL, 0, NULL, NULL, NULL, 'active', '2026-03-11 19:31:48', '2026-03-11 19:31:48', '2026-03-11 19:31:48'),
(5, 12, 2, NULL, 'Olan a', '0', '1', NULL, NULL, NULL, '1', 'Okay', NULL, 50, 'Okay', 'Omay', NULL, 'active', '2026-03-12 00:32:59', '2026-03-12 00:32:59', '2026-03-12 00:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `firesafety_fire_extinguishers`
--

CREATE TABLE `firesafety_fire_extinguishers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unified_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `building_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `pressure_level` int(11) NOT NULL DEFAULT 100,
  `date_checked` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `evaluation_result` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `firesafety_fire_extinguishers`
--

INSERT INTO `firesafety_fire_extinguishers` (`id`, `unified_school_id`, `building_id`, `room_id`, `code`, `type`, `status`, `pressure_level`, `date_checked`, `remarks`, `evaluation_result`, `created_at`, `updated_at`) VALUES
(19, 1, 17, 35, 'EXT-001', 'ABC', 'active', 100, '2026-03-29', 'Okay to be installed', 'Passed', '2026-02-11 18:00:23', '2026-03-29 02:30:42'),
(20, 1, 19, 38, 'EXT-002', 'ABC', 'active', 100, '2026-02-18', 'Refill Needed', 'Passed', '2026-02-12 18:25:51', '2026-02-17 18:15:56'),
(21, 1, 20, 41, 'EXT-004', 'ABC', 'active', 100, '2026-02-13', 'Okay To be use', 'Passed', '2026-02-12 18:39:05', '2026-02-12 18:39:05'),
(22, 1, 25, 49, 'EXT-005', 'ABC', 'active', 100, '2026-03-03', NULL, 'Passed', '2026-02-12 18:55:56', '2026-03-02 18:49:50'),
(23, 1, 26, 50, 'EXT-006', 'CO2', 'active', 100, '2026-02-13', 'Okay', 'Passed', '2026-02-12 18:59:22', '2026-02-12 18:59:22'),
(24, 1, 27, 52, 'EXT-08', 'CO2', 'active', 100, '2026-02-13', 'Okay Extinguisher', 'Passed', '2026-02-12 19:02:49', '2026-02-12 19:02:49'),
(25, 1, 27, 53, 'EXT-009', 'CO2', 'active', 100, '2026-02-13', 'Okay Extinguisher', 'Passed', '2026-02-12 19:03:25', '2026-02-12 19:03:25'),
(26, 1, 27, 54, 'EXT-010', 'CO2', 'active', 100, '2026-02-13', 'Feeding Room Covered', 'Passed', '2026-02-12 19:04:34', '2026-02-12 19:04:34'),
(27, 1, 28, 55, 'EXT-008', 'ABC', 'active', 100, '2026-02-13', NULL, 'Passed', '2026-02-12 19:07:20', '2026-02-12 19:07:20'),
(28, 1, 28, 56, 'EXT-011', 'ABC', 'active', 100, '2026-03-11', NULL, 'Passed', '2026-02-12 19:09:28', '2026-03-10 17:50:10'),
(29, 2, 31, 57, 'FRXT-01', 'ABC', 'active', 100, '2026-02-19', 'Okay extinguisher', 'Passed', '2026-02-18 19:34:38', '2026-02-18 19:34:38'),
(30, 7, 32, 58, '01', 'ABC', 'maintenance', 20, '2026-02-19', 'For Preventive Maintenance', 'Failed', '2026-03-01 18:38:19', '2026-03-01 18:38:19'),
(32, 7, 32, 59, '02', 'ABC', 'maintenance', 20, '2026-02-19', 'For Preventive Maintenance', 'Failed', '2026-03-01 18:42:19', '2026-03-01 18:42:19'),
(33, 7, 33, 63, '05', 'ABC', 'active', 90, '2026-02-19', 'Newly Purchased', 'Passed', '2026-03-01 18:53:20', '2026-03-01 18:53:20'),
(34, 1, 25, 65, 'FRCT-007', 'ABC', 'active', 100, '2026-03-03', NULL, 'Passed', '2026-03-02 01:02:50', '2026-03-02 18:50:28'),
(35, 1, 19, 40, 'FRXT-04', 'ABC', 'active', 100, '2026-03-03', 'Okay', 'Passed', '2026-03-02 16:08:06', '2026-03-02 16:08:06'),
(36, 8, 36, 67, 'FE-001', 'ABC', 'decommissioned', 100, '2026-03-04', NULL, 'Failed', '2026-03-02 22:58:27', '2026-03-03 21:55:02'),
(37, 8, 37, 77, '4354', 'ABC', 'active', 100, '2026-03-04', 'Okay', 'Passed', '2026-03-03 16:48:56', '2026-03-03 16:48:56'),
(39, 10, 49, 93, '01', 'ABC', 'active', 100, '2026-03-12', 'Please hang the Fire Extinguisher', 'Passed', '2026-03-04 22:16:30', '2026-03-12 00:35:46'),
(40, 10, 49, 94, '02', 'ABC', 'active', 100, '2026-03-12', 'Please hang the unit after Preventive Maintenance', 'Passed', '2026-03-05 00:05:25', '2026-03-12 00:39:01'),
(41, 10, 52, 98, '03', 'ABC', 'active', 80, '2026-03-04', NULL, 'Passed', '2026-03-05 00:06:05', '2026-03-05 00:06:05'),
(42, 10, 52, 100, '05', 'ABC', 'active', 90, '2026-03-04', 'Hang the unit', 'Passed', '2026-03-05 00:10:15', '2026-03-05 00:10:15'),
(43, 10, 52, 99, '06', 'ABC', 'active', 100, '2026-03-12', 'Note have label and hook', 'Passed', '2026-03-05 00:11:10', '2026-03-12 00:58:41'),
(44, 10, 52, 101, '07', 'ABC', 'active', 100, '2026-03-12', 'Hang the unit and have labels', 'Passed', '2026-03-05 00:12:01', '2026-03-12 00:59:08'),
(45, 10, 52, 102, '08', 'ABC', 'active', 100, '2026-03-12', 'Hang the unit and put label and instructions', 'Passed', '2026-03-05 00:12:56', '2026-03-12 01:04:49'),
(46, 10, 54, 103, '10', 'ABC', 'active', 100, '2026-03-16', 'Hang the unit and put label and instructions', 'Passed', '2026-03-05 00:13:37', '2026-03-16 00:58:38'),
(47, 10, 54, 104, '11', 'ABC', 'active', 100, '2026-03-16', 'Hang the unit and put label and instructions', 'Passed', '2026-03-05 00:14:06', '2026-03-16 00:58:10'),
(48, 1, 19, 39, 'frxt-01', 'ABC', 'active', 100, '2026-03-06', NULL, 'Passed', '2026-03-05 17:02:09', '2026-03-05 17:02:09'),
(49, 4, 57, 111, 'FRXT-01', 'ABC', 'active', 100, '2026-03-11', 'Good to go Extinguisher', 'Passed', '2026-03-10 19:48:28', '2026-03-10 19:50:18'),
(50, 4, 57, 110, 'FRXT-02', 'ABC', 'active', 100, '2026-03-11', 'Good to go Extinguisher', 'Passed', '2026-03-10 19:49:06', '2026-03-10 19:50:41'),
(51, 4, 58, 113, 'FRXT-03', 'ABC', 'active', 100, '2026-03-11', 'Good to go Extinguisher', 'Passed', '2026-03-10 19:49:45', '2026-03-10 21:30:56'),
(52, 4, 58, 120, 'FRXT-04', 'ABC', 'active', 100, '2026-03-11', NULL, 'Passed', '2026-03-10 19:57:21', '2026-03-10 21:31:51'),
(53, 4, 58, 117, 'FRXT-05', 'ABC', 'active', 100, '2026-03-11', 'Good to go Extinguisher', 'Passed', '2026-03-10 20:01:35', '2026-03-10 21:41:24'),
(54, 4, 58, 122, 'FRXT-06', 'ABC', 'active', 100, '2026-03-11', 'Good to go Extinguisher', 'Passed', '2026-03-10 21:39:55', '2026-03-10 21:39:55'),
(55, 4, 59, 123, 'FRXT-08', 'ABC', 'active', 100, '2026-02-25', 'Good to go Extinguisher', 'Passed', '2026-03-10 21:42:21', '2026-03-10 21:42:21'),
(56, 4, 59, 125, 'FRXT-09', 'ABC', 'active', 70, '2026-03-12', 'Extinguisher ready to use', 'Passed', '2026-03-10 21:43:01', '2026-03-12 00:31:11'),
(57, 5, 60, 127, 'FRXT-01', 'ABC', 'maintenance', 57, '2026-03-19', 'Okay extinguisher', 'Failed', '2026-03-12 00:02:17', '2026-03-19 08:00:17'),
(58, 10, 55, 105, '12', 'ABC', 'active', 100, '2026-03-16', 'Purchased', 'Passed', '2026-03-12 00:47:07', '2026-03-16 00:57:36'),
(59, 10, 55, 108, '13', 'ABC', 'active', 100, '2026-03-16', 'Purchased', 'Passed', '2026-03-12 00:48:02', '2026-03-16 00:57:16'),
(60, 10, 56, 109, '14', 'ABC', 'active', 100, '2026-03-16', 'Purchased', 'Passed', '2026-03-12 00:50:12', '2026-03-16 00:53:58'),
(61, 10, 52, 97, '04', 'ABC', 'active', 100, '2026-03-12', NULL, 'Passed', '2026-03-12 00:55:29', '2026-03-12 00:59:47'),
(62, 10, 56, 109, '15', 'ABC', 'active', 100, '2026-03-16', NULL, 'Passed', '2026-03-12 01:01:18', '2026-03-16 00:53:44'),
(63, 10, 52, 97, '09', 'ABC', 'active', 100, '2026-03-16', 'Okay', 'Passed', '2026-03-13 00:21:54', '2026-03-16 00:59:00'),
(64, 3, 50, 95, '01', 'ABC', 'active', 100, '2026-03-17', 'Good Extinguisher', 'Passed', '2026-03-16 17:37:51', '2026-03-16 17:37:51'),
(65, 3, 51, 130, '03', 'ABC', 'active', 100, '2026-03-17', 'Okay Extinguisher', 'Passed', '2026-03-16 18:09:17', '2026-03-17 06:12:57'),
(66, 3, 51, 96, '02', 'ABC', 'active', 100, '2026-03-17', 'Oks', 'Passed', '2026-03-17 05:20:24', '2026-03-17 06:12:36'),
(67, 3, 61, 134, '04', 'ABC', 'active', 100, '2026-03-17', 'Okay', 'Passed', '2026-03-17 06:11:24', '2026-03-17 06:12:08'),
(68, 3, 62, 136, '05', 'ABC', 'active', 100, '2026-03-17', 'Covering multiples', 'Passed', '2026-03-17 06:39:13', '2026-03-17 06:39:13'),
(69, 3, 63, 139, '06', 'ABC', 'active', 100, '2026-03-17', 'Okay', 'Passed', '2026-03-17 06:40:03', '2026-03-17 06:41:29'),
(70, 3, 63, 140, '07', 'ABC', 'active', 100, '2026-03-17', 'Okay room', 'Passed', '2026-03-17 06:42:08', '2026-03-17 06:42:08'),
(71, 3, 64, 142, '08', 'ABC', 'active', 100, '2026-03-17', 'Okay extinguisher', 'Passed', '2026-03-17 06:45:56', '2026-03-17 06:45:56'),
(72, 3, 65, 144, '09', 'ABC', 'active', 100, '2026-03-17', 'Okay', 'Passed', '2026-03-17 07:01:01', '2026-03-17 07:01:01'),
(73, 11, 68, 148, 'FRXT-01', 'ABC', 'active', 100, '2026-03-19', NULL, 'Passed', '2026-03-19 02:23:25', '2026-03-19 02:23:25'),
(74, 11, 68, 150, 'FRXT-02', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 02:24:52', '2026-03-19 02:24:52'),
(75, 11, 68, 151, 'FRXT-03', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 02:25:42', '2026-03-19 02:25:42'),
(76, 11, 68, 152, 'FRXT-04', 'ABC', 'active', 90, '2026-03-19', NULL, 'Passed', '2026-03-19 02:26:18', '2026-03-19 02:26:18'),
(77, 11, 69, 154, 'FRXT-19', 'ABC', 'purchase', 0, '2026-03-19', NULL, 'Failed', '2026-03-19 02:35:04', '2026-03-19 03:18:41'),
(78, 11, 70, 155, 'FRXT-05', 'ABC', 'active', 80, '2026-03-19', NULL, 'Passed', '2026-03-19 02:43:11', '2026-03-19 02:43:11'),
(79, 11, 71, 156, 'FRXT-06', 'ABC', 'maintenance', 69, '2026-03-19', NULL, 'Failed', '2026-03-19 02:45:13', '2026-03-19 02:45:13'),
(80, 11, 72, 157, 'FRXT-07', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 02:55:55', '2026-03-19 02:55:55'),
(81, 11, 72, 160, 'FRXT-08', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 02:56:44', '2026-03-19 02:58:31'),
(82, 11, 72, 161, 'FRXT-09', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 02:57:25', '2026-03-19 02:57:25'),
(83, 11, 72, 163, 'FRXT-10', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 02:59:25', '2026-03-19 02:59:25'),
(84, 11, 72, 166, 'FRXT-11', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 03:00:27', '2026-03-19 03:00:27'),
(85, 11, 72, 167, 'FRXT-12', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 03:01:12', '2026-03-19 03:01:12'),
(86, 11, 73, 169, 'FRXT-13', 'ABC', 'maintenance', 20, '2026-03-19', NULL, 'Failed', '2026-03-19 03:19:38', '2026-03-19 03:19:38'),
(87, 11, 73, 172, 'FXRT-14', 'ABC', 'purchase', 0, '2026-03-19', NULL, 'Failed', '2026-03-19 03:20:56', '2026-03-19 03:20:56'),
(88, 11, 73, 174, 'FRXT-15', 'ABC', 'purchase', 0, '2026-03-19', NULL, 'Failed', '2026-03-19 03:22:00', '2026-03-19 03:22:00'),
(89, 11, 73, 176, 'FRXT-16', 'ABC', 'purchase', 0, '2026-03-19', NULL, 'Failed', '2026-03-19 03:23:05', '2026-03-19 03:23:05'),
(90, 11, 73, 178, 'FXRT-17', 'ABC', 'purchase', 0, '2026-03-19', NULL, 'Failed', '2026-03-19 03:24:09', '2026-03-19 03:24:09'),
(91, 11, 73, 181, 'FXRT-18', 'ABC', 'purchase', 0, '2026-03-19', NULL, 'Failed', '2026-03-19 03:25:10', '2026-03-19 03:25:10'),
(93, 5, 60, NULL, 'EXT-001', 'ABC', 'active', 100, '2026-03-19', NULL, 'Passed', '2026-03-19 08:01:51', '2026-03-19 08:01:51'),
(94, 1, 74, NULL, 'FRCT-0011', 'ABC', 'expired', 19, '2026-03-30', 'okay', 'Failed', '2026-03-30 06:25:12', '2026-03-30 06:25:12'),
(95, 9, 76, 184, '01', 'ABC', 'active', 100, '2026-04-07', NULL, 'Passed', '2026-04-07 00:43:43', '2026-04-07 00:43:43'),
(96, 9, 76, NULL, '02', 'ABC', 'active', 100, '2026-04-07', 'Okay Extinguisher', 'Passed', '2026-04-07 00:44:05', '2026-04-07 00:44:05'),
(97, 9, 75, 186, '03', 'ABC', 'active', 100, '2026-04-07', 'Okay', 'Passed', '2026-04-07 00:46:27', '2026-04-07 00:46:27'),
(98, 9, 75, 186, '04', 'ABC', 'active', 100, '2026-04-07', 'Okay', 'Passed', '2026-04-07 00:47:06', '2026-04-07 00:47:06'),
(100, 9, 75, 185, '06', 'ABC', 'active', 100, '2026-04-07', 'Oks', 'Passed', '2026-04-07 00:49:47', '2026-04-07 00:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `firesafety_school_snapshots`
--

CREATE TABLE `firesafety_school_snapshots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id_code` varchar(255) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `full_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`full_data`)),
  `deleted_by` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `firesafety_school_snapshots`
--

INSERT INTO `firesafety_school_snapshots` (`id`, `school_id_code`, `school_name`, `full_data`, `deleted_by`, `reason`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'SCH-0451', 'San Isidro Integrated School', '{\"id\":4,\"school_name\":\"San Isidro Integrated School\",\"evacuation_map_layout\":{\"building_9\":{\"x\":50,\"y\":50},\"building_12\":{\"x\":882,\"y\":380}},\"address\":\"San Isidro, Olongapo City, Zambales, Philippines\",\"school_id\":\"SCH-0451\",\"school_head\":\"Dr. Maria L. Fernandez\",\"school_drrm_coordinator\":\"Engr. Paolo R. Villanueva\",\"status\":\"unconfigured\",\"alerts\":[{\"id\":\"69898c5f1d3c5\",\"title\":\"Blocked Path Way\",\"description\":\"Needs to immediately fic now\",\"type\":\"danger\",\"created_at\":\"2026-02-09 07:27:27\"},{\"id\":\"698bd48cd6696\",\"title\":\"Fire Alert at Purok 7 New Cabalan\",\"description\":\"as per source it is near a public school.\",\"type\":\"warning\",\"created_at\":\"2026-02-11 00:59:56\"}],\"events\":[{\"id\":\"69898c89b000e\",\"title\":\"Annual Firing Drill\",\"description\":\"Fire Evacuation Training\",\"date\":\"2026-02-18\",\"time\":\"15:44\",\"created_at\":\"2026-02-09 07:28:09\"}],\"created_at\":\"2026-02-03T00:41:41.000000Z\",\"updated_at\":\"2026-02-11T03:53:09.000000Z\",\"buildings\":[{\"id\":9,\"school_id\":4,\"building_no\":\"BLDG-005\",\"building_name\":\"001\",\"floors\":4,\"max_floors\":3,\"rooms\":12,\"max_rooms\":10,\"required_extinguishers\":5,\"year_constructed\":1999,\"last_renovation\":2018,\"emergency_exits\":7,\"building_type\":\"classroom\",\"description\":\"Okay to evacuate\",\"features\":\"emergency_lights\",\"created_at\":\"2026-02-03T08:18:35.000000Z\",\"updated_at\":\"2026-02-11T03:04:14.000000Z\",\"actual_rooms\":[{\"id\":6,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-001\",\"room_name\":\"Filipino Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-04T05:56:01.000000Z\",\"updated_at\":\"2026-02-06T01:45:31.000000Z\",\"nearest_extinguisher_room_id\":7},{\"id\":7,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-002\",\"room_name\":\"Filipino Buillding\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-04T05:56:23.000000Z\",\"updated_at\":\"2026-02-04T05:56:23.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":8,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-003\",\"room_name\":\"Single Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-04T05:56:48.000000Z\",\"updated_at\":\"2026-02-06T01:45:46.000000Z\",\"nearest_extinguisher_room_id\":7},{\"id\":10,\"school_id\":4,\"building_id\":9,\"room_code\":\"Rom-04\",\"room_name\":\"English Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-05T02:16:48.000000Z\",\"updated_at\":\"2026-02-05T02:16:48.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":12,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-006\",\"room_name\":\"English Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-05T02:17:48.000000Z\",\"updated_at\":\"2026-02-06T01:46:29.000000Z\",\"nearest_extinguisher_room_id\":10},{\"id\":13,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-007\",\"room_name\":\"English Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":3,\"created_at\":\"2026-02-05T02:18:16.000000Z\",\"updated_at\":\"2026-02-05T02:18:16.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":14,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-02\",\"room_name\":\"Rat Lab\",\"room_type\":\"laboratory\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-05T02:45:56.000000Z\",\"updated_at\":\"2026-02-05T02:45:56.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":15,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-009\",\"room_name\":\"GMRC Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-06T00:35:09.000000Z\",\"updated_at\":\"2026-02-06T01:49:01.000000Z\",\"nearest_extinguisher_room_id\":14},{\"id\":16,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-010\",\"room_name\":\"GMRC Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":3,\"created_at\":\"2026-02-06T00:38:31.000000Z\",\"updated_at\":\"2026-02-06T05:20:14.000000Z\",\"nearest_extinguisher_room_id\":13},{\"id\":19,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-102\",\"room_name\":\"English\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":3,\"created_at\":\"2026-02-06T01:00:25.000000Z\",\"updated_at\":\"2026-02-06T05:20:26.000000Z\",\"nearest_extinguisher_room_id\":13},{\"id\":20,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-014\",\"room_name\":\"Roomer\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-06T05:21:32.000000Z\",\"updated_at\":\"2026-02-06T05:21:32.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":31,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-101\",\"room_name\":\"Department of Culturing and Science\",\"room_type\":\"department\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-09T07:35:32.000000Z\",\"updated_at\":\"2026-02-09T07:35:44.000000Z\",\"nearest_extinguisher_room_id\":10}],\"fire_extinguishers\":[{\"id\":2,\"school_id\":4,\"building_id\":9,\"room_id\":7,\"code\":\"EXT-001\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":null,\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-04T06:10:23.000000Z\",\"updated_at\":\"2026-02-06T01:16:24.000000Z\"},{\"id\":5,\"school_id\":4,\"building_id\":9,\"room_id\":10,\"code\":\"EXT-002\",\"type\":\"Dry Powder(blue)\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Okay now\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T01:16:10.000000Z\",\"updated_at\":\"2026-02-06T05:22:33.000000Z\"},{\"id\":6,\"school_id\":4,\"building_id\":9,\"room_id\":14,\"code\":\"EXT-004\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T01:48:07.000000Z\",\"updated_at\":\"2026-02-06T01:48:29.000000Z\"},{\"id\":9,\"school_id\":4,\"building_id\":9,\"room_id\":13,\"code\":\"EXT-013\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":null,\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T05:19:21.000000Z\",\"updated_at\":\"2026-02-06T05:23:13.000000Z\"}],\"alarm_systems_many\":[{\"id\":2,\"school_id\":4,\"building_id\":9,\"code\":\"ALM-004\",\"location\":\"2nd Floor - Hallway\",\"alarm_type\":\"Mechanical\",\"status\":\"decommissioned\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-18\",\"manufacturer\":\"Ilopop\",\"installation_date\":\"2026-02-05\",\"notes\":\"Loud mechanical alarm\",\"created_at\":\"2026-02-05T08:23:31.000000Z\",\"updated_at\":\"2026-02-11T08:17:20.000000Z\",\"pivot\":{\"building_id\":9,\"alarm_id\":2}},{\"id\":10,\"school_id\":4,\"building_id\":9,\"code\":\"alrm-001\",\"location\":\"All Floors - 2nd Floor near stairway\",\"alarm_type\":\"Digital\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-16\",\"manufacturer\":null,\"installation_date\":\"2026-02-12\",\"notes\":\"Okay alarm\",\"created_at\":\"2026-02-11T05:36:48.000000Z\",\"updated_at\":\"2026-02-11T08:17:36.000000Z\",\"pivot\":{\"building_id\":9,\"alarm_id\":10}},{\"id\":11,\"school_id\":4,\"building_id\":null,\"code\":\"ALRM-004\",\"location\":\"Multiple Buildings - Shared System\",\"alarm_type\":\"Bell\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-12\",\"manufacturer\":null,\"installation_date\":\"2026-02-11\",\"notes\":\"To be Installed\",\"created_at\":\"2026-02-11T06:49:00.000000Z\",\"updated_at\":\"2026-02-11T08:17:45.000000Z\",\"pivot\":{\"building_id\":9,\"alarm_id\":11}}],\"evacuation_plan\":null},{\"id\":12,\"school_id\":4,\"building_no\":\"bldg-001\",\"building_name\":\"colored building\",\"floors\":1,\"max_floors\":2,\"rooms\":2,\"max_rooms\":2,\"required_extinguishers\":2,\"year_constructed\":1988,\"last_renovation\":2019,\"emergency_exits\":7,\"building_type\":\"classroom\",\"description\":\"a colorful building build for joy\",\"features\":null,\"created_at\":\"2026-02-05T02:50:16.000000Z\",\"updated_at\":\"2026-02-10T00:58:06.000000Z\",\"actual_rooms\":[{\"id\":17,\"school_id\":4,\"building_id\":12,\"room_code\":\"Room-008\",\"room_name\":\"TECHVO room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T00:41:18.000000Z\",\"updated_at\":\"2026-02-06T00:41:18.000000Z\",\"nearest_extinguisher_room_id\":null}],\"fire_extinguishers\":[{\"id\":7,\"school_id\":4,\"building_id\":12,\"room_id\":17,\"code\":\"EXT-003\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Okay now\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T01:56:25.000000Z\",\"updated_at\":\"2026-02-06T01:56:25.000000Z\"}],\"alarm_systems_many\":[{\"id\":11,\"school_id\":4,\"building_id\":null,\"code\":\"ALRM-004\",\"location\":\"Multiple Buildings - Shared System\",\"alarm_type\":\"Bell\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-12\",\"manufacturer\":null,\"installation_date\":\"2026-02-11\",\"notes\":\"To be Installed\",\"created_at\":\"2026-02-11T06:49:00.000000Z\",\"updated_at\":\"2026-02-11T08:17:45.000000Z\",\"pivot\":{\"building_id\":12,\"alarm_id\":11}}],\"evacuation_plan\":null}],\"alarm_systems\":[{\"id\":2,\"school_id\":4,\"building_id\":9,\"code\":\"ALM-004\",\"location\":\"2nd Floor - Hallway\",\"alarm_type\":\"Mechanical\",\"status\":\"decommissioned\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-18\",\"manufacturer\":\"Ilopop\",\"installation_date\":\"2026-02-05\",\"notes\":\"Loud mechanical alarm\",\"created_at\":\"2026-02-05T08:23:31.000000Z\",\"updated_at\":\"2026-02-11T08:17:20.000000Z\"},{\"id\":10,\"school_id\":4,\"building_id\":9,\"code\":\"alrm-001\",\"location\":\"All Floors - 2nd Floor near stairway\",\"alarm_type\":\"Digital\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-16\",\"manufacturer\":null,\"installation_date\":\"2026-02-12\",\"notes\":\"Okay alarm\",\"created_at\":\"2026-02-11T05:36:48.000000Z\",\"updated_at\":\"2026-02-11T08:17:36.000000Z\"},{\"id\":11,\"school_id\":4,\"building_id\":null,\"code\":\"ALRM-004\",\"location\":\"Multiple Buildings - Shared System\",\"alarm_type\":\"Bell\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-12\",\"manufacturer\":null,\"installation_date\":\"2026-02-11\",\"notes\":\"To be Installed\",\"created_at\":\"2026-02-11T06:49:00.000000Z\",\"updated_at\":\"2026-02-11T08:17:45.000000Z\"}],\"extinguishers\":[{\"id\":2,\"school_id\":4,\"building_id\":9,\"room_id\":7,\"code\":\"EXT-001\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":null,\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-04T06:10:23.000000Z\",\"updated_at\":\"2026-02-06T01:16:24.000000Z\"},{\"id\":5,\"school_id\":4,\"building_id\":9,\"room_id\":10,\"code\":\"EXT-002\",\"type\":\"Dry Powder(blue)\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Okay now\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T01:16:10.000000Z\",\"updated_at\":\"2026-02-06T05:22:33.000000Z\"},{\"id\":6,\"school_id\":4,\"building_id\":9,\"room_id\":14,\"code\":\"EXT-004\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T01:48:07.000000Z\",\"updated_at\":\"2026-02-06T01:48:29.000000Z\"},{\"id\":7,\"school_id\":4,\"building_id\":12,\"room_id\":17,\"code\":\"EXT-003\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Okay now\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T01:56:25.000000Z\",\"updated_at\":\"2026-02-06T01:56:25.000000Z\"},{\"id\":9,\"school_id\":4,\"building_id\":9,\"room_id\":13,\"code\":\"EXT-013\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":null,\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T05:19:21.000000Z\",\"updated_at\":\"2026-02-06T05:23:13.000000Z\"}],\"evacuation_plans\":[],\"rooms\":[{\"id\":6,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-001\",\"room_name\":\"Filipino Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-04T05:56:01.000000Z\",\"updated_at\":\"2026-02-06T01:45:31.000000Z\",\"nearest_extinguisher_room_id\":7},{\"id\":7,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-002\",\"room_name\":\"Filipino Buillding\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-04T05:56:23.000000Z\",\"updated_at\":\"2026-02-04T05:56:23.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":8,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-003\",\"room_name\":\"Single Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-04T05:56:48.000000Z\",\"updated_at\":\"2026-02-06T01:45:46.000000Z\",\"nearest_extinguisher_room_id\":7},{\"id\":10,\"school_id\":4,\"building_id\":9,\"room_code\":\"Rom-04\",\"room_name\":\"English Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-05T02:16:48.000000Z\",\"updated_at\":\"2026-02-05T02:16:48.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":12,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-006\",\"room_name\":\"English Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-05T02:17:48.000000Z\",\"updated_at\":\"2026-02-06T01:46:29.000000Z\",\"nearest_extinguisher_room_id\":10},{\"id\":13,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-007\",\"room_name\":\"English Building\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":3,\"created_at\":\"2026-02-05T02:18:16.000000Z\",\"updated_at\":\"2026-02-05T02:18:16.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":14,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-02\",\"room_name\":\"Rat Lab\",\"room_type\":\"laboratory\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-05T02:45:56.000000Z\",\"updated_at\":\"2026-02-05T02:45:56.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":15,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-009\",\"room_name\":\"GMRC Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-06T00:35:09.000000Z\",\"updated_at\":\"2026-02-06T01:49:01.000000Z\",\"nearest_extinguisher_room_id\":14},{\"id\":16,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-010\",\"room_name\":\"GMRC Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":3,\"created_at\":\"2026-02-06T00:38:31.000000Z\",\"updated_at\":\"2026-02-06T05:20:14.000000Z\",\"nearest_extinguisher_room_id\":13},{\"id\":17,\"school_id\":4,\"building_id\":12,\"room_code\":\"Room-008\",\"room_name\":\"TECHVO room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T00:41:18.000000Z\",\"updated_at\":\"2026-02-06T00:41:18.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":19,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-102\",\"room_name\":\"English\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":3,\"created_at\":\"2026-02-06T01:00:25.000000Z\",\"updated_at\":\"2026-02-06T05:20:26.000000Z\",\"nearest_extinguisher_room_id\":13},{\"id\":20,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-014\",\"room_name\":\"Roomer\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-06T05:21:32.000000Z\",\"updated_at\":\"2026-02-06T05:21:32.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":31,\"school_id\":4,\"building_id\":9,\"room_code\":\"Room-101\",\"room_name\":\"Department of Culturing and Science\",\"room_type\":\"department\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-09T07:35:32.000000Z\",\"updated_at\":\"2026-02-09T07:35:44.000000Z\",\"nearest_extinguisher_room_id\":10}],\"inspections\":[],\"drills\":[]}', 'Adan Kristopher B. Dumpit', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 16:46:40', '2026-02-12 16:46:40', '2026-02-12 16:46:40'),
(2, 'SCH-0789', 'Mabini National High School', '{\"id\":5,\"school_name\":\"Mabini National High School\",\"evacuation_map_layout\":null,\"address\":\"Street, Barangay East Bajac-Bajac, Olongapo City, Zambales\",\"school_id\":\"SCH-0789\",\"school_head\":\"Mrs. Alicia T. Ramos\",\"school_drrm_coordinator\":\"Mr. Jerome C. De la Cruz\",\"status\":\"unconfigured\",\"alerts\":null,\"events\":null,\"created_at\":\"2026-02-03T00:42:48.000000Z\",\"updated_at\":\"2026-02-03T00:42:48.000000Z\",\"buildings\":[{\"id\":10,\"school_id\":5,\"building_no\":\"BLDG-003\",\"building_name\":\"Filips\",\"floors\":1,\"max_floors\":1,\"rooms\":2,\"max_rooms\":1,\"required_extinguishers\":0,\"year_constructed\":1991,\"last_renovation\":1993,\"emergency_exits\":4,\"building_type\":\"classroom\",\"description\":\"Tower Building\",\"features\":\"exit_signs\",\"created_at\":\"2026-02-04T08:02:34.000000Z\",\"updated_at\":\"2026-02-06T06:47:38.000000Z\",\"actual_rooms\":[{\"id\":25,\"school_id\":5,\"building_id\":10,\"room_code\":\"Room-101\",\"room_name\":\"Filipino Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T06:46:46.000000Z\",\"updated_at\":\"2026-02-06T06:46:46.000000Z\",\"nearest_extinguisher_room_id\":null}],\"fire_extinguishers\":[{\"id\":13,\"school_id\":5,\"building_id\":10,\"room_id\":25,\"code\":\"EXT-012\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"OKAY\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T06:48:14.000000Z\",\"updated_at\":\"2026-02-06T06:48:14.000000Z\"}],\"alarm_systems_many\":[{\"id\":5,\"school_id\":5,\"building_id\":null,\"code\":\"ALRM-001\",\"location\":\"Multiple Buildings - Shared System\",\"alarm_type\":\"Bell\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-06\",\"manufacturer\":\"IWANINI\",\"installation_date\":\"2026-02-06\",\"notes\":\"Okay ready to use\",\"created_at\":\"2026-02-06T06:32:25.000000Z\",\"updated_at\":\"2026-02-11T03:01:29.000000Z\",\"pivot\":{\"building_id\":10,\"alarm_id\":5}}],\"evacuation_plan\":null},{\"id\":14,\"school_id\":5,\"building_no\":\"BLDG-002\",\"building_name\":\"Chapel & Events Hall\",\"floors\":3,\"max_floors\":3,\"rooms\":9,\"max_rooms\":9,\"required_extinguishers\":8,\"year_constructed\":2010,\"last_renovation\":2017,\"emergency_exits\":6,\"building_type\":\"classroom\",\"description\":\"Wide assembly hall evacuation layout\",\"features\":\"two_stairways\",\"created_at\":\"2026-02-06T06:30:39.000000Z\",\"updated_at\":\"2026-02-06T06:55:49.000000Z\",\"actual_rooms\":[{\"id\":21,\"school_id\":5,\"building_id\":14,\"room_code\":\"Room-004\",\"room_name\":\"Chap-Chap Room\",\"room_type\":\"storage\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T06:41:52.000000Z\",\"updated_at\":\"2026-02-06T06:41:52.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":23,\"school_id\":5,\"building_id\":14,\"room_code\":\"Room-002\",\"room_name\":\"Chap-Chap Room 3\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T06:43:44.000000Z\",\"updated_at\":\"2026-02-06T06:43:44.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":24,\"school_id\":5,\"building_id\":14,\"room_code\":\"Room-001\",\"room_name\":\"Chapper-Room\",\"room_type\":\"department\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-06T06:44:47.000000Z\",\"updated_at\":\"2026-02-06T06:44:47.000000Z\",\"nearest_extinguisher_room_id\":null}],\"fire_extinguishers\":[{\"id\":11,\"school_id\":5,\"building_id\":14,\"room_id\":21,\"code\":\"EXT-001\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Passed\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T06:43:05.000000Z\",\"updated_at\":\"2026-02-06T06:43:05.000000Z\"},{\"id\":12,\"school_id\":5,\"building_id\":14,\"room_id\":24,\"code\":\"EXT-002\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"OKAY\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T06:45:25.000000Z\",\"updated_at\":\"2026-02-06T06:45:25.000000Z\"}],\"alarm_systems_many\":[{\"id\":5,\"school_id\":5,\"building_id\":null,\"code\":\"ALRM-001\",\"location\":\"Multiple Buildings - Shared System\",\"alarm_type\":\"Bell\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-06\",\"manufacturer\":\"IWANINI\",\"installation_date\":\"2026-02-06\",\"notes\":\"Okay ready to use\",\"created_at\":\"2026-02-06T06:32:25.000000Z\",\"updated_at\":\"2026-02-11T03:01:29.000000Z\",\"pivot\":{\"building_id\":14,\"alarm_id\":5}}],\"evacuation_plan\":null}],\"alarm_systems\":[{\"id\":5,\"school_id\":5,\"building_id\":null,\"code\":\"ALRM-001\",\"location\":\"Multiple Buildings - Shared System\",\"alarm_type\":\"Bell\",\"status\":\"active\",\"last_test\":\"2026-02-11\",\"next_test_due\":\"2026-02-06\",\"manufacturer\":\"IWANINI\",\"installation_date\":\"2026-02-06\",\"notes\":\"Okay ready to use\",\"created_at\":\"2026-02-06T06:32:25.000000Z\",\"updated_at\":\"2026-02-11T03:01:29.000000Z\"}],\"extinguishers\":[{\"id\":11,\"school_id\":5,\"building_id\":14,\"room_id\":21,\"code\":\"EXT-001\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"Passed\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T06:43:05.000000Z\",\"updated_at\":\"2026-02-06T06:43:05.000000Z\"},{\"id\":12,\"school_id\":5,\"building_id\":14,\"room_id\":24,\"code\":\"EXT-002\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"OKAY\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T06:45:25.000000Z\",\"updated_at\":\"2026-02-06T06:45:25.000000Z\"},{\"id\":13,\"school_id\":5,\"building_id\":10,\"room_id\":25,\"code\":\"EXT-012\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-06T00:00:00.000000Z\",\"remarks\":\"OKAY\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-06T06:48:14.000000Z\",\"updated_at\":\"2026-02-06T06:48:14.000000Z\"}],\"evacuation_plans\":[],\"rooms\":[{\"id\":21,\"school_id\":5,\"building_id\":14,\"room_code\":\"Room-004\",\"room_name\":\"Chap-Chap Room\",\"room_type\":\"storage\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T06:41:52.000000Z\",\"updated_at\":\"2026-02-06T06:41:52.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":23,\"school_id\":5,\"building_id\":14,\"room_code\":\"Room-002\",\"room_name\":\"Chap-Chap Room 3\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T06:43:44.000000Z\",\"updated_at\":\"2026-02-06T06:43:44.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":24,\"school_id\":5,\"building_id\":14,\"room_code\":\"Room-001\",\"room_name\":\"Chapper-Room\",\"room_type\":\"department\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-06T06:44:47.000000Z\",\"updated_at\":\"2026-02-06T06:44:47.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":25,\"school_id\":5,\"building_id\":10,\"room_code\":\"Room-101\",\"room_name\":\"Filipino Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-06T06:46:46.000000Z\",\"updated_at\":\"2026-02-06T06:46:46.000000Z\",\"nearest_extinguisher_room_id\":null}],\"inspections\":[],\"drills\":[]}', 'Adan Kristopher B. Dumpit', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 16:59:43', '2026-02-12 16:59:43', '2026-02-12 16:59:43'),
(3, 'SCH-1126', 'Rizal City Science High Schoo', '{\"id\":6,\"school_name\":\"Rizal City Science High Schoo\",\"evacuation_map_layout\":null,\"address\":\"Rizal Avenue, Barangay New Cabalan, Olongapo City, Zambales\",\"school_id\":\"SCH-1126\",\"school_head\":\"Dr. Victor M. Alonzo\",\"school_drrm_coordinator\":\"Ms. Karen F. Bautista\",\"status\":\"unconfigured\",\"alerts\":null,\"events\":null,\"created_at\":\"2026-02-04T07:57:16.000000Z\",\"updated_at\":\"2026-02-04T07:57:16.000000Z\",\"buildings\":[{\"id\":15,\"school_id\":6,\"building_no\":\"gvhg\",\"building_name\":\"Geehaun\",\"floors\":1,\"max_floors\":2,\"rooms\":3,\"max_rooms\":2,\"required_extinguishers\":3,\"year_constructed\":1999,\"last_renovation\":2010,\"emergency_exits\":4,\"building_type\":\"administrative\",\"description\":\"Sturdy and enduring building\",\"features\":\"emergency_lights\",\"created_at\":\"2026-02-10T01:00:43.000000Z\",\"updated_at\":\"2026-02-10T08:40:31.000000Z\",\"actual_rooms\":[{\"id\":32,\"school_id\":6,\"building_id\":15,\"room_code\":\"Room-010\",\"room_name\":\"Room of Fidelity\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-10T01:04:44.000000Z\",\"updated_at\":\"2026-02-10T01:04:44.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":33,\"school_id\":6,\"building_id\":15,\"room_code\":\"room010\",\"room_name\":\"room101\",\"room_type\":\"Classroom\",\"room_type_config_id\":46,\"calculated_priority_label\":\"Shared Coverage (Up to 3 Classrooms)\",\"coverage_limit\":3,\"floor_no\":1,\"created_at\":\"2026-02-10T08:40:00.000000Z\",\"updated_at\":\"2026-02-10T08:40:00.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":34,\"school_id\":6,\"building_id\":15,\"room_code\":\"Medic\",\"room_name\":\"Medic\",\"room_type\":\"Clinic\",\"room_type_config_id\":50,\"calculated_priority_label\":\"Dedicated \\/ Limited Shared\",\"coverage_limit\":2,\"floor_no\":1,\"created_at\":\"2026-02-10T08:40:52.000000Z\",\"updated_at\":\"2026-02-10T08:40:52.000000Z\",\"nearest_extinguisher_room_id\":null}],\"fire_extinguishers\":[{\"id\":16,\"school_id\":6,\"building_id\":15,\"room_id\":32,\"code\":\"Ext-11\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-10T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-10T08:39:35.000000Z\",\"updated_at\":\"2026-02-10T08:39:35.000000Z\"},{\"id\":17,\"school_id\":6,\"building_id\":15,\"room_id\":34,\"code\":\"EXT-003\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-10T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-10T08:41:09.000000Z\",\"updated_at\":\"2026-02-10T08:41:09.000000Z\"},{\"id\":18,\"school_id\":6,\"building_id\":15,\"room_id\":33,\"code\":\"EXT-004\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-10T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-10T08:41:43.000000Z\",\"updated_at\":\"2026-02-10T08:41:43.000000Z\"}],\"alarm_systems_many\":[],\"evacuation_plan\":null}],\"alarm_systems\":[],\"extinguishers\":[{\"id\":16,\"school_id\":6,\"building_id\":15,\"room_id\":32,\"code\":\"Ext-11\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-10T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-10T08:39:35.000000Z\",\"updated_at\":\"2026-02-10T08:39:35.000000Z\"},{\"id\":17,\"school_id\":6,\"building_id\":15,\"room_id\":34,\"code\":\"EXT-003\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-10T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-10T08:41:09.000000Z\",\"updated_at\":\"2026-02-10T08:41:09.000000Z\"},{\"id\":18,\"school_id\":6,\"building_id\":15,\"room_id\":33,\"code\":\"EXT-004\",\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"date_checked\":\"2026-02-10T00:00:00.000000Z\",\"remarks\":\"Okay\",\"evaluation_result\":\"Passed\",\"created_at\":\"2026-02-10T08:41:43.000000Z\",\"updated_at\":\"2026-02-10T08:41:43.000000Z\"}],\"evacuation_plans\":[],\"rooms\":[{\"id\":32,\"school_id\":6,\"building_id\":15,\"room_code\":\"Room-010\",\"room_name\":\"Room of Fidelity\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-10T01:04:44.000000Z\",\"updated_at\":\"2026-02-10T01:04:44.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":33,\"school_id\":6,\"building_id\":15,\"room_code\":\"room010\",\"room_name\":\"room101\",\"room_type\":\"Classroom\",\"room_type_config_id\":46,\"calculated_priority_label\":\"Shared Coverage (Up to 3 Classrooms)\",\"coverage_limit\":3,\"floor_no\":1,\"created_at\":\"2026-02-10T08:40:00.000000Z\",\"updated_at\":\"2026-02-10T08:40:00.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":34,\"school_id\":6,\"building_id\":15,\"room_code\":\"Medic\",\"room_name\":\"Medic\",\"room_type\":\"Clinic\",\"room_type_config_id\":50,\"calculated_priority_label\":\"Dedicated \\/ Limited Shared\",\"coverage_limit\":2,\"floor_no\":1,\"created_at\":\"2026-02-10T08:40:52.000000Z\",\"updated_at\":\"2026-02-10T08:40:52.000000Z\",\"nearest_extinguisher_room_id\":null}],\"inspections\":[],\"drills\":[]}', 'Adan Kristopher B. Dumpit', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 17:01:02', '2026-02-12 17:01:02', '2026-02-12 17:01:02'),
(4, 'SCH-2101', 'West Ridge Secondary School', '{\"id\":7,\"school_name\":\"West Ridge Secondary School\",\"evacuation_map_layout\":null,\"address\":\"Sitio Maligaya, Castillejos, Zambales\",\"school_id\":\"SCH-2101\",\"school_head\":\"Mr. Eduardo N. Salazares\",\"school_drrm_coordinator\":\"Ms. Liza A. Romero\",\"status\":\"unconfigured\",\"alerts\":null,\"events\":null,\"created_at\":\"2026-02-05T05:42:48.000000Z\",\"updated_at\":\"2026-02-10T07:55:07.000000Z\",\"buildings\":[{\"id\":13,\"school_id\":7,\"building_no\":\"BLDG=002\",\"building_name\":\"The Building\",\"floors\":4,\"max_floors\":3,\"rooms\":4,\"max_rooms\":2,\"required_extinguishers\":6,\"year_constructed\":1999,\"last_renovation\":2023,\"emergency_exits\":6,\"building_type\":\"classroom\",\"description\":\"Need to action immediately if arson happens\",\"features\":\"two_stairways\",\"created_at\":\"2026-02-06T01:07:12.000000Z\",\"updated_at\":\"2026-02-09T08:29:52.000000Z\",\"actual_rooms\":[{\"id\":26,\"school_id\":7,\"building_id\":13,\"room_code\":\"Room-002\",\"room_name\":\"Resting Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-09T03:20:08.000000Z\",\"updated_at\":\"2026-02-09T07:54:41.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":27,\"school_id\":7,\"building_id\":13,\"room_code\":\"Room-003\",\"room_name\":\"Storage Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-09T05:40:59.000000Z\",\"updated_at\":\"2026-02-09T05:40:59.000000Z\",\"nearest_extinguisher_room_id\":null}],\"fire_extinguishers\":[],\"alarm_systems_many\":[],\"evacuation_plan\":null},{\"id\":18,\"school_id\":7,\"building_no\":\"BLDG-002\",\"building_name\":\"Second Building\",\"floors\":3,\"max_floors\":3,\"rooms\":6,\"max_rooms\":6,\"required_extinguishers\":7,\"year_constructed\":2002,\"last_renovation\":2024,\"emergency_exits\":5,\"building_type\":\"School Building\",\"description\":\"Sturdy Building Enough to last at century\",\"features\":null,\"created_at\":\"2026-02-11T03:59:46.000000Z\",\"updated_at\":\"2026-02-11T03:59:46.000000Z\",\"actual_rooms\":[],\"fire_extinguishers\":[],\"alarm_systems_many\":[],\"evacuation_plan\":null}],\"alarm_systems\":[],\"extinguishers\":[],\"evacuation_plans\":[],\"rooms\":[{\"id\":26,\"school_id\":7,\"building_id\":13,\"room_code\":\"Room-002\",\"room_name\":\"Resting Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":1,\"created_at\":\"2026-02-09T03:20:08.000000Z\",\"updated_at\":\"2026-02-09T07:54:41.000000Z\",\"nearest_extinguisher_room_id\":null},{\"id\":27,\"school_id\":7,\"building_id\":13,\"room_code\":\"Room-003\",\"room_name\":\"Storage Room\",\"room_type\":\"classroom\",\"room_type_config_id\":null,\"calculated_priority_label\":null,\"coverage_limit\":null,\"floor_no\":2,\"created_at\":\"2026-02-09T05:40:59.000000Z\",\"updated_at\":\"2026-02-09T05:40:59.000000Z\",\"nearest_extinguisher_room_id\":null}],\"inspections\":[],\"drills\":[]}', 'Adan Kristopher B. Dumpit', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 17:01:12', '2026-02-12 17:01:12', '2026-02-12 17:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `fire_safety_alarm_building`
--

CREATE TABLE `fire_safety_alarm_building` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alarm_id` bigint(20) UNSIGNED NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fire_safety_alarm_building`
--

INSERT INTO `fire_safety_alarm_building` (`id`, `alarm_id`, `building_id`, `created_at`, `updated_at`) VALUES
(15, 13, 19, NULL, NULL),
(17, 15, 24, NULL, NULL),
(25, 19, 31, NULL, NULL),
(26, 20, 32, NULL, NULL),
(27, 21, 32, NULL, NULL),
(28, 22, 33, NULL, NULL),
(29, 23, 33, NULL, NULL),
(30, 24, 28, NULL, NULL),
(31, 25, 36, NULL, NULL),
(32, 26, 44, NULL, NULL),
(33, 27, 47, NULL, NULL),
(34, 28, 49, NULL, NULL),
(35, 29, 54, NULL, NULL),
(36, 30, 55, NULL, NULL),
(37, 31, 59, NULL, NULL),
(38, 32, 58, NULL, NULL),
(39, 33, 51, NULL, NULL),
(40, 34, 63, NULL, NULL),
(41, 35, 68, NULL, NULL),
(42, 36, 68, NULL, NULL),
(43, 37, 68, NULL, NULL),
(45, 39, 72, NULL, NULL),
(49, 41, 19, NULL, NULL),
(50, 41, 22, NULL, NULL),
(51, 42, 26, NULL, NULL),
(52, 43, 75, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fire_safety_archives`
--

CREATE TABLE `fire_safety_archives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unified_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `item_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`item_data`)),
  `reason` text DEFAULT NULL,
  `removed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fire_safety_archives`
--

INSERT INTO `fire_safety_archives` (`id`, `unified_school_id`, `type`, `item_id`, `item_code`, `item_data`, `reason`, `removed_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'room', 22, 'Room-003', '{\"room_name\":\"Chap-Chap Room 2\",\"room_type\":\"classroom\",\"floor_no\":1,\"building_name\":\"Chapel & Events Hall\"}', 'Mistaken floor', '2026-02-05 22:55:49', '2026-02-05 22:55:49', '2026-02-05 22:55:49'),
(2, NULL, 'extinguisher', 10, 'EXT', '{\"type\":\"ABC\",\"status\":\"decommissioned\",\"pressure_level\":100,\"building_name\":\"001\",\"floor_no\":2,\"room_name\":\"Roomer\"}', 'Removal of Extinguisher', '2026-02-08 19:19:04', '2026-02-08 19:19:04', '2026-02-08 19:19:04'),
(3, NULL, 'extinguisher', 14, 'EXT-001', '{\"type\":\"ABC\",\"status\":\"decommissioned\",\"pressure_level\":100,\"building_name\":\"The Building\",\"floor_no\":1,\"room_name\":\"Resting Room\"}', 'Removal of Fire Extinguisher', '2026-02-08 19:21:02', '2026-02-08 19:21:02', '2026-02-08 19:21:02'),
(4, NULL, 'floor', NULL, 'FLR-4', '{\"building_name\":\"The Building\",\"building_no\":\"BLDG=002\",\"floor_no\":\"4\"}', 'There isn\'t a fourth floor', '2026-02-08 21:43:48', '2026-02-08 21:43:48', '2026-02-08 21:43:48'),
(5, NULL, 'room', 29, 'Room-06', '{\"room_name\":\"Great Britain Room\",\"room_type\":\"department\",\"floor_no\":4,\"building_name\":\"The Building\"}', 'Cascading removal: Floor 4 removed. Reason: There isn\'t a fourth floor', '2026-02-08 21:43:48', '2026-02-08 21:43:48', '2026-02-08 21:43:48'),
(6, NULL, 'room', 28, 'Room-002', '{\"room_name\":\"Gracias Room\",\"room_type\":\"classroom\",\"floor_no\":3,\"building_name\":\"The Building\"}', 'Edit its not gracias room its a clinic room and add one class room', '2026-02-08 21:43:48', '2026-02-08 21:43:48', '2026-02-08 21:43:48'),
(7, NULL, 'floor', NULL, 'FLR-4', '{\"building_name\":\"The Building\",\"building_no\":\"BLDG=002\",\"floor_no\":\"4\"}', 'There isn\'t a 4th floor again', '2026-02-08 21:48:51', '2026-02-08 21:48:51', '2026-02-08 21:48:51'),
(8, NULL, 'floor', NULL, 'FLR-4', '{\"building_name\":\"The Building\",\"building_no\":\"BLDG=002\",\"floor_no\":\"4\"}', 'Bell Removal', '2026-02-08 21:51:34', '2026-02-08 21:51:34', '2026-02-08 21:51:34'),
(9, NULL, 'floor', NULL, 'FLR-4', '{\"building_name\":\"The Building\",\"building_no\":\"BLDG=002\",\"floor_no\":\"4\"}', 'Removal of all bell, extinguisher and even rooms', '2026-02-08 21:53:16', '2026-02-08 21:53:16', '2026-02-08 21:53:16'),
(10, NULL, 'room', 30, 'Room-03', '{\"room_name\":\"Britain\",\"room_type\":\"classroom\",\"floor_no\":4,\"building_name\":\"The Building\"}', 'Cascading removal: Floor 4 removed. Reason: Removal of all bell, extinguisher and even rooms', '2026-02-08 21:53:16', '2026-02-08 21:53:16', '2026-02-08 21:53:16'),
(11, NULL, 'extinguisher', 15, 'EXT-008', '{\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"building_name\":\"The Building\",\"floor_no\":4,\"room_name\":\"Britain\",\"cascaded_from\":\"Room Britain Removal\"}', 'Cascading removal: Room Britain removed. Reason: Cascading removal: Floor 4 removed. Reason: Removal of all bell, extinguisher and even rooms', '2026-02-08 21:53:16', '2026-02-08 21:53:16', '2026-02-08 21:53:16'),
(12, NULL, 'room', 11, 'Rom-01', '{\"room_name\":\"English Building\",\"room_type\":\"classroom\",\"floor_no\":1,\"building_name\":\"001\"}', 'English Building updated to Administrative type of room', '2026-02-08 23:34:22', '2026-02-08 23:34:22', '2026-02-08 23:34:22'),
(13, NULL, 'floor', NULL, 'FLR-4', '{\"building_name\":\"The Building\",\"building_no\":\"BLDG=002\",\"floor_no\":\"4\"}', 'Removal of floor', '2026-02-09 00:29:52', '2026-02-09 00:29:52', '2026-02-09 00:29:52'),
(14, NULL, 'alarm', 6, 'ALM-002', '{\"alarm_type\":\"Bell\",\"status\":\"functional\",\"building_name\":\"The Building\",\"manufacturer\":null,\"last_test\":\"2026-02-09\",\"cascaded_from\":\"Floor 4 Removal\"}', 'Cascading removal: Floor 4 removed. Reason: Removal of floor', '2026-02-09 00:29:52', '2026-02-09 00:29:52', '2026-02-09 00:29:52'),
(15, NULL, 'floor', NULL, 'FLR-2', '{\"building_name\":\"colored building\",\"building_no\":\"bldg-001\",\"floor_no\":\"2\"}', 'Removal', '2026-02-09 16:58:06', '2026-02-09 16:58:06', '2026-02-09 16:58:06'),
(16, NULL, 'room', 18, 'Room-011', '{\"room_name\":\"Regent\",\"room_type\":\"clinic\",\"floor_no\":2,\"building_name\":\"colored building\"}', 'Cascading removal: Floor 2 removed. Reason: Removal', '2026-02-09 16:58:06', '2026-02-09 16:58:06', '2026-02-09 16:58:06'),
(17, NULL, 'extinguisher', 8, 'EXT-005', '{\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"building_name\":\"colored building\",\"floor_no\":2,\"room_name\":\"Regent\",\"cascaded_from\":\"Room Regent Removal\"}', 'Cascading removal: Room Regent removed. Reason: Cascading removal: Floor 2 removed. Reason: Removal', '2026-02-09 16:58:06', '2026-02-09 16:58:06', '2026-02-09 16:58:06'),
(18, NULL, 'floor', NULL, 'FLR-2', '{\"building_name\":\"Geehaun\",\"building_no\":\"gvhg\",\"floor_no\":\"2\"}', 'Tangalin?', '2026-02-09 17:01:10', '2026-02-09 17:01:10', '2026-02-09 17:01:10'),
(19, NULL, 'floor', NULL, 'FLR-1', '{\"building_name\":\"Geehaun\",\"building_no\":\"gvhg\",\"floor_no\":\"1\"}', 'Removal of building', '2026-02-09 17:03:57', '2026-02-09 17:03:57', '2026-02-09 17:03:57'),
(20, NULL, 'alarm', 4, 'ALRM-008', '{\"alarm_type\":\"Bell\",\"status\":\"active\",\"building_name\":\"001\",\"manufacturer\":null,\"last_test\":\"2026-02-09\"}', 'I don\'t need this, it\'ll be now decommissioned', '2026-02-09 18:38:22', '2026-02-09 18:38:22', '2026-02-09 18:38:22'),
(27, NULL, 'alarm', 8, 'ALM-0011', '{\"alarm_type\":\"Bell\",\"status\":\"active\",\"building_name\":\"N\\/A\",\"manufacturer\":\"Hochiki\",\"last_test\":\"2026-02-11\"}', 'I\'ve hated it', '2026-02-10 21:33:48', '2026-02-10 21:33:48', '2026-02-10 21:33:48'),
(28, NULL, 'alarm', 3, 'ALRM-004', '{\"alarm_type\":\"Bell\",\"status\":\"active\",\"building_name\":\"colored building\",\"manufacturer\":\"Hochiki\",\"last_test\":\"2026-02-06\"}', 'Decommisioned', '2026-02-10 22:47:21', '2026-02-10 22:47:21', '2026-02-10 22:47:21'),
(29, 1, 'alarm', 9, 'Alrms-001', '{\"alarm_type\":\"Digital\",\"status\":\"active\",\"building_name\":\"N\\/A\",\"manufacturer\":null,\"last_test\":\"2026-02-11\"}', 'Its not digital its only bell', '2026-02-11 00:40:04', '2026-02-11 00:40:04', '2026-02-11 00:40:04'),
(30, NULL, 'school_deletion', 4, 'SCH-0451', '{\"school_name\":\"San Isidro Integrated School\",\"school_code\":\"SCH-0451\",\"school_head\":\"Dr. Maria L. Fernandez\",\"drrm_coordinator\":\"Engr. Paolo R. Villanueva\",\"address\":\"San Isidro, Olongapo City, Zambales, Philippines\",\"buildings\":2,\"alarm_systems\":3,\"extinguishers\":5,\"evacuation_plans\":0,\"evacuation_coverage_status\":\"poor\"}', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 16:46:40', '2026-02-12 16:46:40', '2026-02-12 16:46:40'),
(31, NULL, 'school_deletion', 5, 'SCH-0789', '{\"school_name\":\"Mabini National High School\",\"school_code\":\"SCH-0789\",\"school_head\":\"Mrs. Alicia T. Ramos\",\"drrm_coordinator\":\"Mr. Jerome C. De la Cruz\",\"address\":\"Street, Barangay East Bajac-Bajac, Olongapo City, Zambales\",\"buildings\":2,\"alarm_systems\":1,\"extinguishers\":3,\"evacuation_plans\":0,\"evacuation_coverage_status\":\"poor\"}', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 16:59:43', '2026-02-12 16:59:43', '2026-02-12 16:59:43'),
(32, NULL, 'school_deletion', 6, 'SCH-1126', '{\"school_name\":\"Rizal City Science High Schoo\",\"school_code\":\"SCH-1126\",\"school_head\":\"Dr. Victor M. Alonzo\",\"drrm_coordinator\":\"Ms. Karen F. Bautista\",\"address\":\"Rizal Avenue, Barangay New Cabalan, Olongapo City, Zambales\",\"buildings\":1,\"alarm_systems\":0,\"extinguishers\":3,\"evacuation_plans\":0,\"evacuation_coverage_status\":\"poor\"}', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 17:01:02', '2026-02-12 17:01:02', '2026-02-12 17:01:02'),
(33, NULL, 'school_deletion', 7, 'SCH-2101', '{\"school_name\":\"West Ridge Secondary School\",\"school_code\":\"SCH-2101\",\"school_head\":\"Mr. Eduardo N. Salazares\",\"drrm_coordinator\":\"Ms. Liza A. Romero\",\"address\":\"Sitio Maligaya, Castillejos, Zambales\",\"buildings\":2,\"alarm_systems\":0,\"extinguishers\":0,\"evacuation_plans\":0,\"evacuation_coverage_status\":\"poor\"}', 'School deleted by Adan Kristopher B. Dumpit', '2026-02-12 17:01:12', '2026-02-12 17:01:12', '2026-02-12 17:01:12'),
(35, 1, 'alarm', 16, 'ALRM-0014', '{\"alarm_type\":\"Bell\",\"status\":\"functional\",\"building_name\":\"N\\/A\",\"manufacturer\":null,\"last_test\":\"2026-02-16\"}', 'Alarm didn\'t exist at first place', '2026-02-15 18:09:00', '2026-02-15 18:09:00', '2026-02-15 18:09:00'),
(36, 1, 'alarm', 17, 'ALRM-008', '{\"alarm_type\":\"Bell\",\"status\":\"missing\",\"building_name\":\"N\\/A\",\"manufacturer\":null,\"last_test\":\"2026-02-16\"}', 'Alarm didn\'t even existed', '2026-02-15 22:16:12', '2026-02-15 22:16:12', '2026-02-15 22:16:12'),
(37, 1, 'alarm', 18, 'ALRM-008', '{\"alarm_type\":\"Bell\",\"status\":\"functional\",\"building_name\":\"06\",\"manufacturer\":null,\"last_test\":\"2026-02-17\"}', 'To be removed', '2026-02-17 21:27:12', '2026-02-17 21:27:12', '2026-02-17 21:27:12'),
(38, 7, 'extinguisher', 31, '02', '{\"type\":\"ABC\",\"status\":\"maintenance\",\"pressure_level\":20,\"building_name\":\"01\",\"floor_no\":1,\"room_name\":\"05\"}', 'Wrong Code', '2026-03-01 18:41:12', '2026-03-01 18:41:12', '2026-03-01 18:41:12'),
(39, 1, 'floor', NULL, 'FLR-2', '{\"building_name\":\"014\",\"building_no\":\"014\",\"floor_no\":\"2\"}', 'Ayoko na', '2026-03-03 01:06:56', '2026-03-03 01:06:56', '2026-03-03 01:06:56'),
(40, 8, 'extinguisher', 38, 'FRXT-01', '{\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"building_name\":\"N\\/A\",\"floor_no\":1,\"room_name\":\"24\"}', 'Wrong installment', '2026-03-03 21:47:20', '2026-03-03 21:47:20', '2026-03-03 21:47:20'),
(41, 4, 'room', 115, '01', '{\"room_name\":\"Grade 5 Classroom\",\"room_type\":\"Classroom\",\"floor_no\":1,\"building_name\":\"Okay room\"}', 'I don\'t like it', '2026-03-10 19:34:55', '2026-03-10 19:34:55', '2026-03-10 19:34:55'),
(42, 4, 'room', 114, 'LB-01', '{\"room_name\":\"Science Laboratory\",\"room_type\":\"Laboratory\",\"floor_no\":2,\"building_name\":\"Okay room\"}', 'This too', '2026-03-10 19:35:10', '2026-03-10 19:35:10', '2026-03-10 19:35:10'),
(43, 4, 'room', 118, '4354', '{\"room_name\":\"65546\",\"room_type\":\"Administration\",\"floor_no\":1,\"building_name\":\"Okay room\"}', 'Wrong floor encoded', '2026-03-10 19:38:57', '2026-03-10 19:38:57', '2026-03-10 19:38:57'),
(44, 4, 'room', 119, 'te6t54', '{\"room_name\":\"56546\",\"room_type\":\"Classroom\",\"floor_no\":1,\"building_name\":\"Okay room\"}', 'Wrong floor encoded', '2026-03-10 19:39:09', '2026-03-10 19:39:09', '2026-03-10 19:39:09'),
(45, 3, 'room', 129, '03', '{\"room_name\":\"ffrg\",\"room_type\":\"Classroom\",\"floor_no\":1,\"building_name\":\"02\"}', 'Wrong room', '2026-03-16 17:53:54', '2026-03-16 17:53:54', '2026-03-16 17:53:54'),
(46, 1, 'building', NULL, '014', '{\"building_name\":\"014\",\"type\":\"School Building\",\"required_fext\":2,\"year_constructed\":1994,\"last_renovation\":2025,\"description\":null,\"safety_features\":null}', 'To be removed, building doesn\'t exist at first place', '2026-03-17 07:51:17', '2026-03-17 07:51:17', '2026-03-17 07:51:17'),
(47, 8, 'building', NULL, '099', '{\"building_name\":null,\"type\":\"Reggiestar building\",\"required_fext\":1,\"year_constructed\":null,\"last_renovation\":null,\"description\":null,\"safety_features\":null}', 'Testing building', '2026-03-18 01:37:05', '2026-03-18 01:37:05', '2026-03-18 01:37:05'),
(48, 11, 'alarm', 38, 'ALARM-04', '{\"alarm_type\":\"Mechanical\",\"status\":\"active\",\"building_name\":\"N\\/A\",\"manufacturer\":null,\"last_test\":\"2026-03-19\"}', 'Wrong Entry', '2026-03-19 03:29:41', '2026-03-19 03:29:41', '2026-03-19 03:29:41'),
(49, 11, 'alarm', 40, 'Alrm-001', '{\"alarm_type\":\"Mechanical\",\"status\":\"active\",\"building_name\":\"School Canteen\",\"manufacturer\":null,\"last_test\":\"2026-03-18\"}', 'Wrong alarm installed', '2026-03-19 06:46:53', '2026-03-19 06:46:53', '2026-03-19 06:46:53'),
(50, 5, 'extinguisher', 92, 'fdd', '{\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"building_name\":\"colored building\",\"floor_no\":\"N\\/A\",\"room_name\":\"N\\/A\"}', 'Testing something', '2026-03-19 08:01:00', '2026-03-19 08:01:00', '2026-03-19 08:01:00'),
(51, 1, 'alarm', 14, 'ALRM-003', '{\"alarm_type\":\"Bell\",\"status\":\"broken\",\"building_name\":\"N\\/A\",\"manufacturer\":\"curuosh\",\"last_test\":\"2026-02-16\"}', 'To be re-assign yet again', '2026-03-29 01:14:05', '2026-03-29 01:14:05', '2026-03-29 01:14:05'),
(52, 1, 'floor', NULL, 'FLR-2', '{\"building_name\":\"011\",\"building_no\":\"011\",\"floor_no\":\"2\"}', 'there isn\'t 2nd floor', '2026-03-29 02:26:32', '2026-03-29 02:26:32', '2026-03-29 02:26:32'),
(53, 9, 'extinguisher', 99, '022', '{\"type\":\"ABC\",\"status\":\"active\",\"pressure_level\":100,\"building_name\":\"N\\/A\",\"floor_no\":\"N\\/A\",\"room_name\":\"N\\/A\"}', 'Didn\'t exist', '2026-04-07 00:50:39', '2026-04-07 00:50:39', '2026-04-07 00:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `fire_safety_extinguisher_inspections`
--

CREATE TABLE `fire_safety_extinguisher_inspections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `extinguisher_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inspection_date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `pressure_level` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fire_safety_extinguisher_inspections`
--

INSERT INTO `fire_safety_extinguisher_inspections` (`id`, `extinguisher_id`, `user_id`, `inspection_date`, `status`, `pressure_level`, `notes`, `created_at`, `updated_at`) VALUES
(42, 20, 1, '2026-02-18', 'active', '100', 'Okay now', '2026-02-17 18:15:56', '2026-02-17 18:15:56'),
(43, 34, 1, '2026-03-03', 'active', '100', 'Covering the unassigned extinguisher that room hasn\'t been installed with', '2026-03-02 18:39:58', '2026-03-02 18:39:58'),
(44, 22, 1, '2026-03-03', 'active', '100', 'okay', '2026-03-02 18:49:18', '2026-03-02 18:49:18'),
(45, 22, 1, '2026-03-03', 'active', '100', 'huk', '2026-03-02 18:49:33', '2026-03-02 18:49:33'),
(46, 22, 1, '2026-03-03', 'active', '100', 'Okay na', '2026-03-02 18:49:50', '2026-03-02 18:49:50'),
(47, 34, 1, '2026-03-03', 'active', '100', 'huh!', '2026-03-02 18:50:09', '2026-03-02 18:50:09'),
(48, 34, 1, '2026-03-03', 'active', '100', 'Okay to use', '2026-03-02 18:50:28', '2026-03-02 18:50:28'),
(49, 36, 1, '2026-03-04', 'active', '100', 'Ready to use', '2026-03-03 16:37:18', '2026-03-03 16:37:18'),
(50, 36, 1, '2026-03-04', 'active', '100', 'Position moved', '2026-03-03 16:51:57', '2026-03-03 16:51:57'),
(51, 36, 1, '2026-03-04', 'active', '100', 'Okay assignment', '2026-03-03 18:14:48', '2026-03-03 18:14:48'),
(52, 36, 1, '2026-03-04', 'decommissioned', '100', 'Okay?', '2026-03-03 18:19:00', '2026-03-03 18:19:00'),
(53, 36, 1, '2026-03-04', 'decommissioned', '100', 'Coverage', '2026-03-03 21:55:03', '2026-03-03 21:55:03'),
(54, 19, 1, '2026-03-05', 'active', '100', 'Okay now', '2026-03-05 00:05:32', '2026-03-05 00:05:32'),
(55, 46, 5, '2026-03-05', 'purchase', '0', 'Wrong Number', '2026-03-05 00:16:25', '2026-03-05 00:16:25'),
(56, 28, 1, '2026-03-11', 'active', '100', 'Okay assign a room', '2026-03-10 17:50:10', '2026-03-10 17:50:10'),
(57, 49, 3, '2026-03-11', 'active', '100', 'Good to go Extinguisher', '2026-03-10 19:50:19', '2026-03-10 19:50:19'),
(58, 50, 3, '2026-03-11', 'active', '100', 'Good to go Extinguisher', '2026-03-10 19:50:41', '2026-03-10 19:50:41'),
(59, 50, 3, '2026-03-11', 'active', '100', 'Good to go Extinguisher', '2026-03-10 19:50:41', '2026-03-10 19:50:41'),
(60, 51, 3, '2026-03-11', 'active', '100', 'Room coverage', '2026-03-10 20:02:40', '2026-03-10 20:02:40'),
(61, 52, 3, '2026-03-11', 'active', '100', 'Okay', '2026-03-10 20:03:45', '2026-03-10 20:03:45'),
(62, 52, 3, '2026-03-11', 'active', '100', 'Okay', '2026-03-10 20:03:45', '2026-03-10 20:03:45'),
(63, 51, 1, '2026-03-11', 'active', '100', 'Okay', '2026-03-10 21:29:41', '2026-03-10 21:29:41'),
(64, 51, 1, '2026-03-11', 'active', '100', 'Okay', '2026-03-10 21:30:56', '2026-03-10 21:30:56'),
(65, 51, 1, '2026-03-11', 'active', '100', 'Okay', '2026-03-10 21:30:56', '2026-03-10 21:30:56'),
(66, 52, 1, '2026-03-11', 'active', '100', 'Okay', '2026-03-10 21:31:51', '2026-03-10 21:31:51'),
(67, 53, 3, '2026-03-11', 'active', '100', 'Two for one', '2026-03-10 21:41:24', '2026-03-10 21:41:24'),
(68, 56, 3, '2026-03-11', 'active', '100', 'Okay to use', '2026-03-10 21:44:51', '2026-03-10 21:44:51'),
(69, 56, 1, '2026-03-11', 'active', '71', 'Used a little bit', '2026-03-10 21:46:27', '2026-03-10 21:46:27'),
(70, 56, 1, '2026-03-11', 'maintenance', '69', 'reduced', '2026-03-10 21:47:40', '2026-03-10 21:47:40'),
(71, 56, 1, '2026-03-11', 'maintenance', '50', 'Reduced again', '2026-03-10 22:07:51', '2026-03-10 22:07:51'),
(72, 56, 1, '2026-03-11', 'maintenance', '50', 'Reduced again', '2026-03-10 22:07:51', '2026-03-10 22:07:51'),
(73, 56, 1, '2026-03-11', 'active', '100', 'Refilled', '2026-03-10 22:22:56', '2026-03-10 22:22:56'),
(74, 56, 3, '2026-03-12', 'maintenance', '60', 'It was used', '2026-03-11 17:08:52', '2026-03-11 17:08:52'),
(75, 56, 3, '2026-03-12', 'maintenance', '60', 'It was used', '2026-03-11 17:08:53', '2026-03-11 17:08:53'),
(78, 57, 1, '2026-03-12', 'maintenance', '59', 'Was used now', '2026-03-12 00:03:04', '2026-03-12 00:03:04'),
(79, 56, 1, '2026-03-12', 'active', '70', 'Okay', '2026-03-12 00:31:11', '2026-03-12 00:31:11'),
(80, 39, 5, '2026-03-12', 'active', '100', 'Assigned', '2026-03-12 00:35:46', '2026-03-12 00:35:46'),
(81, 40, 5, '2026-03-12', 'active', '100', 'Preventive Maintenance Done', '2026-03-12 00:38:55', '2026-03-12 00:38:55'),
(82, 40, 5, '2026-03-12', 'active', '100', 'Preventive Maintenance Done', '2026-03-12 00:39:01', '2026-03-12 00:39:01'),
(83, 46, 5, '2026-03-12', 'active', '100', 'Purchased', '2026-03-12 00:43:27', '2026-03-12 00:43:27'),
(84, 47, 5, '2026-03-12', 'active', '100', 'Purchased', '2026-03-12 00:44:03', '2026-03-12 00:44:03'),
(85, 59, 5, '2026-03-12', 'active', '100', 'Updated', '2026-03-12 00:48:29', '2026-03-12 00:48:29'),
(86, 43, 5, '2026-03-12', 'active', '100', 'Purchased', '2026-03-12 00:58:41', '2026-03-12 00:58:41'),
(87, 44, 5, '2026-03-12', 'active', '100', 'Addressed', '2026-03-12 00:59:08', '2026-03-12 00:59:08'),
(88, 61, 5, '2026-03-12', 'active', '100', 'addressed', '2026-03-12 00:59:47', '2026-03-12 00:59:47'),
(89, 45, 5, '2026-03-12', 'active', '100', 'Updated', '2026-03-12 01:04:49', '2026-03-12 01:04:49'),
(90, 62, 1, '2026-03-16', 'active', '100', 'Extinguisher Code updated', '2026-03-16 00:53:44', '2026-03-16 00:53:44'),
(91, 60, 1, '2026-03-16', 'active', '100', 'Extinguisher Code updated', '2026-03-16 00:53:58', '2026-03-16 00:53:58'),
(92, 59, 1, '2026-03-16', 'active', '100', 'Extinguisher Code updated', '2026-03-16 00:57:16', '2026-03-16 00:57:16'),
(93, 58, 1, '2026-03-16', 'active', '100', 'Extinguisher Code updated', '2026-03-16 00:57:36', '2026-03-16 00:57:36'),
(94, 47, 1, '2026-03-16', 'active', '100', 'Extinguisher Code updated', '2026-03-16 00:58:10', '2026-03-16 00:58:10'),
(95, 46, 1, '2026-03-16', 'active', '100', 'Extinguisher Code updated', '2026-03-16 00:58:38', '2026-03-16 00:58:38'),
(96, 63, 1, '2026-03-16', 'active', '100', 'Extinguisher Code updated', '2026-03-16 00:59:00', '2026-03-16 00:59:00'),
(97, 65, 4, '2026-03-17', 'active', '100', 'Changing', '2026-03-16 18:09:56', '2026-03-16 18:09:56'),
(98, 66, 4, '2026-03-17', 'active', '100', 'oks', '2026-03-17 05:20:42', '2026-03-17 05:20:42'),
(99, 67, 1, '2026-03-17', 'active', '100', 'Update number', '2026-03-17 06:12:08', '2026-03-17 06:12:08'),
(100, 66, 1, '2026-03-17', 'active', '100', 'Change code', '2026-03-17 06:12:36', '2026-03-17 06:12:36'),
(101, 65, 1, '2026-03-17', 'active', '100', 'Change code', '2026-03-17 06:12:57', '2026-03-17 06:12:57'),
(102, 69, 1, '2026-03-17', 'active', '100', 'Change code', '2026-03-17 06:41:29', '2026-03-17 06:41:29'),
(103, 81, 1, '2026-03-19', 'maintenance', '20', 'wrong Entry', '2026-03-19 02:58:31', '2026-03-19 02:58:31'),
(104, 77, 1, '2026-03-19', 'purchase', '0', 'Wrong Numbering', '2026-03-19 03:18:41', '2026-03-19 03:18:41'),
(105, 57, 1, '2026-03-19', 'maintenance', '57', 'Okay?', '2026-03-19 08:00:17', '2026-03-19 08:00:17'),
(106, 19, 1, '2026-03-29', 'active', '100', 'new cod', '2026-03-29 02:30:29', '2026-03-29 02:30:29'),
(107, 19, 1, '2026-03-29', 'active', '100', 'Reverted code back', '2026-03-29 02:30:42', '2026-03-29 02:30:42');

-- --------------------------------------------------------

--
-- Table structure for table `fire_safety_extinguisher_room_coverage`
--

CREATE TABLE `fire_safety_extinguisher_room_coverage` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `extinguisher_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fire_safety_extinguisher_room_coverage`
--

INSERT INTO `fire_safety_extinguisher_room_coverage` (`id`, `extinguisher_id`, `room_id`, `created_at`, `updated_at`) VALUES
(32, 19, 37, '2026-02-12 18:11:03', '2026-02-12 18:11:03'),
(33, 20, 38, '2026-02-12 18:25:51', '2026-02-12 18:25:51'),
(34, 21, 41, '2026-02-12 18:39:05', '2026-02-12 18:39:05'),
(35, 21, 42, '2026-02-12 18:39:29', '2026-02-12 18:39:29'),
(37, 22, 49, '2026-02-12 18:56:07', '2026-02-12 18:56:07'),
(38, 23, 50, '2026-02-12 18:59:22', '2026-02-12 18:59:22'),
(40, 24, 52, '2026-02-12 19:02:49', '2026-02-12 19:02:49'),
(41, 25, 53, '2026-02-12 19:03:25', '2026-02-12 19:03:25'),
(42, 26, 54, '2026-02-12 19:04:34', '2026-02-12 19:04:34'),
(43, 27, 55, '2026-02-12 19:07:20', '2026-02-12 19:07:20'),
(47, 29, 57, '2026-02-18 19:34:38', '2026-02-18 19:34:38'),
(48, 30, 58, '2026-03-01 18:38:19', '2026-03-01 18:38:19'),
(51, 32, 60, '2026-03-01 18:42:19', '2026-03-01 18:42:19'),
(52, 32, 59, '2026-03-01 18:42:19', '2026-03-01 18:42:19'),
(53, 33, 63, '2026-03-01 18:53:20', '2026-03-01 18:53:20'),
(54, 35, 40, '2026-03-02 16:08:06', '2026-03-02 16:08:06'),
(55, 34, 65, '2026-03-02 18:39:58', '2026-03-02 18:39:58'),
(56, 22, 48, '2026-03-02 18:49:50', '2026-03-02 18:49:50'),
(58, 36, 67, '2026-03-03 16:37:18', '2026-03-03 16:37:18'),
(59, 37, 77, '2026-03-03 16:48:56', '2026-03-03 16:48:56'),
(60, 37, 78, '2026-03-03 16:48:56', '2026-03-03 16:48:56'),
(61, 37, 79, '2026-03-03 16:48:56', '2026-03-03 16:48:56'),
(63, 36, 66, '2026-03-03 21:55:03', '2026-03-03 21:55:03'),
(64, 36, 68, '2026-03-03 21:55:03', '2026-03-03 21:55:03'),
(67, 23, 51, '2026-03-05 00:04:36', '2026-03-05 00:04:36'),
(69, 19, 35, '2026-03-05 00:05:32', '2026-03-05 00:05:32'),
(70, 41, 98, '2026-03-05 00:06:05', '2026-03-05 00:06:05'),
(71, 42, 100, '2026-03-05 00:10:15', '2026-03-05 00:10:15'),
(72, 43, 99, '2026-03-05 00:11:10', '2026-03-05 00:11:10'),
(73, 44, 101, '2026-03-05 00:12:01', '2026-03-05 00:12:01'),
(74, 45, 102, '2026-03-05 00:12:56', '2026-03-05 00:12:56'),
(75, 46, 103, '2026-03-05 00:13:37', '2026-03-05 00:13:37'),
(76, 47, 104, '2026-03-05 00:14:06', '2026-03-05 00:14:06'),
(77, 48, 39, '2026-03-05 17:02:09', '2026-03-05 17:02:09'),
(78, 28, 56, '2026-03-10 17:50:10', '2026-03-10 17:50:10'),
(80, 49, 111, '2026-03-10 19:50:19', '2026-03-10 19:50:19'),
(81, 49, 112, '2026-03-10 19:50:19', '2026-03-10 19:50:19'),
(82, 50, 110, '2026-03-10 19:50:41', '2026-03-10 19:50:41'),
(87, 51, 113, '2026-03-10 21:29:41', '2026-03-10 21:29:41'),
(89, 51, 116, '2026-03-10 21:30:56', '2026-03-10 21:30:56'),
(90, 52, 120, '2026-03-10 21:31:51', '2026-03-10 21:31:51'),
(91, 52, 121, '2026-03-10 21:31:51', '2026-03-10 21:31:51'),
(92, 54, 122, '2026-03-10 21:39:55', '2026-03-10 21:39:55'),
(93, 53, 117, '2026-03-10 21:41:24', '2026-03-10 21:41:24'),
(94, 55, 123, '2026-03-10 21:42:21', '2026-03-10 21:42:21'),
(98, 56, 125, '2026-03-10 21:44:51', '2026-03-10 21:44:51'),
(99, 56, 126, '2026-03-10 21:45:05', '2026-03-10 21:45:05'),
(101, 56, 124, '2026-03-11 17:08:06', '2026-03-11 17:08:06'),
(102, 57, 127, '2026-03-12 00:02:17', '2026-03-12 00:02:17'),
(103, 39, 93, '2026-03-12 00:35:46', '2026-03-12 00:35:46'),
(104, 40, 94, '2026-03-12 00:38:55', '2026-03-12 00:38:55'),
(105, 58, 105, '2026-03-12 00:47:07', '2026-03-12 00:47:07'),
(106, 58, 106, '2026-03-12 00:47:07', '2026-03-12 00:47:07'),
(107, 59, 108, '2026-03-12 00:48:02', '2026-03-12 00:48:02'),
(108, 59, 107, '2026-03-12 00:48:29', '2026-03-12 00:48:29'),
(109, 60, 109, '2026-03-12 00:50:12', '2026-03-12 00:50:12'),
(110, 61, 97, '2026-03-12 00:55:29', '2026-03-12 00:55:29'),
(111, 63, 97, '2026-03-13 00:21:54', '2026-03-13 00:21:54'),
(112, 62, 109, '2026-03-16 00:53:44', '2026-03-16 00:53:44'),
(113, 64, 95, '2026-03-16 17:37:51', '2026-03-16 17:37:51'),
(116, 65, 130, '2026-03-16 18:09:56', '2026-03-16 18:09:56'),
(117, 65, 131, '2026-03-16 18:09:56', '2026-03-16 18:09:56'),
(118, 66, 96, '2026-03-17 05:20:24', '2026-03-17 05:20:24'),
(119, 66, 128, '2026-03-17 05:20:24', '2026-03-17 05:20:24'),
(120, 67, 133, '2026-03-17 06:11:24', '2026-03-17 06:11:24'),
(121, 67, 134, '2026-03-17 06:11:24', '2026-03-17 06:11:24'),
(122, 67, 135, '2026-03-17 06:11:24', '2026-03-17 06:11:24'),
(123, 68, 136, '2026-03-17 06:39:13', '2026-03-17 06:39:13'),
(124, 68, 137, '2026-03-17 06:39:13', '2026-03-17 06:39:13'),
(125, 68, 138, '2026-03-17 06:39:13', '2026-03-17 06:39:13'),
(126, 69, 139, '2026-03-17 06:40:03', '2026-03-17 06:40:03'),
(127, 69, 141, '2026-03-17 06:40:03', '2026-03-17 06:40:03'),
(128, 70, 140, '2026-03-17 06:42:08', '2026-03-17 06:42:08'),
(129, 71, 142, '2026-03-17 06:45:56', '2026-03-17 06:45:56'),
(130, 71, 143, '2026-03-17 06:45:56', '2026-03-17 06:45:56'),
(131, 72, 144, '2026-03-17 07:01:01', '2026-03-17 07:01:01'),
(132, 72, 145, '2026-03-17 07:01:01', '2026-03-17 07:01:01'),
(133, 73, 148, '2026-03-19 02:23:25', '2026-03-19 02:23:25'),
(134, 73, 149, '2026-03-19 02:23:25', '2026-03-19 02:23:25'),
(135, 74, 150, '2026-03-19 02:24:52', '2026-03-19 02:24:52'),
(136, 75, 151, '2026-03-19 02:25:42', '2026-03-19 02:25:42'),
(137, 76, 152, '2026-03-19 02:26:18', '2026-03-19 02:26:18'),
(138, 76, 153, '2026-03-19 02:26:18', '2026-03-19 02:26:18'),
(139, 77, 154, '2026-03-19 02:35:04', '2026-03-19 02:35:04'),
(140, 78, 155, '2026-03-19 02:43:11', '2026-03-19 02:43:11'),
(141, 79, 156, '2026-03-19 02:45:13', '2026-03-19 02:45:13'),
(142, 80, 157, '2026-03-19 02:55:55', '2026-03-19 02:55:55'),
(143, 80, 158, '2026-03-19 02:55:55', '2026-03-19 02:55:55'),
(144, 80, 159, '2026-03-19 02:55:55', '2026-03-19 02:55:55'),
(145, 82, 161, '2026-03-19 02:57:25', '2026-03-19 02:57:25'),
(146, 81, 160, '2026-03-19 02:58:31', '2026-03-19 02:58:31'),
(147, 83, 162, '2026-03-19 02:59:25', '2026-03-19 02:59:25'),
(148, 83, 163, '2026-03-19 02:59:25', '2026-03-19 02:59:25'),
(149, 83, 164, '2026-03-19 02:59:25', '2026-03-19 02:59:25'),
(150, 84, 165, '2026-03-19 03:00:27', '2026-03-19 03:00:27'),
(151, 84, 166, '2026-03-19 03:00:27', '2026-03-19 03:00:27'),
(152, 85, 167, '2026-03-19 03:01:12', '2026-03-19 03:01:12'),
(153, 85, 168, '2026-03-19 03:01:12', '2026-03-19 03:01:12'),
(154, 86, 169, '2026-03-19 03:19:38', '2026-03-19 03:19:38'),
(155, 86, 170, '2026-03-19 03:19:38', '2026-03-19 03:19:38'),
(156, 87, 171, '2026-03-19 03:20:56', '2026-03-19 03:20:56'),
(157, 87, 172, '2026-03-19 03:20:56', '2026-03-19 03:20:56'),
(158, 87, 173, '2026-03-19 03:20:56', '2026-03-19 03:20:56'),
(159, 88, 174, '2026-03-19 03:22:00', '2026-03-19 03:22:00'),
(160, 88, 175, '2026-03-19 03:22:00', '2026-03-19 03:22:00'),
(161, 89, 176, '2026-03-19 03:23:05', '2026-03-19 03:23:05'),
(162, 89, 177, '2026-03-19 03:23:05', '2026-03-19 03:23:05'),
(163, 90, 178, '2026-03-19 03:24:09', '2026-03-19 03:24:09'),
(164, 90, 179, '2026-03-19 03:24:09', '2026-03-19 03:24:09'),
(165, 91, 180, '2026-03-19 03:25:10', '2026-03-19 03:25:10'),
(166, 91, 181, '2026-03-19 03:25:10', '2026-03-19 03:25:10'),
(167, 91, 182, '2026-03-19 03:25:10', '2026-03-19 03:25:10'),
(168, 95, 184, '2026-04-07 00:43:43', '2026-04-07 00:43:43'),
(169, 97, 186, '2026-04-07 00:46:27', '2026-04-07 00:46:27'),
(170, 98, 186, '2026-04-07 00:47:06', '2026-04-07 00:47:06'),
(172, 100, 185, '2026-04-07 00:49:47', '2026-04-07 00:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `fire_safety_inspections`
--

CREATE TABLE `fire_safety_inspections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unified_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `drill_type` varchar(255) NOT NULL,
  `inspection_date` date NOT NULL,
  `inspection_time` time NOT NULL,
  `time_started` time DEFAULT NULL,
  `time_finished` time DEFAULT NULL,
  `elapsed_time` varchar(255) DEFAULT NULL,
  `no_of_exits` int(11) DEFAULT NULL,
  `no_of_buildings` int(11) DEFAULT NULL,
  `no_of_students` int(11) DEFAULT NULL,
  `no_of_personnel` int(11) DEFAULT NULL,
  `monitored_by` varchar(255) DEFAULT NULL,
  `monitored_by_position` varchar(255) DEFAULT NULL,
  `checklist_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`checklist_data`)),
  `observers_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`observers_data`)),
  `remarks` text DEFAULT NULL,
  `coordinator_name` varchar(255) DEFAULT NULL,
  `school_head_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fire_safety_inspections`
--

INSERT INTO `fire_safety_inspections` (`id`, `unified_school_id`, `drill_type`, `inspection_date`, `inspection_time`, `time_started`, `time_finished`, `elapsed_time`, `no_of_exits`, `no_of_buildings`, `no_of_students`, `no_of_personnel`, `monitored_by`, `monitored_by_position`, `checklist_data`, `observers_data`, `remarks`, `coordinator_name`, `school_head_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Fire', '2026-02-03', '09:00:00', '09:45:00', '10:00:00', '4:56', 2, 10, 615, 29, 'John Benedict G. Pecson', 'TA-1 DRRM1', '[\"Alarm\",\"Evacuation Plan (Updated)\",\"First Aid Kit\",\"Actual Head Count\",\"Directional Arrows\",\"SF 2 \\/ Attendance Sheet\",\"Group Signage\",\"Walked Casually\",\"Guard On Duty\",\"Closed Doors (Fire)\"]', '[\"Local Barangay\",\"City DRRM\",\"BFP\"]', 'Repaint directional Arrows\r\nPut up exit signages\r\nInc. I.D for personnel & Students', 'Eleazar Arazadon', 'Mr. Raymund F. Camacho', '2026-02-12 22:46:47', '2026-03-17 07:39:56'),
(2, 2, 'Fire', '2026-02-19', '14:01:00', '02:00:00', '02:30:00', '30mins', 2, 6, 899, 19, 'John Benedict G. Pecson', NULL, '[\"Alarm\",\"First Aid Kit\",\"Hotline Numbers\",\"Command Center\"]', '[\"City DRRM\",\"OTMPS\",\"PNP\"]', 'Okay Inspection', 'Jeffrey C. Mabini', 'Froilan N. Rivas', '2026-02-18 19:31:17', '2026-02-18 19:31:17'),
(3, 10, 'Fire', '2026-03-04', '09:30:00', '09:37:00', '09:39:00', '02:24', 1, 5, 253, 25, 'Erwin A. Castillejo', NULL, '[\"Alarm\",\"Actual Head Count\"]', '[\"BFP\"]', 'Change Evacuation Area farther back\r\nRemove books along corridor at Bldg 2 room 6\r\nAvoid using Butane Stove as main mode of cooking\r\nHang all Fire Extinguishers\r\nTransfer Alarm switch to Building 4\r\nutilize Digital Alarm system', 'Judith Jao', 'Judith Jao', '2026-03-04 23:32:40', '2026-03-04 23:32:40'),
(4, 3, 'Fire', '2026-03-12', '02:16:00', '10:20:00', '10:40:00', '15:30', 4, 3, 545, 26, 'Zaldy Danaytan, Jr.', 'Disc Head', '[\"Alarm\",\"Actual Head Count\",\"Command Center\",\"Megaphone\",\"Group Signage\",\"School ID (Personnel)\",\"School ID (Students)\",\"Closed Doors (Fire)\"]', '[\"City DRRM\",\"OTMPS\",\"Others: Special Investigator\"]', 'Good drilling, be safe when evacuating, follow the rules', 'Nestor Sison', 'Nestor Sison', '2026-03-11 18:18:52', '2026-03-11 18:19:43'),
(5, 3, 'Fire', '2026-03-17', '10:18:00', '10:18:00', '10:23:00', '5:07', 2, 7, 337, 21, 'John Benedict G. Pecson', 'TA-1 DRRM1', '[\"Alarm\",\"Evacuation Plan (Updated)\",\"First Aid Kit\",\"Actual Head Count\",\"Directional Arrows\",\"Hotline Numbers\",\"Command Center\",\"SF 2 \\/ Attendance Sheet\",\"Megaphone\",\"Group Signage\",\"Walked Casually\",\"Guard On Duty\",\"School ID (Personnel)\",\"School ID (Students)\",\"Closed Doors (Fire)\"]', '[\"Local Barangay\",\"City DRRM\"]', '-Hotline # inc. of BFP\r\n- Inc. I.D for personnel & Students\r\n- Revise Evac Area', 'Zaldy D. Danaytab, Jr.', 'Nestor Sison', '2026-03-17 07:17:36', '2026-03-17 07:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `fire_safety_rooms`
--

CREATE TABLE `fire_safety_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unified_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `room_code` varchar(255) DEFAULT NULL,
  `room_name` varchar(255) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `has_smoke_detector` tinyint(1) NOT NULL DEFAULT 0,
  `smoke_detector_required` tinyint(1) NOT NULL DEFAULT 0,
  `has_secondary_exit` tinyint(1) NOT NULL DEFAULT 0,
  `secondary_exit_remarks` text DEFAULT NULL,
  `room_type_config_id` bigint(20) UNSIGNED DEFAULT NULL,
  `calculated_priority_label` varchar(120) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `coverage_limit` tinyint(3) UNSIGNED DEFAULT NULL,
  `floor_no` smallint(5) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nearest_extinguisher_room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `last_inspector_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approval_status` varchar(255) DEFAULT NULL,
  `approval_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fire_safety_rooms`
--

INSERT INTO `fire_safety_rooms` (`id`, `unified_school_id`, `building_id`, `room_code`, `room_name`, `room_type`, `has_smoke_detector`, `smoke_detector_required`, `has_secondary_exit`, `secondary_exit_remarks`, `room_type_config_id`, `calculated_priority_label`, `remarks`, `coverage_limit`, `floor_no`, `created_at`, `updated_at`, `nearest_extinguisher_room_id`, `last_inspector_id`, `approval_status`, `approval_message`) VALUES
(35, 1, 17, 'Room 01', 'Room 01', 'Administration', 0, 0, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-11 17:59:57', '2026-03-05 00:02:27', NULL, 1, 'approved', NULL),
(36, 1, 17, 'Room 02', 'Room 02', 'Administration', 0, 0, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:09:45', '2026-03-05 00:05:42', 35, 1, 'approved', NULL),
(37, 1, 17, 'ADMNRM-001', 'ADMNRM-001', 'Department', 0, 0, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:10:54', '2026-02-12 18:11:03', 35, NULL, NULL, NULL),
(38, 1, 19, 'Room-001', 'Room 001', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:23:44', '2026-02-12 18:23:44', NULL, NULL, NULL, NULL),
(39, 1, 19, 'Room-002', 'Room 02', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:24:06', '2026-02-17 18:15:16', 38, NULL, NULL, NULL),
(40, 1, 19, 'Lab-001', 'Lab-001', 'Laboratory', 0, 0, 0, NULL, 49, 'Dedicated / Limited Shared', NULL, 2, 1, '2026-02-12 18:24:34', '2026-02-12 18:24:34', NULL, NULL, NULL, NULL),
(41, 1, 20, 'Room-004', 'Room 01', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:32:17', '2026-02-12 18:32:17', NULL, NULL, NULL, NULL),
(42, 1, 20, 'Room-005', 'Room 05', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:34:02', '2026-02-12 18:39:29', 41, NULL, NULL, NULL),
(43, 1, 21, 'Room-006', 'Room-067', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:42:05', '2026-02-17 18:15:00', NULL, NULL, NULL, NULL),
(44, 1, 21, 'Room-007', 'Room 07', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:42:25', '2026-02-12 18:42:25', NULL, NULL, NULL, NULL),
(45, 1, 22, 'Room-008', 'Room-008', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:50:38', '2026-02-12 18:50:38', NULL, NULL, NULL, NULL),
(46, 1, 23, 'Room-009', 'Room-009', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:51:21', '2026-02-12 18:51:21', NULL, NULL, NULL, NULL),
(47, 1, 24, 'Room-010', 'Room-010', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay kayo room', 3, 1, '2026-02-12 18:51:46', '2026-03-11 23:55:05', NULL, 1, 'approved', NULL),
(48, 1, 25, 'Room-011', 'Room-011', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:54:51', '2026-02-12 18:54:51', NULL, NULL, NULL, NULL),
(49, 1, 25, 'Room-0012', 'Room-012', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:55:09', '2026-02-12 18:56:07', 48, NULL, NULL, NULL),
(50, 1, 26, 'Room-013', 'Room-013', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:58:18', '2026-02-12 18:58:18', NULL, NULL, NULL, NULL),
(51, 1, 26, 'Room-014', 'Room-014', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 18:58:34', '2026-03-05 00:04:36', 50, 1, 'approved', NULL),
(52, 1, 27, 'Room-016', 'Room-016', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 19:01:06', '2026-02-12 19:01:06', NULL, NULL, NULL, NULL),
(53, 1, 27, 'Room-017', 'Room-017', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 19:01:29', '2026-02-12 19:01:29', NULL, NULL, NULL, NULL),
(54, 1, 27, 'Room-018', 'Room-018', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 19:01:51', '2026-02-12 19:01:51', NULL, NULL, NULL, NULL),
(55, 1, 28, 'Room-019', 'Room-019', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 19:06:16', '2026-02-12 19:06:16', NULL, NULL, NULL, NULL),
(56, 1, 28, 'Room-02', 'Room-020', 'Administration', 0, 1, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-02-12 19:06:54', '2026-03-10 17:48:56', NULL, 1, 'approved', NULL),
(57, 2, 31, '01', '01', 'Classroom', 0, 0, 1, 'Okay', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay now', 3, 1, '2026-02-18 19:34:09', '2026-03-12 00:32:17', NULL, 1, 'approved', NULL),
(58, 7, 32, '101', 'Computer Laboratory', 'Laboratory', 0, 0, 0, NULL, 49, 'Dedicated / Limited Shared', NULL, 2, 2, '2026-03-01 18:34:45', '2026-03-01 18:34:45', NULL, NULL, NULL, NULL),
(59, 7, 32, '02', 'Principals Office', 'Administration', 0, 0, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-01 18:35:19', '2026-03-01 18:35:19', NULL, NULL, NULL, NULL),
(60, 7, 32, '03', 'Faculty Room', 'Administration', 0, 0, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-01 18:35:59', '2026-03-01 18:35:59', NULL, NULL, NULL, NULL),
(61, 7, 32, '04', '04', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-01 18:36:42', '2026-03-01 18:36:42', NULL, NULL, NULL, NULL),
(62, 7, 32, '05', '05', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-01 18:37:08', '2026-03-01 18:37:08', NULL, NULL, NULL, NULL),
(63, 7, 33, '08', '08', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-01 18:52:47', '2026-03-01 18:52:47', NULL, NULL, NULL, NULL),
(64, 7, 32, '06', '06', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-01 22:27:23', '2026-03-01 22:27:23', NULL, NULL, NULL, NULL),
(65, 1, 25, 'Room-0111', 'Room-0111', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-02 01:05:00', '2026-03-02 01:05:00', NULL, NULL, NULL, NULL),
(66, 8, 36, '1', '1', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Open Outlet', 3, 1, '2026-03-02 23:57:21', '2026-03-03 16:59:56', NULL, NULL, NULL, NULL),
(67, 8, 36, '2', 'Grade 2 Classroom', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay has a fire extinguisher now', 3, 1, '2026-03-02 23:58:04', '2026-03-03 22:20:17', NULL, NULL, NULL, NULL),
(68, 8, 36, '3', '3', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-02 23:58:28', '2026-03-02 23:58:28', NULL, NULL, NULL, NULL),
(69, 8, 36, '4', '4', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-02 23:59:12', '2026-03-02 23:59:12', NULL, NULL, NULL, NULL),
(70, 8, 36, '5', '5', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-02 23:59:32', '2026-03-02 23:59:32', NULL, NULL, NULL, NULL),
(71, 8, 36, '6', '6', 'Administration', 0, 0, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-03 00:00:41', '2026-03-03 00:00:41', NULL, NULL, NULL, NULL),
(72, 8, 36, '7', '7', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-03 00:03:11', '2026-03-03 00:03:11', NULL, NULL, NULL, NULL),
(73, 8, 36, '8', '8', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-03 00:04:34', '2026-03-03 00:04:34', NULL, NULL, NULL, NULL),
(74, 8, 36, '9', '9', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-03 00:05:40', '2026-03-03 00:05:40', NULL, NULL, NULL, NULL),
(75, 8, 36, '10', '10', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-03 00:06:20', '2026-03-03 00:06:20', NULL, NULL, NULL, NULL),
(76, 8, 36, '11', 'Grade 6 Classroom', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Needs minor repair', 3, 2, '2026-03-03 00:06:35', '2026-03-03 22:18:20', NULL, NULL, NULL, NULL),
(77, 8, 37, '12', '12', 'Laboratory', 0, 0, 0, NULL, 46, 'Dedicated / Limited Shared (Up to 2 rooms)', NULL, 2, 1, '2026-03-03 00:07:18', '2026-03-03 00:07:18', NULL, NULL, NULL, NULL),
(78, 8, 37, '13', '13', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:07:56', '2026-03-03 00:07:56', NULL, NULL, NULL, NULL),
(79, 8, 37, '14', '14', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:08:18', '2026-03-03 00:08:18', NULL, NULL, NULL, NULL),
(80, 8, 38, '15', '15', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:11:37', '2026-03-03 00:11:37', NULL, NULL, NULL, NULL),
(81, 8, 38, '16', '16', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:12:02', '2026-03-03 00:12:02', NULL, NULL, NULL, NULL),
(82, 8, 38, '17', '17', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:12:22', '2026-03-03 00:12:22', NULL, NULL, NULL, NULL),
(83, 8, 39, '18', '18', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:12:56', '2026-03-03 00:12:56', NULL, NULL, NULL, NULL),
(84, 8, 39, '19', '19', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:13:11', '2026-03-03 00:13:11', NULL, NULL, NULL, NULL),
(85, 8, 39, '20', '20', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:13:27', '2026-03-03 00:13:27', NULL, NULL, NULL, NULL),
(86, 8, 40, '21', '21', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-03 00:14:12', '2026-03-03 00:14:12', NULL, NULL, NULL, NULL),
(87, 8, 41, '23', '23', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Needs to be covered now', 3, 1, '2026-03-03 00:14:41', '2026-03-11 18:12:17', NULL, 1, 'approved', NULL),
(88, 8, 42, '24', '24', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay now room', 3, 1, '2026-03-03 00:15:43', '2026-03-03 21:59:30', NULL, NULL, NULL, NULL),
(89, 8, 42, '26', '26', 'Canteen', 0, 0, 0, NULL, 155, 'Shared Coverage (Up to 3 Classrooms)', 'Okay now room', 3, 1, '2026-03-03 00:17:22', '2026-03-03 21:59:20', NULL, NULL, NULL, NULL),
(90, 8, 42, '28', 'Not currently use', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Needs Major Repair', 3, 1, '2026-03-03 21:59:07', '2026-03-03 21:59:07', NULL, NULL, NULL, NULL),
(91, 6, 48, '01', 'Grade 1 Classroom', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Updated its all good now', 3, 1, '2026-03-03 23:15:06', '2026-03-03 23:48:25', NULL, 4, 'approved', 'Approved by Adan Kristopher B. Dumpit'),
(92, 6, 48, '02', 'Elementary\'s room', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay room', 3, 1, '2026-03-04 21:41:06', '2026-03-04 21:41:06', NULL, 4, 'pending', NULL),
(93, 10, 49, '01', 'Faculty Room', 'Administration', 0, 0, 1, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-04 22:14:49', '2026-03-12 00:33:25', NULL, 5, 'approved', NULL),
(94, 10, 49, '02', 'Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-04 22:15:08', '2026-03-12 00:33:08', NULL, 5, 'approved', NULL),
(95, 3, 50, '01', '01', 'Classroom', 0, 0, 1, 'Secondary Exit good to use', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay room', 3, 1, '2026-03-04 22:46:05', '2026-03-16 17:37:18', NULL, 1, 'approved', 'The room info is incompleted and needed of fire extinguisher'),
(96, 3, 51, '01', '01', 'Classroom', 0, 0, 1, 'Ready to use', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay room', 3, 1, '2026-03-04 22:47:16', '2026-03-16 17:51:12', NULL, 1, 'approved', 'Approved by Adan Kristopher B. Dumpit'),
(97, 10, 52, '01', 'Principal\'s Office and Classroom', 'Classroom and Administration', 0, 0, 0, NULL, 156, 'Shared Space', NULL, 1, 1, '2026-03-04 23:07:05', '2026-03-12 00:53:43', NULL, 5, 'approved', NULL),
(98, 10, 52, '02', 'SBM and Classroom', 'Classroom and Administration', 0, 0, 0, 'Need to clear grills at back area or create Fire Exit that can be opened.', 156, 'Shared Space', NULL, 1, 1, '2026-03-04 23:08:19', '2026-03-12 00:54:16', NULL, 5, 'approved', NULL),
(99, 10, 52, '03', 'LRC and Classroom', 'Classroom and Administration', 0, 0, 0, 'No General Secondary Exit', 156, 'Shared Space', NULL, 1, 2, '2026-03-04 23:09:24', '2026-03-04 23:09:24', NULL, 5, 'approved', NULL),
(100, 10, 52, '04', '04', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With electrical concern regarding Switch', 3, 2, '2026-03-04 23:09:49', '2026-03-05 00:46:04', NULL, 5, 'approved', NULL),
(101, 10, 52, '05', '05', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 3, '2026-03-04 23:10:18', '2026-03-04 23:10:18', NULL, 5, 'approved', NULL),
(102, 10, 52, '06', '06', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Electrical Concerns', 3, 3, '2026-03-04 23:11:22', '2026-03-05 00:45:32', NULL, 5, 'approved', NULL),
(103, 10, 54, '01', '01', 'Classroom', 0, 0, 0, 'Need to remove grills', 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-04 23:22:20', '2026-03-12 00:42:07', NULL, 5, 'approved', NULL),
(104, 10, 54, '02', 'HE Rooom', 'Laboratory', 0, 0, 0, 'Need to remove grills or create fire exit', 49, 'Dedicated / Limited Shared', NULL, 2, 1, '2026-03-04 23:22:59', '2026-03-12 00:42:32', NULL, 5, 'approved', NULL),
(105, 10, 55, '01', '01', 'Classroom', 0, 0, 0, 'Need to remove grills or create fire exit.', 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-04 23:24:59', '2026-03-04 23:24:59', NULL, 5, 'approved', NULL),
(106, 10, 55, '02', '02', 'Classroom', 0, 0, 0, 'Need to remove grills or create fire exit.', 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-04 23:25:19', '2026-03-04 23:25:19', NULL, 5, 'approved', NULL),
(107, 10, 55, '03', '03', 'Classroom', 0, 0, 0, 'Need to remove grills or create fire exit.', 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-04 23:25:38', '2026-03-04 23:25:38', NULL, 5, 'approved', NULL),
(108, 10, 55, '04', '04', 'Classroom', 0, 0, 0, 'Need to remove grills or create fire exit.', 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-04 23:25:59', '2026-03-04 23:25:59', NULL, 5, 'approved', NULL),
(109, 10, 56, '01', 'Clinic', 'Classroom and Administration', 0, 0, 0, 'Need to remove grills or create fire exit.', 156, 'Shared Space', NULL, 1, 1, '2026-03-04 23:27:24', '2026-03-12 00:49:11', NULL, 5, 'approved', NULL),
(110, 4, 57, '01', 'Grade 1 Classroom', 'Classroom', 0, 0, 1, 'OKAY', 46, 'Shared Coverage (Up to 3 Classrooms)', 'okay', 3, 1, '2026-03-10 18:41:09', '2026-03-10 18:55:43', NULL, 3, 'pending', NULL),
(111, 4, 57, '02', 'Grade 4 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay Room', 3, 1, '2026-03-10 18:54:39', '2026-03-10 18:55:51', NULL, 3, 'pending', 'Approved by Adan Kristopher B. Dumpit'),
(112, 4, 57, '03', 'Grade 4 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Starred room', 3, 1, '2026-03-10 18:56:22', '2026-03-10 18:56:22', NULL, 3, 'pending', NULL),
(113, 4, 58, 'ADMN-01', 'Administration Room', 'Administration', 1, 1, 1, 'Closed', 47, 'Shared Coverage (Up to 3 Classrooms)', 'Room is active and compliant', 3, 1, '2026-03-10 18:59:26', '2026-03-10 18:59:52', NULL, 3, 'pending', NULL),
(116, 4, 58, '02', 'Grade 5 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Good now', 3, 1, '2026-03-10 19:36:00', '2026-03-10 19:36:40', NULL, 3, 'pending', NULL),
(117, 4, 58, '03', 'Grade 5 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Good to go', 3, 1, '2026-03-10 19:37:12', '2026-03-10 19:37:12', NULL, 3, 'pending', NULL),
(120, 4, 58, 'LAB-04', 'Science Laboratory', 'Laboratory', 0, 0, 1, NULL, 49, 'Dedicated / Limited Shared', 'Laboratory compliant room', 2, 2, '2026-03-10 19:40:04', '2026-03-10 19:40:30', NULL, 3, 'pending', NULL),
(121, 4, 58, '04', 'Grade 5 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay room and active', 3, 2, '2026-03-10 19:41:20', '2026-03-10 19:41:20', NULL, 3, 'pending', NULL),
(122, 4, 58, '05', 'Storage Room, filled', 'Storage', 0, 0, 1, 'okay', 51, 'Dedicated / Limited Shared', 'Good storage, Resupplied', 2, 2, '2026-03-10 19:42:59', '2026-03-11 19:05:08', NULL, 3, 'pending', 'Approved by Adan Kristopher B. Dumpit'),
(123, 4, 59, '01', 'Grade 1-3 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay room', 3, 1, '2026-03-10 19:45:55', '2026-03-10 19:45:55', NULL, 3, 'pending', NULL),
(124, 4, 59, '02', 'Grade 2 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Grade 2 classroom only & changing hosting', 3, 1, '2026-03-10 19:46:43', '2026-03-11 17:08:05', 125, 3, 'pending', NULL),
(125, 4, 59, '03', 'Grade 1-3 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Good to go', 3, 1, '2026-03-10 19:47:10', '2026-03-10 19:47:10', NULL, 3, 'pending', NULL),
(126, 4, 59, '04', 'Grade 1-3 Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Good to go', 3, 1, '2026-03-10 19:47:41', '2026-03-10 21:45:36', 125, 3, 'approved', 'Approved by Adan Kristopher B. Dumpit'),
(127, 5, 60, '01', 'Grade 1 Classroom', 'Classroom', 0, 0, 1, 'Okay', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-12 00:01:43', '2026-03-19 07:58:00', NULL, 1, 'approved', NULL),
(128, 3, 51, '02', 'Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay room', 3, 1, '2026-03-16 17:52:39', '2026-03-16 17:52:39', NULL, 1, 'approved', NULL),
(130, 3, 51, '03', 'Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Good Room', 3, 2, '2026-03-16 17:54:20', '2026-03-16 17:54:20', NULL, 1, 'approved', NULL),
(131, 3, 51, '04', 'Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay room', 3, 2, '2026-03-16 18:07:56', '2026-03-16 18:10:48', NULL, 4, 'approved', 'Approved by Adan Kristopher B. Dumpit'),
(132, 3, 53, '01', 'Administration Room', 'Administration', 0, 0, 1, 'yes', 47, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 05:22:24', '2026-03-17 05:22:38', NULL, 4, 'pending', NULL),
(133, 3, 61, '01', 'Classroom', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay Room', 3, 1, '2026-03-17 06:09:46', '2026-03-17 06:09:46', NULL, 1, 'approved', NULL),
(134, 3, 61, '02', 'Classroom', 'Classroom', 0, 0, 0, 'Okay', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:10:15', '2026-03-17 06:10:15', NULL, 1, 'approved', NULL),
(135, 3, 61, '01', 'Classroom', 'Classroom', 0, 0, 0, 'Okay', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:10:47', '2026-03-17 06:10:47', NULL, 1, 'approved', NULL),
(136, 3, 62, '01', 'Classroom', 'Classroom', 0, 0, 0, 'Doesn\'t have', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:26:56', '2026-03-17 06:28:19', NULL, 1, 'approved', NULL),
(137, 3, 62, '02', 'Classroom', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'OKay', 3, 1, '2026-03-17 06:29:19', '2026-03-17 06:29:19', NULL, 1, 'approved', NULL),
(138, 3, 62, '01', 'Classroom', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:30:40', '2026-03-17 06:30:40', NULL, 1, 'approved', NULL),
(139, 3, 63, '01', 'Administration', 'Administration', 0, 0, 1, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:35:48', '2026-03-17 06:35:48', NULL, 1, 'approved', NULL),
(140, 3, 63, '02', 'Administration', 'Administration', 0, 0, 1, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:36:25', '2026-03-17 06:36:25', NULL, 1, 'approved', NULL),
(141, 3, 63, '03', 'Canteen', 'Canteen', 0, 0, 1, NULL, 155, 'Shared Coverage (Up to 3 Classrooms)', 'Okay Canteen', 3, 1, '2026-03-17 06:37:29', '2026-03-17 06:37:29', NULL, 1, 'approved', NULL),
(142, 3, 64, '01', 'Classroom room', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:44:05', '2026-03-17 06:44:05', NULL, 1, 'approved', NULL),
(143, 3, 64, '02', 'Classroom room', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:44:56', '2026-03-17 06:44:56', NULL, 1, 'approved', NULL),
(144, 3, 65, '01', 'Classroom room', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 06:47:11', '2026-03-17 06:47:55', NULL, 1, 'approved', NULL),
(145, 3, 65, '02', 'Classroom room', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-03-17 07:00:30', '2026-03-17 07:00:30', NULL, 1, 'approved', NULL),
(146, 3, 66, '01', 'Lab 1', 'Laboratory', 0, 0, 1, NULL, 49, 'Dedicated / Limited Shared', 'Okay', 2, 1, '2026-03-17 07:03:29', '2026-03-17 07:03:29', NULL, 1, 'approved', NULL),
(147, 3, 66, '02', 'Lab 2', 'Laboratory', 0, 0, 1, NULL, 49, 'Dedicated / Limited Shared', 'Okay', 2, 1, '2026-03-17 07:03:57', '2026-03-17 07:03:57', NULL, 1, 'approved', NULL),
(148, 11, 68, '01', 'Classroom', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 02:20:15', '2026-03-19 02:20:15', NULL, 1, 'approved', NULL),
(149, 11, 68, '02', '02', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 02:20:54', '2026-03-19 02:20:54', NULL, 1, 'approved', NULL),
(150, 11, 68, '03', '03', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-19 02:21:20', '2026-03-19 02:21:20', NULL, 1, 'approved', NULL),
(151, 11, 68, '04', 'Science Laboratory', 'Laboratory', 0, 0, 1, NULL, 49, 'Dedicated / Limited Shared', NULL, 2, 2, '2026-03-19 02:21:50', '2026-03-19 02:21:50', NULL, 1, 'approved', NULL),
(152, 11, 68, '05', '05', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 3, '2026-03-19 02:22:16', '2026-03-19 02:22:16', NULL, 1, 'approved', NULL),
(153, 11, 68, '06', '06', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 3, '2026-03-19 02:22:36', '2026-03-19 02:22:36', NULL, 1, 'approved', NULL),
(154, 11, 69, '01', 'School Canteen', 'Canteen', 0, 0, 1, NULL, 155, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 02:33:34', '2026-03-19 02:33:34', NULL, 1, 'approved', NULL),
(155, 11, 70, '01', 'HE Laboratory', 'Laboratory', 0, 0, 1, NULL, 49, 'Dedicated / Limited Shared', 'Need  to address locked gate', 2, 1, '2026-03-19 02:42:31', '2026-03-19 02:42:31', NULL, 1, 'approved', NULL),
(156, 11, 71, '01', '01', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 02:44:33', '2026-03-19 02:44:33', NULL, 1, 'approved', NULL),
(157, 11, 72, '01', '01', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With grills - Creation of Secondary Exit', 3, 1, '2026-03-19 02:48:04', '2026-03-19 02:48:04', NULL, 1, 'approved', NULL),
(158, 11, 72, '02', '02', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With grills - creation of Secondary Exit', 3, 1, '2026-03-19 02:48:46', '2026-03-19 02:48:46', NULL, 1, 'approved', NULL),
(159, 11, 72, '03', '03', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 1, '2026-03-19 02:49:24', '2026-03-19 02:49:24', NULL, 1, 'approved', NULL),
(160, 11, 72, '04', 'Feeding Room', 'Administration', 0, 0, 0, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 1, '2026-03-19 02:50:10', '2026-03-19 02:50:10', NULL, 1, 'approved', NULL),
(161, 11, 72, '05', 'HE and Storage', 'Storage', 0, 0, 0, NULL, 51, 'Dedicated / Limited Shared', 'With Grills - Creation of Secondary Exit', 2, 1, '2026-03-19 02:50:49', '2026-03-19 02:51:12', NULL, 1, 'approved', NULL),
(162, 11, 72, '01', '01', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 2, '2026-03-19 02:51:49', '2026-03-19 02:51:49', NULL, 1, 'approved', NULL),
(163, 11, 72, '02', 'Classroom', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-19 02:52:13', '2026-03-19 02:52:13', NULL, 1, 'approved', NULL),
(164, 11, 72, '03', '03', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 2, '2026-03-19 02:52:45', '2026-03-19 02:52:45', NULL, 1, 'approved', NULL),
(165, 11, 72, '04', '04', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 2, '2026-03-19 02:53:15', '2026-03-19 02:53:15', NULL, 1, 'approved', NULL),
(166, 11, 72, '05', '05', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 2, '2026-03-19 02:53:46', '2026-03-19 02:53:46', NULL, 1, 'approved', NULL),
(167, 11, 72, '06', '06', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 2, '2026-03-19 02:54:09', '2026-03-19 02:54:09', NULL, 1, 'approved', NULL),
(168, 11, 72, '07', '07', 'Classroom', 0, 0, 0, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'With Grills - Creation of Secondary Exit', 3, 2, '2026-03-19 02:54:30', '2026-03-19 02:54:30', NULL, 1, 'approved', NULL),
(169, 11, 73, '01', '01', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 03:06:32', '2026-03-19 03:06:32', NULL, 1, 'approved', NULL),
(170, 11, 73, '01-01', '01-01', 'Clinic', 0, 0, 0, NULL, 50, 'Dedicated / Limited Shared', 'Converted Comfort Room to Admin Function', 2, 1, '2026-03-19 03:07:23', '2026-03-19 03:07:23', NULL, 1, 'approved', NULL),
(171, 11, 73, '02', '02', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Check Electrical Connections (Extension Chords)', 3, 1, '2026-03-19 03:07:45', '2026-03-19 03:08:40', NULL, 1, 'approved', NULL),
(172, 11, 73, '03', '03', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 03:08:09', '2026-03-19 03:08:09', NULL, 1, 'approved', NULL),
(173, 11, 73, '04', '04', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 03:09:01', '2026-03-19 03:09:01', NULL, 1, 'approved', NULL),
(174, 11, 73, '05', '05', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 1, '2026-03-19 03:09:24', '2026-03-19 03:09:24', NULL, 1, 'approved', NULL),
(175, 11, 73, '06', '06', 'Classroom', 0, 0, 0, 'Need to create an exit point', 46, 'Shared Coverage (Up to 3 Classrooms)', 'No Secondary Exit \r\nCheck for Electrical Concerns\r\nRemoval of Defective Oscillating Fan', 3, 1, '2026-03-19 03:11:09', '2026-03-19 03:11:09', NULL, 1, 'approved', NULL),
(176, 11, 73, '01', 'Principal\'s Office', 'Administration', 0, 1, 0, 'Create alternative exit', 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-19 03:11:54', '2026-03-19 03:12:44', NULL, 1, 'approved', NULL),
(177, 11, 73, '02', 'EMIS', 'Administration', 0, 1, 1, NULL, 47, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-19 03:12:28', '2026-03-19 03:12:28', NULL, 1, 'approved', NULL),
(178, 11, 73, '03', 'LRC', 'Library', 0, 0, 1, NULL, 48, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-19 03:13:12', '2026-03-19 03:13:12', NULL, 1, 'approved', NULL),
(179, 11, 73, '04', '04', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-19 03:13:42', '2026-03-19 03:13:42', NULL, 1, 'approved', NULL),
(180, 11, 73, '05', '05', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', NULL, 3, 2, '2026-03-19 03:14:05', '2026-03-19 03:14:05', NULL, 1, 'approved', NULL),
(181, 11, 73, '06', '06', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Change Electric Fan Switch', 3, 2, '2026-03-19 03:14:55', '2026-03-19 03:14:55', NULL, 1, 'approved', NULL),
(182, 11, 73, '07', '07', 'Classroom', 0, 0, 0, 'Creation of Alternative Exit', 46, 'Shared Coverage (Up to 3 Classrooms)', 'Remove Defective Electric Oscillating Fan\r\nSecure electrical wires \r\nMove books to other location', 3, 2, '2026-03-19 03:17:44', '2026-03-19 03:17:44', NULL, 1, 'approved', NULL),
(183, 1, 74, '01', 'amande', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'okay', 3, 1, '2026-03-30 06:24:28', '2026-03-30 06:24:28', NULL, 1, 'approved', NULL),
(184, 9, 76, '01', 'Classroom Grade 1', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay roo', 3, 1, '2026-04-07 00:42:39', '2026-04-07 00:42:39', NULL, 1, 'approved', NULL),
(185, 9, 75, '01', 'Oks', 'Classroom', 0, 0, 1, NULL, 46, 'Shared Coverage (Up to 3 Classrooms)', 'Okay', 3, 1, '2026-04-07 00:44:30', '2026-04-07 00:44:30', NULL, 1, 'approved', NULL),
(186, 9, 75, '02', 'Classroom Grade 2', 'Classroom and Administration', 1, 1, 1, NULL, 156, 'Shared Space', 'Okay', 1, 2, '2026-04-07 00:45:26', '2026-04-07 00:45:26', NULL, 1, 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `incident_calendars`
--

CREATE TABLE `incident_calendars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'accepted',
  `contributor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `incident_date` date NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_name` varchar(255) NOT NULL,
  `entry_type` enum('incident','compliance') NOT NULL,
  `incident_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `incident_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_name` varchar(255) DEFAULT NULL,
  `attachment_size` bigint(20) UNSIGNED DEFAULT NULL,
  `attachment_mime` varchar(255) DEFAULT NULL,
  `reported_by` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` varchar(255) DEFAULT NULL,
  `affected_personnel` int(11) NOT NULL DEFAULT 0,
  `affected_students` int(11) NOT NULL DEFAULT 0,
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `incident_calendars`
--

INSERT INTO `incident_calendars` (`id`, `status`, `contributor_id`, `incident_date`, `school_id`, `school_name`, `entry_type`, `incident_type_id`, `incident_status_id`, `remarks`, `attachment_path`, `attachment_name`, `attachment_size`, `attachment_mime`, `reported_by`, `is_verified`, `verified_at`, `verified_by`, `affected_personnel`, `affected_students`, `additional_data`, `created_at`, `updated_at`) VALUES
(2, 'accepted', NULL, '2026-03-11', NULL, 'Integrated School at the Mabini High School', 'compliance', NULL, 4, 'Cancel Class Suspension news', NULL, NULL, NULL, NULL, 'Adan Kristopher B. Dumpit', 0, NULL, NULL, 0, 0, NULL, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(12, 'accepted', NULL, '2026-02-25', NULL, 'Integrated School at the Mabini High School', 'incident', 4, NULL, 'Minor landslide observed near hillside area; no injuries reported.', 'incident-attachments/2026/02/fNOXHnyGje792zSa6tKmffPxQo1ovKIhW73fybqv.png', 'Datasets-of-Landslide-Samples.png', 25671, 'image/png', 'Adan Kristopher B. Dumpit', 0, NULL, NULL, 0, 0, NULL, '2026-02-12 22:11:17', '2026-02-12 22:11:17'),
(15, 'accepted', NULL, '2026-03-12', NULL, 'Deped SDO department', 'compliance', NULL, 3, 'To practice personnel', NULL, NULL, NULL, NULL, 'Adan Kristopher B. Dumpit', 0, NULL, NULL, 0, 0, NULL, '2026-03-12 00:05:29', '2026-03-12 00:05:29'),
(20, 'accepted', NULL, '2026-03-16', NULL, 'OLONGAPO CITY NATIONAL HIGH SCHOOL', 'incident', 6, NULL, 'One room affected Location Admin Building Room 207, Cause of Fire Electrical (Broken Oscillating Fan), Approximately 1230H On scene City DRRMO, BFP, PNP,  and SDO DRRM Focal Person. Fire out 1245H as per officer Erwin Magaway and Officer Lising.', NULL, NULL, NULL, NULL, 'Adan Kristopher B. Dumpit', 0, NULL, NULL, 0, 0, NULL, '2026-03-19 00:33:45', '2026-03-19 00:33:45'),
(21, 'accepted', NULL, '2026-03-17', NULL, 'Gordon Heights National High School', 'incident', 9, NULL, 'Stabbing incident outside school premises involving outsider and 2 GHNHS Students. Outsider was rushed to the nearest hospital for treatment. Later parents of involve d students and outsider set Barangay Meeting to settle the concerned issue.', NULL, NULL, NULL, NULL, 'Adan Kristopher B. Dumpit', 0, NULL, NULL, 0, 2, NULL, '2026-03-19 00:41:18', '2026-03-19 00:41:18'),
(22, 'accepted', 7, '2026-03-23', NULL, 'Integrated School at the Mabini High School', 'incident', 8, NULL, 'This Love', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2, 0, NULL, '2026-03-23 01:44:08', '2026-03-23 01:44:08'),
(23, 'accepted', 1, '2026-03-26', NULL, 'Integrated School at the Mabini High School', 'compliance', NULL, 6, 'oks', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, '2026-03-23 15:43:12', '2026-03-23 15:43:12');

-- --------------------------------------------------------

--
-- Table structure for table `incident_checklists`
--

CREATE TABLE `incident_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `checklist_date` date NOT NULL,
  `label` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `incident_checklists`
--

INSERT INTO `incident_checklists` (`id`, `user_id`, `checklist_date`, `label`, `is_default`, `is_deleted`, `is_completed`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 1, '2026-02-09', 'Incident Verification Completed', 0, 0, 0, 1, '2026-02-08 22:58:19', '2026-02-08 22:58:41'),
(3, 1, '2026-02-09', 'Victim Assistance Log Updated', 0, 0, 1, 2, '2026-02-08 22:58:19', '2026-02-08 22:58:44'),
(4, 1, '2026-02-09', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-02-08 22:58:19', '2026-02-08 22:58:19'),
(5, 1, '2026-02-09', 'New ways to do it', 0, 0, 1, 4, '2026-02-08 22:58:56', '2026-02-08 22:59:02'),
(6, 1, '2026-02-10', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-02-09 16:15:32', '2026-02-09 16:15:32'),
(7, 1, '2026-02-10', 'Incident Verification Completed', 0, 0, 0, 1, '2026-02-09 16:15:32', '2026-02-09 16:15:32'),
(8, 1, '2026-02-10', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-02-09 16:15:32', '2026-02-09 16:15:32'),
(9, 1, '2026-02-10', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-02-09 16:15:32', '2026-02-09 16:15:32'),
(10, 1, '2026-02-12', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-02-11 17:48:14', '2026-02-11 17:48:14'),
(11, 1, '2026-02-12', 'Incident Verification Completed', 0, 0, 0, 1, '2026-02-11 17:48:16', '2026-02-11 17:48:16'),
(12, 1, '2026-02-12', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-02-11 17:48:16', '2026-02-11 17:48:16'),
(13, 1, '2026-02-12', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-02-11 17:48:16', '2026-02-11 17:48:16'),
(14, 1, '2026-02-13', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-02-12 16:42:20', '2026-02-12 16:42:20'),
(15, 1, '2026-02-13', 'Incident Verification Completed', 0, 0, 0, 1, '2026-02-12 16:42:20', '2026-02-12 16:42:20'),
(16, 1, '2026-02-13', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-02-12 16:42:20', '2026-02-12 16:42:20'),
(17, 1, '2026-02-13', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-02-12 16:42:20', '2026-02-12 16:42:20'),
(18, 1, '2026-02-16', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-02-15 16:59:38', '2026-02-15 16:59:38'),
(19, 1, '2026-02-16', 'Incident Verification Completed', 0, 0, 1, 1, '2026-02-15 16:59:38', '2026-02-15 23:17:14'),
(20, 1, '2026-02-16', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-02-15 16:59:38', '2026-02-15 16:59:38'),
(21, 1, '2026-02-16', 'School Head Confirmation Received', 0, 0, 1, 3, '2026-02-15 16:59:38', '2026-02-15 23:17:18'),
(22, 1, '2026-02-18', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-02-17 16:04:38', '2026-02-17 16:04:38'),
(23, 1, '2026-02-18', 'Incident Verification Completed', 0, 0, 0, 1, '2026-02-17 16:04:38', '2026-02-17 16:04:38'),
(24, 1, '2026-02-18', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-02-17 16:04:38', '2026-02-17 16:04:38'),
(25, 1, '2026-02-18', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-02-17 16:04:38', '2026-02-17 16:04:38'),
(26, 1, '2026-02-19', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-02-18 19:13:06', '2026-02-18 19:13:06'),
(27, 1, '2026-02-19', 'Incident Verification Completed', 0, 0, 0, 1, '2026-02-18 19:13:06', '2026-02-18 19:13:06'),
(28, 1, '2026-02-19', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-02-18 19:13:06', '2026-02-18 19:13:06'),
(29, 1, '2026-02-19', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-02-18 19:13:06', '2026-02-18 19:13:06'),
(30, 1, '2026-03-03', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-03 00:32:00', '2026-03-03 00:32:00'),
(31, 1, '2026-03-03', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-03 00:32:00', '2026-03-03 00:32:00'),
(32, 1, '2026-03-03', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-03 00:32:00', '2026-03-03 00:32:00'),
(33, 1, '2026-03-03', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-03 00:32:00', '2026-03-03 00:32:00'),
(34, 1, '2026-03-04', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-03 21:20:11', '2026-03-03 21:20:11'),
(35, 1, '2026-03-04', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-03 21:20:11', '2026-03-03 21:20:11'),
(36, 1, '2026-03-04', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-03 21:20:11', '2026-03-03 21:20:11'),
(37, 1, '2026-03-04', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-03 21:20:11', '2026-03-03 21:20:11'),
(38, 8, '2026-03-05', 'Daily Monitoring Report Submitted', 0, 0, 1, 0, '2026-03-04 21:16:31', '2026-03-04 21:18:44'),
(39, 8, '2026-03-05', 'Incident Verification Completed', 0, 0, 1, 1, '2026-03-04 21:16:31', '2026-03-04 21:18:47'),
(40, 8, '2026-03-05', 'Victim Assistance Log Updated', 0, 0, 1, 2, '2026-03-04 21:16:31', '2026-03-04 21:18:48'),
(41, 8, '2026-03-05', 'School Head Confirmation Received', 0, 0, 1, 3, '2026-03-04 21:16:31', '2026-03-04 21:18:50'),
(42, 5, '2026-03-05', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-04 23:36:47', '2026-03-04 23:36:47'),
(43, 5, '2026-03-05', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-04 23:36:47', '2026-03-04 23:36:47'),
(44, 5, '2026-03-05', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-04 23:36:47', '2026-03-04 23:36:47'),
(45, 5, '2026-03-05', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-04 23:36:47', '2026-03-04 23:36:47'),
(46, 1, '2026-03-12', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-11 21:51:01', '2026-03-11 21:51:01'),
(47, 1, '2026-03-12', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-11 21:51:01', '2026-03-11 21:51:01'),
(48, 1, '2026-03-12', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-11 21:51:01', '2026-03-11 21:51:01'),
(49, 1, '2026-03-12', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-11 21:51:01', '2026-03-11 21:51:01'),
(50, 5, '2026-03-12', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-12 00:12:48', '2026-03-12 00:12:48'),
(51, 5, '2026-03-12', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-12 00:12:48', '2026-03-12 00:12:48'),
(52, 5, '2026-03-12', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-12 00:12:48', '2026-03-12 00:12:48'),
(53, 5, '2026-03-12', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-12 00:12:48', '2026-03-12 00:12:48'),
(54, 1, '2026-03-13', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-13 01:02:19', '2026-03-13 01:02:19'),
(55, 1, '2026-03-13', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-13 01:02:19', '2026-03-13 01:02:19'),
(56, 1, '2026-03-13', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-13 01:02:19', '2026-03-13 01:02:19'),
(57, 1, '2026-03-13', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-13 01:02:19', '2026-03-13 01:02:19'),
(58, 1, '2026-03-15', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-14 17:55:31', '2026-03-14 17:55:31'),
(59, 1, '2026-03-15', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-14 17:55:31', '2026-03-14 17:55:31'),
(60, 1, '2026-03-15', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-14 17:55:31', '2026-03-14 17:55:31'),
(61, 1, '2026-03-15', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-14 17:55:31', '2026-03-14 17:55:31'),
(62, 1, '2026-03-16', 'Daily Monitoring Report Submitted', 0, 0, 1, 0, '2026-03-15 17:41:52', '2026-03-15 23:59:41'),
(63, 1, '2026-03-16', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-15 17:41:52', '2026-03-15 22:41:43'),
(64, 1, '2026-03-16', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-15 17:41:52', '2026-03-15 17:41:52'),
(66, 1, '2026-03-16', 'Go to Jackson and inspect fire extinguisher there?', 0, 0, 1, 4, '2026-03-15 18:26:38', '2026-03-15 18:26:40'),
(67, 8, '2026-03-16', 'Daily Monitoring Report Submitted', 0, 0, 0, 0, '2026-03-15 18:48:53', '2026-03-15 18:48:53'),
(68, 8, '2026-03-16', 'Incident Verification Completed', 0, 0, 0, 1, '2026-03-15 18:48:53', '2026-03-15 18:48:53'),
(69, 8, '2026-03-16', 'Victim Assistance Log Updated', 0, 0, 0, 2, '2026-03-15 18:48:53', '2026-03-15 18:48:53'),
(70, 8, '2026-03-16', 'School Head Confirmation Received', 0, 0, 0, 3, '2026-03-15 18:48:53', '2026-03-15 18:48:53'),
(71, 1, '2026-03-17', 'Daily Monitoring Report Submitted', 1, 0, 0, 0, '2026-03-16 16:40:52', '2026-03-16 16:40:52'),
(72, 1, '2026-03-17', 'Incident Verification Completed', 1, 1, 0, 1, '2026-03-16 16:40:52', '2026-03-16 16:48:27'),
(73, 1, '2026-03-17', 'Victim Assistance Log Updated', 1, 1, 0, 2, '2026-03-16 16:40:52', '2026-03-16 16:48:33'),
(74, 1, '2026-03-17', 'Go to Jackson and inspect fire extinguisher there?', 0, 1, 0, 3, '2026-03-16 16:40:52', '2026-03-16 16:48:30'),
(75, 1, '2026-03-17', 'Encode and put all info that has happened this week', 0, 0, 1, 4, '2026-03-16 16:48:59', '2026-03-16 16:49:01'),
(76, 1, '2026-03-18', 'Daily Monitoring Report Submitted', 1, 0, 1, 0, '2026-03-17 16:49:17', '2026-03-17 16:50:20'),
(77, 1, '2026-03-18', 'Encode and put all info that has happened this week', 0, 0, 0, 1, '2026-03-17 16:49:17', '2026-03-17 16:49:17'),
(78, 1, '2026-03-18', 'Other task (outdoor)', 0, 0, 1, 2, '2026-03-17 16:50:17', '2026-03-17 16:50:18'),
(79, 1, '2026-03-19', 'Daily Monitoring Report Submitted', 1, 0, 0, 0, '2026-03-18 16:51:05', '2026-03-18 16:51:05'),
(80, 1, '2026-03-19', 'Encode and put all info that has happened this week', 0, 0, 0, 1, '2026-03-18 16:51:06', '2026-03-18 16:51:06'),
(81, 1, '2026-03-19', 'Other task (outdoor)', 0, 0, 0, 2, '2026-03-18 16:51:06', '2026-03-18 16:51:06'),
(82, 1, '2026-03-19', 'Go to elementary school', 0, 0, 0, 3, '2026-03-19 06:04:33', '2026-03-19 06:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `incident_statuses`
--

CREATE TABLE `incident_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `color_class` varchar(255) NOT NULL,
  `short_code` varchar(255) NOT NULL,
  `is_compliance` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `incident_statuses`
--

INSERT INTO `incident_statuses` (`id`, `name`, `color_class`, `short_code`, `is_compliance`, `created_at`, `updated_at`) VALUES
(1, 'Holiday', 'status-holiday', 'H', 1, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(2, 'Incident In School', 'status-incident', 'I', 0, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(3, 'Classes/Work Suspended', 'status-suspended', 'S', 1, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(4, 'No Class Suspension', 'status-no-suspension', 'N', 1, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(5, 'Suspended F2F Classes', 'status-f2f-suspended', 'F', 1, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(6, 'Fire Drilling', 'status-no-suspension', 'F', 1, '2026-03-12 00:04:20', '2026-03-12 00:04:20');

-- --------------------------------------------------------

--
-- Table structure for table `incident_types`
--

CREATE TABLE `incident_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `color_class` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `incident_types`
--

INSERT INTO `incident_types` (`id`, `name`, `color_class`, `description`, `priority`, `created_at`, `updated_at`) VALUES
(1, 'Tropical Cyclones', 'type-cyclone', NULL, 1, '2026-02-09 00:04:51', '2026-03-03 21:30:07'),
(2, 'Heavy Rainfall', 'type-rainfall', NULL, 2, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(3, 'Earthquake', 'type-earthquake', NULL, 1, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(4, 'Landslide', 'type-landslide', NULL, 1, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(5, 'Flooding', 'type-flooding', NULL, 2, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(6, 'Fire', 'type-fire', NULL, 1, '2026-02-09 00:04:51', '2026-03-03 21:30:24'),
(7, 'Accidents', 'type-accident', NULL, 3, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(8, 'Violence/Conflict', 'type-violence', NULL, 1, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(9, 'Others', 'type-others', NULL, 4, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(10, 'Volcano Eruption', 'type-others', NULL, 5, '2026-02-09 00:04:51', '2026-02-09 00:04:51'),
(11, 'Drought', 'type-others', NULL, 6, '2026-02-17 16:05:07', '2026-02-17 16:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_28_031158_add_role_to_users_table', 1),
(5, '2026_01_28_031531_create_firesafety_school_information_table', 1),
(6, '2026_01_28_031540_create_firesafety_buildings_table', 1),
(7, '2026_01_28_031620_create_firesafety_fire_extinguishers_table', 1),
(8, '2026_01_28_031737_create_firesafety_alarm_systems_table', 1),
(9, '2026_01_28_031842_create_firesafety_evacuationplans_table', 1),
(10, '2026_01_28_081051_add_more_columns_to_firesafety_buildings_table', 1),
(11, '2026_01_28_082521_add_building_id_to_firesafety_fire_extinguishers_table', 1),
(12, '2026_01_29_004322_add_missing_columns_to_alarm_systems_table', 1),
(13, '2026_01_29_021022_update_alarm_systems_status_enum', 1),
(14, '2026_01_29_035331_create_fire_safety_inspections_table', 1),
(15, '2026_01_29_120000_create_fire_safety_rooms_table', 1),
(16, '2026_01_29_120100_add_room_id_to_firesafety_fire_extinguishers_table', 1),
(17, '2026_01_29_120200_create_fire_safety_extinguisher_room_coverage_table', 1),
(18, '2026_01_30_090000_update_fire_extinguishers_and_inspections', 1),
(19, '2026_01_30_095000_add_address_to_firesafety_school_information', 2),
(20, '2026_01_30_052436_add_columns_to_firesafety_evacuationplans_table', 3),
(21, '2026_01_30_072826_add_school_id_and_module_access_to_users_table', 4),
(22, '2026_01_30_073240_create_system_configurations_table', 5),
(23, '2026_01_30_073647_create_fire_safety_evacuation_drills_table', 6),
(24, '2026_01_30_074640_add_approved_at_to_firesafety_evacuationplans_table', 7),
(25, '2026_02_02_000000_ensure_building_id_on_firesafety_evacuationplans', 8),
(26, '2026_02_02_075606_add_map_data_to_firesafety_evacuationplans_table', 9),
(27, '2026_02_02_081642_add_details_to_fire_safety_evacuation_drills_table', 10),
(28, '2026_02_02_081708_create_fire_safety_drill_building_table', 10),
(29, '2026_02_02_085629_create_fire_safety_alarm_building_table', 11),
(30, '2026_02_04_160000_add_remarks_to_fire_extinguishers_table', 12),
(31, '2026_02_05_160000_update_firesafety_tables', 13),
(32, '2026_02_05_063905_update_firesafety_status_and_building_limits_table', 14),
(33, '2026_02_06_031041_create_fire_safety_archives_table', 15),
(34, '2026_02_06_071117_add_evacuation_map_layout_to_schools_table', 16),
(35, '2026_02_06_142228_add_alerts_and_events_to_firesafety_school_information', 17),
(36, '2026_02_06_142249_add_alerts_and_events_to_firesafety_school_information_v2', 17),
(37, '2026_02_09_023042_create_incident_types_table', 17),
(38, '2026_02_09_023111_create_incident_statuses_table', 17),
(39, '2026_02_09_023119_create_incident_calendars_table', 17),
(40, '2026_02_09_023125_create_incident_schools_table', 17),
(41, '2026_02_09_100000_create_incident_checklists_table', 18),
(42, '2026_02_10_100000_make_fire_safety_archives_school_id_nullable', 19),
(43, '2026_02_10_100001_add_building_limits_to_system_configurations', 19),
(44, '2026_02_10_100002_add_parent_id_to_system_configurations', 19),
(45, '2026_02_10_100003_add_pressure_range_to_system_configurations', 19),
(46, '2026_02_10_100004_add_room_types_and_calculated_priorities', 20),
(47, '2026_02_10_100005_update_fire_safety_rooms_for_room_types', 20),
(48, '2026_02_10_080353_fix_cascade_deletes_for_fire_safety', 21),
(49, '2026_02_10_080434_create_firesafety_school_snapshots_table', 22),
(50, '2026_02_11_130544_update_fire_safety_inspections_table_v2', 23),
(51, '2026_02_12_015504_create_announcements_table', 24),
(52, '2026_02_12_120000_create_typ_fld_evacuation_centers_table', 25),
(53, '2026_02_12_120010_create_typ_fld_families_table', 25),
(54, '2026_02_12_120020_create_typ_fld_family_members_table', 25),
(55, '2026_02_12_120030_create_typ_fld_monitoring_snapshots_table', 25),
(56, '2026_02_12_130000_create_pie_pra_scenarios_table', 26),
(57, '2026_02_12_130010_create_pie_pra_recommendations_table', 26),
(58, '2026_02_12_130020_create_pie_pra_volunteers_table', 26),
(59, '2026_02_12_130030_create_pie_pra_volunteer_skills_table', 26),
(60, '2026_02_12_130040_create_pie_pra_volunteer_assignments_table', 26),
(61, '2026_02_13_004642_add_usage_status_to_evacuation_centers', 27),
(62, '2026_02_13_004642_fix_missing_columns_in_typhoon_tables', 27),
(63, '2026_02_13_004642_update_typhoon_tables_v2', 27),
(64, '2026_02_13_055753_add_attachments_to_incident_calendars_table', 27),
(65, '2026_02_16_072211_add_status_to_users_table', 28),
(66, '2026_02_16_152000_add_status_to_users_table', 28),
(67, '2026_02_18_003323_add_floor_id_to_firesafety_alarm_systems_table', 29),
(68, '2026_02_18_005534_add_replies_to_firesafety_school_information', 30),
(69, '2026_02_18_121500_update_typ_fld_evacuation_centers_columns', 31),
(70, '2026_02_19_095000_add_smoke_detector_to_fire_safety_rooms', 32),
(71, '2026_02_27_010753_add_primary_route_to_firesafety_evacuationplans_table', 33),
(72, '2026_02_27_020000_add_safety_features_to_evacuation_plans', 34),
(73, '2026_02_27_071906_fix_evacuation_plans_status_column', 35),
(74, '2026_02_27_075502_make_evacuation_plans_fields_nullable', 36),
(75, '2026_02_27_120000_add_compliance_to_buildings', 37),
(76, '2026_02_27_130000_add_unique_plan_constraint', 38),
(77, '2026_02_27_132858_add_compliance_fields_to_firesafety_buildings', 39),
(78, '2026_02_27_132908_add_unique_plan_constraint_to_evacuation_plans', 40),
(79, '2026_03_04_000942_add_remarks_to_fire_safety_rooms_table', 41),
(80, '2026_03_04_144841_add_inspector_and_approval_to_fire_safety_rooms', 42),
(81, '2026_03_05_064129_add_has_secondary_exit_to_fire_safety_rooms_table', 43),
(82, '2026_03_05_064349_add_secondary_exit_remarks_to_fire_safety_rooms_table', 44),
(83, '2026_03_07_025114_add_smoke_detector_required_to_fire_safety_rooms', 45),
(84, '2026_03_10_014346_add_monitored_by_position_to_fire_safety_inspections', 45),
(85, '2026_03_11_000000_create_firesafety_notifications_table', 46),
(86, '2026_03_12_000000_create_notifications_table', 47),
(87, '2026_03_12_100000_create_activity_logs_table', 48),
(88, '2026_03_13_120000_add_shared_space_priority_for_classroom_and_administration', 49),
(89, '2026_03_13_121000_add_required_extinguishers_to_calculated_priorities', 50),
(90, '2026_03_16_043352_add_typhoon_school_id_to_users_table', 51),
(91, '2026_03_17_100000_update_incident_checklists_add_is_default_and_is_deleted', 52),
(92, '2026_03_17_112454_add_attached_evacuation_map_to_schools_table', 53),
(93, '2026_03_19_164756_add_status_and_contributor_to_incident_calendars', 54),
(94, '2026_03_23_090000_add_incident_school_id_to_users_table', 55),
(95, '2025_02_05_000001_create_school_safety_tables', 56),
(107, '2026_03_26_100001_create_schools_table', 57),
(108, '2026_03_26_100002_create_school_specifics_information_table', 57),
(109, '2026_03_26_100003_migrate_school_data_to_unified_tables', 57),
(110, '2026_03_26_100004_add_unified_id_to_existing_tables', 58),
(111, '2026_03_27_214722_align_fire_safety_tables_to_unified_schools', 59),
(112, '2026_03_29_120000_drop_legacy_module_school_tables', 60),
(113, '2026_03_30_100000_alarm_anchor_and_evacuation_plan_school_nullable', 61),
(114, '2026_03_30_110000_add_students_personnel_to_schools_table', 62),
(115, '2026_04_06_120000_add_position_to_users_table', 62);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `compliance_type` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `action_type` varchar(255) DEFAULT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `action_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`action_data`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `compliance_type`, `module`, `school_id`, `user_id`, `type`, `title`, `message`, `action_type`, `action_url`, `action_data`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'fire_safety', 'fire_safety', 4, 3, 'extinguisher_inspection', 'Extinguisher Inspected: FRXT-09', 'Extinguisher FRXT-09 was inspected. Status: Maintenance, Pressure: 60%', 'update_now', NULL, '{\"extinguisher_id\":56,\"school_id\":14}', 1, '2026-03-11 17:08:52', '2026-03-11 18:20:56'),
(2, 'fire_safety', 'fire_safety', 4, 3, 'extinguisher_inspection', 'Extinguisher Inspected: FRXT-09', 'Extinguisher FRXT-09 was inspected. Status: Maintenance, Pressure: 60%', 'update_now', NULL, '{\"extinguisher_id\":56,\"school_id\":14}', 1, '2026-03-11 17:08:53', '2026-03-11 18:20:59'),
(3, 'fire_safety', 'fire_safety', 7, NULL, 'alarm_due', 'Alarm Test Due Today: 001', 'Alarm 001 is scheduled for testing today.', 'go_test', NULL, '{\"alarm_id\":20,\"school_id\":17}', 1, '2026-03-11 17:17:57', '2026-03-18 02:05:20'),
(4, 'fire_safety', 'fire_safety', 8, 1, 'room_update', 'Room Updated: 23', 'Adan Kristopher B. Dumpit updated room 23. Changes: Remarks updated', 'see_inspection', NULL, '{\"room_id\":87,\"school_id\":18,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 18:12:17', '2026-03-18 02:05:20'),
(5, 'fire_safety', 'fire_safety', 4, 3, 'building_update', 'Building Updated: 02', 'Dolores A. Umbina updated building Okay room. Changes: Last Renovation: 2022, Description updated', NULL, NULL, '{\"building_id\":58,\"school_id\":14,\"updated_by\":\"Dolores A. Umbina\"}', 1, '2026-03-11 18:14:43', '2026-03-18 02:05:20'),
(6, 'fire_safety', 'fire_safety', 3, 4, 'inspection', 'Inspection Completed: Fire', 'Fire inspection at Bangal Integrated School on 2026-03-15. Monitored by: Zaldy Danaytan, Jr.', 'see_inspection', NULL, '{\"inspection_id\":4,\"school_id\":\"13\"}', 1, '2026-03-11 18:18:52', '2026-03-11 18:26:26'),
(7, 'fire_safety', 'fire_safety', 3, 4, 'room_approval', 'Room Update Pending Approval: 01', 'Zaldy Danaytan, Jr. updated room 01 and it requires administrator approval. Changes: Secondary Exit: No', 'see_inspection', NULL, '{\"room_id\":96,\"school_id\":13,\"status\":\"pending\",\"updated_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-11 18:23:47', '2026-03-11 18:26:25'),
(8, 'fire_safety', 'fire_safety', 3, 1, 'room_approval', 'Room Update Approved', 'Room 01 has been approved by Adan Kristopher B. Dumpit.', 'see_inspection', NULL, '{\"room_id\":96,\"school_id\":13,\"status\":\"approved\"}', 1, '2026-03-11 18:24:39', '2026-03-11 18:26:22'),
(9, 'fire_safety', 'fire_safety', 3, 1, 'room_approval', 'Room Update Rejected', 'Room 01 has been rejected. Reason: The room info is incompleted and needed of fire extinguisher', 'see_inspection', NULL, '{\"room_id\":95,\"school_id\":13,\"status\":\"rejected\"}', 1, '2026-03-11 18:25:40', '2026-03-11 18:26:19'),
(10, 'fire_safety', 'fire_safety', 4, 1, 'event', 'Event: The stray and stucky event', 'To provide stray animals more comfort adopt it for the students or pet them temporarily | Date: 2026-03-20 at 11:00 (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"event_date\":\"2026-03-20\",\"event_time\":\"11:00\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 18:42:54', '2026-03-18 02:05:20'),
(11, 'fire_safety', 'fire_safety', 1, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(12, 'fire_safety', 'fire_safety', 2, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(13, 'fire_safety', 'fire_safety', 3, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(14, 'fire_safety', 'fire_safety', 4, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(15, 'fire_safety', 'fire_safety', 5, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(16, 'fire_safety', 'fire_safety', 6, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(17, 'fire_safety', 'fire_safety', 7, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(18, 'fire_safety', 'fire_safety', 8, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(19, 'fire_safety', 'fire_safety', 9, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(20, 'fire_safety', 'fire_safety', 10, 1, 'alert', 'Alert: Evacuation area must clear', 'All schools need to check now (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:01:21', '2026-03-18 02:05:20'),
(21, 'fire_safety', 'fire_safety', 4, 3, 'room_approval', 'Room Update Pending Approval: 05', 'Dolores A. Umbina updated room 05 and it requires administrator approval. Changes: Name: Storage Room, filled, Remarks updated', 'see_inspection', NULL, '{\"room_id\":122,\"school_id\":14,\"status\":\"pending\",\"updated_by\":\"Dolores A. Umbina\"}', 1, '2026-03-11 19:05:08', '2026-03-18 02:05:20'),
(22, 'fire_safety', 'fire_safety', 4, 1, 'alert', 'Alert: Suntukan', 'May nangyari (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"alert_type\":\"warning\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:25:06', '2026-03-18 02:05:20'),
(23, 'fire_safety', 'fire_safety', 3, 4, 'evacuation_plan', 'Evacuation Plan Created: Plan A', 'Zaldy Danaytan, Jr. created a new evacuation plan \"Plan A\" - School-Wide Plan', NULL, NULL, '{\"plan_id\":3,\"plan_type\":\"school\",\"posted_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-11 19:30:58', '2026-03-18 02:05:20'),
(24, 'fire_safety', 'fire_safety', 3, 4, 'evacuation_plan', 'Evacuation Plan Updated: Plan A', 'Zaldy Danaytan, Jr. updated evacuation plan \"Plan A\" - School-Wide Plan', NULL, NULL, '{\"plan_id\":3,\"plan_type\":\"school\",\"posted_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-11 19:31:18', '2026-03-18 02:05:20'),
(25, 'fire_safety', 'fire_safety', 3, 4, 'evacuation_plan', 'Evacuation Plan Created: Pwede na', 'Zaldy Danaytan, Jr. created a new evacuation plan \"Pwede na\" - Building Plan (Unknown)', NULL, NULL, '{\"plan_id\":4,\"plan_type\":\"building\",\"posted_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-11 19:31:49', '2026-03-18 02:05:20'),
(26, 'fire_safety', 'fire_safety', 3, 4, 'evacuation_plan', 'Evacuation Map Updated: Bangal Integrated School', 'Zaldy Danaytan, Jr. updated the evacuation map layout. Details: New placement', NULL, NULL, '{\"plan_type\":\"map\",\"posted_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-11 19:32:19', '2026-03-18 02:05:20'),
(27, 'fire_safety', 'fire_safety', 8, 1, 'event', 'Event: Kono omoi wo', 'Ni hungry | Date: 2026-04-01 at 02:41 (Posted by: Adan Kristopher B. Dumpit)', NULL, NULL, '{\"event_date\":\"2026-04-01\",\"event_time\":\"02:41\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 19:41:33', '2026-03-18 02:05:20'),
(28, 'fire_safety', 'fire_safety', 4, 3, 'alert', 'Alert: Pagbigyan mo ako', 'Pagbigyan mo ako (Posted by: Dolores A. Umbina)', NULL, NULL, '{\"alert_type\":\"danger\",\"posted_by\":\"Dolores A. Umbina\"}', 1, '2026-03-11 19:58:58', '2026-03-18 02:05:20'),
(31, 'fire_safety', 'fire_safety', 1, 1, 'room_update', 'Room Updated: Room-010', 'Adan Kristopher B. Dumpit updated room Room-010. Changes: Remarks updated', 'see_inspection', NULL, '{\"room_id\":47,\"school_id\":11,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-11 23:55:05', '2026-03-18 02:05:20'),
(32, 'fire_safety', 'fire_safety', 9, 1, 'evacuation_plan', 'Evacuation Map Updated: New Cabalan Elementary School', 'Adan Kristopher B. Dumpit updated the evacuation map layout. Details: Need buildings to register', NULL, NULL, '{\"plan_type\":\"map\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-12 00:00:32', '2026-03-18 02:05:20'),
(33, 'fire_safety', 'fire_safety', 5, 1, 'extinguisher_inspection', 'Extinguisher Inspected: FRXT-01', 'Extinguisher FRXT-01 was inspected. Status: Maintenance, Pressure: 59%', 'update_now', NULL, '{\"extinguisher_id\":57,\"school_id\":15}', 1, '2026-03-12 00:03:05', '2026-03-18 02:05:20'),
(34, 'fire_safety', 'fire_safety', 4, 1, 'extinguisher_inspection', 'Extinguisher Inspected: FRXT-09', 'Extinguisher FRXT-09 was inspected. Status: Active, Pressure: 70%', 'update_now', NULL, '{\"extinguisher_id\":56,\"school_id\":14}', 1, '2026-03-12 00:31:11', '2026-03-18 02:05:20'),
(35, 'fire_safety', 'fire_safety', 2, 1, 'room_update', 'Room Updated: 01', 'Adan Kristopher B. Dumpit updated room 01. Changes: Secondary Exit: Yes, Remarks updated', 'see_inspection', NULL, '{\"room_id\":57,\"school_id\":12,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-12 00:32:17', '2026-03-18 02:05:20'),
(36, 'fire_safety', 'fire_safety', 10, 5, 'room_update', 'Room Updated: 01', 'Erwin A. Castillejo updated room 01. Changes: Secondary Exit: Yes', 'see_inspection', NULL, '{\"room_id\":93,\"school_id\":20,\"updated_by\":\"Erwin A. Castillejo\"}', 1, '2026-03-12 00:32:49', '2026-03-18 02:05:20'),
(37, 'fire_safety', 'fire_safety', 2, 1, 'evacuation_plan', 'Evacuation Plan Created: Olan a', 'Adan Kristopher B. Dumpit created a new evacuation plan \"Olan a\" - School-Wide Plan', NULL, NULL, '{\"plan_id\":5,\"plan_type\":\"school\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-12 00:32:59', '2026-03-18 02:05:20'),
(38, 'fire_safety', 'fire_safety', 10, 5, 'room_update', 'Room Updated: 02', 'Erwin A. Castillejo updated room 02. Changes: Secondary Exit: Yes', 'see_inspection', NULL, '{\"room_id\":94,\"school_id\":20,\"updated_by\":\"Erwin A. Castillejo\"}', 1, '2026-03-12 00:33:08', '2026-03-18 02:05:20'),
(39, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 01', 'Extinguisher 01 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":39,\"school_id\":20}', 1, '2026-03-12 00:35:46', '2026-03-18 02:05:20'),
(40, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 02', 'Extinguisher 02 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":40,\"school_id\":20}', 1, '2026-03-12 00:38:55', '2026-03-18 02:05:20'),
(41, 'fire_safety', 'fire_safety', 10, 5, 'building_update', 'Building Updated: 003', 'Erwin A. Castillejo updated building 3. Changes: Name: 3', NULL, NULL, '{\"building_id\":54,\"school_id\":20,\"updated_by\":\"Erwin A. Castillejo\"}', 1, '2026-03-12 00:41:28', '2026-03-18 02:05:20'),
(42, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 09', 'Extinguisher 09 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":46,\"school_id\":20}', 1, '2026-03-12 00:43:27', '2026-03-18 02:05:20'),
(43, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 10', 'Extinguisher 10 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":47,\"school_id\":20}', 1, '2026-03-12 00:44:03', '2026-03-18 02:05:20'),
(44, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 12', 'Extinguisher 12 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":59,\"school_id\":20}', 1, '2026-03-12 00:48:29', '2026-03-18 02:05:20'),
(45, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 06', 'Extinguisher 06 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":43,\"school_id\":20}', 1, '2026-03-12 00:58:41', '2026-03-18 02:05:20'),
(46, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 07', 'Extinguisher 07 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":44,\"school_id\":20}', 1, '2026-03-12 00:59:08', '2026-03-18 02:05:20'),
(47, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 04', 'Extinguisher 04 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":61,\"school_id\":20}', 1, '2026-03-12 00:59:47', '2026-03-18 02:05:20'),
(48, 'fire_safety', 'fire_safety', 10, 5, 'extinguisher_inspection', 'Extinguisher Inspected: 08', 'Extinguisher 08 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":45,\"school_id\":20}', 1, '2026-03-12 01:04:49', '2026-03-18 02:05:20'),
(49, 'fire_safety', 'fire_safety', 10, 5, 'building_update', 'Building Updated: 001', 'Erwin A. Castillejo updated building 001. Changes: Required Extinguishers: 2', NULL, NULL, '{\"building_id\":49,\"school_id\":20,\"updated_by\":\"Erwin A. Castillejo\"}', 1, '2026-03-12 01:05:57', '2026-03-18 02:05:20'),
(50, 'fire_safety', 'fire_safety', 10, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 15', 'Extinguisher 15 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":62,\"school_id\":20}', 1, '2026-03-16 00:53:44', '2026-03-18 02:05:20'),
(51, 'fire_safety', 'fire_safety', 10, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 14', 'Extinguisher 14 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":60,\"school_id\":20}', 1, '2026-03-16 00:53:58', '2026-03-18 02:05:20'),
(52, 'fire_safety', 'fire_safety', 10, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 13', 'Extinguisher 13 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":59,\"school_id\":20}', 1, '2026-03-16 00:57:16', '2026-03-18 02:05:20'),
(53, 'fire_safety', 'fire_safety', 10, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 12', 'Extinguisher 12 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":58,\"school_id\":20}', 1, '2026-03-16 00:57:36', '2026-03-18 02:05:20'),
(54, 'fire_safety', 'fire_safety', 10, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 11', 'Extinguisher 11 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":47,\"school_id\":20}', 1, '2026-03-16 00:58:10', '2026-03-18 02:05:20'),
(55, 'fire_safety', 'fire_safety', 10, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 10', 'Extinguisher 10 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":46,\"school_id\":20}', 1, '2026-03-16 00:58:38', '2026-03-18 02:05:20'),
(56, 'fire_safety', 'fire_safety', 10, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 09', 'Extinguisher 09 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":63,\"school_id\":20}', 1, '2026-03-16 00:59:00', '2026-03-18 02:05:20'),
(57, 'fire_safety', 'fire_safety', 4, 1, 'building_update', 'Building Updated: 02', 'Adan Kristopher B. Dumpit updated building Okay room. Changes: Required Extinguishers: 4', NULL, NULL, '{\"building_id\":58,\"school_id\":14,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-16 17:25:10', '2026-03-18 02:05:20'),
(58, 'fire_safety', 'fire_safety', 3, 1, 'room_update', 'Room Updated: 01', 'Adan Kristopher B. Dumpit updated room 01. Changes: Secondary Exit: Yes', 'see_inspection', NULL, '{\"room_id\":95,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-16 17:37:18', '2026-03-18 02:05:20'),
(59, 'fire_safety', 'fire_safety', 3, 1, 'alarm_due', 'Alarm Tested: 11', 'Alarm 11 has been tested successfully.', 'go_test', NULL, '{\"alarm_id\":33,\"school_id\":13}', 1, '2026-03-16 17:40:29', '2026-03-18 02:05:20'),
(60, 'fire_safety', 'fire_safety', 3, 1, 'room_update', 'Room Updated: 01', 'Adan Kristopher B. Dumpit updated room 01. Changes: Secondary Exit: Yes', 'see_inspection', NULL, '{\"room_id\":96,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-16 17:51:12', '2026-03-18 02:05:20'),
(61, 'fire_safety', 'fire_safety', 3, 4, 'room_approval', 'New Room Created (Pending Approval)', 'Contributor Zaldy Danaytan, Jr. created a new room and it requires administrator approval.', NULL, NULL, '{\"posted_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-16 18:07:56', '2026-03-18 02:05:20'),
(62, 'fire_safety', 'fire_safety', 3, 4, 'extinguisher_inspection', 'Extinguisher Inspected: FR-XT 02', 'Extinguisher FR-XT 02 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":65,\"school_id\":13}', 1, '2026-03-16 18:09:56', '2026-03-18 02:05:20'),
(63, 'fire_safety', 'fire_safety', 3, 1, 'room_approval', 'Room Update Approved', 'Room 04 has been approved by Adan Kristopher B. Dumpit.', 'see_inspection', NULL, '{\"room_id\":131,\"school_id\":13,\"status\":\"approved\"}', 1, '2026-03-16 18:10:48', '2026-03-18 02:05:20'),
(64, 'fire_safety', 'fire_safety', 3, 1, 'evacuation_plan', 'Evacuation Map Updated: Bangal Integrated School', 'Adan Kristopher B. Dumpit updated the evacuation map layout. Details: attached file here in bangal', NULL, NULL, '{\"plan_type\":\"map\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 03:38:58', '2026-03-18 02:05:20'),
(65, 'fire_safety', 'fire_safety', 3, 4, 'extinguisher_inspection', 'Extinguisher Inspected: FR-XT 01', 'Extinguisher FR-XT 01 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":66,\"school_id\":13}', 1, '2026-03-17 05:20:42', '2026-03-18 02:05:20'),
(66, 'fire_safety', 'fire_safety', 3, 4, 'room_approval', 'New Room Created (Pending Approval)', 'Contributor Zaldy Danaytan, Jr. created a new room and it requires administrator approval.', NULL, NULL, '{\"posted_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-17 05:22:24', '2026-03-18 02:05:20'),
(67, 'fire_safety', 'fire_safety', 3, 4, 'room_approval', 'Room Update Pending Approval: 01', 'Zaldy Danaytan, Jr. updated room 01 and it requires administrator approval. Changes: Secondary Exit: Yes', 'see_inspection', NULL, '{\"room_id\":132,\"school_id\":13,\"status\":\"pending\",\"updated_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-17 05:22:38', '2026-03-18 02:05:20'),
(68, 'fire_safety', 'fire_safety', 3, 4, 'building_update', 'Building Updated: 03', 'Zaldy Danaytan, Jr. updated building 03. Changes: Required Extinguishers: 1', NULL, NULL, '{\"building_id\":53,\"school_id\":13,\"updated_by\":\"Zaldy Danaytan, Jr.\"}', 1, '2026-03-17 05:47:32', '2026-03-18 02:05:20'),
(69, 'fire_safety', 'fire_safety', 3, 1, 'building_update', 'Building Updated: 03', 'Adan Kristopher B. Dumpit updated building 03. Changes: Description updated', NULL, NULL, '{\"building_id\":53,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 06:06:32', '2026-03-18 02:05:20'),
(70, 'fire_safety', 'fire_safety', 3, 1, 'building_update', 'Building Updated: 05', 'Adan Kristopher B. Dumpit updated building 05. Changes: Description updated', NULL, NULL, '{\"building_id\":61,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 06:08:04', '2026-03-18 02:05:20'),
(71, 'fire_safety', 'fire_safety', 3, 1, 'building_update', 'Building Updated: 04', 'Adan Kristopher B. Dumpit updated building 04. Changes: Building No: 04', NULL, NULL, '{\"building_id\":61,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 06:08:42', '2026-03-18 02:05:20'),
(72, 'fire_safety', 'fire_safety', 3, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 04', 'Extinguisher 04 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":67,\"school_id\":13}', 1, '2026-03-17 06:12:08', '2026-03-18 02:05:20'),
(73, 'fire_safety', 'fire_safety', 3, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 02', 'Extinguisher 02 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":66,\"school_id\":13}', 1, '2026-03-17 06:12:36', '2026-03-18 02:05:20'),
(74, 'fire_safety', 'fire_safety', 3, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 03', 'Extinguisher 03 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":65,\"school_id\":13}', 1, '2026-03-17 06:12:57', '2026-03-18 02:05:20'),
(75, 'fire_safety', 'fire_safety', 3, 1, 'building_update', 'Building Updated: 07', 'Adan Kristopher B. Dumpit updated building 07. Changes: Description updated', NULL, NULL, '{\"building_id\":64,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 06:23:59', '2026-03-18 02:05:20'),
(76, 'fire_safety', 'fire_safety', 3, 1, 'building_update', 'Building Updated: 06', 'Adan Kristopher B. Dumpit updated building 06. Changes: Description updated', NULL, NULL, '{\"building_id\":63,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 06:24:32', '2026-03-18 02:05:20'),
(77, 'fire_safety', 'fire_safety', 3, 1, 'room_update', 'Room Updated: 01', 'Adan Kristopher B. Dumpit updated room 01. Changes: Secondary Exit: No', 'see_inspection', NULL, '{\"room_id\":136,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 06:28:19', '2026-03-18 02:05:20'),
(78, 'fire_safety', 'fire_safety', 3, 1, 'extinguisher_inspection', 'Extinguisher Inspected: 06', 'Extinguisher 06 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":69,\"school_id\":13}', 1, '2026-03-17 06:41:29', '2026-03-18 02:05:20'),
(79, 'fire_safety', 'fire_safety', 3, 1, 'room_update', 'Room Updated: 02', 'Adan Kristopher B. Dumpit updated room 02. Changes: Code: 01', 'see_inspection', NULL, '{\"room_id\":144,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 06:47:55', '2026-03-18 02:05:20'),
(80, 'fire_safety', 'fire_safety', 3, 1, 'building_update', 'Building Updated: 09', 'Adan Kristopher B. Dumpit updated building Fil-Chi. Changes: Name: Fil-Chi', NULL, NULL, '{\"building_id\":66,\"school_id\":13,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 07:02:48', '2026-03-18 02:05:20'),
(81, 'fire_safety', 'fire_safety', 3, 1, 'evacuation_plan', 'Evacuation Map Updated: Bangal Integrated School', 'Adan Kristopher B. Dumpit updated the evacuation map layout. Details: Map updated', NULL, NULL, '{\"plan_type\":\"map\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-17 07:08:54', '2026-03-18 02:05:20'),
(82, 'fire_safety', 'fire_safety', 3, 1, 'inspection', 'Inspection Completed: Fire', 'Fire inspection at Bangal Integrated School on 2026-03-17. Monitored by: John Benedict G. Pecson', 'see_inspection', NULL, '{\"inspection_id\":5,\"school_id\":\"13\"}', 1, '2026-03-17 07:17:36', '2026-03-18 02:05:20'),
(83, 'fire_safety', 'fire_safety', 8, 1, 'alarm_update', 'Alarm Updated: 01', 'Adan Kristopher B. Dumpit updated alarm 01. Changes: Status: Active, Location: Between Room 8 and 9 and possibly 7, Notes updated', 'go_test', NULL, '{\"alarm_id\":25,\"school_id\":18,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-18 01:07:17', '2026-03-18 02:05:20'),
(84, 'fire_safety', 'fire_safety', 1, 1, 'alarm_update', 'Alarm Updated: ALARM-03', 'Adan Kristopher B. Dumpit updated alarm ALARM-03. Changes: Last Test: 2026-03-19', 'go_test', NULL, '{\"alarm_id\":37,\"school_id\":21,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-19 02:32:35', '2026-03-29 02:31:03'),
(85, 'fire_safety', 'fire_safety', 1, 1, 'extinguisher_inspection', 'Extinguisher Inspected: FRXT-08', 'Extinguisher FRXT-08 was inspected. Status: Maintenance, Pressure: 20%', 'update_now', NULL, '{\"extinguisher_id\":81,\"school_id\":21}', 1, '2026-03-19 02:58:31', '2026-03-29 02:31:03'),
(86, 'fire_safety', 'fire_safety', 1, 1, 'room_update', 'Room Updated: 02', 'Adan Kristopher B. Dumpit updated room 02. Changes: Remarks updated', 'see_inspection', NULL, '{\"room_id\":171,\"school_id\":21,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-19 03:08:40', '2026-03-29 02:31:03'),
(87, 'fire_safety', 'fire_safety', 1, 1, 'room_update', 'Room Updated: 01', 'Adan Kristopher B. Dumpit updated room 01. Changes: Smoke Detector Required: Yes', 'see_inspection', NULL, '{\"room_id\":176,\"school_id\":21,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-19 03:12:44', '2026-03-29 02:31:03'),
(88, 'fire_safety', 'fire_safety', 1, 1, 'extinguisher_inspection', 'Extinguisher Inspected: FRXT-19', 'Extinguisher FRXT-19 was inspected. Status: Purchase, Pressure: 0%', 'update_now', NULL, '{\"extinguisher_id\":77,\"school_id\":21}', 1, '2026-03-19 03:18:41', '2026-03-29 02:31:03'),
(89, 'fire_safety', 'fire_safety', 1, 1, 'building_update', 'Building Updated: 06', 'Adan Kristopher B. Dumpit updated building 06. Changes: Required Extinguishers: 6', NULL, NULL, '{\"building_id\":73,\"school_id\":21,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-19 03:25:57', '2026-03-29 02:31:03'),
(90, 'fire_safety', 'fire_safety', 1, 1, 'building_update', 'Building Updated: 01', 'Adan Kristopher B. Dumpit updated building 01. Changes: Safety Features updated', NULL, NULL, '{\"building_id\":68,\"school_id\":21,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-19 06:29:17', '2026-03-29 02:31:03'),
(91, 'fire_safety', 'fire_safety', 1, 1, 'alarm_update', 'Alarm Updated: ALARM-01', 'Adan Kristopher B. Dumpit updated alarm ALARM-01. Changes: Notes updated', 'go_test', NULL, '{\"alarm_id\":35,\"school_id\":21,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-19 06:33:39', '2026-03-29 02:31:03'),
(92, 'fire_safety', 'fire_safety', 1, 1, 'alarm_update', 'Alarm Updated: ALARM-02', 'Adan Kristopher B. Dumpit updated alarm ALARM-02. Changes: Notes updated', 'go_test', NULL, '{\"alarm_id\":36,\"school_id\":21,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-19 06:47:34', '2026-03-29 02:31:03'),
(93, 'fire_safety', 'fire_safety', 5, 1, 'extinguisher_inspection', 'Extinguisher Inspected: FRXT-01', 'Extinguisher FRXT-01 was inspected. Status: Maintenance, Pressure: 57%', 'update_now', NULL, '{\"extinguisher_id\":57,\"school_id\":15}', 1, '2026-03-19 08:00:17', '2026-03-29 02:31:03'),
(94, 'fire_safety', 'fire_safety', 4, NULL, 'alarm_due', 'Alarm Test Due Today: ALRM-002', 'Alarm ALRM-002 is scheduled for testing today.', 'go_test', NULL, '{\"alarm_id\":32,\"school_id\":14}', 1, '2026-03-22 23:53:55', '2026-03-29 02:31:03'),
(95, 'typhoon_flood', 'announcement', NULL, 1, 'announcement', 'Event Nomalization', 'To normalize the new gasoline savings', 'mark_read', NULL, '{\"urgency\":\"info\"}', 1, '2026-03-23 01:40:52', '2026-03-23 01:41:05'),
(96, 'fire_safety', 'fire_safety', 7, NULL, 'alarm_due', 'Alarm Test Due Today: 001', 'Alarm 001 is scheduled for testing today.', 'go_test', NULL, '{\"alarm_id\":20,\"school_id\":17}', 1, '2026-03-25 02:47:08', '2026-03-29 02:31:03'),
(97, 'fire_safety', 'fire_safety', 4, NULL, 'alarm_due', 'Alarm Test Due Today: AlRM-001', 'Alarm AlRM-001 is scheduled for testing today.', 'go_test', NULL, '{\"alarm_id\":31,\"school_id\":14}', 1, '2026-03-26 07:00:11', '2026-03-29 02:31:03'),
(98, 'fire_safety', 'fire_safety', 1, 1, 'building_update', 'Building Updated: 011', 'Adan Kristopher B. Dumpit updated building 011. Changes: Building No: 011, Name: 011, Year Constructed: 1995, Last Renovation: 1996, Safety Features updated, Required Extinguishers: 5', NULL, NULL, '{\"building_id\":17,\"unified_school_id\":1,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-29 01:12:37', '2026-03-29 02:31:03'),
(99, 'fire_safety', 'fire_safety', 1, 1, 'alarm_update', 'Alarm Updated: ALRM-003', 'Adan Kristopher B. Dumpit updated alarm ALRM-003. Changes: Status: Broken, Manufacturer: curuosh, Floor Level changed', 'go_test', NULL, '{\"alarm_id\":14,\"unified_school_id\":1,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-29 01:12:59', '2026-03-29 02:31:03'),
(100, 'fire_safety', 'fire_safety', 1, 1, 'building_update', 'Building Updated: 011', 'Adan Kristopher B. Dumpit updated building 011. Changes: Safety Features updated, Floor 2 and its associated safety systems were archived and removed.', NULL, NULL, '{\"building_id\":17,\"unified_school_id\":1,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-29 02:26:32', '2026-03-29 02:31:03'),
(101, 'fire_safety', 'fire_safety', 1, 1, 'building_update', 'Building Updated: 01', 'Adan Kristopher B. Dumpit updated building 011. Changes: Building No: 01, Emergency Exits: 0', NULL, NULL, '{\"building_id\":17,\"unified_school_id\":1,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-29 02:27:04', '2026-03-29 02:31:03'),
(102, 'fire_safety', 'fire_safety', 1, 1, 'extinguisher_inspection', 'Extinguisher Inspected: EXT-0012', 'Extinguisher EXT-0012 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":19,\"unified_school_id\":1}', 1, '2026-03-29 02:30:29', '2026-03-29 02:31:03'),
(103, 'fire_safety', 'fire_safety', 1, 1, 'extinguisher_inspection', 'Extinguisher Inspected: EXT-001', 'Extinguisher EXT-001 was inspected. Status: Active, Pressure: 100%', 'update_now', NULL, '{\"extinguisher_id\":19,\"unified_school_id\":1}', 1, '2026-03-29 02:30:42', '2026-03-29 02:31:03'),
(104, 'fire_safety', 'fire_safety', 1, 1, 'evacuation_plan', 'Evacuation Plan Updated: PLAN AB', 'Adan Kristopher B. Dumpit updated evacuation plan \"PLAN AB\" - School-Wide Plan', NULL, NULL, '{\"plan_id\":1,\"plan_type\":\"school\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-29 02:32:51', '2026-04-07 01:00:54'),
(105, 'fire_safety', 'fire_safety', 1, 1, 'evacuation_plan', 'Evacuation Plan Updated: PLAN A', 'Adan Kristopher B. Dumpit updated evacuation plan \"PLAN A\" - School-Wide Plan', NULL, NULL, '{\"plan_id\":1,\"plan_type\":\"school\",\"posted_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-29 02:32:59', '2026-04-07 01:00:54'),
(106, 'fire_safety', 'fire_safety', 1, 1, 'alarm_update', 'Alarm Updated: ALRM-001', 'Adan Kristopher B. Dumpit updated alarm ALRM-001. Changes: Floor Level changed', 'go_test', NULL, '{\"alarm_id\":12,\"unified_school_id\":1,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-03-30 01:10:44', '2026-04-07 01:00:54'),
(107, 'typhoon_flood', 'announcement', NULL, 1, 'announcement', 'Tropical Storms', 'B e ready', 'mark_read', NULL, '{\"urgency\":\"warning\"}', 1, '2026-04-07 00:20:54', '2026-04-07 00:21:05'),
(108, 'fire_safety', 'fire_safety', 9, 1, 'alarm_update', 'Alarm Updated: 01', 'Adan Kristopher B. Dumpit updated alarm 01. Changes: Notes updated', 'go_test', NULL, '{\"alarm_id\":43,\"unified_school_id\":9,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-04-07 00:29:52', '2026-04-07 01:00:54'),
(109, 'fire_safety', 'fire_safety', 9, 1, 'building_update', 'Building Updated: 01', 'Adan Kristopher B. Dumpit updated building 01. Changes: Required Extinguishers: 3', NULL, NULL, '{\"building_id\":75,\"unified_school_id\":9,\"updated_by\":\"Adan Kristopher B. Dumpit\"}', 1, '2026-04-07 00:45:51', '2026-04-07 01:00:54');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('adankristopher.dumpit@gmail.com', '$2y$12$8CH4LamfoUmrt7Trg13ub.CM358r8nmWiZlEWHapo6MM2WB4zMQRm', '2026-03-16 17:22:51'),
('example@gmail.com', '$2y$12$fjGRid7.gJ86HnhRJWmZf.xvVD8GrOofoFYf.q4En00zGnsxA38re', '2026-03-04 21:49:18'),
('kristopheradan59@gmail.com', '$2y$12$S8N/qa.Ost6/Ghgkb3MmqebsZgw27CYT1yyOAW5gK5qHhrZgtwvP.', '2026-02-17 22:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` varchar(255) DEFAULT NULL COMMENT 'DepEd school ID from fire safety module',
  `school_id_number` varchar(255) DEFAULT NULL COMMENT 'DepEd school ID from comprehensive module',
  `school_name` varchar(255) NOT NULL COMMENT 'Canonical school name',
  `address` text DEFAULT NULL,
  `school_head` varchar(255) DEFAULT NULL,
  `drrm_coordinator` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `contact_number_2` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `evacuation_map_layout` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`evacuation_map_layout`)),
  `attached_evacuation_map` varchar(255) DEFAULT NULL,
  `fire_safety_status` varchar(255) NOT NULL DEFAULT 'unconfigured',
  `alerts` longtext DEFAULT NULL,
  `events` longtext DEFAULT NULL,
  `replies` longtext DEFAULT NULL,
  `identification` varchar(255) DEFAULT NULL,
  `evacuation_identification` varchar(255) DEFAULT NULL COMMENT 'Evacuation center identification',
  `evacuation_location` varchar(255) DEFAULT NULL COMMENT 'Readable evacuation center location',
  `evacuation_capacity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `number_students` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `number_personnel` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `operational_status` varchar(255) NOT NULL DEFAULT 'operational' COMMENT 'operational|partial|closed',
  `evacuation_status` varchar(255) NOT NULL DEFAULT 'cleared' COMMENT 'occupied|full|cleared (was usage_status)',
  `occupancy_safety` varchar(255) NOT NULL DEFAULT 'safe' COMMENT 'safe|warning|critical',
  `emergency_resources` text DEFAULT NULL,
  `emergency_resources_status` varchar(255) DEFAULT NULL COMMENT 'Low/Moderate/High',
  `needs_summary` varchar(255) DEFAULT NULL COMMENT 'Quick badge summary on dashboard',
  `monitoring_status` varchar(255) DEFAULT NULL,
  `reports_status` varchar(255) DEFAULT NULL,
  `incident_count` int(11) NOT NULL DEFAULT 0,
  `last_incident_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_id`, `school_id_number`, `school_name`, `address`, `school_head`, `drrm_coordinator`, `contact_number`, `contact_number_2`, `district`, `division`, `region`, `evacuation_map_layout`, `attached_evacuation_map`, `fire_safety_status`, `alerts`, `events`, `replies`, `identification`, `evacuation_identification`, `evacuation_location`, `evacuation_capacity`, `number_students`, `number_personnel`, `operational_status`, `evacuation_status`, `occupancy_safety`, `emergency_resources`, `emergency_resources_status`, `needs_summary`, `monitoring_status`, `reports_status`, `incident_count`, `last_incident_date`, `created_at`, `updated_at`) VALUES
(1, '107121', '107121', 'Iram I Elementary School', 'Iram resettlement Area New Cabalan', 'Mr. Raymund F Camacho', 'Eleazar Arazadon', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_17\":{\"x\":59,\"y\":765,\"rotation\":270,\"width\":280.66317038371176,\"height\":140},\"building_19\":{\"x\":549,\"y\":160,\"rotation\":0,\"width\":300,\"height\":136.66666666666669},\"building_20\":{\"x\":1328,\"y\":387,\"rotation\":90,\"width\":300,\"height\":185},\"building_21\":{\"x\":1061,\"y\":15,\"rotation\":90,\"width\":300,\"height\":185},\"building_22\":{\"x\":590,\"y\":0,\"rotation\":0,\"width\":220,\"height\":151.82492710705836},\"building_23\":{\"x\":239,\"y\":499,\"rotation\":270,\"width\":220,\"height\":140},\"building_24\":{\"x\":1093,\"y\":428,\"rotation\":90,\"width\":220,\"height\":140},\"building_25\":{\"x\":1241,\"y\":0,\"rotation\":90,\"width\":300,\"height\":136.66666666666669},\"building_26\":{\"x\":206,\"y\":0,\"rotation\":0,\"width\":220,\"height\":140},\"building_27\":{\"x\":4,\"y\":411,\"rotation\":270,\"width\":300,\"height\":136.66666666666669},\"building_28\":{\"x\":918,\"y\":736,\"rotation\":0,\"width\":300,\"height\":185},\"facility_1772669252052\":{\"type\":\"facility\",\"name\":\"Covered Court\",\"description\":\"Basketball Court\",\"color\":\"#e83e8c\",\"x\":483,\"y\":421,\"width\":271.1495340327758,\"height\":486.052},\"facility_1772669381877\":{\"type\":\"facility\",\"name\":\"Exit Area\",\"description\":\"For students to take a path exit to school\",\"color\":\"#6c757d\",\"x\":300,\"y\":845,\"width\":200,\"height\":100},\"facility_1773793742149\":{\"type\":\"facility\",\"name\":\"STAGE\",\"description\":null,\"color\":\"#20c997\",\"x\":521,\"y\":322,\"width\":200,\"height\":100}}', 'evacuation_maps/R2VPdmt74QpLkxvohRp1GoKo8XUeqgmwXi6DsX3Q.png', 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 800, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, 'Active', NULL, 0, NULL, '2026-02-10 17:03:08', '2026-03-26 08:53:05'),
(2, '1', '1', 'Mabayuan Elementary School', 'Otero Avenue, Mabayuan, Olongapo City, Central Luzon, Philippines', 'Froilan N. Rivas', 'Jeffrey C. Mabini', NULL, NULL, 'Unknown', NULL, NULL, NULL, NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-02-12 19:21:22', '2026-03-26 08:53:05'),
(3, '502678', '502678', 'Bangal Integrated School', 'National Hi-Way, Purok 6, New Cabalan, Olongapo City, Zambales, Philippines, 2200', 'Nestor Sison', 'Nestor Sison', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_50\":{\"x\":574,\"y\":94,\"rotation\":0,\"width\":300,\"height\":330},\"building_51\":{\"x\":421,\"y\":475,\"rotation\":0,\"width\":307.198,\"height\":353.68138696814253},\"building_53\":{\"x\":1247,\"y\":55,\"rotation\":90,\"width\":300,\"height\":330},\"building_61\":{\"x\":103,\"y\":589,\"rotation\":270,\"width\":255.61356449469042,\"height\":140},\"building_62\":{\"x\":1008,\"y\":695,\"rotation\":90,\"width\":300,\"height\":136.66666666666669},\"building_63\":{\"x\":103,\"y\":954,\"rotation\":270,\"width\":341.98736660796465,\"height\":143.865},\"building_64\":{\"x\":720,\"y\":409,\"rotation\":0,\"width\":300,\"height\":185},\"building_65\":{\"x\":202,\"y\":80,\"rotation\":0,\"width\":300,\"height\":185},\"building_66\":{\"x\":1219,\"y\":487,\"rotation\":90,\"width\":300,\"height\":185}}', 'evacuation_maps/3mP2oJnOUg18vPj5jdnjkyxK7M1X7rRT448Wg32V.jpg', 'unconfigured', '[{\"id\":\"69a926ad26cad\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Zaldy Danaytan, Jr. created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-05 06:46:05\"},{\"id\":\"69a926f41d9ab\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Zaldy Danaytan, Jr. created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-05 06:47:16\"}]', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-02-16 00:29:57', '2026-03-26 08:53:05'),
(4, '107119', '107119', 'Boton ES', 'Pinagpala Street, Purok 4, New Cabalan, Olongapo City, 2200, Philippine', 'Dolores A. Umbina', 'Dolores A. Umbina', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_57\":{\"x\":160,\"y\":595,\"rotation\":270,\"width\":300,\"height\":136.66666666666669},\"building_58\":{\"x\":394,\"y\":87,\"rotation\":0,\"width\":300,\"height\":233.33333333333334},\"building_59\":{\"x\":968,\"y\":130,\"rotation\":90,\"width\":300,\"height\":112.5},\"facility_1773213058363\":{\"type\":\"facility\",\"name\":\"Assembly Area\",\"description\":\"1st and largest assembly area\",\"color\":\"#fd7e14\",\"x\":338,\"y\":355,\"width\":409.6270893320134,\"height\":236.86817974826755},\"facility_1773213084435\":{\"type\":\"facility\",\"name\":\"Secondary Assembly Area\",\"description\":null,\"color\":\"#20c997\",\"x\":803,\"y\":512,\"width\":251.854994578796,\"height\":172.59699241031444},\"facility_1773213098851\":{\"type\":\"facility\",\"name\":\"Garden\",\"description\":null,\"color\":\"#28a745\",\"x\":40,\"y\":102,\"width\":308.4240795738462,\"height\":151.85481374628765}}', NULL, 'unconfigured', '[{\"id\":\"69b0d6458b7d4\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:41:09\"},{\"id\":\"69b0d96d0b07d\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:54:37\"},{\"id\":\"69b0d98697427\",\"title\":\"Room Update Approved\",\"description\":\"Your update for room 01 has been approved by the administrator.\",\"type\":\"success\",\"created_at\":\"2026-03-11 02:55:02\"},{\"id\":\"69b0d986c0e1e\",\"title\":\"Room Update Approved\",\"description\":\"Your update for room 01 has been approved by the administrator.\",\"type\":\"success\",\"created_at\":\"2026-03-11 02:55:02\"},{\"id\":\"69b0d9af997f1\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 91 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:55:43\"},{\"id\":\"69b0d9afcf794\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 01 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:55:43\"},{\"id\":\"69b0d9b77690d\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 01 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:55:51\"},{\"id\":\"69b0d9d686b78\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:56:22\"},{\"id\":\"69b0da8ed5e05\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:59:26\"},{\"id\":\"69b0daa83d199\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room ADMN-01 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 02:59:52\"},{\"id\":\"69b0dadea8133\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:00:46\"},{\"id\":\"69b0db0304514\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room LB-01 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:01:23\"},{\"id\":\"69b0db35a952a\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:02:13\"},{\"id\":\"69b0e3209337f\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:36:00\"},{\"id\":\"69b0e348d75f5\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room te65 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:36:40\"},{\"id\":\"69b0e368149f6\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:37:12\"},{\"id\":\"69b0e3860b1b7\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:37:42\"},{\"id\":\"69b0e395a7b2e\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:37:57\"},{\"id\":\"69b0e41443c2a\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:40:04\"},{\"id\":\"69b0e42e21204\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 04 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:40:30\"},{\"id\":\"69b0e460b0ab5\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:41:20\"},{\"id\":\"69b0e4c318101\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:42:59\"},{\"id\":\"69b0e57374c10\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:45:55\"},{\"id\":\"69b0e5a3cfde6\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:46:43\"},{\"id\":\"69b0e5be6ba49\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:47:10\"},{\"id\":\"69b0e5dd9a344\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Dolores A. Umbina created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 03:47:41\"},{\"id\":\"69b100f6cfe0d\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 04 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 05:43:18\"},{\"id\":\"69b100f7461bb\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 04 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 05:43:19\"},{\"id\":\"69b10161206da\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 04 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-11 05:45:05\"},{\"id\":\"69b10180381bb\",\"title\":\"Room Update Approved\",\"description\":\"Your update for room 04 has been approved by the administrator.\",\"type\":\"success\",\"created_at\":\"2026-03-11 05:45:36\"},{\"id\":\"69b111ece3494\",\"title\":\"Room Update Approved\",\"description\":\"Your update for room 05 has been approved by the administrator.\",\"type\":\"success\",\"created_at\":\"2026-03-11 06:55:40\"},{\"id\":\"69b211f590fd8\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 02 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-12 01:08:05\"},{\"id\":\"69b211f5e9f51\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Dolores A. Umbina updated room 02 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-12 01:08:05\"}]', '[{\"id\":\"69b2282e51c41\",\"title\":\"The stray and stucky event\",\"description\":\"To provide stray animals more comfort adopt it for the students or pet them temporarily\",\"date\":\"2026-03-20\",\"time\":\"11:00\",\"posted_by\":\"Adan Kristopher B. Dumpit\",\"created_at\":\"2026-03-12 02:42:54\"}]', NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-02-16 00:30:55', '2026-03-26 08:53:05'),
(5, '162002', '162002', 'Amelia Heights ES', 'Barangay New Cabalan, Olongapo City, Philippines', 'Laura Managbanag', 'Laura Managbanag', NULL, NULL, 'Unknown', NULL, NULL, NULL, NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 1, '2026-03-19', '2026-02-16 00:35:07', '2026-03-26 08:53:05'),
(6, '305898', '305898', 'New Cabalan Senior High School', 'Lopez Jaena Street, Purok 2, in Barangay New Cabalan, Olongapo City, 2200 Zambales', 'Erwin A. Bucasas, EdD', 'Erwin A. Bucasas, EdD', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_48\":{\"x\":608,\"y\":259,\"rotation\":90}}', NULL, 'unconfigured', '[{\"id\":\"6995083f52f87\",\"title\":\"Fire Evacuation Plan to be action\",\"description\":\"To evacuate students safely\",\"type\":\"warning\",\"created_at\":\"2026-02-18 00:30:55\"},{\"id\":\"69a7dbfaad964\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Ragdoll@gmail.com created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-04 07:15:06\"},{\"id\":\"69a7dc31bc036\",\"title\":\"Room Update Approved\",\"description\":\"Your update for room 01 has been approved by the administrator.\",\"type\":\"success\",\"created_at\":\"2026-03-04 07:16:01\"},{\"id\":\"69a7e3983dcf2\",\"title\":\"Room Update Pending Approval\",\"description\":\"Contributor Ragdoll@gmail.com updated room 01 and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-04 07:47:36\"},{\"id\":\"69a7e3c98fb46\",\"title\":\"Room Update Approved\",\"description\":\"Your update for room 01 has been approved by the administrator.\",\"type\":\"success\",\"created_at\":\"2026-03-04 07:48:25\"},{\"id\":\"69a917724226f\",\"title\":\"New Room Created (Pending Approval)\",\"description\":\"Contributor Zaldy Danaytan, Jr. created a new room and it requires administrator approval.\",\"type\":\"warning\",\"created_at\":\"2026-03-05 05:41:06\"}]', NULL, '[{\"id\":\"69967d78a2c13\",\"item_id\":\"6995083f52f87\",\"user_name\":\"Adan Kristopher B. Dumpit\",\"user_role\":\"admin\",\"message\":\"Not so okay?\",\"created_at\":\"2026-02-19 03:03:20\"}]', NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-02-17 16:24:30', '2026-03-26 08:53:05'),
(7, '345224', '345224', 'Mabayuan Senior High School', 'Otero Avenue, Mabayuan, Olongapo City', 'Albert Llego', 'ON PROCESS', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_32\":{\"x\":50,\"y\":50},\"building_33\":{\"x\":370,\"y\":50},\"building_34\":{\"x\":674,\"y\":31},\"building_35\":{\"x\":1010,\"y\":50}}', NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-03-01 18:16:24', '2026-03-26 08:53:05'),
(8, '107136', '107136', 'Nellie E. Brown Elementary School', '#17 Davidson St. West Bajac Bajac', 'Letecia F. Farne', 'Kriz Anne A Hemenez', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_36\":{\"x\":1216,\"y\":513,\"rotation\":0,\"width\":538.876,\"height\":195.62027851791396},\"building_37\":{\"x\":435,\"y\":149,\"rotation\":0,\"width\":300,\"height\":136.66666666666669},\"building_38\":{\"x\":882,\"y\":1,\"rotation\":90,\"width\":300,\"height\":136.66666666666669},\"building_39\":{\"x\":339,\"y\":0,\"rotation\":0,\"width\":300,\"height\":136.66666666666669},\"building_40\":{\"x\":0,\"y\":369,\"rotation\":270,\"width\":391.42478314455144,\"height\":330},\"building_41\":{\"x\":417,\"y\":369,\"rotation\":0,\"width\":220,\"height\":184.6834374989679},\"building_42\":{\"x\":1118,\"y\":0,\"rotation\":90,\"width\":300,\"height\":136.66666666666669},\"building_43\":{\"x\":815,\"y\":615,\"rotation\":0,\"width\":300,\"height\":330},\"building_44\":{\"x\":1599,\"y\":485,\"rotation\":180,\"width\":404.03390958660714,\"height\":389.447},\"building_45\":{\"x\":193,\"y\":871,\"rotation\":0,\"width\":300,\"height\":330},\"building_46\":{\"x\":743,\"y\":370,\"rotation\":0,\"width\":220,\"height\":140},\"building_47\":{\"x\":589,\"y\":614,\"rotation\":0,\"width\":220,\"height\":276.3455611440555},\"facility_1773194696679\":{\"type\":\"facility\",\"name\":\"Evacuation Area\",\"description\":null,\"color\":\"#6f42c1\",\"x\":1448,\"y\":835,\"width\":326.9176772893508,\"height\":256.2063720484318}}', NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-03-02 22:54:00', '2026-03-26 08:53:05'),
(9, '107122', '107122', 'New Cabalan Elementary School', 'Barangay New Cabalan, Olongapo City, Zambales, Philippines', 'Marites A. Calara', 'Denver Faenticilia', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_75\":{\"x\":100,\"y\":100,\"rotation\":0,\"width\":300,\"height\":707},\"building_76\":{\"x\":420,\"y\":100,\"rotation\":0,\"width\":300,\"height\":391},\"specific_1775524130884\":{\"type\":\"specific\",\"subType\":\"stairs\",\"x\":284,\"y\":148,\"rotation\":0,\"width\":60.736,\"height\":102.20809100155027}}', NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-03-04 18:32:48', '2026-04-07 01:09:01'),
(10, '500135', '500135', 'Sergia Soriano Esteban Integrated School - Coral', 'Coral St. Kalaklan Olongapo City', 'Judith Jao', 'Judith Jao', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_49\":{\"x\":58,\"y\":118,\"rotation\":0,\"width\":300,\"height\":185},\"building_52\":{\"x\":469,\"y\":158,\"rotation\":0,\"width\":274.2390041718834,\"height\":219.0985477506633},\"building_54\":{\"x\":836,\"y\":104,\"rotation\":0,\"width\":300,\"height\":185},\"building_55\":{\"x\":591,\"y\":562,\"rotation\":0,\"width\":300,\"height\":112.5},\"building_56\":{\"x\":177,\"y\":558,\"rotation\":0,\"width\":300,\"height\":330}}', NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-03-04 22:13:06', '2026-03-26 08:53:05'),
(11, '107141', '107141', 'Tapinac Elementary School', '13th Street, East Tapinac', 'Joseph Gregorio', 'Joseph Gregorio', NULL, NULL, 'Unknown', NULL, NULL, '{\"building_68\":{\"x\":667,\"y\":70,\"rotation\":0,\"width\":220,\"height\":437.4289282601542},\"building_69\":{\"x\":1097,\"y\":29,\"rotation\":0,\"width\":220,\"height\":205.51749618564207},\"building_70\":{\"x\":1479,\"y\":354,\"rotation\":90,\"width\":220,\"height\":292.2754415181643},\"building_71\":{\"x\":1179,\"y\":275,\"rotation\":90,\"width\":294.0166934253433,\"height\":207.51193171052765},\"building_72\":{\"x\":568,\"y\":564,\"rotation\":0,\"width\":460,\"height\":275},\"building_73\":{\"x\":25,\"y\":518,\"rotation\":0,\"width\":540.7042869889913,\"height\":353.4307631499679},\"facility_1773891450543\":{\"type\":\"facility\",\"name\":\"STAGE\",\"description\":null,\"color\":\"#28a745\",\"x\":300,\"y\":253,\"width\":200,\"height\":100},\"facility_1773891474740\":{\"type\":\"facility\",\"name\":\"Covered Court\",\"description\":null,\"color\":\"#007bff\",\"x\":1,\"y\":114,\"width\":650.3576973248234,\"height\":348.11307812970654}}', NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-03-19 02:16:39', '2026-03-26 08:53:05'),
(18, NULL, NULL, 'Integrated School at the Mabini High School', NULL, NULL, NULL, NULL, NULL, 'Unknown', NULL, NULL, NULL, NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 4, '2026-03-23', '2026-02-09 00:04:51', '2026-03-23 15:43:12'),
(27, NULL, NULL, 'Deped SDO department', NULL, NULL, NULL, NULL, NULL, 'Unknown', NULL, NULL, NULL, NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 1, '2026-03-12', '2026-03-12 00:05:29', '2026-03-12 00:05:29'),
(30, NULL, NULL, 'OLONGAPO CITY NATIONAL HIGH SCHOOL', NULL, NULL, NULL, NULL, NULL, 'Unknown', NULL, NULL, NULL, NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 1, '2026-03-19', '2026-03-19 00:33:45', '2026-03-19 00:33:45'),
(31, NULL, NULL, 'Gordon Heights National High School', NULL, NULL, NULL, NULL, NULL, 'Unknown', NULL, NULL, NULL, NULL, 'unconfigured', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 'operational', 'cleared', 'safe', NULL, NULL, NULL, NULL, NULL, 1, '2026-03-19', '2026-03-19 00:41:18', '2026-03-19 00:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `school_specifics_information`
--

CREATE TABLE `school_specifics_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(255) NOT NULL COMMENT 'fire_safety|incident|typhoon_flood|comprehensive',
  `key` varchar(255) NOT NULL COMMENT 'The metadata key (e.g., original_id, needs_summary)',
  `value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_specifics_information`
--

INSERT INTO `school_specifics_information` (`id`, `school_id`, `module`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'fire_safety', 'original_fire_safety_id', '11', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(2, 2, 'fire_safety', 'original_fire_safety_id', '12', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(3, 3, 'fire_safety', 'original_fire_safety_id', '13', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(4, 4, 'fire_safety', 'original_fire_safety_id', '14', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(5, 5, 'fire_safety', 'original_fire_safety_id', '15', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(6, 6, 'fire_safety', 'original_fire_safety_id', '16', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(7, 7, 'fire_safety', 'original_fire_safety_id', '17', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(8, 8, 'fire_safety', 'original_fire_safety_id', '18', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(9, 9, 'fire_safety', 'original_fire_safety_id', '19', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(10, 10, 'fire_safety', 'original_fire_safety_id', '20', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(11, 11, 'fire_safety', 'original_fire_safety_id', '21', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(13, 1, 'comprehensive', 'original_cmpr_school_id', '1', '2026-03-26 08:53:04', '2026-03-26 08:53:04'),
(24, 18, 'incident', 'marked_for_deletion', 'true', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(25, 18, 'incident', 'original_incident_school_id', '6', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(43, 27, 'incident', 'marked_for_deletion', 'true', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(44, 27, 'incident', 'original_incident_school_id', '16', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(49, 5, 'incident', 'original_incident_school_id', '19', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(50, 30, 'incident', 'original_incident_school_id', '20', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(51, 31, 'incident', 'original_incident_school_id', '21', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(52, 1, 'incident', 'original_incident_school_id', '22', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(53, 2, 'incident', 'original_incident_school_id', '23', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(54, 3, 'incident', 'original_incident_school_id', '24', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(55, 4, 'incident', 'original_incident_school_id', '25', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(56, 6, 'incident', 'original_incident_school_id', '26', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(57, 7, 'incident', 'original_incident_school_id', '27', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(58, 8, 'incident', 'original_incident_school_id', '28', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(59, 9, 'incident', 'original_incident_school_id', '29', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(60, 10, 'incident', 'original_incident_school_id', '30', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(61, 11, 'incident', 'original_incident_school_id', '31', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(62, 1, 'typhoon_flood', 'original_evacuation_center_id', '25', '2026-03-26 08:53:05', '2026-03-26 08:53:05'),
(64, 30, 'comprehensive', 'original_cmpr_school_id', '30', '2026-04-06 08:27:27', '2026-04-06 08:27:27');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2P4Gp4YxXLj0dtFb37OGTfATcVtixUZpJRzOgt1W', 3, '10.100.30.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoid2NHWjFycTE1WjFidWFzWUtPVDV4OXhyZkNLdW41TFlZZGJEVnJBYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMC4xMDAuMzAuNDA6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NzI3NjYwNjk7fX0=', 1772766069),
('AGjpXFIZwlmCOHredRU3DqUG1B4R5Ra3mXu7Mk4y', 4, '10.100.30.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVU1zUm10N29vRGtHdkpkUWxOb1lwYWlkdDBxd21ZODN5S3Nyd05HMiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTAuMTAwLjMwLjQwOjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzcyNzY0OTYzO319', 1772764968),
('IEoqZltoOvTOj27vvBcJi3cC55eibK6yi3BdB4aV', 5, '10.100.30.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoib0x2RWU0dk5XaXpoN0d1THI0ZTJLeGhwYlRIa2JwVFhhSnVMOEhjaSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjUwOiJodHRwOi8vMTAuMTAwLjMwLjQwOjgwMDAvZmlyZS1zYWZldHkvbm90aWZpY2F0aW9ucyI7czo1OiJyb3V0ZSI7czoyNToiZmlyZS1zYWZldHkubm90aWZpY2F0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzcyNzU4MjUyO319', 1772761124),
('U1RFQUTQvX2qmrnIKQOihzwU3fe8oMbwSKqa3Oee', NULL, '10.100.30.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS243TmV4bHpsZ2o2VUs5RXJVbnZQRko3UXVTNDB3M2Z3RjlUMUk5dSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzEwLjEwMC4zMC40MDo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vMTAuMTAwLjMwLjQwOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773133393),
('XodXGmvhnYdoIQwAe75LL8GeYKGU06brmd7cGw3B', 1, '10.100.30.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoib1NucnBydFVQNXMxUHZvYUlmM0xybms5TjRwemxJU0pKcFBxQ0ZqSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMC4xMDAuMzAuNDA6ODAwMC91c2VycyI7czo1OiJyb3V0ZSI7czoxMToidXNlcnMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3Mjc2NDk1MDt9fQ==', 1772766037);

-- --------------------------------------------------------

--
-- Table structure for table `system_configurations`
--

CREATE TABLE `system_configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `config_type` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `min_floors` int(10) UNSIGNED DEFAULT NULL,
  `total_rooms` int(10) UNSIGNED DEFAULT NULL,
  `pressure_min` decimal(8,2) DEFAULT NULL,
  `pressure_max` decimal(8,2) DEFAULT NULL,
  `max_rooms_covered` tinyint(3) UNSIGNED DEFAULT NULL,
  `required_extinguishers` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `code` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `color_class` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `system_configurations`
--

INSERT INTO `system_configurations` (`id`, `config_type`, `parent_id`, `name`, `description`, `min_floors`, `total_rooms`, `pressure_min`, `pressure_max`, `max_rooms_covered`, `required_extinguishers`, `code`, `category`, `color_class`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'building_type', NULL, 'School Building', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-01-29 23:40:22', '2026-02-18 16:42:51'),
(2, 'building_type', NULL, 'Laboratory', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, 1, '2026-01-29 23:40:22', '2026-02-18 16:42:51'),
(3, 'building_type', NULL, 'Administration', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 4, 1, '2026-01-29 23:40:22', '2026-02-18 16:42:51'),
(4, 'building_type', NULL, 'Gymnasium', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 6, 1, '2026-01-29 23:40:22', '2026-02-18 16:42:51'),
(5, 'building_type', NULL, 'Canteen', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 8, 1, '2026-01-29 23:40:22', '2026-02-18 16:42:51'),
(14, 'extinguisher_type', NULL, 'Dry Chemical (ABC)', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-01-29 23:40:22', '2026-01-29 23:40:22'),
(15, 'extinguisher_type', NULL, 'CO2', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-01-29 23:40:22', '2026-01-29 23:40:22'),
(16, 'extinguisher_type', NULL, 'Water', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-01-29 23:40:22', '2026-01-29 23:40:22'),
(17, 'extinguisher_type', NULL, 'Foam', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-01-29 23:40:22', '2026-01-29 23:40:22'),
(18, 'extinguisher_type', NULL, 'Clean Agent', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-01-29 23:40:22', '2026-01-29 23:40:22'),
(19, 'extinguisher_status', NULL, 'Active', NULL, NULL, NULL, 70.00, 100.00, NULL, 1, NULL, NULL, 'success', 0, 1, '2026-01-29 23:40:22', '2026-02-10 00:20:01'),
(20, 'extinguisher_status', NULL, 'Expired', NULL, NULL, NULL, 0.00, 100.00, NULL, 1, NULL, NULL, 'danger', 0, 1, '2026-01-29 23:40:22', '2026-02-10 00:22:19'),
(21, 'extinguisher_status', NULL, 'For Refill', NULL, NULL, NULL, 20.00, 69.00, NULL, 1, NULL, NULL, 'warning', 0, 1, '2026-01-29 23:40:22', '2026-02-10 00:22:48'),
(22, 'extinguisher_status', NULL, 'Damaged', NULL, NULL, NULL, 0.00, 100.00, NULL, 1, NULL, NULL, 'secondary', 0, 1, '2026-01-29 23:40:22', '2026-02-10 00:23:09'),
(23, 'safety_feature', NULL, 'Emergency Lights', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-01-29 23:40:22', '2026-02-09 18:07:57'),
(24, 'safety_feature', NULL, 'Fire Exit Signs', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 3, 1, '2026-01-29 23:40:22', '2026-02-09 18:07:57'),
(25, 'safety_feature', NULL, 'First Aid Kits', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2026-01-29 23:40:22', '2026-02-09 18:07:57'),
(26, 'safety_feature', NULL, 'Sprinkler System', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, 1, '2026-01-29 23:40:22', '2026-02-09 18:07:57'),
(27, 'building_type', NULL, 'Office of canteen', 'Okay na building palaban', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 10, 0, '2026-02-09 17:39:40', '2026-02-18 16:42:51'),
(44, 'calculated_priority', NULL, 'Shared Coverage (Up to 3 Classrooms)', 'Shared coverage priority for classroom-like rooms', NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, 0, 1, '2026-02-09 20:03:31', '2026-03-13 00:08:28'),
(45, 'calculated_priority', NULL, 'Dedicated / Limited Shared', 'Dedicated / limited share priority for specialized rooms', NULL, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 1, 1, '2026-02-09 20:03:31', '2026-03-13 00:08:28'),
(46, 'room_type', 44, 'Classroom', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-09 20:03:31', '2026-02-09 20:03:31'),
(47, 'room_type', 44, 'Administration', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2026-02-09 20:03:31', '2026-02-18 16:41:31'),
(48, 'room_type', 44, 'Library', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, 1, '2026-02-09 20:03:31', '2026-02-09 20:03:31'),
(49, 'room_type', 45, 'Laboratory', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 3, 1, '2026-02-09 20:03:31', '2026-02-09 20:03:31'),
(50, 'room_type', 45, 'Clinic', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 4, 1, '2026-02-09 20:03:31', '2026-02-09 20:03:31'),
(51, 'room_type', 45, 'Storage', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 5, 1, '2026-02-09 20:03:31', '2026-02-09 20:03:31'),
(80, 'building_type', NULL, 'School Building', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2026-02-10 18:09:31', '2026-02-18 16:42:51'),
(81, 'building_type', NULL, 'Laboratory', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 3, 1, '2026-02-10 18:09:31', '2026-02-18 16:42:51'),
(82, 'building_type', NULL, 'Administration', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 5, 1, '2026-02-10 18:09:31', '2026-02-18 16:42:51'),
(83, 'building_type', NULL, 'Gymnasium', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 7, 1, '2026-02-10 18:09:31', '2026-02-18 16:42:51'),
(84, 'building_type', NULL, 'Canteen', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 9, 1, '2026-02-10 18:09:31', '2026-02-18 16:42:51'),
(92, 'extinguisher_type', NULL, 'Dry Chemical (ABC)', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(93, 'extinguisher_type', NULL, 'CO2', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(94, 'extinguisher_type', NULL, 'Water', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(95, 'extinguisher_type', NULL, 'Foam', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(96, 'extinguisher_type', NULL, 'Clean Agent', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(97, 'extinguisher_status', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'success', 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(98, 'extinguisher_status', NULL, 'Expired', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'danger', 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(99, 'extinguisher_status', NULL, 'For Refill', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'warning', 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(100, 'extinguisher_status', NULL, 'Damaged', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'secondary', 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(101, 'safety_feature', NULL, 'Emergency Lights', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(102, 'safety_feature', NULL, 'Fire Exit Signs', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(103, 'safety_feature', NULL, 'First Aid Kits', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(104, 'safety_feature', NULL, 'Sprinkler System', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 3, 1, '2026-02-10 18:09:31', '2026-02-10 18:09:31'),
(105, 'alarm_type', NULL, 'Bell', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-10 18:29:58', '2026-02-10 18:29:58'),
(110, 'alarm_type', NULL, 'Mechanical', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2026-02-10 18:30:37', '2026-02-10 18:30:37'),
(115, 'alarm_type', NULL, 'Digital', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, 1, '2026-02-10 18:31:07', '2026-02-10 18:31:07'),
(121, 'inspection_checklist', NULL, 'Alarm', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-12 22:25:20', '2026-02-12 22:25:20'),
(122, 'inspection_checklist', NULL, 'Evacuation Plan (Updated)', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2026-02-12 22:25:37', '2026-02-12 22:25:49'),
(123, 'inspection_checklist', NULL, 'DRRM Team', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, 1, '2026-02-12 22:28:24', '2026-02-12 22:28:24'),
(124, 'inspection_checklist', NULL, 'First Aid Kit', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 3, 1, '2026-02-12 22:28:43', '2026-02-12 22:28:43'),
(125, 'inspection_checklist', NULL, 'Actual Head Count', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 4, 1, '2026-02-12 22:29:01', '2026-02-12 22:29:01'),
(126, 'inspection_observer', NULL, 'Local Barangay', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, 1, '2026-02-12 22:29:22', '2026-02-12 22:29:22'),
(127, 'inspection_observer', NULL, 'City DRRM', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2026-02-12 22:29:34', '2026-02-12 22:29:34'),
(128, 'inspection_observer', NULL, 'BFP', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, 1, '2026-02-12 22:29:45', '2026-02-12 22:29:45'),
(129, 'inspection_observer', NULL, 'PTA', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 3, 1, '2026-02-12 22:29:53', '2026-02-12 22:29:53'),
(130, 'inspection_observer', NULL, 'OTMPS', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 4, 1, '2026-02-12 22:30:12', '2026-02-12 22:30:57'),
(131, 'inspection_observer', NULL, 'PNP', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 5, 1, '2026-02-12 22:30:34', '2026-02-12 22:31:09'),
(132, 'inspection_checklist', NULL, 'Directional Arrows', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 5, 1, '2026-02-12 22:31:33', '2026-02-12 22:31:33'),
(133, 'inspection_checklist', NULL, 'Hotline Numbers', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 6, 1, '2026-02-12 22:33:35', '2026-02-12 22:33:35'),
(134, 'inspection_checklist', NULL, 'Command Center', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 7, 1, '2026-02-12 22:33:52', '2026-02-12 22:33:52'),
(135, 'inspection_checklist', NULL, 'Student Release Form', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 8, 1, '2026-02-12 22:34:06', '2026-02-12 22:34:06'),
(136, 'inspection_checklist', NULL, 'SF 2 / Attendance Sheet', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 9, 1, '2026-02-12 22:34:20', '2026-02-12 22:34:20'),
(137, 'inspection_checklist', NULL, 'Megaphone', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 10, 1, '2026-02-12 22:34:40', '2026-02-12 22:34:40'),
(138, 'inspection_checklist', NULL, 'Group Signage', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 11, 1, '2026-02-12 22:34:51', '2026-02-12 22:34:51'),
(147, 'inspection_checklist', NULL, 'Exit Signage', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 12, 1, '2026-03-02 00:40:48', '2026-03-02 00:40:48'),
(148, 'inspection_checklist', NULL, 'Bert/Sert', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 13, 1, '2026-03-02 00:41:03', '2026-03-02 00:41:03'),
(149, 'inspection_checklist', NULL, 'Walked Casually', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 14, 1, '2026-03-02 00:41:17', '2026-03-02 00:41:17'),
(150, 'inspection_checklist', NULL, 'Guard On Duty', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 15, 1, '2026-03-02 00:41:35', '2026-03-02 00:41:35'),
(151, 'inspection_checklist', NULL, 'School ID (Personnel)', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 16, 1, '2026-03-02 00:41:56', '2026-03-02 00:41:56'),
(152, 'inspection_checklist', NULL, 'School ID (Students)', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 17, 1, '2026-03-02 00:42:14', '2026-03-02 00:42:14'),
(154, 'inspection_checklist', NULL, 'Closed Doors (Fire)', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 19, 1, '2026-03-02 00:43:00', '2026-03-02 00:43:00'),
(155, 'room_type', 44, 'Canteen', 'Canteen Eatery for Children', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 6, 1, '2026-03-03 00:17:00', '2026-03-03 00:17:00'),
(156, 'room_type', 157, 'Classroom and Administration', 'Room divided by a wall to serve as two functions', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 7, 1, '2026-03-04 22:24:18', '2026-03-12 23:50:55'),
(157, 'calculated_priority', NULL, 'Shared Space', 'Rooms under this priority can host up to 2 extinguishers.', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, 2, 1, '2026-03-12 23:50:55', '2026-03-13 00:08:28'),
(158, 'safety_feature', NULL, 'Dry Stand Pipe', 'With Fire Hose Cabinet', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 8, 1, '2026-03-19 02:18:25', '2026-03-19 02:18:25'),
(159, 'alarm_status', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'bg-success', 0, 1, '2026-03-19 06:21:37', '2026-03-19 06:21:37'),
(160, 'alarm_status', NULL, 'Broken', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'bg-danger', 0, 1, '2026-03-19 06:21:37', '2026-03-19 06:21:37'),
(161, 'alarm_status', NULL, 'Missing', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'bg-warning text-dark', 0, 1, '2026-03-19 06:21:37', '2026-03-19 06:21:37'),
(162, 'alarm_status', NULL, 'Not Installed', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'bg-secondary', 0, 1, '2026-03-19 06:21:37', '2026-03-19 06:21:37'),
(163, 'alarm_status', NULL, 'Decommissioned', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'bg-dark', 0, 1, '2026-03-19 06:21:37', '2026-03-19 06:21:37'),
(164, 'alarm_status', 115, 'Offline', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'bg-info', 0, 1, '2026-03-19 06:21:37', '2026-03-19 06:21:37'),
(165, 'safety_feature', NULL, 'Axe', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 9, 1, '2026-03-19 06:32:17', '2026-03-19 06:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `typ_fld_families`
--

CREATE TABLE `typ_fld_families` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `head_family_name` varchar(255) NOT NULL,
  `collective_needs` text DEFAULT NULL,
  `has_pregnant` tinyint(1) NOT NULL DEFAULT 0,
  `has_pwd` tinyint(1) NOT NULL DEFAULT 0,
  `has_senior` tinyint(1) NOT NULL DEFAULT 0,
  `has_lactating` tinyint(1) NOT NULL DEFAULT 0,
  `has_child_under5` tinyint(1) NOT NULL DEFAULT 0,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_out_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `typ_fld_family_members`
--

CREATE TABLE `typ_fld_family_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` smallint(5) UNSIGNED NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `needs` varchar(255) DEFAULT NULL,
  `is_head` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('normal','missing','injured','deceased') NOT NULL DEFAULT 'normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `typ_fld_monitoring_snapshots`
--

CREATE TABLE `typ_fld_monitoring_snapshots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `recorded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','contributor') NOT NULL DEFAULT 'contributor',
  `position` varchar(255) DEFAULT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `typhoon_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `incident_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `needs_fs_registration` tinyint(1) NOT NULL DEFAULT 0,
  `needs_tf_registration` tinyint(1) NOT NULL DEFAULT 0,
  `module_access` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`module_access`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`, `role`, `position`, `school_id`, `typhoon_school_id`, `incident_school_id`, `needs_fs_registration`, `needs_tf_registration`, `module_access`) VALUES
(1, 'Adan Kristopher B. Dumpit', 'adankristopher.dumpit@gmail.com', NULL, '$2y$12$f/IBhh/RpO6ADlpoIqSna.FDNT9DBaQxckg/zZV5xdqRHDLtqgUhK', 1, NULL, '2026-01-29 17:42:14', '2026-02-15 23:58:44', 'admin', NULL, NULL, NULL, NULL, 0, 0, '[\"fire_safety\",\"typhoon_flood\",\"incident_checklist\",\"comprehensive_school_safety\",\"hazard_mapping\"]'),
(3, 'Dolores A. Umbina', 'doloresU@yahoo.com', NULL, '$2y$12$scwhOjfpK5qqr91XJEP1ke5e6mTQnwCLfE4JNQFdXV3RNLHhptLgu', 1, NULL, '2026-02-02 23:02:12', '2026-04-06 03:37:41', 'contributor', 'School Head', 4, NULL, NULL, 0, 0, '[\"fire_safety\"]'),
(4, 'Zaldy Danaytan, Jr.', 'zaldydanaytan@gmail.com', NULL, '$2y$12$8q/N0j0E/yciGkEQL41CHucDsK85ip5Z0zF/Ec567K477K84K.JhK', 1, NULL, '2026-02-15 23:37:41', '2026-04-06 05:58:51', 'contributor', 'School Head', 1, NULL, NULL, 0, 0, '[\"fire_safety\",\"comprehensive_school_safety\"]'),
(5, 'Erwin A. Castillejo', 'erwin.castillejo@deped.gov.ph', NULL, '$2y$12$0oYX2QkQ/5gVDmUISOaCie.kLG2VzbpjCu/ye4qhyz.a/F7R76yIW', 1, NULL, '2026-02-17 22:02:34', '2026-03-12 00:14:02', 'admin', NULL, NULL, NULL, NULL, 0, 0, '[]'),
(6, 'Test', 'test@example.com', NULL, '$2y$12$8K1mUZDkVQLWM71Nomvb/ebTbN2mWD7Bn/fHa73acRWKx.xCMlyUK', 1, NULL, '2026-03-04 01:02:07', '2026-04-06 05:58:41', 'contributor', 'School Head', NULL, NULL, NULL, 0, 0, '[\"typhoon_flood\"]'),
(7, 'Denver Faenticilia', 'dennieverrycilia@gmail.com', NULL, '$2y$12$vdbXKy.XedE/IG8fcy5K2ufF7PtmsE6fefrjnpjpU15nxuVfUnNay', 1, NULL, '2026-03-04 18:19:34', '2026-04-06 03:37:55', 'contributor', 'School Head', 9, NULL, 9, 0, 0, '[\"fire_safety\",\"incident_checklist\"]'),
(8, 'example', 'example@gmail.com', NULL, '$2y$12$yi9.rTefhqJGOWOAWnpPL.W5EDUkaCZWqOa4AkCqM44A3RNO4hx22', 1, NULL, '2026-03-04 21:11:53', '2026-04-06 05:58:24', 'contributor', 'School Head', 1, NULL, NULL, 0, 0, '[\"fire_safety\",\"typhoon_flood\",\"incident_checklist\",\"comprehensive_school_safety\",\"hazard_mapping\"]'),
(9, 'Subagent', 'subagent@example.com', NULL, '$2y$12$LvfQTg7xEGgPtKFJstHClu1Fvbnifl0wAw9iH3GTQc310H.cwH86q', 0, NULL, '2026-03-18 05:50:27', '2026-04-07 02:35:49', 'contributor', 'School Head', 5, NULL, 4, 0, 0, '[\"fire_safety\",\"typhoon_flood\",\"incident_checklist\",\"comprehensive_school_safety\",\"hazard_mapping\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `activity_logs_module_created_at_index` (`module`,`created_at`) USING BTREE,
  ADD KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`) USING BTREE,
  ADD KEY `activity_logs_school_id_module_index` (`school_id`,`module`) USING BTREE;

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `cmpr_schl_sfty_assessments`
--
ALTER TABLE `cmpr_schl_sfty_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cmpr_schl_sfty_assessments_school_id_foreign` (`school_id`);

--
-- Indexes for table `cmpr_schl_sfty_assessment_items`
--
ALTER TABLE `cmpr_schl_sfty_assessment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cmpr_schl_sfty_assessment_items_assessment_id_foreign` (`assessment_id`);

--
-- Indexes for table `cmpr_schl_sfty_facilities`
--
ALTER TABLE `cmpr_schl_sfty_facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cmpr_schl_sfty_facilities_school_id_foreign` (`school_id`);

--
-- Indexes for table `cmpr_schl_sfty_students`
--
ALTER TABLE `cmpr_schl_sfty_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cmpr_schl_sfty_students_student_lrn_unique` (`student_lrn`),
  ADD KEY `cmpr_schl_sfty_students_school_id_foreign` (`school_id`);

--
-- Indexes for table `cmpr_schl_sfty_student_pathways`
--
ALTER TABLE `cmpr_schl_sfty_student_pathways`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cmpr_schl_sfty_student_pathways_student_id_foreign` (`student_id`);

--
-- Indexes for table `firesafety_alarm_systems`
--
ALTER TABLE `firesafety_alarm_systems`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `firesafety_alarm_systems_building_id_foreign` (`building_id`) USING BTREE,
  ADD KEY `firesafety_alarm_systems_unified_school_id_foreign` (`unified_school_id`),
  ADD KEY `firesafety_alarm_systems_anchor_building_id_foreign` (`anchor_building_id`);

--
-- Indexes for table `firesafety_buildings`
--
ALTER TABLE `firesafety_buildings`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `firesafety_buildings_unified_school_id_foreign` (`unified_school_id`);

--
-- Indexes for table `firesafety_evacuationplans`
--
ALTER TABLE `firesafety_evacuationplans`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `evacuationplans_school_planno_unique` (`school_id`,`plan_no`) USING BTREE,
  ADD UNIQUE KEY `unique_plan_per_building` (`school_id`,`building_id`) USING BTREE,
  ADD UNIQUE KEY `evacuationplans_school_building_unique` (`school_id`,`building_id`) USING BTREE,
  ADD KEY `firesafety_evacuationplans_building_id_foreign` (`building_id`) USING BTREE,
  ADD KEY `firesafety_evacuationplans_unified_school_id_foreign` (`unified_school_id`);

--
-- Indexes for table `firesafety_fire_extinguishers`
--
ALTER TABLE `firesafety_fire_extinguishers`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `firesafety_fire_extinguishers_room_id_foreign` (`room_id`) USING BTREE,
  ADD KEY `firesafety_fire_extinguishers_building_id_foreign` (`building_id`) USING BTREE,
  ADD KEY `firesafety_fire_extinguishers_unified_school_id_foreign` (`unified_school_id`);

--
-- Indexes for table `firesafety_school_snapshots`
--
ALTER TABLE `firesafety_school_snapshots`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `firesafety_school_snapshots_school_id_code_index` (`school_id_code`) USING BTREE;

--
-- Indexes for table `fire_safety_alarm_building`
--
ALTER TABLE `fire_safety_alarm_building`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fire_safety_alarm_building_alarm_id_foreign` (`alarm_id`) USING BTREE,
  ADD KEY `fire_safety_alarm_building_building_id_foreign` (`building_id`) USING BTREE;

--
-- Indexes for table `fire_safety_archives`
--
ALTER TABLE `fire_safety_archives`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fire_safety_archives_unified_school_id_foreign` (`unified_school_id`);

--
-- Indexes for table `fire_safety_extinguisher_inspections`
--
ALTER TABLE `fire_safety_extinguisher_inspections`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fire_safety_extinguisher_inspections_extinguisher_id_foreign` (`extinguisher_id`) USING BTREE;

--
-- Indexes for table `fire_safety_extinguisher_room_coverage`
--
ALTER TABLE `fire_safety_extinguisher_room_coverage`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `fs_ext_room_cov_unique` (`extinguisher_id`,`room_id`) USING BTREE,
  ADD KEY `fire_safety_extinguisher_room_coverage_room_id_foreign` (`room_id`) USING BTREE;

--
-- Indexes for table `fire_safety_inspections`
--
ALTER TABLE `fire_safety_inspections`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fire_safety_inspections_unified_school_id_foreign` (`unified_school_id`);

--
-- Indexes for table `fire_safety_rooms`
--
ALTER TABLE `fire_safety_rooms`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fire_safety_rooms_school_id_building_id_index` (`building_id`) USING BTREE,
  ADD KEY `fire_safety_rooms_nearest_extinguisher_room_id_foreign` (`nearest_extinguisher_room_id`) USING BTREE,
  ADD KEY `fire_safety_rooms_room_type_config_id_foreign` (`room_type_config_id`) USING BTREE,
  ADD KEY `fire_safety_rooms_building_id_foreign` (`building_id`) USING BTREE,
  ADD KEY `fire_safety_rooms_last_inspector_id_foreign` (`last_inspector_id`) USING BTREE,
  ADD KEY `fire_safety_rooms_unified_school_id_foreign` (`unified_school_id`);

--
-- Indexes for table `incident_calendars`
--
ALTER TABLE `incident_calendars`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `incident_calendars_incident_type_id_foreign` (`incident_type_id`) USING BTREE,
  ADD KEY `incident_calendars_incident_status_id_foreign` (`incident_status_id`) USING BTREE,
  ADD KEY `incident_calendars_incident_date_index` (`incident_date`) USING BTREE,
  ADD KEY `incident_calendars_incident_date_school_name_index` (`incident_date`,`school_name`) USING BTREE,
  ADD KEY `incident_calendars_contributor_id_foreign` (`contributor_id`),
  ADD KEY `incident_calendars_status_index` (`status`);

--
-- Indexes for table `incident_checklists`
--
ALTER TABLE `incident_checklists`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `incident_checklists_user_id_checklist_date_index` (`user_id`,`checklist_date`) USING BTREE;

--
-- Indexes for table `incident_statuses`
--
ALTER TABLE `incident_statuses`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `incident_types`
--
ALTER TABLE `incident_types`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `notifications_compliance_type_school_id_is_read_index` (`compliance_type`,`school_id`,`is_read`) USING BTREE,
  ADD KEY `notifications_compliance_type_user_id_is_read_index` (`compliance_type`,`user_id`,`is_read`) USING BTREE,
  ADD KEY `notifications_compliance_type_type_index` (`compliance_type`,`type`) USING BTREE;

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`) USING BTREE;

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schools_school_name_index` (`school_name`),
  ADD KEY `schools_school_id_index` (`school_id`),
  ADD KEY `schools_school_id_number_index` (`school_id_number`),
  ADD KEY `schools_district_index` (`district`);

--
-- Indexes for table `school_specifics_information`
--
ALTER TABLE `school_specifics_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_specifics_information_school_id_module_key_unique` (`school_id`,`module`,`key`),
  ADD KEY `school_specifics_information_school_id_module_index` (`school_id`,`module`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `sessions_user_id_index` (`user_id`) USING BTREE,
  ADD KEY `sessions_last_activity_index` (`last_activity`) USING BTREE;

--
-- Indexes for table `system_configurations`
--
ALTER TABLE `system_configurations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `typ_fld_families`
--
ALTER TABLE `typ_fld_families`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `typ_fld_families_school_id_foreign` (`school_id`);

--
-- Indexes for table `typ_fld_family_members`
--
ALTER TABLE `typ_fld_family_members`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `typ_fld_family_members_family_id_foreign` (`family_id`) USING BTREE;

--
-- Indexes for table `typ_fld_monitoring_snapshots`
--
ALTER TABLE `typ_fld_monitoring_snapshots`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `typ_fld_monitoring_snapshots_evacuation_center_id_type_index` (`type`) USING BTREE,
  ADD KEY `typ_fld_monitoring_snapshots_school_id_foreign` (`school_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `users_email_unique` (`email`) USING BTREE,
  ADD KEY `users_school_id_foreign` (`school_id`) USING BTREE,
  ADD KEY `users_typhoon_school_id_foreign` (`typhoon_school_id`),
  ADD KEY `users_incident_school_id_foreign` (`incident_school_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cmpr_schl_sfty_assessments`
--
ALTER TABLE `cmpr_schl_sfty_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cmpr_schl_sfty_assessment_items`
--
ALTER TABLE `cmpr_schl_sfty_assessment_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `cmpr_schl_sfty_facilities`
--
ALTER TABLE `cmpr_schl_sfty_facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmpr_schl_sfty_students`
--
ALTER TABLE `cmpr_schl_sfty_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cmpr_schl_sfty_student_pathways`
--
ALTER TABLE `cmpr_schl_sfty_student_pathways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `firesafety_alarm_systems`
--
ALTER TABLE `firesafety_alarm_systems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `firesafety_buildings`
--
ALTER TABLE `firesafety_buildings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `firesafety_evacuationplans`
--
ALTER TABLE `firesafety_evacuationplans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `firesafety_fire_extinguishers`
--
ALTER TABLE `firesafety_fire_extinguishers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `firesafety_school_snapshots`
--
ALTER TABLE `firesafety_school_snapshots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fire_safety_alarm_building`
--
ALTER TABLE `fire_safety_alarm_building`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `fire_safety_archives`
--
ALTER TABLE `fire_safety_archives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `fire_safety_extinguisher_inspections`
--
ALTER TABLE `fire_safety_extinguisher_inspections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `fire_safety_extinguisher_room_coverage`
--
ALTER TABLE `fire_safety_extinguisher_room_coverage`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `fire_safety_inspections`
--
ALTER TABLE `fire_safety_inspections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fire_safety_rooms`
--
ALTER TABLE `fire_safety_rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `incident_calendars`
--
ALTER TABLE `incident_calendars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `incident_checklists`
--
ALTER TABLE `incident_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `incident_statuses`
--
ALTER TABLE `incident_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `incident_types`
--
ALTER TABLE `incident_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `school_specifics_information`
--
ALTER TABLE `school_specifics_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `system_configurations`
--
ALTER TABLE `system_configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `typ_fld_families`
--
ALTER TABLE `typ_fld_families`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `typ_fld_family_members`
--
ALTER TABLE `typ_fld_family_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `typ_fld_monitoring_snapshots`
--
ALTER TABLE `typ_fld_monitoring_snapshots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cmpr_schl_sfty_assessments`
--
ALTER TABLE `cmpr_schl_sfty_assessments`
  ADD CONSTRAINT `cmpr_schl_sfty_assessments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmpr_schl_sfty_assessment_items`
--
ALTER TABLE `cmpr_schl_sfty_assessment_items`
  ADD CONSTRAINT `cmpr_schl_sfty_assessment_items_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `cmpr_schl_sfty_assessments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmpr_schl_sfty_facilities`
--
ALTER TABLE `cmpr_schl_sfty_facilities`
  ADD CONSTRAINT `cmpr_schl_sfty_facilities_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmpr_schl_sfty_students`
--
ALTER TABLE `cmpr_schl_sfty_students`
  ADD CONSTRAINT `cmpr_schl_sfty_students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmpr_schl_sfty_student_pathways`
--
ALTER TABLE `cmpr_schl_sfty_student_pathways`
  ADD CONSTRAINT `cmpr_schl_sfty_student_pathways_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `cmpr_schl_sfty_students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `firesafety_alarm_systems`
--
ALTER TABLE `firesafety_alarm_systems`
  ADD CONSTRAINT `firesafety_alarm_systems_anchor_building_id_foreign` FOREIGN KEY (`anchor_building_id`) REFERENCES `firesafety_buildings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `firesafety_alarm_systems_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `firesafety_buildings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `firesafety_alarm_systems_unified_school_id_foreign` FOREIGN KEY (`unified_school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `firesafety_buildings`
--
ALTER TABLE `firesafety_buildings`
  ADD CONSTRAINT `firesafety_buildings_unified_school_id_foreign` FOREIGN KEY (`unified_school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `firesafety_evacuationplans`
--
ALTER TABLE `firesafety_evacuationplans`
  ADD CONSTRAINT `firesafety_evacuationplans_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `firesafety_buildings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `firesafety_evacuationplans_unified_school_id_foreign` FOREIGN KEY (`unified_school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `firesafety_fire_extinguishers`
--
ALTER TABLE `firesafety_fire_extinguishers`
  ADD CONSTRAINT `firesafety_fire_extinguishers_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `firesafety_buildings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `firesafety_fire_extinguishers_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `fire_safety_rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `firesafety_fire_extinguishers_unified_school_id_foreign` FOREIGN KEY (`unified_school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fire_safety_alarm_building`
--
ALTER TABLE `fire_safety_alarm_building`
  ADD CONSTRAINT `fire_safety_alarm_building_alarm_id_foreign` FOREIGN KEY (`alarm_id`) REFERENCES `firesafety_alarm_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fire_safety_alarm_building_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `firesafety_buildings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fire_safety_archives`
--
ALTER TABLE `fire_safety_archives`
  ADD CONSTRAINT `fire_safety_archives_unified_school_id_foreign` FOREIGN KEY (`unified_school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fire_safety_extinguisher_inspections`
--
ALTER TABLE `fire_safety_extinguisher_inspections`
  ADD CONSTRAINT `fire_safety_extinguisher_inspections_extinguisher_id_foreign` FOREIGN KEY (`extinguisher_id`) REFERENCES `firesafety_fire_extinguishers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fire_safety_extinguisher_room_coverage`
--
ALTER TABLE `fire_safety_extinguisher_room_coverage`
  ADD CONSTRAINT `fire_safety_extinguisher_room_coverage_extinguisher_id_foreign` FOREIGN KEY (`extinguisher_id`) REFERENCES `firesafety_fire_extinguishers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fire_safety_extinguisher_room_coverage_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `fire_safety_rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fire_safety_inspections`
--
ALTER TABLE `fire_safety_inspections`
  ADD CONSTRAINT `fire_safety_inspections_unified_school_id_foreign` FOREIGN KEY (`unified_school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fire_safety_rooms`
--
ALTER TABLE `fire_safety_rooms`
  ADD CONSTRAINT `fire_safety_rooms_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `firesafety_buildings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fire_safety_rooms_last_inspector_id_foreign` FOREIGN KEY (`last_inspector_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fire_safety_rooms_nearest_extinguisher_room_id_foreign` FOREIGN KEY (`nearest_extinguisher_room_id`) REFERENCES `fire_safety_rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fire_safety_rooms_room_type_config_id_foreign` FOREIGN KEY (`room_type_config_id`) REFERENCES `system_configurations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fire_safety_rooms_unified_school_id_foreign` FOREIGN KEY (`unified_school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_calendars`
--
ALTER TABLE `incident_calendars`
  ADD CONSTRAINT `incident_calendars_contributor_id_foreign` FOREIGN KEY (`contributor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incident_calendars_incident_status_id_foreign` FOREIGN KEY (`incident_status_id`) REFERENCES `incident_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incident_calendars_incident_type_id_foreign` FOREIGN KEY (`incident_type_id`) REFERENCES `incident_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `school_specifics_information`
--
ALTER TABLE `school_specifics_information`
  ADD CONSTRAINT `school_specifics_information_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typ_fld_families`
--
ALTER TABLE `typ_fld_families`
  ADD CONSTRAINT `typ_fld_families_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typ_fld_family_members`
--
ALTER TABLE `typ_fld_family_members`
  ADD CONSTRAINT `typ_fld_family_members_family_id_foreign` FOREIGN KEY (`family_id`) REFERENCES `typ_fld_families` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typ_fld_monitoring_snapshots`
--
ALTER TABLE `typ_fld_monitoring_snapshots`
  ADD CONSTRAINT `typ_fld_monitoring_snapshots_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_incident_school_id_foreign` FOREIGN KEY (`incident_school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_typhoon_school_id_foreign` FOREIGN KEY (`typhoon_school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
