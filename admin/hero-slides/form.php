<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/HeroSlide.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$slidesAdminBase = $projectBase . '/admin/hero-slides/';
$slideUploadDir = __DIR__ . '/../../public/assets/images/hero';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$slide = $id ? HeroSlide::find($id) : null;

if ($id && !$slide) {
    header('Location: ' . $slidesAdminBase . '?missing=1');
    exit;
}

$isEdit = (bool) $slide;
$errors = [];
$values = $slide ?: [
    'image' => null,
    'eyebrow' => '',
    'title' => '',
    'description' => '',
    'primary_label' => '',
    'primary_url' => '',
    'secondary_label' => '',
    'secondary_url' => '',
    'sort_order' => HeroSlide::nextSortOrder(),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $values['eyebrow'] = trim((string) ($_POST['eyebrow'] ?? ''));
    $values['title'] = trim((string) ($_POST['title'] ?? ''));
    $values['description'] = trim((string) ($_POST['description'] ?? ''));
    $values['primary_label'] = trim((string) ($_POST['primary_label'] ?? ''));
    $values['primary_url'] = trim((string) ($_POST['primary_url'] ?? ''));
    $values['secondary_label'] = trim((string) ($_POST['secondary_label'] ?? ''));
    $values['secondary_url'] = trim((string) ($_POST['secondary_url'] ?? ''));
    $values['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));

    if ($values['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if ($values['primary_label'] === '') {
        $errors[] = 'The main button label is required.';
    }

    if ($values['primary_url'] === '') {
        $errors[] = 'The main button link is required.';
    }

    if (($values['secondary_label'] === '') !== ($values['secondary_url'] === '')) {
        $errors[] = 'Fill in both the second button label and link, or leave both blank.';
    }

    $uploadResult = store_uploaded_image($_FILES['image'] ?? [], $slideUploadDir);

    if (!$uploadResult['ok']) {
        $errors[] = $uploadResult['error'];
    } elseif (!$uploadResult['filename'] && !$isEdit) {
        $errors[] = 'Please upload a background image.';
    }

    if (!$errors) {
        $newImage = $uploadResult['filename'] ?? null;

        if ($newImage) {
            $oldImage = $values['image'] ?? null;
            $values['image'] = $newImage;

            if ($isEdit && $oldImage && $oldImage !== $newImage) {
                $oldPath = $slideUploadDir . '/' . $oldImage;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        if ($isEdit) {
            HeroSlide::update($id, $values);
            header('Location: ' . $slidesAdminBase . '?updated=1');
        } else {
            HeroSlide::create($values);
            header('Location: ' . $slidesAdminBase . '?created=1');
        }
        exit;
    }
}

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' Hero Slide - Busitema Admin';
$pageHeading = $isEdit ? 'Edit Hero Slide' : 'Add Hero Slide';
$activeNav = '/hero-slides/';

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
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($values['title']) ?>" required>
            <p class="admin-field__hint">The large heading shown on the slide.</p>
        </div>

        <div class="admin-field">
            <label for="eyebrow">Small label above the title</label>
            <input type="text" id="eyebrow" name="eyebrow" value="<?= htmlspecialchars($values['eyebrow'] ?? '') ?>" placeholder="e.g. Partnerships">
        </div>

        <div class="admin-field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"><?= htmlspecialchars($values['description'] ?? '') ?></textarea>
        </div>

        <div class="admin-field">
            <label for="primary_label">Main button text</label>
            <input type="text" id="primary_label" name="primary_label" value="<?= htmlspecialchars($values['primary_label']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="primary_url">Main button link</label>
            <input type="text" id="primary_url" name="primary_url" value="<?= htmlspecialchars($values['primary_url']) ?>" placeholder="e.g. /study/" required>
        </div>

        <div class="admin-field">
            <label for="secondary_label">Second button text (optional)</label>
            <input type="text" id="secondary_label" name="secondary_label" value="<?= htmlspecialchars($values['secondary_label'] ?? '') ?>">
        </div>

        <div class="admin-field">
            <label for="secondary_url">Second button link (optional)</label>
            <input type="text" id="secondary_url" name="secondary_url" value="<?= htmlspecialchars($values['secondary_url'] ?? '') ?>" placeholder="e.g. /partnerships/">
        </div>

        <div class="admin-field">
            <label for="sort_order">Display order</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="<?= (int) $values['sort_order'] ?>">
        </div>

        <div class="admin-field">
            <label for="image">Background image</label>
            <?php if (!empty($values['image'])): ?>
                <div class="admin-field__current-photo">
                    <img src="<?= htmlspecialchars($projectBase . '/public/assets/images/hero/' . $values['image']) ?>" alt="Current image">
                    <span>Current image &mdash; upload a new one to replace it</span>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
            <p class="admin-field__hint">JPG, PNG or WEBP, up to 2MB. Use a wide photo &mdash; it fills the whole banner.</p>
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add slide' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($slidesAdminBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
