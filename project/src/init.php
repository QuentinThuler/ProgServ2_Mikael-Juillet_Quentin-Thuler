<?php
// --- Session ---
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

$isConnected = isset($_SESSION['user_id']);
$isAdmin     = $isConnected && ($_SESSION['role'] ?? '') === 'admin';

// --- Database ---
require_once __DIR__ . '/db/Database.php';

$database = Database::getInstance();
$pdo = $database->getConnection();

// --- Functional & constants ---
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/constants.php';

// --- Security services ---
require_once __DIR__ . '/services/security.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
