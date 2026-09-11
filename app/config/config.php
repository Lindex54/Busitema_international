<?php
// Global application configuration. Loads local overrides from a .env file
// (repo root) so real credentials never need to be committed.

if (!function_exists('env')) {
    function env(string $key, ?string $default = null): ?string
    {
        static $loaded = false;

        if (!$loaded) {
            $envPath = __DIR__ . '/../../.env';

            if (is_file($envPath)) {
                foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                    $line = trim($line);

                    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                        continue;
                    }

                    [$name, $value] = explode('=', $line, 2);
                    $name = trim($name);
                    $value = trim($value);

                    if ($name !== '' && getenv($name) === false) {
                        putenv("$name=$value");
                    }
                }
            }

            $loaded = true;
        }

        $value = getenv($key);

        return $value === false ? $default : $value;
    }
}

return [
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'name' => env('DB_NAME', 'busitema_international'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
    ],
];
