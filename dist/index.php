<?php
/**
 * INSPIMA — PHP Entry Point (SPA + Dynamic SEO)
 *
 * Membaca URL path → query SEO meta dari DB → inject ke <head>
 * sehingga Googlebot dan SEO analyzer mendapat meta yang benar
 * tanpa perlu menjalankan JavaScript.
 */

require_once __DIR__ . '/db.php';

// ── Deteksi path & query string ───────────────────────────────────────────────
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path       = parse_url($requestUri, PHP_URL_PATH);
$path       = rtrim($path, '/') ?: '/';
$segments   = array_values(array_filter(explode('/', $path)));
$seg0       = $segments[0] ?? '';
$seg1       = $segments[1] ?? '';

$baseUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
         . '://' . ($_SERVER['HTTP_HOST'] ?? 'inspima.id');

$canonicalUrl = $baseUrl . $requestUri;

// ── Default SEO fallback ──────────────────────────────────────────────────────
$seo = [
    'title'       => 'INSPIMA - Build, Rescue, Boost',
    'description' => 'INSPIMA - Integrated Technology Solutions. Kami Build, Rescue & Boost produk digital Anda.',
    'keywords'    => 'software house, web development, aplikasi mobile, IT solutions, Surabaya',
    'og_image'    => $baseUrl . '/assets/images/og-default.jpg',
    'og_type'     => 'website',
];

// ── Helper: ambil data static_pages ──────────────────────────────────────────
function getPageSeo($pdo, $slug) {
    try {
        $stmt = $pdo->prepare("SELECT meta_title, meta_desc, keyphrase, secondary_keyphrase FROM static_pages WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: [];
    } catch (Exception $e) { return []; }
}

// ── Route matching → SEO data ─────────────────────────────────────────────────
if ($path === '/' || $seg0 === '') {
    // Home
    $row = getPageSeo($pdo, 'home');

} elseif (in_array($seg0, ['build','rescue','boost','projects','blog','contact','about']) && !$seg1) {
    // Static pages
    $row = getPageSeo($pdo, $seg0);

} elseif ($seg0 === 'project' && $seg1) {
    // Project detail — ambil dari tabel projects
    try {
        $stmt = $pdo->prepare(
            "SELECT p.title, p.description, p.image, c.name as category_name
             FROM projects p LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.slug = ? LIMIT 1"
        );
        $stmt->execute([$seg1]);
        $proj = $stmt->fetch();
        if ($proj) {
            $desc = mb_substr(strip_tags(html_entity_decode($proj['description'] ?? '')), 0, 155);
            $seo['title']       = $proj['title'] . ' — INSPIMA Portfolio';
            $seo['description'] = $desc ?: $seo['description'];
            $seo['keywords']    = trim(($proj['category_name'] ?? '') . ', portfolio, INSPIMA, software house');
            $seo['og_type']     = 'article';
            if (!empty($proj['image'])) {
                $img = strpos($proj['image'], 'assets/') === 0
                    ? '/' . $proj['image']
                    : '/assets/images/projects/' . $proj['image'];
                $seo['og_image'] = $baseUrl . $img;
            }
        }
    } catch (Exception $e) {}
    $row = [];

} elseif ($seg0 === 'article' && $seg1) {
    // Article detail — ambil dari tabel articles
    try {
        $stmt = $pdo->prepare(
            "SELECT a.title, a.content, a.featured_image, c.name as category_name
             FROM articles a LEFT JOIN categories c ON a.category_id = c.id
             WHERE a.slug = ? LIMIT 1"
        );
        $stmt->execute([$seg1]);
        $art = $stmt->fetch();
        if ($art) {
            $desc = mb_substr(strip_tags(html_entity_decode($art['content'] ?? '')), 0, 155);
            $seo['title']       = $art['title'] . ' — INSPIMA Insights';
            $seo['description'] = $desc ?: $seo['description'];
            $seo['keywords']    = trim(($art['category_name'] ?? '') . ', blog, insights, INSPIMA');
            $seo['og_type']     = 'article';
            if (!empty($art['featured_image'])) {
                $img = strpos($art['featured_image'], 'assets/') === 0
                    ? '/' . $art['featured_image']
                    : '/assets/images/articles/' . $art['featured_image'];
                $seo['og_image'] = $baseUrl . $img;
            }
        }
    } catch (Exception $e) {}
    $row = [];

} else {
    $row = [];
}

// ── Terapkan data dari static_pages jika ada ─────────────────────────────────
if (!empty($row)) {
    if (!empty($row['meta_title']))  $seo['title']       = $row['meta_title'];
    if (!empty($row['meta_desc']))   $seo['description'] = $row['meta_desc'];

    $kw = array_filter([$row['keyphrase'] ?? '', $row['secondary_keyphrase'] ?? '']);
    if ($kw) $seo['keywords'] = implode(', ', $kw);
}

// ── Helper: HTML escape ───────────────────────────────────────────────────────
function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); }

// ── Baca template Vite (dist/vite.html) ───────────────────────────────────────
$vitePath = __DIR__ . '/dist/vite.html';
$html = file_exists($vitePath) ? file_get_contents($vitePath) : '';

// ── Inject SEO meta: ganti placeholder statis di template ────────────────────

// 1. Ganti <title>
$html = preg_replace(
    '/<title>[^<]*<\/title>/',
    '<title>' . e($seo['title']) . '</title>',
    $html, 1
);

// 2. Ganti <meta name="description">
$html = preg_replace(
    '/<meta\s+name=["\']description["\'][^>]*>/i',
    '<meta name="description" content="' . e($seo['description']) . '" />',
    $html, 1
);

// 3. Build blok meta SEO tambahan (OG, Twitter, keywords, canonical)
$extraMeta = '
  <!-- SEO Dynamic Meta -->
  <meta name="keywords" content="' . e($seo['keywords']) . '" />
  <link rel="canonical" href="' . e($canonicalUrl) . '" />
  <!-- Open Graph -->
  <meta property="og:type" content="' . e($seo['og_type']) . '" />
  <meta property="og:title" content="' . e($seo['title']) . '" />
  <meta property="og:description" content="' . e($seo['description']) . '" />
  <meta property="og:url" content="' . e($canonicalUrl) . '" />
  <meta property="og:image" content="' . e($seo['og_image']) . '" />
  <meta property="og:site_name" content="INSPIMA" />
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="' . e($seo['title']) . '" />
  <meta name="twitter:description" content="' . e($seo['description']) . '" />
  <meta name="twitter:image" content="' . e($seo['og_image']) . '" />';

// 4. Sisipkan sebelum </head>
$html = str_replace('</head>', $extraMeta . "\n</head>", $html);

// ── Output ────────────────────────────────────────────────────────────────────
header('Content-Type: text/html; charset=utf-8');
echo $html;
