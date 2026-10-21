-- Insert default 'user' and 'admin' roles into the roles table
-- Run this in your database tool (phpMyAdmin, DBeaver, etc)

INSERT INTO roles (name, guard_name, created_at, updated_at)
VALUES 
    ('user', 'web', NOW(), NOW()),
    ('admin', 'web', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    updated_at = NOW();

-- Verify the roles were created
SELECT * FROM roles WHERE guard_name = 'web';
