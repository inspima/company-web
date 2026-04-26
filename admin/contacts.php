<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: /admin/login.php");
    exit;
}
require_once '../db.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: /admin/contacts.php?deleted=1");
    exit;
}

// Search & Filter
$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$where = "";
$params = [];

if ($q) {
    $where = "WHERE name LIKE ? OR email LIKE ? OR message LIKE ? OR service LIKE ? OR phone LIKE ?";
    $s = "%$q%";
    $params = [$s, $s, $s, $s, $s];
}

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM contacts $where");
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $limit);

$contacts = $pdo->prepare("SELECT * FROM contacts $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
$contacts->execute($params);
$results = $contacts->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Masuk — INSPIMA Admin</title>
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
                <h2 class="text-base font-bold text-slate-800">Pesan Masuk</h2>
                <p class="text-xs text-slate-400">Kelola pesan dan konsultasi dari customer</p>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8">

            <!-- Search -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                <form action="" method="GET" class="relative w-full sm:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari pesan..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border-0 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                </form>
                <div class="text-xs text-slate-400 font-medium">
                    Total: <span class="text-slate-800 font-bold"><?= $totalItems ?></span> pesan ditemukan
                </div>
            </div>
            
            <?php if(isset($_GET['deleted'])): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i> Pesan berhasil dihapus.
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Tanggal</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Customer</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Layanan</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Pesan</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(empty($results)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-envelope-open text-3xl mb-4 opacity-20 block"></i>
                                Belum ada pesan yang masuk.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($results as $c): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="text-xs font-semibold text-slate-700"><?= date('d M Y', strtotime($c['created_at'])) ?></div>
                                <div class="text-[10px] text-slate-400"><?= date('H:i', strtotime($c['created_at'])) ?> WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800"><?= htmlspecialchars($c['name']) ?></div>
                                <div class="text-xs text-slate-500 mb-0.5"><?= htmlspecialchars($c['email']) ?></div>
                                <div class="text-[10px] text-blue-600 font-medium"><?= htmlspecialchars($c['phone'] ?: '-') ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight
                                    <?php
                                    switch(strtolower($c['service'])) {
                                        case 'build': echo 'bg-blue-50 text-blue-600 border border-blue-100'; break;
                                        case 'rescue': echo 'bg-teal-50 text-teal-600 border border-teal-100'; break;
                                        case 'boost': echo 'bg-orange-50 text-orange-600 border border-orange-100'; break;
                                        default: echo 'bg-slate-50 text-slate-500 border border-slate-100';
                                    }
                                    ?>
                                ">
                                    <?= htmlspecialchars($c['service'] ?: 'Umum') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-600 leading-relaxed max-w-md line-clamp-2" title="<?= htmlspecialchars($c['message']) ?>">
                                    <?= htmlspecialchars($c['message']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="viewMessage(<?= htmlspecialchars(json_encode($c)) ?>)" 
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <a href="?delete=<?= $c['id'] ?>" 
                                       onclick="return confirm('Hapus pesan ini?')"
                                       class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <?php if($totalPages > 1): ?>
            <div class="mt-6 flex items-center justify-center gap-2">
                <?php if($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&q=<?= urlencode($q) ?>" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-all">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
                <?php endif; ?>

                <?php for($i=1; $i<=$totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&q=<?= urlencode($q) ?>" 
                   class="w-10 h-10 rounded-xl border flex items-center justify-center text-sm font-bold transition-all
                   <?= $i == $page ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-900/20' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>&q=<?= urlencode($q) ?>" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-all">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Modal View -->
    <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white rounded-[2rem] w-full max-w-xl overflow-hidden shadow-2xl border border-white/20">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Detail Pesan</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama</p>
                        <p id="m-name" class="text-sm font-bold text-slate-800"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                        <p id="m-date" class="text-sm text-slate-600"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email</p>
                        <p id="m-email" class="text-sm font-semibold text-blue-600"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Telepon</p>
                        <p id="m-phone" class="text-sm text-slate-800 font-bold"></p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Layanan</p>
                    <span id="m-service" class="px-3 py-1 rounded-full text-[10px] font-black uppercase"></span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pesan</p>
                    <div id="m-message" class="bg-slate-50 rounded-2xl p-5 text-sm text-slate-600 leading-relaxed border border-slate-100 italic"></div>
                </div>
            </div>
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <a id="m-wa" href="#" target="_blank" class="bg-green-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-green-700 transition-all">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Balas via WhatsApp
                </a>
                <button onclick="closeModal()" class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function viewMessage(data) {
            document.getElementById('m-name').innerText = data.name;
            document.getElementById('m-email').innerText = data.email;
            document.getElementById('m-phone').innerText = data.phone || '-';
            document.getElementById('m-date').innerText = data.created_at;
            document.getElementById('m-message').innerText = data.message;
            
            const svc = document.getElementById('m-service');
            svc.innerText = data.service || 'UMUM';
            svc.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase ";
            switch((data.service||'').toLowerCase()) {
                case 'build': svc.classList.add('bg-blue-100', 'text-blue-600'); break;
                case 'rescue': svc.classList.add('bg-teal-100', 'text-teal-600'); break;
                case 'boost': svc.classList.add('bg-orange-100', 'text-orange-600'); break;
                default: svc.classList.add('bg-slate-200', 'text-slate-600');
            }

            const waBtn = document.getElementById('m-wa');
            if (data.phone) {
                let p = data.phone.replace(/[^0-9]/g, '');
                if (p.startsWith('0')) p = '62' + p.slice(1);
                waBtn.href = `https://wa.me/${p}?text=Halo%20${encodeURIComponent(data.name)}%2C%20ini%20INSPIMA%20ingin%20membalas%20pesan%20Anda.`;
                waBtn.style.display = 'flex';
            } else {
                waBtn.style.display = 'none';
            }

            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        // Close modal on escape
        document.addEventListener('keydown', (e) => {
            if(e.key === 'Escape') closeModal();
        });
    </script>
</body>
</html>
