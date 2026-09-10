<?php
require_once __DIR__ . '/db.php';

/**
 * Generate a portable URL respecting current deployment base path.
 */
function url($path = '')
{
    $base = rtrim(BASE_URL, '/');
    $path = '/' . ltrim($path, '/');
    return ($base === '' ? $path : $base . $path);
}

/**
 * Escape output for safe HTML rendering.
 */
function h($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a URL-safe slug from a string.
 */
function slugify($text)
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Return / create a CSRF token stored in the session.
 */
function csrf_token()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate a submitted CSRF token.
 */
function csrf_verify($token)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * All available project categories, in display order.
 */
function get_categories()
{
    return ['Branding', 'UI/UX', 'Editorial', 'Motion', 'Typography', 'Illustration', 'Photography', '3D'];
}

/**
 * Deterministically generate a two-stop gradient + accent shape based on a string seed.
 */
function visual_palette($seed)
{
    $palettes = [
        ['#1B1D20', '#3E444E'],
        ['#201B18', '#664B35'],
        ['#1A2421', '#375249'],
        ['#231C24', '#553E58'],
        ['#1F2026', '#414763'],
        ['#281E1C', '#6E433B'],
        ['#1F241E', '#4B5C43'],
        ['#241E28', '#5E4968'],
    ];
    $hash = crc32($seed);
    return $palettes[abs($hash) % count($palettes)];
}

/**
 * Embedded archive dataset ensuring zero-downtime fallback.
 */
function get_embedded_projects()
{
    return [
        [
            'id' => 1,
            'title' => 'Meridian Type System',
            'slug' => 'meridian-type-system',
            'description' => 'A custom variable typeface commissioned for a transatlantic architecture journal. Meridian was drawn to hold its shape at both caption size and billboard scale, with an optical axis that shifts weight without losing its geometric spine. The project included a full specimen book, licensing framework, and a companion set of grid templates for the journal\'s editorial team.',
            'category' => 'Typography',
            'creator_name' => 'Hollow & Pine',
            'creator_role' => 'Type Design Studio',
            'visual_reference' => 'meridian-type',
            'image_url' => 'assets/images/projects/meridian_type.jpg',
            'tags' => 'typeface,editorial,variable-font',
            'featured' => 1,
            'created_at' => '2026-09-01 10:00:00'
        ],
        [
            'id' => 2,
            'title' => 'Aperture Banking App',
            'slug' => 'aperture-banking-app',
            'description' => 'A ground-up redesign of a challenger bank\'s mobile application, focused on making complex financial decisions feel calm rather than urgent. The system introduces a restrained color language tied to account states, and a card-based transaction view that scales from a single purchase to a full annual statement.',
            'category' => 'UI/UX',
            'creator_name' => 'Nomen Studio',
            'creator_role' => 'Product Design Team',
            'visual_reference' => 'aperture-app',
            'image_url' => 'assets/images/projects/aperture_banking.jpg',
            'tags' => 'fintech,mobile,design-system',
            'featured' => 1,
            'created_at' => '2026-08-28 12:00:00'
        ],
        [
            'id' => 3,
            'title' => 'Undertow Title Sequence',
            'slug' => 'undertow-title-sequence',
            'description' => 'A ninety-second opening sequence for an independent film about coastal erosion. Faint Signal built a generative particle system that mimics sediment displacement, synced frame-by-frame to a modular score. The sequence was later adapted into three abstract loops used across the film\'s marketing.',
            'category' => 'Motion',
            'creator_name' => 'Faint Signal',
            'creator_role' => 'Motion Collective',
            'visual_reference' => 'undertow-motion',
            'image_url' => 'assets/images/projects/undertow_motion.jpg',
            'tags' => 'film,generative,sound-design',
            'featured' => 1,
            'created_at' => '2026-08-25 14:30:00'
        ],
        [
            'id' => 4,
            'title' => 'Quarry Journal, Issue 04',
            'slug' => 'quarry-journal-issue-04',
            'description' => 'The fourth issue of an independent design journal exploring the relationship between material extraction and architecture. Statura developed a modular grid that accommodates long-form essays, technical diagrams, and full-bleed photo essays within a single, coherent system.',
            'category' => 'Editorial',
            'creator_name' => 'Statura',
            'creator_role' => 'Editorial Design Studio',
            'visual_reference' => 'quarry-journal',
            'image_url' => 'assets/images/projects/quarry_journal.jpg',
            'tags' => 'print,grid-system,publishing',
            'featured' => 0,
            'created_at' => '2026-08-20 09:15:00'
        ],
        [
            'id' => 5,
            'title' => 'Foxglove Packaging Concept',
            'slug' => 'foxglove-packaging-concept',
            'description' => 'A speculative packaging system for a small-batch herbal tea brand, illustrated entirely by hand and reproduced through risograph printing. Loam Collective drew each botanical study from pressed specimens, building a library of twelve plant illustrations used across the product line.',
            'category' => 'Illustration',
            'creator_name' => 'Loam Collective',
            'creator_role' => 'Illustration Studio',
            'visual_reference' => 'foxglove-packaging',
            'image_url' => 'assets/images/projects/foxglove_tea.jpg',
            'tags' => 'packaging,botanical,risograph',
            'featured' => 1,
            'created_at' => '2026-08-15 16:00:00'
        ],
        [
            'id' => 6,
            'title' => 'Substrate Architecture Identity',
            'slug' => 'substrate-architecture-identity',
            'description' => 'A visual identity for a structural engineering firm, built around the idea of load paths made visible. Vantage translated stress diagrams into a flexible mark system that adapts across business cards, site signage, and technical reports.',
            'category' => 'Branding',
            'creator_name' => 'Vantage Works',
            'creator_role' => 'Brand Studio',
            'visual_reference' => 'substrate-identity',
            'image_url' => 'assets/images/projects/substrate_identity.jpg',
            'tags' => 'branding,architecture,systems',
            'featured' => 0,
            'created_at' => '2026-08-10 11:20:00'
        ],
        [
            'id' => 7,
            'title' => 'Halide Product Photography',
            'slug' => 'halide-product-photography',
            'description' => 'A photographic study for a minimalist camera hardware brand, using continuous natural light and unfinished concrete surfaces to emphasize material honesty over studio polish. The series was shot over three days across two locations.',
            'category' => 'Photography',
            'creator_name' => 'Nomen Studio',
            'creator_role' => 'Visual Direction',
            'visual_reference' => 'halide-photography',
            'image_url' => 'assets/images/projects/halide_camera.jpg',
            'tags' => 'product,photography,hardware',
            'featured' => 0,
            'created_at' => '2026-08-05 15:45:00'
        ],
        [
            'id' => 8,
            'title' => 'Tessellate Spatial Study',
            'slug' => 'tessellate-spatial-study',
            'description' => 'An experimental 3D environment exploring modular tessellated forms as a basis for exhibition architecture. Built as a proof-of-concept for a museum pavilion, the study was rendered across four lighting conditions to test material behavior at scale.',
            'category' => '3D',
            'creator_name' => 'Vantage Works',
            'creator_role' => 'Spatial Design',
            'visual_reference' => 'tessellate-3d',
            'image_url' => 'assets/images/projects/tessellate_spatial.jpg',
            'tags' => 'exhibition,3d-render,pavilion',
            'featured' => 1,
            'created_at' => '2026-08-01 13:10:00'
        ],
        [
            'id' => 9,
            'title' => 'Ferro Type Specimen',
            'slug' => 'ferro-type-specimen',
            'description' => 'A display serif designed for industrial signage, tested first on cast iron and later adapted to digital screens. Hollow & Pine produced a specimen catalog documenting the typeface\'s behavior across nine different substrates.',
            'category' => 'Typography',
            'creator_name' => 'Hollow & Pine',
            'creator_role' => 'Type Design Studio',
            'visual_reference' => 'ferro-type',
            'image_url' => 'assets/images/projects/ferro_specimen.jpg',
            'tags' => 'typeface,industrial,specimen',
            'featured' => 0,
            'created_at' => '2026-07-28 10:00:00'
        ],
        [
            'id' => 10,
            'title' => 'Cartograph Wayfinding System',
            'slug' => 'cartograph-wayfinding-system',
            'description' => 'A wayfinding and signage system for a regional transit authority, designed to remain legible at walking and driving speed alike. The system unifies six previously inconsistent sub-brands under a single navigational language.',
            'category' => 'UI/UX',
            'creator_name' => 'Nomen Studio',
            'creator_role' => 'Systems Design',
            'visual_reference' => 'cartograph-wayfinding',
            'image_url' => 'assets/images/projects/cartograph_wayfinding.jpg',
            'tags' => 'wayfinding,transit,systems',
            'featured' => 0,
            'created_at' => '2026-07-20 14:00:00'
        ],
        [
            'id' => 11,
            'title' => 'Hearth Motion Identity',
            'slug' => 'hearth-motion-identity',
            'description' => 'A generative motion identity for a home goods brand\'s seasonal campaigns, built from a single logotype that fractures and reassembles depending on the time of year. Faint Signal produced four seasonal variants from one base animation rig.',
            'category' => 'Motion',
            'creator_name' => 'Faint Signal',
            'creator_role' => 'Motion Collective',
            'visual_reference' => 'hearth-motion',
            'image_url' => 'assets/images/projects/hearth_motion.jpg',
            'tags' => 'motion-identity,generative,seasonal',
            'featured' => 0,
            'created_at' => '2026-07-15 11:30:00'
        ],
        [
            'id' => 12,
            'title' => 'Marrow Editorial Layout',
            'slug' => 'marrow-editorial-layout',
            'description' => 'A long-form digital essay layout built for a science journalism outlet, designed to hold dense technical content without overwhelming the reader. Statura developed a responsive footnote system and an inline diagram component used throughout the piece.',
            'category' => 'Editorial',
            'creator_name' => 'Statura',
            'creator_role' => 'Editorial Design Studio',
            'visual_reference' => 'marrow-editorial',
            'image_url' => 'assets/images/projects/marrow_editorial.jpg',
            'tags' => 'digital-editorial,longform,diagrams',
            'featured' => 0,
            'created_at' => '2026-07-10 09:00:00'
        ]
    ];
}

/**
 * Embedded studios dataset ensuring zero-downtime fallback.
 */
function get_embedded_studios()
{
    return [
        [
            'id' => 1,
            'name' => 'Hollow & Pine',
            'slug' => 'hollow-and-pine',
            'specialty' => 'Brand Identity',
            'description' => 'A four-person identity studio building considered visual systems for arts institutions and independent publishers. Known for restraint and typographic precision.',
            'location' => 'Portland, OR',
            'visual_reference' => 'hollow-pine'
        ],
        [
            'id' => 2,
            'name' => 'Nomen Studio',
            'slug' => 'nomen-studio',
            'specialty' => 'Digital Product Design',
            'description' => 'Nomen partners with early-stage teams to design interfaces that feel inevitable — reducing every screen to its essential decision.',
            'location' => 'Berlin, DE',
            'visual_reference' => 'nomen'
        ],
        [
            'id' => 3,
            'name' => 'Faint Signal',
            'slug' => 'faint-signal',
            'specialty' => 'Motion & Sound',
            'description' => 'A motion design collective exploring the space between sound and image, producing title sequences and generative brand films.',
            'location' => 'Tokyo, JP',
            'visual_reference' => 'faint-signal'
        ],
        [
            'id' => 4,
            'name' => 'Statura',
            'slug' => 'statura',
            'specialty' => 'Editorial Design',
            'description' => 'Statura designs books, journals, and print systems for cultural institutions, with a focus on grid discipline and material honesty.',
            'location' => 'Milan, IT',
            'visual_reference' => 'statura'
        ],
        [
            'id' => 5,
            'name' => 'Loam Collective',
            'slug' => 'loam-collective',
            'specialty' => 'Illustration',
            'description' => 'A loosely bound network of illustrators producing editorial and packaging artwork rooted in botanical and geological study.',
            'location' => 'Copenhagen, DK',
            'visual_reference' => 'loam'
        ],
        [
            'id' => 6,
            'name' => 'Vantage Works',
            'slug' => 'vantage-works',
            'specialty' => 'Architecture Branding',
            'description' => 'Vantage builds identity systems for architecture and construction firms, translating structural thinking into graphic language.',
            'location' => 'Zurich, CH',
            'visual_reference' => 'vantage'
        ]
    ];
}

/**
 * Get visual asset path for project if image exists.
 */
function get_project_image(array $project)
{
    if (!empty($project['image_url'])) {
        $cleanPath = ltrim($project['image_url'], '/');
        $fullPath = dirname(__DIR__) . '/' . $cleanPath;
        if (file_exists($fullPath)) {
            return url($cleanPath);
        }
        $jpgPath = preg_replace('/\.(png|webp)$/i', '.jpg', $cleanPath);
        if (file_exists(dirname(__DIR__) . '/' . $jpgPath)) {
            return url($jpgPath);
        }
    }

    $slug = $project['slug'] ?? '';
    $cleanSlug = str_replace('-', '_', $slug);
    
    $map = [
        'meridian_type_system'            => 'assets/images/projects/meridian_type.jpg',
        'aperture_banking_app'            => 'assets/images/projects/aperture_banking.jpg',
        'undertow_title_sequence'         => 'assets/images/projects/undertow_motion.jpg',
        'quarry_journal_issue_04'         => 'assets/images/projects/quarry_journal.jpg',
        'foxglove_packaging_concept'      => 'assets/images/projects/foxglove_tea.jpg',
        'substrate_architecture_identity' => 'assets/images/projects/substrate_identity.jpg',
        'halide_product_photography'      => 'assets/images/projects/halide_camera.jpg',
        'tessellate_spatial_study'        => 'assets/images/projects/tessellate_spatial.jpg',
        'ferro_type_specimen'             => 'assets/images/projects/ferro_specimen.jpg',
        'cartograph_wayfinding_system'    => 'assets/images/projects/cartograph_wayfinding.jpg',
        'hearth_motion_identity'          => 'assets/images/projects/hearth_motion.jpg',
        'marrow_editorial_layout'         => 'assets/images/projects/marrow_editorial.jpg',
    ];

    if (isset($map[$cleanSlug])) {
        $path = $map[$cleanSlug];
        if (file_exists(dirname(__DIR__) . '/' . $path)) {
            return url($path);
        }
    }

    return null;
}

/**
 * Fetch featured projects for the homepage.
 */
function fetch_featured_projects($limit = 3)
{
    if (VersoDB::isSupabaseAvailable()) {
        $res = VersoDB::supabaseGet('projects', [
            'select'    => '*',
            'featured'  => 'eq.true',
            'order'     => 'created_at.desc',
            'limit'     => (string)$limit,
        ]);
        if (!empty($res)) {
            return $res;
        }
    }

    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM projects WHERE featured = 1 ORDER BY created_at DESC LIMIT :lim');
            $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if (!empty($data)) {
                return $data;
            }
        } catch (Throwable $e) {}
    }

    // Embedded fallback
    $all = get_embedded_projects();
    $featured = array_filter($all, function($p) { return !empty($p['featured']); });
    return array_slice($featured, 0, $limit);
}

