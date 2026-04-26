<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: /admin/login.php");
    exit;
}
require_once '../db.php';

$msg = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['admin_username'];

    if($new_password !== $confirm_password) {
        $error = "Konfirmasi password baru tidak cocok.";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM admin_users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if($user && password_verify($current_password, $user['password'])) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE admin_users SET password = :password WHERE username = :username");
            $update->execute(['password' => $hashed, 'username' => $username]);
            $msg = "Password berhasil diubah.";
        } else {
            $error = "Password saat ini salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password — INSPIMA Admin</title>
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
                <h2 class="text-base font-bold text-slate-800">Ubah Password</h2>
                <p class="text-xs text-slate-400">Ganti password akun administrator Anda</p>
            </div>
            <a href="/" target="_blank"
               class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-full transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website
            </a>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8">
            <div class="max-w-xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                <?php if($msg): ?>
                <div class="flex items-center gap-2.5 bg-green-50 text-green-600 px-4 py-3 rounded-xl mb-6 text-sm border border-green-200">
                    <i class="fa-solid fa-circle-check flex-shrink-0"></i>
                    <?= htmlspecialchars($msg) ?>
                </div>
                <?php endif; ?>
                <?php if($error): ?>
                <div class="flex items-center gap-2.5 bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm border border-red-200">
                    <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Password Baru</label>
                        <input type="password" name="new_password" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                            <i class="fa-solid fa-save"></i> Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
