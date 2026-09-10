<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'about';
$page_title = 'Our Creative Philosophy — Verso';
$page_description = 'Verso is a curated discovery archive built for considered creative work. Learn about our editorial philosophy, our selection criteria, and why we exist.';

include __DIR__ . '/includes/header.php';
?>

<section class="section-tight page-header">
  <div class="container">
    <span class="eyebrow">Editorial Manifesto</span>
    <h1 style="max-width: 18ch; margin-top: var(--space-3);">A deliberate counterweight to the infinite feed.</h1>
    <p class="lede">Verso is an architectural and digital design publication built around the conviction that good work deserves thoughtful documentation — not scroll velocity.</p>
  </div>
</section>

<section class="section-tight" style="padding-top: 0;">
  <div class="container">
    <div class="editorial-layout">
      <div class="editorial-prose">
        <p class="lead-paragraph">Verso began as an antidote to a growing condition in modern design culture: exceptional creative work was increasingly marooned inside platforms designed to commodify distraction. Micro-thumbnails, autoplay algorithms, and metrics that privilege instant visual shocks over enduring substance.</p>
        
        <p>We built Verso to reflect the cadence of an independent design monograph. A space where a corporate identity system can explain its mathematical grid; where a variable typeface can display its optical axes across multiple sizes; and where the specific practitioners behind the work are foregrounded rather than relegated to hidden tags.</p>

        <blockquote class="pull-quote">
          "Attention is the rarest currency a creative team can spend. Our mission is to ensure none of it is squandered on algorithmic noise."
        </blockquote>

        <p>Every piece indexed on Verso is reviewed by hand. We reject automated scrapers, sponsored listicles, and algorithmic feeds. Our editorial team investigates three primary criteria before entering any work into the catalogue: the conceptual clarity of the brief, the craft of execution, and the longevity of the solution.</p>
      </div>

      <aside class="editorial-stats-sidebar">
        <div class="curation-box">
          <h4>Curation Protocol</h4>
          <ul class="protocol-list">
            <li>
              <strong>100% Hand-Vetted</strong>
              <span>Every entry is evaluated and catalogued by our editorial team.</span>
            </li>
            <li>
              <strong>Attribution Transparency</strong>
              <span>Full credit lines for art directors, typographers, and developers.</span>
            </li>
            <li>
              <strong>No Pay-to-Play</strong>
              <span>Inclusion in our archive cannot be bought or sponsored.</span>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- Three Core Values -->
<section class="section section-muted">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="eyebrow">Core Principles</span>
        <h2>Three convictions we refuse to compromise</h2>
      </div>
    </div>

    <div class="value-grid">
      <div class="value-card">
        <div class="value-number">01</div>
        <h3>Context Over Volume</h3>
        <p>A design artifact divorced from its constraints is merely decoration. We ask every studio for the brief, the architectural parameters, and the technical trade-offs that dictated the outcome.</p>
      </div>
      <div class="value-card">
        <div class="value-number">02</div>
        <h3>Authorship, Always</h3>
        <p>Anonymous moodboards erase the labor of creative practitioners. On Verso, every line of credit is explicit, permanent, and linked directly to the studio's broader body of work.</p>
      </div>
      <div class="value-card">
        <div class="value-number">03</div>
        <h3>Material Honesty</h3>
        <p>Whether examining letterpress ink on unbleached cotton paper or WebGL particle dynamics on a screen, we celebrate work that honors the authentic physical or digital qualities of its medium.</p>
      </div>
    </div>
  </div>
</section>

<!-- Colophon Section -->
<section class="section">
  <div class="container">
    <div class="colophon-card">
      <span class="eyebrow">Colophon</span>
      <h3>Built with intentional minimalism</h3>
      <div class="colophon-grid">
        <div>
          <span class="colophon-label">Typography</span>
          <p>Set in <em>Fraunces</em>, an optical variable serif designed by Phaedra Charles and Flavia Zimbardi, paired with <em>Plus Jakarta Sans</em> for geometric structural readability.</p>
        </div>
        <div>
          <span class="colophon-label">Architecture</span>
          <p>Engineered with lightweight native PHP 8+, Supabase PostgreSQL REST data layer, and semantic HTML5/CSS3. Zero runtime framework bloat.</p>
        </div>
        <div>
          <span class="colophon-label">Ethical Standard</span>
          <p>Self-funded, tracker-free, and accessible according to WCAG 2.1 AA standards with full keyboard navigation and reduced-motion fidelity.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Join / Contact CTA -->
<section class="section section-cta">
  <div class="container">
    <div class="cta-band">
      <div class="cta-badge">Collaborations</div>
      <h2>Think your studio belongs in the archive?</h2>
      <p>We review portfolios on rolling cycles. Tell us about your projects, your team, and your philosophy.</p>
      <div class="cta-actions">
        <a href="<?= url('contact.php') ?>" class="btn btn-cta-primary">Contact the editorial desk</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
