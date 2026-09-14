<?php
class Partner
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM partners ORDER BY region ASC, sort_order ASC, id ASC')->fetchAll();
    }

    public static function allGroupedByRegion(): array
    {
        $grouped = [];

        foreach (self::all() as $partner) {
            $grouped[$partner['region']][] = $partner;
        }

        return $grouped;
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM partners WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $statement = db()->prepare('SELECT * FROM partners WHERE slug = ? LIMIT 1');
        $statement->execute([$slug]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO partners (name, slug, region, country, about, logo, sort_order, seo_title, seo_description, seo_image, is_indexed)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $data['name'],
            $data['slug'],
            $data['region'],
            $data['country'],
            $data['about'],
            $data['logo'],
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
            'UPDATE partners
             SET name = ?, slug = ?, region = ?, country = ?, about = ?, logo = ?, sort_order = ?,
                 seo_title = ?, seo_description = ?, seo_image = ?, is_indexed = ?
             WHERE id = ?'
        );
        $statement->execute([
            $data['name'],
            $data['slug'],
            $data['region'],
            $data['country'],
            $data['about'],
            $data['logo'],
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
        $statement = db()->prepare('DELETE FROM partners WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function nextSortOrder(): int
    {
        $max = (int) db()->query('SELECT COALESCE(MAX(sort_order), 0) FROM partners')->fetchColumn();

        return $max + 1;
    }

    public static function distinctRegions(): array
    {
        return array_column(db()->query('SELECT DISTINCT region FROM partners ORDER BY region ASC')->fetchAll(), 'region');
    }

    // Builds a unique slug from an institution name, appending -2, -3, etc.
    // if needed. Pass $excludeId when editing so a row doesn't collide with itself.
    public static function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        require_once __DIR__ . '/../helpers/functions.php';

        $base = slugify($name);
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
            $statement = db()->prepare('SELECT COUNT(*) FROM partners WHERE slug = ? AND id != ?');
            $statement->execute([$slug, $excludeId]);
        } else {
            $statement = db()->prepare('SELECT COUNT(*) FROM partners WHERE slug = ?');
            $statement->execute([$slug]);
        }

        return (int) $statement->fetchColumn() > 0;
    }
}
