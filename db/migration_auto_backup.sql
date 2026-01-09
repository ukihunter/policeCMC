-- Migration to add database_backup activity type
-- Run this SQL to enable automatic backup logging

USE police_cms;

-- Add 'database_backup' to the activity_type enum
ALTER TABLE activity_logs 
MODIFY COLUMN activity_type ENUM(
    'case_added',
    'case_edited',
    'case_printed',
    'case_deleted',
    'user_added',
    'user_edited',
    'user_deleted',
    'password_changed',
    'login',
    'logout',
    'database_backup'
) NOT NULL;

-- Create the system_settings entry for tracking last backup (if it doesn't exist)
-- This will be created automatically by the backup script, but we can initialize it here
INSERT IGNORE INTO system_settings (setting_key, setting_value, updated_at) 
VALUES ('last_auto_backup_date', '2000-01-01 00:00:00', NOW());
