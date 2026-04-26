<?php
/**
 * Dynamic Sitemap Generator — INSPIMA
 * 
 * Scans all content (static pages, projects, articles) from the database
 * and outputs a valid XML sitemap for Google Search Console indexing.
 * 
 * URL: https://inspima.id/sitemap.php
 */

require_once __DIR__ . '/db.php';

// ── Configuration ─────────────────────────────────────────────────────────────
$base_url = 'https://inspima.id';

// Detect scheme + host dynamically as fallback
if (php_sapi_name() !== 'cli') {
    $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'inspima.id';
    $base_url = $scheme . '://' . $host;
}

// ── Output XML header ─────────────────────────────────────────────────────────
header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex'); // sitemap file itself shouldn't be indexed

// ── Helper: format date to W3C Datetime ───────────────────────────────────────
function w3cDate($datetime) {
    if (empty($datetime)) return date('Y-m-d');
    return date('Y-m-d', strtotime($datetime));
}

// ── Helper: XML-safe string ────────────────────────────────────────────────────
function xmlEsc($str) {
    return htmlspecialchars($str, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

// ── Collect all URLs ──────────────────────────────────────────────────────────
$urls = [];

// 1. Static / SPA-routed pages
//    These are hash-routed (#/) but we register the canonical base paths
//    using the static_pages table if it exists, otherwise fall back to a
//    hard-coded list.
$static_pages = [
    ['loc' => '/',         'priority' => '1.0', 'changefreq' => 'weekly',  'lastmod' => date('Y-m-d')],
    ['loc' => '/build',    'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
    ['loc' => '/rescue',   'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
    ['loc' => '/boost',    'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
    ['loc' => '/about',    'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
    ['loc' => '/projects', 'priority' => '0.8', 'changefreq' => 'weekly',  'lastmod' => date('Y-m-d')],
    ['loc' => '/blog',     'priority' => '0.8', 'changefreq' => 'daily',   'lastmod' => date('Y-m-d')],
    ['loc' => '/contact',  'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
];

// Try to enrich lastmod from static_pages table
try {
    $sp_rows = $pdo->query("SELECT slug, updated_at FROM static_pages")->fetchAll();
    $sp_map  = [];
    foreach ($sp_rows as $r) {
        $slug = $r['slug'] === 'home' ? '/' : '/' . $r['slug'];
        $sp_map[$slug] = $r['updated_at'];
    }
    foreach ($static_pages as &$sp) {
        if (!empty($sp_map[$sp['loc']])) {
            $sp['lastmod'] = w3cDate($sp_map[$sp['loc']]);
        }
    }
    unset($sp);
} catch (Exception $e) {
    // static_pages table missing — use defaults above
}

foreach ($static_pages as $sp) {
    $urls[] = $sp;
}

// 2. Projects (portfolio case studies)
try {
    $projects = $pdo->query(
        "SELECT slug, updated_at, created_at FROM projects ORDER BY id DESC"
    )->fetchAll();

    foreach ($projects as $p) {
        $lastmod = !empty($p['updated_at']) ? $p['updated_at'] : ($p['created_at'] ?? null);
        $urls[] = [
            'loc'        => '/project/' . $p['slug'],
            'priority'   => '0.7',
            'changefreq' => 'monthly',
            'lastmod'    => w3cDate($lastmod),
        ];
    }
} catch (Exception $e) {
    // projects table missing — skip
}

// 3. Articles (blog posts)
try {
    $articles = $pdo->query(
        "SELECT slug, updated_at, created_at FROM articles ORDER BY id DESC"
    )->fetchAll();

    foreach ($articles as $a) {
        $lastmod = !empty($a['updated_at']) ? $a['updated_at'] : ($a['created_at'] ?? null);
        $urls[] = [
            'loc'        => '/article/' . $a['slug'],
            'priority'   => '0.6',
            'changefreq' => 'monthly',
            'lastmod'    => w3cDate($lastmod),
        ];
    }
} catch (Exception $e) {
    // articles table missing — skip
}

// 4. Category pages (blog filtered by category)
try {
    $categories = $pdo->query(
        "SELECT slug FROM categories ORDER BY name ASC"
    )->fetchAll();

    foreach ($categories as $cat) {
        $urls[] = [
            'loc'        => '/blog?category=' . urlencode($cat['slug']),
            'priority'   => '0.5',
            'changefreq' => 'weekly',
            'lastmod'    => date('Y-m-d'),
        ];
    }
} catch (Exception $e) {
    // categories table missing — skip
}

// ── Output XML ────────────────────────────────────────────────────────────────
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php foreach ($urls as $url): ?>
    <url>
        <loc><?= xmlEsc($base_url . $url['loc']) ?></loc>
        <lastmod><?= xmlEsc($url['lastmod']) ?></lastmod>
        <changefreq><?= xmlEsc($url['changefreq']) ?></changefreq>
        <priority><?= xmlEsc($url['priority']) ?></priority>
    </url>
<?php endforeach; ?>
</urlset>
