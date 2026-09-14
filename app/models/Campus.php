<?php
class Campus
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM campuses ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM campuses WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO campuses (name, district, focus, description, link, is_featured, is_new, sort_order)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $data['name'],
            $data['district'],
            $data['focus'],
            $data['description'],
            $data['link'] !== '' ? $data['link'] : null,
            $data['is_featured'] ? 1 : 0,
            $data['is_new'] ? 1 : 0,
            $data['sort_order'],
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $statement = db()->prepare(
            'UPDATE campuses
             SET name = ?, district = ?, focus = ?, description = ?, link = ?, is_featured = ?, is_new = ?, sort_order = ?
             WHERE id = ?'
        );
        $statement->execute([
            $data['name'],
            $data['district'],
            $data['focus'],
            $data['description'],
            $data['link'] !== '' ? $data['link'] : null,
            $data['is_featured'] ? 1 : 0,
            $data['is_new'] ? 1 : 0,
            $data['sort_order'],
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $statement = db()->prepare('DELETE FROM campuses WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function nextSortOrder(): int
    {
        $max = (int) db()->query('SELECT COALESCE(MAX(sort_order), 0) FROM campuses')->fetchColumn();

        return $max + 1;
    }
}
