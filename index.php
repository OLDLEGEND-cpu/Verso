<?php
// Root router: Automatically routes root domain requests to the verso web application
if (file_exists(__DIR__ . '/verso/index.php')) {
    require_once __DIR__ . '/verso/index.php';
} else {
    require_once __DIR__ . '/index.php';
}
