<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';

  let data = null;
  let loading = true;
  let apiError = null;

  let stats = [
    { value: 0, target: 150, label: 'Projects Completed', suffix: '+' },
    { value: 0, target: 98,  label: 'Happy Clients',       suffix: '%' },
    { value: 0, target: 7,   label: 'Years of Experience', suffix: '+' },
    { value: 0, target: 24,  label: 'Hours Available',     suffix: '/7' },
  ];

  function animateCounters() {
    stats.forEach((stat, i) => {
      let start = 0;
      const step = Math.ceil(stat.target / 60);
      const iv = setInterval(() => {
        start = Math.min(start + step, stat.target);
        stats[i] = { ...stats[i], value: start };
        if (start >= stat.target) clearInterval(iv);
      }, 20);
    });
  }

  onMount(async () => {
    // ── Set up static observers BEFORE API call ──────────────────────────
    // Counter
    const strip = document.getElementById('stats-strip');
    if (strip) {
      const cObs = new IntersectionObserver(([e]) => {
        if (e.isIntersecting) { animateCounters(); cObs.disconnect(); }
      }, { threshold: 0.3 });
      cObs.observe(strip);
    }

    // Reveal for section headers (always in DOM immediately)
    const revObs = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

    // ── Fetch data ────────────────────────────────────────────────────────
    try {
      data = await api.home();
    } catch(e) {
      apiError = 'Waduh, koneksi ke server bermasalah. Coba lagi sebentar ya.';
    } finally { loading = false; }
  });
</script>

