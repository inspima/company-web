<script>
  import { onMount } from "svelte";
  import { api } from "../lib/api.js";
  import { navigate } from "../lib/nav.js";
  import Icon from "../lib/Icon.svelte";

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

    // ── Lazy image fade-in (via onload) ──────────────────────────────────
    function setupImgFade() {
      document.querySelectorAll("img.img-fade").forEach((img) => {
        if (img.complete) {
          img.classList.add("loaded");
        } else {
          img.addEventListener("load", () => img.classList.add("loaded"), { once: true });
        }
      });
    }

    // ── Fetch data ────────────────────────────────────────────────────────
    try {
      data = await api.home();
      // Re-run observer for dynamic elements after they render
      import("svelte").then(({ tick }) => {
        tick().then(() => {
          document
            .querySelectorAll(".reveal")
            .forEach((el) => revObs.observe(el));
          setupImgFade();
        });
      });
    } catch (e) {
      apiError = "Waduh, koneksi ke server bermasalah. Coba lagi sebentar ya.";
    } finally {
      loading = false;
    }
  });
</script>

<!-- ── Hero ─────────────────────────────────────────────────────────────── -->
<section id="home" class="hero-section">


  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position:relative;z-index:1">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
      <!-- LEFT -->
      <div class="hero-left">
        <p class="hero-eyebrow"><span></span> INTEGRATED TECHNOLOGY SOLUTIONS</p>
        <h1 class="hero-heading">
          Kami bangun<br />
          teknologi yang
          <em class="hero-italic-accent">benar-benar bekerja.</em>
        </h1>

        <p class="hero-subtext">
          Build. Rescue. Boost. Inspima adalah studio engineering senior untuk
          para founder dan product team yang membutuhkan software yang berjalan
          — dan terus berjalan setelah peluncuran.
        </p>

        <div class="hero-cta-row">
          <button
            on:click={() => navigate('/contact')}
            class="hero-btn-primary"
          >
            Mulai Proyek <Icon name="arrow" size={12} />
          </button>
          <a href="/projects" class="hero-btn-ghost"> Lihat Portofolio </a>
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
              <Icon name="lock" size={9} cls="mr-1 opacity-40" />
              <span>inspima.dev / dashboard</span>
            </div>
          </div>

          <!-- Dashboard body -->
          <div class="mockup-body">
            <!-- Sidebar -->
            <div class="mockup-sidebar">
              <p class="sidebar-label">WORKSPACE</p>
              <ul class="sidebar-nav">
                <li class="sidebar-item active">
                  <Icon name="home" size={10} cls="sidebar-icon" /> Overview
                </li>
                <li class="sidebar-item">
                  <Icon name="layers" size={10} cls="sidebar-icon" /> Projects
                </li>
                <li class="sidebar-item sidebar-sub">
                  <Icon name="merge" size={10} cls="sidebar-icon" /> Pipelines
                </li>
                <li class="sidebar-item sidebar-sub">
                  <Icon name="shield" size={10} cls="sidebar-icon" /> Monitoring
                </li>
                <li class="sidebar-item">
                  <Icon name="warning" size={10} cls="sidebar-icon" /> Incidents
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
                  <span class="m-act-text">Deploy <b>production</b> berhasil</span>
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
<section class="stats-bridge-section">
  <div class="max-w-7xl mx-auto px-4">
    <div id="stats-strip" class="stats-strip">
      {#each stats as s, i}
        <div class="stats-item stats-item-{i}">
          <div class="text-3xl font-bold text-brand-accent font-heading stats-text-value">
            {s.value}{s.suffix}
          </div>
          <div
            class="text-xs text-brand-textSec uppercase tracking-widest mt-1 font-semibold stats-text-label"
          >
            {s.label}
          </div>
        </div>
      {/each}
    </div>
  </div>
</section>

<div class="home-services-flow">


<!-- ── Services / Pillars ─────────────────────────────────────────────── -->
<section id="services" class="svc-section flow-section">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10 reveal">
      <span class="svc-eyebrow">Our Services</span>
      <h2 class="svc-heading">One Team, All Solutions</h2>
      <p class="svc-sub">
        Mau bikin yang baru, fix yang rusak, atau scale lebih jauh — kami siap handle semuanya.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- BUILD -->
      <a href="/build" class="svc-card group svc-card-build">
        <div class="svc-icon-wrap svc-color-blue">
          <Icon name="layers" size={26} />
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
        <div class="svc-cta">LEARN MORE <Icon name="arrow" size={12} /></div>
      </a>

      <!-- RESCUE -->
      <a href="/rescue" class="svc-card group svc-card-rescue">
        <div class="svc-icon-wrap svc-color-green">
          <Icon name="wrench" size={26} />
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
        <div class="svc-cta">LEARN MORE <Icon name="arrow" size={12} /></div>
      </a>

      <!-- BOOST -->
      <a href="/boost" class="svc-card group svc-card-boost">
        <div class="svc-icon-wrap svc-color-yellow">
          <Icon name="rocket" size={26} />
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
        <div class="svc-cta">LEARN MORE <Icon name="arrow" size={12} /></div>
      </a>
    </div>
  </div>
</section>



<!-- ── Latest Projects ────────────────────────────────────────────────── -->
<section
  id="portfolio"
  class="home-section-portfolio flow-section py-16"
>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div
      class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 reveal"
    >
      <div>
        <span
          class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-4"
          >Portfolio</span
        >
        <h2
          class="font-heading text-3xl md:text-5xl font-bold text-brand-textMain"
        >
          Our Recent Work
        </h2>
      </div>
      <a
        href="/projects"
        class="section-cta section-cta-blue mt-6 md:mt-0"
      >
        View All Projects <Icon name="arrow" size={12} />
      </a>
    </div>

    {#if loading}
      <div class="flex justify-center py-14"><div class="spinner"></div></div>
    {:else if apiError}
      <div
        class="text-center py-16 bg-brand-container rounded-2xl border border-brand-border"
      >
        <Icon name="warning" size={32} cls="text-orange-400 mb-3 block" />
        <p class="text-brand-textSec text-sm">{apiError}</p>
      </div>
    {:else if !data?.projects?.length}
      <div
        class="text-center py-16 bg-brand-container rounded-2xl border border-brand-border"
      >
        <Icon name="briefcase" size={40} cls="text-brand-textSec/30 mb-3 block" />
        <p class="text-brand-textSec font-normal">
          Projects coming soon — stay tuned!
        </p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each data.projects as proj}
          <!-- No reveal class — dynamically rendered, observer not set up yet -->
          <a
            href="/project/{proj.slug}"
            class="group bento-item bg-brand-container rounded-2xl overflow-hidden border border-brand-border card-glow block"
          >
            <div class="relative h-56 overflow-hidden bg-brand-main">
              <div class="absolute top-4 left-4 flex gap-2 z-10">
                {#if proj.pilar_build}<span
                    class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-bold text-brand-accent rounded-md border border-brand-accent/30 uppercase"
                    >BUILD</span
                  >{/if}
                {#if proj.pilar_rescue}<span
                    class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-bold text-teal-400 rounded-md border border-teal-500/30 uppercase"
                    >RESCUE</span
                  >{/if}
                {#if proj.pilar_boost}<span
                    class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-bold text-orange-400 rounded-md border border-orange-500/30 uppercase"
                    >BOOST</span
                  >{/if}
              </div>
              {#if proj.image_url}
                <img
                  src={proj.image_url}
                  alt={proj.title}
                  loading="lazy"
                  decoding="async"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 img-fade"
                />
              {:else}
                <div
                  class="w-full h-full flex items-center justify-center text-brand-textSec/10"
                >
                  <Icon name="layers" size={80} cls="rotate-12" />
                </div>
              {/if}
              <div
                class="absolute inset-0 bg-gradient-to-t from-brand-main/60 to-transparent"
              ></div>
            </div>
            <div class="p-6">
              <div class="mb-4">
                <div class="mb-2.5">
                  <span
                    class="text-[10px] font-bold text-brand-accent uppercase tracking-[0.15em] bg-brand-accent/10 px-3 py-1.5 rounded-lg"
                  >
                    {proj.category_name || "Digital"}
                  </span>
                </div>
                {#if proj.client_name}
                  <div
                    class="text-[10px] text-brand-textSec font-bold uppercase tracking-widest flex items-center gap-1.5 opacity-60"
                  >
                    <Icon name="people" size={9} />
                    {proj.client_name}
                  </div>
                {/if}
              </div>
              <h3
                class="text-xl font-bold text-brand-textMain mb-3 group-hover:text-brand-accent transition-colors"
              >
                {proj.title}
              </h3>
              <p class="text-brand-textSec text-sm font-normal line-clamp-2">
                {proj.description_short || ""}...
              </p>
              <div
                class="card-link-cta mt-6"
              >
                View Case Study <Icon name="arrow" size={10} />
              </div>
            </div>
          </a>
        {/each}
      </div>
    {/if}
  </div>
</section>

<!-- ── Latest Articles ────────────────────────────────────────────────── -->
<section class="home-section-insights flow-section py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div
      class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 reveal"
    >
      <div>
        <span
          class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-4"
          >Tips &amp; Insights</span
        >
        <h2
          class="font-heading text-3xl md:text-5xl font-bold text-brand-textMain"
        >
          Latest Articles
        </h2>
      </div>
      <a
        href="/blog"
        class="section-cta section-cta-sky mt-6 md:mt-0"
      >
        View All Articles <Icon name="arrow" size={12} />
      </a>
    </div>

    {#if loading}
      <div class="flex justify-center py-14"><div class="spinner"></div></div>
    {:else if !data?.articles?.length}
      <div
        class="text-center py-16 bg-brand-container rounded-2xl border border-brand-border"
      >
        <Icon name="newspaper" size={40} cls="text-brand-textSec/30 mb-3 block" />
        <p class="text-brand-textSec font-normal">
          Articles coming soon — stay tuned!
        </p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {#each data.articles as art}
          <!-- No reveal class — dynamically rendered -->
          <a
            href="/article/{art.slug}"
            class="group bento-item bg-brand-container rounded-2xl overflow-hidden border border-brand-border card-glow flex flex-col block"
          >
            <div class="relative h-52 overflow-hidden bg-brand-main p-4">
              {#if art.image_url}
                <img
                  src={art.image_url}
                  alt={art.title}
                  loading="lazy"
                  decoding="async"
                  class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-700 img-fade"
                />
              {:else}
                <div
                  class="w-full h-full rounded-xl flex items-center justify-center bg-brand-main border border-brand-border text-brand-textSec/10"
                >
                  <Icon name="newspaper" size={72} cls="rotate-6" />
                </div>
              {/if}
              <div class="absolute top-8 left-8">
                <span
                  class="bg-brand-main/80 backdrop-blur-md text-brand-accent border border-brand-accent/30 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest"
                >
                  {art.category_name || "Blog"}
                </span>
              </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <div
                class="flex items-center gap-3 text-xs text-brand-textSec mb-4 font-medium"
              >
                <span class="flex items-center gap-1">
                  <Icon name="clock" size={12} cls="text-brand-accent" />
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
              <p class="text-brand-textSec text-sm font-normal line-clamp-2">
                {art.excerpt || ""}...
              </p>
              <div
                class="card-link-cta mt-6 pt-5 border-t border-brand-border/50"
              >
                Read Article <Icon name="chevron-right" size={10} />
              </div>
            </div>
          </a>
        {/each}
      </div>
    {/if}
  </div>
</section>

<!-- ── Testimonials ───────────────────────────────────────────────────── -->
<section class="home-section-testimonials flow-section py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 reveal">
      <span
        class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-4"
        >Testimonials</span
      >
      <h2 class="font-heading text-3xl font-bold text-brand-textMain">
        What Our Clients Say
      </h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      {#if data && data.testimonials && data.testimonials.length > 0}
        {@const avatarColors = [
          'bg-blue-500/20 text-blue-500',
          'bg-sky-500/20 text-sky-600',
          'bg-emerald-500/20 text-emerald-500',
          'bg-orange-500/20 text-orange-500',
          'bg-pink-500/20 text-pink-500'
        ]}
        {#each data.testimonials as t}
          <div
            class="bg-brand-container rounded-2xl p-6 border border-brand-border card-glow reveal h-full flex flex-col"
          >
            <div class="flex mb-4">
              {#each Array.from({ length: parseInt(t.stars) || 5 }) as _}
                <Icon name="star" size={14} cls="text-yellow-400" />
              {/each}
            </div>
            <p
              class="text-brand-textSec text-sm font-normal leading-relaxed mb-6 italic flex-1"
            >
              "{t.content}"
            </p>
            <div class="flex items-center gap-3 mt-auto">
              {#if t.image_url}
                <img src={t.image_url} alt={t.name} class="w-10 h-10 rounded-full object-cover border border-brand-border" />
              {:else}
                <div
                  class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {avatarColors[t.id % avatarColors.length]}"
                >
                  {t.avatar_init}
                </div>
              {/if}
              <div class="min-w-0">
                <div class="font-bold text-brand-textMain text-sm truncate">{t.name}</div>
                <div class="text-brand-textSec text-[10px] truncate">
                  {t.role}{t.company ? ` — ${t.company}` : ''}
                </div>
              </div>
            </div>
          </div>
        {/each}
      {:else}
        <div class="col-span-3 text-center py-10 text-brand-textSec opacity-50 italic text-sm">
          No testimonials yet.
        </div>
      {/if}
    </div>
  </div>
</section>
</div>

<style>
  :global(.img-fade) { opacity: 0; transition: opacity .3s ease; }
  :global(.img-fade.loaded) { opacity: 1; }
  .hero-section {
    position: relative;
    padding: 6.25rem 0 2.5rem;
    background: #f8fafc;
    overflow: hidden;
  }
  .hero-section::before {
    content: ''; position: absolute; inset: 0 0 0 50%; pointer-events: none;
    background-image: linear-gradient(#e5ebf3 1px, transparent 1px), linear-gradient(90deg, #e5ebf3 1px, transparent 1px);
    background-size: 36px 36px;
    mask-image: radial-gradient(ellipse at 55% 50%, #000 0%, transparent 68%);
    opacity: .65;
  }
  .hero-left { padding-bottom: .5rem; }
  .hero-eyebrow {
    display: flex; align-items: center; gap: .55rem;
    color: rgb(var(--color-text-sec)); font-size: .6rem; font-weight: 600;
    letter-spacing: .16em; margin-bottom: 1rem;
  }
  .hero-eyebrow span { width: 20px; height: 2px; background: rgb(var(--color-accent)); }
  .hero-left :global(.hero-heading) { font-size: clamp(2rem, 3.8vw, 3.25rem); line-height: 1.16; margin-bottom: 1.25rem; }
  .hero-italic-accent { display: block; font-style: normal; color: rgb(var(--color-accent)); }
  .hero-subtext { font-size: .9rem; line-height: 1.8; color: rgb(var(--color-text-sec)); max-width: 29rem; margin-bottom: 1.5rem; }
  .hero-cta-row { display: flex; flex-wrap: wrap; gap: .65rem; }
  .hero-btn-primary, .hero-btn-ghost {
    display: inline-flex; align-items: center; justify-content: center; gap: .75rem;
    padding: .8rem 1.15rem; border-radius: 8px; font-weight: 600; font-size: .8rem;
    transition: background .2s ease, transform .2s ease, border-color .2s ease;
  }
  .hero-btn-primary { background: rgb(var(--color-accent)); color: #fff; border: 1px solid rgb(var(--color-accent)); cursor: pointer; box-shadow: 0 3px 8px rgb(37 86 216 / .12); }
  .hero-btn-primary:hover { background: #1d46b7; transform: translateY(-1px); }
  .hero-btn-ghost { background: #fff; color: rgb(var(--color-text-main)); border: 1px solid rgb(var(--color-border)); }
  .hero-btn-ghost:hover { border-color: rgb(var(--color-accent) / .5); color: rgb(var(--color-accent)); }
  .hero-mockup-wrap { position: relative; min-width: 0; }
  .hero-mockup {
    background: #fff; border: 1px solid #dfe6ef; border-radius: 12px;
    overflow: hidden; box-shadow: 0 20px 50px rgb(20 34 56 / .07), 0 2px 5px rgb(20 34 56 / .04);
  }
  /* Chrome bar */
  .mockup-chrome {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 0.9rem;
    border-bottom: 1px solid rgb(var(--color-border));
    background: rgb(255 255 255 / 0.56);
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
    background: rgb(255 255 255 / 0.72);
    border: 1px solid rgb(var(--color-border) / 0.72);
    border-radius: 6px;
    padding: 0.2rem 0.65rem;
    font-size: 0.65rem;
    color: rgb(var(--color-text-sec));
    margin: 0 0.5rem;
    display: flex;
    align-items: center;
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


  .mockup-main {
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
  }







  .stats-bridge-section { background: #fff; border-top: 1px solid rgb(var(--color-border)); border-bottom: 1px solid rgb(var(--color-border)); }
  .home-services-flow { background: #fff; }
  .flow-section { position: relative; }
  .svc-section { padding: 3.75rem 0; }
  .svc-eyebrow { display: inline-block; color: rgb(var(--color-accent)); font-size: .65rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; margin-bottom: .75rem; }
  .svc-heading { font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(1.6rem, 3vw, 2rem); letter-spacing: -.04em; font-weight: 800; margin-bottom: .75rem; color: rgb(var(--color-text-main)); }
  .svc-sub { font-size: .9rem; line-height: 1.7; color: rgb(var(--color-text-sec)); max-width: 37rem; margin: 0 auto; }
  .svc-card {
    display: flex; flex-direction: column; padding: 1.5rem; background: #fff;
    border: 1px solid rgb(var(--color-border)); border-radius: 12px;
    transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
  }
  .svc-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgb(20 34 56 / .05); }
  .svc-card-build:hover { border-color: #2556d8; }
  .svc-card-rescue:hover { border-color: #0e8176; }
  .svc-card-boost:hover { border-color: #c66020; }
  .svc-icon-wrap { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; }
  .svc-color-blue { background: #eef3ff; color: #2556d8; }
  .svc-color-green { background: #edf8f5; color: #0e8176; }
  .svc-color-yellow { background: #fff5ed; color: #c66020; }
  .svc-title { font-size: 1.15rem; font-weight: 700; letter-spacing: -.025em; color: rgb(var(--color-text-main)); margin-bottom: .75rem; }
  .svc-desc { font-size: .825rem; line-height: 1.8; color: rgb(var(--color-text-sec)); margin-bottom: 1.25rem; }
  .svc-pills { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: auto; margin-bottom: 1.25rem; }
  .svc-pill { font-size: .55rem; font-weight: 600; padding: .3rem .5rem; border-radius: 4px; background: #f6f8fb; color: rgb(var(--color-text-sec)); letter-spacing: .04em; }
  .svc-cta { display: flex; align-items: center; justify-content: space-between; gap: .5rem; border-top: 1px solid rgb(var(--color-border)); padding-top: 1rem; font-size: .65rem; font-weight: 700; letter-spacing: .08em; }
  .svc-card-build .svc-cta { color: #2556d8; }
  .svc-card-rescue .svc-cta { color: #0e8176; }
  .svc-card-boost .svc-cta { color: #c66020; }
  .section-cta { display: inline-flex; align-items: center; gap: .75rem; border: 1px solid rgb(var(--color-border)); padding: .65rem .9rem; border-radius: 8px; font-size: .75rem; font-weight: 600; color: rgb(var(--color-text-main)); background: #fff; transition: border-color .2s ease, color .2s ease; }
  .section-cta:hover { border-color: rgb(var(--color-accent)); color: rgb(var(--color-accent)); }
  .card-link-cta { display: inline-flex; align-items: center; gap: .5rem; font-size: .65rem; font-weight: 600; color: rgb(var(--color-accent)); transition: gap .2s ease; }
  .group:hover .card-link-cta { gap: .75rem; }
  .home-section-portfolio, .home-section-testimonials { background: #f8fafc; border-top: 1px solid rgb(var(--color-border)); border-bottom: 1px solid rgb(var(--color-border)); }
  /* ── Sidebar enhancements ──────────────────────────────────────────── */
  :global(.sidebar-icon) {
    font-size: 0.6rem;
    width: 12px;
    text-align: center;
    color: rgb(var(--color-text-sec));
    flex-shrink: 0;
  }
  .sidebar-item.active :global(.sidebar-icon) {
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
    background: rgb(255 255 255 / 0.66);
    border: 1px solid rgb(255 255 255 / 0.72);
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
    background: rgb(255 255 255 / 0.66);
    border: 1px solid rgb(255 255 255 / 0.72);
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
    background: rgb(255 255 255 / 0.66);
    border: 1px solid rgb(255 255 255 / 0.72);
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

  .mockup-chrome { background: #f8fafc; padding: .65rem .9rem; }
  .mockup-sidebar { background: #fbfcfe; }
  .m-stat-card, .m-chart-wrap, .m-act-item { border-color: rgb(var(--color-border)); background: #fff; }
  .m-stat-card { padding: .65rem; }
  .m-chart-wrap { padding: .65rem; }
  .m-chart { height: 52px; }
  .m-chart-badge { background: #eef3ff; color: #2556d8; }
  .m-stat-delta { margin-top: .2rem; }
  .mockup-main { min-width: 0; gap: .6rem; }
  .mockup-chrome { font-size: .65rem; }
  .m-chart-header, .m-act-item { font-size: .6rem; }
  @media (max-width: 1023px) {
    .hero-section { padding-top: 6rem; padding-bottom: 2.5rem; }
    .hero-section::before { inset: 30% 0 0; }
    .hero-mockup-wrap { max-width: 38rem; width: 100%; }
    .hero-left :global(.hero-heading) { max-width: 36rem; }
  }
  @media (max-width: 640px) {
    .hero-eyebrow { font-size: .55rem; letter-spacing: .1em; }
    .hero-subtext { font-size: .85rem; }
    .mockup-body { grid-template-columns: 95px minmax(0, 1fr); min-height: 0; }
    .mockup-sidebar { display: block; padding: .8rem .45rem; }
    .sidebar-item { font-size: .55rem; padding: .3rem .25rem; gap: .25rem; }
    .sidebar-sub { padding-left: .65rem; }
    .mockup-main { padding: .75rem; }
    .m-stat-card { padding: .4rem; }
    .m-stat-value { font-size: .85rem; }
    .m-act-time { display: none; }
    .m-act-text { font-size: .5rem; }
    .svc-section { padding: 2.75rem 0; }
    .svc-card { padding: 1.25rem; }
  }
</style>
