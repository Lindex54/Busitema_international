<?php
class User
{
    public static function findByEmail(string $email): ?array
    {
        $statement = db()->prepare('SELECT * FROM admin_users WHERE email = ? LIMIT 1');
        $statement->execute([$email]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public static function touchLastLogin(int $id): void
    {
        $statement = db()->prepare('UPDATE admin_users SET last_login_at = NOW() WHERE id = ?');
        $statement->execute([$id]);
    }
}
