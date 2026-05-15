<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import { navigate } from '../lib/nav.js';
  import Icon from '../lib/Icon.svelte';

  export let category = 'all';
  export let page = 1;

  let data = null;
  let categories = [];
  let loading = true;

  async function load() {
    loading = true;
    try {
      [data, categories] = await Promise.all([
        api.articles({ category, page, limit: 9 }),
        api.categories(),
      ]);
    } catch(e) {}
    loading = false;
  }

  function navTo(cat, p) {
    const qs = [];
    if (cat && cat !== 'all') qs.push(`category=${cat}`);
    if (p > 1) qs.push(`page=${p}`);
    navigate('/blog' + (qs.length ? '?' + qs.join('&') : ''));
  }

  onMount(load);
  $: if (category || page) load();
</script>

<!-- Hero -->
<section class="relative pt-32 pb-24 overflow-hidden bg-brand-main">
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-20">
    <div class="absolute top-1/4 left-10 w-96 h-96 bg-brand-accent/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-1/4 right-10 w-96 h-96 bg-brand-accent/10 rounded-full blur-[120px]"></div>
  </div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
    <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-8">Knowledge Base</span>
    <h1 class="hero-heading">
      Inspima <span class="text-brand-accent underline decoration-brand-accent/30 underline-offset-8">Insights</span>
    </h1>
    <p class="text-xl text-brand-textSec max-w-2xl mx-auto font-light leading-relaxed">
      Sharing thoughts, latest tech trends, and deep dives to help you navigate the ever-evolving digital ecosystem.
    </p>
  </div>
</section>

<!-- Content -->
<section class="py-20 bg-brand-main min-h-[60vh]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Filter Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-20">
      <div class="flex flex-wrap items-center gap-3">
        <button
          on:click={() => navTo('all', 1)}
          class="px-6 py-2.5 rounded-2xl text-sm font-bold transition-all {category === 'all' ? 'bg-brand-accent text-white shadow-xl shadow-brand-accent/30' : 'bg-brand-container text-brand-textSec border border-brand-border hover:border-brand-accent/50'}"
        >All</button>
        {#each categories as cat}
          <button
            on:click={() => navTo(cat.slug, 1)}
            class="px-6 py-2.5 rounded-2xl text-sm font-bold transition-all {category === cat.slug ? 'bg-brand-accent text-white shadow-xl shadow-brand-accent/30' : 'bg-brand-container text-brand-textSec border border-brand-border hover:border-brand-accent/50'}"
          >{cat.name}</button>
        {/each}
      </div>
    </div>

    <!-- Grid -->
    {#if loading}
      <div class="flex justify-center py-32"><div class="spinner"></div></div>
    {:else if !data?.data?.length}
      <div class="py-32 text-center">
        <div class="w-24 h-24 bg-brand-container rounded-full flex items-center justify-center mx-auto mb-6 border border-brand-border">
          <Icon name="newspaper" size={36} cls="text-brand-textSec/30" />
        </div>
        <h3 class="text-2xl font-bold text-brand-textMain mb-2">No articles found</h3>
        <p class="text-brand-textSec font-light">Try a different filter.</p>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        {#each data.data as art}
          <a href="/article/{art.slug}" class="group bg-brand-container rounded-[2.5rem] overflow-hidden border border-brand-border hover:-translate-y-3 transition-all duration-500 card-glow flex flex-col h-full">
            <div class="relative h-60 overflow-hidden bg-brand-main p-4">
              {#if art.image_url}
                <img src={art.image_url} alt={art.title} class="w-full h-full object-cover rounded-[1.5rem] group-hover:scale-110 transition-transform duration-1000 opacity-90 group-hover:opacity-100">
              {:else}
                <div class="w-full h-full rounded-[1.5rem] flex items-center justify-center bg-brand-main border border-white/5 text-brand-textSec/10">
                  <div class="text-brand-textSec/10"><Icon name="newspaper" size={72} /></div>
                </div>
              {/if}
              <div class="absolute top-8 left-8">
                <span class="bg-brand-main/80 backdrop-blur-md text-brand-accent border border-brand-accent/30 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg">
                  {art.category_name || 'Blog'}
                </span>
              </div>
            </div>
            <div class="p-10 flex flex-col flex-grow">
              <div class="flex items-center gap-4 text-xs text-brand-textSec mb-6 font-medium">
                <span class="flex items-center gap-1.5"><Icon name="clock" size={12} cls="text-brand-accent" />
                  {new Date(art.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                </span>
                <span class="w-1 h-1 bg-brand-border rounded-full"></span>
                <span>{art.read_time} min read</span>
              </div>
              <h3 class="text-2xl font-bold text-brand-textMain mb-4 group-hover:text-brand-accent transition-colors leading-tight">{art.title}</h3>
              <p class="text-brand-textSec text-sm font-light leading-relaxed line-clamp-3 mb-8 flex-grow">{art.excerpt}...</p>
              <div class="pt-6 border-t border-brand-border/50 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-accent flex items-center gap-2 group-hover:gap-4 transition-all">Read More <Icon name="chevron-right" size={12} /></span>
              </div>
            </div>
          </a>
        {/each}
      </div>

      <!-- Pagination -->
      {#if data.total_pages > 1}
        <div class="mt-20 flex justify-center gap-3">
          {#each Array(data.total_pages) as _, i}
            <button
              on:click={() => navTo(category, i + 1)}
              class="w-14 h-14 flex items-center justify-center rounded-2xl border font-black transition-all {page === i + 1 ? 'bg-brand-accent text-white border-brand-accent shadow-2xl shadow-brand-accent/40' : 'bg-brand-container text-brand-textSec border-brand-border hover:border-brand-accent/50'}"
            >{i + 1}</button>
          {/each}
        </div>
      {/if}
    {/if}
  </div>
</section>


