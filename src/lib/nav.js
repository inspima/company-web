/**
 * Client-side navigation helper.
 * Uses History API so URLs are clean (no #/).
 */
export function navigate(url) {
  history.pushState(null, '', url);
  // Dispatch popstate so App.svelte router picks it up
  window.dispatchEvent(new PopStateEvent('popstate', { state: null }));
}

/**
 * Navigate to a section or dedicated page.
 * - 'contact' → /contact page
 * - 'about'   → /about page
 * - other     → scrolls to element with that id on the home page
 */
export function goToSection(sectionId) {
  if (sectionId === 'contact') {
    navigate('/contact');
    return;
  }
  if (sectionId === 'about') {
    navigate('/about');
    return;
  }

  const isHome = window.location.pathname === '/' || window.location.pathname === '';
  if (!isHome) navigate('/');
  setTimeout(() => {
    document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, isHome ? 60 : 420);
}
