<?php
// Diagnostics & Hosting Environment Inspector
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$phpVersion = phpversion();
$isPhp8 = version_compare($phpVersion, '8.0.0', '>=');
$extensions = [
    'pdo'        => extension_loaded('pdo'),
    'pdo_mysql'  => extension_loaded('pdo_mysql'),
    'pdo_sqlite' => extension_loaded('pdo_sqlite'),
    'curl'       => extension_loaded('curl'),
    'mbstring'   => extension_loaded('mbstring'),
    'session'    => extension_loaded('session'),
];

$activeDriver = VersoDB::getActiveDriver();
$projects = fetch_projects(null, 'All', 'newest');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verso System Diagnostics</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0F172A; color: #E2E8F0; padding: 2rem; }
  .card { max-width: 700px; margin: 0 auto; background: #1E293B; border-radius: 12px; padding: 2rem; border: 1px solid #334155; }
  h1 { font-size: 1.6rem; margin-top: 0; color: #38BDF8; }
  .item { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #334155; font-size: 0.95rem; }
  .status { font-weight: bold; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem; }
  .ok { background: #14532D; color: #4ADE80; }
  .warn { background: #713F12; color: #FACC15; }
  .btn { display: inline-block; background: #38BDF8; color: #0F172A; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 1.5rem; }
</style>
</head>
<body>
<div class="card">
  <h1>Verso Server & Hosting Diagnostics</h1>
  <div class="item">
    <span>PHP Version</span>
    <span class="status <?= $isPhp8 ? 'ok' : 'warn' ?>"><?= $phpVersion ?> (<?= $isPhp8 ? 'PHP 8+' : 'Legacy PHP 7' ?>)</span>
  </div>
  <?php foreach ($extensions as $ext => $loaded): ?>
    <div class="item">
      <span>Extension: <code><?= $ext ?></code></span>
      <span class="status <?= $loaded ? 'ok' : 'warn' ?>"><?= $loaded ? 'Enabled' : 'Disabled' ?></span>
    </div>
  <?php endforeach; ?>
  <div class="item">
    <span>Active Storage Driver</span>
    <span class="status ok"><?= htmlspecialchars($activeDriver) ?></span>
  </div>
  <div class="item">
    <span>Catalogued Projects Loaded</span>
    <span class="status ok"><?= count($projects) ?> / 12 Projects</span>
  </div>
  <a href="index.php" class="btn">Return to Homepage &rarr;</a>
</div>
</body>
</html>
