<script>
  import { api } from '../lib/api.js';

  let form = { name: '', email: '', phone: '', service: '', message: '' };
  let sending = false;
  let sent = false;
  let formError = '';

  async function handleContact(e) {
    e.preventDefault();
    sending = true;
    formError = '';
    try {
      const res = await api.contact(form);
      sent = true;
      form = { name: '', email: '', phone: '', service: '', message: '' };
    } catch(err) {
      formError = 'Pesan gagal terkirim. Coba hubungi kami langsung via WhatsApp ya.';
    } finally {
      sending = false;
    }
  }
</script>

<!-- Hero -->
<section class="pt-32 pb-16 bg-brand-main border-b border-brand-border">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
    <span class="inline-block bg-brand-accent/10 border border-brand-accent/30 text-brand-accent px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-6">Kontak</span>
    <h1 class="hero-heading">
      Mari Bicara
    </h1>
    <p class="text-lg text-brand-textSec font-light leading-relaxed max-w-xl mx-auto">
      Ceritakan kebutuhan Anda — kami biasanya membalas dalam waktu kurang dari 2 jam.
    </p>
  </div>
</section>

<!-- Main Content -->
<section class="py-20 bg-brand-main">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">

      <!-- Form -->
      <div class="lg:col-span-3">
        <div class="bg-brand-container rounded-3xl p-8 md:p-10 border border-brand-border">
          {#if sent}
            <div class="text-center py-12">
              <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-5 border border-green-500/20">
                <i class="fa-solid fa-check text-3xl text-green-500"></i>
              </div>
              <h3 class="text-xl font-bold text-brand-textMain mb-2">Pesan Terkirim!</h3>
              <p class="text-brand-textSec font-light mb-6">Tim kami akan menghubungi Anda secepatnya.</p>
              <button on:click={() => sent = false}
                class="text-brand-accent text-sm font-semibold hover:underline bg-transparent border-0 cursor-pointer">
                Kirim pesan lain
              </button>
            </div>
          {:else}
            <h2 class="text-xl font-bold text-brand-textMain mb-7">Kirim Pesan</h2>
            <form on:submit={handleContact} class="space-y-5">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label for="c-name" class="block text-xs font-bold uppercase tracking-widest text-brand-textSec mb-2">Nama Lengkap *</label>
                  <input id="c-name" bind:value={form.name} type="text" placeholder="Nama Anda" required
                    class="w-full bg-brand-main border border-brand-border rounded-xl px-4 py-3 text-sm text-brand-textMain focus:outline-none focus:ring-2 focus:ring-brand-accent/40 transition-all placeholder:text-brand-textSec/40"/>
                </div>
                <div>
                  <label for="c-email" class="block text-xs font-bold uppercase tracking-widest text-brand-textSec mb-2">Email *</label>
                  <input id="c-email" bind:value={form.email} type="email" placeholder="email@bisnis.com" required
                    class="w-full bg-brand-main border border-brand-border rounded-xl px-4 py-3 text-sm text-brand-textMain focus:outline-none focus:ring-2 focus:ring-brand-accent/40 transition-all placeholder:text-brand-textSec/40"/>
                </div>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label for="c-phone" class="block text-xs font-bold uppercase tracking-widest text-brand-textSec mb-2">No. WhatsApp</label>
                  <input id="c-phone" bind:value={form.phone} type="tel" placeholder="+62 812 xxxx xxxx"
                    class="w-full bg-brand-main border border-brand-border rounded-xl px-4 py-3 text-sm text-brand-textMain focus:outline-none focus:ring-2 focus:ring-brand-accent/40 transition-all placeholder:text-brand-textSec/40"/>
                </div>
                <div>
                  <label for="c-service" class="block text-xs font-bold uppercase tracking-widest text-brand-textSec mb-2">Butuh Layanan Apa?</label>
                  <select id="c-service" bind:value={form.service}
                    class="w-full bg-brand-main border border-brand-border rounded-xl px-4 py-3 text-sm text-brand-textMain focus:outline-none focus:ring-2 focus:ring-brand-accent/40 transition-all">
                    <option value="">Pilih layanan...</option>
                    <option value="build">BUILD — Bikin Produk Baru</option>
                    <option value="rescue">RESCUE — Perbaiki yang Rusak</option>
                    <option value="boost">BOOST — Tingkatkan Performa</option>
                    <option value="konsultasi">Konsultasi</option>
                    <option value="other">Lainnya</option>
                  </select>
                </div>
              </div>
              <div>
                <label for="c-msg" class="block text-xs font-bold uppercase tracking-widest text-brand-textSec mb-2">Ceritakan Kebutuhan Anda *</label>
                <textarea id="c-msg" bind:value={form.message} rows="5"
                  placeholder="Jelaskan proyek atau masalah yang ingin Anda selesaikan. Semakin detail, semakin cepat kami bisa bantu." required
                  class="w-full bg-brand-main border border-brand-border rounded-xl px-4 py-3 text-sm text-brand-textMain focus:outline-none focus:ring-2 focus:ring-brand-accent/40 transition-all resize-none placeholder:text-brand-textSec/40"></textarea>
              </div>
              {#if formError}
                <p class="text-red-500 text-sm flex items-center gap-2">
                  <i class="fa-solid fa-circle-exclamation"></i> {formError}
                </p>
              {/if}
              <button type="submit" disabled={sending}
                class="w-full bg-brand-accent text-white py-4 rounded-xl font-bold text-sm shadow-lg shadow-brand-accent/20 hover:-translate-y-0.5 transition-all disabled:opacity-60 disabled:cursor-not-allowed border-0 cursor-pointer">
                {#if sending}
                  <i class="fa-solid fa-spinner animate-spin mr-2"></i>Mengirim...
                {:else}
                  <i class="fa-solid fa-paper-plane mr-2"></i>Kirim Pesan
                {/if}
              </button>
            </form>
          {/if}
        </div>
      </div>

      <!-- Contact Info -->
      <div class="lg:col-span-2 space-y-4">

        <!-- Info cards -->
        <div class="bg-brand-container rounded-3xl p-8 border border-brand-border">
          <h3 class="font-bold text-brand-textMain mb-6 text-sm uppercase tracking-widest">Info Kontak</h3>
          <ul class="space-y-5">
            <li class="flex items-start gap-4">
              <div class="w-9 h-9 rounded-xl bg-brand-accent/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fa-solid fa-location-dot text-brand-accent text-sm"></i>
              </div>
              <div>
                <div class="text-xs text-brand-textSec font-semibold uppercase tracking-wider mb-0.5">Alamat</div>
                <div class="text-sm text-brand-textMain font-medium">Surabaya, Jawa Timur</div>
                <div class="text-xs text-brand-textSec mb-2">Indonesia</div>
                <a href="https://maps.app.goo.gl/q2XFHadifQ3dmhyf9?g_st=atm" target="_blank" rel="noopener" 
                   class="text-[10px] font-bold text-brand-accent hover:underline flex items-center gap-1">
                   <i class="fa-solid fa-diamond-turn-right text-[9px]"></i> Petunjuk Arah
                </a>
              </div>
            </li>
            <li class="flex items-start gap-4">
              <div class="w-9 h-9 rounded-xl bg-brand-accent/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fa-solid fa-envelope text-brand-accent text-sm"></i>
              </div>
              <div>
                <div class="text-xs text-brand-textSec font-semibold uppercase tracking-wider mb-0.5">Email</div>
                <a href="mailto:hello@inspima.id" class="text-sm text-brand-textMain font-medium hover:text-brand-accent transition-colors">hello@inspima.id</a>
              </div>
            </li>
            <li class="flex items-start gap-4">
              <div class="w-9 h-9 rounded-xl bg-green-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fa-brands fa-whatsapp text-green-500 text-sm"></i>
              </div>
              <div>
                <div class="text-xs text-brand-textSec font-semibold uppercase tracking-wider mb-0.5">WhatsApp</div>
                <a href="https://wa.me/6285156625480" target="_blank" rel="noopener" class="text-sm text-brand-textMain font-medium hover:text-brand-accent transition-colors">+62 851-5662-5480</a>
              </div>
            </li>
            <li class="flex items-start gap-4">
              <div class="w-9 h-9 rounded-xl bg-brand-accent/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fa-solid fa-clock text-brand-accent text-sm"></i>
              </div>
              <div>
                <div class="text-xs text-brand-textSec font-semibold uppercase tracking-wider mb-0.5">Jam Kerja</div>
                <div class="text-sm text-brand-textMain font-medium">Senin – Jumat</div>
                <div class="text-xs text-brand-textSec">09.00 – 18.00 WIB</div>
              </div>
            </li>
          </ul>
        </div>

        <!-- WhatsApp CTA -->
        <div class="bg-green-500 rounded-3xl p-6 text-white">
          <div class="flex items-center gap-3 mb-4">
            <i class="fa-brands fa-whatsapp text-2xl"></i>
            <div>
              <div class="font-bold text-sm">Chat Langsung via WhatsApp</div>
              <div class="text-green-100 text-xs">Biasanya dibalas dalam 1 jam</div>
            </div>
          </div>
          <a href="https://wa.me/6285156625480?text=Halo%20INSPIMA%2C%20saya%20ingin%20konsultasi." target="_blank" rel="noopener"
            class="block w-full text-center bg-white text-green-600 py-3 rounded-xl font-black text-sm hover:-translate-y-0.5 transition-all shadow-lg shadow-green-700/20">
            Mulai Chat Sekarang
          </a>
        </div>

        <!-- Response guarantee -->
        <div class="bg-brand-container rounded-2xl px-5 py-4 border border-brand-border flex items-center gap-3">
          <i class="fa-solid fa-bolt text-brand-accent text-lg flex-shrink-0"></i>
          <p class="text-xs text-brand-textSec leading-relaxed">
            Kami berkomitmen membalas setiap pesan dalam <span class="font-bold text-brand-textMain">waktu kurang dari 2 jam</span> di hari kerja.
          </p>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- Services quick links -->
<section class="py-16 bg-brand-container/50 border-t border-brand-border">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <p class="text-sm text-brand-textSec font-light mb-8">Belum tahu butuh layanan apa? Pelajari dulu pilihan kami.</p>
    <div class="flex flex-wrap justify-center gap-3">
      <a href="/build"  class="inline-flex items-center gap-2 bg-brand-container border border-brand-border text-brand-textMain px-5 py-2.5 rounded-full text-sm font-bold hover:border-brand-accent/50 hover:text-brand-accent transition-all">
        <i class="fa-solid fa-hammer text-brand-accent text-xs"></i> BUILD
      </a>
      <a href="/rescue" class="inline-flex items-center gap-2 bg-brand-container border border-brand-border text-brand-textMain px-5 py-2.5 rounded-full text-sm font-bold hover:border-teal-500/50 hover:text-teal-500 transition-all">
        <i class="fa-solid fa-life-ring text-teal-500 text-xs"></i> RESCUE
      </a>
      <a href="/boost"  class="inline-flex items-center gap-2 bg-brand-container border border-brand-border text-brand-textMain px-5 py-2.5 rounded-full text-sm font-bold hover:border-orange-400/50 hover:text-orange-400 transition-all">
        <i class="fa-solid fa-rocket text-orange-400 text-xs"></i> BOOST
      </a>
      <a href="/projects" class="inline-flex items-center gap-2 bg-brand-container border border-brand-border text-brand-textMain px-5 py-2.5 rounded-full text-sm font-bold hover:border-brand-accent/50 hover:text-brand-accent transition-all">
        <i class="fa-solid fa-briefcase text-brand-accent text-xs"></i> Lihat Portfolio
      </a>
    </div>
  </div>
</section>
