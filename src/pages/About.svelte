<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';

  let about = null;
  let team = [];
  let loading = true;

  // Defaults shown while API loads or if content is empty
  const defaults = {
    headline: 'Tentang INSPIMA',
    tagline: 'Software house & IT consultant dari Surabaya yang fokus menghasilkan teknologi yang benar-benar bekerja untuk bisnis Anda.',
    description: '<p>INSPIMA hadir untuk membantu bisnis dari berbagai skala — dari startup hingga enterprise — dalam membangun, memperbaiki, dan mengoptimalkan teknologi digital mereka.</p><p>Dengan pengalaman lebih dari 7 tahun dan 150+ proyek yang telah diselesaikan, kami memahami bahwa teknologi yang baik bukan hanya soal kode yang berjalan, tapi soal dampak nyata pada bisnis.</p>',
    mission: 'Memberikan solusi teknologi yang efektif, efisien, dan dapat diandalkan — sehingga bisnis klien kami bisa tumbuh tanpa hambatan teknis.',
    vision: 'Menjadi mitra teknologi terpercaya bagi ribuan bisnis di Indonesia, dikenal karena kualitas kerja dan kejujuran dalam setiap hubungan.',
  };

  onMount(async () => {
    try {
      const res = await api.about();
      about = res.about || {};
      team  = res.team  || [];
    } catch(e) {
      about = {};
      team  = [];
    }
    loading = false;
  });

  $: headline    = about?.headline    || defaults.headline;
  $: tagline     = about?.tagline     || defaults.tagline;
  $: description = about?.description || defaults.description;
  $: mission     = about?.mission     || defaults.mission;
  $: vision      = about?.vision      || defaults.vision;
</script>

<!-- Hero -->
<section class="pt-32 pb-20 bg-brand-main border-b border-brand-border">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
    <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-6">Tentang Kami</span>
    {#if loading}
      <div class="h-16 w-64 bg-brand-border/30 rounded-xl animate-pulse mx-auto mb-5"></div>
      <div class="h-5 w-96 bg-brand-border/30 rounded animate-pulse mx-auto"></div>
    {:else}
      <h1 class="hero-heading">{headline}</h1>
      <p class="text-lg text-brand-textSec font-light leading-relaxed max-w-2xl mx-auto">{tagline}</p>
    {/if}
  </div>
</section>

<!-- Stats strip -->
<section class="py-10 bg-brand-container border-y border-brand-border">
  <div class="max-w-4xl mx-auto px-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-brand-border rounded-2xl overflow-hidden">
      {#each [
        { v: '150+', l: 'Proyek Selesai' },
        { v: '98%',  l: 'Klien Puas' },
        { v: '7+',   l: 'Tahun Pengalaman' },
        { v: '24/7', l: 'Siap Dihubungi' },
      ] as s}
        <div class="bg-brand-container py-7 text-center">
          <div class="text-3xl font-black text-brand-accent font-heading mb-1">{s.v}</div>
          <div class="text-[10px] text-brand-textSec uppercase tracking-widest font-semibold">{s.l}</div>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- Company story -->
