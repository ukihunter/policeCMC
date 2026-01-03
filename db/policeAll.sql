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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.activity_logs: ~1 rows (approximately)
DELETE FROM `activity_logs`;
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `activity_type`, `case_id`, `case_number`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
	(1, 1, 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 10:49:08');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.cases: ~0 rows (approximately)
DELETE FROM `cases`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table police_cms.next_date_history: ~0 rows (approximately)
DELETE FROM `next_date_history`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table police_cms.system_settings: ~0 rows (approximately)
DELETE FROM `system_settings`;

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
