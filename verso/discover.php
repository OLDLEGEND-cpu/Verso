<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'discover';
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? 'All');
$sort = trim($_GET['sort'] ?? 'newest');

$allowed_sorts = ['newest', 'oldest', 'az', 'za'];
if (!in_array($sort, $allowed_sorts, true)) { 
    $sort = 'newest'; 
}

$categories = get_categories();
if (!in_array($category, $categories, true)) { 
    $category = 'All'; 
}

$projects = fetch_projects($search ?: null, $category, $sort);

$page_title = 'Discover Creative Work — Verso Archive';
$page_description = 'Browse branding, UI/UX, editorial, motion, typography, illustration, photography, and 3D projects from independent studios.';

include __DIR__ . '/includes/header.php';
?>

<section class="section-tight page-header">
  <div class="container">
    <div class="page-header-top">
      <span class="eyebrow">The Living Archive</span>
      <h1>Discover considered creative work</h1>
      <p class="lede">Explore <?= count($projects) ?> documented projects spanning variable typography, physical editorial publications, spatial computing, and digital systems.</p>
    </div>
  </div>
</section>

<section class="section-tight" style="padding-top: 0;">
  <div class="container">

    <form id="discoverForm" method="get" action="<?= url('discover.php') ?>">
      <input type="hidden" name="category" id="categoryInput" value="<?= h($category) ?>">

      <div class="discover-toolbar">
        <div class="search-field">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <label for="searchInput" class="sr-only">Search projects</label>
          <input type="search" id="searchInput" name="search" value="<?= h($search) ?>" placeholder="Filter by title, creator, or keyword…" autocomplete="off">
          <?php if (!empty($search)): ?>
            <button type="button" class="search-clear-btn" id="searchClearBtn" aria-label="Clear search">&times;</button>
          <?php endif; ?>
        </div>

        <div class="filter-controls">
          <div class="select-wrap">
            <label for="sortSelect" class="sr-only">Sort by</label>
            <select id="sortSelect" name="sort" class="select-field" aria-label="Sort projects">
              <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest additions</option>
              <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Earliest catalogued</option>
              <option value="az" <?= $sort === 'az' ? 'selected' : '' ?>>Title (A – Z)</option>
              <option value="za" <?= $sort === 'za' ? 'selected' : '' ?>>Title (Z – A)</option>
            </select>
          </div>

          <!-- Layout Switcher -->
          <div class="layout-toggle" role="group" aria-label="Grid layout view">
            <button type="button" class="layout-btn is-active" data-layout="grid-3" title="Standard Grid" aria-label="3-column grid">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </button>
            <button type="button" class="layout-btn" data-layout="grid-2-showcase" title="Showcase View" aria-label="2-column showcase">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="8" height="18"/><rect x="13" y="3" width="8" height="18"/></svg>
            </button>
            <button type="button" class="layout-btn" data-layout="grid-list" title="List View" aria-label="Detailed list view">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </button>
          </div>

          <a href="<?= url('discover.php') ?>" class="btn btn-secondary btn-small reset-btn" id="resetFilters">Reset</a>
        </div>
      </div>

      <!-- Category Filter Pills -->
      <div class="category-pills" role="group" aria-label="Filter by discipline">
        <button type="button" class="pill <?= $category === 'All' ? 'is-active' : '' ?>" data-category="All">
          <span>All Disciplines</span>
        </button>
        <?php foreach ($categories as $cat): ?>
          <button type="button" class="pill <?= $category === $cat ? 'is-active' : '' ?>" data-category="<?= h($cat) ?>">
            <span><?= h($cat) ?></span>
          </button>
        <?php endforeach; ?>
      </div>
    </form>

    <!-- Results Status Bar -->
    <div class="results-meta-bar">
      <div class="results-count" id="resultsCount">
        Showing <strong><?= count($projects) ?></strong> project<?= count($projects) === 1 ? '' : 's' ?>
        <?php if ($search): ?> matching "<span class="highlight-query"><?= h($search) ?></span>"<?php endif; ?>
        <?php if ($category !== 'All'): ?> in <strong><?= h($category) ?></strong><?php endif; ?>
      </div>

      <?php if (!empty($search) || $category !== 'All'): ?>
        <a href="<?= url('discover.php') ?>" class="active-filter-tag">
          <span>Clear active filters</span>
          <span aria-hidden="true">&times;</span>
        </a>
      <?php endif; ?>
    </div>

    <!-- Projects Grid Container -->
    <div id="projectsGrid" class="grid grid-3">
      <?php if (empty($projects)): ?>
        <div class="state-block full-width">
          <div class="state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          </div>
          <h3>No matching projects found</h3>
          <p>We couldn't find any projects matching your current search parameters. Try expanding your search terms or resetting the discipline filter.</p>
          <a href="<?= url('discover.php') ?>" class="btn btn-primary">Reset all filters</a>
        </div>
      <?php else: ?>
        <?php foreach ($projects as $project): 
          [$v1, $v2] = visual_palette($project['visual_reference']); 
          $img = get_project_image($project);
        ?>
          <article class="card project-item" 
                   data-slug="<?= h($project['slug']) ?>" 
                   data-title="<?= h($project['title']) ?>" 
                   data-category="<?= h($project['category']) ?>" 
                   data-creator="<?= h($project['creator_name']) ?>"
                   data-desc="<?= h($project['description']) ?>"
                   data-img="<?= h($img ?: '') ?>">
            
            <div class="card-media-wrap">
              <a href="<?= url('project.php?slug=' . urlencode($project['slug'])) ?>" class="card-media" aria-label="View project <?= h($project['title']) ?>">
                <?php if ($img): ?>
                  <img src="<?= h($img) ?>" alt="<?= h($project['title']) ?>" class="card-img" loading="lazy">
                <?php else: ?>
                  <div class="visual" style="--v1: <?= $v1 ?>; --v2: <?= $v2 ?>;" role="img" aria-label="Visual placeholder for <?= h($project['title']) ?>">
                    <span class="visual-tag"><?= h($project['title']) ?></span>
                  </div>
                <?php endif; ?>
              </a>
              <button type="button" class="btn-bookmark" data-bookmark-slug="<?= h($project['slug']) ?>" aria-label="Save project" title="Save to collection">
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
              <p class="card-excerpt"><?= h(mb_strimwidth(strip_tags($project['description']), 0, 115, '…')) ?></p>
              
              <?php if (!empty($project['tags'])): ?>
                <div class="tag-list">
                  <?php foreach (array_slice(explode(',', $project['tags']), 0, 3) as $tag): ?>
                    <span class="tag">#<?= h(trim($tag)) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
