<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import { goToSection } from '../lib/nav.js';

  export let slug = '';

  let proj = null;
  let related = [];
  let loading = true;
  let error = null;

  onMount(async () => {
    try {
      const res = await api.project(slug);
      proj = res.project;
      related = res.related || [];
    } catch(e) {
      error = 'Proyek tidak ditemukan.';
    }
    loading = false;
  });
</script>

{#if loading}
  <div class="flex justify-center items-center min-h-screen">
    <div class="spinner"></div>
  </div>
{:else if error}
  <div class="flex flex-col items-center justify-center min-h-screen gap-4 text-center px-4">
    <i class="fa-solid fa-triangle-exclamation text-5xl text-brand-textSec/30"></i>
    <h2 class="text-2xl font-bold text-brand-textMain">{error}</h2>
    <a href="#/projects" class="text-brand-accent font-semibold hover:underline">← Kembali ke Portfolio</a>
  </div>
{:else}
  <!-- Hero -->
  <section class="relative pt-32 pb-0 bg-brand-main overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-xs text-brand-textSec mb-8">
        <a href="#/" class="hover:text-brand-accent transition-colors">Home</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <a href="#/projects" class="hover:text-brand-accent transition-colors">Portfolio</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <span class="text-brand-textMain">{proj.title}</span>
      </div>

      <!-- Pillar badges -->
      <div class="flex flex-wrap gap-2 mb-6">
        {#if proj.pilar_build}<span class="bg-brand-accent/10 text-brand-accent border border-brand-accent/30 px-3 py-1 text-[9px] font-black rounded-md uppercase">BUILD</span>{/if}
        {#if proj.pilar_rescue}<span class="bg-teal-500/10 text-teal-500 border border-teal-500/30 px-3 py-1 text-[9px] font-black rounded-md uppercase">RESCUE</span>{/if}
        {#if proj.pilar_boost}<span class="bg-orange-500/10 text-orange-400 border border-orange-500/30 px-3 py-1 text-[9px] font-black rounded-md uppercase">BOOST</span>{/if}
        {#if proj.category_name}<span class="bg-brand-container text-brand-textSec border border-brand-border px-3 py-1 text-[9px] font-bold rounded-md uppercase">{proj.category_name}</span>{/if}
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 pb-16 items-start">
        <!-- Main content -->
        <div class="lg:col-span-2">
          <h1 class="font-heading text-4xl md:text-6xl font-black text-brand-textMain leading-tight mb-8">{proj.title}</h1>

          {#if proj.image_url}
            <div class="rounded-[2.5rem] overflow-hidden mb-12 border border-brand-border">
              <img src={proj.image_url} alt={proj.title} class="w-full object-cover max-h-[500px]">
            </div>
          {/if}

          <div class="prose prose-lg max-w-none">
            {@html proj.description || '<p class="text-brand-textSec">Deskripsi tidak tersedia.</p>'}
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <div class="bg-brand-container rounded-3xl p-8 border border-brand-border sticky top-28">
            <h3 class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-6">Project Details</h3>
            <div class="space-y-5">
              {#if proj.client_name}
                <div>
                  <div class="text-[10px] text-brand-textSec uppercase tracking-widest font-bold mb-1">Client</div>
                  <div class="text-brand-textMain font-semibold">{proj.client_name}</div>
                </div>
              {/if}
              {#if proj.category_name}
                <div>
                  <div class="text-[10px] text-brand-textSec uppercase tracking-widest font-bold mb-1">Kategori</div>
                  <div class="text-brand-textMain font-semibold">{proj.category_name}</div>
                </div>
              {/if}
              {#if proj.project_url}
                <div>
                  <div class="text-[10px] text-brand-textSec uppercase tracking-widest font-bold mb-1">Live URL</div>
                  <a href={proj.project_url} target="_blank" rel="noopener" class="text-brand-accent font-semibold text-sm hover:underline flex items-center gap-1">
                    Lihat Website <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                  </a>
                </div>
              {/if}
            </div>

            <div class="mt-8 pt-6 border-t border-brand-border">
              <a href="#/" on:click|preventDefault={() => goToSection('contact')} class="block w-full text-center bg-brand-accent text-white py-3.5 rounded-xl font-bold text-sm hover:-translate-y-0.5 transition-all shadow-lg shadow-brand-accent/30">
                Proyek Serupa? Hubungi Kami
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Related -->
  {#if related.length > 0}
    <section class="py-20 bg-brand-container/50 border-t border-brand-border">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-black text-brand-textMain mb-10">Proyek Lainnya</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          {#each related as r}
            <a href="#/project/{r.slug}" class="group bento-item bg-brand-container rounded-3xl overflow-hidden border border-brand-border card-glow">
              <div class="h-44 overflow-hidden bg-brand-main">
                {#if r.image_url}
                  <img src={r.image_url} alt={r.title} class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                {:else}
                  <div class="w-full h-full flex items-center justify-center text-brand-textSec/10">
                    <i class="fa-solid fa-layer-group text-7xl"></i>
                  </div>
                {/if}
              </div>
              <div class="p-6">
                <h3 class="font-bold text-brand-textMain group-hover:text-brand-accent transition-colors">{r.title}</h3>
                {#if r.client_name}<p class="text-xs text-brand-textSec mt-1">{r.client_name}</p>{/if}
              </div>
            </a>
          {/each}
        </div>
      </div>
    </section>
  {/if}
{/if}
