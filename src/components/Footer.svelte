<script>
  import { goToSection } from "../lib/nav.js";
  import Icon from "../lib/Icon.svelte";
  export let currentPage = '';
  const year = new Date().getFullYear();
</script>

<footer class="bg-brand-container border-t border-brand-border">
  <!-- Top CTA (hidden on contact page) -->
  {#if currentPage !== 'contact'}
  <div class="footer-cta border-b border-blue-900/40 py-12">
    <div class="max-w-5xl mx-auto px-4 text-center">
      <h3 class="text-2xl md:text-3xl font-black text-white mb-4">
        Siap Memulai Proyek Anda?
      </h3>
      <p class="text-blue-100/85 mb-6 font-light">
        Hubungi tim kami dan dapatkan konsultasi gratis hari ini.
      </p>
      <button
        on:click={() => goToSection("contact")}
        class="inline-flex items-center gap-2 bg-white text-[#1544e6] px-8 py-3.5 rounded-full font-bold text-sm shadow-xl shadow-black/20 hover:-translate-y-1 hover:bg-sky-50 transition-all border-0 cursor-pointer"
      >
        <Icon name="rocket" size={14} /> Mulai Sekarang
      </button>
    </div>
  </div>
  {/if}

  <!-- Main Footer -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
      <!-- Brand -->
      <div class="md:col-span-1">
        <a href="/" class="inline-flex items-center gap-2 mb-4">
          <img
            src="/assets/images/logo.png"
            alt="Inspima"
            class="h-10 w-auto"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
          />
          <span class="hidden font-heading font-black text-xl text-brand-accent">INSPIMA</span>
        </a>
        <p class="text-brand-textSec text-sm font-light leading-relaxed mt-4">
          Solusi teknologi digital terpercaya untuk bisnis Anda. Kami hadir
          untuk Build, Rescue, dan Boost produk digital Anda.
        </p>
        <div class="flex gap-3 mt-6">
          <a href="https://maps.app.goo.gl/q2XFHadifQ3dmhyf9?g_st=atm" target="_blank" rel="noopener"
            class="w-9 h-9 flex items-center justify-center rounded-full bg-brand-main border border-brand-border text-brand-textSec hover:text-white hover:bg-[#4285F4] hover:border-[#4285F4] transition-all"
            title="Google Maps">
            <Icon name="map-pin" size={16} />
          </a>
          <a href="https://www.instagram.com/inspima.creative" target="_blank" rel="noopener"
            class="w-9 h-9 flex items-center justify-center rounded-full bg-brand-main border border-brand-border text-brand-textSec hover:text-white hover:bg-[#e1306c] hover:border-[#e1306c] transition-all"
            title="Instagram">
            <Icon name="instagram" size={16} />
          </a>
        </div>
      </div>

      <!-- Services -->
      <div>
        <h4 class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5">Layanan</h4>
        <ul class="space-y-3 text-sm text-brand-textSec font-light">
          <li>
            <a href="/build" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="w-4 text-brand-accent/60 flex-shrink-0"><Icon name="layers" size={14} /></span>BUILD
            </a>
          </li>
          <li>
            <a href="/rescue" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="w-4 text-brand-accent/60 flex-shrink-0"><Icon name="wrench" size={14} /></span>RESCUE
            </a>
          </li>
          <li>
            <a href="/boost" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="w-4 text-brand-accent/60 flex-shrink-0"><Icon name="rocket" size={14} /></span>BOOST
            </a>
          </li>
          <li>
            <a href="/projects" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="w-4 text-brand-accent/60 flex-shrink-0"><Icon name="briefcase" size={14} /></span>Portfolio
            </a>
          </li>
          <li>
            <a href="/blog" class="hover:text-brand-accent transition-colors flex items-center gap-2">
              <span class="w-4 text-brand-accent/60 flex-shrink-0"><Icon name="newspaper" size={14} /></span>Insights
            </a>
          </li>
        </ul>
      </div>

      <!-- Company -->
      <div>
        <h4 class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5">Perusahaan</h4>
        <ul class="space-y-3 text-sm text-brand-textSec font-light">
          <li><a href="/about" class="hover:text-brand-accent transition-colors">Tentang Kami</a></li>
          <li>
            <button on:click={() => goToSection("services")}
              class="hover:text-brand-accent transition-colors bg-transparent border-0 p-0 cursor-pointer text-sm font-light text-brand-textSec">
              Layanan
            </button>
          </li>
          <li><a href="/contact" class="hover:text-brand-accent transition-colors">Kontak</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h4 class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5">Kontak</h4>
        <ul class="space-y-4 text-sm text-brand-textSec font-light">
          <li class="flex items-start gap-3">
            <span class="text-brand-accent mt-0.5 flex-shrink-0"><Icon name="location" size={16} /></span>
            <span>Surabaya, Jawa Timur, Indonesia</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="text-brand-accent flex-shrink-0"><Icon name="envelope" size={16} /></span>
            <a href="mailto:hello@inspima.id" class="hover:text-brand-accent transition-colors">hello@inspima.id</a>
          </li>
          <li class="flex items-center gap-3">
            <span class="text-brand-accent flex-shrink-0"><Icon name="whatsapp" size={16} /></span>
            <a href="https://wa.me/6285156625480" target="_blank" rel="noopener" class="hover:text-brand-accent transition-colors">+62 851-5662-5480</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="border-t border-brand-border py-6">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2 text-xs text-brand-textSec">
      <span>&copy; {year} INSPIMA. All rights reserved.</span>
      <span class="flex items-center gap-1">Built with <span class="text-red-500 inline-flex"><Icon name="heart" size={12} /></span> in Surabaya</span>
    </div>
  </div>
</footer>

<style>
  .footer-cta {
    background: #08245c;
    position: relative;
    overflow: hidden;
  }

  .footer-cta::before {
    content: "";
    position: absolute;
    width: 28rem;
    aspect-ratio: 1;
    right: -9rem;
    top: -14rem;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, rgb(69 184 239 / 0.62), rgb(21 68 230 / 0.3) 48%, transparent 72%);
    pointer-events: none;
  }

  .footer-cta > div {
    position: relative;
    z-index: 1;
  }
</style>
