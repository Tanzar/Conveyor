import { dirname, resolve } from 'node:path'
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const __dirname = dirname(fileURLToPath(import.meta.url))

export default defineConfig({
  plugins: [
    laravel([
        './resources/assets/js/index.js'
    ]),
  ],
  build: {
    lib: {
      entry: resolve(__dirname, 'resources/assets/js/index.js'),
      name: 'Conveyor',
      fileName: 'conveyor-js',
    },
    outDir: './resources/dist',
    emptyOutDir: true,
    rollupOptions: {
      external: ['laravel-echo', '@laravel/echo-vue', 'axios'],
      output: {
        globals: {
          "laravel-echo": 'laravel-echo',
          "@laravel/echo-vue": '@laravel/echo-vue',
          "axios": 'axios'
        },
      },
    },
  }
});