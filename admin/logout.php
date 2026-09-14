<?php
require_once __DIR__ . '/../app/helpers/url.php';
require_once __DIR__ . '/../app/middleware/auth.php';

logout_admin();

header('Location: ' . resolve_project_base('/admin/') . '/admin/login');
exit;
