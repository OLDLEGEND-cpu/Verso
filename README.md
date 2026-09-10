# Verso — Editorial Creative Discovery Platform

A lightweight, high-fidelity creative discovery platform built with **PHP 8+**, **Supabase (PostgreSQL)**, vanilla HTML5/CSS3/JavaScript, and zero runtime framework bloat.

---

## Highlights

- **Supabase Cloud Backend**: Connected to Supabase PostgreSQL via PostgREST API with Row Level Security (RLS) policies.
- **Smart Fallback Architecture**: Seamless auto-fallback to MySQL PDO and an auto-seeded local SQLite database ensuring 100% uptime with zero 500 crashes.
- **Curated High-Resolution Visuals**: 12 bespoke showcase photography and design assets across typography specimens, fintech interfaces, generative coastal erosion motion art, architecture journals, risograph packaging, and tactile hardware.
- **Real-Time Archive Discovery**: Client-side instant search, multi-tag discipline filtering, and view mode switching (3-Column Grid, 2-Column Showcase, List View).
- **Saved Collection Drawer**: Global bookmark curation system persisted in `localStorage` with a slide-out drawer.
- **Editorial Experience**: Dedicated project narratives, studio directory with practice profiles, core philosophy manifesto, and a validated contact desk.

---

## Directory Structure

```
verso/
├── index.php             # Home: Hero spotlight, marquee ticker, featured projects, manifesto
├── discover.php          # Living Archive: Live search, filters, layout switcher, bookmarks
├── project.php           # Project Details: Hero banner, editorial brief, metadata, share link
├── studio.php            # Studios: Practice directory and individual studio profiles
├── about.php             # Editorial Ethos: Three non-negotiable principles, curation protocol, colophon
├── contact.php           # Inquiries: Form with CSRF protection, character count, dual-storage
├── includes/
│   ├── config.php        # Configuration constants & smart portable BASE_URL detection
│   ├── db.php            # Multi-backend database layer (Supabase, MySQL, auto-seeded SQLite)
│   ├── functions.php     # Helper utilities, query handlers, and visual asset resolvers
│   ├── header.php        # Glassmorphic navigation header & saved collection trigger
│   └── footer.php        # Footer, interactive database architecture modal & toast container
├── assets/
│   ├── css/style.css     # Design system, luxury paper palette, typography rhythm & layout modes
│   ├── js/main.js        # Live filtering, bookmarks drawer, share feedback & form validation
│   └── images/projects/  # High-resolution project showcase imagery for all 12 projects
└── sql/
    ├── supabase_schema.sql  # Idempotent PostgreSQL schema migration for Supabase (with RLS)
    └── schema.sql           # MySQL database schema & seed data
```

---

## Quick Start

### 1. Run via PHP Built-in Server
From the repository root:
```bash
php -S localhost:8080 -t verso
```
Then visit: `http://localhost:8080/index.php`

### 2. Run via XAMPP
1. Place the repository inside `C:/xampp/htdocs/verso/`.
2. Start Apache in XAMPP Control Panel.
3. Visit: `http://localhost/verso/verso/index.php` (or `http://localhost/verso/index.php`).

---

## Database Configuration

The application is pre-configured with **Supabase Cloud (PostgreSQL)** and auto-seeded SQLite.
- To run migrations in Supabase, execute `verso/sql/supabase_schema.sql` in the Supabase SQL Editor.
- Database settings can be reviewed or adjusted in `verso/includes/config.php`.

---

## License

Curated and built with editorial restraint. All rights reserved.
