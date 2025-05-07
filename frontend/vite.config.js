import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000', // URL backend của bạn
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '/api'), // Tùy chọn: giữ nguyên đường dẫn
      },
      '/sanctum': {
        target: 'http://localhost:8000', // URL backend của bạn
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/sanctum/, '/sanctum'), // Tùy chọn: giữ nguyên đường dẫn
      },
    },
  },
});