<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';

function imgPath($image, $type = 'projects')
{
    if (empty($image))
        return null;
    if (strpos($image, 'assets/') === 0)
        return '/' . $image;
    return "/assets/images/{$type}/{$image}";
}

function respond($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function respondError($msg, $code = 400)
{
    respond(['error' => $msg], $code);
}

switch ($action) {

    // ── Site settings ────────────────────────────────────────────────────────
    case 'settings':
        $s = $pdo->query("SELECT * FROM site_settings LIMIT 1")->fetch();
        respond($s ?: (object) []);

    // ── Homepage bundle ──────────────────────────────────────────────────────
    case 'home':
        $settings = $pdo->query("SELECT * FROM site_settings LIMIT 1")->fetch();

        $projects = $pdo->query(
            "SELECT p.id, p.title, p.slug, p.image, p.client_name, p.description,
                    p.pilar_build, p.pilar_rescue, p.pilar_boost,
                    c.name as category_name
             FROM projects p LEFT JOIN categories c ON p.category_id = c.id
             ORDER BY p.id DESC LIMIT 6"
        )->fetchAll();
        foreach ($projects as &$row) {
            $row['image_url'] = imgPath($row['image'], 'projects');
            $row['description_short'] = mb_substr(strip_tags(html_entity_decode($row['description'])), 0, 120);
        }

        $articles = $pdo->query(
            "SELECT a.id, a.title, a.slug, a.featured_image, a.content, a.created_at,
                    c.name as category_name
             FROM articles a LEFT JOIN categories c ON a.category_id = c.id
             ORDER BY a.id DESC LIMIT 3"
        )->fetchAll();
        foreach ($articles as &$row) {
            $row['image_url'] = imgPath($row['featured_image'], 'articles');
            $row['read_time'] = max(1, (int) (str_word_count(strip_tags($row['content'])) / 200));
            $row['excerpt'] = mb_substr(strip_tags(html_entity_decode($row['content'])), 0, 120);
            unset($row['content']);
        }

        respond(['settings' => $settings, 'projects' => $projects, 'articles' => $articles]);

    // ── Projects list (paginated + filter) ───────────────────────────────────
    case 'projects':
        $catSlug = $_GET['category'] ?? 'all';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = max(1, min(36, (int) ($_GET['limit'] ?? 9)));
        $offset = ($page - 1) * $limit;

        $where = $catSlug !== 'all' ? " WHERE c.slug = ?" : "";
        $params = $catSlug !== 'all' ? [$catSlug] : [];

        $total = $pdo->prepare("SELECT COUNT(*) FROM projects p LEFT JOIN categories c ON p.category_id = c.id" . $where);
        $total->execute($params);
        $totalCount = (int) $total->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT p.id, p.title, p.slug, p.image, p.client_name, p.description,
                    p.pilar_build, p.pilar_rescue, p.pilar_boost,
                    c.name as category_name
             FROM projects p LEFT JOIN categories c ON p.category_id = c.id
             {$where} ORDER BY p.id DESC LIMIT {$limit} OFFSET {$offset}"
        );
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['image_url'] = imgPath($row['image'], 'projects');
            $row['description_short'] = mb_substr(strip_tags(html_entity_decode($row['description'])), 0, 120);
        }

        respond([
            'data' => $rows,
            'total' => $totalCount,
            'page' => $page,
            'total_pages' => (int) ceil($totalCount / $limit),
        ]);

    // ── Single project ───────────────────────────────────────────────────────
    case 'project':
        $slug = $_GET['slug'] ?? '';
        if (!$slug)
            respondError('slug required');

        $stmt = $pdo->prepare(
            "SELECT p.*, c.name as category_name
             FROM projects p LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.slug = ? LIMIT 1"
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if (!$row)
            respondError('Not found', 404);

        $row['image_url'] = imgPath($row['image'], 'projects');

        // related projects
        $rel = $pdo->prepare(
            "SELECT id, title, slug, image, client_name FROM projects
             WHERE id != ? ORDER BY id DESC LIMIT 3"
        );
        $rel->execute([$row['id']]);
        $related = $rel->fetchAll();
        foreach ($related as &$r) {
            $r['image_url'] = imgPath($r['image'], 'projects');
        }

        respond(['project' => $row, 'related' => $related]);

    // ── Articles list (paginated + filter) ───────────────────────────────────
    case 'articles':
        $catSlug = $_GET['category'] ?? 'all';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = max(1, min(36, (int) ($_GET['limit'] ?? 9)));
        $offset = ($page - 1) * $limit;

        $where = $catSlug !== 'all' ? " WHERE c.slug = ?" : "";
        $params = $catSlug !== 'all' ? [$catSlug] : [];

        $total = $pdo->prepare("SELECT COUNT(*) FROM articles a LEFT JOIN categories c ON a.category_id = c.id" . $where);
        $total->execute($params);
        $totalCount = (int) $total->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT a.id, a.title, a.slug, a.featured_image, a.content, a.created_at,
                    c.name as category_name
             FROM articles a LEFT JOIN categories c ON a.category_id = c.id
             {$where} ORDER BY a.id DESC LIMIT {$limit} OFFSET {$offset}"
        );
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['image_url'] = imgPath($row['featured_image'], 'articles');
            $row['read_time'] = max(1, (int) (str_word_count(strip_tags($row['content'])) / 200));
            $row['excerpt'] = mb_substr(strip_tags(html_entity_decode($row['content'])), 0, 120);
            unset($row['content']);
        }

        respond([
            'data' => $rows,
            'total' => $totalCount,
            'page' => $page,
            'total_pages' => (int) ceil($totalCount / $limit),
        ]);

    // ── Single article ───────────────────────────────────────────────────────
    case 'article':
        $slug = $_GET['slug'] ?? '';
        if (!$slug)
            respondError('slug required');

        $stmt = $pdo->prepare(
            "SELECT a.*, c.name as category_name
             FROM articles a LEFT JOIN categories c ON a.category_id = c.id
             WHERE a.slug = ? LIMIT 1"
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if (!$row)
            respondError('Not found', 404);

        $row['image_url'] = imgPath($row['featured_image'], 'articles');
        $row['read_time'] = max(1, (int) (str_word_count(strip_tags($row['content'])) / 200));

        $rel = $pdo->prepare(
            "SELECT id, title, slug, featured_image, created_at FROM articles
             WHERE id != ? ORDER BY id DESC LIMIT 3"
        );
        $rel->execute([$row['id']]);
        $related = $rel->fetchAll();
        foreach ($related as &$r) {
            $r['image_url'] = imgPath($r['featured_image'], 'articles');
        }

        respond(['article' => $row, 'related' => $related]);

    // ── Categories ───────────────────────────────────────────────────────────
    case 'categories':
        $rows = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
        respond($rows);

    // ── Pilar bundle ─────────────────────────────────────────────────────────
    case 'pilar':
        $name = strtolower($_GET['name'] ?? '');
        $allowed = ['build', 'rescue', 'boost'];
        if (!in_array($name, $allowed))
            respondError('invalid pilar');

        $col = "pilar_{$name}";
        $projects = $pdo->query(
            "SELECT p.id, p.title, p.slug, p.image, p.client_name, p.description,
                    c.name as category_name
             FROM projects p LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.{$col} = 1 ORDER BY p.id DESC LIMIT 6"
        )->fetchAll();
        foreach ($projects as &$row) {
            $row['image_url'] = imgPath($row['image'], 'projects');
            $row['description_short'] = mb_substr(strip_tags(html_entity_decode($row['description'])), 0, 120);
        }

        $articles = $pdo->query(
            "SELECT a.id, a.title, a.slug, a.featured_image, a.content, a.created_at,
                    c.name as category_name
             FROM articles a LEFT JOIN categories c ON a.category_id = c.id
             WHERE a.{$col} = 1 ORDER BY a.id DESC LIMIT 3"
        )->fetchAll();
        foreach ($articles as &$row) {
            $row['image_url'] = imgPath($row['featured_image'], 'articles');
            $row['read_time'] = max(1, (int) (str_word_count(strip_tags($row['content'])) / 200));
            $row['excerpt'] = mb_substr(strip_tags(html_entity_decode($row['content'])), 0, 120);
            unset($row['content']);
        }

        respond(['projects' => $projects, 'articles' => $articles]);

    // ── About page ───────────────────────────────────────────────────────────
    case 'about':
        // Auto-create tables if they don't exist
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS about_page (
                id INT AUTO_INCREMENT PRIMARY KEY,
                headline VARCHAR(255) DEFAULT 'Tentang INSPIMA',
                tagline VARCHAR(1000) DEFAULT '',
                description TEXT,
                mission TEXT,
                vision TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $pdo->exec("CREATE TABLE IF NOT EXISTS team_members (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                role VARCHAR(255),
                bio TEXT,
                photo VARCHAR(255),
                linkedin_url VARCHAR(500),
                sort_order INT DEFAULT 0,
                is_active TINYINT(1) DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        } catch (Exception $e) {
        }

        $about = $pdo->query("SELECT * FROM about_page LIMIT 1")->fetch();
        $team = $pdo->query("SELECT * FROM team_members WHERE is_active=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
        foreach ($team as &$m) {
            $m['photo_url'] = !empty($m['photo']) ? imgPath($m['photo'], 'team') : null;
        }
        respond(['about' => $about ?: (object) [], 'team' => $team]);

    // ── Contact form ─────────────────────────────────────────────────────────
    case 'contact':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            respondError('POST required', 405);
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $name = trim($body['name'] ?? '');
        $email = trim($body['email'] ?? '');
        $message = trim($body['message'] ?? '');
        $phone = trim($body['phone'] ?? '');
        $service = trim($body['service'] ?? '');

        if (!$name || !$email || !$message)
            respondError('name, email, message required');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            respondError('invalid email');

        // Auto-create table if doesn't exist
        try {

            $stmt = $pdo->prepare(
                "INSERT INTO contacts (name, email, phone, service, message, created_at)
                 VALUES (?, ?, ?, ?, ?, NOW())"
            );
            $stmt->execute([$name, $email, $phone, $service, $message]);
        } catch (Exception $e) {
            // Silently ignore if something goes wrong with DB but continue to email
        }

        // ── Kirim Email ke hello@inspima.id ──
        $to = "hello@inspima.id";
        $subject = "[New Contact] " . $name . " - " . ($service ?: 'General Inquiry');

        $content = "Detail Pesan Baru:\n";
        $content .= "----------------------------------\n";
        $content .= "Nama    : " . $name . "\n";
        $content .= "Email   : " . $email . "\n";
        $content .= "Telepon : " . ($phone ?: '-') . "\n";
        $content .= "Layanan : " . ($service ?: '-') . "\n\n";
        $content .= "Pesan   :\n" . $message . "\n";
        $content .= "----------------------------------\n";
        $content .= "Dikirim pada: " . date("Y-m-d H:i:s") . "\n";

        $headers = "From: noreply@inspima.id\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        @mail($to, $subject, $content, $headers);

        respond(['success' => true, 'message' => 'Pesan berhasil dikirim!']);

    default:
        respondError('Unknown action', 404);
}
