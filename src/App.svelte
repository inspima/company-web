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
    const pathname = window.location.pathname || '/';
    const search = window.location.search || '';

    // Parse query string
    const qs = {};
    if (search) {
      new URLSearchParams(search).forEach((v, k) => { qs[k] = v; });
    }

    const segments = pathname.replace(/^\//, '').split('/').filter(Boolean);
    const [seg0 = '', seg1 = ''] = segments;

    if (!seg0)                            { page = 'home';           params = qs; return; }
    if (seg0 === 'build')                 { page = 'build';          params = qs; return; }
    if (seg0 === 'rescue')                { page = 'rescue';         params = qs; return; }
    if (seg0 === 'boost')                 { page = 'boost';          params = qs; return; }
    if (seg0 === 'projects')              { page = 'projects';       params = qs; return; }
    if (seg0 === 'blog')                  { page = 'blog';           params = qs; return; }
    if (seg0 === 'contact')               { page = 'contact';        params = qs; return; }
    if (seg0 === 'about')                 { page = 'about';          params = qs; return; }
    if (seg0 === 'project' && seg1)       { page = 'project-detail'; params = { slug: seg1, ...qs }; return; }
    if (seg0 === 'article' && seg1)       { page = 'article-detail'; params = { slug: seg1, ...qs }; return; }

    page = 'notfound';
    params = {};
  }

  /**
   * Global <a> click interceptor — prevents full page reloads for internal links.
   * External links, target="_blank", admin, and api paths are left alone.
   */
  function handleClick(e) {
    const a = e.target.closest('a[href]');
    if (!a) return;

    const href = a.getAttribute('href');
    if (!href) return;

    // Let external links, mailto, tel, anchors pass through
    if (
      href.startsWith('http') ||
      href.startsWith('//') ||
      href.startsWith('mailto:') ||
      href.startsWith('tel:') ||
      href.startsWith('#')
    ) return;

    // Let target="_blank" etc. pass through
    if (a.target && a.target !== '_self') return;

    // Let admin and api requests pass through (full page load)
    if (href.startsWith('/admin') || href.startsWith('/api.php')) return;

    e.preventDefault();
    history.pushState(null, '', href);
    parseRoute();
    window.scrollTo({ top: 0 });
  }

  onMount(() => {
    parseRoute();
    window.addEventListener('popstate', parseRoute);
    document.addEventListener('click', handleClick);
    return () => {
      window.removeEventListener('popstate', parseRoute);
      document.removeEventListener('click', handleClick);
    };
  });
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
