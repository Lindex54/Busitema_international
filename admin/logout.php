<?php
require_once __DIR__ . '/../app/helpers/url.php';
require_once __DIR__ . '/../app/helpers/security.php';
require_once __DIR__ . '/../app/middleware/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method Not Allowed');
}

if (!csrf_verify($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    exit('Invalid request token.');
}

logout_admin();

header('Location: ' . resolve_project_base('/admin/') . '/admin/login');
exit;
