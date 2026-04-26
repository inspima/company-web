<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: /admin/login.php"); exit; }
require_once '../db.php';

// Auto-create tables
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
} catch (Exception $e) {}

$msg = '';
$msgType = 'success';
$tab = $_GET['tab'] ?? 'content';

// ── Handle POST actions ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? '';

    // Save main about content
    if ($action === 'save_about') {
        $headline    = trim($_POST['headline']    ?? '');
        $tagline     = trim($_POST['tagline']     ?? '');
        $description = trim($_POST['description'] ?? '');
        $mission     = trim($_POST['mission']     ?? '');
        $vision      = trim($_POST['vision']      ?? '');

        $existing = $pdo->query("SELECT id FROM about_page LIMIT 1")->fetch();
        if ($existing) {
            $stmt = $pdo->prepare("UPDATE about_page SET headline=?, tagline=?, description=?, mission=?, vision=? WHERE id=?");
            $stmt->execute([$headline, $tagline, $description, $mission, $vision, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO about_page (headline, tagline, description, mission, vision) VALUES (?,?,?,?,?)");
            $stmt->execute([$headline, $tagline, $description, $mission, $vision]);
        }
        $msg = 'Konten halaman Tentang berhasil disimpan.';
        $tab = 'content';
    }

    // Add team member
    if ($action === 'add_member') {
        $name         = trim($_POST['name']         ?? '');
        $role         = trim($_POST['role']         ?? '');
        $bio          = trim($_POST['bio']          ?? '');
        $linkedin_url = trim($_POST['linkedin_url'] ?? '');
        $sort_order   = (int)($_POST['sort_order']  ?? 0);

        if (!$name) { $msg = 'Nama anggota tim wajib diisi.'; $msgType = 'error'; $tab = 'team'; }
        else {
            // Handle photo upload
            $photoName = '';
            if (!empty($_FILES['photo']['name'])) {
                $ext      = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $allowed  = ['jpg','jpeg','png','webp'];
                if (!in_array($ext, $allowed)) {
                    $msg = 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.';
                    $msgType = 'error';
                } else {
                    $dir = '../assets/images/team/';
                    if (!is_dir($dir)) mkdir($dir, 0755, true);
                    $photoName = uniqid('team_') . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], $dir . $photoName);
                }
            }

            if ($msgType !== 'error') {
                $stmt = $pdo->prepare("INSERT INTO team_members (name, role, bio, photo, linkedin_url, sort_order) VALUES (?,?,?,?,?,?)");
                $stmt->execute([$name, $role, $bio, $photoName, $linkedin_url, $sort_order]);
                $msg = "Anggota tim \"{$name}\" berhasil ditambahkan.";
            }
            $tab = 'team';
        }
    }

    // Update team member
    if ($action === 'update_member') {
        $id           = (int)($_POST['id'] ?? 0);
        $name         = trim($_POST['name']         ?? '');
        $role         = trim($_POST['role']         ?? '');
        $bio          = trim($_POST['bio']          ?? '');
        $linkedin_url = trim($_POST['linkedin_url'] ?? '');
        $sort_order   = (int)($_POST['sort_order']  ?? 0);
        $is_active    = isset($_POST['is_active']) ? 1 : 0;

        // Handle photo upload
        $photoClause = '';
        $params      = [$name, $role, $bio, $linkedin_url, $sort_order, $is_active];
        if (!empty($_FILES['photo']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed)) {
                $dir = '../assets/images/team/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $photoName = uniqid('team_') . '.' . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], $dir . $photoName);
                $photoClause = ', photo=?';
                $params[]    = $photoName;
            }
        }
        $params[] = $id;
        $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, bio=?, linkedin_url=?, sort_order=?, is_active=?{$photoClause} WHERE id=?");
        $stmt->execute($params);
        $msg = 'Data anggota tim berhasil diperbarui.';
        $tab = 'team';
    }

    // Delete team member
    if ($action === 'delete_member') {
        $id = (int)($_POST['id'] ?? 0);
        $member = $pdo->prepare("SELECT name FROM team_members WHERE id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM team_members WHERE id=?")->execute([$id]);
        $msg = 'Anggota tim berhasil dihapus.';
        $tab = 'team';
    }
}

