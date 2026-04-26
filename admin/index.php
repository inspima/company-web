<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: /admin/login.php");
    exit;
}
require_once '../db.php';

$projCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$artCount  = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$latestProjects = $pdo->query("SELECT title, slug FROM projects ORDER BY id DESC LIMIT 4")->fetchAll();
$latestArticles = $pdo->query("SELECT title, slug, created_at FROM articles ORDER BY id DESC LIMIT 4")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — INSPIMA Admin</title>
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
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 pl-16 lg:pl-8 flex-shrink-0">
            <div>
                <h2 class="text-base font-bold text-slate-800">Dashboard</h2>
                <p class="text-xs text-slate-400">Selamat datang kembali, <span class="font-semibold text-slate-600"><?= htmlspecialchars($_SESSION['admin_username']) ?></span></p>
            </div>
            <a href="/" target="_blank"
               class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-full transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website
            </a>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8">

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fa-solid fa-briefcase text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?= $projCount ?></div>
                    <div class="text-xs text-slate-500 font-medium mt-1">Proyek Portofolio</div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <i class="fa-solid fa-newspaper text-emerald-600 text-sm"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800"><?= $artCount ?></div>
                    <div class="text-xs text-slate-500 font-medium mt-1">Artikel Blog</div>
                </div>
                <a href="/admin/projects.php?action=add" class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 shadow-lg shadow-blue-900/20 hover:-translate-y-0.5 transition-all group cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-plus text-white text-sm"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Tambah Proyek</div>
                    <div class="text-xs text-blue-200 mt-0.5">Buat entri portofolio baru</div>
                </a>
                <a href="/admin/articles.php?action=add" class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-6 shadow-lg shadow-emerald-900/20 hover:-translate-y-0.5 transition-all group cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-pen-nib text-white text-sm"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Tulis Artikel</div>
                    <div class="text-xs text-emerald-200 mt-0.5">Publikasikan insight baru</div>
                </a>
            </div>

            <!-- Recent content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Latest Projects -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm">Proyek Terbaru</h3>
                        <a href="/admin/projects.php" class="text-xs text-blue-600 font-semibold hover:underline">Lihat semua →</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <?php if(empty($latestProjects)): ?>
                        <div class="p-6 text-center text-slate-400 text-sm">Belum ada proyek.</div>
                        <?php else: ?>
                        <?php foreach($latestProjects as $p): ?>
                        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-briefcase text-blue-500 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-slate-800 truncate"><?= htmlspecialchars($p['title']) ?></div>
                                <div class="text-xs text-slate-400">/project/<?= htmlspecialchars($p['slug']) ?></div>
                            </div>
                            <a href="/admin/projects.php?action=edit&id=<?php
                                $s=$pdo->prepare("SELECT id FROM projects WHERE slug=?"); $s->execute([$p['slug']]); $r=$s->fetch(); echo $r['id']??'';
                            ?>" class="text-slate-400 hover:text-blue-600 transition-colors text-xs">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Latest Articles -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm">Artikel Terbaru</h3>
                        <a href="/admin/articles.php" class="text-xs text-blue-600 font-semibold hover:underline">Lihat semua →</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <?php if(empty($latestArticles)): ?>
                        <div class="p-6 text-center text-slate-400 text-sm">Belum ada artikel.</div>
                        <?php else: ?>
                        <?php foreach($latestArticles as $a): ?>
                        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-newspaper text-emerald-500 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-slate-800 truncate"><?= htmlspecialchars($a['title']) ?></div>
                                <div class="text-xs text-slate-400"><?= date('d M Y', strtotime($a['created_at'])) ?></div>
                            </div>
                            <a href="/admin/articles.php?action=edit&id=<?php
                                $s=$pdo->prepare("SELECT id FROM articles WHERE slug=?"); $s->execute([$a['slug']]); $r=$s->fetch(); echo $r['id']??'';
                            ?>" class="text-slate-400 hover:text-blue-600 transition-colors text-xs">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tips banner -->
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 flex items-start gap-5">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-lightbulb text-blue-400"></i>
                </div>
                <div>
                    <div class="font-bold text-white text-sm mb-1">Tips SEO</div>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Setiap konten yang Anda buat, isi kolom <strong class="text-slate-200">Meta Title</strong> (50–60 karakter),
                        <strong class="text-slate-200">Meta Description</strong> (120–155 karakter), dan
                        <strong class="text-slate-200">Primary Keyword</strong> agar halaman lebih mudah ditemukan di mesin pencari.
                    </p>
                </div>
            </div>

        </main>
    </div>
</body>
</html>
