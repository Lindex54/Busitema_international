<?php
class ActivityLog
{
    public static function record(
        string $action,
        string $status = 'success',
        ?int $adminUserId = null,
        ?string $actorUsername = null,
        ?string $description = null
    ): bool {
        try {
            $statement = db()->prepare(
                'INSERT INTO admin_activity_logs
                    (admin_user_id, actor_username, action, status, description, ip_address, user_agent)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );

            return $statement->execute([
                $adminUserId,
                self::limit($actorUsername, 80),
                self::limit($action, 80),
                $status === 'failure' ? 'failure' : 'success',
                self::limit($description, 255),
                self::limit($_SERVER['REMOTE_ADDR'] ?? null, 45),
                self::limit($_SERVER['HTTP_USER_AGENT'] ?? null, 500),
            ]);
        } catch (Throwable $exception) {
            // Logging must never stop login or another admin operation.
            error_log('Could not write admin activity log: ' . $exception->getMessage());
            return false;
        }
    }

    public static function latest(int $limit = 200, ?string $category = null): array
    {
        $limit = max(1, min($limit, 500));
        return self::paginate(1, $limit, $category)['items'];
    }

    public static function paginate(int $page = 1, int $perPage = 25, ?string $category = null): array
    {
        $perPage = max(10, min($perPage, 100));
        $where = self::categoryWhere($category);
        $total = (int) db()->query("SELECT COUNT(*) FROM admin_activity_logs $where")->fetchColumn();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        $statement = db()->prepare(
            "SELECT * FROM admin_activity_logs $where
             ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?"
        );
        $statement->bindValue(1, $perPage, PDO::PARAM_INT);
        $statement->bindValue(2, $offset, PDO::PARAM_INT);
        $statement->execute();

        return [
            'items' => $statement->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
        ];
    }

    public static function dashboardSummary(): array
    {
        $row = db()->query(
            "SELECT
                SUM(action = 'login_success' AND created_at >= NOW() - INTERVAL 1 DAY) AS successful_logins,
                SUM(action = 'login_failed' AND created_at >= NOW() - INTERVAL 1 DAY) AS failed_logins
             FROM admin_activity_logs"
        )->fetch();

        return [
            'successful_logins' => (int) ($row['successful_logins'] ?? 0),
            'failed_logins' => (int) ($row['failed_logins'] ?? 0),
        ];
    }

    private static function categoryWhere(?string $category): string
    {
        $where = '';

        if ($category === 'login') {
            $where = "WHERE action IN ('login_success', 'login_failed', 'logout')";
        } elseif ($category === 'admins') {
            $where = "WHERE action IN ('admin_created', 'admin_updated', 'admin_deleted', 'admin_activated', 'admin_deactivated')";
        }

        return $where;
    }

    private static function limit(?string $value, int $length): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : mb_substr($value, 0, $length);
    }
}
