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
    'seo_default_description' => 'Default meta description',
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
    $seoImageUpload = store_uploaded_image($_FILES['seo_default_image'] ?? [], $seoUploadDir);

    if (!$seoImageUpload['ok']) {
        $errors[] = $seoImageUpload['error'];
    }

    if (!$errors) {
        if (!empty($seoImageUpload['filename'])) {
            $oldImage = $values['seo_default_image'] ?? '';
            $values['seo_default_image'] = 'seo/' . $seoImageUpload['filename'];

            if ($oldImage && $oldImage !== $values['seo_default_image']) {
                $oldPath = __DIR__ . '/../../public/assets/images/' . $oldImage;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        Setting::setMany($values);
        header('Location: ' . $settingsAdminBase . '?updated=1');
        exit;
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

        <h2 class="admin-form-card__subheading">Search Engine Optimization (SEO)</h2>
        <p class="admin-field__hint">
            These are the site-wide defaults used when a page doesn't have its own title, description or image.
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
            <label for="seo_default_image">Default social image</label>
            <?php if (!empty($values['seo_default_image'])): ?>
                <div class="admin-field__current-photo">
                    <img src="<?= htmlspecialchars($projectBase . '/public/assets/images/' . $values['seo_default_image']) ?>" alt="Current default social image">
                    <span>Current image &mdash; upload a new one to replace it</span>
                </div>
            <?php endif; ?>
            <input type="file" id="seo_default_image" name="seo_default_image" accept="image/jpeg,image/png,image/webp">
            <p class="admin-field__hint">
                Optional. Used for social sharing (Facebook, X/Twitter, WhatsApp) on pages that don't have their own image. JPG, PNG or WEBP, up to 2MB.
            </p>
        </div>

        <div class="admin-field">
            <label class="admin-inline-checkbox">
                <input type="checkbox" name="seo_site_indexable" value="1" <?= ($values['seo_site_indexable'] ?? '1') !== '0' ? 'checked' : '' ?>>
                Allow search engines to index this website
            </label>
            <p class="admin-field__hint">
                Turn this off while the site is still in development or not ready to be found publicly &mdash; it hides every page from Google and other search engines regardless of any per-page setting.
            </p>
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit">Save settings</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
