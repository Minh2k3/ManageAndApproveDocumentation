<template>
  <div class="document-lookup-container">
    <!-- Header theo mẫu dashboard -->
    <header class="bg-header text-white py-4" :class="{ 'header-hidden': isHeaderHidden }">
      <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <img src="@/assets/images/logo_tlu.png" alt="Logo ĐH Thủy Lợi" class="me-2"
                    style="height: 40px; width: 40px;" />
          <span class="fs-4 mb-0 fw-bold">ĐH THỦY LỢI</span>
        </div>
        <nav class="d-none d-md-flex">
          <router-link to="/" class="text-white mx-3 text-decoration-none fw-medium">
            <span class="fw-bold text-uppercase fs-3">tra cứu chứng chỉ</span>
          </router-link>
        </nav>
        <div class="gap-2 d-flex align-items-center">
          <router-link to="/">
            <a-button type="primary" ghost class="bg-white text-primary btn-link">Trang chủ</a-button>
          </router-link>
          <router-link to="/login">
            <a-button type="primary" ghost class="bg-white text-primary btn-link">Đăng nhập</a-button>
          </router-link>
        </div>
      </div>
    </header>

    <!-- Vùng cảm ứng để hiện header khi di chuột lên trên -->
    <div class="header-trigger" @mouseenter="showHeader"></div>

    <!-- Search Section -->
    <div class="search-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-md-10">
            <a-card class="search-card" :bordered="false">
              <div class="search-form">
                <a-form @submit="handleSearch" layout="vertical">
                  <a-form-item label="Mã tài liệu" class="mb-3">
                    <a-input-search
                      v-model:value="searchCode"
                      placeholder="Nhập mã tài liệu cần tra cứu (VD: 20-1751203066)..."
                      size="large"
                      enter-button="Tìm kiếm"
                      :loading="loading"
                      @search="handleSearch"
                      class="search-input"
                    >
                      <template #enterButton>
                        <a-button type="primary" :loading="loading">
                          <i class="bi bi-search me-1"></i>
                          Tìm kiếm
                        </a-button>
                      </template>
                    </a-input-search>
                  </a-form-item>
                </a-form>
              </div>
            </a-card>
          </div>
        </div>
      </div>
    </div>

    <!-- Document Display Section -->
    <div class="document-section" v-if="certificateStore.certificates">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <a-card class="document-card">
              <template #title>
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <div class="document-actions">
                    <a-tooltip title="Tải xuống tài liệu">
                      <a-button 
                        type="primary" 
                        shape="circle" 
                        size="large"
                        @click="downloadDocument"
                        class="action-btn download-btn me-2"
                      >
                        <i class="bi bi-download"></i>
                      </a-button>
                    </a-tooltip>
                    <a-tooltip title="In tài liệu">
                      <a-button 
                        shape="circle" 
                        size="large"
                        @click="printDocument"
                        class="action-btn print-btn"
                      >
                        <i class="bi bi-printer"></i>
                      </a-button>
                    </a-tooltip>
                  </div>
                </div>
              </template>

              <!-- PDF Viewer -->
              <div class="pdf-section">
                <div class="pdf-header">
                  <h5 class="section-title">
                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    Nội dung tài liệu
                  </h5>
                </div>
                <div class="pdf-viewer-container" ref="pdfContainer">
                  <PDFViewer 
                    :key="pdfViewerKey"
                    :pdf-url="pdfUrl" 
                    @pdf-loaded="onPdfLoaded"
                    @pdf-error="onPdfError"
                    ref="pdfViewerRef"
                  />
                </div>
              </div>
            </a-card>
          </div>
        </div>
      </div>
    </div>

    <!-- No Results State -->
    <div class="no-results-section" v-else-if="hasSearched && !loading">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <a-card class="empty-state-card">
              <div class="text-center py-5">
                <div class="empty-icon">
                  <i class="bi bi-file-earmark-x"></i>
                </div>
                <h4 class="mt-3 no-result-title">Không tìm thấy tài liệu</h4>
                <p class="no-result-text">
                  Không tìm thấy tài liệu với mã "<strong class="text-primary">{{ searchCode }}</strong>"
                </p>
                <p class="no-result-hint">
                  Vui lòng kiểm tra lại mã tài liệu và thử lại.
                </p>
                <div class="mt-4">
                  <a-button type="primary" @click="clearSearch" class="me-2">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Tìm kiếm lại
                  </a-button>
                  <a-button @click="scrollToSearch">
                    <i class="bi bi-arrow-up me-1"></i>
                    Về đầu trang
                  </a-button>
                </div>
              </div>
            </a-card>
          </div>
        </div>
      </div>
    </div>

    <!-- Welcome State -->
    <div class="welcome-section" v-else-if="!hasSearched && !loading">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <a-card class="welcome-card">
              <div class="text-center py-5">
                <div class="welcome-icon">
                  <i class="bi bi-search"></i>
                </div>
                <h4 class="mt-3 welcome-title">Tra cứu tài liệu đã ký số</h4>
                <p class="welcome-text">
                  Nhập mã tài liệu vào ô tìm kiếm phía trên để xem thông tin chi tiết và nội dung tài liệu đã được ký số.
                </p>
                <div class="search-tips mt-5">
                  <h6 class="tips-title">
                    <i class="bi bi-lightbulb me-2"></i>
                    Hướng dẫn sử dụng
                  </h6>
                  <div class="tips-content">
                    <div class="row g-4 mt-2">
                      <div class="col-md-4">
                        <div class="tip-item">
                          <div class="tip-icon">
                            <i class="bi bi-123"></i>
                          </div>
                          <h6>Mã tài liệu</h6>
                          <p>Mã có dạng: 20-1751203066, QĐ-123/2024</p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="tip-item">
                          <div class="tip-icon">
                            <i class="bi bi-shield-check"></i>
                          </div>
                          <h6>Tài liệu đã ký</h6>
                          <p>Chỉ hiển thị văn bản đã được ký số</p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="tip-item">
                          <div class="tip-icon">
                            <i class="bi bi-download"></i>
                          </div>
                          <h6>Tải xuống</h6>
                          <p>Có thể tải về và in tài liệu</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </a-card>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div class="loading-section" v-if="loading">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <a-card class="loading-card">
              <div class="text-center py-5">
                <a-spin size="large" />
                <h5 class="mt-3 loading-text">Đang tìm kiếm tài liệu...</h5>
                <p class="loading-hint">Vui lòng chờ trong giây lát</p>
              </div>
            </a-card>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Action Button for Mobile -->
    <div class="floating-actions d-md-none">
      <a-button 
        type="primary" 
        shape="circle" 
        size="large"
        @click="scrollToTop"
        class="fab-scroll-top"
      >
        <i class="bi bi-arrow-up"></i>
      </a-button>
    </div>

    <!-- Footer - Đã sửa với màu nền và container-fluid -->
    <div class="footer-section bg-header text-white py-4">
      <div class="container-fluid">
        <div class="text-center">
          <p class="footer-text mb-3">
            © {{ currentYear }} Đại học Thủy lợi - Hệ thống quản lý tài liệu điện tử
          </p>
          <small class="text-white-50">
            <i class="bi bi-calendar me-1"></i>
            {{ formatDate(new Date()) }}
          </small>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { message } from 'ant-design-vue'
