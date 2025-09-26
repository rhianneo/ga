import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      // Resolve frappe-gantt to its ES module
      'frappe-gantt': path.resolve(__dirname, 'node_modules/frappe-gantt/dist/frappe-gantt.es.js'),
      '@': path.resolve(__dirname, 'resources/js'),  // Alias '@' to 'resources/js'
    },
  },
  build: {
    outDir: 'public/build',
    manifest: true,
    rollupOptions: {
      input: 'resources/js/app.js',
    },
  },
  server: {
    hmr: {
      protocol: 'ws',
      host: 'localhost',
    },
  },
});
