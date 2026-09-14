<?php
class Opportunity
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM opportunities ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT * FROM opportunities WHERE id = ? LIMIT 1');
        $statement->execute([$id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $statement = db()->prepare('SELECT * FROM opportunities WHERE slug = ? LIMIT 1');
        $statement->execute([$slug]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $statement = db()->prepare(
            'INSERT INTO opportunities (title, slug, category, status, deadline, summary, overview, eligibility, how_to_apply, sort_order, seo_title, seo_description, seo_image, is_indexed)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $data['title'],
            $data['slug'],
            $data['category'],
            $data['status'],
            $data['deadline'],
            $data['summary'],
            $data['overview'],
            $data['eligibility'],
            $data['how_to_apply'],
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
            'UPDATE opportunities
             SET title = ?, slug = ?, category = ?, status = ?, deadline = ?, summary = ?, overview = ?, eligibility = ?, how_to_apply = ?, sort_order = ?,
                 seo_title = ?, seo_description = ?, seo_image = ?, is_indexed = ?
             WHERE id = ?'
        );
        $statement->execute([
            $data['title'],
            $data['slug'],
            $data['category'],
            $data['status'],
            $data['deadline'],
            $data['summary'],
            $data['overview'],
            $data['eligibility'],
            $data['how_to_apply'],
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
        $statement = db()->prepare('DELETE FROM opportunities WHERE id = ?');
        $statement->execute([$id]);
    }

    public static function nextSortOrder(): int
    {
        $max = (int) db()->query('SELECT COALESCE(MAX(sort_order), 0) FROM opportunities')->fetchColumn();

        return $max + 1;
    }

    public static function distinctCategories(): array
    {
        return array_column(db()->query('SELECT DISTINCT category FROM opportunities ORDER BY category ASC')->fetchAll(), 'category');
    }

    // Splits a newline-separated field (eligibility, how_to_apply) into a
    // clean list, dropping blank lines.
    public static function linesToList(?string $value): array
    {
        if (!$value) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $value);
        $lines = array_map('trim', $lines);

        return array_values(array_filter($lines, static fn ($line) => $line !== ''));
    }

    // Builds a unique slug from a title, appending -2, -3, etc. if needed.
    // Pass $excludeId when editing so a row doesn't collide with itself.
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
            $statement = db()->prepare('SELECT COUNT(*) FROM opportunities WHERE slug = ? AND id != ?');
            $statement->execute([$slug, $excludeId]);
        } else {
            $statement = db()->prepare('SELECT COUNT(*) FROM opportunities WHERE slug = ?');
            $statement->execute([$slug]);
        }

        return (int) $statement->fetchColumn() > 0;
    }
}
