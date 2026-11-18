<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    // 'secure' => true,  // to be activated when switching to HTTPS
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isConnected = isset($_SESSION['user_id']);
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

require_once __DIR__ . '/db/Database.php';

$database = Database::getInstance();
$pdo = $database->getConnection();

require_once __DIR__ . '/services/security.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