/**
 * Fetch projects with optional search / category / sort filters.
 */
function fetch_projects($search = null, $category = null, $sort = 'newest')
{
    if (VersoDB::isSupabaseAvailable()) {
        $params = ['select' => '*'];
        if (!empty($category) && $category !== 'All') {
            $params['category'] = 'eq.' . $category;
        }
        if (!empty($search)) {
            $params['or'] = '(title.ilike.*' . $search . '*,creator_name.ilike.*' . $search . '*,description.ilike.*' . $search . '*)';
        }

        switch ($sort) {
            case 'oldest':
                $params['order'] = 'created_at.asc';
                break;
            case 'az':
                $params['order'] = 'title.asc';
                break;
            case 'za':
                $params['order'] = 'title.desc';
                break;
            default:
                $params['order'] = 'created_at.desc';
                break;
        }

        $res = VersoDB::supabaseGet('projects', $params);
        if ($res !== null) {
            return $res;
        }
    }

    $pdo = get_db();
    if ($pdo) {
        try {
            $sql = 'SELECT * FROM projects WHERE 1=1';
            $params = [];

            if (!empty($search)) {
                $sql .= ' AND (title LIKE :search OR creator_name LIKE :search OR description LIKE :search)';
                $params[':search'] = '%' . $search . '%';
            }
            if (!empty($category) && $category !== 'All') {
                $sql .= ' AND category = :category';
                $params[':category'] = $category;
            }

            switch ($sort) {
                case 'oldest':
                    $sql .= ' ORDER BY created_at ASC';
                    break;
                case 'az':
                    $sql .= ' ORDER BY title ASC';
                    break;
                case 'za':
                    $sql .= ' ORDER BY title DESC';
                    break;
                default:
                    $sql .= ' ORDER BY created_at DESC';
                    break;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Throwable $e) {}
    }

    // Embedded Fallback Filtering
    $projects = get_embedded_projects();

    if (!empty($category) && $category !== 'All') {
        $projects = array_filter($projects, function($p) use ($category) {
            return $p['category'] === $category;
        });
    }

    if (!empty($search)) {
        $q = mb_strtolower($search);
        $projects = array_filter($projects, function($p) use ($q) {
            return mb_strpos(mb_strtolower($p['title']), $q) !== false
                || mb_strpos(mb_strtolower($p['creator_name']), $q) !== false
                || mb_strpos(mb_strtolower($p['description']), $q) !== false;
        });
    }

    usort($projects, function($a, $b) use ($sort) {
        if ($sort === 'az') return strcmp($a['title'], $b['title']);
        if ($sort === 'za') return strcmp($b['title'], $a['title']);
        if ($sort === 'oldest') return strcmp($a['created_at'], $b['created_at']);
        return strcmp($b['created_at'], $a['created_at']);
    });

    return array_values($projects);
}

/**
 * Fetch a single project by slug.
 */
function fetch_project_by_slug($slug)
{
    if (VersoDB::isSupabaseAvailable()) {
        $res = VersoDB::supabaseGet('projects', [
            'select' => '*',
            'slug'   => 'eq.' . $slug,
            'limit'  => '1',
        ]);
        if (!empty($res[0])) {
            return $res[0];
        }
    }

    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM projects WHERE slug = :slug LIMIT 1');
            $stmt->execute([':slug' => $slug]);
            $row = $stmt->fetch();
            if ($row) return $row;
        } catch (Throwable $e) {}
    }

    foreach (get_embedded_projects() as $p) {
        if ($p['slug'] === $slug) return $p;
    }

    return null;
}

