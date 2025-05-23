<template>
  <div class="pdf-viewer-container">
    <div v-if="loading" class="loading-spinner">
      <div class="spinner"></div>
      <p>Đang tải PDF...</p>
    </div>
    
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <div v-if="!loading && !error" class="pdf-controls">
      <button @click="previousPage" :disabled="pageNum <= 1">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M15 18l-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Trang trước
      </button>
      
      <span class="page-info">
        Trang {{ pageNum }} / {{ totalPages }}
      </span>
      
      <button @click="nextPage" :disabled="pageNum >= totalPages">
        Trang sau
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M9 18l6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div class="zoom-controls">
        <button @click="zoomOut" :disabled="scale <= 0.5">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8" stroke-width="2"/>
            <path d="M21 21l-4.35-4.35M8 11h6" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
        
        <span class="zoom-info">{{ Math.round(scale * 100) }}%</span>
        
        <button @click="zoomIn" :disabled="scale >= 3">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8" stroke-width="2"/>
            <path d="M21 21l-4.35-4.35M11 8v6M8 11h6" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="pdf-canvas-container" ref="pdfContainer">
      <canvas ref="pdfCanvas"></canvas>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch, onUnmounted } from 'vue'

export default {
  name: 'PDFViewer',
  props: {
    pdfUrl: {
      type: String,
      required: true
    },
    initialPage: {
      type: Number,
      default: 1
    },
    initialScale: {
      type: Number,
      default: 1.5
    }
  },
  setup(props, { emit }) {
    const pdfCanvas = ref(null)
    const pdfContainer = ref(null)
    const loading = ref(true)
    const error = ref(null)
    const pageNum = ref(props.initialPage)
    const totalPages = ref(0)
    const scale = ref(props.initialScale)
    
    let pdfDoc = null
    let pageRendering = false
    let pageNumPending = null
    let pdfjsLib = null

    const loadPdfJs = async () => {
      // Tạo script tag để load pdf.js
      const script = document.createElement('script')
      script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js'
      script.async = true
      
      return new Promise((resolve, reject) => {
        script.onload = () => {
          pdfjsLib = window.pdfjsLib
          pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js'
          resolve()
        }
        script.onerror = reject
        document.head.appendChild(script)
      })
    }

    const renderPage = (num) => {
      pageRendering = true
      
      pdfDoc.getPage(num).then((page) => {
        const viewport = page.getViewport({ scale: scale.value })
        const canvas = pdfCanvas.value
        const context = canvas.getContext('2d')
        
        canvas.height = viewport.height
        canvas.width = viewport.width

        const renderContext = {
          canvasContext: context,
          viewport: viewport
        }
        
        const renderTask = page.render(renderContext)
        
        renderTask.promise.then(() => {
          pageRendering = false
          
          if (pageNumPending !== null) {
            renderPage(pageNumPending)
            pageNumPending = null
          }
        })
      })
      
      emit('pageChange', num)
    }

    const queueRenderPage = (num) => {
      if (pageRendering) {
        pageNumPending = num
      } else {
        renderPage(num)
      }
    }

    const previousPage = () => {
      if (pageNum.value <= 1) return
      pageNum.value--
      queueRenderPage(pageNum.value)
    }

    const nextPage = () => {
      if (pageNum.value >= totalPages.value) return
      pageNum.value++
      queueRenderPage(pageNum.value)
    }

    const zoomIn = () => {
      if (scale.value >= 3) return
      scale.value = Math.min(scale.value + 0.25, 3)
      queueRenderPage(pageNum.value)
    }

    const zoomOut = () => {
      if (scale.value <= 0.5) return
      scale.value = Math.max(scale.value - 0.25, 0.5)
      queueRenderPage(pageNum.value)
    }

    const loadPdf = async () => {
      try {
        loading.value = true
        error.value = null

        if (!pdfjsLib) {
          await loadPdfJs()
        }

        // Proxy URL để tránh CORS - sử dụng URL đầy đủ của Laravel
        const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000'
        const proxyUrl = `${baseURL}/api/pdf-proxy?file=${encodeURIComponent(props.pdfUrl)}`
        
        const loadingTask = pdfjsLib.getDocument(proxyUrl)
        pdfDoc = await loadingTask.promise
        
        totalPages.value = pdfDoc.numPages
        renderPage(pageNum.value)
        
        loading.value = false
      } catch (err) {
        console.error('Error loading PDF:', err)
        error.value = 'Không thể tải file PDF. Vui lòng thử lại.'
        loading.value = false
      }
    }

    onMounted(() => {
      loadPdf()
    })

    watch(() => props.pdfUrl, () => {
      pageNum.value = 1
      loadPdf()
    })

    onUnmounted(() => {
      if (pdfDoc) {
        pdfDoc.destroy()
      }
    })

    return {
      pdfCanvas,
      pdfContainer,
      loading,
      error,
      pageNum,
      totalPages,
      scale,
      previousPage,
      nextPage,
      zoomIn,
      zoomOut
    }
  }
}
</script>

<style scoped>
.pdf-viewer-container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  background-color: #f5f5f5;
  position: relative;
}

.loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 400px;
}

.spinner {
  border: 3px solid #f3f3f3;
  border-top: 3px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-message {
  color: #d32f2f;
  text-align: center;
  padding: 20px;
  background-color: #ffebee;
  border-radius: 4px;
  margin: 20px;
}

.pdf-controls {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  padding: 16px;
  background-color: #fff;
  border-bottom: 1px solid #e0e0e0;
  flex-wrap: wrap;
}

.pdf-controls button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border: 1px solid #e0e0e0;
  background-color: #fff;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 14px;
}

.pdf-controls button:hover:not(:disabled) {
  background-color: #f5f5f5;
  border-color: #bdbdbd;
}

.pdf-controls button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  font-size: 14px;
  color: #424242;
  min-width: 120px;
  text-align: center;
}

.zoom-controls {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-left: 20px;
}

.zoom-info {
  font-size: 14px;
  color: #424242;
  min-width: 50px;
  text-align: center;
}

.pdf-canvas-container {
  flex: 1;
  overflow: auto;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 20px;
}

canvas {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  background-color: #fff;
  max-width: 100%;
  height: auto;
}
</style>