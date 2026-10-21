-- Run this once only when upgrading a database created before username login.
-- Fresh installations using database/database.sql do not need this migration.

ALTER TABLE admin_users
    ADD COLUMN username VARCHAR(80) NULL AFTER name;

UPDATE admin_users
SET username = CONCAT('admin', id)
WHERE username IS NULL OR username = '';

ALTER TABLE admin_users
    MODIFY username VARCHAR(80) NOT NULL,
    MODIFY email VARCHAR(190) NULL,
    ADD UNIQUE KEY uq_admin_users_username (username);
