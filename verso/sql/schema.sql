-- Verso MySQL Database Schema + Seed Data
-- Import into MySQL: mysql -u root -p < sql/schema.sql

CREATE DATABASE IF NOT EXISTS verso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE verso;

-- ---------------------------------------------------------------
-- Table: studios
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS studios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL UNIQUE,
    specialty VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(150) NOT NULL,
    visual_reference VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- Table: projects
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    category VARCHAR(60) NOT NULL,
    creator_name VARCHAR(150) NOT NULL,
    creator_role VARCHAR(150) NOT NULL,
    visual_reference VARCHAR(150) NOT NULL,
    image_url VARCHAR(255) DEFAULT '',
    tags VARCHAR(255) DEFAULT '',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_featured (featured)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- Table: contact_messages
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- Seed: studios
-- ---------------------------------------------------------------
INSERT INTO studios (name, slug, specialty, description, location, visual_reference) VALUES
('Hollow & Pine', 'hollow-and-pine', 'Brand Identity', 'A four-person identity studio building considered visual systems for arts institutions and independent publishers. Known for restraint and typographic precision.', 'Portland, OR', 'hollow-pine'),
('Nomen Studio', 'nomen-studio', 'Digital Product Design', 'Nomen partners with early-stage teams to design interfaces that feel inevitable — reducing every screen to its essential decision.', 'Berlin, DE', 'nomen'),
('Faint Signal', 'faint-signal', 'Motion & Sound', 'A motion design collective exploring the space between sound and image, producing title sequences and generative brand films.', 'Tokyo, JP', 'faint-signal'),
('Statura', 'statura', 'Editorial Design', 'Statura designs books, journals, and print systems for cultural institutions, with a focus on grid discipline and material honesty.', 'Milan, IT', 'statura'),
('Loam Collective', 'loam-collective', 'Illustration', 'A loosely bound network of illustrators producing editorial and packaging artwork rooted in botanical and geological study.', 'Copenhagen, DK', 'loam'),
('Vantage Works', 'vantage-works', 'Architecture Branding', 'Vantage builds identity systems for architecture and construction firms, translating structural thinking into graphic language.', 'Zurich, CH', 'vantage')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- ---------------------------------------------------------------
-- Seed: projects
-- ---------------------------------------------------------------
INSERT INTO projects (title, slug, description, category, creator_name, creator_role, visual_reference, image_url, tags, featured) VALUES

('Meridian Type System', 'meridian-type-system',
'A custom variable typeface commissioned for a transatlantic architecture journal. Meridian was drawn to hold its shape at both caption size and billboard scale, with an optical axis that shifts weight without losing its geometric spine. The project included a full specimen book, licensing framework, and a companion set of grid templates for the journal''s editorial team.',
'Typography', 'Hollow & Pine', 'Type Design Studio', 'meridian-type', 'assets/images/projects/meridian_type.jpg', 'typeface,editorial,variable-font', 1),

('Aperture Banking App', 'aperture-banking-app',
'A ground-up redesign of a challenger bank''s mobile application, focused on making complex financial decisions feel calm rather than urgent. The system introduces a restrained color language tied to account states, and a card-based transaction view that scales from a single purchase to a full annual statement.',
'UI/UX', 'Nomen Studio', 'Product Design Team', 'aperture-app', 'assets/images/projects/aperture_banking.jpg', 'fintech,mobile,design-system', 1),

('Undertow Title Sequence', 'undertow-title-sequence',
'A ninety-second opening sequence for an independent film about coastal erosion. Faint Signal built a generative particle system that mimics sediment displacement, synced frame-by-frame to a modular score. The sequence was later adapted into three abstract loops used across the film''s marketing.',
'Motion', 'Faint Signal', 'Motion Collective', 'undertow-motion', 'assets/images/projects/undertow_motion.jpg', 'film,generative,sound-design', 1),

