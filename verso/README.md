# Verso — Creative Discovery Platform

A lightweight PHP + MySQL website for discovering creative projects and studios.

## Stack
PHP 8+, MySQL, vanilla HTML/CSS/JS (no frameworks).

## Setup (XAMPP or similar)

1. Copy the `verso/` folder into your web root (e.g. `htdocs/verso`).
2. Create the database and load seed data:
   ```
   mysql -u root -p < sql/schema.sql
   ```
   This creates the `verso` database, tables (`projects`, `studios`, `contact_messages`), and seeds 12 projects + 6 studios.
3. Edit `includes/config.php` with your local DB credentials (defaults: `root` / empty password / `localhost`).
4. Visit `http://localhost/verso/index.php`.

## Structure
```
verso/
├── index.php, discover.php, project.php, studio.php, about.php, contact.php
├── includes/       config.php, db.php, functions.php, header.php, footer.php
├── assets/css/style.css
├── assets/js/main.js
└── sql/schema.sql
```

## Notes
- All visuals are CSS-generated placeholders (no external image dependencies).
- Contact form uses PDO prepared statements + CSRF tokens; submissions are stored in `contact_messages`.
- Discover page filters/search/sort via GET params, queried server-side with prepared statements.
- Responsive breakpoints at 960px, 860px, and 720px.
