<?php
require_once __DIR__ . '/db.php';

/**
 * Generate a portable URL respecting current deployment base path.
 */
function url(string $path = ''): string
{
    $base = rtrim(BASE_URL, '/');
    $path = '/' . ltrim($path, '/');
    return ($base === '' ? $path : $base . $path);
}

/**
 * Escape output for safe HTML rendering.
 */
function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a URL-safe slug from a string.
 */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Return / create a CSRF token stored in the session.
 */
function csrf_token(): string
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
function csrf_verify(?string $token): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * All available project categories, in display order.
 */
function get_categories(): array
{
    return ['Branding', 'UI/UX', 'Editorial', 'Motion', 'Typography', 'Illustration', 'Photography', '3D'];
}

/**
 * Deterministically generate a two-stop gradient + accent shape based on a string seed.
 * Used for CSS visual artwork / placeholders.
 */
function visual_palette(string $seed): array
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
 * Get visual asset path for project if image exists.
 */
function get_project_image(array $project): ?string
{
    if (!empty($project['image_url'])) {
        $cleanPath = ltrim($project['image_url'], '/');
        $fullPath = dirname(__DIR__) . '/' . $cleanPath;
        if (file_exists($fullPath)) {
            return url($cleanPath);
        }
        // Check .jpg version
        $jpgPath = preg_replace('/\.(png|webp)$/i', '.jpg', $cleanPath);
        if (file_exists(dirname(__DIR__) . '/' . $jpgPath)) {
            return url($jpgPath);
        }
    }

    // Slug-based mapping for generated project imagery
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
function fetch_featured_projects(int $limit = 3): array
{
    if (VersoDB::isSupabaseAvailable()) {
        $res = VersoDB::supabaseGet('projects', [
            'select'    => '*',
            'featured'  => 'eq.true',
            'order'     => 'created_at.desc',
            'limit'     => (string)$limit,
        ]);
        if ($res !== null) {
            return $res;
        }
    }

    $stmt = get_db()->prepare('SELECT * FROM projects WHERE featured = 1 ORDER BY created_at DESC LIMIT :lim');
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Fetch projects with optional search / category / sort filters.
 */
function fetch_projects(?string $search = null, ?string $category = null, string $sort = 'newest'): array
{
    if (VersoDB::isSupabaseAvailable()) {
        $params = ['select' => '*'];
        if (!empty($category) && $category !== 'All') {
            $params['category'] = 'eq.' . $category;
        }
        if (!empty($search)) {
            $params['or'] = '(title.ilike.*' . $search . '*,creator_name.ilike.*' . $search . '*,description.ilike.*' . $search . '*)';
        }

        $params['order'] = match ($sort) {
            'oldest' => 'created_at.asc',
            'az'     => 'title.asc',
            'za'     => 'title.desc',
            default  => 'created_at.desc',
        };

        $res = VersoDB::supabaseGet('projects', $params);
        if ($res !== null) {
            return $res;
        }
    }

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

    $sql .= match ($sort) {
        'oldest' => ' ORDER BY created_at ASC',
        'az'     => ' ORDER BY title ASC',
        'za'     => ' ORDER BY title DESC',
        default  => ' ORDER BY created_at DESC',
    };

    $stmt = get_db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Fetch a single project by slug.
 */
function fetch_project_by_slug(string $slug): ?array
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

    $stmt = get_db()->prepare('SELECT * FROM projects WHERE slug = :slug LIMIT 1');
    $stmt->execute([':slug' => $slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Fetch related projects by category.
 */
function fetch_related_projects(string $category, int $excludeId, int $limit = 3): array
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

    $stmt = get_db()->prepare('SELECT * FROM projects WHERE category = :category AND id != :id ORDER BY created_at DESC LIMIT :lim');
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Fetch studios list.
 */
function fetch_studios(?int $limit = null): array
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

    $sql = 'SELECT * FROM studios ORDER BY name ASC';
    if ($limit) {
        $sql .= ' LIMIT ' . (int) $limit;
    }
    return get_db()->query($sql)->fetchAll();
}

/**
 * Fetch a single studio by slug.
 */
function fetch_studio_by_slug(string $slug): ?array
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

    $stmt = get_db()->prepare('SELECT * FROM studios WHERE slug = :slug LIMIT 1');
    $stmt->execute([':slug' => $slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Fetch projects by a creator studio name.
 */
function fetch_projects_by_creator(string $creatorName, int $limit = 4): array
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

    $stmt = get_db()->prepare('SELECT * FROM projects WHERE creator_name = :name ORDER BY created_at DESC LIMIT :lim');
    $stmt->bindValue(':name', $creatorName);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Save contact inquiry to Supabase and local DB.
 */
function save_contact_message(string $name, string $email, string $subject, string $message): bool
{
    $saved = false;

    // Save to local PDO database
    try {
        $stmt = get_db()->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)');
        $stmt->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':subject' => $subject,
            ':message' => $message,
        ]);
        $saved = true;
    } catch (Throwable $e) {
        // Fallback continues
    }

    // Also send to Supabase REST API
    $supabaseSuccess = VersoDB::supabasePost('contact_messages', [
        'name'    => $name,
        'email'   => $email,
        'subject' => $subject,
        'message' => $message,
    ]);

    return $saved || $supabaseSuccess;
}
