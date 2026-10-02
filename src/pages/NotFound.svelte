<script>
  import Icon from '../lib/Icon.svelte';
</script>

<div class="flex flex-col items-center justify-center min-h-screen gap-6 text-center px-4">
  <div class="w-28 h-28 bg-brand-container rounded-full flex items-center justify-center border border-brand-border">
    <span class="text-brand-textSec/30"><Icon name="map-pin" size={40} /></span>
  </div>
  <h1 class="font-heading text-6xl font-bold text-brand-textMain">404</h1>
  <p class="text-brand-textSec text-xl font-normal">Halaman yang Anda cari tidak ditemukan.</p>
  <a href="/" class="inline-flex items-center gap-2 bg-brand-accent text-white px-8 py-3.5 rounded-full font-bold shadow-lg shadow-brand-accent/30 hover:-translate-y-1 transition-all">
    <Icon name="home" size={14} /> Kembali ke Beranda
  </a>
</div>