// ── Fetch data ─────────────────────────────────────────────────────────────
$about   = $pdo->query("SELECT * FROM about_page LIMIT 1")->fetch() ?: [];
$members = $pdo->query("SELECT * FROM team_members ORDER BY sort_order ASC, id ASC")->fetchAll();
$editId  = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editMember = $editId ? ($pdo->prepare("SELECT * FROM team_members WHERE id=?") ? null : null) : null;
if ($editId) {
    $s = $pdo->prepare("SELECT * FROM team_members WHERE id=?");
    $s->execute([$editId]);
    $editMember = $s->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami — INSPIMA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/favicon.png" />
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 flex h-screen overflow-hidden">
    <?php include '_sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
            <div>
                <h2 class="text-base font-bold text-slate-800">Halaman Tentang Kami</h2>
                <p class="text-xs text-slate-400">Edit konten dan tim untuk halaman /about</p>
            </div>
            <a href="/#/about" target="_blank"
               class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-8">

            <?php if ($msg): ?>
            <div class="flex items-center gap-2.5 px-5 py-3.5 rounded-xl mb-6 text-sm
                <?= $msgType === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-emerald-50 border border-emerald-200 text-emerald-700' ?>">
                <i class="fa-solid <?= $msgType === 'error' ? 'fa-triangle-exclamation' : 'fa-circle-check' ?>"></i>
                <?= htmlspecialchars($msg) ?>
            </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="flex gap-2 mb-8 border-b border-slate-200 pb-0">
                <a href="?tab=content"
                   class="px-5 py-3 text-sm font-bold transition-all border-b-2 -mb-px
                          <?= $tab === 'content' ? 'text-blue-600 border-blue-600' : 'text-slate-400 border-transparent hover:text-slate-700' ?>">
                    <i class="fa-solid fa-file-lines mr-1.5"></i>Konten Halaman
                </a>
                <a href="?tab=team"
                   class="px-5 py-3 text-sm font-bold transition-all border-b-2 -mb-px
                          <?= $tab === 'team' ? 'text-blue-600 border-blue-600' : 'text-slate-400 border-transparent hover:text-slate-700' ?>">
                    <i class="fa-solid fa-people-group mr-1.5"></i>Tim Kami
                    <?php if (count($members)): ?>
                        <span class="ml-1 bg-slate-200 text-slate-600 text-[10px] font-black px-1.5 py-0.5 rounded-full"><?= count($members) ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <?php if ($tab === 'content'): ?>
            <!-- ── CONTENT TAB ───────────────────────────────────────────── -->
            <form method="POST" class="max-w-3xl space-y-6" enctype="multipart/form-data">
                <input type="hidden" name="_action" value="save_about">

                <!-- Headline & Tagline -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-700 text-sm mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-heading text-blue-500"></i> Headline & Tagline
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Headline Utama</label>
                            <input type="text" name="headline" value="<?= htmlspecialchars($about['headline'] ?? 'Tentang INSPIMA') ?>"
                                placeholder="Tentang INSPIMA"
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tagline / Subtitle</label>
                            <textarea name="tagline" rows="2"
                                placeholder="Kalimat singkat yang mendeskripsikan perusahaan..."
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none"><?= htmlspecialchars($about['tagline'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-700 text-sm mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-align-left text-blue-500"></i> Deskripsi Perusahaan
                    </h3>
                    <div class="mb-2 text-xs text-slate-400">Mendukung HTML dasar: &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;&lt;li&gt;</div>
                    <textarea name="description" rows="8"
                        placeholder="<p>Ceritakan sejarah dan latar belakang perusahaan...</p>"
                        class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-y"><?= htmlspecialchars($about['description'] ?? '') ?></textarea>
                </div>

                <!-- Mission & Vision -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-700 text-sm mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-bullseye text-blue-500"></i> Misi & Visi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                <i class="fa-solid fa-compass text-blue-400 mr-1"></i>Misi
                            </label>
                            <textarea name="mission" rows="4"
                                placeholder="Apa yang ingin dicapai perusahaan saat ini..."
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none"><?= htmlspecialchars($about['mission'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                <i class="fa-solid fa-eye text-blue-400 mr-1"></i>Visi
                            </label>
                            <textarea name="vision" rows="4"
                                placeholder="Gambaran masa depan yang ingin dicapai..."
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none"><?= htmlspecialchars($about['vision'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-sm hover:-translate-y-0.5 transition-all border-0">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

            <?php else: ?>
            <!-- ── TEAM TAB ──────────────────────────────────────────────── -->
            <div class="max-w-5xl">

                <!-- Add / Edit form -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
                    <h3 class="font-bold text-slate-700 text-sm mb-5 flex items-center gap-2">
                        <i class="fa-solid <?= $editMember ? 'fa-pen-to-square' : 'fa-user-plus' ?> text-blue-500"></i>
                        <?= $editMember ? 'Edit Anggota Tim' : 'Tambah Anggota Tim Baru' ?>
                    </h3>
                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="_action" value="<?= $editMember ? 'update_member' : 'add_member' ?>">
                        <?php if ($editMember): ?>
                            <input type="hidden" name="id" value="<?= $editMember['id'] ?>">
                        <?php endif; ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama *</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($editMember['name'] ?? '') ?>"
                                    placeholder="Nama lengkap" required
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jabatan / Role</label>
                                <input type="text" name="role" value="<?= htmlspecialchars($editMember['role'] ?? '') ?>"
                                    placeholder="e.g. Lead Developer, UI/UX Designer"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bio Singkat</label>
                            <textarea name="bio" rows="3"
                                placeholder="Deskripsi singkat tentang anggota tim..."
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none"><?= htmlspecialchars($editMember['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto</label>
                                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                                    class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all">
                                <?php if (!empty($editMember['photo'])): ?>
                                    <p class="text-xs text-slate-400 mt-1">Foto saat ini: <?= htmlspecialchars($editMember['photo']) ?>. Upload baru untuk mengganti.</p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">LinkedIn URL</label>
                                <input type="url" name="linkedin_url" value="<?= htmlspecialchars($editMember['linkedin_url'] ?? '') ?>"
                                    placeholder="https://linkedin.com/in/..."
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Urutan Tampil</label>
                                <input type="number" name="sort_order" value="<?= (int)($editMember['sort_order'] ?? 0) ?>"
                                    min="0" placeholder="0"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        <?php if ($editMember): ?>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                <?= $editMember['is_active'] ? 'checked' : '' ?>
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-medium text-slate-700">Tampilkan di website</label>
                        </div>
                        <?php endif; ?>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm hover:-translate-y-0.5 transition-all border-0">
                                <i class="fa-solid <?= $editMember ? 'fa-floppy-disk' : 'fa-plus' ?>"></i>
                                <?= $editMember ? 'Update Anggota' : 'Tambahkan' ?>
                            </button>
                            <?php if ($editMember): ?>
                                <a href="?tab=team" class="text-sm font-semibold text-slate-400 hover:text-slate-600 transition-colors">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Team list -->
                <?php if (count($members)): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-700 text-sm">Daftar Anggota Tim (<?= count($members) ?>)</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <?php foreach ($members as $m): ?>
                        <div class="flex items-center gap-4 px-6 py-4">
                            <!-- Avatar -->
                            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                <?php if (!empty($m['photo'])): ?>
                                    <img src="../assets/images/team/<?= htmlspecialchars($m['photo']) ?>" alt="<?= htmlspecialchars($m['name']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-lg font-black text-slate-400"><?= strtoupper(substr($m['name'], 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($m['name']) ?></div>
                                <?php if ($m['role']): ?>
                                    <div class="text-xs text-slate-400"><?= htmlspecialchars($m['role']) ?></div>
                                <?php endif; ?>
                            </div>
                            <!-- Status -->
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full <?= $m['is_active'] ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' ?>">
                                <?= $m['is_active'] ? 'Aktif' : 'Disembunyikan' ?>
                            </span>
                            <span class="text-xs text-slate-400">Urutan: <?= (int)$m['sort_order'] ?></span>
                            <!-- Actions -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <a href="?tab=team&edit=<?= $m['id'] ?>"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form method="POST" onsubmit="return confirm('Hapus <?= htmlspecialchars(addslashes($m['name'])) ?>?')" class="inline">
                                    <input type="hidden" name="_action" value="delete_member">
                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors border-0" title="Hapus">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
                    <i class="fa-solid fa-people-group text-4xl text-slate-200 mb-3 block"></i>
                    <p class="text-slate-400 text-sm">Belum ada anggota tim. Tambahkan lewat form di atas.</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </main>
    </div>
</body>
</html>
