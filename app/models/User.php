<?php
class User
{
    public static function all(): array
    {
        return db()->query(
            'SELECT id, name, username, email, role, is_active, last_login_at, created_at, updated_at
             FROM admin_users ORDER BY name ASC, id ASC'
        )->fetchAll();
    }

    public static function recentlyLoggedIn(int $limit = 6): array
    {
        $limit = max(1, min($limit, 25));
        $statement = db()->prepare(
            'SELECT id, name, username, email, role, is_active, last_login_at
             FROM admin_users
             ORDER BY last_login_at IS NULL ASC, last_login_at DESC, name ASC
             LIMIT ?'
        );
        $statement->bindValue(1, $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM admin_users WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public static function findByUsername(string $username): ?array
    {
        $statement = db()->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
        $statement->execute([$username]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public static function touchLastLogin(int $id): void
    {
        $statement = db()->prepare('UPDATE admin_users SET last_login_at = NOW() WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function setActive(int $id, bool $isActive): void
    {
        $statement = db()->prepare('UPDATE admin_users SET is_active = ? WHERE id = ?');
        $statement->execute([$isActive ? 1 : 0, $id]);
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO admin_users (name, username, email, password_hash, role)
             VALUES (?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $data['name'],
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] !== '' ? $data['role'] : 'admin',
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $parameters = [
            $data['name'],
            $data['username'],
            $data['email'],
            $data['role'] !== '' ? $data['role'] : 'admin',
        ];
        $passwordSql = '';

        if ($data['password'] !== '') {
            $passwordSql = ', password_hash = ?';
            $parameters[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $parameters[] = $id;
        $statement = db()->prepare(
            "UPDATE admin_users
             SET name = ?, username = ?, email = ?, role = ?$passwordSql
             WHERE id = ?"
        );
        $statement->execute($parameters);
    }

    public static function delete(int $id): void
    {
        $statement = db()->prepare('DELETE FROM admin_users WHERE id = ?');
        $statement->execute([$id]);
    }
}
