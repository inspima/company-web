<?php
/**
 * Dynamic XML Sitemap Generator for INSPIMA
 * Outputs a valid XML sitemap using clean URLs (no hash routing).
 * All routes are handled by .htaccess → index.html SPA fallback.
 *
 * URL: https://inspima.id/sitemap.php
 */

require_once __DIR__ . '/db.php';

// ── Configuration ─────────────────────────────────────────────────────────────
$base_url = 'https://inspima.id';

// Detect scheme + host dynamically as fallback
if (php_sapi_name() !== 'cli') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'inspima.id';
    $base_url = $scheme . '://' . $host;
}

// ── Output XML header ─────────────────────────────────────────────────────────
header('Content-Type: application/xml; charset=utf-8');

// ── Helper: format date to W3C Datetime ───────────────────────────────────────
function w3cDate($datetime)
{
    if (empty($datetime))
        return date('Y-m-d');
    return date('Y-m-d', strtotime($datetime));
}

// ── Helper: XML-safe string ────────────────────────────────────────────────────
function xmlEsc($str)
{
    return htmlspecialchars($str, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

// ── Collect all URLs ──────────────────────────────────────────────────────────
$urls = [];

// Fetch latest dates for dynamic index pages
$latest_project_date = date('Y-m-d');
$latest_article_date = date('Y-m-d');

try {
    $lp = $pdo->query("SELECT MAX(COALESCE(updated_at, created_at)) FROM projects")->fetchColumn();
    if ($lp)
        $latest_project_date = w3cDate($lp);

    $la = $pdo->query("SELECT MAX(COALESCE(updated_at, created_at)) FROM articles")->fetchColumn();
    if ($la)
        $latest_article_date = w3cDate($la);
} catch (Exception $e) {
}

// 1. Static pages — clean URLs (no hash, fully indexable by Google)
$static_pages = [
    ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'weekly', 'lastmod' => max($latest_project_date, $latest_article_date)],
    ['loc' => '/build', 'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => $latest_project_date],
    ['loc' => '/rescue', 'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => $latest_project_date],
    ['loc' => '/boost', 'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => $latest_project_date],
    ['loc' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
    ['loc' => '/projects', 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => $latest_project_date],
    ['loc' => '/blog', 'priority' => '0.8', 'changefreq' => 'daily', 'lastmod' => $latest_article_date],
    ['loc' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
];

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
            'loc' => '/project/' . $p['slug'],
            'priority' => '0.7',
            'changefreq' => 'monthly',
            'lastmod' => w3cDate($lastmod),
        ];
    }
} catch (Exception $e) {
}

// 3. Articles (blog posts)
try {
    $articles = $pdo->query(
        "SELECT slug, updated_at, created_at FROM articles ORDER BY id DESC"
    )->fetchAll();

    foreach ($articles as $a) {
        $lastmod = !empty($a['updated_at']) ? $a['updated_at'] : ($a['created_at'] ?? null);
        $urls[] = [
            'loc' => '/article/' . $a['slug'],
            'priority' => '0.6',
            'changefreq' => 'monthly',
            'lastmod' => w3cDate($lastmod),
        ];
    }
} catch (Exception $e) {
}

// 4. Category pages
try {
    $categories = $pdo->query(
        "SELECT slug FROM categories ORDER BY name ASC"
    )->fetchAll();

    foreach ($categories as $cat) {
        $urls[] = [
            'loc' => '/blog?category=' . urlencode($cat['slug']),
            'priority' => '0.5',
            'changefreq' => 'weekly',
            'lastmod' => $latest_article_date,
        ];
    }
} catch (Exception $e) {
}

// ── Output XML ────────────────────────────────────────────────────────────────
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

foreach ($urls as $url) {
    echo "  <url>\n";
    echo "    <loc>" . xmlEsc($base_url . $url['loc']) . "</loc>\n";
    echo "    <lastmod>" . xmlEsc($url['lastmod']) . "</lastmod>\n";
    echo "    <changefreq>" . xmlEsc($url['changefreq']) . "</changefreq>\n";
    echo "    <priority>" . xmlEsc($url['priority']) . "</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
