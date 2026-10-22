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

const DB_HOST     = 'mariadb';
const DB_PORT     = 3306;
const DB_NAME     = 'gatherly';
const DB_USER     = 'username';
const DB_PASSWORD = 'password';

$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
