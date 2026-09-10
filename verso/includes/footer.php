</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a href="<?= url('index.php') ?>" class="brand">
        <span class="brand-mark">V</span><span class="brand-name">erso</span>
      </a>
      <p class="footer-tagline"><?= h(SITE_TAGLINE) ?></p>
      
      <button type="button" class="db-status-badge" id="dbStatusBtn" title="Click to view database connection details & Supabase setup">
        <span class="db-dot"></span>
        <span>Storage: <strong><?= h(VersoDB::getActiveDriver()) ?></strong></span>
      </button>
    </div>

    <div class="footer-col">
      <h3 class="footer-heading">Platform</h3>
      <a href="<?= url('index.php') ?>">Index</a>
      <a href="<?= url('discover.php') ?>">Archive</a>
      <a href="<?= url('studio.php') ?>">Studios</a>
      <a href="<?= url('about.php') ?>">Editorial Ethos</a>
      <a href="<?= url('contact.php') ?>">Submit Project</a>
    </div>

    <div class="footer-col">
      <h3 class="footer-heading">Inquiries</h3>
      <a href="mailto:<?= h(SITE_EMAIL) ?>"><?= h(SITE_EMAIL) ?></a>
      <a href="tel:<?= h(str_replace([' ', '(', ')'], '', SITE_PHONE)) ?>"><?= h(SITE_PHONE) ?></a>
      <span class="footer-address"><?= h(SITE_ADDRESS) ?></span>
    </div>

    <div class="footer-col">
      <h3 class="footer-heading">Dispatches</h3>
      <a href="https://instagram.com" target="_blank" rel="noopener">Instagram</a>
      <a href="https://are.na" target="_blank" rel="noopener">Are.na</a>
      <a href="https://linkedin.com" target="_blank" rel="noopener">LinkedIn</a>
      <a href="https://twitter.com" target="_blank" rel="noopener">X / Twitter</a>
    </div>
  </div>

  <div class="container footer-bottom">
    <span>&copy; <?= date('Y') ?> Verso Platform Inc. All curation rights reserved.</span>
    <div class="footer-legal">
      <span>Curated with editorial restraint.</span>
      <a href="<?= url('about.php') ?>">Colophon</a>
      <a href="<?= url('contact.php') ?>">Press</a>
    </div>
  </div>
</footer>

<!-- Slide-out Bookmark / Saved Collection Drawer -->
<div class="drawer-overlay" id="drawerOverlay" aria-hidden="true"></div>
<aside class="saved-drawer" id="savedDrawer" aria-label="Saved Collection" aria-hidden="true">
  <div class="saved-drawer-head">
    <div>
      <h3>Saved Collection</h3>
      <p class="saved-drawer-subtitle">Your locally curated works</p>
    </div>
    <button type="button" class="drawer-close" id="drawerCloseBtn" aria-label="Close saved collection">&times;</button>
  </div>
  <div class="saved-drawer-body" id="savedListContainer">
    <!-- Populated dynamically via main.js -->
    <div class="drawer-empty-state">
      <p>No saved projects yet. Click the bookmark icon on any project to curate your personal archive.</p>
    </div>
  </div>
  <div class="saved-drawer-footer">
    <button type="button" class="btn btn-secondary btn-small" id="clearBookmarksBtn">Clear All</button>
    <a href="<?= url('discover.php') ?>" class="btn btn-primary btn-small">Explore Work</a>
  </div>
</aside>

<?php
$db = get_db();
$projCount = $db->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$studioCount = $db->query('SELECT COUNT(*) FROM studios')->fetchColumn();
$msgCount = $db->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
$supabaseReady = VersoDB::isSupabaseAvailable();
?>
<!-- Database Status & Supabase Setup Modal -->
<div class="modal-overlay" id="dbModalOverlay" aria-hidden="true">
  <div class="db-modal" id="dbModal" role="dialog" aria-labelledby="dbModalTitle" aria-modal="true">
    <div class="db-modal-head">
      <div>
        <span class="eyebrow" style="color:var(--color-accent);">System Architecture</span>
        <h3 id="dbModalTitle">Database & Storage Status</h3>
      </div>
      <button type="button" class="modal-close-btn" id="dbModalCloseBtn" aria-label="Close modal">&times;</button>
    </div>

    <div class="db-modal-body">
      <div class="db-card-status <?= $supabaseReady ? 'is-supabase' : 'is-sqlite' ?>">
        <div class="db-card-icon">
          <span class="db-dot-large"></span>
        </div>
        <div class="db-card-info">
          <div class="db-card-label">Active Storage Driver</div>
          <div class="db-card-value"><?= h(VersoDB::getActiveDriver()) ?></div>
        </div>
      </div>

      <div class="db-specs-grid">
        <div class="db-spec-item">
          <span class="spec-label">Catalogued Projects</span>
          <span class="spec-value"><?= (int)$projCount ?></span>
        </div>
        <div class="db-spec-item">
          <span class="spec-label">Verified Studios</span>
          <span class="spec-value"><?= (int)$studioCount ?></span>
        </div>
        <div class="db-spec-item">
          <span class="spec-label">Contact Messages</span>
          <span class="spec-value"><?= (int)$msgCount ?></span>
        </div>
      </div>

      <div class="supabase-status-box">
        <div class="supabase-box-header">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3ECF8E" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          <strong>Supabase Cloud Integration</strong>
        </div>
        <p class="supabase-box-desc">
          Project URL: <code><?= h(SUPABASE_URL) ?></code><br>
          Status: <strong><?= $supabaseReady ? '<span style="color:#38A169;">Connected & Syncing</span>' : '<span style="color:#C95D26;">Connected (Schema ready to run in Supabase SQL Editor)</span>' ?></strong>
        </p>
        <div class="supabase-box-actions">
          <a href="https://supabase.com/dashboard/project/gzoukcvejprmvtwiojml/sql" target="_blank" rel="noopener" class="btn btn-small btn-primary">
            Open Supabase SQL Editor &rarr;
          </a>
          <a href="<?= url('sql/supabase_schema.sql') ?>" target="_blank" class="btn btn-small btn-secondary">
            View Supabase Schema SQL
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Global Toast Notification Container -->
<div class="toast-container" id="toastContainer" aria-live="polite"></div>

<script src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
