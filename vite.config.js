import { defineConfig } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
  plugins: [svelte()],
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
});
