<script>
  import { onMount } from 'svelte';
  import { navigate } from '../lib/nav.js';

  export let currentPage = 'home';

  let scrolled = false;
  let mobileOpen = false;
  let theme = 'light';

  function navToSection(sectionId) {
    mobileOpen = false;
    const onHome = currentPage === 'home';
    if (!onHome) { navigate('/'); }
    setTimeout(() => {
      document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, onHome ? 0 : 420);
  }

  function toggleTheme() {
    theme = theme === 'dark' ? 'light' : 'dark';
    if (theme === 'dark') {
      document.documentElement.setAttribute('data-theme', 'dark');
      localStorage.setItem('theme', 'dark');
    } else {
      document.documentElement.removeAttribute('data-theme');
      localStorage.setItem('theme', 'light');
    }
  }

  function closeMobile() { mobileOpen = false; }

  onMount(() => {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      theme = 'dark';
      document.documentElement.setAttribute('data-theme', 'dark');
    }
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
        <button on:click={toggleTheme}
          class="w-9 h-9 rounded-full flex items-center justify-center text-brand-textSec hover:text-brand-accent hover:bg-brand-accent/10 transition-all focus:outline-none"
          title="Toggle tema">
          {#if theme === 'dark'}
            <i class="fa-solid fa-sun text-sm"></i>
          {:else}
            <i class="fa-solid fa-moon text-sm"></i>
          {/if}
        </button>
        <a href="/contact"
          class="inline-flex items-center gap-1.5 bg-brand-accent text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg shadow-brand-accent/25 hover:shadow-brand-accent/40 hover:-translate-y-0.5 transition-all duration-200"
          on:click={closeMobile}>
          <i class="fa-solid fa-paper-plane text-[10px]"></i> Hubungi Kami
        </a>
      </div>

      <!-- Mobile Right -->
      <div class="flex items-center gap-1 md:hidden">
        <button on:click={toggleTheme}
          class="w-9 h-9 rounded-full flex items-center justify-center text-brand-textSec hover:text-brand-accent hover:bg-brand-accent/10 transition-all focus:outline-none">
          {#if theme === 'dark'}
            <i class="fa-solid fa-sun text-sm"></i>
          {:else}
            <i class="fa-solid fa-moon text-sm"></i>
          {/if}
        </button>
        <button
          on:click={() => mobileOpen = !mobileOpen}
          class="w-9 h-9 rounded-full flex items-center justify-center text-brand-textSec hover:text-brand-textMain hover:bg-brand-container transition-all focus:outline-none"
          aria-expanded={mobileOpen}>
          <i class="text-base {mobileOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'}"></i>
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
            <i class="fa-solid fa-house w-4 text-center text-brand-accent"></i> Home
          </a>
          <a href="/projects" on:click={closeMobile}
            class="mobile-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
            class:mobile-active={currentPage === 'projects'}>
            <i class="fa-solid fa-briefcase w-4 text-center text-brand-accent"></i> Portfolio
          </a>
          <a href="/about" on:click={closeMobile}
            class="mobile-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
            class:mobile-active={currentPage === 'about'}>
            <i class="fa-solid fa-building w-4 text-center text-brand-accent"></i> Tentang Kami
          </a>
          <a href="/blog" on:click={closeMobile}
            class="mobile-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all"
            class:mobile-active={currentPage === 'blog'}>
            <i class="fa-solid fa-newspaper w-4 text-center text-brand-accent"></i> Blog
          </a>
        </div>
        <div class="px-3 pb-3">
          <a href="/contact" on:click={closeMobile}
            class="w-full flex items-center justify-center gap-2 bg-brand-accent text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg hover:-translate-y-0.5 transition-all">
            <i class="fa-solid fa-paper-plane text-xs"></i> Hubungi Kami
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
    background: rgb(var(--color-main) / 0.85);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid rgb(var(--color-border) / 0.6);
    padding: 0 1rem;
  }

  /* Floating pill state (scrolled) */
  .nav-floating {
    max-width: 900px;
    margin: 0.75rem auto;
    background: rgb(var(--color-container) / 0.92);
    backdrop-filter: blur(20px);
    border: 1px solid rgb(var(--color-border));
    border-radius: 9999px;
    padding: 0 1.25rem;
    box-shadow: 0 8px 32px rgb(0 0 0 / 0.12), 0 2px 8px rgb(0 0 0 / 0.06);
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
