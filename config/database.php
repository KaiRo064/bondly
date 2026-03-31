<?php
/**
 * Database Configuration File
 * Bondly Social Network Application
 * Using PDO with Prepared Statements
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'bondly');
define('DB_USER', 'root');
define('DB_PASS', '');

// PDO Instance
$pdo = null;

try {
    // Create PDO connection
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

// Application base directory
define('BASE_URL', '/bondly/public/');
define('UPLOADS_DIR', __DIR__ . '/../public/uploads/');
define('UPLOADS_URL', BASE_URL . 'uploads/');

// Session configuration
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
session_start();

/**
 * Helper function to ensure user is logged in
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'index.php?page=login');
        exit;
    }
}

/**
 * Helper function to check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Helper function to get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Helper function to sanitize output
 */
function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Helper function to validate inputs
 */
function validateInput($data) {
    return trim(stripslashes($data));
}

/**
 * Helper function to format date
 */
function formatDate($date) {
    return date('M d, Y H:i', strtotime($date));
}

/**
 * Helper function to calculate time ago
 */
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $current_time = time();
    $time_difference = $current_time - $time;
    
    if ($time_difference < 60) {
        return $time_difference . ' seconds ago';
    } else if ($time_difference < 3600) {
        return round($time_difference / 60) . ' minutes ago';
    } else if ($time_difference < 86400) {
        return round($time_difference / 3600) . ' hours ago';
    } else if ($time_difference < 604800) {
        return round($time_difference / 86400) . ' days ago';
    } else {
        return date('M d, Y', $time);
    }
}
?>
