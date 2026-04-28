<script>
  import { onMount } from 'svelte';
  import Navbar from './components/Navbar.svelte';
  import Footer from './components/Footer.svelte';
  import Home from './pages/Home.svelte';
  import Build from './pages/Build.svelte';
  import Rescue from './pages/Rescue.svelte';
  import Boost from './pages/Boost.svelte';
  import Projects from './pages/Projects.svelte';
  import Blog from './pages/Blog.svelte';
  import ProjectDetail from './pages/ProjectDetail.svelte';
  import ArticleDetail from './pages/ArticleDetail.svelte';
  import Contact from './pages/Contact.svelte';
  import About from './pages/About.svelte';
  import NotFound from './pages/NotFound.svelte';

  let page = 'home';
  let params = {};

  function parseRoute() {
    const hash = window.location.hash || '#/';
    const raw  = hash.startsWith('#') ? hash.slice(1) : hash;
    const [pathPart, queryPart] = raw.split('?');
    const segments = pathPart.replace(/^\//, '').split('/').filter(Boolean);

    // parse query string
    const qs = {};
    if (queryPart) {
      new URLSearchParams(queryPart).forEach((v, k) => { qs[k] = v; });
    }

    const [seg0 = '', seg1 = ''] = segments;

    if (!seg0 || seg0 === '') { page = 'home';    params = qs; return; }
    if (seg0 === 'build')     { page = 'build';   params = qs; return; }
    if (seg0 === 'rescue')    { page = 'rescue';  params = qs; return; }
    if (seg0 === 'boost')     { page = 'boost';   params = qs; return; }
    if (seg0 === 'projects')  { page = 'projects'; params = qs; return; }
    if (seg0 === 'blog')      { page = 'blog';    params = qs; return; }
    if (seg0 === 'contact')  { page = 'contact'; params = qs; return; }
    if (seg0 === 'about')    { page = 'about';   params = qs; return; }
    if (seg0 === 'project' && seg1) { page = 'project-detail'; params = { slug: seg1, ...qs }; return; }
    if (seg0 === 'article' && seg1) { page = 'article-detail'; params = { slug: seg1, ...qs }; return; }

    page = 'notfound';
    params = {};
  }

  onMount(() => {
    parseRoute();
    window.addEventListener('hashchange', parseRoute);
    return () => window.removeEventListener('hashchange', parseRoute);
  });

  // Scroll to top on navigation
  $: if (page) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
</script>

<div class="flex flex-col min-h-screen">
  <Navbar currentPage={page} />

  <main class="flex-grow">
    {#if page === 'home'}
      <Home />
    {:else if page === 'build'}
      <Build />
    {:else if page === 'rescue'}
      <Rescue />
    {:else if page === 'boost'}
      <Boost />
    {:else if page === 'projects'}
      <Projects category={params.category || 'all'} page={parseInt(params.page || '1')} />
    {:else if page === 'blog'}
      <Blog category={params.category || 'all'} page={parseInt(params.page || '1')} />
    {:else if page === 'project-detail'}
      <ProjectDetail slug={params.slug} />
    {:else if page === 'article-detail'}
      <ArticleDetail slug={params.slug} />
    {:else if page === 'contact'}
      <Contact />
    {:else if page === 'about'}
      <About />
    {:else}
      <NotFound />
    {/if}
  </main>

  <Footer currentPage={page} />
</div>

<!-- WhatsApp Float Button -->
<a href="https://wa.me/6285156625480" target="_blank" rel="noopener" class="wa-btn" title="Chat via WhatsApp">
  <i class="fa-brands fa-whatsapp"></i>
</a>
