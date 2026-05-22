import { defineConfig, loadEnv } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';

function googleAnalyticsPlugin(googleAnalyticsId) {
  return {
    name: 'inject-google-analytics',
    apply: 'build',
    transformIndexHtml(html) {
      if (!googleAnalyticsId) return html;

      const encodedId = encodeURIComponent(googleAnalyticsId);
      const tagId = JSON.stringify(googleAnalyticsId);
      const googleAnalyticsTag = `  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=${encodedId}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', ${tagId});
  </script>`;

      return html.replace('</head>', `${googleAnalyticsTag}\n</head>`);
    },
  };
}

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');
  const googleAnalyticsId = env.GOOGLE_ANALYTICS_ID
    || env.VITE_GOOGLE_ANALYTICS_ID
    || env.VITE_FIREBASE_MEASUREMENT_ID
    || '';

  return {
    plugins: [svelte(), googleAnalyticsPlugin(googleAnalyticsId)],
    base: '/dist/',
    build: {
      outDir: 'dist',
      emptyOutDir: true,
      rollupOptions: {
        input: 'vite.html',
      },
    },
    server: {
      proxy: {
        '/api.php': {
          target: 'http://inspima.test',
          changeOrigin: true,
        },
        '/assets/images': {
          target: 'http://inspima.test',
          changeOrigin: true,
        },
      },
    },
  };
});
