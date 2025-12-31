-- Add role column to users table
-- Roles: admin (can manage users) and user (regular user, can only change own password)

USE police_cms;

-- Add the role column with default value 'user'
ALTER TABLE users
ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER status;

-- Set the first user as admin (or modify the WHERE clause as needed)
UPDATE users SET role = 'admin' WHERE id = 1 LIMIT 1;

-- Add an index for better performance
CREATE INDEX idx_user_role ON users(role);

SELECT 'User role column added successfully! First user has been set as admin.' AS status;