<!-- ── Hero ─────────────────────────────────────────────────────────────── -->
<section id="home" class="pt-24 pb-0 bg-brand-main border-b border-brand-border">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

      <!-- LEFT -->
      <div>
        <p class="text-[11px] font-bold text-brand-textSec uppercase tracking-[0.2em] mb-7">
          Software House &amp; IT Consultant · Surabaya
        </p>

        <h1 class="font-heading font-black text-brand-textMain leading-[1.1] tracking-tight mb-6"
            style="font-size: clamp(2.4rem, 5vw, 3.8rem);">
          Kami bangun teknologi<br/>
          yang <span class="text-brand-accent">benar-benar</span><br/>
          bekerja.
        </h1>

        <p class="text-base md:text-lg text-brand-textSec font-light leading-relaxed mb-9 max-w-lg">
          Dari website sederhana sampai sistem enterprise — dikerjakan dengan serius, hasilnya nyata untuk bisnis Anda.
        </p>

        <div class="flex flex-wrap gap-3 mb-10">
          <button on:click={() => document.getElementById('contact')?.scrollIntoView({behavior:'smooth'})}
            class="inline-flex items-center gap-2 bg-brand-accent text-white px-7 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-brand-accent/20 hover:brightness-110 hover:-translate-y-0.5 transition-all border-0 cursor-pointer">
            Hubungi Kami <i class="fa-solid fa-arrow-right text-xs"></i>
          </button>
          <a href="#/projects"
            class="inline-flex items-center gap-2 bg-brand-main text-brand-textMain border border-brand-border px-7 py-3.5 rounded-xl font-bold text-sm hover:border-brand-accent/50 hover:text-brand-accent hover:-translate-y-0.5 transition-all">
            Lihat Portofolio
          </a>
        </div>

        <!-- Social proof -->
        <div class="flex flex-wrap items-center gap-5 pt-6 border-t border-brand-border">
          <div class="flex items-center gap-3">
            <div class="flex -space-x-2.5">
              <div class="w-8 h-8 rounded-full bg-brand-accent border-2 border-brand-main flex items-center justify-center text-white text-[9px] font-black">AD</div>
              <div class="w-8 h-8 rounded-full bg-teal-500 border-2 border-brand-main flex items-center justify-center text-white text-[9px] font-black">RS</div>
              <div class="w-8 h-8 rounded-full bg-orange-400 border-2 border-brand-main flex items-center justify-center text-white text-[9px] font-black">BK</div>
              <div class="w-8 h-8 rounded-full bg-brand-container border-2 border-brand-main flex items-center justify-center text-[9px] font-bold text-brand-textSec">+97</div>
            </div>
            <span class="text-sm text-brand-textSec"><span class="font-bold text-brand-textMain">150+ klien</span> mempercayai kami</span>
          </div>
          <div class="w-px h-8 bg-brand-border hidden sm:block"></div>
          <div class="flex items-center gap-1.5 text-sm">
            <span class="text-yellow-400 text-xs tracking-tight">★★★★★</span>
            <span class="font-bold text-brand-textMain">4.9</span>
            <span class="text-brand-textSec font-light">· 200+ ulasan</span>
          </div>
          <div class="w-px h-8 bg-brand-border hidden sm:block"></div>
          <span class="text-sm text-brand-textSec flex items-center gap-1.5">
            <i class="fa-solid fa-clock text-brand-accent text-xs"></i>
            Respons <span class="font-bold text-brand-textMain">&lt;2 jam</span>
          </span>
        </div>
      </div>

      <!-- RIGHT: Service pillars -->
      <div class="hidden lg:flex flex-col gap-3">
        {#each [
          { href: '#/build',  label: 'BUILD',  tc: 'text-brand-accent', ic: 'fa-hammer',    desc: 'Website, aplikasi, dan sistem digital dari nol — sesuai kebutuhan bisnis Anda.' },
          { href: '#/rescue', label: 'RESCUE', tc: 'text-teal-500',     ic: 'fa-life-ring',  desc: 'Sistem error, server down, atau bug kritis? Kami tangani dalam hitungan jam.' },
          { href: '#/boost',  label: 'BOOST',  tc: 'text-orange-400',   ic: 'fa-rocket',     desc: 'Percepat loading, stabilkan server, dan otomatisasi proses bisnis Anda.' },
        ] as p}
          <a href={p.href} class="group flex items-start gap-5 p-6 rounded-2xl border border-brand-border bg-brand-container hover:-translate-y-0.5 hover:border-brand-border/0 hover:shadow-md transition-all duration-200">
            <div class="w-10 h-10 rounded-xl bg-brand-main border border-brand-border flex items-center justify-center flex-shrink-0 mt-0.5">
              <i class="fa-solid {p.ic} {p.tc} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-[10px] font-black {p.tc} uppercase tracking-widest mb-1.5">{p.label}</div>
              <p class="text-sm text-brand-textSec font-light leading-relaxed group-hover:text-brand-textMain transition-colors">{p.desc}</p>
            </div>
            <i class="fa-solid fa-chevron-right text-[10px] text-brand-textSec/30 group-hover:text-brand-accent group-hover:translate-x-0.5 transition-all mt-1 flex-shrink-0"></i>
          </a>
        {/each}

        <!-- Trust micro-badges -->
        <div class="flex gap-2 mt-1">
          <span class="flex-1 bg-brand-container border border-brand-border rounded-xl px-4 py-3 text-center text-[10px] font-semibold text-brand-textSec">
            <i class="fa-solid fa-circle-check text-green-500 mr-1"></i>Terdaftar NIB
          </span>
          <span class="flex-1 bg-brand-container border border-brand-border rounded-xl px-4 py-3 text-center text-[10px] font-semibold text-brand-textSec">
            <i class="fa-solid fa-shield-halved text-teal-500 mr-1"></i>Garansi 3 Bulan
          </span>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ── Stats ──────────────────────────────────────────────────────────── -->
