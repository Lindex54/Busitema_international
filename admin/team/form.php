<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/ContactPerson.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$teamAdminBase = $projectBase . '/admin/team/';
$teamUploadDir = __DIR__ . '/../../public/assets/images/team';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$person = $id ? ContactPerson::find($id) : null;

if ($id && !$person) {
    header('Location: ' . $teamAdminBase . '?missing=1');
    exit;
}

$isEdit = (bool) $person;
$errors = [];
$values = $person ?: [
    'name' => '',
    'role' => '',
    'bio' => '',
    'email' => '',
    'phone' => '',
    'photo' => null,
    'sort_order' => ContactPerson::nextSortOrder(),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $values['name'] = trim((string) ($_POST['name'] ?? ''));
    $values['role'] = trim((string) ($_POST['role'] ?? ''));
    $values['bio'] = trim((string) ($_POST['bio'] ?? ''));
    $values['email'] = trim((string) ($_POST['email'] ?? ''));
    $values['phone'] = trim((string) ($_POST['phone'] ?? ''));
    $values['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));

    if ($values['name'] === '') {
        $errors[] = 'Name is required.';
    }

    if ($values['role'] === '') {
        $errors[] = 'Role is required.';
    }

    if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    $uploadResult = $errors
        ? ['ok' => true, 'filename' => null]
        : store_uploaded_image($_FILES['photo'] ?? [], $teamUploadDir);

    if (!$uploadResult['ok']) {
        $errors[] = $uploadResult['error'];
    } elseif (!$uploadResult['filename'] && !$isEdit) {
        $errors[] = 'Please upload a photo.';
    }

    if (!$errors) {
        $newPhoto = $uploadResult['filename'] ?? null;
        $oldPhoto = $person['photo'] ?? null;

        if ($newPhoto) {
            $values['photo'] = $newPhoto;
        }

        try {
            if ($isEdit) {
                ContactPerson::update($id, $values);
            } else {
                ContactPerson::create($values);
            }
        } catch (Throwable $exception) {
            if ($newPhoto) {
                remove_managed_file($teamUploadDir, $newPhoto);
                $values['photo'] = $oldPhoto;
            }
            $errors[] = database_write_error($exception);
        }

        if (!$errors) {
            if ($newPhoto && $oldPhoto && $oldPhoto !== $newPhoto) {
                remove_managed_file($teamUploadDir, $oldPhoto);
            }

            header('Location: ' . $teamAdminBase . ($isEdit ? '?updated=1' : '?created=1'));
            exit;
        }
    }
}

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' Contact Person - Busitema Admin';
$pageHeading = $isEdit ? 'Edit Contact Person' : 'Add Contact Person';
$activeNav = '/team/';

include __DIR__ . '/../includes/header.php';
?>

<?php if ($errors): ?>
    <div class="admin-alert admin-alert--error" role="alert">
        <span aria-hidden="true">&#9888;</span>
        <span><?= htmlspecialchars(implode(' ', $errors)) ?></span>
    </div>
<?php endif; ?>

<div class="admin-form-card">
    <form method="post" action="" enctype="multipart/form-data" novalidate>
        <?= csrf_field() ?>

        <div class="admin-field">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($values['name']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="role">Role</label>
            <input type="text" id="role" name="role" value="<?= htmlspecialchars($values['role']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="bio">Short bio</label>
            <textarea id="bio" name="bio" rows="3"><?= htmlspecialchars($values['bio'] ?? '') ?></textarea>
        </div>

        <div class="admin-field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($values['email']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="phone">Phone number</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>">
        </div>

        <div class="admin-field">
            <label for="sort_order">Display order</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="<?= (int) $values['sort_order'] ?>">
        </div>

        <div class="admin-field">
            <label for="photo">Photo</label>
            <?php if (!empty($values['photo'])): ?>
                <div class="admin-field__current-photo">
                    <img src="<?= htmlspecialchars($projectBase . '/assets/images/team/' . $values['photo']) ?>" alt="Current photo">
                    <span>Current photo &mdash; upload a new one to replace it</span>
                </div>
            <?php endif; ?>
            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
            <p class="admin-field__hint">JPG, PNG or WEBP, up to 2MB.</p>
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add contact person' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($teamAdminBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
