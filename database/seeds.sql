-- Starter database seed data.
-- Default admin login: okelloericdenis@gmail.com / Busitema@2026
-- Change this password immediately after first login.

USE busitema_international;

INSERT INTO admin_users (name, email, password_hash, role)
VALUES (
    'Site Administrator',
    'okelloericdenis@gmail.com',
    '$2y$10$6aSATlyZdhTczk4GIJInrOIEURPjtJPy2/ILSS7g2s8Sv5LCpzucC',
    'admin'
)
ON DUPLICATE KEY UPDATE name = VALUES(name);
