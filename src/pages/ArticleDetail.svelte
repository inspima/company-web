<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';

  export let slug = '';

  let art = null;
  let related = [];
  let loading = true;
  let error = null;

  onMount(async () => {
    try {
      const res = await api.article(slug);
      art = res.article;
      related = res.related || [];
    } catch(e) {
      error = 'Artikel tidak ditemukan.';
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
    <a href="#/blog" class="text-brand-accent font-semibold hover:underline">← Kembali ke Blog</a>
  </div>
{:else}
  <!-- Hero -->
  <section class="relative pt-32 pb-16 bg-brand-main overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-xs text-brand-textSec mb-8">
        <a href="#/" class="hover:text-brand-accent transition-colors">Home</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <a href="#/blog" class="hover:text-brand-accent transition-colors">Insights</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <span class="text-brand-textMain line-clamp-1">{art.title}</span>
      </div>

      <!-- Meta -->
      <div class="flex flex-wrap items-center gap-4 mb-8">
        {#if art.category_name}
          <span class="bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">{art.category_name}</span>
        {/if}
        <span class="flex items-center gap-1.5 text-xs text-brand-textSec">
          <i class="fa-regular fa-calendar-check text-brand-accent"></i>
          {new Date(art.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
        </span>
        <span class="text-xs text-brand-textSec">{art.read_time} min read</span>
      </div>

      <h1 class="hero-heading">{art.title}</h1>

      {#if art.image_url}
        <div class="rounded-[2.5rem] overflow-hidden mb-12 border border-brand-border">
          <img src={art.image_url} alt={art.title} class="w-full object-cover max-h-[500px]">
        </div>
      {/if}
    </div>
  </section>

  <!-- Article Content -->
  <section class="pb-24 bg-brand-main">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="prose prose-lg max-w-none">
        {@html art.content || '<p>Konten tidak tersedia.</p>'}
      </div>

      <!-- Tags / Share -->
      <div class="mt-16 pt-10 border-t border-brand-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-3 text-sm text-brand-textSec">
          <span class="font-semibold">Share:</span>
          <a href="https://twitter.com/intent/tweet?text={encodeURIComponent(art.title)}&url={encodeURIComponent(window.location.href)}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-brand-container border border-brand-border flex items-center justify-center text-brand-textSec hover:text-white hover:bg-[#1da1f2] hover:border-[#1da1f2] transition-all">
            <i class="fa-brands fa-x-twitter text-sm"></i>
          </a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url={encodeURIComponent(window.location.href)}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-brand-container border border-brand-border flex items-center justify-center text-brand-textSec hover:text-white hover:bg-[#0077b5] hover:border-[#0077b5] transition-all">
            <i class="fa-brands fa-linkedin-in text-sm"></i>
          </a>
        </div>
        <a href="#/blog" class="text-brand-accent font-semibold text-sm flex items-center gap-2 hover:gap-4 transition-all">
          <i class="fa-solid fa-arrow-left text-xs"></i> Semua Artikel
        </a>
      </div>
    </div>
  </section>

  <!-- Related Articles -->
  {#if related.length > 0}
    <section class="py-20 bg-brand-container/50 border-t border-brand-border">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-black text-brand-textMain mb-10">Artikel Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          {#each related as r}
            <a href="#/article/{r.slug}" class="group bento-item bg-brand-container rounded-3xl overflow-hidden border border-brand-border card-glow">
              <div class="h-44 overflow-hidden bg-brand-main p-3">
                {#if r.image_url}
                  <img src={r.image_url} alt={r.title} class="w-full h-full object-cover rounded-2xl group-hover:scale-110 transition-transform duration-700">
                {:else}
                  <div class="w-full h-full rounded-2xl flex items-center justify-center bg-brand-main border border-white/5 text-brand-textSec/10">
                    <i class="fa-solid fa-newspaper text-6xl"></i>
                  </div>
                {/if}
              </div>
              <div class="p-6">
                <p class="text-xs text-brand-textSec mb-2">{new Date(r.created_at).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'})}</p>
                <h3 class="font-bold text-brand-textMain group-hover:text-brand-accent transition-colors leading-tight">{r.title}</h3>
              </div>
            </a>
          {/each}
        </div>
      </div>
    </section>
  {/if}
{/if}
