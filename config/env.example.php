<?php
/**
 * Environment Configuration Example
 * Copy this file to .env and update values
 * Not required for basic setup, but recommended for production
 */

// Database Configuration
define('APP_ENV', 'development'); // development or production
define('APP_DEBUG', true); // Set to false in production

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'bondly');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);

// Application Settings
define('APP_NAME', 'Bondly');
define('APP_URL', 'http://localhost/bondly/');
define('APP_TIMEZONE', 'UTC');

// Session Settings
define('SESSION_LIFETIME', 3600); // 1 hour in seconds
define('SESSION_PATH', '/');
define('SESSION_DOMAIN', '');
define('SESSION_SECURE', false); // Set to true if using HTTPS
define('SESSION_HTTPONLY', true);

// Upload Settings
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_DIRECTORY', './uploads/');

// Email Settings (for future notifications)
define('MAIL_HOST', 'smtp.mailtrap.io');
define('MAIL_PORT', 2525);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');
define('MAIL_FROM', 'noreply@bondly.local');

// Pagination
define('POSTS_PER_PAGE', 20);
define('USERS_PER_PAGE', 10);

// Security
define('DEMO_MODE', false); // Prevent account deletion in demo mode
define('PASSWORD_REQUIREMENT', 6); // Minimum password length

?>