import PDFViewer from '@/components/PDFViewer.vue'
import { useCertificateStore } from "@/stores/use-certificates"

export default {
  name: 'DocumentLookup',
  components: {
    PDFViewer
  },
  setup() {
    const searchCode = ref('')
    const loading = ref(false)
    const hasSearched = ref(false)
    const pdfViewerKey = ref(0)
    const pdfViewerRef = ref(null)
    const pdfContainer = ref(null)
    
    // Header hiding functionality từ dashboard
    const isHeaderHidden = ref(false)
    const hideTimeout = ref(null)
    const lastScrollY = ref(0)
    
    const certificateStore = useCertificateStore()

    // Current user and date info
    const currentUser = ref('Minh2k3')
    const currentYear = computed(() => new Date().getFullYear())

    // Date/Time formatting functions
    const formatDate = (date) => {
      return date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      })
    }

    const formatTime = (date) => {
      return date.toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      })
    }

    // Computed PDF URL
    const pdfUrl = computed(() => {
      if (certificateStore.certificates?.file_path) {
        const filePath = certificateStore.certificates.file_path
        const filename = filePath.includes('/') ? filePath.split('/').pop() : filePath
        return filename
      }
      return null
    })

    // Watch pdfUrl để reload PDFViewer
    watch(pdfUrl, (newUrl, oldUrl) => {
      if (newUrl && newUrl !== oldUrl) {
        pdfViewerKey.value += 1
        message.info('Đang tải tài liệu...', 2)
      }
    })

    // Header scroll handling từ dashboard
    const handleScroll = () => {
      const currentScrollY = window.scrollY
      
      if (currentScrollY > lastScrollY.value && currentScrollY > 100) {
        if (hideTimeout.value) {
          clearTimeout(hideTimeout.value)
        }
        
        hideTimeout.value = setTimeout(() => {
          isHeaderHidden.value = true
        }, 4000)
      } else {
        if (hideTimeout.value) {
          clearTimeout(hideTimeout.value)
          hideTimeout.value = null
        }
        isHeaderHidden.value = false
      }
      
      lastScrollY.value = currentScrollY
    }

    const showHeader = () => {
      if (hideTimeout.value) {
        clearTimeout(hideTimeout.value)
        hideTimeout.value = null
      }
      isHeaderHidden.value = false
    }

    const handleSearch = async () => {
      if (!searchCode.value.trim()) {
        message.warning('Vui lòng nhập mã tài liệu')
        return
      }

      loading.value = true
      hasSearched.value = true
      
      try {
        certificateStore.certificates = null
        const result = await certificateStore.findCertificateByCode(searchCode.value.trim())
        
        if (!result) {
          message.info('Không tìm thấy tài liệu với mã này')
        }
      } catch (error) {
        console.error('Search error:', error)
        message.error('Có lỗi xảy ra khi tìm kiếm tài liệu')
      } finally {
        loading.value = false
      }
    }

    const clearSearch = () => {
      searchCode.value = ''
      hasSearched.value = false
      certificateStore.certificates = null
      pdfViewerKey.value += 1
    }

    const scrollToTop = () => {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }

    const scrollToSearch = () => {
      document.querySelector('.search-section')?.scrollIntoView({ behavior: 'smooth' })
    }

    const downloadDocument = () => {
      if (pdfUrl.value) {
        const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000'
        const downloadUrl = `${baseURL}/api/pdf-proxy?file=${encodeURIComponent(pdfUrl.value)}`
        
        const link = document.createElement('a')
        link.href = downloadUrl
        link.download = `${certificateStore.certificates?.code || 'document'}.pdf`
        link.click()
        
        message.success('Đang tải xuống tài liệu...', 3)
      } else {
        message.error('Không có tài liệu để tải xuống')
      }
    }

    const printDocument = () => {
      if (pdfUrl.value) {
        const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000'
        const printUrl = `${baseURL}/api/pdf-proxy?file=${encodeURIComponent(pdfUrl.value)}`
        
        window.open(printUrl, '_blank')
        message.info('Đang mở tài liệu để in...', 3)
      } else {
        message.error('Không có tài liệu để in')
      }
    }

    // PDF event handlers
    const onPdfLoaded = (info) => {
      message.success(`Tài liệu đã được tải thành công (${info.totalPages} trang)`, 3)
    }

    const onPdfError = (error) => {
      message.error('Không thể tải tài liệu PDF. Vui lòng thử lại sau.')
    }

    // Lifecycle hooks
    onMounted(() => {
      window.addEventListener('scroll', handleScroll)
    })

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScroll)
      if (hideTimeout.value) {
        clearTimeout(hideTimeout.value)
      }
    })

    return {
      searchCode,
      loading,
      hasSearched,
      certificateStore,
      pdfUrl,
      pdfViewerKey,
      pdfViewerRef,
      pdfContainer,
      isHeaderHidden,
      currentUser,
      currentYear,
      handleSearch,
      clearSearch,
      scrollToTop,
      scrollToSearch,
      downloadDocument,
      printDocument,
      onPdfLoaded,
      onPdfError,
      showHeader,
      formatDate,
      formatTime
    }
  }
}
</script>

<style scoped>
/* Header styling từ dashboard */
.bg-header {
  background-color: #0078e8;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  transition: transform 0.3s ease-in-out;
}

.header-hidden {
  transform: translateY(-100%);
}

.header-trigger {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 60px;
  z-index: 999;
  pointer-events: auto;
}

.btn-link:hover {
  background-color: transparent !important;
  color: #fff !important;
  

}

/* Đảm bảo nội dung không bị che bởi fixed header */
.document-lookup-container {
  padding-top: 80px;
}

/* Footer styling - Override fixed positioning for footer */
.footer-section.bg-header {
  position: static !important;
  transform: none !important;
}

/* PDF Viewer Container - Cần thiết cho functionality */
.pdf-viewer-container {
  height: 700px;
  border-radius: 15px;
  overflow: hidden;
}

/* Floating Action Button positioning - Cần thiết cho mobile */
.floating-actions {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 1000;
}

@media (max-width: 768px) {
  .pdf-viewer-container {
    height: 400px;
  }
  
  .document-lookup-container {
    padding-top: 70px;
  }
}

@media (max-width: 576px) {
  .pdf-viewer-container {
    height: 350px;
  }
}
</style>