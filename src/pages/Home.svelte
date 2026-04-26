<script>
  import { onMount } from "svelte";
  import { api } from "../lib/api.js";

  let data = null;
  let loading = true;
  let apiError = null;

  let stats = [
    { value: 0, target: 98, label: "Happy Clients", suffix: "%" },
    { value: 0, target: 7, label: "Years of Experience", suffix: "+" },
    { value: 0, target: 24, label: "Hours Available", suffix: "/7" },
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
    const strip = document.getElementById("stats-strip");
    if (strip) {
      const cObs = new IntersectionObserver(
        ([e]) => {
          if (e.isIntersecting) {
            animateCounters();
            cObs.disconnect();
          }
        },
        { threshold: 0.3 },
      );
      cObs.observe(strip);
    }

    // Reveal for section headers (always in DOM immediately)
    const revObs = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) e.target.classList.add("visible");
        });
      },
      { threshold: 0.08 },
    );
    document.querySelectorAll(".reveal").forEach((el) => revObs.observe(el));

    // ── Fetch data ────────────────────────────────────────────────────────
    try {
      data = await api.home();
    } catch (e) {
      apiError = "Waduh, koneksi ke server bermasalah. Coba lagi sebentar ya.";
    } finally {
      loading = false;
    }
  });
</script>

