<?php
class Setting
{
    public static function all(): array
    {
        $rows = db()->query('SELECT `key`, `value` FROM settings')->fetchAll();

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }

        return $settings;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $statement = db()->prepare('SELECT `value` FROM settings WHERE `key` = ? LIMIT 1');
        $statement->execute([$key]);
        $value = $statement->fetchColumn();

        return $value === false ? $default : $value;
    }

    public static function set(string $key, ?string $value): void
    {
        $statement = db()->prepare(
            'INSERT INTO settings (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
        );
        $statement->execute([$key, $value]);
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::set($key, $value);
        }
    }
}
