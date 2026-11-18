<?php

// --- Session ---------------------------------------------------------------
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

function requireLogin(): void {
    global $isConnected;

    if (!$isConnected) {
        header('Location: /login.php');
        exit;
    }
}

function requireAdmin(): void {
    global $isAdmin;

    requireLogin();
    if (!$isAdmin) {
        http_response_code(403);
        exit('Accès refusé.');
    }
}

require_once __DIR__ . '/db/Database.php';

$database = Database::getInstance();
$pdo = $database->getConnection();

require_once __DIR__ . '/services/security.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
