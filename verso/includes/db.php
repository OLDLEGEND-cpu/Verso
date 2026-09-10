<?php
require_once __DIR__ . '/config.php';

/**
 * Verso Unified Data Layer
 * Seamlessly integrates Supabase REST API with local MySQL & auto-seeded SQLite fallback.
 */
class VersoDB
{
    private static ?PDO $pdo = null;
    private static ?string $activeDriver = null;
    private static ?bool $supabaseAvailable = null;

    /**
     * Get or initialize the active PDO connection (MySQL or SQLite fallback).
     */
    public static function getPdo(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        // Try MySQL first if credentials provided
        if (defined('DB_HOST') && defined('DB_NAME') && DB_HOST !== '') {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT            => 1,
                ];
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
                self::$activeDriver = 'mysql';
                return self::$pdo;
            } catch (Throwable $e) {
                // MySQL unavailable, fall back to SQLite
            }
        }

        // SQLite fallback
        $sqliteDir = dirname(SQLITE_DB_PATH);
        if (!is_dir($sqliteDir)) {
            @mkdir($sqliteDir, 0777, true);
        }

        $needsSeed = !file_exists(SQLITE_DB_PATH) || filesize(SQLITE_DB_PATH) === 0;

        self::$pdo = new PDO('sqlite:' . SQLITE_DB_PATH);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        self::$activeDriver = 'sqlite';

        if ($needsSeed) {
            self::initSqliteDatabase(self::$pdo);
        }

        return self::$pdo;
    }

    /**
     * Query Supabase REST API via HTTP GET.
     */
    public static function supabaseGet(string $endpoint, array $queryParams = []): ?array
    {
        if (!defined('SUPABASE_URL') || !defined('SUPABASE_ANON_KEY') || empty(SUPABASE_URL)) {
            return null;
        }

        $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($endpoint, '/');
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'apikey: ' . SUPABASE_ANON_KEY,
                'Authorization: Bearer ' . SUPABASE_ANON_KEY,
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => 2,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && is_string($response)) {
            $data = json_decode($response, true);
            if (is_array($data)) {
                return $data;
            }
        }

        return null;
    }

    /**
     * Send POST request to Supabase REST API.
     */
    public static function supabasePost(string $endpoint, array $payload): bool
    {
        if (!defined('SUPABASE_URL') || !defined('SUPABASE_ANON_KEY') || empty(SUPABASE_URL)) {
            return false;
        }

        $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($endpoint, '/');
        $jsonData = json_encode($payload);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'apikey: ' . SUPABASE_ANON_KEY,
                'Authorization: Bearer ' . SUPABASE_ANON_KEY,
                'Content-Type: application/json',
                'Prefer: return=minimal',
            ],
            CURLOPT_TIMEOUT        => 3,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return in_array($httpCode, [200, 201, 204], true);
    }

    /**
     * Check if Supabase projects table is reachable and populated.
     */
    public static function isSupabaseAvailable(): bool
    {
        if (self::$supabaseAvailable !== null) {
            return self::$supabaseAvailable;
        }

        $test = self::supabaseGet('projects', ['select' => 'id', 'limit' => '1']);
        self::$supabaseAvailable = ($test !== null);
        return self::$supabaseAvailable;
    }

    /**
     * Returns name of current primary data provider.
     */
    public static function getActiveDriver(): string
    {
        if (self::isSupabaseAvailable()) {
            return 'Supabase (PostgreSQL)';
        }
        self::getPdo();
        return self::$activeDriver === 'mysql' ? 'MySQL' : 'Local SQLite';
    }

    /**
     * Initialize SQLite database schema and seed data.
     */
    private static function initSqliteDatabase(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS studios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                specialty TEXT NOT NULL,
                description TEXT NOT NULL,
                location TEXT NOT NULL,
                visual_reference TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS projects (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                description TEXT NOT NULL,
                category TEXT NOT NULL,
                creator_name TEXT NOT NULL,
                creator_role TEXT NOT NULL,
                visual_reference TEXT NOT NULL,
                image_url TEXT DEFAULT '',
                tags TEXT DEFAULT '',
                featured INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS contact_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                subject TEXT NOT NULL,
                message TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $stmt = $db->prepare("INSERT OR IGNORE INTO studios (name, slug, specialty, description, location, visual_reference) VALUES
            ('Hollow & Pine', 'hollow-and-pine', 'Brand Identity', 'A four-person identity studio building considered visual systems for arts institutions and independent publishers. Known for restraint and typographic precision.', 'Portland, OR', 'hollow-pine'),
            ('Nomen Studio', 'nomen-studio', 'Digital Product Design', 'Nomen partners with early-stage teams to design interfaces that feel inevitable — reducing every screen to its essential decision.', 'Berlin, DE', 'nomen'),
            ('Faint Signal', 'faint-signal', 'Motion & Sound', 'A motion design collective exploring the space between sound and image, producing title sequences and generative brand films.', 'Tokyo, JP', 'faint-signal'),
            ('Statura', 'statura', 'Editorial Design', 'Statura designs books, journals, and print systems for cultural institutions, with a focus on grid discipline and material honesty.', 'Milan, IT', 'statura'),
            ('Loam Collective', 'loam-collective', 'Illustration', 'A loosely bound network of illustrators producing editorial and packaging artwork rooted in botanical and geological study.', 'Copenhagen, DK', 'loam'),
            ('Vantage Works', 'vantage-works', 'Architecture Branding', 'Vantage builds identity systems for architecture and construction firms, translating structural thinking into graphic language.', 'Zurich, CH', 'vantage')
        ");
        $stmt->execute();

        $stmtProj = $db->prepare("INSERT OR IGNORE INTO projects (title, slug, description, category, creator_name, creator_role, visual_reference, image_url, tags, featured) VALUES
            ('Meridian Type System', 'meridian-type-system', 'A custom variable typeface commissioned for a transatlantic architecture journal. Meridian was drawn to hold its shape at both caption size and billboard scale, with an optical axis that shifts weight without losing its geometric spine. The project included a full specimen book, licensing framework, and a companion set of grid templates for the journal''s editorial team.', 'Typography', 'Hollow & Pine', 'Type Design Studio', 'meridian-type', 'assets/images/projects/meridian_type.png', 'typeface,editorial,variable-font', 1),
            ('Aperture Banking App', 'aperture-banking-app', 'A ground-up redesign of a challenger bank''s mobile application, focused on making complex financial decisions feel calm rather than urgent. The system introduces a restrained color language tied to account states, and a card-based transaction view that scales from a single purchase to a full annual statement.', 'UI/UX', 'Nomen Studio', 'Product Design Team', 'aperture-app', 'assets/images/projects/aperture_banking.png', 'fintech,mobile,design-system', 1),
            ('Undertow Title Sequence', 'undertow-title-sequence', 'A ninety-second opening sequence for an independent film about coastal erosion. Faint Signal built a generative particle system that mimics sediment displacement, synced frame-by-frame to a modular score. The sequence was later adapted into three abstract loops used across the film''s marketing.', 'Motion', 'Faint Signal', 'Motion Collective', 'undertow-motion', 'assets/images/projects/undertow_motion.png', 'film,generative,sound-design', 1),
            ('Quarry Journal, Issue 04', 'quarry-journal-issue-04', 'The fourth issue of an independent design journal exploring the relationship between material extraction and architecture. Statura developed a modular grid that accommodates long-form essays, technical diagrams, and full-bleed photo essays within a single, coherent system.', 'Editorial', 'Statura', 'Editorial Design Studio', 'quarry-journal', 'assets/images/projects/quarry_journal.png', 'print,grid-system,publishing', 0),
            ('Foxglove Packaging Concept', 'foxglove-packaging-concept', 'A speculative packaging system for a small-batch herbal tea brand, illustrated entirely by hand and reproduced through risograph printing. Loam Collective drew each botanical study from pressed specimens, building a library of twelve plant illustrations used across the product line.', 'Illustration', 'Loam Collective', 'Illustration Studio', 'foxglove-packaging', 'assets/images/projects/foxglove_tea.png', 'packaging,botanical,risograph', 1),
            ('Substrate Architecture Identity', 'substrate-architecture-identity', 'A visual identity for a structural engineering firm, built around the idea of load paths made visible. Vantage translated stress diagrams into a flexible mark system that adapts across business cards, site signage, and technical reports.', 'Branding', 'Vantage Works', 'Brand Studio', 'substrate-identity', 'assets/images/projects/substrate_identity.png', 'branding,architecture,systems', 0),
            ('Halide Product Photography', 'halide-product-photography', 'A photographic study for a minimalist camera hardware brand, using continuous natural light and unfinished concrete surfaces to emphasize material honesty over studio polish. The series was shot over three days across two locations.', 'Photography', 'Nomen Studio', 'Visual Direction', 'halide-photography', 'assets/images/projects/halide_camera.png', 'product,photography,hardware', 0),
            ('Tessellate Spatial Study', 'tessellate-spatial-study', 'An experimental 3D environment exploring modular tessellated forms as a basis for exhibition architecture. Built as a proof-of-concept for a museum pavilion, the study was rendered across four lighting conditions to test material behavior at scale.', '3D', 'Vantage Works', 'Spatial Design', 'tessellate-3d', 'assets/images/projects/tessellate_spatial.png', 'exhibition,3d-render,pavilion', 1),
            ('Ferro Type Specimen', 'ferro-type-specimen', 'A display serif designed for industrial signage, tested first on cast iron and later adapted to digital screens. Hollow & Pine produced a specimen catalog documenting the typeface''s behavior across nine different substrates.', 'Typography', 'Hollow & Pine', 'Type Design Studio', 'ferro-type', 'assets/images/projects/ferro_specimen.png', 'typeface,industrial,specimen', 0),
            ('Cartograph Wayfinding System', 'cartograph-wayfinding-system', 'A wayfinding and signage system for a regional transit authority, designed to remain legible at walking and driving speed alike. The system unifies six previously inconsistent sub-brands under a single navigational language.', 'UI/UX', 'Nomen Studio', 'Systems Design', 'cartograph-wayfinding', 'assets/images/projects/cartograph_wayfinding.png', 'wayfinding,transit,systems', 0),
            ('Hearth Motion Identity', 'hearth-motion-identity', 'A generative motion identity for a home goods brand''s seasonal campaigns, built from a single logotype that fractures and reassembles depending on the time of year. Faint Signal produced four seasonal variants from one base animation rig.', 'Motion', 'Faint Signal', 'Motion Collective', 'hearth-motion', 'assets/images/projects/hearth_motion.png', 'motion-identity,generative,seasonal', 0),
            ('Marrow Editorial Layout', 'marrow-editorial-layout', 'A long-form digital essay layout built for a science journalism outlet, designed to hold dense technical content without overwhelming the reader. Statura developed a responsive footnote system and an inline diagram component used throughout the piece.', 'Editorial', 'Statura', 'Editorial Design Studio', 'marrow-editorial', 'assets/images/projects/marrow_editorial.png', 'digital-editorial,longform,diagrams', 0)
        ");
        $stmtProj->execute();
    }
}

/**
 * Backward-compatible helper for PDO access.
 */
function get_db(): PDO
{
    return VersoDB::getPdo();
}
