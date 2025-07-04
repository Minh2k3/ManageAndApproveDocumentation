import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  base: '/',
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  // optimizeDeps: {
  //   include: [
  //     'pdfjs-dist/build/pdf',
  //     'pdfjs-dist/build/pdf.worker.min.js'
  //   ],
  // },
  // build: {
  //   rollupOptions: {
  //     output: {
  //       manualChunks: {
  //         pdfjs: [
  //           'pdfjs-dist/build/pdf',
  //           'pdfjs-dist/build/pdf.worker.min.js',
  //         ]
  //       }
  //     }
  //   }
  // },
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000', // URL backend của bạn
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path.replace(/^\/api/, '/api'), // Tùy chọn: giữ nguyên đường dẫn
      },
      '/sanctum': {
        target: 'http://localhost:8000', // URL backend của bạn
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path.replace(/^\/sanctum/, '/sanctum'), // Tùy chọn: giữ nguyên đường dẫn
      },
      '/documents': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: false,
      }
    },
    allowedHosts: [
      'd65e-116-106-97-2.ngrok-free.app', // Thêm domain ngrok của bạn vào đây
    ],
  },
});