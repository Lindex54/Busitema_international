<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/security.php';
require_once __DIR__ . '/../app/helpers/url.php';
require_once __DIR__ . '/../app/middleware/auth.php';
require_once __DIR__ . '/../app/models/ActivityLog.php';
require_once __DIR__ . '/../app/models/User.php';

$projectBase = resolve_project_base('/admin/');
$adminBase = $projectBase . '/admin';

if (current_admin()) {
    header('Location: ' . $adminBase . '/dashboard');
    exit;
}

$pageTitle = "Admin Login - Busitema University";
$errorMessage = isset($_GET['access']) ? 'Unable to log in. Check your username and password and try again.' : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));

    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        ActivityLog::record('login_failed', 'failure', null, $username, 'Invalid or expired request token');
        $errorMessage = 'Your session expired. Please try again.';
    } elseif (login_attempts_remaining() <= 0) {
        ActivityLog::record('login_failed', 'failure', null, $username, 'Login temporarily rate limited');
        $errorMessage = 'Too many failed attempts. Please wait a minute and try again.';
    } else {
        $password = (string) ($_POST['password'] ?? '');
        $user = $username !== '' ? User::findByUsername($username) : null;

        if ($user && password_verify($password, $user['password_hash']) && (int) ($user['is_active'] ?? 1) === 1) {
            clear_login_throttle();
            login_admin($user);
            User::touchLastLogin((int) $user['id']);
            ActivityLog::record('login_success', 'success', (int) $user['id'], $user['username'], 'Administrator signed in');

            $redirectTo = safe_local_redirect($_SESSION['redirect_after_login'] ?? null, $adminBase . '/dashboard');
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirectTo);
            exit;
        }

        register_failed_login();
        $failureDescription = $user && (int) ($user['is_active'] ?? 1) !== 1
            ? 'Inactive administrator attempted sign-in'
            : 'Invalid username or password';
        ActivityLog::record('login_failed', 'failure', $user ? (int) $user['id'] : null, $username, $failureDescription);
        $errorMessage = 'Unable to log in. Check your username and password and try again.';
    }
}

$adminAssetBase = $projectBase . '/assets';
$adminAssetVersion = static function (string $relativePath): string {
    $assetPath = __DIR__ . '/../public/assets/' . ltrim($relativePath, '/');

    return is_file($assetPath) ? '?v=' . filemtime($assetPath) : '';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" type="image/png" sizes="512x512" href="<?= htmlspecialchars($adminAssetBase) ?>/images/icons/favicon.png<?= $adminAssetVersion('images/icons/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= htmlspecialchars($adminAssetBase) ?>/images/icons/favicon.png<?= $adminAssetVersion('images/icons/favicon.png') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($adminAssetBase) ?>/css/index.css<?= $adminAssetVersion('css/index.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($adminAssetBase) ?>/css/admin.css<?= $adminAssetVersion('css/admin.css') ?>">
</head>
<body class="app">

<div class="admin-auth">
    <section class="admin-auth__brand">
        <div class="admin-auth__logo">
            <a class="admin-logo-badge" href="<?= htmlspecialchars($projectBase . '/') ?>" aria-label="Go to the website home page">
                <img src="<?= htmlspecialchars($adminAssetBase) ?>/images/icons/Busitema_University_Logo.png" alt="Busitema University" width="828" height="301">
            </a>
            <span>Internationalization &amp; Partnerships</span>
        </div>

        <div class="admin-auth__copy">
            <p class="eyebrow">Admin Panel</p>
            <h1>Manage your site content in one place</h1>
            <p>
                Sign in to add and update partners, news, events, scholarships and contact details &mdash;
                no coding required.
            </p>
        </div>

        <p class="admin-auth__footnote">&copy; <?= date('Y') ?> Busitema University. Internal use only.</p>
    </section>

    <section class="admin-auth__panel">
        <div class="admin-auth__form-wrap">
            <h2>Welcome back</h2>
            <p>Sign in with your admin account to continue.</p>

            <?php if ($errorMessage): ?>
                <div class="admin-alert admin-alert--error" role="alert">
                    <span aria-hidden="true">&#9888;</span>
                    <span><?= htmlspecialchars($errorMessage) ?></span>
                </div>
            <?php endif; ?>

            <form method="post" action="" novalidate>
                <?= csrf_field() ?>

                <div class="admin-field">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        autocomplete="username"
                        minlength="3"
                        maxlength="80"
                        required
                        autofocus
                    >
                </div>

                <div class="admin-field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button class="admin-auth__submit" type="submit">Sign in</button>
            </form>

            <a class="admin-auth__back" href="<?= htmlspecialchars($projectBase . '/') ?>">
                <span aria-hidden="true">&larr;</span> Back to the website
            </a>
        </div>
    </section>
</div>

</body>
</html>
