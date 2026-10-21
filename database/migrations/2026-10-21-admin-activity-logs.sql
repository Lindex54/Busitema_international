-- Run once when upgrading an existing database. Fresh installations using
-- database/database.sql already include this table.

CREATE TABLE IF NOT EXISTS admin_activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id INT UNSIGNED NULL,
    actor_username VARCHAR(80) NULL,
    action VARCHAR(80) NOT NULL,
    status ENUM('success', 'failure') NOT NULL DEFAULT 'success',
    description VARCHAR(255) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_logs_created (created_at, id),
    INDEX idx_admin_logs_action (action, status),
    INDEX idx_admin_logs_user (admin_user_id)
) ENGINE=InnoDB;
