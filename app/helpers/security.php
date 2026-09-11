<?php
// Security helper functions: CSRF protection and login rate limiting.

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify(?string $token): bool
    {
        return is_string($token)
            && !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('login_attempts_remaining')) {
    function login_attempts_remaining(int $maxAttempts = 5, int $lockoutSeconds = 60): int
    {
        $state = $_SESSION['login_throttle'] ?? null;

        if (!$state) {
            return $maxAttempts;
        }

        if (time() - $state['first_attempt_at'] > $lockoutSeconds) {
            unset($_SESSION['login_throttle']);
            return $maxAttempts;
        }

        return max(0, $maxAttempts - $state['count']);
    }
}

if (!function_exists('register_failed_login')) {
    function register_failed_login(): void
    {
        $state = $_SESSION['login_throttle'] ?? ['count' => 0, 'first_attempt_at' => time()];
        $state['count']++;
        $_SESSION['login_throttle'] = $state;
    }
}

if (!function_exists('clear_login_throttle')) {
    function clear_login_throttle(): void
    {
        unset($_SESSION['login_throttle']);
    }
}
