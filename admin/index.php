<?php
require_once __DIR__ . '/../app/helpers/url.php';
require_once __DIR__ . '/../app/middleware/auth.php';

$adminBase = resolve_project_base('/admin/') . '/admin';

header('Location: ' . $adminBase . (current_admin() ? '/dashboard' : '/login'));
exit;
