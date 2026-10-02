<script>
  import { onMount } from 'svelte';
  import { api } from '../lib/api.js';
  import ServicePage from '../components/ServicePage.svelte';

  let data = null;
  const config = {
    "name": "boost",
    "heading": "Skalakan Bisnis Anda",
    "accent": "Tanpa Batas",
    "color": "orange",
    "primary": "Mulai BOOST",
    "secondary": "Lihat Case Study",
    "panel": "Fondasi teknologi untuk pertumbuhan",
    "note": "Cloud · Otomatisasi · Performa",
    "section": "Layanan BOOST",
    "sub": "Dari cloud migration hingga CI/CD pipeline, kami handle semua aspek teknis agar tim Anda fokus pada bisnis.",
    "steps": [
      [
        "Audit & pemetaan",
        "Menemukan bottleneck dan peluang optimasi infrastruktur."
      ],
      [
        "Optimasi & otomatisasi",
        "Meningkatkan performa dan menyederhanakan deployment."
      ],
      [
        "Monitoring & scale",
        "Menyiapkan visibilitas sistem dan kapasitas untuk bertumbuh."
      ]
    ],
    "description": "Infrastruktur yang skalabel, deployment otomatis, dan monitoring proaktif. Kami optimalkan tumpukan teknologi Anda sehingga bisnis bisa bertumbuh tanpa hambatan teknis.",
    "primaryHref": "/contact",
    "secondaryHref": "/projects"
  };
  const services = [
    { icon: 'server',   title: 'Cloud Migration',         desc: 'Migrasi aman ke AWS, GCP, atau Azure dengan zero downtime dan arsitektur cloud-native yang efisien.' },
    { icon: 'gears', title: 'CI/CD Pipeline',          desc: 'Otomatisasi deployment dengan GitHub Actions, GitLab CI, atau Jenkins — dari commit ke production dalam menit.' },
    { icon: 'chart',   title: 'Performance Optimization', desc: 'Analisis bottleneck, optimasi query database, caching strategy, dan CDN setup untuk aplikasi super cepat.' },
    { icon: 'shield-check',  title: 'Security Hardening',      desc: 'Audit keamanan menyeluruh, implementasi WAF, enkripsi data, dan penetration testing untuk proteksi optimal.' },
    { icon: 'eye', title: 'Monitoring & Alerting',   desc: 'Setup Grafana, Prometheus, atau Datadog untuk visibilitas penuh sistem dan respons insiden yang cepat.' },
    { icon: 'scale',   title: 'Auto Scaling',            desc: 'Arsitektur yang otomatis scale up saat traffic tinggi dan scale down saat idle — bayar sesuai kebutuhan.' },
  ];
  const metrics = [
    { v: '40%', l: 'Pengurangan Biaya Infrastruktur' },
    { v: '99.9%', l: 'Uptime Guarantee' },
    { v: '10x', l: 'Kecepatan Deploy' },
  ];
  const faqs = [];
  const testimonial = {"quote": "Setelah BOOST oleh INSPIMA, biaya server kami turun 45% dan deployment time berkurang dari 2 jam menjadi 8 menit. ROI-nya luar biasa dalam 3 bulan pertama.", "name": "Budi Santoso", "role": "CEO, DataKu SaaS"};

  onMount(async () => {
    try { data = await api.pilar(config.name); } catch (e) {}
  });
</script>

<ServicePage {config} {services} {metrics} {faqs} {testimonial} projects={data?.projects || []} />
