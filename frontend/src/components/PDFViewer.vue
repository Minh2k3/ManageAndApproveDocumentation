<template>
  <div class="pdf-viewer-container">
    <div v-if="loading" class="loading-spinner">
      <div class="spinner"></div>
      <p>Đang tải PDF...</p>
    </div>
    
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
      <button @click="retryLoad" class="retry-btn">
        <i class="anticon anticon-reload"></i>
        Thử lại
      </button>
    </div>

    <div v-if="!loading && !error" class="pdf-controls">
      <button @click="previousPage" :disabled="pageNum <= 1" class="nav-btn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M15 18l-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Trang trước
      </button>
      
      <span class="page-info">
        Trang {{ pageNum }} / {{ totalPages }}
      </span>
      
      <button @click="nextPage" :disabled="pageNum >= totalPages" class="nav-btn">
        Trang sau
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M9 18l6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div class="zoom-controls">
        <button @click="zoomOut" :disabled="scale <= 0.5" class="zoom-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8" stroke-width="2"/>
            <path d="M21 21l-4.35-4.35M8 11h6" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
        
        <span class="zoom-info">{{ Math.round(scale * 100) }}%</span>
        
        <button @click="zoomIn" :disabled="scale >= 3" class="zoom-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8" stroke-width="2"/>
            <path d="M21 21l-4.35-4.35M11 8v6M8 11h6" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <div class="additional-controls">
        <button @click="fitToWidth" class="control-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
            <line x1="9" y1="9" x2="15" y2="15" stroke-width="2"/>
          </svg>
          Vừa khung
        </button>
        
        <button @click="downloadPdf" class="control-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke-width="2"/>
            <polyline points="7,10 12,15 17,10" stroke-width="2"/>
            <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"/>
          </svg>
          Tải xuống
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
import { message } from 'ant-design-vue'

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
      default: 1.2
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
    let currentPdfUrl = null

    const loadPdfJs = async () => {
      try {
        // Kiểm tra xem pdf.js đã được load chưa
        if (window.pdfjsLib) {
          pdfjsLib = window.pdfjsLib
          return
        }

        // Load pdf.js từ CDN
        const script = document.createElement('script')
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js'
        script.async = true
        
        await new Promise((resolve, reject) => {
          script.onload = () => {
            pdfjsLib = window.pdfjsLib
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js'
            resolve()
          }
          script.onerror = reject
          document.head.appendChild(script)
        })
      } catch (err) {
        console.error('Error loading PDF.js:', err)
        throw new Error('Không thể tải thư viện PDF')
      }
    }

    const buildPdfUrl = (url) => {
      if (!url) return null
      
      // Nếu đã là URL đầy đủ thì return luôn
      if (url.startsWith('http://') || url.startsWith('https://')) {
        return url
      }
      
      // Xử lý đường dẫn local
      const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000'
      
      // Nếu URL bắt đầu bằng storage/ thì giữ nguyên
      if (url.startsWith('storage/')) {
        return `${baseURL}/${url}`
      }
      
      // Nếu URL bắt đầu bằng public/ thì bỏ public/
      if (url.startsWith('public/')) {
        return `${baseURL}/${url.replace('public/', '')}`
      }
      
      // Nếu URL bắt đầu bằng documents/ thì thêm vào sau baseURL
      if (url.startsWith('documents/')) {
        return `${baseURL}/${url}`
      }
      
      // Mặc định thêm vào sau baseURL
      return `${baseURL}/${url}`
    }

    const renderPage = (num) => {
      if (!pdfDoc) return
      
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
          
          emit('pageRendered', { page: num, scale: scale.value })
        }).catch((err) => {
          console.error('Error rendering page:', err)
          pageRendering = false
        })
      }).catch((err) => {
        console.error('Error getting page:', err)
        pageRendering = false
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

    const fitToWidth = () => {
      const container = pdfContainer.value
      if (!container || !pdfDoc) return
      
      // Tính toán scale để fit width
      const containerWidth = container.clientWidth - 40 // padding
      scale.value = Math.min((containerWidth / 600), 2) // 600 là width mặc định của PDF
      queueRenderPage(pageNum.value)
    }

    const downloadPdf = () => {
      if (currentPdfUrl) {
        const link = document.createElement('a')
        link.href = currentPdfUrl
        link.download = `document_${Date.now()}.pdf`
        link.click()
        message.success('Đang tải xuống tài liệu...')
      }
    }

    const retryLoad = () => {
      loadPdf()
    }

    const loadPdf = async () => {
      try {
        loading.value = true
        error.value = null

        if (!pdfjsLib) {
          await loadPdfJs()
        }

        // Xây dựng URL cho PDF proxy
        const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000'
        const proxyUrl = `${baseURL}/api/pdf-proxy?file=${encodeURIComponent(props.pdfUrl)}`
        
        console.log('Loading PDF via proxy:', proxyUrl)
        console.log('Original PDF URL:', props.pdfUrl)

        const loadingTask = pdfjsLib.getDocument({
          url: proxyUrl,
          httpHeaders: {
            'Accept': 'application/pdf',
          },
          withCredentials: false
        })
        
        pdfDoc = await loadingTask.promise
        
        totalPages.value = pdfDoc.numPages
        pageNum.value = Math.min(pageNum.value, totalPages.value)
        
        renderPage(pageNum.value)
        
        loading.value = false
        message.success(`Đã tải thành công tài liệu ${totalPages.value} trang`)
        
      } catch (err) {
        console.error('Error loading PDF:', err)
        error.value = `Không thể tải file PDF: ${err.message || 'Lỗi không xác định'}`
        loading.value = false
        message.error('Không thể tải file PDF')
      }
    }

    onMounted(() => {
      if (props.pdfUrl) {
        loadPdf()
      }
    })

    watch(() => props.pdfUrl, (newUrl) => {
      if (newUrl) {
        pageNum.value = 1
        loadPdf()
      }
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
      zoomOut,
      fitToWidth,
      downloadPdf,
      retryLoad
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
  background: linear-gradient(135deg, #f8fcff 0%, #f0f8ff 100%);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
}

.loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 400px;
  color: #1890ff;
}

.spinner {
  border: 3px solid #e6f7ff;
  border-top: 3px solid #1890ff;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loading-spinner p {
  margin-top: 1rem;
  font-weight: 500;
}

.error-message {
  color: #ff4d4f;
  text-align: center;
  padding: 2rem;
  background: linear-gradient(135deg, #fff2f0 0%, #ffece6 100%);
  border-radius: 8px;
  margin: 1rem;
  border: 1px solid #ffccc7;
}

.retry-btn {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background: #ff4d4f;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.retry-btn:hover {
  background: #ff7875;
  transform: translateY(-1px);
}

.pdf-controls {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  padding: 1rem;
  background: linear-gradient(135deg, #ffffff 0%, #f8fcff 100%);
  border-bottom: 1px solid #e6f7ff;
  flex-wrap: wrap;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.nav-btn,
.zoom-btn,
.control-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border: 1px solid #d9d9d9;
  background: white;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.875rem;
  font-weight: 500;
  color: #262626;
}

.nav-btn:hover:not(:disabled),
.zoom-btn:hover:not(:disabled),
.control-btn:hover:not(:disabled) {
  background: #f0f8ff;
  border-color: #1890ff;
  color: #1890ff;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(24, 144, 255, 0.15);
}

.nav-btn:disabled,
.zoom-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.page-info {
  font-size: 0.875rem;
  color: #595959;
  min-width: 120px;
  text-align: center;
  font-weight: 600;
  padding: 0.5rem;
  background: #f0f8ff;
  border-radius: 6px;
  border: 1px solid #e6f7ff;
}

.zoom-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.25rem;
  background: #f8fcff;
  border-radius: 8px;
  border: 1px solid #e6f7ff;
}

.zoom-info {
  font-size: 0.875rem;
  color: #262626;
  min-width: 50px;
  text-align: center;
  font-weight: 600;
  padding: 0.25rem 0.5rem;
}

.additional-controls {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.pdf-canvas-container {
  flex: 1;
  overflow: auto;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 1.5rem;
  background: #f8fcff;
}

canvas {
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
  background: white;
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  border: 1px solid #e6f7ff;
  transition: transform 0.3s ease;
}

canvas:hover {
  transform: scale(1.02);
}

/* Custom scrollbar */
.pdf-canvas-container::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.pdf-canvas-container::-webkit-scrollbar-track {
  background: #f0f8ff;
  border-radius: 4px;
}

.pdf-canvas-container::-webkit-scrollbar-thumb {
  background: #1890ff;
  border-radius: 4px;
}

.pdf-canvas-container::-webkit-scrollbar-thumb:hover {
  background: #40a9ff;
}

/* Responsive */
@media (max-width: 768px) {
  .pdf-controls {
    gap: 0.5rem;
    padding: 0.75rem;
  }
  
  .nav-btn,
  .zoom-btn,
  .control-btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
  }
  
  .additional-controls {
    width: 100%;
    justify-content: center;
    margin-top: 0.5rem;
  }
  
  .pdf-canvas-container {
    padding: 1rem;
  }
}
</style>