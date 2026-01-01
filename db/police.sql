-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for police_cms
CREATE DATABASE IF NOT EXISTS `police_cms` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `police_cms`;

-- Dumping structure for table police_cms.activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `activity_type` enum('case_added','case_edited','case_printed','case_deleted','user_added','user_edited','user_deleted','password_changed','login','logout') NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `case_number` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `case_id` (`case_id`),
  KEY `idx_activity_type` (`activity_type`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_user_activity` (`user_id`,`created_at`),
  KEY `idx_case_activity` (`case_id`,`created_at`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activity_logs_ibfk_2` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.activity_logs: ~65 rows (approximately)
DELETE FROM `activity_logs`;
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `activity_type`, `case_id`, `case_number`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
	(1, 2, 'uki', 'user_edited', NULL, NULL, 'Updated user: uki (uki@gmail.com)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:30:35'),
	(2, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:32:18'),
	(3, 1, 'Admin User', 'case_printed', 3, '3007002', 'Printed case: 3007002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:35:50'),
	(4, 1, 'Admin User', 'case_printed', 4, '3007003', 'Printed case: 3007003', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:35:57'),
	(5, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:36:25'),
	(6, 1, 'Admin User', 'case_printed', 5, '3007004', 'Printed case: 3007004', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:36:34'),
	(7, 1, 'Admin User', 'case_edited', 2, '3007001', 'Edited case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:38:19'),
	(8, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:40:47'),
	(9, 2, 'uki', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:40:51'),
	(10, 2, 'uki', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:40:52'),
	(11, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:40:55'),
	(12, 1, 'Admin User', 'password_changed', NULL, NULL, 'Changed password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:41:15'),
	(13, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:41:21'),
	(14, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:41:23'),
	(15, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:41:30'),
	(16, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:41:57'),
	(17, 1, 'Admin User', 'password_changed', NULL, NULL, 'Changed password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:42:49'),
	(18, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:42:55'),
	(19, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:42:58'),
	(20, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:01'),
	(21, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:10'),
	(22, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:11'),
	(23, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:22'),
	(24, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:32'),
	(25, 2, 'uki', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:35'),
	(26, 2, 'uki', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:48'),
	(27, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:43:54'),
	(28, 1, 'Admin User', 'password_changed', NULL, NULL, 'Admin reset password for user: uki', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:49:30'),
	(29, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:49:34'),
	(30, 2, 'uki', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:49:56'),
	(31, 2, 'uki', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:50:01'),
	(32, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:50:11'),
	(33, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:50:19'),
	(34, 2, 'uki', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:50:22'),
	(35, 2, 'uki', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:50:27'),
	(36, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:50:37'),
	(37, 1, 'Admin User', 'password_changed', NULL, NULL, 'Admin reset password for user: uki', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:51:12'),
	(38, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:51:27'),
	(39, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:51:39'),
	(40, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:51:45'),
	(41, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:51:53'),
	(42, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:51:55'),
	(43, 2, 'uki', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:52:15'),
	(44, 2, 'uki', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:53:26'),
	(45, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 16:53:32'),
	(46, 1, 'Admin User', 'case_edited', 8, '3007007', 'Edited case: 3007007', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:05:01'),
	(47, 1, 'Admin User', 'case_edited', 2, '3007001', 'Edited case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:11:51'),
	(48, 1, 'Admin User', 'case_edited', 2, '3007001', 'Edited case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:13:28'),
	(49, 1, 'Admin User', 'case_edited', 2, '3007001', 'Edited case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:15:14'),
	(50, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:15:34'),
	(51, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:15:51'),
	(52, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:16:06'),
	(53, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:20:08'),
	(54, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:20:28'),
	(55, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:21:08'),
	(56, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:23:27'),
	(57, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:23:40'),
	(58, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:23:48'),
	(59, 1, 'Admin User', 'case_edited', 2, '3007001', 'Edited case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:29:01'),
	(60, 1, 'Admin User', 'case_edited', 2, '3007001', 'Edited case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 17:30:07'),
	(61, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 07:25:51'),
	(62, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 07:25:51'),
	(63, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 07:26:06'),
	(64, 1, 'Admin User', 'case_printed', 2, '3007001', 'Printed case: 3007001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 07:31:09'),
	(65, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 07:37:44'),
	(66, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 07:40:36');

-- Dumping structure for table police_cms.cases
CREATE TABLE IF NOT EXISTS `cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_number` varchar(100) NOT NULL,
  `previous_date` date NOT NULL,
  `information_book` text NOT NULL,
  `register_number` varchar(50) NOT NULL COMMENT 'Format: TYPE MM/YYYY (e.g., GCR 08/2022)',
  `date_produce_b_report` date DEFAULT NULL,
  `date_produce_plant` date DEFAULT NULL,
  `opens` text DEFAULT NULL,
  `attorney_general_advice` enum('YES','NO') DEFAULT NULL,
  `production_register_number` text DEFAULT NULL,
  `date_handover_court` date DEFAULT NULL,
  `government_analyst_report` text DEFAULT NULL,
  `receival_memorandum` enum('YES','NO') DEFAULT NULL,
  `analyst_report` enum('YES','NO') DEFAULT NULL,
  `suspect_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`suspect_data`)),
  `witness_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`witness_data`)),
  `progress` text DEFAULT NULL,
  `results` text DEFAULT NULL,
  `next_date` date DEFAULT NULL,
  `case_status` enum('Ongoing','Pending','Closed') NOT NULL DEFAULT 'Ongoing',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_number` (`case_number`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `idx_case_status` (`case_status`),
  CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cases_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.cases: ~13 rows (approximately)
DELETE FROM `cases`;
INSERT INTO `cases` (`id`, `case_number`, `previous_date`, `information_book`, `register_number`, `date_produce_b_report`, `date_produce_plant`, `opens`, `attorney_general_advice`, `production_register_number`, `date_handover_court`, `government_analyst_report`, `receival_memorandum`, `analyst_report`, `suspect_data`, `witness_data`, `progress`, `results`, `next_date`, `case_status`, `created_by`, `created_at`, `updated_at`, `updated_by`) VALUES
	(1, '2005685', '2025-12-17', 'EIB', 'GCR 05/2022', '2025-12-19', '2025-12-11', 'kill by slip', 'YES', 'PR-200/123', '2025-12-11', '', 'YES', 'YES', '[{"name":"KUMARA","address":"kurunagala mawathagama ","ic":"20024568742"}]', '[{"name":"janidu","address":"kurunagala mawathagama ","ic":"20024567645"}]', 'good', 'good', NULL, 'Ongoing', 1, '2025-12-28 13:19:43', '2025-12-28 13:29:42', 1),
	(2, '3007001', '2025-01-05', 'EIB', 'AR 01/2025', '2025-01-10', '2025-01-07', 'ඝාතනය සම්බන්ධ විවෘත නඩුව', 'YES', 'PR-001/2025', '2025-01-08', 'YES', 'YES', 'YES', '[{"name":"\\u0d9a\\u0dc3\\u0dd4\\u0db1\\u0dca","ic":"200245700001","address":"\\u0d9a\\u0dd4\\u0dbb\\u0dd4\\u0dab\\u0dd1\\u0d9c\\u0dbd"}]', '[{"name":"\\u0db1\\u0dd2\\u0db8\\u0dbd\\u0dca","ic":"200245700101","address":"\\u0d9a\\u0dd4\\u0dbb\\u0dd4\\u0dab\\u0dd1\\u0d9c\\u0dbd"}]', 'අධිකරණයේ සාක්ෂි විභාගය සිදු වෙමින් පවතී', 'අවසන් තීන්දුව බලාපොරොත්තු වේ', '2025-12-31', 'Pending', 1, '2025-12-28 13:50:04', '2025-12-31 17:30:07', 1),
	(3, '3007002', '2025-01-06', 'EIB', 'GCR 02/2025', '2025-01-11', '2025-01-08', 'හොරකම් කිරීමේ සිද්ධිය', 'NO', 'PR-002/2025', '2025-01-09', '', 'YES', 'NO', '[{"name":"\\u0dc3\\u0db8\\u0db1\\u0dca","ic":"200245700002","address":"\\u0db8\\u0dcf\\u0dad\\u0dbd\\u0dda"}]', '[{"name":"\\u0dbb\\u0dd4\\u0dc0\\u0db1\\u0dca","ic":"200245700102","address":"\\u0db8\\u0dcf\\u0dad\\u0dbd\\u0dda"}]', 'සාක්ෂි කැඳවමින් පවතී', 'නඩුව ඉදිරියට විභාගයට නියමිත', '2026-02-02', 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-31 15:39:48', 1),
	(4, '3007003', '2025-01-07', 'EIB', 'GCR 03/2025', '2025-01-12', '2025-01-09', 'මත්ද්‍රව්‍ය භාවිතය හා ගබඩා කිරීම', 'YES', 'PR-003/2025', '2025-01-10', 'YES', 'YES', 'YES', '[{"name":"\\u0daf\\u0dd2\\u0db1\\u0dd4\\u0d9a","ic":"200245700003","address":"\\u0d9a\\u0ddc\\u0dc5\\u0db9"}]', '[{"name":"\\u0d85\\u0db8\\u0dd2\\u0dbd","ic":"200245700103","address":"\\u0d9a\\u0ddc\\u0dc5\\u0db9"}]', 'රජයේ විශ්ලේෂක වාර්තාව ඉදිරිපත් කර ඇත', 'අධිකරණය විසින් දඬුවම් නියම කර ඇත', '2026-01-23', 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:51:12', 1),
	(5, '3007004', '2025-01-08', 'EIB', 'GCR 04/2025', '2025-01-13', '2025-01-10', 'දේපල හානි කිරීම', 'NO', 'PR-004/2025', '2025-01-11', '', 'YES', 'NO', '[{"name":"\\u0db4\\u0dc3\\u0db1\\u0dca","ic":"200245700004","address":"\\u0d9c\\u0dcf\\u0dbd\\u0dca\\u0dbd"}]', '[{"name":"\\u0da0\\u0db8\\u0dd2\\u0db1\\u0dca\\u0daf","ic":"200245700104","address":"\\u0d9c\\u0dcf\\u0dbd\\u0dca\\u0dbd"}]', 'අධිකරණය වෙත නඩුව ඉදිරිපත් කර ඇත', 'සාමදානයට එකඟතාවය ලබාගෙන ඇත', '2025-03-01', 'Pending', 1, '2025-12-28 13:50:04', '2025-12-28 14:14:54', 1),
	(6, '3007005', '2025-01-09', 'EIB', 'GCR 05/2025', '2025-01-14', '2025-01-11', 'වංචා සහ වංචනික ක්‍රියාව', 'YES', 'PR-005/2025', '2025-01-12', 'YES', 'YES', 'YES', '[{"name":"ඉසුරු","address":"නුවර","ic":"200245700005"}]', '[{"name":"සහන්","address":"නුවර","ic":"200245700105"}]', 'නීතිපති උපදෙස් අනුව කටයුතු සිදු වෙයි', 'නඩුව ඉදිරියට ගෙන යාමට නියමිත', NULL, 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:50:04', 1),
	(7, '3007006', '2025-01-10', 'EIB', 'GCR 06/2025', '2025-01-15', '2025-01-12', 'ගෘහස්ථ හිංසනය', 'NO', 'PR-006/2025', '2025-01-13', '', 'YES', 'NO', '[{"name":"ලහිරු","address":"කෑගල්ල","ic":"200245700006"}]', '[{"name":"මධුෂා","address":"කෑගල්ල","ic":"200245700106"}]', 'අධිකරණය විසින් වාරණ නියෝග නිකුත් කර ඇත', 'නීතිමය ක්‍රියාමාර්ග අඛණ්ඩව සිදු වේ', '2025-02-20', 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:50:04', 1),
	(8, '3007007', '2025-01-11', 'GCIB III', 'GCR 07/2025', '2025-01-16', '2025-01-13', 'මාර්ග අනතුරක් සම්බන්ධ නඩුව', 'NO', 'PR-007/2025', '2025-01-14', '', 'YES', 'NO', '[{"name":"\\u0da0\\u0dad\\u0dd4\\u0dbb","ic":"200245700007","address":"\\u0dc4\\u0ddd\\u0db8\\u0dcf\\u0d9c\\u0db8"}]', '[{"name":"\\u0db4\\u0dca\\u200d\\u0dbb\\u0dd2\\u0dba\\u0db1\\u0dca\\u0dad","ic":"200245700107","address":"\\u0dc4\\u0ddd\\u0db8\\u0dcf\\u0d9c\\u0db8"}]', 'පොලිස් වාර්තාව අධිකරණයට ඉදිරිපත් කර ඇත', 'විභාගය සඳහා දිනය නියම කර ඇත', '2025-03-05', 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-31 17:05:01', 1),
	(9, '3007008', '2025-01-12', 'EIB', 'GCR 08/2025', '2025-01-17', '2025-01-14', 'නීති විරෝධී ආයුධ භාවිතය', 'YES', 'PR-008/2025', '2025-01-15', 'YES', 'YES', 'YES', '[{"name":"රංග","address":"අනුරාධපුර","ic":"200245700008"}]', '[{"name":"සුමිත්","address":"අනුරාධපුර","ic":"200245700108"}]', 'විශ්ලේෂණ වාර්තා සම්පූර්ණ කර ඇත', 'අධිකරණය විසින් දඬුවම් නියම කර ඇත', NULL, 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:50:04', 1),
	(10, '3007009', '2025-01-13', 'EIB', 'GCR 09/2025', '2025-01-18', '2025-01-15', 'රාජ්‍ය දේපල හානි කිරීම', 'YES', 'PR-009/2025', '2025-01-16', 'YES', 'YES', 'YES', '[{"name":"ශාන්","address":"බදුල්ල","ic":"200245700009"}]', '[{"name":"නාලක","address":"බදුල්ල","ic":"200245700109"}]', 'නීතිපති උපදෙස් ලබාගෙන ඇත', 'නඩුව ඉදිරියට ගෙන යාමට තීරණය කර ඇත', NULL, 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:50:04', 1),
	(11, '3007010', '2025-01-14', 'EIB', 'GCR 10/2025', '2025-01-19', '2025-01-16', 'වංචනික ලේඛන සකස් කිරීම', 'YES', 'PR-010/2025', '2025-01-17', 'YES', 'YES', 'YES', '[{"name":"මධුර","address":"කළුතර","ic":"200245700010"}]', '[{"name":"රොෂාන්","address":"කළුතර","ic":"200245700110"}]', 'ලේඛන පරීක්ෂා කටයුතු අවසන්', 'නඩුව විභාගය සඳහා නියමිත', '2025-03-10', 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:50:04', 1),
	(12, '3007011', '2025-01-15', 'EIB', 'GCR 11/2025', '2025-01-20', '2025-01-17', 'දූෂණ චෝදනාව', 'YES', 'PR-011/2025', '2025-01-18', 'YES', 'YES', 'YES', '[{"name":"අජිත්","address":"කුරුණෑගල","ic":"200245700011"}]', '[{"name":"සුරේෂ්","address":"කුරුණෑගල","ic":"200245700111"}]', 'නඩුව විභාගයට ලක් වෙමින් පවතී', 'අවසන් තීන්දුව අපේක්ෂාවෙන්', NULL, 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:50:04', 1),
	(13, '3007012', '2025-01-16', 'EIB', 'GCR 12/2025', '2025-01-21', '2025-01-18', 'පොදු සාමය භංග කිරීම', 'NO', 'PR-012/2025', '2025-01-19', '', 'YES', 'NO', '[{"name":"චමින්ද","address":"ගම්පහ","ic":"200245700012"}]', '[{"name":"රවී","address":"ගම්පහ","ic":"200245700112"}]', 'පොලිස් වාර්තාව ඉදිරිපත් කර ඇත', 'නඩුව ඉදිරියට විභාගයට නියමිත', '2025-03-15', 'Ongoing', 1, '2025-12-28 13:50:04', '2025-12-28 13:50:04', 1);

-- Dumping structure for table police_cms.next_date_history
CREATE TABLE IF NOT EXISTS `next_date_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL,
  `next_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `idx_case_id` (`case_id`),
  KEY `idx_next_date` (`next_date`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `next_date_history_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `next_date_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table police_cms.next_date_history: ~5 rows (approximately)
DELETE FROM `next_date_history`;
INSERT INTO `next_date_history` (`id`, `case_id`, `next_date`, `notes`, `created_by`, `created_at`) VALUES
	(1, 4, '2026-01-23', '', 1, '2025-12-28 13:51:12'),
	(2, 2, '2026-01-01', '', 1, '2025-12-28 14:39:14'),
	(3, 2, '2026-02-02', '', 1, '2025-12-28 14:39:52'),
	(4, 3, '2026-02-02', '', 1, '2025-12-31 15:39:48'),
	(5, 2, '2026-01-31', '', 1, '2025-12-31 16:38:19'),
	(6, 2, '2025-12-31', '', 1, '2025-12-31 17:30:07');

-- Dumping structure for table police_cms.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(50) DEFAULT NULL,
  `rank_title` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_user_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `position`, `rank_title`, `status`, `role`, `created_at`, `updated_at`) VALUES
	(1, 'Admin User', 'admin@police.gov', '$2y$10$oFqF6ibXi6mWl5HIfDngNObE46srJTzM0nThc6w4..VasCSpKzqBG', 'System Administrator', 'Chief', 'active', 'admin', '2025-12-25 19:21:53', '2025-12-31 16:42:49'),
	(2, 'uki', 'uki@gmail.com', '$2y$10$wP8h/vE4z47r84iVOgYtL.LzoNht6rgb0XxrS8oD.NBQOh78nXhmG', 'dasd', 'asdas', 'active', 'user', '2025-12-31 16:19:21', '2025-12-31 16:51:12');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