<section class="py-24 bg-brand-main">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
      <div>
        <span class="text-[10px] font-black text-brand-accent uppercase tracking-[0.2em]">Cerita Kami</span>
        <h2 class="font-heading text-3xl md:text-4xl font-black text-brand-textMain mt-3 mb-6 leading-tight">
          Teknologi seharusnya memudahkan, bukan mempersulit.
        </h2>
        {#if loading}
          <div class="space-y-3">
            <div class="h-4 bg-brand-border/30 rounded animate-pulse w-full"></div>
            <div class="h-4 bg-brand-border/30 rounded animate-pulse w-4/5"></div>
            <div class="h-4 bg-brand-border/30 rounded animate-pulse w-full"></div>
            <div class="h-4 bg-brand-border/30 rounded animate-pulse w-3/4"></div>
          </div>
        {:else}
          <div class="prose prose-lg max-w-none text-brand-textSec">
            {@html description}
          </div>
        {/if}
      </div>

      <!-- Values -->
      <div class="space-y-4">
        {#each [
          { icon: 'fa-handshake', title: 'Jujur & Transparan', desc: 'Kami bilang apa adanya — tidak ada biaya tersembunyi, tidak ada janji yang tidak bisa ditepati.' },
          { icon: 'fa-medal',     title: 'Kualitas Dulu',      desc: 'Setiap baris kode dan setiap desain kami kerjakan dengan standar yang tidak kami kompromikan.' },
          { icon: 'fa-headset',   title: 'Support Nyata',      desc: 'Kami tidak menghilang setelah proyek selesai. Anda bisa hubungi kami kapan pun butuh bantuan.' },
          { icon: 'fa-bullseye',  title: 'Fokus pada Hasil',   desc: 'Bukan sekedar website yang cantik — kami fokus pada teknologi yang benar-benar berdampak untuk bisnis Anda.' },
        ] as v}
          <div class="flex items-start gap-4 p-5 bg-brand-container rounded-2xl border border-brand-border">
            <div class="w-10 h-10 rounded-xl bg-brand-accent/10 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid {v.icon} text-brand-accent text-sm"></i>
            </div>
            <div>
              <div class="font-bold text-brand-textMain text-sm mb-1">{v.title}</div>
              <p class="text-brand-textSec text-sm font-light leading-relaxed">{v.desc}</p>
            </div>
          </div>
        {/each}
      </div>
    </div>
  </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-brand-container/50 border-t border-brand-border">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Mission -->
      <div class="bg-brand-container rounded-3xl p-10 border border-brand-border">
        <div class="w-12 h-12 rounded-2xl bg-brand-accent/10 flex items-center justify-center mb-6">
          <i class="fa-solid fa-compass text-brand-accent text-lg"></i>
        </div>
        <div class="text-[10px] font-black text-brand-accent uppercase tracking-[0.2em] mb-3">Misi</div>
        {#if loading}
          <div class="space-y-2">
            <div class="h-4 bg-brand-border/30 rounded animate-pulse"></div>
            <div class="h-4 bg-brand-border/30 rounded animate-pulse w-4/5"></div>
          </div>
        {:else}
          <p class="text-brand-textMain text-base font-light leading-relaxed">{mission}</p>
        {/if}
      </div>
      <!-- Vision -->
      <div class="bg-brand-accent rounded-3xl p-10 text-white">
        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-6">
          <i class="fa-solid fa-eye text-white text-lg"></i>
        </div>
        <div class="text-[10px] font-black text-white/70 uppercase tracking-[0.2em] mb-3">Visi</div>
        {#if loading}
          <div class="space-y-2">
            <div class="h-4 bg-white/20 rounded animate-pulse"></div>
            <div class="h-4 bg-white/20 rounded animate-pulse w-4/5"></div>
          </div>
        {:else}
          <p class="text-white text-base font-light leading-relaxed">{vision}</p>
        {/if}
      </div>
    </div>
  </div>
</section>

<!-- Team -->
{#if team.length > 0}
  <section class="py-24 bg-brand-main border-t border-brand-border">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-14">
        <span class="text-[10px] font-black text-brand-accent uppercase tracking-[0.2em]">Tim Kami</span>
        <h2 class="font-heading text-3xl md:text-4xl font-black text-brand-textMain mt-3">Orang-orang di balik INSPIMA</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each team as member}
          <div class="bg-brand-container rounded-3xl overflow-hidden border border-brand-border text-center">
            <!-- Avatar -->
            <div class="h-48 bg-brand-main flex items-center justify-center">
              {#if member.photo_url}
                <img src={member.photo_url} alt={member.name} class="w-full h-full object-cover object-top">
              {:else}
                <div class="w-24 h-24 rounded-full bg-brand-accent/10 border-2 border-brand-border flex items-center justify-center text-3xl font-black text-brand-accent">
                  {(member.name || '?').charAt(0).toUpperCase()}
                </div>
              {/if}
            </div>
            <div class="p-6">
              <h3 class="font-bold text-brand-textMain text-base mb-1">{member.name}</h3>
              {#if member.role}<p class="text-brand-accent text-xs font-bold uppercase tracking-widest mb-3">{member.role}</p>{/if}
              {#if member.bio}<p class="text-brand-textSec text-sm font-light leading-relaxed">{member.bio}</p>{/if}
              {#if member.linkedin_url}
                <a href={member.linkedin_url} target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 mt-4 text-xs font-semibold text-brand-textSec hover:text-brand-accent transition-colors">
                  <i class="fa-brands fa-linkedin text-sm"></i> LinkedIn
                </a>
              {/if}
            </div>
          </div>
        {/each}
      </div>
    </div>
  </section>
{/if}

<!-- Pillars -->
<section class="py-20 bg-brand-container/50 border-t border-brand-border">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="font-heading text-3xl font-black text-brand-textMain">Tiga Pilar Layanan Kami</h2>
      <p class="text-brand-textSec font-light mt-3 max-w-xl mx-auto">Setiap solusi yang kami tawarkan masuk ke dalam salah satu dari tiga pilar ini.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      {#each [
        { href: '#/build',  label: 'BUILD',  tc: 'text-brand-accent', bg: 'bg-brand-accent', icon: 'fa-hammer',    desc: 'Bangun produk digital dari nol — website, aplikasi mobile, dan sistem enterprise.' },
        { href: '#/rescue', label: 'RESCUE', tc: 'text-teal-500',     bg: 'bg-teal-500',     icon: 'fa-life-ring',  desc: 'Selamatkan sistem yang bermasalah — dari bug kritis hingga server yang tidak stabil.' },
        { href: '#/boost',  label: 'BOOST',  tc: 'text-orange-400',   bg: 'bg-orange-500',   icon: 'fa-rocket',     desc: 'Tingkatkan performa dan skalabilitas sistem yang sudah berjalan.' },
      ] as p}
        <a href={p.href} class="group bg-brand-container rounded-3xl p-8 border border-brand-border hover:-translate-y-1 hover:shadow-lg transition-all">
          <div class="w-12 h-12 {p.bg}/10 rounded-2xl flex items-center justify-center mb-5">
            <i class="fa-solid {p.icon} {p.tc} text-lg"></i>
          </div>
          <div class="text-[10px] font-black {p.tc} uppercase tracking-[0.2em] mb-2">{p.label}</div>
          <p class="text-brand-textSec text-sm font-light leading-relaxed group-hover:text-brand-textMain transition-colors">{p.desc}</p>
        </a>
      {/each}
    </div>
  </div>
</section>


