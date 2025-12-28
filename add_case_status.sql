-- Add case_status column to cases table
-- Status options: Ongoing, Pending, Closed

USE police_cms;

-- Add the status column with default value 'Ongoing'
ALTER TABLE cases 
ADD COLUMN case_status ENUM('Ongoing', 'Pending', 'Closed') NOT NULL DEFAULT 'Ongoing' AFTER next_date;

-- Add an index for better performance
CREATE INDEX idx_case_status ON cases(case_status);

SELECT 'Case status column added successfully!' AS status;
SELECT 'All existing cases are set to Ongoing by default' AS note;