<section class="bg-brand-container border-y border-brand-border py-6">
  <div class="max-w-7xl mx-auto px-4">
    <div id="stats-strip" class="stats-strip">
      {#each stats as s}
        <div class="stats-item">
          <div class="text-3xl font-black text-brand-accent font-heading">{s.value}{s.suffix}</div>
          <div class="text-xs text-brand-textSec uppercase tracking-widest mt-1 font-semibold">{s.label}</div>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- ── Services / Pillars ─────────────────────────────────────────────── -->
<section id="services" class="py-32 bg-brand-main">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-20 reveal">
      <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-6">Our Services</span>
      <h2 class="font-heading text-4xl md:text-5xl font-black text-brand-textMain mb-6">One Team, All Solutions</h2>
      <p class="text-brand-textSec max-w-2xl mx-auto font-light text-lg">
        Mau bikin yang baru, fix yang sudah ada, atau dorong bisnis Anda supaya makin scale — semua bisa kami handle.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <a href="#/build" class="group bento-item bg-brand-container rounded-[2.5rem] p-10 border border-brand-border hover:border-brand-accent/40 card-glow block">
        <div class="w-16 h-16 bg-brand-accent/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-accent/20 transition-colors">
          <i class="fa-solid fa-hammer text-2xl text-brand-accent"></i>
        </div>
        <span class="inline-block text-[10px] font-black uppercase tracking-[0.2em] text-brand-accent bg-brand-accent/10 px-3 py-1 rounded-full mb-4">BUILD</span>
        <h3 class="text-2xl font-bold text-brand-textMain mb-4 group-hover:text-brand-accent transition-colors">Build from Scratch</h3>
        <p class="text-brand-textSec text-sm font-light leading-relaxed mb-6">
          Ada ide tapi belum jadi product? Kami bantu wujudkan — dari website, mobile app, sampai custom system buat kebutuhan bisnis Anda.
        </p>
        <div class="flex flex-wrap gap-2 mb-8">
          {#each ['Laravel', 'React', 'Flutter', 'Node.js'] as tech}
            <span class="text-[10px] font-bold uppercase tracking-wider bg-brand-main border border-brand-border text-brand-textSec px-3 py-1 rounded-full">{tech}</span>
          {/each}
        </div>
        <div class="flex items-center gap-2 text-brand-accent font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
          Learn More <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </div>
      </a>

      <a href="#/rescue" class="group bento-item bg-brand-container rounded-[2.5rem] p-10 border border-brand-border hover:border-teal-500/40 card-glow block">
        <div class="w-16 h-16 bg-teal-500/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-teal-500/20 transition-colors">
          <i class="fa-solid fa-life-ring text-2xl text-teal-500"></i>
        </div>
        <span class="inline-block text-[10px] font-black uppercase tracking-[0.2em] text-teal-500 bg-teal-500/10 px-3 py-1 rounded-full mb-4">RESCUE</span>
        <h3 class="text-2xl font-bold text-brand-textMain mb-4 group-hover:text-teal-500 transition-colors">Fix &amp; Rescue</h3>
        <p class="text-brand-textSec text-sm font-light leading-relaxed mb-6">
          Website tiba-tiba down? Aplikasi error? Data hilang? Tim kami langsung action dan resolve masalahnya — bisa di hari yang sama juga.
        </p>
        <div class="flex flex-wrap gap-2 mb-8">
          {#each ['Bug Fix', 'Data Recovery', 'Server Migration', 'Security Audit'] as tech}
            <span class="text-[10px] font-bold uppercase tracking-wider bg-brand-main border border-brand-border text-brand-textSec px-3 py-1 rounded-full">{tech}</span>
          {/each}
        </div>
        <div class="flex items-center gap-2 text-teal-500 font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
          Learn More <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </div>
      </a>

      <a href="#/boost" class="group bento-item bg-brand-container rounded-[2.5rem] p-10 border border-brand-border hover:border-orange-500/40 card-glow block">
        <div class="w-16 h-16 bg-orange-500/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-orange-500/20 transition-colors">
          <i class="fa-solid fa-rocket text-2xl text-orange-400"></i>
        </div>
        <span class="inline-block text-[10px] font-black uppercase tracking-[0.2em] text-orange-400 bg-orange-500/10 px-3 py-1 rounded-full mb-4">BOOST</span>
        <h3 class="text-2xl font-bold text-brand-textMain mb-4 group-hover:text-orange-400 transition-colors">Scale Up Your Business</h3>
        <p class="text-brand-textSec text-sm font-light leading-relaxed mb-6">
          Bisnis sudah jalan tapi pengen lebih performa, lebih efisien, dan siap compete? Kami bantu optimize dari dalamnya — biar Anda bisa fokus ke growth.
        </p>
        <div class="flex flex-wrap gap-2 mb-8">
          {#each ['Performance', 'Cloud Server', 'Automation', 'Monitoring'] as tech}
            <span class="text-[10px] font-bold uppercase tracking-wider bg-brand-main border border-brand-border text-brand-textSec px-3 py-1 rounded-full">{tech}</span>
          {/each}
        </div>
        <div class="flex items-center gap-2 text-orange-400 font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
          Learn More <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ── Latest Projects ────────────────────────────────────────────────── -->
<section id="portfolio" class="py-32 bg-brand-container/50 border-t border-brand-border">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-end mb-16 reveal">
      <div>
        <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4">Portfolio</span>
        <h2 class="font-heading text-4xl md:text-5xl font-black text-brand-textMain">Our Recent Work</h2>
      </div>
      <a href="#/projects" class="mt-6 md:mt-0 text-brand-accent font-bold text-sm flex items-center gap-2 hover:gap-4 transition-all">
        View All Projects <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    {#if loading}
      <div class="flex justify-center py-20"><div class="spinner"></div></div>
    {:else if apiError}
      <div class="text-center py-16 bg-brand-container rounded-3xl border border-brand-border">
        <i class="fa-solid fa-triangle-exclamation text-3xl text-orange-400 mb-3 block"></i>
        <p class="text-brand-textSec text-sm">{apiError}</p>
      </div>
    {:else if !data?.projects?.length}
      <div class="text-center py-16 bg-brand-container rounded-3xl border border-brand-border">
        <i class="fa-solid fa-folder-open text-4xl text-brand-textSec/30 mb-3 block"></i>
        <p class="text-brand-textSec font-light">Projects coming soon — stay tuned!</p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {#each data.projects as proj}
          <!-- No reveal class — dynamically rendered, observer not set up yet -->
          <a href="#/project/{proj.slug}"
            class="group bento-item bg-brand-container rounded-[2.5rem] overflow-hidden border border-brand-border card-glow block">
            <div class="relative h-56 overflow-hidden bg-brand-main">
              <div class="absolute top-4 left-4 flex gap-2 z-10">
                {#if proj.pilar_build}<span class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-brand-accent rounded-md border border-brand-accent/30 uppercase">BUILD</span>{/if}
                {#if proj.pilar_rescue}<span class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-teal-400 rounded-md border border-teal-500/30 uppercase">RESCUE</span>{/if}
                {#if proj.pilar_boost}<span class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-orange-400 rounded-md border border-orange-500/30 uppercase">BOOST</span>{/if}
              </div>
              {#if proj.image_url}
                <img src={proj.image_url} alt={proj.title}
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
              {:else}
                <div class="w-full h-full flex items-center justify-center text-brand-textSec/10">
                  <i class="fa-solid fa-layer-group text-8xl rotate-12"></i>
                </div>
              {/if}
              <div class="absolute inset-0 bg-gradient-to-t from-brand-main/60 to-transparent"></div>
            </div>
            <div class="p-8">
              <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-brand-accent uppercase tracking-widest bg-brand-accent/10 px-3 py-1 rounded-full border border-brand-accent/20">
                  {proj.category_name || 'Digital'}
                </span>
                <span class="text-[10px] text-brand-textSec font-medium uppercase opacity-50">{proj.client_name || ''}</span>
              </div>
              <h3 class="text-xl font-bold text-brand-textMain mb-3 group-hover:text-brand-accent transition-colors">{proj.title}</h3>
              <p class="text-brand-textSec text-sm font-light line-clamp-2">{proj.description_short || ''}...</p>
              <div class="mt-6 flex items-center gap-2 text-brand-accent font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
                View Case Study <i class="fa-solid fa-arrow-right text-[10px]"></i>
              </div>
            </div>
          </a>
        {/each}
      </div>
    {/if}
  </div>
</section>

<!-- ── Testimonials ───────────────────────────────────────────────────── -->
<section class="py-24 bg-brand-main border-t border-brand-border">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-14 reveal">
      <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4">Testimonials</span>
      <h2 class="font-heading text-4xl font-black text-brand-textMain">What Our Clients Say</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      {#each [
        { name: 'Budi Santoso',  role: 'Owner, DataKu SaaS',          av: 'B', color: 'bg-orange-500',  text: 'Setelah dibantu INSPIMA, biaya server kami turun sampai 45% dan deploy fitur baru yang dulu butuh 2 jam sekarang cuma 8 menit. The results exceeded our expectations!' },
        { name: 'Siti Rahayu',   role: 'CTO, FinTech Nusantara',      av: 'S', color: 'bg-teal-500',    text: 'Sistem kami down total sehari sebelum investor presentation. Tim INSPIMA langsung response dalam 45 menit, dan 3 jam kemudian everything was back to normal. Lega banget!' },
        { name: 'Ahmad Fauzi',   role: 'Founder, TokoPintar.id',      av: 'A', color: 'bg-brand-accent', text: 'INSPIMA built our online store dari nol cuma dalam 8 minggu. Hasilnya clean, user-friendly, dan timnya selalu fast response kalau ada yang ditanyain.' },
      ] as t}
        <div class="bg-brand-container rounded-3xl p-8 border border-brand-border card-glow">
          <div class="flex mb-4">
            {#each [1,2,3,4,5] as _}<i class="fa-solid fa-star text-yellow-400 text-sm"></i>{/each}
          </div>
          <p class="text-brand-textSec text-sm font-light leading-relaxed mb-6 italic">"{t.text}"</p>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full {t.color} flex items-center justify-center text-white font-black">{t.av}</div>
            <div>
              <div class="font-bold text-brand-textMain text-sm">{t.name}</div>
              <div class="text-brand-textSec text-xs">{t.role}</div>
            </div>
          </div>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- ── Latest Articles ────────────────────────────────────────────────── -->
<section class="py-32 bg-brand-container/50 border-t border-brand-border">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-end mb-16 reveal">
      <div>
        <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4">Tips &amp; Insights</span>
        <h2 class="font-heading text-4xl md:text-5xl font-black text-brand-textMain">Latest Articles</h2>
      </div>
      <a href="#/blog" class="mt-6 md:mt-0 text-brand-accent font-bold text-sm flex items-center gap-2 hover:gap-4 transition-all">
        View All Articles <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    {#if loading}
      <div class="flex justify-center py-20"><div class="spinner"></div></div>
    {:else if !data?.articles?.length}
      <div class="text-center py-16 bg-brand-container rounded-3xl border border-brand-border">
        <i class="fa-solid fa-pencil text-4xl text-brand-textSec/30 mb-3 block"></i>
        <p class="text-brand-textSec font-light">Articles coming soon — stay tuned!</p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {#each data.articles as art}
          <!-- No reveal class — dynamically rendered -->
          <a href="#/article/{art.slug}"
            class="group bento-item bg-brand-container rounded-[2.5rem] overflow-hidden border border-brand-border card-glow flex flex-col block">
            <div class="relative h-52 overflow-hidden bg-brand-main p-4">
              {#if art.image_url}
                <img src={art.image_url} alt={art.title}
                  class="w-full h-full object-cover rounded-[1.5rem] group-hover:scale-110 transition-transform duration-700">
              {:else}
                <div class="w-full h-full rounded-[1.5rem] flex items-center justify-center bg-brand-main border border-brand-border text-brand-textSec/10">
                  <i class="fa-solid fa-newspaper text-7xl rotate-6"></i>
                </div>
              {/if}
              <div class="absolute top-8 left-8">
                <span class="bg-brand-main/80 backdrop-blur-md text-brand-accent border border-brand-accent/30 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest">
                  {art.category_name || 'Blog'}
                </span>
              </div>
            </div>
            <div class="p-8 flex flex-col flex-grow">
              <div class="flex items-center gap-3 text-xs text-brand-textSec mb-4 font-medium">
                <span class="flex items-center gap-1">
                  <i class="fa-regular fa-calendar-check text-brand-accent"></i>
                  {new Date(art.created_at).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'})}
                </span>
                <span class="w-1 h-1 bg-brand-border rounded-full"></span>
                <span>{art.read_time} min read</span>
              </div>
              <h3 class="text-xl font-bold text-brand-textMain mb-3 group-hover:text-brand-accent transition-colors leading-tight flex-grow">{art.title}</h3>
              <p class="text-brand-textSec text-sm font-light line-clamp-2">{art.excerpt || ''}...</p>
              <div class="mt-6 pt-5 border-t border-brand-border/50 flex items-center gap-2 text-brand-accent font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
                Read Article <i class="fa-solid fa-chevron-right text-[10px]"></i>
              </div>
            </div>
          </a>
        {/each}
      </div>
    {/if}
  </div>
</section>
