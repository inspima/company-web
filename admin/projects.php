<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) { header("Location: /admin/login.php"); exit; }
require_once '../db.php';
require_once '_image_helper.php';

$action = $_GET['action'] ?? 'list';
$msg = '';
$msg_type = 'green';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = $_POST['id'] ?? '';
    $title       = trim($_POST['title']);
    $slug        = trim($_POST['slug']);
    if (!$slug) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
    $category_id = $_POST['category_id'] ?: null;
    $client_name = trim($_POST['client_name']);
    $description = $_POST['description'];
    $meta_title  = $_POST['meta_title'];
    $meta_desc   = $_POST['meta_desc'];
    $keyphrase   = $_POST['keyphrase'];
    $secondary_keyphrase = $_POST['secondary_keyphrase'] ?? '';

    $pilar_build  = isset($_POST['pilar_build']) ? 1 : 0;
    $pilar_rescue = isset($_POST['pilar_rescue']) ? 1 : 0;
    $pilar_boost  = isset($_POST['pilar_boost']) ? 1 : 0;

    $dir = '../assets/images/projects/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $old = ['image'=>'','image_alt'=>'','image_name'=>'','image_2'=>'','image_2_name'=>'','image_2_alt'=>'','image_3'=>'','image_3_name'=>'','image_3_alt'=>'','image_4'=>'','image_4_name'=>'','image_4_alt'=>''];
    if ($id) {
        $s = $pdo->prepare("SELECT * FROM projects WHERE id=?"); $s->execute([$id]); $old = $s->fetch() ?: $old;
    }

    $imgs = [];
    $imgFields = [
        ['file_key'=>'image_file', 'name_key'=>'image_name', 'alt_key'=>'image_alt', 'db_img'=>'image', 'db_name'=>'image_name', 'db_alt'=>'image_alt'],
        ['file_key'=>'image_2_file','name_key'=>'image_2_name','alt_key'=>'image_2_alt','db_img'=>'image_2','db_name'=>'image_2_name','db_alt'=>'image_2_alt'],
        ['file_key'=>'image_3_file','name_key'=>'image_3_name','alt_key'=>'image_3_alt','db_img'=>'image_3','db_name'=>'image_3_name','db_alt'=>'image_3_alt'],
        ['file_key'=>'image_4_file','name_key'=>'image_4_name','alt_key'=>'image_4_alt','db_img'=>'image_4','db_name'=>'image_4_name','db_alt'=>'image_4_alt'],
    ];
    foreach ($imgFields as $f) {
        $uploaded = processUploadedImage($f['file_key'], $dir, 'proj', 1920, 1440);
        if (strpos((string)$uploaded, 'ERR:') === 0) { $msg = 'Error upload: ' . $f['file_key']; $msg_type='red'; $uploaded=''; }
        $imgs[$f['db_img']]  = $uploaded ?: ($old[$f['db_img']] ?? '');
        $imgs[$f['db_name']] = trim($_POST[$f['name_key']] ?? '');
        $imgs[$f['db_alt']]  = trim($_POST[$f['alt_key']]  ?? '');
    }

    if (!$msg || $msg_type !== 'red') {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE projects SET category_id=?,title=?,slug=?,description=?,client_name=?,meta_title=?,meta_desc=?,keyphrase=?,secondary_keyphrase=?,image=?,image_alt=?,image_name=?,image_2=?,image_2_name=?,image_2_alt=?,image_3=?,image_3_name=?,image_3_alt=?,image_4=?,image_4_name=?,image_4_alt=?, pilar_build=?, pilar_rescue=?, pilar_boost=? WHERE id=?");
            $stmt->execute([$category_id,$title,$slug,$description,$client_name,$meta_title,$meta_desc,$keyphrase,$secondary_keyphrase,$imgs['image'],$imgs['image_alt'],$imgs['image_name'],$imgs['image_2'],$imgs['image_2_name'],$imgs['image_2_alt'],$imgs['image_3'],$imgs['image_3_name'],$imgs['image_3_alt'],$imgs['image_4'],$imgs['image_4_name'],$imgs['image_4_alt'],$pilar_build,$pilar_rescue,$pilar_boost,$id]);
            $msg = "Proyek berhasil diupdate.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO projects (category_id,title,slug,description,client_name,meta_title,meta_desc,keyphrase,secondary_keyphrase,image,image_alt,image_name,image_2,image_2_name,image_2_alt,image_3,image_3_name,image_3_alt,image_4,image_4_name,image_4_alt, pilar_build, pilar_rescue, pilar_boost) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$category_id,$title,$slug,$description,$client_name,$meta_title,$meta_desc,$keyphrase,$secondary_keyphrase,$imgs['image'],$imgs['image_alt'],$imgs['image_name'],$imgs['image_2'],$imgs['image_2_name'],$imgs['image_2_alt'],$imgs['image_3'],$imgs['image_3_name'],$imgs['image_3_alt'],$imgs['image_4'],$imgs['image_4_name'],$imgs['image_4_alt'],$pilar_build,$pilar_rescue,$pilar_boost]);
            $msg = "Proyek berhasil ditambahkan.";
        }
        $action = 'list';
    }
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM projects WHERE id=?")->execute([$_GET['delete']]);
    $msg = "Proyek dihapus."; $action = 'list';
}

