<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = 'Verso — Discover Exceptional Creative Work';
$page_description = 'Verso is a discovery platform for exceptional creative work — branding, digital product design, motion, and editorial craft from studios worldwide.';
$active = 'home';

$featured = fetch_featured_projects(3);
$studios = fetch_studios(3);

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
  <div class="container hero-inner">
    <div class="hero-content">
      <div class="hero-badge">
        <span class="hero-pulse"></span>
        <span class="eyebrow hero-eyebrow">Curated Design Archive</span>
      </div>
      <h1>Where considered work finds the audience it deserves.</h1>
      <p class="hero-lede">Verso curates typography, product design, spatial environments, and editorial craft from independent studios worldwide who treat every detail as the point — never an afterthought.</p>
      <div class="hero-actions">
        <a href="<?= url('discover.php') ?>" class="btn btn-primary-hero">
          <span>Browse the archive</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="<?= url('studio.php') ?>" class="btn btn-secondary-hero">Directory</a>
      </div>
    </div>

    <div class="hero-aside">
      <a href="<?= url('project.php?slug=meridian-type-system') ?>" class="hero-card-preview" title="View Spotlight: Meridian Variable Type System">
        <div class="hero-card-media">
          <img src="<?= url('assets/images/projects/meridian_type.jpg') ?>" alt="Meridian Variable Type System" loading="eager">
          <div class="hero-card-overlay"></div>
        </div>
        <div class="hero-card-body">
          <div class="hero-preview-badge">
            <span class="hero-preview-tag">Curator's Spotlight</span>
            <span class="hero-preview-arrow">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
            </span>
          </div>
          <div class="hero-preview-title">Meridian Variable Type System</div>
          <div class="hero-preview-meta">Hollow &amp; Pine &bull; Typography Specimen</div>
        </div>
      </a>
      <div class="hero-stats">
        <div class="hero-stat-item">
          <div class="hero-stat-value">240+</div>
          <div class="hero-stat-label">Projects catalogued</div>
        </div>
        <div class="hero-stat-item">
          <div class="hero-stat-value">38</div>
          <div class="hero-stat-label">Studios verified</div>
        </div>
        <div class="hero-stat-item">
          <div class="hero-stat-value">8</div>
          <div class="hero-stat-label">Disciplines</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Editorial Ticker Strip -->
  <div class="marquee-strip" aria-hidden="true">
    <div class="marquee-track">
      <span>Typography Specimen</span> <span class="marquee-bullet">&bull;</span>
      <span>Spatial Environments</span> <span class="marquee-bullet">&bull;</span>
      <span>Generative Motion</span> <span class="marquee-bullet">&bull;</span>
      <span>Editorial Publishing</span> <span class="marquee-bullet">&bull;</span>
      <span>Digital Product Systems</span> <span class="marquee-bullet">&bull;</span>
      <span>Botanical Risograph</span> <span class="marquee-bullet">&bull;</span>
      <span>Brand Architecture</span> <span class="marquee-bullet">&bull;</span>
      <span>Tactile Photography</span> <span class="marquee-bullet">&bull;</span>
      <!-- Repeated for infinite scroll -->
      <span>Typography Specimen</span> <span class="marquee-bullet">&bull;</span>
      <span>Spatial Environments</span> <span class="marquee-bullet">&bull;</span>
      <span>Generative Motion</span> <span class="marquee-bullet">&bull;</span>
      <span>Editorial Publishing</span> <span class="marquee-bullet">&bull;</span>
      <span>Digital Product Systems</span> <span class="marquee-bullet">&bull;</span>
      <span>Botanical Risograph</span> <span class="marquee-bullet">&bull;</span>
      <span>Brand Architecture</span> <span class="marquee-bullet">&bull;</span>
      <span>Tactile Photography</span> <span class="marquee-bullet">&bull;</span>
    </div>
  </div>
</section>

