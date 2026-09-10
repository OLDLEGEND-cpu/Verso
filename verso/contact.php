<?php
require_once __DIR__ . '/includes/functions.php';

$active = 'contact';
$page_title = 'Contact the Editorial Desk — Verso';
$page_description = 'Submit a studio for review, send editorial inquiries, or contact the curators at Verso.';

$errors = [];
$success = false;
$old = [
    'name'    => '',
    'email'   => '',
    'subject' => trim($_GET['subject'] ?? ''),
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']    = trim($_POST['name'] ?? '');
    $old['email']   = trim($_POST['email'] ?? '');
    $old['subject'] = trim($_POST['subject'] ?? '');
    $old['message'] = trim($_POST['message'] ?? '');
    $token          = $_POST['csrf_token'] ?? '';

    if (!csrf_verify($token)) {
        $errors['general'] = 'Your security session expired. Please resubmit the form.';
    }

    if ($old['name'] === '') {
        $errors['name'] = 'Please provide your full or studio name.';
    }
    if ($old['email'] === '') {
        $errors['email'] = 'Please provide your contact email address.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address (e.g. name@studio.com).';
    }
    if ($old['subject'] === '') {
        $errors['subject'] = 'Please provide a concise subject or project title.';
    }
    if ($old['message'] === '') {
        $errors['message'] = 'Please write your message or project brief.';
    } elseif (mb_strlen($old['message']) < 10) {
        $errors['message'] = 'Your message must be at least 10 characters long.';
    }

    if (empty($errors)) {
        $ok = save_contact_message($old['name'], $old['email'], $old['subject'], $old['message']);
        if ($ok) {
            $success = true;
            $old = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
        } else {
            $errors['general'] = 'An unexpected error occurred while archiving your message. Please try again shortly.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="section-tight page-header">
  <div class="container">
    <span class="eyebrow">Direct Inquiries</span>
    <h1>Submit work or connect with our curators</h1>
    <p class="lede">We read every dispatch from independent studios, typographers, architects, and product designers. Submissions are reviewed weekly.</p>
  </div>
</section>

<section class="section-tight" style="padding-top: 0;">
  <div class="container contact-grid">

    <!-- Contact Form Column -->
    <div class="contact-form-column">
      <?php if ($success): ?>
        <div class="alert alert-success" role="status">
          <div class="alert-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
          </div>
          <div>
            <strong>Inquiry recorded successfully.</strong>
            <p>Thank you for getting in touch. Your dispatch has been archived in our editorial review queue. A curator will review your message and reply within 2–3 business days.</p>
          </div>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error" role="alert">
          <div class="alert-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          </div>
          <div><?= h($errors['general']) ?></div>
        </div>
      <?php endif; ?>

      <form id="contactForm" class="form-card" method="post" action="<?= url('contact.php') ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

        <div class="form-grid">
          <div class="form-field <?= isset($errors['name']) ? 'has-error' : '' ?>">
            <label for="name">Your Name / Studio Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" data-required value="<?= h($old['name']) ?>" placeholder="e.g. Elena Rostova or Nomen Studio" autocomplete="name" required>
            <?php if (isset($errors['name'])): ?>
              <span class="field-error"><?= h($errors['name']) ?></span>
            <?php endif; ?>
          </div>

          <div class="form-field <?= isset($errors['email']) ? 'has-error' : '' ?>">
            <label for="email">Contact Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" data-required value="<?= h($old['email']) ?>" placeholder="e.g. studio@practice.com" autocomplete="email" required>
            <?php if (isset($errors['email'])): ?>
              <span class="field-error"><?= h($errors['email']) ?></span>
            <?php endif; ?>
          </div>

          <div class="form-field full <?= isset($errors['subject']) ? 'has-error' : '' ?>">
            <label for="subject">Subject / Project Title <span class="required">*</span></label>
            <input type="text" id="subject" name="subject" data-required value="<?= h($old['subject']) ?>" placeholder="e.g. Studio Archive Submission: Brand Identity System" required>
            <?php if (isset($errors['subject'])): ?>
              <span class="field-error"><?= h($errors['subject']) ?></span>
            <?php endif; ?>
          </div>

          <div class="form-field full <?= isset($errors['message']) ? 'has-error' : '' ?>">
            <div class="form-field-header">
              <label for="message">Project Description & Links <span class="required">*</span></label>
              <span class="char-count" id="charCount">0 / 10 min</span>
            </div>
            <textarea id="message" name="message" data-required rows="7" placeholder="Please detail the project brief, key constraints, studio credits, and any online specimens or portfolio URLs…" required><?= h($old['message']) ?></textarea>
            <?php if (isset($errors['message'])): ?>
              <span class="field-error"><?= h($errors['message']) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary btn-submit">
            <span>Send Dispatch</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
          <span class="privacy-note">Protected by CSRF session verification and encrypted transfer.</span>
        </div>
      </form>
    </div>

    <!-- Sidebar Guidelines & Inquiries -->
    <aside class="contact-sidebar">
      <div class="contact-info-card">
        <h3>Direct Channels</h3>
        <div class="contact-info-list">
          <div class="contact-item">
            <span class="meta-label">Curatorial Desk</span>
            <a href="mailto:<?= h(SITE_EMAIL) ?>" class="contact-val"><?= h(SITE_EMAIL) ?></a>
          </div>
          <div class="contact-item">
            <span class="meta-label">Direct Line</span>
            <span class="contact-val"><?= h(SITE_PHONE) ?></span>
          </div>
          <div class="contact-item">
            <span class="meta-label">Studio Archive Location</span>
            <span class="contact-val"><?= h(SITE_ADDRESS) ?></span>
          </div>
        </div>
      </div>

      <div class="submission-tips-card">
        <h4>Submission Checklist</h4>
        <ul class="tips-list">
          <li><strong>High-Resolution Specimens:</strong> Provide links to uncompressed imagery or PDF specimen books.</li>
          <li><strong>Clear Attribution:</strong> List every contributor, type designer, photographer, and 3D artist involved.</li>
          <li><strong>Rationalized Intent:</strong> Brief summaries explaining the conceptual framework and design decisions.</li>
        </ul>
      </div>
    </aside>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
