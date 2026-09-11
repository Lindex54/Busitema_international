<?php
require_once __DIR__ . '/../app/middleware/auth.php';

logout_admin();

header('Location: login');
exit;
