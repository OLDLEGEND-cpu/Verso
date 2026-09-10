<?php
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
$project = $slug ? fetch_project_by_slug($slug) : null;

if (!$project) {
    http_response_code(404);
    $page_title = 'Project Not Found — Verso';
    $active = 'discover';
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="section text-center">
      <div class="container state-block" style="max-width: 580px; margin: var(--space-8) auto;">
        <div class="state-icon">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <h2>Project not found</h2>
        <p>The project record you requested could not be located in the Verso catalogue. It may have been renamed or repositioned in our archive.</p>
        <a href="<?= url('discover.php') ?>" class="btn btn-primary">Return to Discover</a>
      </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

$related = fetch_related_projects($project['category'], (int) $project['id']);
[$v1, $v2] = visual_palette($project['visual_reference']);
$studio = fetch_studio_by_slug(slugify($project['creator_name']));
$img = get_project_image($project);

$page_title = $project['title'] . ' — ' . $project['creator_name'] . ' — Verso';
$page_description = mb_strimwidth(strip_tags($project['description']), 0, 155, '…');
$active = 'discover';

include __DIR__ . '/includes/header.php';
?>

<article class="project-detail-view" data-slug="<?= h($project['slug']) ?>">
  <section class="section-tight">
    <div class="container">

      <!-- Breadcrumbs -->
      <nav class="breadcrumbs" aria-label="Breadcrumb">
        <a href="<?= url('index.php') ?>">Home</a>
        <span aria-hidden="true">/</span>
        <a href="<?= url('discover.php') ?>">Archive</a>
        <span aria-hidden="true">/</span>
        <a href="<?= url('discover.php?category=' . urlencode($project['category'])) ?>"><?= h($project['category']) ?></a>
        <span aria-hidden="true">/</span>
        <span class="current"><?= h($project['title']) ?></span>
      </nav>

      <!-- Project Header -->
      <header class="project-header">
        <div class="project-header-top">
          <span class="badge"><?= h($project['category']) ?></span>
          <span class="project-meta-pill">Curated Entry #<?= h((string)$project['id']) ?></span>
        </div>
        <h1 class="project-title"><?= h($project['title']) ?></h1>
        <p class="project-byline">
          Designed by <a href="<?= $studio ? url('studio.php?slug=' . urlencode($studio['slug'])) : '#' ?>" class="creator-link"><strong><?= h($project['creator_name']) ?></strong></a> &bull; <?= h($project['creator_role']) ?>
        </p>
      </header>

      <!-- Hero Visual Banner -->
      <div class="project-visual-banner">
        <?php if ($img): ?>
          <img src="<?= h($img) ?>" alt="<?= h($project['title']) ?> Showcase Visual" class="project-hero-img">
        <?php else: ?>
          <div class="visual visual-wide" style="--v1: <?= $v1 ?>; --v2: <?= $v2 ?>;" role="img" aria-label="<?= h($project['title']) ?>">
            <span class="visual-tag" style="font-size: 2.2rem;"><?= h($project['title']) ?></span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Main Project Body & Aside Grid -->
      <div class="project-grid-content">
        <div class="project-content-main">
          <section class="project-section">
            <h2 class="project-section-title">Design Brief & Architectural Intent</h2>
            <div class="editorial project-editorial">
              <p class="lead-paragraph"><?= h($project['description']) ?></p>
              
              <p>In developing the visual language for this engagement, the creative team prioritized restraint and long-term utility over ephemeral design trends. The system balances structural legibility with expressive personality, designed to perform consistently across both physical and responsive digital touchpoints.</p>
              
              <blockquote class="pull-quote">
                "We set out to remove every decorative flourish that did not advance comprehension or material honesty."
              </blockquote>
              
              <p>Iterative rounds of physical prototyping and typographic testing informed the final decisions, resulting in a cohesive identity that adapts gracefully across disparate scales, resolutions, and medium constraints.</p>
            </div>
          </section>

          <?php if (!empty($project['tags'])): ?>
            <section class="project-section">
              <h3 class="project-section-title" style="font-size: 1.1rem;">Taxonomy & Tags</h3>
              <div class="tag-list">
                <?php foreach (explode(',', $project['tags']) as $tag): ?>
                  <a href="<?= url('discover.php?search=' . urlencode(trim($tag))) ?>" class="tag">#<?= h(trim($tag)) ?></a>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endif; ?>
        </div>

        <aside class="project-sidebar">
          <!-- Studio Card -->
          <div class="sidebar-box">
            <div class="creator-card">
              <div class="studio-avatar" style="--v1: <?= $v1 ?>; --v2: <?= $v2 ?>;" aria-hidden="true">
                <?= h(mb_substr($project['creator_name'], 0, 1)) ?>
              </div>
              <div>
                <div class="creator-name"><?= h($project['creator_name']) ?></div>
                <div class="creator-role"><?= h($project['creator_role']) ?></div>
              </div>
            </div>

            <?php if ($studio): ?>
              <p class="studio-sidebar-bio"><?= h(mb_strimwidth($studio['description'], 0, 140, '…')) ?></p>
              <a href="<?= url('studio.php?slug=' . urlencode($studio['slug'])) ?>" class="btn btn-secondary btn-full btn-small">
                View Studio Profile &rarr;
              </a>
            <?php endif; ?>
          </div>

          <!-- Project Metadata Table -->
          <div class="sidebar-box meta-table">
            <div class="meta-row">
              <span class="meta-label">Discipline</span>
              <span class="meta-value"><?= h($project['category']) ?></span>
            </div>
            <div class="meta-row">
              <span class="meta-label">Archived On</span>
              <span class="meta-value"><?= h(date('F Y', strtotime($project['created_at'] ?? 'now'))) ?></span>
            </div>
            <div class="meta-row">
              <span class="meta-label">Curation Status</span>
              <span class="meta-value status-badge">&bull; Verified</span>
            </div>
          </div>

          <!-- Interactive Actions (Save, Share) -->
          <div class="sidebar-actions">
            <button type="button" class="btn btn-primary btn-full btn-bookmark-toggle" data-bookmark-slug="<?= h($project['slug']) ?>">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
              <span class="bookmark-btn-text">Save to Collection</span>
            </button>
            <button type="button" class="btn btn-secondary btn-full btn-share" id="shareProjectBtn" data-title="<?= h($project['title']) ?>">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
              <span>Share Project</span>
            </button>
          </div>
        </aside>
      </div>

    </div>
  </section>

  <!-- Related Projects Section -->
  <?php if (!empty($related)): ?>
  <section class="section section-muted">
    <div class="container">
      <div class="section-head">
        <div>
          <span class="eyebrow">Related Works</span>
          <h2>More explorations in <?= h($project['category']) ?></h2>
        </div>
        <a href="<?= url('discover.php?category=' . urlencode($project['category'])) ?>" class="btn btn-secondary">
          View all in <?= h($project['category']) ?>
        </a>
      </div>

      <div class="grid grid-3">
        <?php foreach ($related as $r): 
          [$rv1, $rv2] = visual_palette($r['visual_reference']); 
          $rimg = get_project_image($r);
        ?>
          <article class="card">
            <div class="card-media-wrap">
              <a href="<?= url('project.php?slug=' . urlencode($r['slug'])) ?>" class="card-media" aria-label="<?= h($r['title']) ?>">
                <?php if ($rimg): ?>
                  <img src="<?= h($rimg) ?>" alt="<?= h($r['title']) ?>" class="card-img" loading="lazy">
                <?php else: ?>
                  <div class="visual" style="--v1: <?= $rv1 ?>; --v2: <?= $rv2 ?>;" role="img" aria-label="<?= h($r['title']) ?>">
                    <span class="visual-tag"><?= h($r['title']) ?></span>
                  </div>
                <?php endif; ?>
              </a>
              <button type="button" class="btn-bookmark" data-bookmark-slug="<?= h($r['slug']) ?>" aria-label="Save project">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
              </button>
            </div>
            <div class="card-body">
              <div class="card-topline">
                <span class="card-category"><?= h($r['category']) ?></span>
                <span class="card-creator"><?= h($r['creator_name']) ?></span>
              </div>
              <h3 class="card-title">
                <a href="<?= url('project.php?slug=' . urlencode($r['slug'])) ?>"><?= h($r['title']) ?></a>
              </h3>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="section-tight text-center">
    <div class="container">
      <a href="<?= url('discover.php') ?>" class="btn btn-secondary">
        &larr; Return to Discovery Archive
      </a>
    </div>
  </section>
</article>

<?php include __DIR__ . '/includes/footer.php'; ?>
