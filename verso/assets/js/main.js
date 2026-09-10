/**
 * Verso — Editorial Creative Discovery Platform
 * Interactive UX & Client-Side Systems
 */

document.addEventListener('DOMContentLoaded', () => {
  initMobileNav();
  initBookmarks();
  initDiscoverInteractive();
  initProjectShare();
  initContactForm();
  initDbModal();
});

/* ==========================================================================
   Toast Notification Utility
   ========================================================================== */
function showToast(message, duration = 3000) {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    <span>${message}</span>
  `;

  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('is-active'));

  setTimeout(() => {
    toast.classList.remove('is-active');
    setTimeout(() => toast.remove(), 350);
  }, duration);
}

/* ==========================================================================
   Mobile Navigation
   ========================================================================== */
function initMobileNav() {
  const toggle = document.getElementById('navToggle');
  const nav = document.getElementById('mobileNav');
  if (!toggle || !nav) return;

  toggle.addEventListener('click', () => {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!isOpen));
    nav.hidden = isOpen;
    toggle.setAttribute('aria-label', isOpen ? 'Open menu' : 'Close menu');
  });

  nav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      toggle.setAttribute('aria-expanded', 'false');
      nav.hidden = true;
    });
  });
}

/* ==========================================================================
   Bookmarks & Saved Collection Drawer
   ========================================================================== */
const BOOKMARKS_STORAGE_KEY = 'verso_saved_projects';

function getBookmarks() {
  try {
    const data = localStorage.getItem(BOOKMARKS_STORAGE_KEY);
    return data ? JSON.parse(data) : [];
  } catch (e) {
    return [];
  }
}

function saveBookmarks(list) {
  try {
    localStorage.setItem(BOOKMARKS_STORAGE_KEY, JSON.stringify(list));
  } catch (e) {}
  updateBookmarkUI();
}

function isProjectBookmarked(slug) {
  const list = getBookmarks();
  return list.some(item => item.slug === slug);
}

function toggleBookmark(projectData) {
  let list = getBookmarks();
  const exists = list.some(item => item.slug === projectData.slug);

  if (exists) {
    list = list.filter(item => item.slug !== projectData.slug);
    showToast(`Removed from your collection.`);
  } else {
    list.unshift(projectData);
    showToast(`Saved to your collection.`);
  }

  saveBookmarks(list);
}

function updateBookmarkUI() {
  const list = getBookmarks();
  const countBadges = document.querySelectorAll('#bookmarkCount');
  countBadges.forEach(b => {
    b.textContent = list.length;
    b.style.display = list.length > 0 ? 'flex' : 'none';
  });

  // Update bookmark buttons on cards
  document.querySelectorAll('[data-bookmark-slug]').forEach(btn => {
    const slug = btn.getAttribute('data-bookmark-slug');
    const bookmarked = isProjectBookmarked(slug);
    btn.classList.toggle('is-bookmarked', bookmarked);

    const textSpan = btn.querySelector('.bookmark-btn-text');
    if (textSpan) {
      textSpan.textContent = bookmarked ? 'Saved to Collection' : 'Save to Collection';
    }
  });

  renderBookmarkDrawer();
}

function renderBookmarkDrawer() {
  const container = document.getElementById('savedListContainer');
  if (!container) return;

  const list = getBookmarks();

  if (list.length === 0) {
    container.innerHTML = `
      <div class="drawer-empty-state">
        <p>No saved projects yet. Click the bookmark icon on any project to curate your personal archive.</p>
      </div>
    `;
    return;
  }

  container.innerHTML = list.map(item => `
    <div class="saved-item" data-saved-slug="${item.slug}">
      ${item.img 
        ? `<img src="${item.img}" alt="${item.title}" class="saved-item-img">`
        : `<div class="saved-item-img" style="background:#2B2621;"></div>`
      }
      <div class="saved-item-info">
        <a href="project.php?slug=${encodeURIComponent(item.slug)}" class="saved-item-title">${item.title}</a>
        <span class="saved-item-meta">${item.category || ''} &bull; ${item.creator || ''}</span>
      </div>
      <button type="button" class="saved-item-remove" data-remove-slug="${item.slug}" aria-label="Remove item">&times;</button>
    </div>
  `).join('');

  container.querySelectorAll('[data-remove-slug]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const slug = btn.getAttribute('data-remove-slug');
      let current = getBookmarks().filter(i => i.slug !== slug);
      saveBookmarks(current);
    });
  });
}

function initBookmarks() {
  // Drawer open/close triggers
  const drawerBtn = document.getElementById('bookmarkDrawerBtn');
  const drawer = document.getElementById('savedDrawer');
  const overlay = document.getElementById('drawerOverlay');
  const closeBtn = document.getElementById('drawerCloseBtn');
  const clearBtn = document.getElementById('clearBookmarksBtn');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-active');
    overlay.classList.add('is-active');
    drawer.setAttribute('aria-hidden', 'false');
    overlay.setAttribute('aria-hidden', 'false');
  }

  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('is-active');
    overlay.classList.remove('is-active');
    drawer.setAttribute('aria-hidden', 'true');
    overlay.setAttribute('aria-hidden', 'true');
  }

  if (drawerBtn) drawerBtn.addEventListener('click', openDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if (overlay) overlay.addEventListener('click', closeDrawer);

  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      saveBookmarks([]);
      showToast('Collection cleared.');
    });
  }

  // Delegated bookmark click listener
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-bookmark-slug]');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();

    const slug = btn.getAttribute('data-bookmark-slug');
    const card = btn.closest('.card, .project-detail-view');

    let title = card?.getAttribute('data-title') || document.querySelector('.project-title')?.textContent || slug;
    let category = card?.getAttribute('data-category') || document.querySelector('.badge')?.textContent || '';
    let creator = card?.getAttribute('data-creator') || document.querySelector('.creator-name')?.textContent || '';
    let img = card?.getAttribute('data-img') || document.querySelector('.project-hero-img')?.src || '';

    toggleBookmark({ slug, title, category, creator, img });
  });

  updateBookmarkUI();
}

/* ==========================================================================
   Discover Interactive Live Search & Layout Switcher
   ========================================================================== */
function initDiscoverInteractive() {
  const form = document.getElementById('discoverForm');
  const searchInput = document.getElementById('searchInput');
  const categoryInput = document.getElementById('categoryInput');
  const pills = document.querySelectorAll('.category-pills .pill');
  const layoutBtns = document.querySelectorAll('.layout-btn');
  const gridContainer = document.getElementById('projectsGrid');
  const resultsCount = document.getElementById('resultsCount');
  const clearBtn = document.getElementById('searchClearBtn');

  // Layout mode switcher
  const SAVED_LAYOUT_KEY = 'verso_preferred_layout';
  const savedLayout = localStorage.getItem(SAVED_LAYOUT_KEY) || 'grid-3';

  function setLayout(mode) {
    if (!gridContainer) return;
    gridContainer.classList.remove('grid-3', 'grid-2-showcase', 'grid-list');
    gridContainer.classList.add(mode);
    layoutBtns.forEach(btn => {
      btn.classList.toggle('is-active', btn.dataset.layout === mode);
    });
    localStorage.setItem(SAVED_LAYOUT_KEY, mode);
  }

  setLayout(savedLayout);

  layoutBtns.forEach(btn => {
    btn.addEventListener('click', () => setLayout(btn.dataset.layout));
  });

  // Category pills client filtering
  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      pills.forEach(p => p.classList.remove('is-active'));
      pill.classList.add('is-active');
      const cat = pill.dataset.category;
      if (categoryInput) categoryInput.value = cat;
      filterClientSide();
    });
  });

  // Live client-side instant filtering as user types
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      filterClientSide();
    });
  }

  if (clearBtn && searchInput) {
    clearBtn.addEventListener('click', () => {
      searchInput.value = '';
      clearBtn.remove();
      filterClientSide();
    });
  }

  function filterClientSide() {
    if (!gridContainer) return;
    const query = (searchInput?.value || '').toLowerCase().trim();
    const activePill = document.querySelector('.category-pills .pill.is-active');
    const cat = activePill?.dataset.category || 'All';

    const items = gridContainer.querySelectorAll('.project-item');
    let visibleCount = 0;

    items.forEach(item => {
      const title = (item.dataset.title || '').toLowerCase();
      const creator = (item.dataset.creator || '').toLowerCase();
      const desc = (item.dataset.desc || '').toLowerCase();
      const itemCat = item.dataset.category || '';

      const matchesCat = (cat === 'All' || itemCat === cat);
      const matchesQuery = !query || title.includes(query) || creator.includes(query) || desc.includes(query);

      if (matchesCat && matchesQuery) {
        item.style.display = '';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });

    if (resultsCount) {
      resultsCount.innerHTML = `Showing <strong>${visibleCount}</strong> project${visibleCount === 1 ? '' : 's'}` +
        (query ? ` matching "<span class="highlight-query">${escapeHtml(query)}</span>"` : '') +
        (cat !== 'All' ? ` in <strong>${escapeHtml(cat)}</strong>` : '');
    }
  }

  function escapeHtml(str) {
    return str.replace(/[&<>"']/g, m => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[m]);
  }
}

/* ==========================================================================
   Project Detail Share
   ========================================================================== */
function initProjectShare() {
  const shareBtn = document.getElementById('shareProjectBtn');
  if (!shareBtn) return;

  shareBtn.addEventListener('click', async () => {
    const title = shareBtn.getAttribute('data-title') || document.title;
    const url = window.location.href;

    if (navigator.share) {
      try {
        await navigator.share({ title, url });
        return;
      } catch (e) {}
    }

    // Fallback: Copy link to clipboard
    try {
      await navigator.clipboard.writeText(url);
      showToast('Project link copied to clipboard!');
    } catch (e) {
      showToast('Link ready: ' + url);
    }
  });
}

/* ==========================================================================
   Contact Form Live Validation & Character Count
   ========================================================================== */
function initContactForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;

  const msgField = form.querySelector('#message');
  const charCount = document.getElementById('charCount');

  if (msgField && charCount) {
    function updateCount() {
      const len = msgField.value.trim().length;
      charCount.textContent = `${len} / 10 min`;
      charCount.style.color = len >= 10 ? 'var(--color-accent)' : 'var(--color-text-subtle)';
    }
    msgField.addEventListener('input', updateCount);
    updateCount();
  }

  form.addEventListener('submit', (e) => {
    let valid = true;
    const requiredFields = form.querySelectorAll('[data-required]');

    requiredFields.forEach(field => {
      const wrapper = field.closest('.form-field');
      if (!wrapper) return;

      wrapper.classList.remove('has-error');
      const prevError = wrapper.querySelector('.field-error');
      if (prevError) prevError.remove();

      let fieldValid = field.value.trim().length > 0;
      if (field.type === 'email' && fieldValid) {
        fieldValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value.trim());
      }
      if (field.tagName.toLowerCase() === 'textarea' && fieldValid) {
        fieldValid = field.value.trim().length >= 10;
      }

      if (!fieldValid) {
        valid = false;
        wrapper.classList.add('has-error');
        const err = document.createElement('span');
        err.className = 'field-error';
        if (field.type === 'email') {
          err.textContent = 'Please enter a valid email address.';
        } else if (field.tagName.toLowerCase() === 'textarea') {
          err.textContent = 'Message must contain at least 10 characters.';
        } else {
          err.textContent = 'This field is required.';
        }
        wrapper.appendChild(err);
      }
    });

    if (!valid) {
      e.preventDefault();
      const firstInvalid = form.querySelector('.has-error input, .has-error textarea');
      if (firstInvalid) firstInvalid.focus();
    }
  });
}

/* ==========================================================================
   Database Status Modal Interactions
   ========================================================================== */
function initDbModal() {
  const btn = document.getElementById('dbStatusBtn');
  const overlay = document.getElementById('dbModalOverlay');
  const closeBtn = document.getElementById('dbModalCloseBtn');

  if (!btn || !overlay) return;

  function openModal() {
    overlay.classList.add('is-active');
    overlay.setAttribute('aria-hidden', 'false');
  }

  function closeModal() {
    overlay.classList.remove('is-active');
    overlay.setAttribute('aria-hidden', 'true');
  }

  btn.addEventListener('click', openModal);
  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeModal();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay.classList.contains('is-active')) {
      closeModal();
    }
  });
}

