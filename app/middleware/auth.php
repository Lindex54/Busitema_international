<?php
// Session-based auth guard for admin pages.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
            'email' => $user['email'],
            'role' => $user['role'],
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
        if (!current_admin()) {
            require_once __DIR__ . '/../helpers/url.php';

            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? null;
            header('Location: ' . resolve_project_base('/admin/') . '/admin/login');
            exit;
        }
    }
}
