<?php
class ContactPerson
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM contact_people ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM contact_people WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $statement = db()->prepare('SELECT * FROM contact_people WHERE email = ? LIMIT 1');
        $statement->execute([$email]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO contact_people (name, role, bio, email, phone, photo, sort_order)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $data['name'],
            $data['role'],
            $data['bio'],
            $data['email'],
            $data['phone'],
            $data['photo'],
            $data['sort_order'],
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $statement = db()->prepare(
            'UPDATE contact_people
             SET name = ?, role = ?, bio = ?, email = ?, phone = ?, photo = ?, sort_order = ?
             WHERE id = ?'
        );
        $statement->execute([
            $data['name'],
            $data['role'],
            $data['bio'],
            $data['email'],
            $data['phone'],
            $data['photo'],
            $data['sort_order'],
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $statement = db()->prepare('DELETE FROM contact_people WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function nextSortOrder(): int
    {
        $max = (int) db()->query('SELECT COALESCE(MAX(sort_order), 0) FROM contact_people')->fetchColumn();

        return $max + 1;
    }
}
