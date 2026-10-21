<?php
// Session-based auth guard for admin pages.

require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!function_exists('safe_local_redirect')) {
    function safe_local_redirect(?string $target, string $fallback): string
    {
        if (!$target || !str_starts_with($target, '/') || str_starts_with($target, '//')) {
            return $fallback;
        }

        $parts = parse_url($target);

        return isset($parts['scheme']) || isset($parts['host']) ? $fallback : $target;
    }
}

if (!function_exists('current_admin')) {
    function current_admin(): ?array
    {
        return $_SESSION['admin'] ?? null;
    }
}

if (!function_exists('login_admin')) {
    function login_admin(array $user): void
    {
        session_regenerate_id(true);

        $_SESSION['admin'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
            'role' => $user['role'],
            'previous_login_at' => $user['last_login_at'] ?? null,
        ];
    }
}

if (!function_exists('logout_admin')) {
    function logout_admin(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $cookieParams = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
        }

        session_destroy();
    }
}

if (!function_exists('require_admin')) {
    function require_admin(): void
    {
        $admin = current_admin();

        if ($admin && function_exists('db')) {
            $statement = db()->prepare('SELECT is_active FROM admin_users WHERE id = ? LIMIT 1');
            $statement->execute([(int) $admin['id']]);
            $isActive = $statement->fetchColumn();

            if ($isActive === false || (int) $isActive !== 1) {
                logout_admin();
                require_once __DIR__ . '/../helpers/url.php';
                header('Location: ' . resolve_project_base('/admin/') . '/admin/login?access=1');
                exit;
            }
        }

        if (!$admin) {
            require_once __DIR__ . '/../helpers/url.php';

            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? null;
            header('Location: ' . resolve_project_base('/admin/') . '/admin/login');
            exit;
        }
    }
}
