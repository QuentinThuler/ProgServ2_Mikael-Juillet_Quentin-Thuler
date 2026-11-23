<?php

use App\db\Database;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
$translations = require __DIR__ . '/translations/translations.php';

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

$database = Database::getInstance();
$pdo = $database->getConnection();

// --- Security services ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// --- i18n traductions -------------------------------------------------------
const DEFAULT_LANGUAGE = 'fr';

if (isset($_GET['lang']) && is_string($_GET['lang']) && array_key_exists($_GET['lang'], $translations)) {
    setcookie('language', $_GET['lang'], [
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => '/',
        'samesite' => 'Lax',
    ]);
    $_COOKIE['language'] = $_GET['lang'];
} 

// Determining the Active Language
$language = DEFAULT_LANGUAGE;

if (isset($_COOKIE['language']) && array_key_exists($_COOKIE['language'], $translations)) {
    $language = $_COOKIE['language'];
}
