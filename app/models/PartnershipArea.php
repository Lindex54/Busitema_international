<?php
class PartnershipArea
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM partnership_areas ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM partnership_areas WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO partnership_areas (title, description, sort_order) VALUES (?, ?, ?)'
        );
        $statement->execute([$data['title'], $data['description'], $data['sort_order']]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $statement = db()->prepare(
            'UPDATE partnership_areas SET title = ?, description = ?, sort_order = ? WHERE id = ?'
        );
        $statement->execute([$data['title'], $data['description'], $data['sort_order'], $id]);
    }

    public static function delete(int $id): void
    {
        $statement = db()->prepare('DELETE FROM partnership_areas WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function nextSortOrder(): int
    {
        $max = (int) db()->query('SELECT COALESCE(MAX(sort_order), 0) FROM partnership_areas')->fetchColumn();

        return $max + 1;
    }
}
