<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import { goToSection } from '../lib/nav.js';

  let data = null;

  onMount(async () => {
    try { data = await api.pilar('boost'); } catch(e) {}
  });
</script>

<!-- Hero -->
<section class="relative pt-32 pb-24 overflow-hidden bg-brand-main">
  <div class="absolute inset-0 pointer-events-none opacity-20">
    <div class="absolute top-1/4 right-10 w-96 h-96 bg-orange-500/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-yellow-500/10 rounded-full blur-[100px]"></div>
  </div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-3xl">
      <span class="inline-block bg-orange-500/10 border border-orange-500/30 text-orange-400 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-8">Pilar BOOST</span>
      <h1 class="font-heading text-5xl md:text-7xl font-black text-brand-textMain leading-tight mb-8">
        Skalakan Bisnis Anda<br/><span class="text-orange-400">Tanpa Batas</span>
      </h1>
      <p class="text-xl text-brand-textSec font-light leading-relaxed mb-10">
        Infrastruktur yang skalabel, deployment otomatis, dan monitoring proaktif. Kami optimalkan tumpukan teknologi Anda sehingga bisnis bisa bertumbuh tanpa hambatan teknis.
      </p>
      <div class="flex flex-wrap gap-4">
        <a href="#/" on:click|preventDefault={() => goToSection('contact')} class="inline-flex items-center gap-2 bg-orange-500 text-white px-8 py-4 rounded-full font-bold shadow-2xl shadow-orange-500/30 hover:-translate-y-1 transition-all">
          Mulai BOOST <i class="fa-solid fa-rocket"></i>
        </a>
        <a href="#/projects" class="inline-flex items-center gap-2 bg-brand-container border border-brand-border text-brand-textMain px-8 py-4 rounded-full font-bold hover:border-orange-500/50 hover:-translate-y-1 transition-all">
          Lihat Case Study <i class="fa-solid fa-chart-line"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Metrics -->
<section class="py-16 bg-brand-container/50 border-y border-brand-border">
  <div class="max-w-5xl mx-auto px-4">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-px bg-brand-border rounded-2xl overflow-hidden">
      {#each [
        { v: '40%', l: 'Pengurangan Biaya Infrastruktur' },
        { v: '99.9%', l: 'Uptime Guarantee' },
        { v: '10x', l: 'Kecepatan Deploy' },
      ] as s}
        <div class="bg-brand-container p-8 text-center">
          <div class="text-3xl font-black text-orange-400 font-heading mb-1">{s.v}</div>
          <div class="text-xs text-brand-textSec uppercase tracking-widest font-semibold">{s.l}</div>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- Services -->
<section class="py-24 bg-brand-main">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="font-heading text-4xl font-black text-brand-textMain mb-4 text-center">Layanan BOOST</h2>
    <p class="text-brand-textSec text-center mb-16 font-light max-w-xl mx-auto">Dari cloud migration hingga CI/CD pipeline, kami handle semua aspek teknis agar tim Anda fokus pada bisnis.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {#each [
        { icon: 'fa-brands fa-aws', title: 'Cloud Migration', desc: 'Migrasi aman ke AWS, GCP, atau Azure dengan zero downtime dan arsitektur cloud-native yang efisien.' },
        { icon: 'fa-solid fa-gears', title: 'CI/CD Pipeline', desc: 'Otomatisasi deployment dengan GitHub Actions, GitLab CI, atau Jenkins — dari commit ke production dalam menit.' },
        { icon: 'fa-solid fa-chart-line', title: 'Performance Optimization', desc: 'Analisis bottleneck, optimasi query database, caching strategy, dan CDN setup untuk aplikasi super cepat.' },
        { icon: 'fa-solid fa-shield-check', title: 'Security Hardening', desc: 'Audit keamanan menyeluruh, implementasi WAF, enkripsi data, dan penetration testing untuk proteksi optimal.' },
        { icon: 'fa-solid fa-eye', title: 'Monitoring & Alerting', desc: 'Setup Grafana, Prometheus, atau Datadog untuk visibilitas penuh sistem dan respons insiden yang cepat.' },
        { icon: 'fa-solid fa-scale-balanced', title: 'Auto Scaling', desc: 'Arsitektur yang otomatis scale up saat traffic tinggi dan scale down saat idle — bayar sesuai kebutuhan.' },
      ] as s}
        <div class="bg-brand-container rounded-3xl p-8 border border-brand-border card-glow hover:-translate-y-2 transition-all">
          <div class="w-12 h-12 bg-orange-500/10 rounded-2xl flex items-center justify-center mb-5">
            <i class="{s.icon} text-orange-400"></i>
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
        "Setelah BOOST oleh INSPIMA, biaya server kami turun 45% dan deployment time berkurang dari 2 jam menjadi 8 menit. ROI-nya luar biasa dalam 3 bulan pertama."
      </p>
      <div class="flex items-center justify-center gap-4">
        <div class="w-12 h-12 rounded-full bg-orange-500 flex items-center justify-center text-white font-black text-lg">B</div>
        <div class="text-left">
          <div class="font-bold text-brand-textMain">Budi Santoso</div>
          <div class="text-brand-textSec text-sm">CEO, DataKu SaaS</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-24 bg-orange-500">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <h2 class="font-heading text-4xl md:text-5xl font-black text-white mb-6">Siap Skalakan Bisnis Anda?</h2>
    <p class="text-orange-100 font-light text-lg mb-10">Mulai dengan audit gratis — kami identifikasi potensi optimasi dalam sistem Anda.</p>
    <a href="#/" on:click|preventDefault={() => goToSection('contact')} class="inline-flex items-center gap-3 bg-white text-orange-500 px-10 py-5 rounded-full font-black text-lg shadow-2xl hover:-translate-y-1 transition-all">
      Minta Audit Gratis <i class="fa-solid fa-arrow-right"></i>
    </a>
  </div>
</section>
