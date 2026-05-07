/**
 * Post-build script: copy PHP/assets into dist/ + copy built index.html to root
 * Run automatically via: npm run build
 */
import { cpSync, existsSync, mkdirSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dir = dirname(fileURLToPath(import.meta.url));
const root  = resolve(__dir, '..');
const dist  = resolve(root, 'dist');

function copy(src, dest, opts = {}) {
  const srcPath  = resolve(root, src);
  const destPath = resolve(dist, dest);
  if (!existsSync(srcPath)) {
    console.warn(`  [skip] ${src} — not found`);
    return;
  }
  mkdirSync(dirname(destPath), { recursive: true });
  cpSync(srcPath, destPath, { recursive: true, ...opts });
  console.log(`  [ok]   ${src} → dist/${dest}`);
}

console.log('\n📦 Copying PHP & assets into dist/\n');

copy('api.php',       'api.php');
copy('db.php',        'db.php');
copy('assets/images', 'assets/images');

// Salin vite.html ke dist/ agar index.php bisa membacanya
// (index.php ada di root dan membaca dist/vite.html untuk inject meta SEO dinamis)
console.log('  [ok]   dist/vite.html tersedia untuk index.php');

// Salin index.php ke dist juga (opsional, untuk referensi)
copy('index.php', 'index.php');

console.log('\n✅ Done!\n');
console.log('   Herd tetap serve dari root inspima/ — tidak perlu konfigurasi tambahan.\n');
