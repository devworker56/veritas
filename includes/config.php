<?php
// AidVeritas Web Portal Configuration
session_start();

// Environment Configuration
define('SITE_NAME', 'Veritas');
define('SITE_URL', 'https://palegoldenrod-mandrill-785025.hostingersite.com/');
define('DEFAULT_LANGUAGE', 'fr');
define('SUPPORTED_LANGUAGES', ['fr', 'en']);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'u834808878_db_veritas');
define('DB_USER', 'u834808878_eritasAdmin');
define('DB_PASS', 'Ossouka@1968');
define('DB_CHARSET', 'utf8mb4');

// Pusher Configuration
define('PUSHER_APP_ID', 'your_pusher_app_id');
define('PUSHER_KEY', 'your_pusher_key');
define('PUSHER_SECRET', 'your_pusher_secret');
define('PUSHER_CLUSTER', 'mt1');

// Security Configuration
define('ENCRYPTION_KEY', 'your_encryption_key_here');
define('HASH_ALGO', 'sha256');

// File Paths
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('RECEIPTS_PATH', __DIR__ . '/../receipts/');

// CRA Receipt Settings
define('CRA_RECEIPT_PREFIX', 'AVR');
define('CRA_RECEIPT_YEAR', date('Y'));

// Error Reporting
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Auto-load classes
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/../classes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
// Set language
session_start(); // Make sure this is at the top

$current_language = DEFAULT_LANGUAGE;

// Check for language change request
if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGUAGES)) {
    $current_language = $_GET['lang'];
    $_SESSION['language'] = $current_language;
} 
// Check for existing session language
elseif (isset($_SESSION['language']) && in_array($_SESSION['language'], SUPPORTED_LANGUAGES)) {
    $current_language = $_SESSION['language'];
}
// Fallback to default
else {
    $current_language = DEFAULT_LANGUAGE;
    $_SESSION['language'] = $current_language;
}

// Language strings
$lang = [];
$lang_file = __DIR__ . "/../languages/{$current_language}.php";

if (file_exists($lang_file)) {
    require_once $lang_file;
} else {
    // Fallback to default language file
    require_once __DIR__ . "/../languages/" . DEFAULT_LANGUAGE . ".php";
}
/*
// Set language
$current_language = DEFAULT_LANGUAGE;
if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGUAGES)) {
    $current_language = $_GET['lang'];
    $_SESSION['language'] = $current_language;
} elseif (isset($_SESSION['language'])) {
    $current_language = $_SESSION['language'];
}

// Language strings
$lang = [];
require_once __DIR__ . "/../languages/{$current_language}.php";
?>
*/