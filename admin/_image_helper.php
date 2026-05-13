<?php
/**
 * INSPIMA Admin — Image Upload & Compression Helper
 *
 * Fungsi processUploadedImage():
 *  - Validasi format & ukuran
 *  - Resize jika melebihi dimensi maksimal (tanpa crop)
 *  - Simpan sebagai WEBP dengan kualitas 85 (kualitas bagus, ukuran ~60% lebih kecil)
 *  - Fallback ke JPEG jika ekstensi WEBP tidak tersedia di server
 *
 * @param  string $fileKey    Nama field <input type="file" name="...">
 * @param  string $uploadDir  Direktori tujuan (dengan trailing slash)
 * @param  string $prefix     Prefix nama file (default 'img')
 * @param  int    $maxW       Lebar maksimal pixel (default 1920)
 * @param  int    $maxH       Tinggi maksimal pixel (default 1440)
 * @param  int    $quality    Kualitas WEBP/JPEG 0-100 (default 85)
 * @return string  Nama file hasil, '' jika tidak ada upload, 'ERR:...' jika gagal
 */
function processUploadedImage(
    string $fileKey,
    string $uploadDir,
    string $prefix  = 'img',
    int    $maxW    = 1920,
    int    $maxH    = 1440,
    int    $quality = 85
): string {
    if (empty($_FILES[$fileKey]['name'])) return '';

    $file = $_FILES[$fileKey];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // ── Validasi format ───────────────────────────────────────────────────────
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
        return 'ERR:format';
    }

    // ── Validasi ukuran file (max 10 MB) ──────────────────────────────────────
    if ($file['size'] > 10 * 1024 * 1024) {
        return 'ERR:size';
    }

    // ── GIF langsung copy tanpa kompresi (animasi bisa rusak) ────────────────
    if ($ext === 'gif') {
        $newName = uniqid($prefix . '_') . '.gif';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        return move_uploaded_file($file['tmp_name'], $uploadDir . $newName) ? $newName : '';
    }

    // ── Baca dimensi asli ─────────────────────────────────────────────────────
    $info = @getimagesize($file['tmp_name']);
    if (!$info) return 'ERR:format';

    [$origW, $origH, $imgType] = $info;

    // ── Load image ke resource GD ─────────────────────────────────────────────
    $src = match($imgType) {
        IMAGETYPE_JPEG => @imagecreatefromjpeg($file['tmp_name']),
        IMAGETYPE_PNG  => @imagecreatefrompng($file['tmp_name']),
        IMAGETYPE_WEBP => @imagecreatefromwebp($file['tmp_name']),
        default        => null,
    };
    if (!$src) return 'ERR:format';

    // ── Hitung dimensi target (proporsional) ──────────────────────────────────
    $ratio  = min($maxW / $origW, $maxH / $origH, 1.0); // 1.0 = jangan upscale
    $dstW   = (int) round($origW * $ratio);
    $dstH   = (int) round($origH * $ratio);

    // ── Buat canvas tujuan & resize ───────────────────────────────────────────
    $dst = imagecreatetruecolor($dstW, $dstH);

    // Pertahankan transparency untuk PNG
    if ($imgType === IMAGETYPE_PNG) {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $dstW, $dstH, $transparent);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $dstW, $dstH, $origW, $origH);
    imagedestroy($src);

    // ── Simpan ke disk ────────────────────────────────────────────────────────
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // Pilih format output: WebP (terbaik) → JPEG fallback
    $useWebP = function_exists('imagewebp');
    $outExt  = $useWebP ? 'webp' : 'jpg';
    $newName = uniqid($prefix . '_') . '.' . $outExt;
    $outPath = $uploadDir . $newName;

    $saved = $useWebP
        ? imagewebp($dst, $outPath, $quality)
        : imagejpeg($dst, $outPath, $quality);

    imagedestroy($dst);

    return $saved ? $newName : '';
}
