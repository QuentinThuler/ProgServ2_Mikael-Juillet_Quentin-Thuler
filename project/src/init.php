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

// --- Base de données -------------------------------------------------------
$dbHost = getenv('DB_HOST') ?: 'mariadb';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'gatherly';
$dbUser = getenv('DB_USER') ?: 'username';
$dbPass = getenv('DB_PASSWORD') ?: 'password';

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Connexion DB impossible : ' . $e->getMessage());
    http_response_code(500);
    exit('Service temporairement indisponible.');
}

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/constants.php';
