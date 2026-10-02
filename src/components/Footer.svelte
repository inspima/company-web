<script>
  import { goToSection } from "../lib/nav.js";
  import Icon from "../lib/Icon.svelte";
  export let currentPage = '';
  const year = new Date().getFullYear();
</script>

<footer class="bg-brand-container border-t border-brand-border">
  <!-- Top CTA (hidden on contact page) -->
  {#if currentPage !== 'contact'}
  <div class="footer-cta py-10">
    <div class="footer-cta-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div>
      <p class="text-[10px] font-semibold tracking-[0.16em] uppercase text-slate-400 mb-2">LET’S WORK TOGETHER</p>
      <h3 class="text-xl md:text-2xl font-bold text-white mb-2">
        Siap Memulai Proyek Anda?
      </h3>
      <p class="text-slate-300 text-sm font-normal">
        Hubungi tim kami dan dapatkan konsultasi gratis hari ini.
      </p>
      </div>
      <button
        on:click={() => goToSection("contact")}
        class="inline-flex items-center gap-2 bg-white text-[#142238] px-5 py-3 rounded-lg font-semibold text-xs hover:-translate-y-0.5 hover:bg-slate-100 transition-all border-0 cursor-pointer"
      >
        Mulai Sekarang <Icon name="arrow-up-right" size={15} />
      </button>
    </div>
  </div>
  {/if}

  <!-- Main Footer -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <!-- Brand -->
      <div class="md:col-span-1">
        <a href="/" class="inline-flex items-center gap-2 mb-4">
          <img
            src="/assets/images/logo.png"
            alt="Inspima"
            class="h-8 w-auto"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
          />
          <span class="hidden font-heading font-black text-xl text-brand-accent">INSPIMA</span>
        </a>
        <p class="text-brand-textSec text-sm font-normal leading-relaxed mt-4">
          Solusi teknologi digital terpercaya untuk bisnis Anda. Kami hadir
          untuk Build, Rescue, dan Boost produk digital Anda.
        </p>
        <div class="flex gap-3 mt-6">
          <a href="https://maps.app.goo.gl/q2XFHadifQ3dmhyf9?g_st=atm" target="_blank" rel="noopener"
            class="footer-social" aria-label="Lokasi INSPIMA di Google Maps"
            title="Google Maps">
            <Icon variant="outline" name="map-pin" size={18} />
          </a>
          <a href="https://www.instagram.com/inspima.creative" target="_blank" rel="noopener"
            class="footer-social" aria-label="Instagram INSPIMA"
            title="Instagram">
            <Icon variant="outline" name="instagram" size={18} />
          </a>
        </div>
      </div>

      <!-- Services -->
      <div>
        <h4 class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5">Layanan</h4>
        <ul class="space-y-3 text-sm text-brand-textSec font-normal">
          <li>
            <a href="/build" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="footer-link-icon"><Icon variant="outline" name="layers" size={18} /></span> BUILD
            </a>
          </li>
          <li>
            <a href="/rescue" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="footer-link-icon"><Icon variant="outline" name="wrench" size={18} /></span> RESCUE
            </a>
          </li>
          <li>
            <a href="/boost" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="footer-link-icon"><Icon variant="outline" name="rocket" size={18} /></span> BOOST
            </a>
          </li>
          <li>
            <a href="/projects" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="footer-link-icon"><Icon variant="outline" name="briefcase" size={18} /></span> Portfolio
            </a>
          </li>
          <li>
            <a href="/blog" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="footer-link-icon"><Icon variant="outline" name="newspaper" size={18} /></span> Insights
            </a>
          </li>
        </ul>
      </div>

      <!-- Company -->
      <div>
        <h4 class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5">Perusahaan</h4>
        <ul class="space-y-3 text-sm text-brand-textSec font-normal">
          <li><a href="/about" class="hover:text-brand-accent transition-colors">Tentang Kami</a></li>
          <li>
            <button on:click={() => goToSection("services")}
              class="hover:text-brand-accent transition-colors bg-transparent border-0 p-0 cursor-pointer text-sm font-normal text-brand-textSec">
              Layanan
            </button>
          </li>
          <li><a href="/contact" class="hover:text-brand-accent transition-colors">Kontak</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h4 class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5">Kontak</h4>
        <ul class="space-y-4 text-sm text-brand-textSec font-normal">
          <li class="flex items-start gap-3">
            <span class="footer-contact-icon mt-0.5"><Icon variant="outline" name="location" size={18} /></span>
            <span>Surabaya, Jawa Timur, Indonesia</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="footer-contact-icon"><Icon variant="outline" name="envelope" size={18} /></span>
            <a href="mailto:hello@inspima.id" class="hover:text-brand-accent transition-colors">hello@inspima.id</a>
          </li>
          <li class="flex items-center gap-3">
            <span class="footer-contact-icon"><Icon variant="outline" name="whatsapp" size={18} /></span>
            <a href="https://wa.me/6285156625480" target="_blank" rel="noopener" class="hover:text-brand-accent transition-colors">+62 851-5662-5480</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="border-t border-brand-border py-4">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2 text-xs text-brand-textSec">
      <span>&copy; {year} INSPIMA. All rights reserved.</span>
      <span class="flex items-center gap-1">Built with <span class="text-brand-textSec inline-flex"><Icon variant="outline" name="heart" size={12} /></span> in Surabaya</span>
    </div>
  </div>
</footer>

<style>
  .footer-social { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgb(var(--color-border)); border-radius: 9px; color: rgb(var(--color-text-sec)); transition: color .2s ease, border-color .2s ease, background .2s ease; }
  .footer-social:hover { color: rgb(var(--color-accent)); border-color: rgb(var(--color-accent) / .35); background: #f5f8ff; }
  .footer-link-icon { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; flex-shrink: 0; }
  .footer-contact-icon { display: inline-flex; flex-shrink: 0; color: rgb(var(--color-text-sec)); }
  .footer-cta { background: #142238; }
  .footer-cta-inner { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; }
  .footer-cta-inner button { flex-shrink: 0; }
  @media (max-width: 640px) {
    .footer-cta-inner { flex-direction: column; text-align: center; align-items: center; }
  }
</style>
