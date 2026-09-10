<?php
// Verso Configuration & System Settings

// Polyfill for PHP < 8.0 compatibility
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return (string)$needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

// Database configuration (Local MySQL / XAMPP / InfinityFree)
$dbHost = getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost';
$dbName = getenv('DB_NAME') ? getenv('DB_NAME') : 'verso';
$dbUser = getenv('DB_USER') ? getenv('DB_USER') : 'root';
$dbPass = getenv('DB_PASS') ? getenv('DB_PASS') : '';

define('DB_HOST', $dbHost);
define('DB_NAME', $dbName);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_CHARSET', 'utf8mb4');

// Supabase Configuration
define('SUPABASE_URL', 'https://gzoukcvejprmvtwiojml.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imd6b3VrY3ZlanBybXZ0d2lvam1sIiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODg5NjI0NjcsImV4cCI6MjEwNDUzODQ2N30.Zr9Fxl8YdaRVPUWCLImNLQCF5YwESasMMD2NEyMZl5c');

// SQLite fallback file path (ensures instant out-of-the-box operation)
define('SQLITE_DB_PATH', __DIR__ . '/../data/verso.sqlite');

// Site-wide metadata
define('SITE_NAME', 'Verso');
define('SITE_TAGLINE', 'Where considered creative work finds its audience.');
define('SITE_EMAIL', 'hello@verso.studio');
define('SITE_PHONE', '+1 (415) 555-0142');
define('SITE_ADDRESS', '148 Mercer Street, New York, NY 10012');

// Smart Base URL detection
if (!defined('BASE_URL')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $basePath = rtrim($scriptDir, '/');
    if (basename($basePath) === 'includes') {
        $basePath = dirname($basePath);
    }
    
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (substr($requestUri, 0, 6) === '/verso') {
        $basePath = '/verso';
    } elseif ($basePath === '/' || $basePath === '.') {
        $basePath = '';
    }
    
    define('BASE_URL', $basePath);
}
