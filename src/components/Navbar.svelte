<script>
  import { onMount } from 'svelte';
  import { navigate } from '../lib/nav.js';
  import Icon from '../lib/Icon.svelte';

  export let currentPage = 'home';

  let scrolled = false;
  let mobileOpen = false;

  function navToSection(sectionId) {
    mobileOpen = false;
    const onHome = currentPage === 'home';
    if (!onHome) { navigate('/'); }
    setTimeout(() => {
      document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, onHome ? 0 : 420);
  }

  function closeMobile() { mobileOpen = false; }

  onMount(() => {
    const onScroll = () => { scrolled = window.scrollY > 40; };
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  });
</script>

<nav
  id="navbar"
  class="fixed w-full z-50 transition-all duration-300"
  class:scrolled
>
  <!-- Main bar -->
  <div
    class="mx-auto transition-all duration-300"
    class:nav-floating={scrolled}
    class:nav-full={!scrolled}
  >
    <div class="flex justify-between items-center h-16 px-4 sm:px-6">

      <!-- Logo -->
      <a href="/" class="flex items-center gap-2.5 group flex-shrink-0" on:click={closeMobile}>
        <img src="/dist/assets/images/logo.png" alt="Inspima"
             class="h-9 w-auto group-hover:scale-105 transition-transform drop-shadow-sm"
             onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex'">
        <span class="hidden font-heading font-black text-lg text-brand-accent" style="display:none">INSPIMA</span>
      </a>

      <!-- Desktop Nav -->
      <div class="hidden md:flex items-center gap-1">
        <a href="/"
           class="nav-item text-sm font-semibold px-3.5 py-2 rounded-full transition-all duration-200"
           class:nav-active={currentPage === 'home'}
           on:click={closeMobile}>
          Home
        </a>
        <a href="/projects"
           class="nav-item text-sm font-semibold px-3.5 py-2 rounded-full transition-all duration-200"
           class:nav-active={currentPage === 'projects'}
           on:click={closeMobile}>
          Portfolio
        </a>
        <a href="/about"
           class="nav-item text-sm font-semibold px-3.5 py-2 rounded-full transition-all duration-200"
           class:nav-active={currentPage === 'about'}
           on:click={closeMobile}>
          Tentang
        </a>
        <a href="/blog"
           class="nav-item text-sm font-semibold px-3.5 py-2 rounded-full transition-all duration-200"
           class:nav-active={currentPage === 'blog'}
           on:click={closeMobile}>
          Blog
        </a>
      </div>

      <!-- Desktop Right -->
      <div class="hidden md:flex items-center gap-2">
        <a href="/contact"
          class="inline-flex items-center gap-1.5 bg-brand-accent text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg shadow-brand-accent/25 hover:shadow-brand-accent/40 hover:-translate-y-0.5 transition-all duration-200"
          on:click={closeMobile}>
          <Icon name="paper-plane" size={12} /> Hubungi Kami
        </a>
      </div>

      <!-- Mobile Right -->
      <div class="flex items-center gap-1 md:hidden">
        <button
          on:click={() => mobileOpen = !mobileOpen}
          class="w-9 h-9 rounded-full flex items-center justify-center text-brand-textSec hover:text-brand-textMain hover:bg-brand-container transition-all focus:outline-none"
          aria-expanded={mobileOpen}>
          {#if mobileOpen}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
              <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
          {:else}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
              <line x1="3" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
              <line x1="3" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
          {/if}
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Dropdown -->
  {#if mobileOpen}
    <div class="md:hidden absolute top-full left-0 w-full px-3 pt-2 pb-3">
      <div class="bg-brand-container/95 backdrop-blur-xl border border-brand-border rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-3 space-y-0.5">
          <a href="/" on:click={closeMobile}
            class="mobile-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
            class:mobile-active={currentPage === 'home'}>
            <span class="w-4 text-brand-accent flex-shrink-0"><Icon name="home" size={16} /></span> Home
          </a>
          <a href="/projects" on:click={closeMobile}
            class="mobile-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
            class:mobile-active={currentPage === 'projects'}>
            <span class="w-4 text-brand-accent flex-shrink-0"><Icon name="briefcase" size={16} /></span> Portfolio
          </a>
          <a href="/about" on:click={closeMobile}
            class="mobile-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
            class:mobile-active={currentPage === 'about'}>
            <span class="w-4 text-brand-accent flex-shrink-0"><Icon name="building" size={16} /></span> Tentang Kami
          </a>
          <a href="/blog" on:click={closeMobile}
            class="mobile-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
            class:mobile-active={currentPage === 'blog'}>
            <span class="w-4 text-brand-accent flex-shrink-0"><Icon name="newspaper" size={16} /></span> Blog
          </a>
        </div>
        <div class="px-3 pb-3">
          <a href="/contact" on:click={closeMobile}
            class="w-full flex items-center justify-center gap-2 bg-brand-accent text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg hover:-translate-y-0.5 transition-all">
            <Icon name="paper-plane" size={14} /> Hubungi Kami
          </a>
        </div>
      </div>
    </div>
  {/if}
</nav>

<style>
  /* Full-width state (top of page) */
  .nav-full {
    max-width: 100%;
    background: rgb(255 255 255 / 0.68);
    backdrop-filter: blur(22px) saturate(165%);
    -webkit-backdrop-filter: blur(22px) saturate(165%);
    border-bottom: 1px solid rgb(255 255 255 / 0.72);
    padding: 0 1rem;
    box-shadow: 0 10px 35px rgb(30 90 230 / 0.07);
  }

  /* Floating pill state (scrolled) */
  .nav-floating {
    max-width: 900px;
    margin: 0.75rem auto;
    background: rgb(255 255 255 / 0.72);
    backdrop-filter: blur(24px) saturate(170%);
    -webkit-backdrop-filter: blur(24px) saturate(170%);
    border: 1px solid rgb(255 255 255 / 0.78);
    border-radius: 9999px;
    padding: 0 1.25rem;
    box-shadow: 0 18px 48px rgb(30 90 230 / 0.13), 0 2px 8px rgb(69 184 239 / 0.08);
  }


  /* Nav link items */
  :global(.nav-item) {
    color: rgb(var(--color-text-sec));
  }
  :global(.nav-item:hover),
  :global(.nav-item.nav-active) {
    color: rgb(var(--color-text-main));
    background: rgb(var(--color-accent) / 0.08);
  }
  :global(.nav-item.nav-active) {
    color: rgb(var(--color-accent));
  }

  /* Mobile items */
  :global(.mobile-item) {
    color: rgb(var(--color-text-sec));
  }
  :global(.mobile-item:hover) {
    color: rgb(var(--color-text-main));
    background: rgb(var(--color-main));
  }
  :global(.mobile-item.mobile-active) {
    color: rgb(var(--color-accent));
    background: rgb(var(--color-accent) / 0.06);
  }
</style>