<!-- ── Hero ─────────────────────────────────────────────────────────────── -->
<section id="home" class="hero-section pt-24 pb-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-0">
    <!-- Stats pill bar -->
    <div class="hero-stats-pill mb-10 reveal hidden lg:inline-flex">
      <div class="hero-stat-item">
        <span class="hero-stat-dots">
          <span class="dot dot-red"></span>
          <span class="dot dot-yellow"></span>
          <span class="dot dot-green"></span>
        </span>
        <span class="hero-stat-value">30+</span>
        <span class="hero-stat-label">proyek selesai</span>
      </div>
      <div class="hero-stat-divider"></div>
      <div class="hero-stat-item">
        <span class="hero-stat-label">Sejak</span>
        <span class="hero-stat-year">2019</span>
      </div>
      <div class="hero-stat-divider"></div>
      <div class="hero-stat-item hero-stat-booking">
        <span class="dot dot-green booking-dot"></span>
        <span class="hero-stat-label">Open to Project</span>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
      <!-- LEFT -->
      <div class="hero-left">
        <h1 class="hero-heading">
          Kami bangun<br />
          teknologi yang<br />
          <em class="hero-italic-accent">benar-benar bekerja.</em>
        </h1>

        <p class="hero-subtext">
          Build. Rescue. Boost. Inspima adalah studio engineering senior untuk
          para founder dan product team yang membutuhkan software yang berjalan
          — dan terus berjalan setelah peluncuran.
        </p>

        <div class="hero-cta-row">
          <button
            on:click={() =>
              document
                .getElementById("contact")
                ?.scrollIntoView({ behavior: "smooth" })}
            class="hero-btn-primary"
          >
            Mulai Proyek <i class="fa-solid fa-arrow-right text-xs"></i>
          </button>
          <a href="#/projects" class="hero-btn-ghost"> Lihat Portofolio </a>
        </div>
      </div>

      <!-- RIGHT: Dashboard mockup -->
      <div class="hero-mockup-wrap">
        <div class="hero-mockup">
          <!-- Window chrome -->
          <div class="mockup-chrome">
            <span class="chrome-dot chrome-red"></span>
            <span class="chrome-dot chrome-yellow"></span>
            <span class="chrome-dot chrome-green"></span>
            <div class="chrome-address">
              <i class="fa-solid fa-lock text-[9px] mr-1" style="opacity:0.4"
              ></i>
              <span>inspima.dev / dashboard</span>
            </div>
            <span class="chrome-version">v4.2</span>
          </div>

          <!-- Dashboard body -->
          <div class="mockup-body">
            <!-- Sidebar -->
            <div class="mockup-sidebar">
              <p class="sidebar-label">WORKSPACE</p>
              <ul class="sidebar-nav">
                <li class="sidebar-item active">
                  <i class="fa-solid fa-house-chimney sidebar-icon"></i> Overview
                </li>
                <li class="sidebar-item">
                  <i class="fa-solid fa-diagram-project sidebar-icon"></i> Projects
                </li>
                <li class="sidebar-item sidebar-sub">
                  <i class="fa-solid fa-code-branch sidebar-icon"></i> Pipelines
                </li>
                <li class="sidebar-item sidebar-sub">
                  <i class="fa-solid fa-shield-halved sidebar-icon"></i> Monitoring
                </li>
                <li class="sidebar-item">
                  <i class="fa-solid fa-triangle-exclamation sidebar-icon"></i> Incidents
                </li>
              </ul>
              <div class="sidebar-divider"></div>
              <p class="sidebar-label">TEAM</p>
              <div class="sidebar-avatars">
                <div class="s-av s-av-blue">AD</div>
                <div class="s-av s-av-teal">RS</div>
                <div class="s-av s-av-orange">BK</div>
                <span class="s-av-more">+5</span>
              </div>
            </div>

            <!-- Main content -->
            <div class="mockup-main">
              <!-- Top stat row -->
              <div class="m-stat-row">
                <div class="m-stat-card">
                  <div class="m-stat-label">Uptime</div>
                  <div class="m-stat-value text-green-500">98.7%</div>
                  <div class="m-stat-delta">↑ 0.3%</div>
                </div>
                <div class="m-stat-card">
                  <div class="m-stat-label">Deploys</div>
                  <div class="m-stat-value">24</div>
                  <div class="m-stat-delta">this month</div>
                </div>
                <div class="m-stat-card">
                  <div class="m-stat-label">Latency</div>
                  <div class="m-stat-value text-brand-accent">42ms</div>
                  <div class="m-stat-delta">↓ 8ms</div>
                </div>
              </div>

              <!-- Chart area -->
              <div class="m-chart-wrap">
                <div class="m-chart-header">
                  <span class="m-chart-title">Request Traffic</span>
                  <span class="m-chart-badge">30d</span>
                </div>
                <svg
                  viewBox="0 0 260 52"
                  class="m-chart"
                  preserveAspectRatio="none"
                >
                  <defs>
                    <linearGradient id="gArea" x1="0" y1="0" x2="0" y2="1">
                      <stop
                        offset="0%"
                        stop-color="rgb(var(--color-accent))"
                        stop-opacity="0.22"
                      />
                      <stop
                        offset="100%"
                        stop-color="rgb(var(--color-accent))"
                        stop-opacity="0"
                      />
                    </linearGradient>
                  </defs>
                  <!-- Grid lines -->
                  <line
                    x1="0"
                    y1="13"
                    x2="260"
                    y2="13"
                    stroke="rgb(var(--color-border))"
                    stroke-width="0.5"
                  />
                  <line
                    x1="0"
                    y1="26"
                    x2="260"
                    y2="26"
                    stroke="rgb(var(--color-border))"
                    stroke-width="0.5"
                  />
                  <line
                    x1="0"
                    y1="39"
                    x2="260"
                    y2="39"
                    stroke="rgb(var(--color-border))"
                    stroke-width="0.5"
                  />
                  <!-- Area fill -->
                  <path
                    d="M0 42 C30 38 50 28 80 22 S120 18 150 14 S200 10 230 8 L260 6 V52 H0Z"
                    fill="url(#gArea)"
                  />
                  <!-- Line -->
                  <path
                    d="M0 42 C30 38 50 28 80 22 S120 18 150 14 S200 10 230 8 L260 6"
                    fill="none"
                    stroke="rgb(var(--color-accent))"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <!-- Tooltip dot -->
                  <circle
                    cx="150"
                    cy="14"
                    r="3.5"
                    fill="rgb(var(--color-accent))"
                  />
                  <circle
                    cx="150"
                    cy="14"
                    r="6"
                    fill="rgb(var(--color-accent))"
                    opacity="0.2"
                  />
                </svg>
              </div>

              <!-- Progress: services -->
              <div class="m-progress-wrap">
                <div class="m-prog-label">
                  <span>Build</span><span class="text-brand-accent font-bold"
                    >87%</span
                  >
                </div>
                <div class="m-prog-bar">
                  <div
                    class="m-prog-fill"
                    style="width:87%;background:rgb(var(--color-accent))"
                  ></div>
                </div>
                <div class="m-prog-label">
                  <span>Rescue</span><span
                    style="color:#14b8a6"
                    class="font-bold">62%</span
                  >
                </div>
                <div class="m-prog-bar">
                  <div
                    class="m-prog-fill"
                    style="width:62%;background:#14b8a6"
                  ></div>
                </div>
                <div class="m-prog-label">
                  <span>Boost</span><span
                    style="color:#f97316"
                    class="font-bold">45%</span
                  >
                </div>
                <div class="m-prog-bar">
                  <div
                    class="m-prog-fill"
                    style="width:45%;background:#f97316"
                  ></div>
                </div>
              </div>

              <!-- Activity feed -->
              <div class="m-activity">
                <div class="m-act-item">
                  <span class="m-act-dot" style="background:#22c55e"></span>
                  <span class="m-act-text">Deploy <b>v2.4.1</b> berhasil</span>
                  <span class="m-act-time">2m</span>
                </div>
                <div class="m-act-item">
                  <span
                    class="m-act-dot"
                    style="background:rgb(var(--color-accent))"
                  ></span>
                  <span class="m-act-text">Pipeline <b>staging</b> passed</span>
                  <span class="m-act-time">14m</span>
                </div>
                <div class="m-act-item">
                  <span class="m-act-dot" style="background:#f97316"></span>
                  <span class="m-act-text">Alert: CPU spike <b>78%</b></span>
                  <span class="m-act-time">1h</span>
                </div>
              </div>
            </div>
            <!-- /mockup-main -->
          </div>
          <!-- /mockup-body -->
        </div>
        <!-- /hero-mockup -->
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
          <div class="text-3xl font-black text-brand-accent font-heading">
            {s.value}{s.suffix}
          </div>
          <div
            class="text-xs text-brand-textSec uppercase tracking-widest mt-1 font-semibold"
          >
            {s.label}
          </div>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- ── Services / Pillars ─────────────────────────────────────────────── -->
