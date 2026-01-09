<?php

/**
 * Automatic Database Backup Script
 * This script should be called periodically (e.g., via Windows Task Scheduler or cron)
 * It checks if 3 days have passed since the last backup and creates a new backup if needed
 */

// Get the absolute path to config/db.php
$config_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/config/db.php';
require_once($config_path);

// Check when the last backup was made
$sql = "SELECT setting_value FROM system_settings WHERE setting_key = 'last_auto_backup_date'";
$result = $conn->query($sql);

$should_backup = false;
$last_backup_date = null;

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_backup_date = $row['setting_value'];

    // Calculate days since last backup
    $last_backup_timestamp = strtotime($last_backup_date);
    $current_timestamp = time();
    $days_since_backup = floor(($current_timestamp - $last_backup_timestamp) / (60 * 60 * 24));

    // Backup if 1 or more days have passed
    if ($days_since_backup >= 1) {
        $should_backup = true;
    }
} else {
    // No backup record found, create initial backup
    $should_backup = true;
}

if ($should_backup) {
    try {
        // Get database credentials
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'police_cms';

        // Create backup folder path
        // Prefer OneDrive if available, otherwise use local folder
        $onedrive_path = getenv('OneDrive');
        if ($onedrive_path && is_dir($onedrive_path)) {
            $backup_folder = $onedrive_path . '\\Police_CMS_Backups';
        } else {
            $project_root = dirname(dirname(dirname(dirname(__FILE__))));
            $backup_folder = $project_root . '\\db\\backups';
        }

        // Create backup folder if it doesn't exist
        if (!is_dir($backup_folder)) {
            mkdir($backup_folder, 0777, true);
        }

        // Generate backup filename with timestamp
        $backup_file = $backup_folder . '\\police_cms_auto_backup_' . date('Y-m-d_H-i-s') . '.sql';

        // Use mysqldump to create backup
        $mysqldump_path = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

        if (!file_exists($mysqldump_path)) {
            error_log("Auto backup failed: mysqldump.exe not found at: " . $mysqldump_path);
            exit(1);
        }

        // Build mysqldump command
        $command = '"' . $mysqldump_path . '" --user=' . $db_user . ' --password= --host=' . $db_host . ' ' . $db_name . ' --result-file="' . $backup_file . '"';

        // Execute backup command
        exec($command . ' 2>&1', $output, $return_var);

        // Check if backup file was created and has content
        if (!file_exists($backup_file) || filesize($backup_file) < 100) {
            error_log("Auto backup failed: Backup file creation error");
            if (file_exists($backup_file)) {
                unlink($backup_file);
            }
            exit(1);
        }

        // Get file size for logging
        $file_size = filesize($backup_file);
        $file_size_mb = round($file_size / 1024 / 1024, 2);

        // Update last backup date in system_settings
        $current_date = date('Y-m-d H:i:s');
        $update_sql = "INSERT INTO system_settings (setting_key, setting_value, updated_at) 
                      VALUES ('last_auto_backup_date', ?, ?) 
                      ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssss", $current_date, $current_date, $current_date, $current_date);
        $stmt->execute();

        // Log the automatic backup activity
        $activity_logger_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/config/activity_logger.php';
        require_once($activity_logger_path);
        $details = "Automatic database backup created: " . basename($backup_file) . " (" . $file_size_mb . " MB)";

        // Log with system user (user_id = null for automated tasks)
        $log_sql = "INSERT INTO activity_logs (user_id, user_name, activity_type, description, ip_address, created_at) 
                    VALUES (NULL, 'System', 'database_backup', ?, 'Auto Backup', NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("s", $details);
        $log_stmt->execute();

        error_log("Auto backup successful: " . basename($backup_file) . " (" . $file_size_mb . " MB)");

        // Clean up old backups (keep only last 10 automatic backups)
        cleanupOldBackups($backup_folder);

        exit(0);
    } catch (Exception $e) {
        error_log("Auto backup error: " . $e->getMessage());
        exit(1);
    }
} else {
    // No backup needed yet
    $days_remaining = 1 - floor((time() - strtotime($last_backup_date)) / (60 * 60 * 24));
    error_log("Auto backup: Not needed yet. Next backup in " . $days_remaining . " day(s)");
    exit(0);
}

$conn->close();

/**
 * Clean up old automatic backup files, keeping only the most recent 10
 */
function cleanupOldBackups($backup_folder)
{
    $backup_files = glob($backup_folder . '\\police_cms_auto_backup_*.sql');

    if (count($backup_files) > 10) {
        // Sort by modification time (oldest first)
        usort($backup_files, function ($a, $b) {
            return filemtime($a) - filemtime($b);
        });

        // Delete oldest backups, keeping only the last 10
        $files_to_delete = array_slice($backup_files, 0, count($backup_files) - 10);
        foreach ($files_to_delete as $file) {
            if (strpos(basename($file), 'auto_backup') !== false) {
                unlink($file);
                error_log("Deleted old backup: " . basename($file));
            }
        }
    }
}
