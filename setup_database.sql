-- Create database
CREATE DATABASE IF NOT EXISTS police_cms;
USE police_cms;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    position VARCHAR(50),
    rank_title VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert test user
-- Email: admin@police.gov
-- Password: admin123
INSERT INTO users (full_name, email, password, position, rank_title, status) 
VALUES (
    'Admin User',
    'admin@police.gov',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'System Administrator',
    'Chief',
    'active'
);

-- Create cases table
CREATE TABLE IF NOT EXISTS cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_number VARCHAR(100) NOT NULL UNIQUE,
    previous_date DATE NOT NULL,
    information_book TEXT NOT NULL,
    register_number VARCHAR(50) NOT NULL COMMENT 'Format: TYPE MM/YYYY (e.g., GCR 08/2022)',
    date_produce_b_report DATE,
    date_produce_plant DATE,
    opens TEXT,
    attorney_general_advice ENUM('YES', 'NO'),
    production_register_number TEXT,
    date_handover_court DATE,
    government_analyst_report TEXT,
    receival_memorandum ENUM('YES', 'NO'),
    analyst_report ENUM('YES', 'NO'),
    suspect_data JSON,
    witness_data JSON,
    progress TEXT,
    results TEXT,
    next_date DATE NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Display success message
SELECT 'Database setup complete! You can now login with:' AS message;
SELECT 'Email: admin@police.gov' AS credentials;
SELECT 'Password: admin123' AS password;
