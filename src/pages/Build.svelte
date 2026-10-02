<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import ServicePage from '../components/ServicePage.svelte';

  let data = null;
  const config = {
    "name": "build",
    "heading": "Bangun Produk Digital",
    "accent": "dari Nol hingga Sempurna",
    "color": "blue",
    "primary": "Mulai Proyek",
    "secondary": "Lihat Portfolio",
    "panel": "Dari ide ke produk yang siap digunakan",
    "note": "Web app · Mobile app · Sistem enterprise",
    "section": "Mengapa Memilih BUILD?",
    "sub": "Kami bukan sekadar vendor, kami adalah mitra teknologi jangka panjang Anda.",
    "steps": [
      [
        "Discovery & perencanaan",
        "Memahami kebutuhan bisnis dan menentukan arah produk."
      ],
      [
        "Desain & pengembangan",
        "Merancang pengalaman pengguna dan membangun fondasi teknologi."
      ],
      [
        "Peluncuran & dukungan",
        "Menyiapkan produk untuk digunakan dan mendampingi setelah rilis."
      ]
    ],
    "description": "Kami merancang, mengembangkan, dan meluncurkan produk digital berkualitas tinggi — web app, mobile app, hingga sistem enterprise — dengan kode bersih dan arsitektur yang skalabel.",
    "primaryHref": "/contact",
    "secondaryHref": "/projects"
  };
  const services = [
    { icon: 'shield',   title: 'Kode Berkualitas', desc: 'Clean code, test coverage, dan dokumentasi lengkap. Setiap baris kode ditulis dengan standar industri terbaik.' },
    { icon: 'gauge',   title: 'Performa Optimal', desc: 'Aplikasi yang responsif, load cepat, dan scalable. Kami optimalkan dari database query hingga frontend rendering.' },
    { icon: 'headset', title: 'Support Penuh',    desc: 'Garansi 3 bulan dan dedicated support post-launch. Kami tidak meninggalkan Anda setelah proyek selesai.' },
  ];
  const metrics = [];
  const faqs = [
    { q: 'Berapa lama waktu pengembangan produk?', a: 'Tergantung kompleksitas. MVP sederhana bisa selesai dalam 4–8 minggu. Produk enterprise biasanya 3–6 bulan.' },
    { q: 'Teknologi apa yang digunakan?', a: 'Kami menggunakan Laravel, React, Next.js, Flutter, Node.js, dan teknologi modern lainnya disesuaikan kebutuhan bisnis Anda.' },
    { q: 'Apakah ada garansi setelah selesai?', a: 'Ya, kami memberikan garansi bug-fix 3 bulan setelah serah terima produk tanpa biaya tambahan.' },
    { q: 'Bagaimana proses pembayarannya?', a: 'Sistem pembayaran bertahap: DP 30% di awal, 40% saat milestone pertama selesai, dan 30% saat serah terima akhir.' },
  ];
  const testimonial = null;

  onMount(async () => {
    try { data = await api.pilar(config.name); } catch (e) {}
  });
</script>

<ServicePage {config} {services} {metrics} {faqs} {testimonial} projects={data?.projects || []} />
