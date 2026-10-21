<?php
// PDO connection, shared across the app via db().

if (!function_exists('db')) {
    function db(): PDO
    {
        static $connection = null;

        if ($connection === null) {
            $config = require __DIR__ . '/config.php';
            $db = $config['db'];

            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";

            $connection = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 5,
            ]);

            $databaseTimezone = $db['timezone'] ?? '+03:00';
            if (preg_match('/^[+-](?:0\d|1[0-4]):[0-5]\d$/', $databaseTimezone)) {
                $connection->exec("SET time_zone = " . $connection->quote($databaseTimezone));
            }
        }

        return $connection;
    }
}
