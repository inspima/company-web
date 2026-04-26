<script>
  import { goToSection } from "../lib/nav.js";
  const year = new Date().getFullYear();
</script>

<footer class="bg-brand-container border-t border-brand-border">
  <!-- Top CTA -->
  <div class="bg-brand-accent/5 border-b border-brand-border py-12">
    <div class="max-w-5xl mx-auto px-4 text-center">
      <h3 class="text-2xl md:text-3xl font-black text-brand-textMain mb-4">
        Siap Memulai Proyek Anda?
      </h3>
      <p class="text-brand-textSec mb-6 font-light">
        Hubungi tim kami dan dapatkan konsultasi gratis hari ini.
      </p>
      <button
        on:click={() => goToSection("contact")}
        class="inline-flex items-center gap-2 bg-brand-accent text-white px-8 py-3.5 rounded-full font-bold text-sm shadow-lg shadow-brand-accent/30 hover:-translate-y-1 transition-all border-0 cursor-pointer"
      >
        <i class="fa-solid fa-rocket"></i> Mulai Sekarang
      </button>
    </div>
  </div>

  <!-- Main Footer -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
      <!-- Brand -->
      <div class="md:col-span-1">
        <a href="#/" class="inline-flex items-center gap-2 mb-4">
          <img
            src="/assets/images/logo.png"
            alt="Inspima"
            class="h-10 w-auto"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
          />
          <span class="hidden font-heading font-black text-xl text-brand-accent"
            >INSPIMA</span
          >
        </a>
        <p class="text-brand-textSec text-sm font-light leading-relaxed mt-4">
          Solusi teknologi digital terpercaya untuk bisnis Anda. Kami hadir
          untuk Build, Rescue, dan Boost produk digital Anda.
        </p>
        <div class="flex gap-3 mt-6">
          <a
            href="https://www.linkedin.com/company/inspima"
            target="_blank"
            rel="noopener"
            class="w-9 h-9 flex items-center justify-center rounded-full bg-brand-main border border-brand-border text-brand-textSec hover:text-white hover:bg-[#0077b5] hover:border-[#0077b5] transition-all"
            title="LinkedIn"
          >
            <i class="fa-brands fa-linkedin-in text-sm"></i>
          </a>
          <a
            href="https://x.com/inspima"
            target="_blank"
            rel="noopener"
            class="w-9 h-9 flex items-center justify-center rounded-full bg-brand-main border border-brand-border text-brand-textSec hover:text-white hover:bg-[#1da1f2] hover:border-[#1da1f2] transition-all"
            title="Twitter / X"
          >
            <i class="fa-brands fa-x-twitter text-sm"></i>
          </a>
          <a
            href="https://www.instagram.com/inspima"
            target="_blank"
            rel="noopener"
            class="w-9 h-9 flex items-center justify-center rounded-full bg-brand-main border border-brand-border text-brand-textSec hover:text-white hover:bg-[#e1306c] hover:border-[#e1306c] transition-all"
            title="Instagram"
          >
            <i class="fa-brands fa-instagram text-sm"></i>
          </a>
        </div>
      </div>

      <!-- Services -->
      <div>
        <h4
          class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5"
        >
          Layanan
        </h4>
        <ul class="space-y-3 text-sm text-brand-textSec font-light">
          <li>
            <a
              href="#/build"
              class="hover:text-brand-accent transition-colors flex items-center gap-2"
              ><i class="fa-solid fa-hammer w-4 text-brand-accent/60"
              ></i>BUILD</a
            >
          </li>
          <li>
            <a
              href="#/rescue"
              class="hover:text-brand-accent transition-colors flex items-center gap-2"
              ><i class="fa-solid fa-life-ring w-4 text-brand-accent/60"
              ></i>RESCUE</a
            >
          </li>
          <li>
            <a
              href="#/boost"
              class="hover:text-brand-accent transition-colors flex items-center gap-2"
              ><i class="fa-solid fa-rocket w-4 text-brand-accent/60"
              ></i>BOOST</a
            >
          </li>
          <li>
            <a
              href="#/projects"
              class="hover:text-brand-accent transition-colors flex items-center gap-2"
              ><i class="fa-solid fa-briefcase w-4 text-brand-accent/60"
              ></i>Portfolio</a
            >
          </li>
          <li>
            <a
              href="#/blog"
              class="hover:text-brand-accent transition-colors flex items-center gap-2"
              ><i class="fa-solid fa-newspaper w-4 text-brand-accent/60"
              ></i>Insights</a
            >
          </li>
        </ul>
      </div>

      <!-- Company -->
      <div>
        <h4
          class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5"
        >
          Perusahaan
        </h4>
        <ul class="space-y-3 text-sm text-brand-textSec font-light">
          <li>
            <a href="#/about" class="hover:text-brand-accent transition-colors"
              >Tentang Kami</a
            >
          </li>
          <li>
            <button
              on:click={() => goToSection("services")}
              class="hover:text-brand-accent transition-colors bg-transparent border-0 p-0 cursor-pointer text-sm font-light text-brand-textSec"
              >Layanan</button
            >
          </li>
          <li>
            <a
              href="#/contact"
              class="hover:text-brand-accent transition-colors">Kontak</a
            >
          </li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h4
          class="font-bold text-brand-textMain text-sm uppercase tracking-widest mb-5"
        >
          Kontak
        </h4>
        <ul class="space-y-4 text-sm text-brand-textSec font-light">
          <li class="flex items-start gap-3">
            <i
              class="fa-solid fa-location-dot text-brand-accent mt-0.5 w-4 flex-shrink-0"
            ></i>
            <span>Surabaya, Jawa TImur, Indonesia</span>
          </li>
          <li class="flex items-center gap-3">
            <i class="fa-solid fa-envelope text-brand-accent w-4 flex-shrink-0"
            ></i>
            <a
              href="mailto:hello@inspima.id"
              class="hover:text-brand-accent transition-colors"
              >hello@inspima.id</a
            >
          </li>
          <li class="flex items-center gap-3">
            <i class="fa-brands fa-whatsapp text-brand-accent w-4 flex-shrink-0"
            ></i>
            <a
              href="https://wa.me/6285156625480"
              target="_blank"
              rel="noopener"
              class="hover:text-brand-accent transition-colors"
              >+62 851-5662-5480</a
            >
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="border-t border-brand-border py-6">
    <div
      class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2 text-xs text-brand-textSec"
    >
      <span>&copy; {year} INSPIMA. All rights reserved.</span>
      <span
        >Built with <i class="fa-solid fa-heart text-red-500 mx-0.5"></i> in Surabaya</span
      >
    </div>
  </div>
</footer>
