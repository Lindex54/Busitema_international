<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/Setting.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$settingsAdminBase = $projectBase . '/admin/settings/';

$fields = [
    'office_address_line1' => 'Address line 1',
    'office_address_line2' => 'Address line 2',
    'office_phone' => 'Phone number',
    'office_email' => 'Email address',
    'office_hours' => 'Office hours',
];

$seoFields = [
    'seo_site_name' => 'Site name',
    'seo_default_description' => 'Default description',
];

$errors = [];
$values = Setting::all();
$seoUploadDir = __DIR__ . '/../../public/assets/images/seo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    foreach (array_keys($fields) as $key) {
        $values[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    foreach (array_keys($seoFields) as $key) {
        $values[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    $values['seo_site_indexable'] = isset($_POST['seo_site_indexable']) ? '1' : '0';

    if ($values['office_email'] !== '' && !filter_var($values['office_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // Default social image is optional - only validated/uploaded if a file was chosen.
    $seoImageUpload = $errors
        ? ['ok' => true, 'filename' => null]
        : store_uploaded_image($_FILES['seo_default_image'] ?? [], $seoUploadDir);

    if (!$seoImageUpload['ok']) {
        $errors[] = $seoImageUpload['error'];
    }

    if (!$errors) {
        $newImage = $seoImageUpload['filename'] ?? null;
        $oldImage = $values['seo_default_image'] ?? '';

        if ($newImage) {
            $values['seo_default_image'] = 'seo/' . $newImage;
        }

        try {
            Setting::setMany($values);
        } catch (Throwable $exception) {
            if ($newImage) {
                remove_managed_file($seoUploadDir, $newImage);
                $values['seo_default_image'] = $oldImage;
            }
            $errors[] = database_write_error($exception);
        }

        if (!$errors) {
            if ($newImage && $oldImage && $oldImage !== $values['seo_default_image']) {
                remove_managed_file(__DIR__ . '/../../public/assets/images', $oldImage);
            }

            header('Location: ' . $settingsAdminBase . '?updated=1');
            exit;
        }
    }
}

$pageTitle = "Settings - Busitema Admin";
$pageHeading = "Settings";
$activeNav = '/settings/';

include __DIR__ . '/../includes/header.php';
?>

<p class="admin-welcome">These details appear in the site footer and on the Contact page.</p>

<?php if ($errors): ?>
    <div class="admin-alert admin-alert--error" role="alert">
        <span aria-hidden="true">&#9888;</span>
        <span><?= htmlspecialchars(implode(' ', $errors)) ?></span>
    </div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Settings saved.</span></div>
<?php endif; ?>

<div class="admin-form-card">
    <form method="post" action="" enctype="multipart/form-data" novalidate>
        <?= csrf_field() ?>

        <?php foreach ($fields as $key => $label): ?>
            <div class="admin-field">
                <label for="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></label>
                <input
                    type="text"
                    id="<?= htmlspecialchars($key) ?>"
                    name="<?= htmlspecialchars($key) ?>"
                    value="<?= htmlspecialchars($values[$key] ?? '') ?>"
                >
            </div>
        <?php endforeach; ?>

        <h2 class="admin-form-card__subheading">Search results &amp; sharing</h2>
        <p class="admin-field__hint">
            These are used as the fallback whenever a page doesn't have its own title, description or picture.
        </p>

        <?php foreach ($seoFields as $key => $label): ?>
            <div class="admin-field">
                <label for="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></label>
                <?php if ($key === 'seo_default_description'): ?>
                    <textarea id="<?= htmlspecialchars($key) ?>" name="<?= htmlspecialchars($key) ?>" rows="2"><?= htmlspecialchars($values[$key] ?? '') ?></textarea>
                <?php else: ?>
                    <input
                        type="text"
                        id="<?= htmlspecialchars($key) ?>"
                        name="<?= htmlspecialchars($key) ?>"
                        value="<?= htmlspecialchars($values[$key] ?? '') ?>"
                    >
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="admin-field">
            <label for="seo_default_image">Default picture for sharing</label>
            <?php if (!empty($values['seo_default_image'])): ?>
                <div class="admin-field__current-photo">
                    <img src="<?= htmlspecialchars($projectBase . '/assets/images/' . $values['seo_default_image']) ?>" alt="Current default picture">
                    <span>Current picture &mdash; upload a new one to replace it</span>
                </div>
            <?php endif; ?>
            <input type="file" id="seo_default_image" name="seo_default_image" accept="image/jpeg,image/png,image/webp">
            <p class="admin-field__hint">
                Optional. Shown when a page without its own picture is shared on Facebook, X or WhatsApp. JPG, PNG or WEBP, up to 2MB.
            </p>
        </div>

        <div class="admin-field">
            <label class="admin-inline-checkbox">
                <input type="checkbox" name="seo_site_indexable" value="1" <?= ($values['seo_site_indexable'] ?? '1') !== '0' ? 'checked' : '' ?>>
                Show this website on Google and other search engines
            </label>
            <p class="admin-field__hint">
                Turn this off while the site is still being built or isn't ready to be found publicly &mdash; it hides every page from search engines, regardless of any individual page's own setting.
            </p>
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit">Save settings</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
