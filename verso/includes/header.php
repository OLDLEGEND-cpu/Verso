<?php
// Expects $page_title, $page_description, and optionally $active set by the calling page.
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($page_title ?? SITE_NAME) ?></title>
<meta name="description" content="<?= h($page_description ?? SITE_TAGLINE) ?>">
<meta property="og:title" content="<?= h($page_title ?? SITE_NAME) ?>">
<meta property="og:description" content="<?= h($page_description ?? SITE_TAGLINE) ?>">
<meta property="og:type" content="website">
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2222%22 fill=%22%23201C18%22/><text x=%2250%22 y=%2267%22 font-family=%22Georgia,serif%22 font-size=%2256%22 font-weight=%22bold%22 fill=%22%23D96B32%22 text-anchor=%22middle%22>V</text></svg>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..800;1,9..144,300..800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="container header-inner">
    <a href="<?= url('index.php') ?>" class="brand" aria-label="<?= h(SITE_NAME) ?> home">
      <span class="brand-mark">V</span><span class="brand-name">erso</span>
    </a>

    <nav class="main-nav" aria-label="Primary">
      <a href="<?= url('index.php') ?>" class="<?= $active === 'home' ? 'is-active' : '' ?>">Home</a>
      <a href="<?= url('discover.php') ?>" class="<?= $active === 'discover' ? 'is-active' : '' ?>">Discover</a>
      <a href="<?= url('studio.php') ?>" class="<?= $active === 'studio' ? 'is-active' : '' ?>">Studios</a>
      <a href="<?= url('about.php') ?>" class="<?= $active === 'about' ? 'is-active' : '' ?>">Philosophy</a>
      <a href="<?= url('contact.php') ?>" class="<?= $active === 'contact' ? 'is-active' : '' ?>">Contact</a>
    </nav>

    <div class="header-actions">
      <!-- Bookmarks / Curated drawer toggle -->
      <button type="button" class="btn-icon-saved" id="bookmarkDrawerBtn" aria-label="View Saved Projects" title="Saved Collection">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
        <span class="bookmark-badge" id="bookmarkCount">0</span>
      </button>

      <a href="<?= url('discover.php') ?>" class="btn btn-small btn-primary header-cta">Explore Archive</a>

      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mobileNav" aria-label="Open menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <nav class="mobile-nav" id="mobileNav" aria-label="Mobile" hidden>
    <a href="<?= url('index.php') ?>" class="<?= $active === 'home' ? 'is-active' : '' ?>">Home</a>
    <a href="<?= url('discover.php') ?>" class="<?= $active === 'discover' ? 'is-active' : '' ?>">Discover</a>
    <a href="<?= url('studio.php') ?>" class="<?= $active === 'studio' ? 'is-active' : '' ?>">Studios</a>
    <a href="<?= url('about.php') ?>" class="<?= $active === 'about' ? 'is-active' : '' ?>">Philosophy</a>
    <a href="<?= url('contact.php') ?>" class="<?= $active === 'contact' ? 'is-active' : '' ?>">Contact</a>
  </nav>
</header>

<main id="main">