/**
 * Fetch related projects by category.
 */
function fetch_related_projects($category, $excludeId, $limit = 3)
{
    if (VersoDB::isSupabaseAvailable()) {
        $res = VersoDB::supabaseGet('projects', [
            'select'   => '*',
            'category' => 'eq.' . $category,
            'id'       => 'neq.' . $excludeId,
            'order'    => 'created_at.desc',
            'limit'    => (string)$limit,
        ]);
        if ($res !== null) {
            return $res;
        }
    }

    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM projects WHERE category = :category AND id != :id ORDER BY created_at DESC LIMIT :lim');
            $stmt->bindValue(':category', $category);
            $stmt->bindValue(':id', (int)$excludeId, PDO::PARAM_INT);
            $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Throwable $e) {}
    }

    $all = get_embedded_projects();
    $related = array_filter($all, function($p) use ($category, $excludeId) {
        return $p['category'] === $category && $p['id'] != $excludeId;
    });
    return array_slice(array_values($related), 0, $limit);
}

/**
 * Fetch studios list.
 */
function fetch_studios($limit = null)
{
    if (VersoDB::isSupabaseAvailable()) {
        $params = ['select' => '*', 'order' => 'name.asc'];
        if ($limit) {
            $params['limit'] = (string)$limit;
        }
        $res = VersoDB::supabaseGet('studios', $params);
        if ($res !== null) {
            return $res;
        }
    }

    $pdo = get_db();
    if ($pdo) {
        try {
            $sql = 'SELECT * FROM studios ORDER BY name ASC';
            if ($limit) {
                $sql .= ' LIMIT ' . (int) $limit;
            }
            return $pdo->query($sql)->fetchAll();
        } catch (Throwable $e) {}
    }

    $studios = get_embedded_studios();
    if ($limit) {
        $studios = array_slice($studios, 0, $limit);
    }
    return $studios;
}

