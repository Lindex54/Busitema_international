<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/models/ActivityLog.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$adminsBase = $projectBase . '/admin/admins/';
$currentAdmin = current_admin();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$user = $id ? User::find($id) : null;

if ($id && !$user) {
    header('Location: ' . $adminsBase . '?missing=1');
    exit;
}

$isEdit = (bool) $user;
$errors = [];
$values = $user ?: [
    'name' => '',
    'username' => '',
    'email' => '',
    'role' => '',
];
$values['password'] = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $values['name'] = trim((string) ($_POST['name'] ?? ''));
    $values['username'] = trim((string) ($_POST['username'] ?? ''));
    $values['email'] = trim((string) ($_POST['email'] ?? ''));
    $values['password'] = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');
    $values['role'] = trim((string) ($_POST['role'] ?? ''));

    if ($values['name'] === '' || mb_strlen($values['name']) > 120) {
        $errors[] = 'Enter a name of no more than 120 characters.';
    }

    if (!preg_match('/^[A-Za-z0-9._-]{3,80}$/', $values['username'])) {
        $errors[] = 'Username must be 3-80 characters using letters, numbers, dots, underscores or hyphens.';
    }

    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($values['email']) > 190) {
        $errors[] = 'Enter a valid email address.';
    }

    if (!$isEdit && $values['password'] === '') {
        $errors[] = 'Password is required for a new administrator.';
    } elseif ($values['password'] === '' && $passwordConfirmation !== '') {
        $errors[] = 'Enter the new password before confirming it.';
    } elseif ($values['password'] !== '' && strlen($values['password']) < 10) {
        $errors[] = 'Password must contain at least 10 characters.';
    } elseif ($values['password'] !== '' && !hash_equals($values['password'], $passwordConfirmation)) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (mb_strlen($values['role']) > 40) {
        $errors[] = 'Role must not exceed 40 characters.';
    }

    if (!$errors) {
        try {
            if ($isEdit) {
                User::update($id, $values);
                $action = 'admin_updated';
            } else {
                User::create($values);
                $action = 'admin_created';
            }

            ActivityLog::record(
                $action,
                'success',
                (int) $currentAdmin['id'],
                $currentAdmin['username'],
                ($isEdit ? 'Updated' : 'Created') . ' administrator: ' . $values['username']
            );

            if ($isEdit && $id === (int) $currentAdmin['id']) {
                $_SESSION['admin']['name'] = $values['name'];
                $_SESSION['admin']['username'] = $values['username'];
                $_SESSION['admin']['role'] = $values['role'] !== '' ? $values['role'] : 'admin';
            }

            header('Location: ' . $adminsBase . ($isEdit ? '?updated=1' : '?created=1'));
            exit;
        } catch (Throwable $exception) {
            $errors[] = database_write_error($exception);
        }
    }
}

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' Administrator - Busitema Admin';
$pageHeading = $isEdit ? 'Edit Administrator' : 'Add Administrator';
$activeNav = '/admins/';

include __DIR__ . '/../includes/header.php';
?>

<?php if ($errors): ?>
    <div class="admin-alert admin-alert--error" role="alert">
        <span aria-hidden="true">&#9888;</span>
        <span><?= htmlspecialchars(implode(' ', $errors)) ?></span>
    </div>
<?php endif; ?>

<div class="admin-form-card">
    <form method="post" action="" novalidate>
        <?= csrf_field() ?>

        <div class="admin-field">
            <label for="name">Name <span class="admin-field__required">*</span></label>
            <input type="text" id="name" name="name" maxlength="120" value="<?= htmlspecialchars($values['name']) ?>" autocomplete="name" required>
        </div>

        <div class="admin-field">
            <label for="username">Username <span class="admin-field__required">*</span></label>
            <input type="text" id="username" name="username" minlength="3" maxlength="80" value="<?= htmlspecialchars($values['username']) ?>" autocomplete="username" required>
            <p class="admin-field__hint">Letters, numbers, dots, underscores and hyphens only.</p>
        </div>

        <div class="admin-field">
            <label for="email">Email address <span class="admin-field__required">*</span></label>
            <input type="email" id="email" name="email" maxlength="190" value="<?= htmlspecialchars($values['email'] ?? '') ?>" autocomplete="email" required>
        </div>

        <div class="admin-field">
            <label for="password">Password<?= $isEdit ? ' (optional)' : ' <span class="admin-field__required">*</span>' ?></label>
            <input type="password" id="password" name="password" minlength="10" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
            <p class="admin-field__hint"><?= $isEdit ? 'Leave blank to keep the current password.' : 'Use at least 10 characters.' ?></p>
        </div>

        <div class="admin-field">
            <label for="password_confirmation">Confirm password<?= $isEdit ? ' (if changing it)' : ' <span class="admin-field__required">*</span>' ?></label>
            <input type="password" id="password_confirmation" name="password_confirmation" minlength="10" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
        </div>

        <div class="admin-field">
            <label for="role">Role (optional)</label>
            <input type="text" id="role" name="role" maxlength="40" value="<?= htmlspecialchars($values['role'] ?? '') ?>" placeholder="admin">
            <p class="admin-field__hint">If left blank, the role is saved as admin.</p>
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add administrator' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminsBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
