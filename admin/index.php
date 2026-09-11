<?php
require_once __DIR__ . '/../app/middleware/auth.php';

header('Location: ' . (current_admin() ? 'dashboard' : 'login'));
exit;
