<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/security.php';
require_once __DIR__ . '/../app/helpers/url.php';
require_once __DIR__ . '/../app/middleware/auth.php';
require_once __DIR__ . '/../app/models/User.php';

$projectBase = resolve_project_base('/admin/');
$adminBase = $projectBase . '/admin';

if (current_admin()) {
    header('Location: ' . $adminBase . '/dashboard');
    exit;
}

$pageTitle = "Admin Login - Busitema University";
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your session expired. Please try again.';
    } elseif (login_attempts_remaining() <= 0) {
        $errorMessage = 'Too many failed attempts. Please wait a minute and try again.';
    } else {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = $email !== '' ? User::findByEmail($email) : null;

        if ($user && password_verify($password, $user['password_hash'])) {
            clear_login_throttle();
            login_admin($user);
            User::touchLastLogin((int) $user['id']);

            $redirectTo = safe_local_redirect($_SESSION['redirect_after_login'] ?? null, $adminBase . '/dashboard');
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirectTo);
            exit;
        }

        register_failed_login();
        $errorMessage = 'Invalid email or password.';
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
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@busitema.ac.ug"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        autocomplete="username"
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