<!-- Featured Projects -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="eyebrow">Curated Selection</span>
        <h2>Work worth slowing down for</h2>
      </div>
      <a href="<?= url('discover.php') ?>" class="btn btn-secondary">
        <span>View full archive</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>

    <div class="grid grid-3">
      <?php foreach ($featured as $project): 
        [$v1, $v2] = visual_palette($project['visual_reference']); 
        $img = get_project_image($project);
      ?>
        <article class="card" data-slug="<?= h($project['slug']) ?>" data-title="<?= h($project['title']) ?>" data-category="<?= h($project['category']) ?>" data-creator="<?= h($project['creator_name']) ?>" data-img="<?= h($img ?: '') ?>">
          <div class="card-media-wrap">
            <a href="<?= url('project.php?slug=' . urlencode($project['slug'])) ?>" class="card-media" aria-label="View <?= h($project['title']) ?>">
              <?php if ($img): ?>
                <img src="<?= h($img) ?>" alt="<?= h($project['title']) ?>" class="card-img" loading="lazy">
              <?php else: ?>
                <div class="visual" style="--v1: <?= $v1 ?>; --v2: <?= $v2 ?>;" role="img" aria-label="Visual placeholder for <?= h($project['title']) ?>">
                  <span class="visual-tag"><?= h($project['title']) ?></span>
                </div>
              <?php endif; ?>
            </a>
            <button type="button" class="btn-bookmark" data-bookmark-slug="<?= h($project['slug']) ?>" aria-label="Save to collection" title="Bookmark project">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
            </button>
          </div>

          <div class="card-body">
            <div class="card-topline">
              <span class="card-category"><?= h($project['category']) ?></span>
              <span class="card-creator"><?= h($project['creator_name']) ?></span>
            </div>
            <h3 class="card-title">
              <a href="<?= url('project.php?slug=' . urlencode($project['slug'])) ?>"><?= h($project['title']) ?></a>
            </h3>
            <p class="card-excerpt"><?= h(mb_strimwidth(strip_tags($project['description']), 0, 110, '…')) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Editorial Manifesto -->
<section class="section section-muted">
  <div class="container">
    <div class="editorial-grid">
      <div class="editorial-statement">
        <span class="eyebrow">Editorial Ethos</span>
        <h2>Why craft still matters in an era of automated noise</h2>
        <div class="editorial-body">
          <p>Most modern discovery feeds are engineered for velocity — an infinite scroll of fleeting thumbnails competing for micro-seconds of distracted attention. Verso was founded on the opposite conviction: that fewer, rigorously crafted works deserve generous room to breathe.</p>
          
          <blockquote class="pull-quote">
            "The work that endures is rarely the loudest. It is the work that was examined from every angle before it was ever published."
          </blockquote>
          
          <p>Every studio in our directory is individually appraised. Every project is documented alongside its brief, architectural constraints, and material logic — because great design is an intentional decision, not a decorative accident.</p>
        </div>
      </div>

      <div class="editorial-pillars">
        <div class="pillar-card">
          <div class="pillar-num">01</div>
          <h4>Context Over Volume</h4>
          <p>An artifact removed from its reasoning is mere decoration. We document the constraints, parameters, and rationale behind each piece.</p>
        </div>
        <div class="pillar-card">
          <div class="pillar-num">02</div>
          <h4>Attribution First</h4>
          <p>Every project credits the specific designers, typographers, and studios who built it. No anonymous aggregation.</p>
        </div>
        <div class="pillar-card">
          <div class="pillar-num">03</div>
          <h4>Physical & Digital Continuity</h4>
          <p>From variable typefaces on paper to reactive particle systems, we champion work that respects material reality.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Selected Studios Section -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="eyebrow">Studio Showcase</span>
        <h2>The independent minds behind the work</h2>
      </div>
      <a href="<?= url('studio.php') ?>" class="btn btn-secondary">
        <span>View all studios</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>

    <div class="grid grid-3">
      <?php foreach ($studios as $studio): 
        [$v1, $v2] = visual_palette($studio['visual_reference']); 
      ?>
        <div class="studio-card">
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
          <p class="studio-desc"><?= h(mb_strimwidth($studio['description'], 0, 130, '…')) ?></p>
          
          <div class="studio-footer">
            <span class="studio-location">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= h($studio['location']) ?>
            </span>
            <a href="<?= url('studio.php?slug=' . urlencode($studio['slug'])) ?>" class="link-arrow">
              <span>Profile</span>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Studio Submission CTA Band -->
<section class="section section-cta">
  <div class="container">
    <div class="cta-band">
      <div class="cta-badge">Open Submissions</div>
      <h2>Have work that belongs in the archive?</h2>
      <p>Verso reviews submissions from independent studios, typographers, and creative technologists on a continuous rolling basis. If your work is rigorous, distinctive, and crafted with intent, we want to see it.</p>
      <div class="cta-actions">
        <a href="<?= url('contact.php') ?>" class="btn btn-cta-primary">
          <span>Submit your studio</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="<?= url('about.php') ?>" class="btn btn-cta-secondary">Learn about our review process</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
