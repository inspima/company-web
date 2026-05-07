<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) { header("Location: /admin/login.php"); exit; }
require_once '../db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $meta_title = $_POST['meta_title'];
    $meta_desc = $_POST['meta_desc'];
    $keyphrase = $_POST['keyphrase'];
    $secondary_keyphrase = $_POST['secondary_keyphrase'];

    $stmt = $pdo->prepare("UPDATE static_pages SET meta_title=?, meta_desc=?, keyphrase=?, secondary_keyphrase=? WHERE id=?");
    $stmt->execute([$meta_title, $meta_desc, $keyphrase, $secondary_keyphrase, $id]);
    $msg = "SEO Halaman berhasil diperbarui.";
}

$pages = $pdo->query("SELECT * FROM static_pages")->fetchAll();

$pageIcons = [
    'home'    => 'fa-house',
    'build'   => 'fa-hammer',
    'rescue'  => 'fa-life-ring',
    'boost'   => 'fa-rocket',
    'blog'    => 'fa-newspaper',
    'projects'=> 'fa-briefcase',
    'about'   => 'fa-building-user',
    'contact' => 'fa-paper-plane',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Halaman — INSPIMA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/favicon.png" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .counter-ok  { color: #22c55e; font-weight: 700; }
        .counter-bad { color: #ef4444; font-weight: 700; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 flex h-screen overflow-hidden">
    <?php include '_sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 pl-16 lg:pl-8 flex-shrink-0">
            <div>
                <h2 class="text-base font-bold text-slate-800">SEO Halaman Statis</h2>
                <p class="text-xs text-slate-400">Optimasi meta untuk setiap halaman</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-8">

            <?php if($msg): ?>
            <div class="flex items-center gap-2.5 px-5 py-3.5 rounded-xl mb-6 text-sm bg-emerald-50 border border-emerald-200 text-emerald-700">
                <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($msg) ?>
            </div>
            <?php endif; ?>

            <!-- Info banner -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl px-6 py-4 mb-6 flex items-start gap-4">
                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                <p class="text-xs text-blue-700 leading-relaxed">
                    Atur <strong>Meta Title</strong> (50–60 karakter), <strong>Meta Description</strong> (120–155 karakter), dan
                    <strong>Keywords</strong> untuk setiap halaman agar mesin pencari mengindeksnya dengan optimal.
                </p>
            </div>

            <div class="space-y-5 max-w-5xl">
                <?php foreach($pages as $p):
                    $slug  = $p['slug'] ?? 'home';
                    $icon  = $pageIcons[$slug] ?? 'fa-file';
                    $title = ucfirst($p['title'] ?? $slug);
                    $url   = 'inspima.id' . ($slug === 'home' ? '/' : '/' . $slug);
                ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <!-- Page header -->
                    <div class="flex items-center gap-4 px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid <?= $icon ?> text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800 text-sm">Halaman <?= htmlspecialchars($title) ?></div>
                            <div class="text-xs text-slate-400 font-mono"><?= $url ?></div>
                        </div>
                    </div>

                    <form method="POST" class="p-6">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <!-- Meta Title -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Meta Title</label>
                                    <span id="title_counter_<?= $p['id'] ?>" class="text-[10px] font-bold text-slate-400">0 / 60</span>
                                </div>
                                <input type="text" name="meta_title" id="meta_title_<?= $p['id'] ?>"
                                    value="<?= htmlspecialchars($p['meta_title'] ?? '') ?>"
                                    placeholder="Judul halaman di Google..."
                                    oninput="updateCounter('meta_title_<?= $p['id'] ?>','title_counter_<?= $p['id'] ?>',60)"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            </div>
                            <!-- Keywords -->
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Primary Keyword</label>
                                    <input type="text" name="keyphrase"
                                        value="<?= htmlspecialchars($p['keyphrase'] ?? '') ?>"
                                        placeholder="e.g. software house indonesia"
                                        class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Secondary Keywords</label>
                                    <input type="text" name="secondary_keyphrase"
                                        value="<?= htmlspecialchars($p['secondary_keyphrase'] ?? '') ?>"
                                        placeholder="e.g. app dev, IT solutions"
                                        class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                                </div>
                            </div>
                        </div>
                        <!-- Meta Description -->
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Meta Description</label>
                                <span id="desc_counter_<?= $p['id'] ?>" class="text-[10px] font-bold text-slate-400">0 / 155</span>
                            </div>
                            <textarea name="meta_desc" id="meta_desc_<?= $p['id'] ?>" rows="2"
                                placeholder="Deskripsi singkat untuk Google (120–155 karakter)..."
                                oninput="updateCounter('meta_desc_<?= $p['id'] ?>','desc_counter_<?= $p['id'] ?>',155)"
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none"><?= htmlspecialchars($p['meta_desc'] ?? '') ?></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-slate-800 hover:bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm hover:-translate-y-0.5 transition-all border-0">
                                <i class="fa-solid fa-floppy-disk"></i> Update SEO <?= htmlspecialchars($title) ?>
                            </button>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

        </main>
    </div>

<script>
function updateCounter(inputId, counterId, max) {
    const el = document.getElementById(inputId);
    const ct = document.getElementById(counterId);
    if (!el || !ct) return;
    const n = el.value.length;
    ct.textContent = n + ' / ' + max;
    ct.className = n > max ? 'text-[10px] counter-bad' : n > max * 0.8 ? 'text-[10px] counter-ok' : 'text-[10px] text-slate-400 font-bold';
}
window.addEventListener('load', () => {
    <?php foreach($pages as $p): ?>
    updateCounter('meta_title_<?= $p['id'] ?>', 'title_counter_<?= $p['id'] ?>', 60);
    updateCounter('meta_desc_<?= $p['id'] ?>', 'desc_counter_<?= $p['id'] ?>', 155);
    <?php endforeach; ?>
});
</script>
</body>
</html>