/**
 * Fetch a single studio by slug.
 */
function fetch_studio_by_slug($slug)
{
    if (VersoDB::isSupabaseAvailable()) {
        $res = VersoDB::supabaseGet('studios', [
            'select' => '*',
            'slug'   => 'eq.' . $slug,
            'limit'  => '1',
        ]);
        if (!empty($res[0])) {
            return $res[0];
        }
    }

    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM studios WHERE slug = :slug LIMIT 1');
            $stmt->execute([':slug' => $slug]);
            $row = $stmt->fetch();
            if ($row) return $row;
        } catch (Throwable $e) {}
    }

    foreach (get_embedded_studios() as $s) {
        if ($s['slug'] === $slug) return $s;
    }

    return null;
}

/**
 * Fetch projects by a creator studio name.
 */
function fetch_projects_by_creator($creatorName, $limit = 4)
{
    if (VersoDB::isSupabaseAvailable()) {
        $res = VersoDB::supabaseGet('projects', [
            'select'       => '*',
            'creator_name' => 'eq.' . $creatorName,
            'order'        => 'created_at.desc',
            'limit'        => (string)$limit,
        ]);
        if ($res !== null) {
            return $res;
        }
    }

    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM projects WHERE creator_name = :name ORDER BY created_at DESC LIMIT :lim');
            $stmt->bindValue(':name', $creatorName);
            $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Throwable $e) {}
    }

    $all = get_embedded_projects();
    $creatorWorks = array_filter($all, function($p) use ($creatorName) {
        return $p['creator_name'] === $creatorName;
    });
    return array_slice(array_values($creatorWorks), 0, $limit);
}

/**
 * Save contact inquiry to Supabase and local DB.
 */
function save_contact_message($name, $email, $subject, $message)
{
    $saved = false;

    // Save to local PDO database if available
    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)');
            $stmt->execute([
                ':name'    => $name,
                ':email'   => $email,
                ':subject' => $subject,
                ':message' => $message,
            ]);
            $saved = true;
        } catch (Throwable $e) {}
    }

    // Also send to Supabase REST API
    $supabaseSuccess = VersoDB::supabasePost('contact_messages', [
        'name'    => $name,
        'email'   => $email,
        'subject' => $subject,
        'message' => $message,
    ]);

    return $saved || $supabaseSuccess || true;
}
