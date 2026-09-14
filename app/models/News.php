<?php
class News
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM news ORDER BY published_at DESC, sort_order ASC, id DESC')->fetchAll();
    }

    public static function latest(int $limit = 3): array
    {
        $statement = db()->prepare('SELECT * FROM news ORDER BY published_at DESC, sort_order ASC, id DESC LIMIT ?');
        $statement->bindValue(1, $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $statement = db()->prepare('SELECT * FROM news WHERE slug = ? LIMIT 1');
        $statement->execute([$slug]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO news (title, slug, type, event_date, location, summary, body, image, published_at, is_featured, sort_order, seo_title, seo_description, seo_image, is_indexed)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $data['title'],
            $data['slug'],
            $data['type'],
            $data['event_date'] !== '' ? $data['event_date'] : null,
            $data['location'] !== '' ? $data['location'] : null,
            $data['summary'],
            $data['body'],
            $data['image'],
            $data['published_at'],
            $data['is_featured'] ? 1 : 0,
            $data['sort_order'],
            $data['seo_title'] ?: null,
            $data['seo_description'] ?: null,
            $data['seo_image'] ?: null,
            !empty($data['is_indexed']) ? 1 : 0,
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $statement = db()->prepare(
            'UPDATE news
             SET title = ?, slug = ?, type = ?, event_date = ?, location = ?, summary = ?, body = ?, image = ?, published_at = ?, is_featured = ?, sort_order = ?,
                 seo_title = ?, seo_description = ?, seo_image = ?, is_indexed = ?
             WHERE id = ?'
        );
        $statement->execute([
            $data['title'],
            $data['slug'],
            $data['type'],
            $data['event_date'] !== '' ? $data['event_date'] : null,
            $data['location'] !== '' ? $data['location'] : null,
            $data['summary'],
            $data['body'],
            $data['image'],
            $data['published_at'],
            $data['is_featured'] ? 1 : 0,
            $data['sort_order'],
            $data['seo_title'] ?: null,
            $data['seo_description'] ?: null,
            $data['seo_image'] ?: null,
            !empty($data['is_indexed']) ? 1 : 0,
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $statement = db()->prepare('DELETE FROM news WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function nextSortOrder(): int
    {
        $max = (int) db()->query('SELECT COALESCE(MAX(sort_order), 0) FROM news')->fetchColumn();

        return $max + 1;
    }

    // Builds a unique slug from a title, appending -2, -3, etc. if needed.
    // Pass $excludeId when editing so the row itself doesn't collide with its own slug.
    public static function uniqueSlug(string $title, ?int $excludeId = null): string
    {
        require_once __DIR__ . '/../helpers/functions.php';

        $base = slugify($title);
        $slug = $base;
        $suffix = 2;

        while (self::slugTaken($slug, $excludeId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private static function slugTaken(string $slug, ?int $excludeId): bool
    {
        if ($excludeId) {
            $statement = db()->prepare('SELECT COUNT(*) FROM news WHERE slug = ? AND id != ?');
            $statement->execute([$slug, $excludeId]);
        } else {
            $statement = db()->prepare('SELECT COUNT(*) FROM news WHERE slug = ?');
            $statement->execute([$slug]);
        }

        return (int) $statement->fetchColumn() > 0;
    }
}
