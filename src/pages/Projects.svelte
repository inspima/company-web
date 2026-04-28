<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import { navigate, goToSection } from '../lib/nav.js';

  export let category = 'all';
  export let page = 1;

  let data = null;
  let categories = [];
  let loading = true;

  async function load() {
    loading = true;
    try {
      [data, categories] = await Promise.all([
        api.projects({ category, page, limit: 9 }),
        api.categories(),
      ]);
    } catch(e) {}
    loading = false;
  }

  function navTo(cat, p) {
    const qs = [];
    if (cat && cat !== 'all') qs.push(`category=${cat}`);
    if (p > 1) qs.push(`page=${p}`);
    navigate('/projects' + (qs.length ? '?' + qs.join('&') : ''));
  }

  onMount(load);

  $: if (category || page) load();
</script>

<!-- Hero -->
<section class="relative pt-32 pb-20 overflow-hidden bg-brand-main">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
    <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-6">Portfolio</span>
    <h1 class="hero-heading">
      Creative <span class="text-brand-accent">Showcase</span>
    </h1>
    <p class="text-xl text-brand-textSec max-w-2xl mx-auto font-light leading-relaxed">
      Setiap proyek adalah bukti keunggulan teknis dan hasil bisnis nyata yang kami berikan.
    </p>
  </div>
</section>

<!-- Filter & Grid -->
<section class="py-16 bg-brand-main min-h-[60vh]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Category Filter -->
    <div class="flex flex-wrap justify-center gap-3 mb-16">
      <button
        on:click={() => navTo('all', 1)}
        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all border {category === 'all' ? 'bg-brand-accent text-white border-brand-accent shadow-lg shadow-brand-accent/20' : 'bg-brand-container text-brand-textSec border-brand-border hover:border-brand-accent/50'}"
      >All Works</button>
      {#each categories as cat}
        <button
          on:click={() => navTo(cat.slug, 1)}
          class="px-6 py-2.5 rounded-full text-sm font-bold transition-all border {category === cat.slug ? 'bg-brand-accent text-white border-brand-accent shadow-lg shadow-brand-accent/20' : 'bg-brand-container text-brand-textSec border-brand-border hover:border-brand-accent/50'}"
        >{cat.name}</button>
      {/each}
    </div>

    {#if loading}
      <div class="flex justify-center py-32"><div class="spinner"></div></div>
    {:else if !data?.data?.length}
      <div class="col-span-full py-32 text-center">
        <div class="w-24 h-24 bg-brand-container rounded-full flex items-center justify-center mx-auto mb-6 border border-brand-border">
          <i class="fa-solid fa-folder-open text-3xl text-brand-textSec/30"></i>
        </div>
        <h3 class="text-2xl font-bold text-brand-textMain mb-2">Belum ada proyek</h3>
        <p class="text-brand-textSec font-light">Belum ada proyek dalam kategori ini.</p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {#each data.data as proj}
          <div class="bento-item bg-brand-container rounded-[2.5rem] overflow-hidden border border-brand-border card-glow">
            <a href="/project/{proj.slug}" class="block">
              <div class="relative h-72 overflow-hidden bg-brand-main">
                <div class="absolute top-5 left-5 flex flex-wrap gap-2 z-10">
                  {#if proj.pilar_build}<span class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-brand-accent rounded-md border border-brand-accent/30 uppercase">BUILD</span>{/if}
                  {#if proj.pilar_rescue}<span class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-teal-400 rounded-md border border-teal-500/30 uppercase">RESCUE</span>{/if}
                  {#if proj.pilar_boost}<span class="bg-brand-main/90 backdrop-blur-md px-3 py-1 text-[9px] font-black text-orange-400 rounded-md border border-orange-500/30 uppercase">BOOST</span>{/if}
                </div>
                {#if proj.image_url}
                  <img src={proj.image_url} alt={proj.title} class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                {:else}
                  <div class="w-full h-full flex items-center justify-center text-brand-textSec/10 scale-150 rotate-12">
                    <i class="fa-solid fa-layer-group text-9xl"></i>
                  </div>
                {/if}
                <div class="absolute inset-0 bg-gradient-to-t from-brand-main via-brand-main/20 to-transparent opacity-60"></div>
              </div>
              <div class="p-10">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-xs font-bold text-brand-accent uppercase tracking-widest bg-brand-accent/10 px-3 py-1 rounded-full border border-brand-accent/20">{proj.category_name || 'Digital'}</span>
                  <span class="text-[10px] text-brand-textSec font-medium uppercase tracking-tighter opacity-50">{proj.client_name || ''}</span>
                </div>
                <h3 class="text-2xl font-bold text-brand-textMain mb-4 group-hover:text-brand-accent transition-colors">{proj.title}</h3>
                <p class="text-brand-textSec text-sm line-clamp-3 font-light leading-relaxed">{proj.description_short}...</p>
                <div class="mt-8 flex items-center gap-2 text-brand-accent font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
                  View Project <i class="fa-solid fa-arrow-right"></i>
                </div>
              </div>
            </a>
          </div>
        {/each}
      </div>

      <!-- Pagination -->
      {#if data.total_pages > 1}
        <div class="mt-20 flex justify-center gap-2">
          {#each Array(data.total_pages) as _, i}
            <button
              on:click={() => navTo(category, i + 1)}
              class="w-12 h-12 flex items-center justify-center rounded-xl border font-bold transition-all {page === i + 1 ? 'bg-brand-accent text-white border-brand-accent shadow-lg shadow-brand-accent/30' : 'bg-brand-container text-brand-textSec border-brand-border hover:border-brand-accent/50'}"
            >{i + 1}</button>
          {/each}
        </div>
      {/if}
    {/if}
  </div>
</section>


