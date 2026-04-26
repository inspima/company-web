<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /admin/login.php");
    exit;
}
require_once '../db.php';

$action = $_GET['action'] ?? 'list';
$msg = '';
$msg_type = 'green';

// Handle Delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM testimonials WHERE id=?")->execute([$_GET['delete']]);
    $msg = "Testimoni dihapus.";
    $action = 'list';
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $company = trim($_POST['company']);
    $content = trim($_POST['content']);
    $stars = (int) ($_POST['stars'] ?? 5);

    $uploadDir = '../assets/images/testimonials/';
    if (!is_dir($uploadDir))
        mkdir($uploadDir, 0755, true);

    $image = '';
    if (!empty($_FILES['image_file']['name'])) {
        $file = $_FILES['image_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $image = uniqid('testi_') . '.' . $ext;
            move_uploaded_file($file['tmp_name'], $uploadDir . $image);
        }
    }

    if ($id) {
        if (!$image) {
            $s = $pdo->prepare("SELECT image FROM testimonials WHERE id=?");
            $s->execute([$id]);
            $image = $s->fetchColumn();
        }
        $stmt = $pdo->prepare("UPDATE testimonials SET name=?, role=?, company=?, content=?, image=?, stars=? WHERE id=?");
        $stmt->execute([$name, $role, $company, $content, $image, $stars, $id]);
        $msg = "Testimoni berhasil diupdate.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO testimonials (name, role, company, content, image, stars) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$name, $role, $company, $content, $image, $stars]);
        $msg = "Testimoni berhasil ditambahkan.";
    }
    $action = 'list';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimoni — INSPIMA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/favicon.png" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 flex h-screen overflow-hidden">
    <?php include '_sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 pl-16 lg:pl-8 flex-shrink-0">
            <div class="flex items-center gap-3">
                <?php if ($action !== 'list'): ?>
                    <a href="testimonials.php"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>
                <?php endif; ?>
                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        <?= $action === 'list' ? 'Testimoni' : ($action === 'add' ? 'Tambah Testimoni' : 'Edit Testimoni') ?>
                    </h2>
                    <p class="text-xs text-slate-400">Kelola ulasan dari klien</p>
                </div>
            </div>
            <?php if ($action === 'list'): ?>
                <a href="?action=add"
                    class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg transition-all">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Testimoni
                </a>
            <?php endif; ?>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-8">
            <?php if ($msg): ?>
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
                <?php
                $q = trim($_GET['q'] ?? '');
                $page = max(1, (int) ($_GET['page'] ?? 1));
                $limit = 10;
                $offset = ($page - 1) * $limit;

                $where = $q ? "WHERE name LIKE ? OR company LIKE ? OR content LIKE ?" : "";
                $params = $q ? ["%$q%", "%$q%", "%$q%"] : [];

                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM testimonials $where");
                $countStmt->execute($params);
                $totalItems = $countStmt->fetchColumn();
                $totalPages = ceil($totalItems / $limit);

                $stmt = $pdo->prepare("SELECT * FROM testimonials $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
                $stmt->execute($params);
                $list = $stmt->fetchAll();
                ?>

                <!-- Search bar sync -->
                <div
                    class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                    <form action="" method="GET" class="relative w-full sm:w-80">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari testimoni..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border-0 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </form>
                    <div class="text-xs text-slate-400 font-medium">
                        Total: <span class="text-slate-800 font-bold"><?= $totalItems ?></span> ulasan
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[700px]">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 w-20">
                                    Foto</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Klien
                                </th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Pesan
                                </th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 w-24 text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (empty($list)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada testimoni.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($list as $row): ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <?php if ($row['image']): ?>
                                                <img src="../assets/images/testimonials/<?= $row['image'] ?>"
                                                    class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                            <?php else: ?>
                                                <div
                                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-400 font-bold text-xs">
                                                    <?= substr($row['name'], 0, 1) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($row['name']) ?>
                                            </div>
                                            <div class="text-xs text-slate-500"><?= htmlspecialchars($row['role']) ?>
                                                <?= $row['company'] ? '— ' . $row['company'] : '' ?></div>
                                            <div class="flex gap-0.5 mt-1">
                                                <?php for ($i = 0; $i < $row['stars']; $i++): ?><i
                                                        class="fa-solid fa-star text-[10px] text-yellow-400"></i><?php endfor; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-slate-600 line-clamp-2 italic italic max-w-md">
                                                "<?= htmlspecialchars($row['content']) ?>"</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a href="?action=edit&id=<?= $row['id'] ?>"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-blue-100 hover:text-blue-600 transition-all"><i
                                                        class="fa-solid fa-pen text-xs"></i></a>
                                                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus testimoni ini?')"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-red-100 hover:text-red-600 transition-all"><i
                                                        class="fa-solid fa-trash text-xs"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination sync -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-6 flex items-center justify-center gap-2">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>&q=<?= urlencode($q) ?>"
                                class="w-10 h-10 rounded-xl border flex items-center justify-center text-sm font-bold transition-all
                   <?= $i == $page ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-900/20' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php else:
                $item = ['id' => '', 'name' => '', 'role' => '', 'company' => '', 'content' => '', 'stars' => 5, 'image' => ''];
                if ($action === 'edit' && isset($_GET['id'])) {
                    $s = $pdo->prepare("SELECT * FROM testimonials WHERE id=?");
                    $s->execute([$_GET['id']]);
                    $item = $s->fetch() ?: $item;
                }
                ?>
                <form method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama
                                    Klien *</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jabatan
                                    / Role</label>
                                <input type="text" name="role" value="<?= htmlspecialchars($item['role']) ?>"
                                    placeholder="contoh: CEO, Founder, etc."
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Perusahaan</label>
                                <input type="text" name="company" value="<?= htmlspecialchars($item['company']) ?>"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Rating
                                    Bintang</label>
                                <select name="stars"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>" <?= $item['stars'] == $i ? 'selected' : '' ?>><?= $i ?> Bintang
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Isi
                                Testimoni *</label>
                            <textarea name="content" required rows="4"
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none"><?= htmlspecialchars($item['content']) ?></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Foto
                                (Optional)</label>
                            <div class="flex items-center gap-4">
                                <?php if ($item['image']): ?>
                                    <img src="../assets/images/testimonials/<?= $item['image'] ?>"
                                        class="w-16 h-16 rounded-full object-cover border border-slate-200">
                                <?php endif; ?>
                                <input type="file" name="image_file" accept="image/*"
                                    class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg transition-all">Simpan
                            Testimoni</button>
                        <a href="testimonials.php"
                            class="text-slate-500 hover:text-slate-700 font-semibold text-sm">Batal</a>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>