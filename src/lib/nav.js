/**
 * Navigate to a section or dedicated page.
 * - 'contact' → dedicated #/contact page
 * - 'about'   → dedicated #/about page
 * - other     → scrolls to element with that id on the home page
 */
export function goToSection(sectionId) {
  if (sectionId === 'contact') {
    window.location.hash = '#/contact';
    return;
  }
  if (sectionId === 'about') {
    window.location.hash = '#/about';
    return;
  }

  const hash   = window.location.hash;
  const isHome = !hash || hash === '#/' || hash === '#';
  if (!isHome) window.location.hash = '#/';
  setTimeout(() => {
    document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, isHome ? 60 : 420);
}
