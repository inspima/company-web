<?php
// Shared sidebar — include in every admin page
// Requires: session already started, $_SESSION['admin_username'] available
$_cur = basename($_SERVER['PHP_SELF']);
$_nav = [
    ['href' => '/admin/index.php',    'icon' => 'fa-chart-pie',             'label' => 'Dashboard',    'file' => 'index.php'],
    ['href' => '/admin/about.php',    'icon' => 'fa-building-user',         'label' => 'Tentang Kami', 'file' => 'about.php'],
    ['href' => '/admin/projects.php', 'icon' => 'fa-briefcase',             'label' => 'Portofolio',   'file' => 'projects.php'],
    ['href' => '/admin/articles.php', 'icon' => 'fa-newspaper',             'label' => 'Artikel Blog', 'file' => 'articles.php'],
    ['href' => '/admin/contacts.php', 'icon' => 'fa-envelope-open-text',    'label' => 'Pesan Masuk',  'file' => 'contacts.php'],
    ['href' => '/admin/pages.php',    'icon' => 'fa-magnifying-glass-chart','label' => 'SEO Halaman',  'file' => 'pages.php'],
    ['href' => '/admin/password.php', 'icon' => 'fa-key',                   'label' => 'Ubah Password','file' => 'password.php'],
];
?>
<aside class="w-60 bg-slate-950 flex flex-col shrink-0 h-screen sticky top-0 z-20">
    <!-- Logo -->
    <div class="h-16 flex items-center px-5 border-b border-slate-800/80">
        <div>
            <div class="text-lg font-black text-white tracking-tight leading-none">INSPIMA</div>
            <div class="text-[9px] text-blue-400/70 uppercase tracking-[0.25em] font-bold mt-0.5">Control Panel</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-5 px-3 space-y-0.5 overflow-y-auto">
        <div class="text-[9px] font-bold text-slate-600 uppercase tracking-[0.18em] px-3 pb-2">Menu Utama</div>
        <?php foreach($_nav as $item): ?>
        <?php $isActive = ($_cur === $item['file']); ?>
        <a href="<?= $item['href'] ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                  <?= $isActive
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40'
                    : 'text-slate-400 hover:bg-slate-800/70 hover:text-white' ?>">
            <i class="fa-solid <?= $item['icon'] ?> w-4 text-center text-[13px] <?= $isActive ? '' : 'opacity-70' ?>"></i>
            <?= $item['label'] ?>
            <?php if($isActive): ?>
            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-300"></span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- User + Logout -->
    <div class="border-t border-slate-800/80 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xs font-black flex-shrink-0">
                <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-white truncate leading-none"><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></div>
                <div class="text-[10px] text-slate-500 mt-0.5">Administrator</div>
            </div>
            <a href="/admin/logout.php" title="Logout"
               class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-red-400 hover:bg-slate-800 transition-all flex-shrink-0">
                <i class="fa-solid fa-right-from-bracket text-sm"></i>
            </a>
        </div>
    </div>
</aside>