function imgUploadBlock($label, $icon, $fileKey, $nameKey, $altKey, $current, $currentName, $currentAlt, $baseDir) {
    $imgSrc = !empty($current) ? $baseDir . htmlspecialchars($current) : '';
    $previewId = 'prev_' . $fileKey;
    echo '<div class="bg-slate-50 border border-slate-200 rounded-xl p-4">';
    echo '<div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">' . $label . '</div>';
    if ($imgSrc) {
        echo "<img id='{$previewId}' src='{$imgSrc}' class='w-full h-32 object-cover rounded-lg border border-slate-200 mb-3'>";
    } else {
        echo "<div id='{$previewId}_wrap' class='w-full h-32 bg-white rounded-lg border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-400 mb-3'><div class='text-center'><i class='fa-regular fa-image text-2xl mb-1 block'></i><span class='text-xs'>Preview</span></div></div>";
        echo "<img id='{$previewId}' class='hidden w-full h-32 object-cover rounded-lg border border-slate-200 mb-3'>";
    }
    echo "<input type='file' name='{$fileKey}' id='{$fileKey}' accept='image/*' class='w-full text-xs text-slate-500 mb-2 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer'>";
    echo "<input type='text' name='{$nameKey}' value='" . htmlspecialchars($currentName) . "' placeholder='Caption' class='w-full border border-slate-200 bg-white rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2'>";
    echo "<input type='text' name='{$altKey}' value='" . htmlspecialchars($currentAlt) . "' placeholder='Alt text (SEO)' class='w-full border border-slate-200 bg-white rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500'>";
    echo "</div>";
    echo "<script>document.getElementById('{$fileKey}')?.addEventListener('change',function(e){const f=e.target.files[0];if(!f)return;const r=new FileReader();r.onload=function(ev){const p=document.getElementById('{$previewId}');const w=document.getElementById('{$previewId}_wrap');if(w)w.classList.add('hidden');p.src=ev.target.result;p.classList.remove('hidden');};r.readAsDataURL(f);});</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio — INSPIMA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/favicon.png" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .tox-tinymce { border-radius: 0.75rem !important; }
        .counter-ok  { color: #22c55e; font-weight: 700; }
        .counter-bad { color: #ef4444; font-weight: 700; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 flex h-screen overflow-hidden">
    <?php include '_sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 pl-16 lg:pl-8 flex-shrink-0">
            <div class="flex items-center gap-3">
                <?php if($action !== 'list'): ?>
                <a href="/admin/projects.php" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <?php endif; ?>
                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        <?= $action === 'list' ? 'Portofolio' : ($action === 'add' ? 'Tambah Proyek' : 'Edit Proyek') ?>
                    </h2>
                    <p class="text-xs text-slate-400">Kelola proyek &amp; portofolio</p>
                </div>
            </div>
            <?php if($action === 'list'): ?>
            <a href="?action=add"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg shadow-blue-900/20 hover:-translate-y-0.5 transition-all">
                <i class="fa-solid fa-plus"></i> Tambah Proyek
            </a>
            <?php endif; ?>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-8">

            <?php if($msg): ?>
            <div class="flex items-center gap-2.5 px-5 py-3.5 rounded-xl mb-6 text-sm <?= $msg_type==='red' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-emerald-50 border border-emerald-200 text-emerald-700' ?>">
                <i class="fa-solid <?= $msg_type==='red' ? 'fa-circle-exclamation' : 'fa-circle-check' ?>"></i>
                <?= htmlspecialchars($msg) ?>
            </div>
            <?php endif; ?>

            <?php if($action === 'list'): ?>
            <?php
                $q = trim($_GET['q'] ?? '');
                $page = max(1, (int)($_GET['page'] ?? 1));
                $limit = 10;
                $offset = ($page - 1) * $limit;

                $where = "";
                $params = [];
                if ($q) {
                    $where = " WHERE title LIKE ? OR client_name LIKE ? OR slug LIKE ? ";
                    $params = ["%$q%", "%$q%", "%$q%"];
                }

                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM projects $where");
                $countStmt->execute($params);
                $totalItems = $countStmt->fetchColumn();
                $totalPages = ceil($totalItems / $limit);

                $stmt = $pdo->prepare("SELECT * FROM projects $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
                $stmt->execute($params);
                $projs = $stmt->fetchAll();
            ?>
            <!-- Search -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                <form action="" method="GET" class="relative w-full sm:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari proyek atau klien..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border-0 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                </form>
                <div class="text-xs text-slate-400 font-medium">
                    Total: <span class="text-slate-800 font-bold"><?= $totalItems ?></span> proyek ditemukan
                </div>
            </div>

            <!-- LIST VIEW -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 w-20">Foto</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Proyek</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 hidden md:table-cell">Klien</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 w-24 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        if(empty($projs)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada proyek. <a href="?action=add" class="text-blue-600 font-semibold">Tambah sekarang →</a></td></tr>
                        <?php else: ?>
                        <?php foreach($projs as $row):
                            $imgSrc = !empty($row['image']) ? '../assets/images/projects/' . $row['image'] : ''; ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-4 py-3.5">
                                <?php if($imgSrc): ?>
                                <img src="<?= $imgSrc ?>" class="w-14 h-10 object-cover rounded-lg border border-slate-200">
                                <?php else: ?>
                                <div class="w-14 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                    <i class="fa-regular fa-image text-xs"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($row['title']) ?></div>
                                <div class="text-xs text-slate-400 mt-0.5 font-mono">/project/<?= htmlspecialchars($row['slug']) ?></div>
                                <div class="flex gap-1 mt-1.5">
                                    <?php if(($row['pilar_build'] ?? 0)): ?><span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[9px] font-black rounded-full uppercase tracking-wider">Build</span><?php endif; ?>
                                    <?php if(($row['pilar_rescue'] ?? 0)): ?><span class="px-2 py-0.5 bg-teal-100 text-teal-700 text-[9px] font-black rounded-full uppercase tracking-wider">Rescue</span><?php endif; ?>
                                    <?php if(($row['pilar_boost'] ?? 0)): ?><span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[9px] font-black rounded-full uppercase tracking-wider">Boost</span><?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-slate-500 hidden md:table-cell"><?= htmlspecialchars($row['client_name']) ?></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="?action=edit&id=<?= $row['id'] ?>"
                                       class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-blue-100 hover:text-blue-600 transition-all" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <a href="?delete=<?= $row['id'] ?>"
                                       class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-red-100 hover:text-red-600 transition-all" title="Hapus"
                                       onclick="return confirm('Hapus proyek ini?')">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
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

            <?php else:
                $proj = ['id'=>'','title'=>'','slug'=>'','category_id'=>'','client_name'=>'','description'=>'','meta_title'=>'','meta_desc'=>'','keyphrase'=>'','secondary_keyphrase'=>'','image'=>'','image_name'=>'','image_alt'=>'','image_2'=>'','image_2_name'=>'','image_2_alt'=>'','image_3'=>'','image_3_name'=>'','image_3_alt'=>'','image_4'=>'','image_4_name'=>'','image_4_alt'=>'','pilar_build'=>0,'pilar_rescue'=>0,'pilar_boost'=>0];
                if($action === 'edit' && isset($_GET['id'])) {
                    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id=?");
                    $stmt->execute([$_GET['id']]);
                    $proj = array_merge($proj, $stmt->fetch() ?: []);
                }
                $cats = $pdo->query("SELECT * FROM categories WHERE type='project'")->fetchAll();
                $baseDir = '../assets/images/projects/';
            ?>
            <!-- FORM VIEW -->
            <form method="POST" action="projects.php" enctype="multipart/form-data" class="space-y-6 max-w-5xl">
                <input type="hidden" name="id" value="<?= $proj['id'] ?>">

                <!-- Basic Info -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i> Informasi Dasar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Judul Proyek *</label>
                            <input type="text" name="title" id="proj_title" value="<?= htmlspecialchars($proj['title']) ?>" required
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Klien</label>
                            <input type="text" name="client_name" value="<?= htmlspecialchars($proj['client_name']) ?>"
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kategori</label>
                            <select name="category_id" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="">— Pilih —</option>
                                <?php foreach($cats as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id']==$proj['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Slug -->
                    <div class="mt-4">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">URL Slug</label>
                        <div class="flex items-center gap-0 border border-slate-200 rounded-xl overflow-hidden bg-slate-50 focus-within:ring-2 focus-within:ring-blue-500 focus-within:bg-white transition-all">
                            <span class="text-xs text-slate-400 pl-4 pr-1 font-mono whitespace-nowrap">inspima.id/project/</span>
                            <input type="text" name="slug" id="proj_slug" value="<?= htmlspecialchars($proj['slug']) ?>" placeholder="slug-url"
                                class="flex-1 bg-transparent border-0 px-2 py-2.5 text-sm text-blue-600 font-mono focus:outline-none focus:ring-0">
                            <button type="button" onclick="generateSlug()"
                                class="text-[10px] font-bold text-slate-500 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 px-3 h-full py-2.5 border-l border-slate-200 transition-all">
                                Auto
                            </button>
                        </div>
                    </div>

                    <!-- Pillar -->
                    <div class="mt-4 flex flex-wrap gap-3">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider self-center mr-2">Pilar:</label>
                        <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 transition-all">
                            <input type="checkbox" name="pilar_build" <?= $proj['pilar_build']?'checked':'' ?> class="rounded text-blue-600">
                            <span class="text-xs font-black text-blue-700">BUILD</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 transition-all">
                            <input type="checkbox" name="pilar_rescue" <?= $proj['pilar_rescue']?'checked':'' ?> class="rounded text-teal-600">
                            <span class="text-xs font-black text-teal-700">RESCUE</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-orange-200 bg-orange-50 hover:bg-orange-100 transition-all">
                            <input type="checkbox" name="pilar_boost" <?= $proj['pilar_boost']?'checked':'' ?> class="rounded text-orange-600">
                            <span class="text-xs font-black text-orange-700">BOOST</span>
                        </label>
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-images text-blue-500"></i> Foto Proyek
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php
                        imgUploadBlock('Thumbnail Utama', 'star', 'image_file', 'image_name', 'image_alt', $proj['image'], $proj['image_name'], $proj['image_alt'], $baseDir);
                        imgUploadBlock('Foto 1', 'camera', 'image_2_file', 'image_2_name', 'image_2_alt', $proj['image_2'], $proj['image_2_name'], $proj['image_2_alt'], $baseDir);
                        imgUploadBlock('Foto 2', 'camera', 'image_3_file', 'image_3_name', 'image_3_alt', $proj['image_3'], $proj['image_3_name'], $proj['image_3_alt'], $baseDir);
                        imgUploadBlock('Foto 3', 'camera', 'image_4_file', 'image_4_name', 'image_4_alt', $proj['image_4'], $proj['image_4_name'], $proj['image_4_alt'], $baseDir);
                        ?>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-align-left text-blue-500"></i> Deskripsi Proyek
                    </h3>
                    <textarea name="description" id="project_content"><?= htmlspecialchars($proj['description']) ?></textarea>
                </div>

                <!-- SEO -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-chart text-blue-500"></i> SEO Optimization
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Meta Title *</label>
                                <span id="title_counter" class="text-[10px] text-slate-400 font-bold">0 / 60</span>
                            </div>
                            <input type="text" name="meta_title" id="meta_title" maxlength="100" value="<?= htmlspecialchars($proj['meta_title']) ?>" placeholder="Judul di hasil pencarian Google..."
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Primary Keyword</label>
                                <input type="text" name="keyphrase" value="<?= htmlspecialchars($proj['keyphrase']) ?>" placeholder="e.g. e-commerce redesign laravel"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Secondary Keywords</label>
                                <input type="text" name="secondary_keyphrase" value="<?= htmlspecialchars($proj['secondary_keyphrase'] ?? '') ?>" placeholder="e.g. react, ui/ux, pwa"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Meta Description *</label>
                            <span id="desc_counter" class="text-[10px] text-slate-400 font-bold">0 / 155</span>
                        </div>
                        <textarea name="meta_desc" id="meta_desc" rows="3" maxlength="300" placeholder="Deskripsi singkat untuk Google (120–155 karakter)..."
                            class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none"><?= htmlspecialchars($proj['meta_desc']) ?></textarea>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3 pb-8">
                    <a href="/admin/projects.php" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-900/20 hover:-translate-y-0.5 transition-all border-0">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Proyek
                    </button>
                </div>
            </form>
            <?php endif; ?>

        </main>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#project_content',
    height: 380,
    plugins: 'advlist autolink lists link charmap preview searchreplace visualblocks code fullscreen table wordcount',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link | code',
    skin: 'oxide',
    content_css: 'default',
    setup: function(editor) { editor.on('change', function() { editor.save(); }); }
});

function generateSlug() {
    const title = document.getElementById('proj_title')?.value || '';
    document.getElementById('proj_slug').value = title.toLowerCase().replace(/[^a-z0-9 -]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-');
}

function updateCounter(inputId, counterId, max) {
    const el = document.getElementById(inputId);
    const ct = document.getElementById(counterId);
    if (!el || !ct) return;
    const n = el.value.length;
    ct.textContent = n + ' / ' + max;
    ct.className = n > max ? 'text-[10px] counter-bad' : n > max * 0.8 ? 'text-[10px] counter-ok' : 'text-[10px] text-slate-400 font-bold';
}
document.getElementById('meta_title')?.addEventListener('input', () => updateCounter('meta_title','title_counter',60));
document.getElementById('meta_desc')?.addEventListener('input', () => updateCounter('meta_desc','desc_counter',155));
if(document.getElementById('meta_title')) { updateCounter('meta_title','title_counter',60); updateCounter('meta_desc','desc_counter',155); }
</script>
</body>
</html>
