-- Run once when upgrading an existing database. Fresh installations using
-- database/database.sql already include this column.

ALTER TABLE admin_users
    ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER role;