('Quarry Journal, Issue 04', 'quarry-journal-issue-04',
'The fourth issue of an independent design journal exploring the relationship between material extraction and architecture. Statura developed a modular grid that accommodates long-form essays, technical diagrams, and full-bleed photo essays within a single, coherent system.',
'Editorial', 'Statura', 'Editorial Design Studio', 'quarry-journal', 'assets/images/projects/quarry_journal.jpg', 'print,grid-system,publishing', 0),

('Foxglove Packaging Concept', 'foxglove-packaging-concept',
'A speculative packaging system for a small-batch herbal tea brand, illustrated entirely by hand and reproduced through risograph printing. Loam Collective drew each botanical study from pressed specimens, building a library of twelve plant illustrations used across the product line.',
'Illustration', 'Loam Collective', 'Illustration Studio', 'foxglove-packaging', 'assets/images/projects/foxglove_tea.jpg', 'packaging,botanical,risograph', 1),

('Substrate Architecture Identity', 'substrate-architecture-identity',
'A visual identity for a structural engineering firm, built around the idea of load paths made visible. Vantage translated stress diagrams into a flexible mark system that adapts across business cards, site signage, and technical reports.',
'Branding', 'Vantage Works', 'Brand Studio', 'substrate-identity', 'assets/images/projects/substrate_identity.jpg', 'branding,architecture,systems', 0),

('Halide Product Photography', 'halide-product-photography',
'A photographic study for a minimalist camera hardware brand, using continuous natural light and unfinished concrete surfaces to emphasize material honesty over studio polish. The series was shot over three days across two locations.',
'Photography', 'Nomen Studio', 'Visual Direction', 'halide-photography', 'assets/images/projects/halide_camera.jpg', 'product,photography,hardware', 0),

('Tessellate Spatial Study', 'tessellate-spatial-study',
'An experimental 3D environment exploring modular tessellated forms as a basis for exhibition architecture. Built as a proof-of-concept for a museum pavilion, the study was rendered across four lighting conditions to test material behavior at scale.',
'3D', 'Vantage Works', 'Spatial Design', 'tessellate-3d', 'assets/images/projects/tessellate_spatial.jpg', 'exhibition,3d-render,pavilion', 1),

('Ferro Type Specimen', 'ferro-type-specimen',
'A display serif designed for industrial signage, tested first on cast iron and later adapted to digital screens. Hollow & Pine produced a specimen catalog documenting the typeface''s behavior across nine different substrates.',
'Typography', 'Hollow & Pine', 'Type Design Studio', 'ferro-type', 'assets/images/projects/ferro_specimen.jpg', 'typeface,industrial,specimen', 0),

('Cartograph Wayfinding System', 'cartograph-wayfinding-system',
'A wayfinding and signage system for a regional transit authority, designed to remain legible at walking and driving speed alike. The system unifies six previously inconsistent sub-brands under a single navigational language.',
'UI/UX', 'Nomen Studio', 'Systems Design', 'cartograph-wayfinding', 'assets/images/projects/cartograph_wayfinding.jpg', 'wayfinding,transit,systems', 0),

('Hearth Motion Identity', 'hearth-motion-identity',
'A generative motion identity for a home goods brand''s seasonal campaigns, built from a single logotype that fractures and reassembles depending on the time of year. Faint Signal produced four seasonal variants from one base animation rig.',
'Motion', 'Faint Signal', 'Motion Collective', 'hearth-motion', 'assets/images/projects/hearth_motion.jpg', 'motion-identity,generative,seasonal', 0),

('Marrow Editorial Layout', 'marrow-editorial-layout',
'A long-form digital essay layout built for a science journalism outlet, designed to hold dense technical content without overwhelming the reader. Statura developed a responsive footnote system and an inline diagram component used throughout the piece.',
'Editorial', 'Statura', 'Editorial Design Studio', 'marrow-editorial', 'assets/images/projects/marrow_editorial.jpg', 'digital-editorial,longform,diagrams', 0)
ON DUPLICATE KEY UPDATE title=VALUES(title);