<section id="services" class="svc-section">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16 reveal">
      <span class="svc-eyebrow">Our Services</span>
      <h2 class="svc-heading">One Team, All Solutions</h2>
      <p class="svc-sub">
        Mau bikin yang baru, fix yang rusak, atau scale lebih jauh — kami siap handle semuanya.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- BUILD -->
      <a href="#/build" class="svc-card group svc-card-build">
        <div class="svc-icon-wrap svc-color-blue">
          <i class="fa-solid fa-cube"></i>
        </div>
        <h3 class="svc-title">Build from Scratch</h3>
        <p class="svc-desc">
          Ada ide tapi belum jadi product? Kami bantu wujudkan — dari website, mobile app, sampai custom system buat kebutuhan bisnis Anda.
        </p>
        <div class="svc-pills">
          <span class="svc-pill">LARAVEL</span>
          <span class="svc-pill">REACT</span>
          <span class="svc-pill">FLUTTER</span>
          <span class="svc-pill">NODE.JS</span>
        </div>
        <div class="svc-cta svc-color-blue">LEARN MORE <i class="fa-solid fa-arrow-right"></i></div>
      </a>

      <!-- RESCUE -->
      <a href="#/rescue" class="svc-card group svc-card-rescue">
        <div class="svc-icon-wrap svc-color-green">
          <i class="fa-solid fa-asterisk"></i>
        </div>
        <h3 class="svc-title">Fix &amp; Rescue</h3>
        <p class="svc-desc">
          Website tiba-tiba down? Aplikasi error? Data hilang? Tim kami langsung action dan resolve masalahnya — bisa di hari yang sama juga.
        </p>
        <div class="svc-pills">
          <span class="svc-pill">BUG FIX</span>
          <span class="svc-pill">DATA RECOVERY</span>
          <span class="svc-pill">SERVER MIGRATION</span>
          <span class="svc-pill">SECURITY AUDIT</span>
        </div>
        <div class="svc-cta svc-color-green">LEARN MORE <i class="fa-solid fa-arrow-right"></i></div>
      </a>

      <!-- BOOST -->
      <a href="#/boost" class="svc-card group svc-card-boost">
        <div class="svc-icon-wrap svc-color-yellow">
          <i class="fa-solid fa-bolt"></i>
        </div>
        <h3 class="svc-title">Scale Up Your Business</h3>
        <p class="svc-desc">
          Bisnis sudah jalan tapi pengen lebih performa, lebih efisien, dan siap compete? Kami bantu optimize dari dalamnya — biar Anda bisa fokus ke growth.
        </p>
        <div class="svc-pills">
          <span class="svc-pill">PERFORMANCE</span>
          <span class="svc-pill">CLOUD SERVER</span>
          <span class="svc-pill">AUTOMATION</span>
          <span class="svc-pill">MONITORING</span>
        </div>
        <div class="svc-cta svc-color-yellow">LEARN MORE <i class="fa-solid fa-arrow-right"></i></div>
      </a>
    </div>
  </div>
</section>



<!-- ── Latest Projects ────────────────────────────────────────────────── -->
<section
  id="portfolio"
  class="py-32 bg-brand-container/50 border-t border-brand-border"
