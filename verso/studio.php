<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'studio';
$slug = trim($_GET['slug'] ?? '');

if ($slug) {
    // -------------------------------------------------------------
    // Individual Studio Profile
    // -------------------------------------------------------------
    $studio = fetch_studio_by_slug($slug);

    if (!$studio) {
        http_response_code(404);
        $page_title = 'Studio Not Found — Verso';
        include __DIR__ . '/includes/header.php';
        ?>
        <section class="section text-center">
          <div class="container state-block" style="max-width: 580px; margin: var(--space-8) auto;">
            <div class="state-icon">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h2>Studio profile not found</h2>
            <p>The design studio you are looking for may have been repositioned or does not currently exist in the active catalogue.</p>
            <a href="<?= url('studio.php') ?>" class="btn btn-primary">Return to Studio Directory</a>
          </div>
        </section>
        <?php
        include __DIR__ . '/includes/footer.php';
        exit;
    }

    $work = fetch_projects_by_creator($studio['name']);
    [$v1, $v2] = visual_palette($studio['visual_reference']);
    $page_title = $studio['name'] . ' — Design Studio Profile — Verso';
    $page_description = mb_strimwidth(strip_tags($studio['description']), 0, 155, '…');

    include __DIR__ . '/includes/header.php';
    ?>

    <section class="section-tight">
      <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumbs" aria-label="Breadcrumb">
          <a href="<?= url('index.php') ?>">Home</a>
          <span aria-hidden="true">/</span>
          <a href="<?= url('studio.php') ?>">Studios</a>
          <span aria-hidden="true">/</span>
          <span class="current"><?= h($studio['name']) ?></span>
        </nav>

        <!-- Studio Header -->
        <div class="studio-profile-header">
          <div class="studio-avatar studio-avatar-large" style="--v1: <?= $v1 ?>; --v2: <?= $v2 ?>;" aria-hidden="true">
            <?= h(mb_substr($studio['name'], 0, 1)) ?>
          </div>
          <div class="studio-profile-title-block">
            <span class="badge"><?= h($studio['specialty']) ?></span>
            <h1 class="studio-profile-name"><?= h($studio['name']) ?></h1>
            <div class="studio-meta-strip">
              <span class="studio-location">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <?= h($studio['location']) ?>
              </span>
              <span class="meta-dot">&bull;</span>
              <span><?= count($work) ?> Documented Work<?= count($work) === 1 ? '' : 's' ?></span>
            </div>
          </div>
        </div>

        <!-- Studio Manifesto / Ethos -->
        <div class="studio-profile-body">
          <div class="editorial studio-editorial">
            <h3 style="font-size: 1.25rem; margin-bottom: var(--space-4);">Practice Philosophy</h3>
            <p class="lead-paragraph"><?= h($studio['description']) ?></p>
            <p>Operating with disciplined attention to typography, materials, and digital utility, <?= h($studio['name']) ?> undertakes selective engagements with cultural institutions, architecture practices, and pioneering technology ventures.</p>
          </div>

          <div class="studio-contact-box">
            <h4>Work with <?= h($studio['name']) ?></h4>
            <p>Direct commissions and editorial inquiries are routed through their primary practice office.</p>
            <a href="<?= url('contact.php?subject=' . urlencode('Inquiry regarding ' . $studio['name'])) ?>" class="btn btn-primary btn-full">
              Inquire via Verso
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Studio Portfolio Work -->
    <?php if (!empty($work)): ?>
    <section class="section section-muted">
      <div class="container">
        <div class="section-head">
          <div>
            <span class="eyebrow">Catalogued Engagements</span>
            <h2>Published works by <?= h($studio['name']) ?></h2>
          </div>
        </div>
        <div class="grid grid-3">
          <?php foreach ($work as $project): 
            [$pv1, $pv2] = visual_palette($project['visual_reference']); 
            $pimg = get_project_image($project);
          ?>
            <article class="card">
              <div class="card-media-wrap">
                <a href="<?= url('project.php?slug=' . urlencode($project['slug'])) ?>" class="card-media" aria-label="<?= h($project['title']) ?>">
                  <?php if ($pimg): ?>
                    <img src="<?= h($pimg) ?>" alt="<?= h($project['title']) ?>" class="card-img" loading="lazy">
                  <?php else: ?>
                    <div class="visual" style="--v1: <?= $pv1 ?>; --v2: <?= $pv2 ?>;" role="img" aria-label="<?= h($project['title']) ?>">
                      <span class="visual-tag"><?= h($project['title']) ?></span>
                    </div>
                  <?php endif; ?>
                </a>
                <button type="button" class="btn-bookmark" data-bookmark-slug="<?= h($project['slug']) ?>" aria-label="Save project">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                </button>
              </div>
              <div class="card-body">
                <div class="card-topline">
                  <span class="card-category"><?= h($project['category']) ?></span>
                </div>
                <h3 class="card-title">
                  <a href="<?= url('project.php?slug=' . urlencode($project['slug'])) ?>"><?= h($project['title']) ?></a>
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
        <a href="<?= url('studio.php') ?>" class="btn btn-secondary">&larr; Return to Studio Directory</a>
      </div>
    </section>

    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

// -------------------------------------------------------------
// Studio Directory Index
// -------------------------------------------------------------
$studios = fetch_studios();
$page_title = 'Studio Directory — Verso';
$page_description = 'Meet the independent studios, type foundries, and design practices catalogued on Verso.';

include __DIR__ . '/includes/header.php';
?>

<section class="section-tight page-header">
  <div class="container">
    <span class="eyebrow">Practice Directory</span>
    <h1>Independent studios & designers</h1>
    <p class="lede">A vetted index of <?= count($studios) ?> independent design offices, typography collectives, and spatial laboratories across Europe, the Americas, and Asia.</p>
  </div>
</section>

<section class="section-tight" style="padding-top: 0;">
  <div class="container">
    <div class="grid grid-3">
      <?php foreach ($studios as $studio): 
        [$v1, $v2] = visual_palette($studio['visual_reference']); 
        $studioWorks = fetch_projects_by_creator($studio['name'], 2);
      ?>
        <div class="studio-card studio-card-rich">
          <div class="studio-head">
            <div class="studio-avatar" style="--v1: <?= $v1 ?>; --v2: <?= $v2 ?>;" aria-hidden="true">
              <?= h(mb_substr($studio['name'], 0, 1)) ?>
            </div>
            <div>
              <h3 class="studio-name">
                <a href="<?= url('studio.php?slug=' . urlencode($studio['slug'])) ?>"><?= h($studio['name']) ?></a>
              </h3>
              <span class="studio-specialty"><?= h($studio['specialty']) ?></span>
            </div>
          </div>

          <p class="studio-desc"><?= h($studio['description']) ?></p>

          <?php if (!empty($studioWorks)): ?>
            <div class="studio-works-preview">
              <span class="preview-label">Selected work:</span>
              <div class="preview-tags">
                <?php foreach ($studioWorks as $sw): ?>
                  <a href="<?= url('project.php?slug=' . urlencode($sw['slug'])) ?>" class="tag tag-link">
                    <?= h(mb_strimwidth($sw['title'], 0, 24, '…')) ?>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <div class="studio-footer">
            <span class="studio-location">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= h($studio['location']) ?>
            </span>
            <a href="<?= url('studio.php?slug=' . urlencode($studio['slug'])) ?>" class="btn btn-small btn-secondary">
              View Profile &rarr;
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Call to action band -->
<section class="section section-cta">
  <div class="container">
    <div class="cta-band">
      <div class="cta-badge">Studio Inquiries</div>
      <h2>Want your practice represented here?</h2>
      <p>We review portfolios on rolling weekly editorial cycles. If your work demonstrates rigor, typographic discipline, and formal clarity, we would love to review your archive.</p>
      <div class="cta-actions">
        <a href="<?= url('contact.php') ?>" class="btn btn-cta-primary">Submit your studio</a>
        <a href="<?= url('about.php') ?>" class="btn btn-cta-secondary">About our curation standards</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
