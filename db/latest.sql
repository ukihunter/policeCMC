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
  `activity_type` enum('case_added','case_edited','case_printed','case_deleted','user_added','user_edited','user_deleted','password_changed','login','logout','database_backup') NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.activity_logs: ~54 rows (approximately)
DELETE FROM `activity_logs`;
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `activity_type`, `case_id`, `case_number`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
	(1, 1, 'Admin User', 'case_added', NULL, '1', 'Added new case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 21:47:37'),
	(2, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 21:48:20'),
	(3, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 21:52:36'),
	(4, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:00:12'),
	(5, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:03:14'),
	(6, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:03:23'),
	(7, 1, 'System Admin', 'case_added', NULL, NULL, '50 sample cases added to the system for testing purposes', '127.0.0.1', 'SQL Script', '2026-01-06 22:03:42'),
	(8, 1, 'Admin User', 'case_edited', NULL, '0', 'Edited case: CASE-2025-002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:04:00'),
	(9, 1, 'Admin User', 'case_edited', 43, '0', 'Edited case: CASE-2024-042', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:11:57'),
	(10, 1, 'Admin User', 'case_edited', NULL, '0', 'Edited case: CASE-2024-044', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:12:08'),
	(11, 1, 'Admin User', 'case_edited', NULL, '0', 'Edited case: CASE-2024-045', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:12:20'),
	(12, 1, 'Admin User', 'case_edited', 13, '0', 'Edited case: CASE-2024-012', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 22:13:01'),
	(13, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 17:55:20'),
	(14, 1, 'Admin User', 'case_edited', 43, '0', 'Edited case: CASE-2024-042', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 17:55:44'),
	(15, 1, 'Admin User', 'case_edited', 43, '0', 'Edited case: CASE-2024-042', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 17:55:53'),
	(16, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 17:56:49'),
	(17, 1, 'Admin User', 'case_edited', NULL, '0', 'Edited case: CASE-2025-004', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:03:30'),
	(18, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:04:50'),
	(19, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:24:16'),
	(20, 1, 'Admin User', 'case_edited', NULL, '1', 'Edited case: 0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:24:44'),
	(21, 1, 'Admin User', 'case_added', NULL, '0', 'Added new case: CASE-2024-090', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:28:23'),
	(22, 1, 'Admin User', 'case_printed', NULL, '0', 'Printed case: CASE-2024-090', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:28:54'),
	(23, 1, 'Admin User', 'case_printed', NULL, '0', 'Printed multiple cases including: , , , , , , , , , ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:30:24'),
	(24, 1, 'Admin User', 'case_added', NULL, NULL, 'user_edited', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:30:43'),
	(25, 1, 'Admin User', 'case_printed', NULL, '0', 'Printed multiple cases including: , , , , , , , , , ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:31:42'),
	(26, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:32:19'),
	(27, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:32:51'),
	(28, 1, 'Admin User', 'logout', NULL, NULL, 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:32:54'),
	(29, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 18:34:03'),
	(30, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 21:50:42'),
	(39, 1, 'Admin User', 'case_deleted', NULL, '0', 'Deleted case: CASE-2024-045 (Info Book: RIB, Register: GCR 12/2024)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:01:57'),
	(40, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-09-40.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:09:41'),
	(41, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-10-14.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:10:14'),
	(42, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-08_23-11-17.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:11:17'),
	(43, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-11-24.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:11:24'),
	(44, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-08_23-12-26.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:12:26'),
	(45, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-14-21.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:14:21'),
	(46, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-16-50.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:16:51'),
	(47, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-08_23-17-11.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:17:11'),
	(48, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-18-35.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:18:35'),
	(49, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-08_23-18-56.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:18:56'),
	(50, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-20-09.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-08 22:20:09'),
	(51, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-08_23-20-21.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-08 22:20:21'),
	(52, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-08_23-39-40.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-08 22:39:40'),
	(53, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:50:54'),
	(54, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-08_23-53-04.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:53:05'),
	(55, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:54:25'),
	(56, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:54:25'),
	(57, 1, 'Admin User', 'case_deleted', NULL, '0', 'Deleted case: CASE-2024-044 (Info Book: RIB, Register: GCR 12/2024)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 22:54:44'),
	(58, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-09 07:26:46'),
	(59, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-09_08-26-52.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-09 07:26:53'),
	(60, 1, 'Admin User', '', NULL, NULL, 'Desktop backup created: police_cms_backup_2026-01-09_08-27-21.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-09 07:27:21'),
	(61, 1, 'Admin User', '', NULL, NULL, 'Database backup created: police_cms_backup_2026-01-09_08-27-24.sql (0.04 MB)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-09 07:27:24'),
	(62, NULL, 'System', 'database_backup', NULL, NULL, 'Automatic database backup created: police_cms_auto_backup_2026-01-09_08-41-57.sql (0.04 MB)', 'Auto Backup', NULL, '2026-01-09 07:41:57'),
	(63, NULL, 'System', 'database_backup', NULL, NULL, 'Automatic database backup created: police_cms_auto_backup_2026-01-09_09-12-24.sql (0.04 MB)', 'Auto Backup', NULL, '2026-01-09 08:12:24');

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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.cases: ~46 rows (approximately)
DELETE FROM `cases`;
INSERT INTO `cases` (`id`, `case_number`, `previous_date`, `information_book`, `register_number`, `date_produce_b_report`, `date_produce_plant`, `opens`, `attorney_general_advice`, `production_register_number`, `date_handover_court`, `government_analyst_report`, `receival_memorandum`, `analyst_report`, `suspect_data`, `witness_data`, `progress`, `results`, `next_date`, `case_status`, `created_by`, `created_at`, `updated_at`, `updated_by`) VALUES
	(2, 'CASE-2024-001', '2024-01-15', 'RIB', 'GCR 01/2024', '2024-01-20', '2024-01-22', 'Theft of mobile phones from electronics shop', 'YES', 'PR-2024-001\nPR-2024-002', '2024-02-01', NULL, 'YES', 'NO', '[{"name":"John Doe","address":"123 Main St, City","ic_number":"840115-05-5678"}]', '[{"name":"Sarah Smith","address":"456 Market Rd","ic_number":"850220-08-1234"}]', 'Investigation ongoing. Suspect arrested and remanded.', NULL, '2024-02-15', 'Ongoing', 1, '2024-01-15 05:00:00', '2026-01-06 22:03:42', NULL),
	(3, 'CASE-2024-002', '2024-01-18', 'GCIB I', 'GCR 01/2024', '2024-01-25', NULL, 'Armed robbery at jewelry store', 'YES', 'PR-2024-003\nPR-2024-004\nPR-2024-005', '2024-02-10', NULL, 'YES', 'YES', '[{"name":"Ahmad Hassan","address":"789 Oak Ave","ic_number":"900305-12-3456"},{"name":"Lim Wei Ming","address":"321 Pine St","ic_number":"910615-07-8901"}]', '[{"name":"Tan Mei Ling","address":"654 Cedar Ln","ic_number":"880912-10-2345"},{"name":"Kumar Rajesh","address":"987 Maple Dr","ic_number":"750404-11-5678"}]', 'Both suspects in custody. Jewelry items recovered.', 'Convicted - 5 years imprisonment', '2024-03-01', 'Closed', 1, '2024-01-18 08:50:00', '2026-01-06 22:03:42', NULL),
	(4, 'CASE-2024-003', '2024-02-01', 'MOIB', 'MOR 02/2024', '2024-02-08', '2024-02-10', 'Motorcycle theft from parking lot', 'NO', 'PR-2024-006', NULL, NULL, 'NO', 'NO', '[{"name":"David Chen","address":"147 River Rd","ic_number":"920825-06-4567"}]', '[{"name":"Emily Wong","address":"258 Hill St","ic_number":"870515-05-6789"}]', 'Suspect identified through CCTV. Motorcycle still missing.', NULL, '2024-02-20', 'Pending', 1, '2024-02-01 03:45:00', '2026-01-06 22:03:42', NULL),
	(5, 'CASE-2024-004', '2024-02-05', 'VIB', 'VMOR 02/2024', '2024-02-12', '2024-02-14', 'Vehicle break-in and theft', 'YES', 'PR-2024-007\nPR-2024-008', '2024-02-28', NULL, 'YES', 'NO', '[{"name":"Michael Tan","address":"369 Valley Rd","ic_number":"880430-09-2345"}]', '[{"name":"Lisa Anderson","address":"741 Summit Ave","ic_number":"910723-12-8901"}]', 'Fingerprints matched. Stolen items partially recovered.', NULL, '2024-03-10', 'Ongoing', 1, '2024-02-05 06:15:00', '2026-01-06 22:03:42', NULL),
	(6, 'CASE-2024-005', '2024-02-10', 'CIB I', 'GCR 02/2024', '2024-02-18', NULL, 'Burglary at residential house', 'NO', 'PR-2024-009', NULL, NULL, 'NO', 'NO', '[]', '[{"name":"Mary Johnson","address":"852 Park Ln","ic_number":"780912-08-3456"}]', 'Investigation in progress. No suspects identified yet.', NULL, '2024-02-25', 'Ongoing', 1, '2024-02-10 11:00:00', '2026-01-06 22:03:42', NULL),
	(7, 'CASE-2024-006', '2024-03-01', 'RIB', 'GCR 03/2024', '2024-03-08', '2024-03-10', 'Shoplifting at supermarket', 'NO', 'PR-2024-010', '2024-03-20', NULL, 'YES', 'NO', '[{"name":"Susan Lee","address":"963 Beach Rd","ic_number":"950617-11-7890"}]', '[{"name":"Security Guard Tom","address":"147 Store St","ic_number":"720305-06-4567"}]', 'Caught on camera. Suspect confessed.', 'Case dismissed - first offense', '2024-04-01', 'Closed', 1, '2024-03-01 07:30:00', '2026-01-06 22:03:42', NULL),
	(8, 'CASE-2024-007', '2024-03-05', 'GCIB II', 'GCR 03/2024', '2024-03-15', '2024-03-18', 'Commercial premises burglary', 'YES', 'PR-2024-011\nPR-2024-012\nPR-2024-013', '2024-03-30', NULL, 'YES', 'YES', '[{"name":"Robert Wong","address":"258 Business Park","ic_number":"860820-10-5678"},{"name":"James Lim","address":"369 Industrial Rd","ic_number":"890515-07-2345"}]', '[{"name":"Security Officer Ali","address":"741 Guard House","ic_number":"750220-12-8901"}]', 'Multiple burglaries linked. Both suspects arrested.', NULL, '2024-04-15', 'Ongoing', 1, '2024-03-05 04:50:00', '2026-01-06 22:03:42', NULL),
	(9, 'CASE-2024-008', '2024-03-12', 'EIB', 'GCR 03/2024', '2024-03-20', NULL, 'Embezzlement of company funds', 'YES', 'PR-2024-014', '2024-04-05', NULL, 'YES', 'NO', '[{"name":"Patricia Kumar","address":"852 Finance Tower","ic_number":"820910-08-6789"}]', '[{"name":"CEO William Tan","address":"963 Corporate Ave","ic_number":"680425-05-3456"}]', 'Financial records being audited.', NULL, '2024-04-20', 'Ongoing', 1, '2024-03-12 10:15:00', '2026-01-06 22:03:42', NULL),
	(10, 'CASE-2024-009', '2024-03-20', 'CPUIB', 'MCR 03/2024', '2024-03-28', '2024-03-30', 'Cybercrime - Online fraud', 'YES', 'PR-2024-015', '2024-04-10', NULL, 'NO', 'YES', '[{"name":"Kevin Ng","address":"147 Tech Park","ic_number":"930605-09-4567"}]', '[{"name":"Victim Sarah Chong","address":"258 Cyber St","ic_number":"850815-11-7890"}]', 'Digital forensics analysis completed.', NULL, '2024-04-25', 'Pending', 1, '2024-03-20 04:00:00', '2026-01-06 22:03:42', NULL),
	(11, 'CASE-2024-010', '2024-04-01', 'WCIB', 'GCR 04/2024', '2024-04-10', '2024-04-12', 'White collar crime - Tax evasion', 'YES', 'PR-2024-016\nPR-2024-017', '2024-04-25', NULL, 'YES', 'YES', '[{"name":"Richard Teo","address":"369 Finance District","ic_number":"770320-06-5678"}]', '[{"name":"Auditor Michelle Lee","address":"741 Accounting Firm","ic_number":"810712-10-2345"}]', 'Tax records seized and under review.', NULL, '2024-05-10', 'Ongoing', 1, '2024-04-01 08:30:00', '2026-01-06 22:03:42', NULL),
	(12, 'CASE-2024-011', '2024-04-05', 'RIB', 'GCR 04/2024', '2024-04-15', '2024-04-18', 'Possession of dangerous drugs', 'YES', 'PR-2024-018', '2024-04-30', NULL, 'YES', 'YES', '[{"name":"Danny Lee","address":"852 Harbor View","ic_number":"940925-07-8901"}]', '[{"name":"Officer Rahman","address":"Police Station A","ic_number":"720508-12-3456"}]', 'Drugs tested positive. Suspect remanded.', NULL, '2024-05-15', 'Ongoing', 1, '2024-04-05 05:50:00', '2026-01-06 22:03:42', NULL),
	(13, 'CASE-2024-012', '2024-04-10', 'TR', 'MOR 04/2024', '2024-04-20', '2024-04-22', 'Drug trafficking', 'YES', 'PR-2024-019\r\nPR-2024-020\r\nPR-2024-021', '2024-05-05', NULL, 'YES', 'YES', '[{"name":"Marcus Chin","ic":"","address":"963 Waterfront"},{"name":"Tony Yap","ic":"","address":"147 Dock Rd"}]', '[{"name":"Informant X","ic":"","address":"Confidential"}]', 'Large quantity seized. International links being investigated.', '', '2024-05-20', 'Ongoing', 1, '2024-04-10 11:20:00', '2026-01-06 22:13:01', 1),
	(14, 'CASE-2024-013', '2024-04-18', '119 TR', 'GCR 04/2024', '2024-04-28', NULL, 'Drug distribution network', 'YES', 'PR-2024-022', '2024-05-15', NULL, 'NO', 'YES', '[{"name":"Steven Loh","address":"258 Urban Area","ic_number":"910830-09-7890"}]', '[{"name":"Undercover Officer","address":"Classified","ic_number":"Classified"}]', 'Controlled delivery operation successful.', NULL, '2024-05-25', 'Ongoing', 1, '2024-04-18 07:45:00', '2026-01-06 22:03:42', NULL),
	(15, 'CASE-2024-014', '2024-05-01', 'VPN TR', 'GCR 05/2024', '2024-05-10', '2024-05-12', 'Cultivation of cannabis', 'YES', 'PR-2024-023\nPR-2024-024', '2024-05-25', NULL, 'YES', 'YES', '[{"name":"Peter Tan","address":"369 Rural Road","ic_number":"830215-10-5678"}]', '[{"name":"Neighbor Alice","address":"741 Next Door","ic_number":"760820-07-2345"}]', 'Plantation discovered and destroyed.', 'Guilty plea - 3 years imprisonment', '2024-06-10', 'Closed', 1, '2024-05-01 04:30:00', '2026-01-06 22:03:42', NULL),
	(16, 'CASE-2024-015', '2024-05-08', '118 TR', 'GCR 05/2024', '2024-05-18', NULL, 'Possession of drug paraphernalia', 'NO', 'PR-2024-025', NULL, NULL, 'NO', 'YES', '[{"name":"Gary Koh","address":"852 Suburban St","ic_number":"960705-12-8901"}]', '[{"name":"Officer Lee","address":"Police HQ","ic_number":"780920-06-3456"}]', 'Minor offense. Counseling recommended.', NULL, '2024-05-30', 'Pending', 1, '2024-05-08 09:10:00', '2026-01-06 22:03:42', NULL),
	(17, 'CASE-2024-016', '2024-05-15', 'RIB', 'GCR 05/2024', '2024-05-25', '2024-05-28', 'Sale of controlled substances', 'YES', 'PR-2024-026', '2024-06-05', NULL, 'YES', 'YES', '[{"name":"Vincent Ng","address":"963 Night Market","ic_number":"870412-08-6789"}]', '[{"name":"Buyer turned informant","address":"Protected","ic_number":"Protected"}]', 'Sting operation conducted. Suspect arrested.', NULL, '2024-06-20', 'Ongoing', 1, '2024-05-15 15:00:00', '2026-01-06 22:03:42', NULL),
	(18, 'CASE-2024-017', '2024-05-22', 'GCIB III', 'GCR 05/2024', '2024-06-01', '2024-06-05', 'Organized drug syndicate', 'YES', 'PR-2024-027\nPR-2024-028\nPR-2024-029\nPR-2024-030', '2024-06-15', NULL, 'YES', 'YES', '[{"name":"Boss Chen","address":"147 Hideout","ic_number":"750630-11-4567"},{"name":"Runner Kumar","address":"258 Delivery Point","ic_number":"820915-09-7890"},{"name":"Dealer Wong","address":"369 Street Corner","ic_number":"890520-10-5678"}]', '[{"name":"Multiple witnesses","address":"Various","ic_number":"Protected"}]', 'Major operation. Multiple arrests made.', NULL, '2024-07-01', 'Ongoing', 1, '2024-05-22 03:15:00', '2026-01-06 22:03:42', NULL),
	(19, 'CASE-2024-018', '2024-06-01', 'AIB', 'GCR 06/2024', '2024-06-10', NULL, 'Import of illegal drugs', 'YES', 'PR-2024-031', '2024-06-25', NULL, 'YES', 'NO', '[{"name":"Import Manager Lee","address":"741 Port Area","ic_number":"810225-07-2345"}]', '[{"name":"Customs Officer","address":"Port Authority","ic_number":"730815-12-8901"}]', 'Customs interception. Large shipment seized.', NULL, '2024-07-10', 'Ongoing', 1, '2024-06-01 06:30:00', '2026-01-06 22:03:42', NULL),
	(20, 'CASE-2024-019', '2024-06-10', 'PIB', 'GCR 06/2024', '2024-06-20', '2024-06-22', 'Prescription drug abuse', 'NO', 'PR-2024-032', NULL, NULL, 'NO', 'YES', '[{"name":"Doctor suspicious","address":"852 Medical Center","ic_number":"680410-08-6789"}]', '[{"name":"Pharmacy witness","address":"963 Drug Store","ic_number":"750920-11-3456"}]', 'Medical records under investigation.', NULL, '2024-06-30', 'Pending', 1, '2024-06-10 09:50:00', '2026-01-06 22:03:42', NULL),
	(21, 'CASE-2024-020', '2024-06-18', 'TIB', 'TAR 06/2024', '2024-06-28', '2024-06-30', 'Cross-border drug smuggling', 'YES', 'PR-2024-033\nPR-2024-034', '2024-07-15', NULL, 'YES', 'YES', '[{"name":"Smuggler A","address":"Border Area","ic_number":"920505-09-4567"}]', '[{"name":"Border patrol","address":"Checkpoint","ic_number":"710825-10-7890"}]', 'International cooperation requested.', NULL, '2024-07-25', 'Ongoing', 1, '2024-06-18 04:00:00', '2026-01-06 22:03:42', NULL),
	(22, 'CASE-2024-021', '2024-06-25', 'VIB', 'GCR 06/2024', '2024-07-05', '2024-07-08', 'Assault causing bodily harm', 'YES', 'PR-2024-035', '2024-07-20', NULL, 'YES', 'NO', '[{"name":"Attacker James","address":"147 Fight Location","ic_number":"850320-12-5678"}]', '[{"name":"Victim Thomas","address":"258 Hospital","ic_number":"880715-06-2345"},{"name":"Witness Maria","address":"369 Nearby","ic_number":"920110-08-8901"}]', 'Medical report obtained. Suspect charged.', NULL, '2024-08-05', 'Ongoing', 1, '2024-06-25 16:45:00', '2026-01-06 22:03:42', NULL),
	(23, 'CASE-2024-022', '2024-07-01', 'RIB', 'GCR 07/2024', '2024-07-12', NULL, 'Domestic violence', 'NO', 'PR-2024-036', NULL, NULL, 'NO', 'NO', '[{"name":"Husband Abdul","address":"741 Family Home","ic_number":"790505-11-3456"}]', '[{"name":"Wife Siti","address":"852 Safe House","ic_number":"820910-09-6789"}]', 'Protection order issued. Case ongoing.', NULL, '2024-07-20', 'Ongoing', 1, '2024-07-01 13:15:00', '2026-01-06 22:03:42', NULL),
	(24, 'CASE-2024-023', '2024-07-08', 'GCIB I', 'GCR 07/2024', '2024-07-18', '2024-07-20', 'Gang-related assault', 'YES', 'PR-2024-037\nPR-2024-038', '2024-08-01', NULL, 'YES', 'YES', '[{"name":"Gang member A","address":"963 Territory A","ic_number":"900625-10-4567"},{"name":"Gang member B","address":"147 Territory A","ic_number":"910815-07-7890"}]', '[{"name":"Multiple victims","address":"258 Incident Site","ic_number":"Various"}]', 'Gang unit involved. Multiple charges filed.', NULL, '2024-08-15', 'Ongoing', 1, '2024-07-07 21:50:00', '2026-01-06 22:03:42', NULL),
	(25, 'CASE-2024-024', '2024-07-15', 'MOIB', 'GCR 07/2024', '2024-07-25', NULL, 'Road rage incident', 'NO', 'PR-2024-039', NULL, NULL, 'NO', 'NO', '[{"name":"Driver angry","address":"369 Highway","ic_number":"860430-08-5678"}]', '[{"name":"Other driver","address":"741 Vehicle","ic_number":"880920-12-2345"}]', 'Traffic camera footage obtained.', NULL, '2024-08-01', 'Pending', 1, '2024-07-15 11:00:00', '2026-01-06 22:03:42', NULL),
	(26, 'CASE-2024-025', '2024-07-22', 'CIB II', 'GCR 07/2024', '2024-08-01', '2024-08-05', 'Armed assault', 'YES', 'PR-2024-040', '2024-08-20', NULL, 'YES', 'YES', '[{"name":"Armed suspect","address":"852 Crime Scene","ic_number":"830715-09-8901"}]', '[{"name":"Injured victim","address":"963 Emergency Room","ic_number":"750220-11-3456"}]', 'Weapon recovered. Serious charges filed.', NULL, '2024-09-01', 'Ongoing', 1, '2024-07-22 15:30:00', '2026-01-06 22:03:42', NULL),
	(27, 'CASE-2024-026', '2024-08-01', 'EIB', 'GCR 08/2024', '2024-08-12', NULL, 'Elder abuse case', 'YES', 'PR-2024-041', '2024-08-25', NULL, 'YES', 'NO', '[{"name":"Caretaker negligent","address":"147 Care Home","ic_number":"920505-10-6789"}]', '[{"name":"Family member","address":"258 Report","ic_number":"650810-07-4567"}]', 'Social services involved. Investigation ongoing.', NULL, '2024-09-10', 'Ongoing', 1, '2024-08-01 04:45:00', '2026-01-06 22:03:42', NULL),
	(28, 'CASE-2024-027', '2024-08-10', 'CPUIB', 'GCR 08/2024', '2024-08-20', '2024-08-22', 'Stalking and harassment', 'NO', 'PR-2024-042', NULL, NULL, 'NO', 'NO', '[{"name":"Stalker person","address":"369 Follows victim","ic_number":"880915-08-7890"}]', '[{"name":"Victim scared","address":"741 Protected address","ic_number":"910425-12-5678"}]', 'Restraining order applied for.', NULL, '2024-08-30', 'Pending', 1, '2024-08-10 09:15:00', '2026-01-06 22:03:42', NULL),
	(29, 'CASE-2024-028', '2024-08-18', 'WCIB', 'GCR 08/2024', '2024-08-28', '2024-08-30', 'Workplace violence', 'YES', 'PR-2024-043', '2024-09-10', NULL, 'YES', 'NO', '[{"name":"Employee aggressor","address":"852 Office","ic_number":"840220-11-2345"}]', '[{"name":"Colleague victim","address":"963 Same office","ic_number":"860710-09-8901"}]', 'HR department cooperating. Charges pending.', NULL, '2024-09-20', 'Ongoing', 1, '2024-08-18 06:00:00', '2026-01-06 22:03:42', NULL),
	(30, 'CASE-2024-029', '2024-08-25', 'RIB', 'GCR 08/2024', '2024-09-05', NULL, 'Public brawl', 'NO', 'PR-2024-044', NULL, NULL, 'NO', 'NO', '[{"name":"Brawler one","address":"147 Bar fight","ic_number":"910605-10-3456"},{"name":"Brawler two","address":"258 Bar fight","ic_number":"900820-07-6789"}]', '[{"name":"Bar owner","address":"369 Establishment","ic_number":"720315-08-4567"}]', 'CCTV reviewed. Both parties charged.', 'Mediation successful - charges dropped', '2024-09-15', 'Closed', 1, '2024-08-25 18:20:00', '2026-01-06 22:03:42', NULL),
	(31, 'CASE-2024-030', '2024-09-01', 'GCIB II', 'GCR 09/2024', '2024-09-12', '2024-09-15', 'Kidnapping attempt', 'YES', 'PR-2024-045\nPR-2024-046', '2024-09-25', NULL, 'YES', 'YES', '[{"name":"Attempted kidnapper","address":"741 Suspect location","ic_number":"870410-12-7890"}]', '[{"name":"Would-be victim","address":"852 Escaped","ic_number":"950920-06-5678"}]', 'Suspect apprehended. Serious charges filed.', NULL, '2024-10-10', 'Ongoing', 1, '2024-09-01 13:50:00', '2026-01-06 22:03:42', NULL),
	(32, 'CASE-2024-031', '2024-09-08', 'PIB', 'GCR 09/2024', '2024-09-18', NULL, 'Credit card fraud', 'YES', 'PR-2024-047', '2024-10-01', NULL, 'YES', 'NO', '[{"name":"Fraudster online","address":"963 Virtual","ic_number":"920725-08-2345"}]', '[{"name":"Bank victim","address":"147 Financial Institution","ic_number":"Corporate"}]', 'Multiple transactions traced. Investigation ongoing.', NULL, '2024-10-15', 'Ongoing', 1, '2024-09-08 07:55:00', '2026-01-06 22:03:42', NULL),
	(33, 'CASE-2024-032', '2024-09-15', 'TIB', 'GCR 09/2024', '2024-09-25', '2024-09-28', 'Identity theft', 'YES', 'PR-2024-048', '2024-10-10', NULL, 'YES', 'YES', '[{"name":"Identity thief","address":"258 Fake address","ic_number":"850510-09-8901"}]', '[{"name":"Real person","address":"369 Actual residence","ic_number":"850510-09-1234"}]', 'Documents forged. Cybercrime unit involved.', NULL, '2024-10-25', 'Ongoing', 1, '2024-09-15 09:40:00', '2026-01-06 22:03:42', NULL),
	(34, 'CASE-2024-033', '2024-09-22', 'AIB', 'GCR 09/2024', '2024-10-02', NULL, 'Check forgery', 'NO', 'PR-2024-049', NULL, NULL, 'NO', 'YES', '[{"name":"Forger suspect","address":"741 Location","ic_number":"880315-11-3456"}]', '[{"name":"Bank teller","address":"852 Branch","ic_number":"760920-07-6789"}]', 'Handwriting analysis pending.', NULL, '2024-10-20', 'Pending', 1, '2024-09-22 05:10:00', '2026-01-06 22:03:42', NULL),
	(35, 'CASE-2024-034', '2024-10-01', 'RIB', 'GCR 10/2024', '2024-10-12', '2024-10-15', 'Insurance fraud', 'YES', 'PR-2024-050', '2024-10-28', NULL, 'YES', 'NO', '[{"name":"Claimant false","address":"963 Claim address","ic_number":"830605-10-4567"}]', '[{"name":"Insurance investigator","address":"147 Company","ic_number":"720810-08-7890"}]', 'False claim detected. Charges prepared.', NULL, '2024-11-10', 'Ongoing', 1, '2024-10-01 03:30:00', '2026-01-06 22:03:42', NULL),
	(36, 'CASE-2024-035', '2024-10-08', 'GCIB III', 'GCR 10/2024', '2024-10-18', NULL, 'Investment scam', 'YES', 'PR-2024-051\nPR-2024-052', '2024-11-01', NULL, 'NO', 'NO', '[{"name":"Scammer leader","address":"258 Scam office","ic_number":"900425-12-5678"}]', '[{"name":"Multiple victims","address":"Various locations","ic_number":"Various"}]', 'Ponzi scheme uncovered. Multiple complaints filed.', NULL, '2024-11-15', 'Ongoing', 1, '2024-10-08 11:25:00', '2026-01-06 22:03:42', NULL),
	(37, 'CASE-2024-036', '2024-10-15', 'MOIB', 'GCR 10/2024', '2024-10-25', '2024-10-28', 'Loan shark activity', 'YES', 'PR-2024-053', '2024-11-10', NULL, 'YES', 'YES', '[{"name":"Loan shark operator","address":"369 Underground","ic_number":"810920-09-2345"}]', '[{"name":"Borrower victim","address":"741 Debt address","ic_number":"890510-11-8901"}]', 'Illegal lending operation shut down.', NULL, '2024-11-25', 'Ongoing', 1, '2024-10-15 06:45:00', '2026-01-06 22:03:42', NULL),
	(38, 'CASE-2024-037', '2024-10-22', 'VIB', 'GCR 10/2024', '2024-11-01', NULL, 'Counterfeit currency', 'YES', 'PR-2024-054', '2024-11-15', NULL, 'YES', 'YES', '[{"name":"Counterfeiter","address":"852 Print shop","ic_number":"870715-10-3456"}]', '[{"name":"Shop owner victim","address":"963 Retail","ic_number":"750220-07-6789"}]', 'Printing equipment seized. Bank Negara notified.', NULL, '2024-12-01', 'Ongoing', 1, '2024-10-22 09:00:00', '2026-01-06 22:03:42', NULL),
	(39, 'CASE-2024-038', '2024-11-01', 'CIB I', 'GCR 11/2024', '2024-11-12', '2024-11-15', 'Romance scam online', 'NO', 'PR-2024-055', NULL, NULL, 'NO', 'NO', '[{"name":"Scammer online","address":"Virtual/Unknown","ic_number":"Unknown"}]', '[{"name":"Victim lonely","address":"147 Local","ic_number":"820405-08-4567"}]', 'Cross-border case. Interpol contacted.', NULL, '2024-11-30', 'Pending', 1, '2024-11-01 05:30:00', '2026-01-06 22:03:42', NULL),
	(41, 'CASE-2024-040', '2024-11-15', 'EIB', 'GCR 11/2024', '2024-11-25', '2024-11-28', 'Property title fraud', 'YES', 'PR-2024-057\nPR-2024-058', '2024-12-10', NULL, 'YES', 'YES', '[{"name":"Fraudster documents","address":"741 False claim","ic_number":"860310-11-5678"}]', '[{"name":"True owner","address":"852 Property","ic_number":"680815-10-2345"}]', 'Land office records examined. Case complex.', NULL, '2024-12-20', 'Ongoing', 1, '2024-11-15 08:15:00', '2026-01-06 22:03:42', NULL),
	(42, 'CASE-2024-041', '2024-11-22', 'CPUIB', 'GCR 11/2024', '2024-12-02', NULL, 'Arson investigation', 'YES', 'PR-2024-059', '2024-12-15', NULL, 'NO', 'YES', '[{"name":"Suspect arson","address":"963 Burned building","ic_number":"900520-12-8901"}]', '[{"name":"Fire marshal","address":"Fire Department","ic_number":"730910-08-3456"}]', 'Fire investigation report pending.', NULL, '2025-01-05', 'Ongoing', 1, '2024-11-21 21:00:00', '2026-01-06 22:03:42', NULL),
	(43, 'CASE-2024-042', '2024-12-01', 'RIB', 'GCR 12/2024', '2024-12-12', '2024-12-15', 'Vandalism of public property', 'NO', 'PR-2024-060', NULL, NULL, 'NO', 'NO', '[{"name":"Vandal young","ic":"","address":"147 Neighborhood"}]', '[{"name":"Witness neighbor","ic":"","address":"258 Same area"}]', 'CCTV captured incident. Minor charged.', 'Community service ordered', '2026-01-15', 'Closed', 1, '2024-12-01 14:45:00', '2026-01-08 17:55:53', 1),
	(44, 'CASE-2024-043', '2024-12-08', 'RIB', 'GCR 12/2024', '2024-12-18', NULL, 'Trespassing on private property', 'NO', 'PR-2024-061', NULL, NULL, 'NO', 'NO', '[{"name":"Trespasser","address":"369 Unknown","ic_number":"920715-10-7890"}]', '[{"name":"Property owner","address":"741 Private land","ic_number":"650320-07-5678"}]', 'Warning issued. Case pending.', NULL, '2024-12-30', 'Pending', 1, '2024-12-08 11:30:00', '2026-01-06 22:03:42', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table police_cms.next_date_history: ~2 rows (approximately)
DELETE FROM `next_date_history`;
INSERT INTO `next_date_history` (`id`, `case_id`, `next_date`, `notes`, `created_by`, `created_at`) VALUES
	(3, 43, '2026-01-15', '', 1, '2026-01-08 17:55:53');

-- Dumping structure for table police_cms.system_settings
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `system_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.system_settings: ~2 rows (approximately)
DELETE FROM `system_settings`;
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `updated_by`, `updated_at`) VALUES
	(1, 'police_station', 'Panadura north', 1, '2026-01-08 18:30:43'),
	(10, 'last_auto_backup_date', '2026-01-09 09:12:24', NULL, '2026-01-09 03:42:24');

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
	(2, 'uki', 'uki@gmail.com', '$2y$10$HT191kCqLBrifZzezjCGfukTHs5EimI1wZjBUnA2CE/jWe2TfKU22', 'OIC', 'IP', 'active', 'user', '2025-12-31 16:19:21', '2026-01-03 09:35:41');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