>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div
      class="flex flex-col md:flex-row justify-between items-end mb-16 reveal"
    >
      <div>
        <span
          class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4"
          >Portfolio</span
        >
        <h2
          class="font-heading text-4xl md:text-5xl font-black text-brand-textMain"
        >
          Our Recent Work
        </h2>
      </div>
      <a
        href="#/projects"
        class="mt-6 md:mt-0 text-brand-accent font-bold text-sm flex items-center gap-2 hover:gap-4 transition-all"
      >
        View All Projects <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    {#if loading}
      <div class="flex justify-center py-20"><div class="spinner"></div></div>
    {:else if apiError}
      <div
        class="text-center py-16 bg-brand-container rounded-3xl border border-brand-border"
      >
        <i
          class="fa-solid fa-triangle-exclamation text-3xl text-orange-400 mb-3 block"
        ></i>
        <p class="text-brand-textSec text-sm">{apiError}</p>
      </div>
    {:else if !data?.projects?.length}
      <div
        class="text-center py-16 bg-brand-container rounded-3xl border border-brand-border"
      >
        <i
          class="fa-solid fa-folder-open text-4xl text-brand-textSec/30 mb-3 block"
        ></i>
        <p class="text-brand-textSec font-light">
          Projects coming soon — stay tuned!
        </p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {#each data.projects as proj}
          <!-- No reveal class — dynamically rendered, observer not set up yet -->
          <a
            href="#/project/{proj.slug}"
            class="group bento-item bg-brand-container rounded-[2.5rem] overflow-hidden border border-brand-border card-glow block"
          >
            <div class="relative h-56 overflow-hidden bg-brand-main">
              <div class="absolute top-4 left-4 flex gap-2 z-10">
                {#if proj.pilar_build}<span
                    class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-brand-accent rounded-md border border-brand-accent/30 uppercase"
                    >BUILD</span
                  >{/if}
                {#if proj.pilar_rescue}<span
                    class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-teal-400 rounded-md border border-teal-500/30 uppercase"
                    >RESCUE</span
                  >{/if}
                {#if proj.pilar_boost}<span
                    class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-orange-400 rounded-md border border-orange-500/30 uppercase"
                    >BOOST</span
                  >{/if}
              </div>
              {#if proj.image_url}
                <img
                  src={proj.image_url}
                  alt={proj.title}
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                />
              {:else}
                <div
                  class="w-full h-full flex items-center justify-center text-brand-textSec/10"
                >
                  <i class="fa-solid fa-layer-group text-8xl rotate-12"></i>
                </div>
              {/if}
              <div
                class="absolute inset-0 bg-gradient-to-t from-brand-main/60 to-transparent"
              ></div>
            </div>
            <div class="p-8">
              <div class="flex items-center justify-between mb-3">
                <span
                  class="text-xs font-bold text-brand-accent uppercase tracking-widest bg-brand-accent/10 px-3 py-1 rounded-full border border-brand-accent/20"
                >
                  {proj.category_name || "Digital"}
                </span>
                <span
                  class="text-[10px] text-brand-textSec font-medium uppercase opacity-50"
                  >{proj.client_name || ""}</span
                >
              </div>
              <h3
                class="text-xl font-bold text-brand-textMain mb-3 group-hover:text-brand-accent transition-colors"
              >
                {proj.title}
              </h3>
              <p class="text-brand-textSec text-sm font-light line-clamp-2">
                {proj.description_short || ""}...
              </p>
              <div
                class="mt-6 flex items-center gap-2 text-brand-accent font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all"
              >
                View Case Study <i class="fa-solid fa-arrow-right text-[10px]"
                ></i>
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
      <span
        class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4"
        >Testimonials</span
      >
      <h2 class="font-heading text-4xl font-black text-brand-textMain">
        What Our Clients Say
      </h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      {#each [{ name: "Budi Santoso", role: "Owner, DataKu SaaS", av: "B", color: "bg-orange-500", text: "Setelah dibantu INSPIMA, biaya server kami turun sampai 45% dan deploy fitur baru yang dulu butuh 2 jam sekarang cuma 8 menit. The results exceeded our expectations!" }, { name: "Siti Rahayu", role: "CTO, FinTech Nusantara", av: "S", color: "bg-teal-500", text: "Sistem kami down total sehari sebelum investor presentation. Tim INSPIMA langsung response dalam 45 menit, dan 3 jam kemudian everything was back to normal. Lega banget!" }, { name: "Ahmad Fauzi", role: "Founder, TokoPintar.id", av: "A", color: "bg-brand-accent", text: "INSPIMA built our online store dari nol cuma dalam 8 minggu. Hasilnya clean, user-friendly, dan timnya selalu fast response kalau ada yang ditanyain." }] as t}
        <div
          class="bg-brand-container rounded-3xl p-8 border border-brand-border card-glow"
        >
          <div class="flex mb-4">
            {#each [1, 2, 3, 4, 5] as _}<i
                class="fa-solid fa-star text-yellow-400 text-sm"
              ></i>{/each}
          </div>
          <p
            class="text-brand-textSec text-sm font-light leading-relaxed mb-6 italic"
          >
            "{t.text}"
          </p>
          <div class="flex items-center gap-3">
            <div
              class="w-10 h-10 rounded-full {t.color} flex items-center justify-center text-white font-black"
            >
              {t.av}
            </div>
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
    <div
      class="flex flex-col md:flex-row justify-between items-end mb-16 reveal"
    >
      <div>
        <span
          class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4"
          >Tips &amp; Insights</span
        >
        <h2
          class="font-heading text-4xl md:text-5xl font-black text-brand-textMain"
        >
          Latest Articles
        </h2>
      </div>
      <a
        href="#/blog"
        class="mt-6 md:mt-0 text-brand-accent font-bold text-sm flex items-center gap-2 hover:gap-4 transition-all"
      >
        View All Articles <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    {#if loading}
      <div class="flex justify-center py-20"><div class="spinner"></div></div>
    {:else if !data?.articles?.length}
      <div
        class="text-center py-16 bg-brand-container rounded-3xl border border-brand-border"
      >
        <i class="fa-solid fa-pencil text-4xl text-brand-textSec/30 mb-3 block"
        ></i>
        <p class="text-brand-textSec font-light">
          Articles coming soon — stay tuned!
        </p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {#each data.articles as art}
          <!-- No reveal class — dynamically rendered -->
          <a
            href="#/article/{art.slug}"
            class="group bento-item bg-brand-container rounded-[2.5rem] overflow-hidden border border-brand-border card-glow flex flex-col block"
          >
            <div class="relative h-52 overflow-hidden bg-brand-main p-4">
              {#if art.image_url}
                <img
                  src={art.image_url}
                  alt={art.title}
                  class="w-full h-full object-cover rounded-[1.5rem] group-hover:scale-110 transition-transform duration-700"
                />
              {:else}
                <div
                  class="w-full h-full rounded-[1.5rem] flex items-center justify-center bg-brand-main border border-brand-border text-brand-textSec/10"
                >
                  <i class="fa-solid fa-newspaper text-7xl rotate-6"></i>
                </div>
              {/if}
              <div class="absolute top-8 left-8">
                <span
                  class="bg-brand-main/80 backdrop-blur-md text-brand-accent border border-brand-accent/30 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest"
                >
                  {art.category_name || "Blog"}
                </span>
              </div>
            </div>
            <div class="p-8 flex flex-col flex-grow">
              <div
                class="flex items-center gap-3 text-xs text-brand-textSec mb-4 font-medium"
              >
                <span class="flex items-center gap-1">
                  <i class="fa-regular fa-calendar-check text-brand-accent"></i>
                  {new Date(art.created_at).toLocaleDateString("id-ID", {
                    day: "numeric",
                    month: "short",
                    year: "numeric",
                  })}
                </span>
                <span class="w-1 h-1 bg-brand-border rounded-full"></span>
                <span>{art.read_time} min read</span>
              </div>
              <h3
                class="text-xl font-bold text-brand-textMain mb-3 group-hover:text-brand-accent transition-colors leading-tight flex-grow"
              >
                {art.title}
              </h3>
              <p class="text-brand-textSec text-sm font-light line-clamp-2">
                {art.excerpt || ""}...
              </p>
              <div
                class="mt-6 pt-5 border-t border-brand-border/50 flex items-center gap-2 text-brand-accent font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all"
              >
                Read Article <i class="fa-solid fa-chevron-right text-[10px]"
                ></i>
              </div>
            </div>
          </a>
        {/each}
      </div>
    {/if}
  </div>
</section>

<style>
  /* ── Hero Section ──────────────────────────────────────────────────── */
  .hero-section {
    position: relative;
    background-color: #ffffff;
    background-image:
      /* Grid horizontal */
      linear-gradient(rgba(30, 90, 230, 0.07) 1px, transparent 1px),
      /* Grid vertical */
      linear-gradient(90deg, rgba(30, 90, 230, 0.07) 1px, transparent 1px),
      /* Blue→white diagonal gradient */
      linear-gradient(135deg, #eff6ff 0%, #ffffff 50%, #ffffff 100%);
    background-size: 64px 64px, 64px 64px, 100% 100%;
    border-bottom: 1px solid rgb(var(--color-border));
    min-height: calc(100vh - 4rem);
    padding-bottom: 0;
  }

  :global([data-theme="dark"]) .hero-section {
    background-color: #090f1c;
    background-image:
      linear-gradient(rgba(59, 130, 246, 0.08) 1px, transparent 1px),
      linear-gradient(90deg, rgba(59, 130, 246, 0.08) 1px, transparent 1px),
      linear-gradient(135deg, #0f172a 0%, #090f1c 100%);
    background-size: 64px 64px, 64px 64px, 100% 100%;
  }

  .hero-stats-pill {
    display: none;
  }
  @media (min-width: 1024px) {
    .hero-stats-pill {
      display: inline-flex;
      align-items: center;
      background: rgb(var(--color-main));
      border: 1px solid rgb(var(--color-border));
      border-radius: 99px;
      padding: 0.45rem 0.5rem;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 2.5rem;
    }
  }


  .hero-stat-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0 1rem;
  }

  .hero-stat-dots {
    display: flex;
    gap: 4px;
  }

  .dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
  }
  .dot-red {
    background: #ff5f57;
  }
  .dot-yellow {
    background: #febc2e;
  }
  .dot-green {
    background: #28c840;
  }
  .dot-blue {
    background: rgb(var(--color-accent));
  }
  .dot-outline {
    background: transparent;
    border: 1.5px solid rgb(var(--color-border));
  }
  .dot-gray {
    background: rgb(var(--color-border));
  }

  .booking-dot {
    width: 8px;
    height: 8px;
    animation: pulse-dot 1.8s ease-in-out infinite;
  }
  @keyframes pulse-dot {
    0%,
    100% {
      opacity: 1;
      transform: scale(1);
    }
    50% {
      opacity: 0.5;
      transform: scale(1.3);
    }
  }

  .hero-stat-value {
    font-weight: 700;
    font-size: 0.8rem;
    color: rgb(var(--color-text-main));
  }
  .hero-stat-label {
    font-size: 0.75rem;
    color: rgb(var(--color-text-sec));
  }
  .hero-stat-year {
    font-weight: 700;
    font-size: 0.8rem;
    color: rgb(var(--color-text-main));
  }
  .hero-stat-divider {
    width: 1px;
    height: 22px;
    background: rgb(var(--color-border));
  }
  .hero-stat-booking {
    gap: 0.3rem;
  }

  /* ── Hero copy ──────────────────────────────────────────────────────── */
  .hero-left {
    padding-bottom: 3.5rem;
  }


  .hero-italic-accent {
    display: block;
    font-style: italic;
    font-weight: 900;
    background: linear-gradient(135deg, #5b7dee 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  :global([data-theme="dark"]) .hero-italic-accent {
    background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-subtext {
    font-size: 1rem;
    line-height: 1.7;
    color: rgb(var(--color-text-sec));
    max-width: 28rem;
    margin-bottom: 2rem;
  }

  /* ── CTA buttons ────────────────────────────────────────────────────── */
  .hero-cta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
  }

  .hero-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgb(var(--color-accent));
    color: #fff;
    padding: 0.85rem 1.75rem;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    box-shadow: 0 8px 24px rgb(var(--color-accent) / 0.3);
    transition:
      transform 0.2s,
      filter 0.2s,
      box-shadow 0.2s;
  }
  .hero-btn-primary:hover {
    filter: brightness(1.1);
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgb(var(--color-accent) / 0.4);
  }

  .hero-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgb(var(--color-main));
    color: rgb(var(--color-text-main));
    border: 1.5px solid rgb(var(--color-border));
    padding: 0.85rem 1.75rem;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: 0.875rem;
    transition:
      border-color 0.2s,
      color 0.2s,
      transform 0.2s;
  }
  .hero-btn-ghost:hover {
    border-color: rgb(var(--color-accent) / 0.5);
    color: rgb(var(--color-accent));
    transform: translateY(-2px);
  }

  /* ── Dashboard mockup ───────────────────────────────────────────────── */
  .hero-mockup-wrap {
    margin-top: -2.5rem;
    margin-bottom: -5rem;
    padding-top: 0;
  }
  @media (max-width: 1024px) {
    .hero-mockup-wrap {
      margin-top: 2rem;
      margin-bottom: 0;
      padding-bottom: 4rem;
    }
  }

  .hero-mockup {
    background: rgb(var(--color-main));
    border: 1px solid rgb(var(--color-border));
    border-radius: 1rem;
    overflow: hidden;
    box-shadow:
      0 12px 32px rgba(0, 0, 0, 0.08),
      0 24px 64px rgba(0, 0, 0, 0.12);
    animation: float-card 5s ease-in-out infinite;
  }
  @keyframes float-card {
    0%,
    100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-8px);
    }
  }

  /* Chrome bar */
  .mockup-chrome {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 0.9rem;
    border-bottom: 1px solid rgb(var(--color-border));
    background: rgb(var(--color-container));
  }
  .chrome-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .chrome-red {
    background: #ff5f57;
  }
  .chrome-yellow {
    background: #febc2e;
  }
  .chrome-green {
    background: #28c840;
  }

  .chrome-address {
    flex: 1;
    background: rgb(var(--color-main));
    border: 1px solid rgb(var(--color-border));
    border-radius: 6px;
    padding: 0.2rem 0.65rem;
    font-size: 0.65rem;
    color: rgb(var(--color-text-sec));
    margin: 0 0.5rem;
    display: flex;
    align-items: center;
  }

  .chrome-version {
    font-size: 0.65rem;
    font-weight: 700;
    color: rgb(var(--color-text-sec));
  }

  /* Body: sidebar + main */
  .mockup-body {
    display: grid;
    grid-template-columns: 145px 1fr;
    min-height: 290px;
  }
  @media (max-width: 640px) {
    .mockup-body {
      grid-template-columns: 1fr;
      min-height: auto;
    }
    .mockup-sidebar {
      display: none;
    }
  }

  .mockup-sidebar {
    border-right: 1px solid rgb(var(--color-border));
    padding: 1rem 0.75rem;
  }
  .sidebar-label {
    font-size: 0.55rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    color: rgb(var(--color-text-sec));
    margin-bottom: 0.75rem;
    padding-left: 0.25rem;
  }
  .sidebar-nav {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
  }
  .sidebar-item {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.7rem;
    font-weight: 500;
    padding: 0.35rem 0.5rem;
    border-radius: 6px;
    color: rgb(var(--color-text-sec));
  }
  .sidebar-item.active {
    background: rgb(var(--color-accent) / 0.08);
    color: rgb(var(--color-text-main));
    font-weight: 600;
  }
  .sidebar-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .sidebar-indent {
    display: inline-block;
    width: 8px;
    height: 8px;
    flex-shrink: 0;
  }

  .mockup-main {
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
  }

  .mockup-tabs {
    display: flex;
    align-items: center;
    gap: 0.3rem;
  }
  .tab {
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 5px;
    color: rgb(var(--color-text-sec));
  }
  .tab-active {
    background: rgb(var(--color-accent));
    color: #fff;
  }

  .metric-card {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: rgb(var(--color-container));
    border: 1px solid rgb(var(--color-border));
    border-radius: 0.6rem;
    padding: 0.65rem 0.85rem;
  }
  .metric-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: linear-gradient(135deg, #7c3aed, #5b7dee);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .metric-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: rgb(var(--color-text-main));
  }
  .metric-delta {
    font-size: 0.6rem;
    color: rgb(var(--color-text-sec));
    line-height: 1.5;
  }

  .big-metric {
    display: flex;
    align-items: baseline;
    gap: 0.3rem;
  }
  .big-num {
    font-size: 2.4rem;
    font-weight: 900;
    color: rgb(var(--color-text-main));
    line-height: 1;
    letter-spacing: -0.02em;
  }
  .big-unit {
    font-size: 1.2rem;
    font-weight: 700;
    color: rgb(var(--color-text-sec));
  }
  .metric-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    background: #22c55e;
    color: #fff;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.2rem 0.45rem;
    border-radius: 5px;
    margin-left: 0.25rem;
    align-self: center;
  }
  .badge-label {
    font-weight: 400;
    opacity: 0.85;
  }

  .sparkline-wrap {
    width: 100%;
    height: 36px;
  }
  .sparkline {
    width: 100%;
    height: 100%;
  }

  .mockup-bottom-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.25rem;
    border-top: 1px solid rgb(var(--color-border));
  }
  @media (max-width: 640px) {
    .m-activity {
      display: none;
    }
  }
  .bottom-label {
    font-size: 0.6rem;
    color: rgb(var(--color-text-sec));
    font-family: monospace;
  }
  .bottom-time {
    font-size: 0.6rem;
    color: rgb(var(--color-text-sec));
    background: rgb(var(--color-container));
    border: 1px solid rgb(var(--color-border));
    border-radius: 4px;
    padding: 0.1rem 0.35rem;
  }

  /* ── Services Section ─────────────────────────────────────────────── */
  .svc-section {
    padding: 8rem 0;
    background: #fff;
  }
  :global([data-theme="dark"]) .svc-section {
    background: rgb(var(--color-main));
  }

  .svc-eyebrow {
    display: inline-block;
    background: #eff6ff;
    color: #2563eb;
    padding: 0.35rem 1rem;
    border-radius: 99px;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
  }
  :global([data-theme="dark"]) .svc-eyebrow {
    background: rgba(37, 99, 235, 0.1);
  }

  .svc-heading {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: rgb(var(--color-text-main));
    margin-bottom: 1rem;
  }

  .svc-sub {
    font-size: 1.1rem;
    color: rgb(var(--color-text-sec));
    max-width: 40rem;
    margin: 0 auto;
  }

  .svc-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 2rem;
    padding: 2.5rem;
    display: flex;
    flex-direction: column;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    position: relative;
    overflow: hidden;
  }
  :global([data-theme="dark"]) .svc-card {
    background: rgb(var(--color-container));
    border-color: rgb(var(--color-border));
  }

  /* Gradient backgrounds per card (Top-Left corner) */
  .svc-card-build {
    background: radial-gradient(circle at 0% 0%, #eff6ff 0%, #ffffff 65%);
  }
  .svc-card-rescue {
    background: radial-gradient(circle at 0% 0%, #f0fdf4 0%, #ffffff 65%);
  }
  .svc-card-boost {
    background: radial-gradient(circle at 0% 0%, #fffbeb 0%, #ffffff 65%);
  }

  :global([data-theme="dark"]) .svc-card-build {
    background: radial-gradient(circle at 0% 0%, rgba(37, 99, 235, 0.1) 0%, rgb(var(--color-container)) 70%);
  }
  :global([data-theme="dark"]) .svc-card-rescue {
    background: radial-gradient(circle at 0% 0%, rgba(22, 163, 74, 0.1) 0%, rgb(var(--color-container)) 70%);
  }
  :global([data-theme="dark"]) .svc-card-boost {
    background: radial-gradient(circle at 0% 0%, rgba(234, 179, 8, 0.1) 0%, rgb(var(--color-container)) 70%);
  }

  .svc-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
  }
  .svc-card-build:hover { border-color: #3b82f6; }
  .svc-card-rescue:hover { border-color: #22c55e; }
  .svc-card-boost:hover { border-color: #eab308; }

  .svc-icon-wrap {
    width: 56px;
    height: 56px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 2rem;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  }
  .svc-color-blue { color: #2563eb; }
  .svc-color-green { color: #16a34a; }
  .svc-color-yellow { color: #ca8a04; }

  .svc-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: rgb(var(--color-text-main));
    line-height: 1.2;
    margin-bottom: 1.25rem;
  }

  .svc-desc {
    font-size: 0.95rem;
    color: rgb(var(--color-text-sec));
    line-height: 1.6;
    margin-bottom: 2rem;
  }

  .svc-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 2.5rem;
    margin-top: auto;
  }
  .svc-pill {
    font-size: 0.6rem;
    font-weight: 800;
    padding: 0.4rem 0.8rem;
    border-radius: 99px;
    background: #fff;
    border: 1px solid #e5e7eb;
    color: #4b5563;
    letter-spacing: 0.05em;
  }
  :global([data-theme="dark"]) .svc-pill {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
    color: #9ca3af;
  }

  .svc-cta {
    font-size: 0.75rem;
    font-weight: 800;
    color: #2563eb;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
    letter-spacing: 0.05em;
  }
  .svc-card:hover .svc-cta { gap: 0.75rem; }
  .svc-cta-orange { color: #14b8a6; }
  .svc-cta-green { color: #f97316; }

  /* ── Sidebar enhancements ──────────────────────────────────────────── */
  .sidebar-icon {
    font-size: 0.6rem;
    width: 12px;
    text-align: center;
    color: rgb(var(--color-text-sec));
    flex-shrink: 0;
  }
  .sidebar-item.active .sidebar-icon {
    color: rgb(var(--color-accent));
  }
  .sidebar-sub {
    padding-left: 1.2rem;
    opacity: 0.75;
  }
  .sidebar-divider {
    height: 1px;
    background: rgb(var(--color-border));
    margin: 0.6rem 0;
  }
  .sidebar-avatars {
    display: flex;
    align-items: center;
    gap: 0.2rem;
    margin-top: 0.3rem;
  }
  .s-av {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.45rem;
    font-weight: 800;
    color: #fff;
    border: 1.5px solid rgb(var(--color-main));
  }
  .s-av-blue {
    background: rgb(var(--color-accent));
  }
  .s-av-teal {
    background: #14b8a6;
  }
  .s-av-orange {
    background: #f97316;
  }
  .s-av-more {
    font-size: 0.55rem;
    color: rgb(var(--color-text-sec));
    font-weight: 600;
    margin-left: 2px;
  }

  /* ── Top stat row ──────────────────────────────────────────────────── */
  .m-stat-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.4rem;
  }
  .m-stat-card {
    background: rgb(var(--color-container));
    border: 1px solid rgb(var(--color-border));
    border-radius: 0.5rem;
    padding: 0.5rem 0.6rem;
  }
  .m-stat-label {
    font-size: 0.55rem;
    color: rgb(var(--color-text-sec));
    font-weight: 600;
    letter-spacing: 0.05em;
  }
  .m-stat-value {
    font-size: 1rem;
    font-weight: 800;
    color: rgb(var(--color-text-main));
    line-height: 1.2;
  }
  .m-stat-delta {
    font-size: 0.55rem;
    color: rgb(var(--color-text-sec));
  }

  /* ── Chart ─────────────────────────────────────────────────────────── */
  .m-chart-wrap {
    background: rgb(var(--color-container));
    border: 1px solid rgb(var(--color-border));
    border-radius: 0.5rem;
    padding: 0.5rem 0.6rem;
  }
  .m-chart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.35rem;
  }
  .m-chart-title {
    font-size: 0.6rem;
    font-weight: 700;
    color: rgb(var(--color-text-main));
  }
  .m-chart-badge {
    font-size: 0.55rem;
    font-weight: 700;
    background: rgb(var(--color-accent));
    color: #fff;
    padding: 0.1rem 0.35rem;
    border-radius: 4px;
  }
  .m-chart {
    width: 100%;
    height: 52px;
    display: block;
  }

  /* ── Progress bars ──────────────────────────────────────────────────── */
  .m-progress-wrap {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
  }
  .m-prog-label {
    display: flex;
    justify-content: space-between;
    font-size: 0.6rem;
    color: rgb(var(--color-text-sec));
  }
  .m-prog-bar {
    height: 5px;
    background: rgb(var(--color-border));
    border-radius: 99px;
    overflow: hidden;
  }
  .m-prog-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 1s ease;
  }

  /* ── Activity feed ──────────────────────────────────────────────────── */
  .m-activity {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
  }
  .m-act-item {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.3rem 0.4rem;
    background: rgb(var(--color-container));
    border: 1px solid rgb(var(--color-border));
    border-radius: 6px;
  }
  .m-act-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .m-act-text {
    font-size: 0.6rem;
    color: rgb(var(--color-text-sec));
    flex: 1;
  }
  .m-act-text b {
    color: rgb(var(--color-text-main));
    font-weight: 700;
  }
  .m-act-time {
    font-size: 0.55rem;
    color: rgb(var(--color-text-sec));
    background: rgb(var(--color-main));
    border: 1px solid rgb(var(--color-border));
    border-radius: 4px;
    padding: 0.1rem 0.3rem;
    flex-shrink: 0;
  }
</style>
