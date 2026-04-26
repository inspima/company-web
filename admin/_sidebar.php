<?php
// Shared sidebar — include in every admin page
$_cur = basename($_SERVER['PHP_SELF']);
$_nav = [
    ['href' => '/admin/index.php',    'icon' => 'fa-chart-pie',             'label' => 'Dashboard',    'file' => 'index.php'],
    ['href' => '/admin/about.php',    'icon' => 'fa-building-user',         'label' => 'Tentang Kami', 'file' => 'about.php'],
    ['href' => '/admin/projects.php', 'icon' => 'fa-briefcase',             'label' => 'Portofolio',   'file' => 'projects.php'],
    ['href' => '/admin/articles.php', 'icon' => 'fa-newspaper',             'label' => 'Artikel Blog', 'file' => 'articles.php'],
    ['href' => '/admin/testimonials.php','icon' => 'fa-comment-dots',         'label' => 'Testimoni',    'file' => 'testimonials.php'],
    ['href' => '/admin/contacts.php', 'icon' => 'fa-envelope-open-text',    'label' => 'Pesan Masuk',  'file' => 'contacts.php'],
    ['href' => '/admin/pages.php',    'icon' => 'fa-magnifying-glass-chart','label' => 'SEO Halaman',  'file' => 'pages.php'],
    ['href' => '/admin/password.php', 'icon' => 'fa-key',                   'label' => 'Ubah Password','file' => 'password.php'],
];
?>

<!-- Mobile Toggle Button (Fixed) -->
<button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-40 w-10 h-10 bg-slate-900 text-white rounded-xl shadow-lg flex items-center justify-center border border-slate-800">
    <i class="fa-solid fa-bars"></i>
</button>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm z-30 hidden lg:hidden"></div>

<aside id="admin-sidebar" class="w-64 bg-slate-950 flex flex-col shrink-0 h-screen lg:h-full fixed lg:sticky top-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300" style="height: 100dvh;">
    <!-- Logo -->
    <div class="h-16 flex items-center px-5 border-b border-slate-800/80 justify-between">
        <div>
            <div class="text-lg font-black text-white tracking-tight leading-none">INSPIMA</div>
            <div class="text-[9px] text-blue-400/70 uppercase tracking-[0.25em] font-bold mt-0.5">Control Panel</div>
        </div>
        <button id="sidebar-close" class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
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
    <div class="border-t border-slate-800/80 p-4 mt-auto">
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

<script>
    const sidebar = document.getElementById('admin-sidebar');
    const toggle = document.getElementById('sidebar-toggle');
    const close = document.getElementById('sidebar-close');
    const overlay = document.getElementById('sidebar-overlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    toggle?.addEventListener('click', toggleSidebar);
    close?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);
</script>
