<?php
class HeroSlide
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM hero_slides ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM hero_slides WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO hero_slides (image, eyebrow, title, description, primary_label, primary_url, secondary_label, secondary_url, sort_order)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $data['image'],
            $data['eyebrow'] !== '' ? $data['eyebrow'] : null,
            $data['title'],
            $data['description'] !== '' ? $data['description'] : null,
            $data['primary_label'],
            $data['primary_url'],
            $data['secondary_label'] !== '' ? $data['secondary_label'] : null,
            $data['secondary_url'] !== '' ? $data['secondary_url'] : null,
            $data['sort_order'],
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $statement = db()->prepare(
            'UPDATE hero_slides
             SET image = ?, eyebrow = ?, title = ?, description = ?, primary_label = ?, primary_url = ?, secondary_label = ?, secondary_url = ?, sort_order = ?
             WHERE id = ?'
        );
        $statement->execute([
            $data['image'],
            $data['eyebrow'] !== '' ? $data['eyebrow'] : null,
            $data['title'],
            $data['description'] !== '' ? $data['description'] : null,
            $data['primary_label'],
            $data['primary_url'],
            $data['secondary_label'] !== '' ? $data['secondary_label'] : null,
            $data['secondary_url'] !== '' ? $data['secondary_url'] : null,
            $data['sort_order'],
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $statement = db()->prepare('DELETE FROM hero_slides WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function nextSortOrder(): int
    {
        $max = (int) db()->query('SELECT COALESCE(MAX(sort_order), 0) FROM hero_slides')->fetchColumn();

        return $max + 1;
    }
}
