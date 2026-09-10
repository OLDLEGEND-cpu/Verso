<?php
// Verso Configuration

// Database configuration (Local MySQL PDO)
define('DB_HOST', 'localhost');
define('DB_NAME', 'verso');
define('DB_USER', 'root');
define('DB_PASS', '');
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

// Smart Base URL detection (works both in /verso/ subdirectory and root http://localhost:8000/)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? '') == 443) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    
    // Normalize path to directory of project root
    $basePath = rtrim($scriptDir, '/');
    if (basename($basePath) === 'includes') {
        $basePath = dirname($basePath);
    }
    
    // If request URI starts with /verso, preserve it
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (str_starts_with($requestUri, '/verso')) {
        $basePath = '/verso';
    } elseif ($basePath === '/' || $basePath === '.') {
        $basePath = '';
    }
    
    define('BASE_URL', $basePath);
}
