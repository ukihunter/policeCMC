-- Database Update Script
-- This script updates the existing database to support the new register_number format
-- Run this if you already have the database created from setup_database.sql

USE police_cms;

-- Update the register_number column to support the new format (TYPE MM/YYYY)
ALTER TABLE cases 
MODIFY COLUMN register_number VARCHAR(50) NOT NULL COMMENT 'Format: TYPE MM/YYYY (e.g., GCR 08/2022)';

-- Display success message
SELECT 'Database update complete! The register_number column has been updated to support the new format (e.g., GCR 08/2022).' AS message;
