<?php
// Root router: Automatically directs requests to the verso web application
if (file_exists(__DIR__ . '/verso/index.php')) {
    header('Location: verso/');
    exit;
} else {
    echo 'Verso application files not found. Please ensure the files from the verso directory are uploaded.';
}
