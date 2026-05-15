<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import { goToSection } from '../lib/nav.js';
  import Icon from '../lib/Icon.svelte';

  let data = null;

  const faqs = [
    { q: 'Berapa lama waktu pengembangan produk?', a: 'Tergantung kompleksitas. MVP sederhana bisa selesai dalam 4–8 minggu. Produk enterprise biasanya 3–6 bulan.', open: false },
    { q: 'Teknologi apa yang digunakan?', a: 'Kami menggunakan Laravel, React, Next.js, Flutter, Node.js, dan teknologi modern lainnya disesuaikan kebutuhan bisnis Anda.', open: false },
    { q: 'Apakah ada garansi setelah selesai?', a: 'Ya, kami memberikan garansi bug-fix 3 bulan setelah serah terima produk tanpa biaya tambahan.', open: false },
    { q: 'Bagaimana proses pembayarannya?', a: 'Sistem pembayaran bertahap: DP 30% di awal, 40% saat milestone pertama selesai, dan 30% saat serah terima akhir.', open: false },
  ];

  let openFaq = -1;

  onMount(async () => {
    try { data = await api.pilar('build'); } catch(e) {}
  });
</script>

<!-- Hero -->
<section class="relative pt-32 pb-24 overflow-hidden bg-brand-main">
  <div class="absolute inset-0 pointer-events-none opacity-30">
    <div class="absolute top-1/4 left-10 w-96 h-96 bg-brand-accent/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-sky-500/10 rounded-full blur-[100px]"></div>
  </div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-3xl">
      <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-8">Pilar BUILD</span>
      <h1 class="hero-heading">
        Bangun Produk Digital<br/><span class="gradient-text">dari Nol hingga Sempurna</span>
      </h1>
      <p class="text-xl text-brand-textSec font-light leading-relaxed mb-10">
        Kami merancang, mengembangkan, dan meluncurkan produk digital berkualitas tinggi — web app, mobile app, hingga sistem enterprise — dengan kode bersih dan arsitektur yang skalabel.
      </p>
      <div class="flex flex-wrap gap-4">
        <a href="/" on:click|preventDefault={() => goToSection('contact')} class="inline-flex items-center gap-2 bg-brand-accent text-white px-8 py-4 rounded-full font-bold shadow-2xl shadow-brand-accent/30 hover:-translate-y-1 transition-all">
          Mulai Project <Icon name="rocket" size={16} />
        </a>
        <a href="/projects" class="inline-flex items-center gap-2 bg-brand-container border border-brand-border text-brand-textMain px-8 py-4 rounded-full font-bold hover:border-brand-accent/50 hover:-translate-y-1 transition-all">
          Lihat Portfolio <Icon name="briefcase" size={16} />
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Why BUILD -->
<section class="py-24 bg-brand-container/50 border-y border-brand-border">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <h2 class="font-heading text-4xl font-black text-brand-textMain mb-4">Mengapa Memilih BUILD?</h2>
      <p class="text-brand-textSec font-light max-w-xl mx-auto">Kami bukan sekadar vendor, kami adalah mitra teknologi jangka panjang Anda.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      {#each [
        { icon: 'shield',   icolor: 'blue',   title: 'Kode Berkualitas', desc: 'Clean code, test coverage, dan dokumentasi lengkap. Setiap baris kode ditulis dengan standar industri terbaik.' },
        { icon: 'gauge',    icolor: 'teal',   title: 'Performa Optimal', desc: 'Aplikasi yang responsif, load cepat, dan scalable. Kami optimalkan dari database query hingga frontend rendering.' },
        { icon: 'headset',  icolor: 'orange', title: 'Support Penuh',    desc: 'Garansi 3 bulan dan dedicated support post-launch. Kami tidak meninggalkan Anda setelah proyek selesai.' },
      ] as item}
        <div class="bg-brand-container rounded-3xl p-8 border border-brand-border card-glow">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 icon-{item.icolor}">
            <Icon name={item.icon} size={24} />
          </div>
          <h3 class="text-xl font-bold text-brand-textMain mb-3">{item.title}</h3>
          <p class="text-brand-textSec text-sm font-light leading-relaxed">{item.desc}</p>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- Projects -->
{#if data?.projects?.length > 0}
<section class="py-24 bg-brand-main">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="font-heading text-3xl font-black text-brand-textMain mb-12 text-center">Proyek BUILD Terbaru</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {#each data.projects as proj}
        <a href="/project/{proj.slug}" class="group bento-item bg-brand-container rounded-3xl overflow-hidden border border-brand-border card-glow">
          <div class="h-52 overflow-hidden bg-brand-main">
            {#if proj.image_url}
              <img src={proj.image_url} alt={proj.title} class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            {:else}
              <div class="w-full h-full flex items-center justify-center text-brand-textSec/20">
                <Icon name="layers" size={80} />
              </div>
            {/if}
          </div>
          <div class="p-7">
            <h3 class="font-bold text-brand-textMain mb-2 group-hover:text-brand-accent transition-colors">{proj.title}</h3>
            <p class="text-brand-textSec text-sm font-light line-clamp-2">{proj.description_short}...</p>
          </div>
        </a>
      {/each}
    </div>
  </div>
</section>
{/if}

<!-- FAQ -->
<section class="py-24 bg-brand-container/50 border-t border-brand-border">
  <div class="max-w-3xl mx-auto px-4">
    <h2 class="font-heading text-3xl font-black text-brand-textMain mb-12 text-center">FAQ</h2>
    <div class="space-y-4">
      {#each faqs as faq, i}
        <div class="bg-brand-container border border-brand-border rounded-2xl overflow-hidden">
          <button
            on:click={() => openFaq = openFaq === i ? -1 : i}
            class="w-full flex items-center justify-between px-6 py-5 text-left font-semibold text-brand-textMain hover:text-brand-accent transition-colors"
          >
            {faq.q}
            <span class="flex-shrink-0 ml-4 text-brand-accent transition-transform duration-300 {openFaq === i ? 'rotate-180' : ''}" style="display:inline-flex">
              <Icon name="chevron" size={16} />
            </span>
          </button>
          {#if openFaq === i}
            <div class="px-6 pb-5 text-brand-textSec text-sm font-light leading-relaxed border-t border-brand-border pt-4">
              {faq.a}
            </div>
          {/if}
        </div>
      {/each}
    </div>
  </div>
</section>

