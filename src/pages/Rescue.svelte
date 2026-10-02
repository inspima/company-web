<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import ServicePage from '../components/ServicePage.svelte';

  let data = null;
  const config = {
    "name": "rescue",
    "heading": "Selamatkan Sistem Anda",
    "accent": "Sebelum Terlambat",
    "color": "teal",
    "primary": "Emergency RESCUE",
    "secondary": "Konsultasi",
    "panel": "Pulihkan sistem. Kembalikan kendali.",
    "note": "Bug kritis · Recovery · Audit keamanan",
    "section": "Respons Cepat, Hasil Nyata",
    "sub": "Layanan RESCUE dirancang untuk situasi kritis — setiap menit downtime berharga.",
    "steps": [
      [
        "Diagnosis masalah",
        "Menelusuri sumber gangguan dan menentukan prioritas perbaikan."
      ],
      [
        "Perbaikan & pemulihan",
        "Menangani masalah kritis agar sistem kembali berjalan."
      ],
      [
        "Pencegahan berulang",
        "Memperkuat fondasi sistem dan mendokumentasikan perbaikan."
      ]
    ],
    "description": "Sistem down? Aplikasi crash? Bug kritis merusak reputasi bisnis? Tim RESCUE kami hadir dalam hitungan jam — mendiagnosis, memperbaiki, dan memulihkan sistem Anda ke kondisi optimal.",
    "primaryHref": "https://wa.me/6285156625480?text=RESCUE%20-%20Butuh%20bantuan%20darurat!",
    "secondaryHref": "/contact"
  };
  const services = [
    { icon: 'bug',   title: 'Bug Critical Fix',   desc: 'Identifikasi dan perbaikan bug kritis yang menyebabkan downtime atau data corruption dalam tempo singkat.' },
    { icon: 'server',   title: 'Server Recovery',    desc: 'Pemulihan server yang crash, database corrupt, atau konfigurasi yang salah setelah update.' },
    { icon: 'shield-virus', title: 'Security Incident',  desc: 'Penanganan serangan siber, injeksi malware, dan pemulihan keamanan sistem dengan audit menyeluruh.' },
    { icon: 'merge', title: 'Code Refactoring',   desc: 'Restrukturisasi kode warisan (legacy) yang tidak terkelola menjadi arsitektur bersih dan maintainable.' },
    { icon: 'gauge',   title: 'Performance Fix',    desc: 'Analisis dan optimasi performa — dari query database lambat hingga bottleneck pada level infrastruktur.' },
    { icon: 'people',  title: 'Team Takeover',      desc: 'Ambil alih proyek yang ditinggal developer sebelumnya, dengan audit kode dan rencana recovery terstruktur.' },
  ];
  const metrics = [
    { v: '< 2 Jam', l: 'Waktu Respons' },
    { v: '98%', l: 'Tingkat Keberhasilan' },
    { v: '24/7', l: 'On-Call Support' },
    { v: '500+', l: 'Sistem Diselamatkan' },
  ];
  const faqs = [];
  const testimonial = {"quote": "Sistem kami down di hari paling kritis — sehari sebelum pitch ke investor. Tim RESCUE INSPIMA merespons dalam 45 menit dan sistem kembali online dalam 3 jam. Luar biasa!", "name": "Siti Rahayu", "role": "CTO, FinTech Nusantara"};

  onMount(async () => {
    try { data = await api.pilar(config.name); } catch (e) {}
  });
</script>

<ServicePage {config} {services} {metrics} {faqs} {testimonial} projects={data?.projects || []} />
