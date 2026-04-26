<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import { goToSection } from '../lib/nav.js';

  let data = null;

  onMount(async () => {
    try { data = await api.pilar('rescue'); } catch(e) {}
  });
</script>

<!-- Hero -->
<section class="relative pt-32 pb-24 overflow-hidden bg-brand-main">
  <div class="absolute inset-0 pointer-events-none opacity-20">
    <div class="absolute top-1/4 left-10 w-96 h-96 bg-teal-500/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-400/10 rounded-full blur-[100px]"></div>
  </div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-3xl">
      <span class="inline-block bg-teal-500/10 border border-teal-500/30 text-teal-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-8">Pilar RESCUE</span>
      <h1 class="font-heading text-5xl md:text-7xl font-black text-brand-textMain leading-tight mb-8">
        Selamatkan Sistem Anda<br/><span class="text-teal-500">Sebelum Terlambat</span>
      </h1>
      <p class="text-xl text-brand-textSec font-light leading-relaxed mb-10">
        Sistem down? Aplikasi crash? Bug kritis merusak reputasi bisnis? Tim RESCUE kami hadir dalam hitungan jam — mendiagnosis, memperbaiki, dan memulihkan sistem Anda ke kondisi optimal.
      </p>
      <div class="flex flex-wrap gap-4">
        <a href="https://wa.me/6285156625480?text=RESCUE%20-%20Butuh%20bantuan%20darurat!" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 bg-teal-500 text-white px-8 py-4 rounded-full font-bold shadow-2xl shadow-teal-500/30 hover:-translate-y-1 transition-all">
          <i class="fa-solid fa-bolt"></i> Emergency RESCUE
        </a>
        <a href="#/" on:click|preventDefault={() => goToSection('contact')} class="inline-flex items-center gap-2 bg-brand-container border border-brand-border text-brand-textMain px-8 py-4 rounded-full font-bold hover:border-teal-500/50 hover:-translate-y-1 transition-all">
          Konsultasi <i class="fa-solid fa-comments"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Response Stats -->
<section class="py-16 bg-brand-container/50 border-y border-brand-border">
  <div class="max-w-5xl mx-auto px-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-brand-border rounded-2xl overflow-hidden">
      {#each [
        { v: '< 2 Jam', l: 'Waktu Respons' },
        { v: '98%', l: 'Tingkat Keberhasilan' },
        { v: '24/7', l: 'On-Call Support' },
        { v: '500+', l: 'Sistem Diselamatkan' },
      ] as s}
        <div class="bg-brand-container p-8 text-center">
          <div class="text-3xl font-black text-teal-500 font-heading mb-1">{s.v}</div>
          <div class="text-xs text-brand-textSec uppercase tracking-widest font-semibold">{s.l}</div>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- Services -->
<section class="py-24 bg-brand-main">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="font-heading text-4xl font-black text-brand-textMain mb-4 text-center">Respons Cepat, Hasil Nyata</h2>
    <p class="text-brand-textSec text-center mb-16 font-light max-w-xl mx-auto">Layanan RESCUE dirancang untuk situasi kritis — setiap menit downtime berharga.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {#each [
        { icon: 'fa-solid fa-bug', title: 'Bug Critical Fix', desc: 'Identifikasi dan perbaikan bug kritis yang menyebabkan downtime atau data corruption dalam tempo singkat.' },
        { icon: 'fa-solid fa-server', title: 'Server Recovery', desc: 'Pemulihan server yang crash, database corrupt, atau konfigurasi yang salah setelah update.' },
        { icon: 'fa-solid fa-shield-virus', title: 'Security Incident', desc: 'Penanganan serangan siber, injeksi malware, dan pemulihan keamanan sistem dengan audit menyeluruh.' },
        { icon: 'fa-solid fa-code-merge', title: 'Code Refactoring', desc: 'Restrukturisasi kode warisan (legacy) yang tidak terkelola menjadi arsitektur bersih dan maintainable.' },
        { icon: 'fa-solid fa-gauge', title: 'Performance Fix', desc: 'Analisis dan optimasi performa — dari query database lambat hingga bottleneck pada level infrastruktur.' },
        { icon: 'fa-solid fa-people-arrows', title: 'Team Takeover', desc: 'Ambil alih proyek yang ditinggal developer sebelumnya, dengan audit kode dan rencana recovery terstruktur.' },
      ] as s}
        <div class="bg-brand-container rounded-3xl p-8 border border-brand-border card-glow hover:-translate-y-2 transition-all">
          <div class="w-12 h-12 bg-teal-500/10 rounded-2xl flex items-center justify-center mb-5">
            <i class="{s.icon} text-teal-500"></i>
          </div>
          <h3 class="font-bold text-brand-textMain mb-3">{s.title}</h3>
          <p class="text-brand-textSec text-sm font-light leading-relaxed">{s.desc}</p>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- Testimonial -->
<section class="py-20 bg-brand-container/50 border-t border-brand-border">
  <div class="max-w-3xl mx-auto px-4 text-center">
    <div class="bg-brand-container rounded-[2.5rem] p-12 border border-brand-border">
      <div class="flex justify-center mb-6">
        {#each [1,2,3,4,5] as _}
          <i class="fa-solid fa-star text-yellow-400 text-lg"></i>
        {/each}
      </div>
      <p class="text-brand-textMain text-xl font-light leading-relaxed mb-8 italic">
        "Sistem kami down di hari paling kritis — sehari sebelum pitch ke investor. Tim RESCUE INSPIMA merespons dalam 45 menit dan sistem kembali online dalam 3 jam. Luar biasa!"
      </p>
      <div class="flex items-center justify-center gap-4">
        <div class="w-12 h-12 rounded-full bg-teal-500 flex items-center justify-center text-white font-black text-lg">S</div>
        <div class="text-left">
          <div class="font-bold text-brand-textMain">Siti Rahayu</div>
          <div class="text-brand-textSec text-sm">CTO, FinTech Nusantara</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-24 bg-teal-600">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <h2 class="font-heading text-4xl md:text-5xl font-black text-white mb-6">Sistem Anda Dalam Bahaya?</h2>
    <p class="text-teal-100 font-light text-lg mb-10">Jangan tunggu lebih lama. Setiap menit downtime merugikan bisnis Anda.</p>
    <a href="https://wa.me/6285156625480?text=RESCUE%20DARURAT" target="_blank" rel="noopener"
       class="inline-flex items-center gap-3 bg-white text-teal-600 px-10 py-5 rounded-full font-black text-lg shadow-2xl hover:-translate-y-1 transition-all">
      <i class="fa-brands fa-whatsapp text-green-500"></i> Minta RESCUE Sekarang
    </a>
  </div>
</section>
