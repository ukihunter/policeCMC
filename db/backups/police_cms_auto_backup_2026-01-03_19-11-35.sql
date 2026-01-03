-- Police CMS Database Auto Backup
-- Generated: 2026-01-03 19:11:35
-- Database: police_cms

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `activity_logs` VALUES ('1', '1', 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 16:19:08');
INSERT INTO `activity_logs` VALUES ('2', '1', 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 17:04:20');
INSERT INTO `activity_logs` VALUES ('3', '1', 'Admin User', 'case_added', '1', '23123213', 'Added new case: 23123213', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 17:05:04');
INSERT INTO `activity_logs` VALUES ('4', '1', 'Admin User', 'case_added', '2', '23123', 'Added new case: 23123', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 17:26:03');
INSERT INTO `activity_logs` VALUES ('5', '1', 'Admin User', 'case_added', '3', '32143214', 'Added new case: 32143214', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 17:31:20');
INSERT INTO `activity_logs` VALUES ('6', '1', 'Admin User', 'login', NULL, NULL, 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 22:59:30');
INSERT INTO `activity_logs` VALUES ('7', '1', 'Admin User', 'case_added', '4', '123456', 'Added new case: 123456', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 23:00:34');
INSERT INTO `activity_logs` VALUES ('8', '1', 'Admin User', 'case_edited', '4', '123456', 'Edited case: 123456', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 23:05:07');

DROP TABLE IF EXISTS `backup_history`;
CREATE TABLE `backup_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `backup_type` enum('manual','auto') NOT NULL DEFAULT 'manual',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `backup_type` (`backup_type`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `backup_history_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `cases`;
CREATE TABLE `cases` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cases` VALUES ('1', '23123213', '2026-01-14', 'CIB_II', 'MOR 08/2023', NULL, NULL, 'dsadasdas', NULL, 'sadsada', NULL, NULL, 'YES', 'YES', '[{\"name\":\"dasad\",\"address\":\"dfdsfsdf\",\"ic\":\"324324324\"}]', '[{\"name\":\"dsfdsfsdfds\",\"address\":\"dsvsdfdsf\",\"ic\":\"3214324324\"}]', 'fdsf', 'dsfsdf', NULL, 'Ongoing', '1', '2026-01-03 17:05:04', '2026-01-03 17:05:04', '1');
INSERT INTO `cases` VALUES ('2', '23123', '2026-01-16', '119_IB', 'MOR 10/2016', '2026-01-16', '2026-01-12', 'dsfsdfdsf', 'YES', '', NULL, NULL, 'NO', 'YES', '[{\"name\":\"fdsfds\",\"address\":\"vcsdfdsf\",\"ic\":\"21321321\"}]', '[{\"name\":\"dsfgvsfgsd\",\"address\":\"fgdsgfsfdgsdfg\",\"ic\":\"13432143214\"}]', 'fdsggdsfg', 'dfgdfgdfg', NULL, 'Ongoing', '1', '2026-01-03 17:26:03', '2026-01-03 17:26:03', '1');
INSERT INTO `cases` VALUES ('3', '32143214', '2026-01-08', 'CIB_II', 'MCR 07/2016', '2026-01-13', '2026-01-13', 'sadasdsad', 'YES', 'sdsadasd', '2026-01-23', NULL, 'YES', 'YES', '[{\"name\":\"asdasdsa\",\"address\":\"sdaasdfasd\",\"ic\":\"13242134321\"}]', '[{\"name\":\"qefwCDSf\",\"address\":\"DSFDSFSDF\",\"ic\":\"23141424\"}]', 'FDSFDSF', 'DSFSDFDSFDSF', NULL, 'Pending', '1', '2026-01-03 17:31:20', '2026-01-03 17:31:20', '1');
INSERT INTO `cases` VALUES ('4', '123456', '2026-01-07', 'CIB_I', 'VMOR 09/2020', '2026-01-08', '2026-01-06', 'dasdasdsad', 'YES', 'PR-20023', '2026-01-14', NULL, 'YES', 'YES', '[{\"name\":\"dasd\",\"ic\":\"131432\",\"address\":\"vsdvsdafgvfdv\"}]', '[{\"name\":\"213\",\"ic\":\"fdsfsdf\",\"address\":\"dfsdf\"}]', 'dsfsdf', 'sfsdf', '2026-01-07', 'Ongoing', '1', '2026-01-03 23:00:34', '2026-01-03 23:05:07', '1');

DROP TABLE IF EXISTS `next_date_history`;
CREATE TABLE `next_date_history` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `next_date_history` VALUES ('1', '4', '2026-01-07', '', '1', '2026-01-03 23:05:07');

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
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

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
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

INSERT INTO `users` VALUES ('1', 'Admin User', 'admin@police.gov', '$2y$10$oFqF6ibXi6mWl5HIfDngNObE46srJTzM0nThc6w4..VasCSpKzqBG', 'System Administrator', 'Chief', 'active', 'admin', '2025-12-26 00:51:53', '2025-12-31 22:12:49');
INSERT INTO `users` VALUES ('2', 'uki', 'uki@gmail.com', '$2y$10$HT191kCqLBrifZzezjCGfukTHs5EimI1wZjBUnA2CE/jWe2TfKU22', 'OIC', 'IP', 'active', 'user', '2025-12-31 21:49:21', '2026-01-03 15:05:41');

SET FOREIGN_KEY_CHECKS=1;
