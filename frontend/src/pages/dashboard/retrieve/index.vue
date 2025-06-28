<template>
  <div class="document-lookup-container">
    <!-- Header -->
    <div class="header-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-2">
            <router-link to="/" class="d-flex align-items-center text-decoration-none">
              <img src="@/assets/images/logo_tlu.png" alt="Logo Thủy lợi" class="university-logo me-2">
              <span class="logo-text fw-bold">ĐH THỦY LỢI</span>
            </router-link>
          </div>
          <div class="col-md-8">
            <h1 class="page-title">
              <i class="anticon anticon-search"></i>
              Tra cứu tài liệu đã ký
            </h1>
            <p class="page-subtitle">
              Hệ thống quản lý và phê duyệt văn bản điện tử
            </p>
          </div>
          <div class="col-md-2 text-end">
            <div class="header-actions d-flex justify-content-end gap-2">
              <router-link to="/">
                <a-button type="primary" ghost class="">
                  <i class="bi bi-house-door me-2"></i>
                  Trang chủ
                </a-button>
              </router-link>
              <router-link to="/login">
                <a-button type="primary">
                  <i class="anticon anticon-user"></i>
                  Đăng nhập
                </a-button>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

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
                      placeholder="Nhập mã tài liệu cần tra cứu..."
                      size="large"
                      enter-button="Tìm kiếm"
                      :loading="loading"
                      @search="handleSearch"
                      class="search-input"
                    >
                      <template #prefix>
                        <i class="anticon anticon-file-text"></i>
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
                <div class="d-flex justify-content-between align-items-center">
                  <span class="document-title">
                    <i class="anticon anticon-file-pdf text-primary"></i>
                    {{ certificateStore.certificates.title }}
                  </span>
                  <div class="document-actions">
                    <a-tooltip title="Tải xuống">
                      <a-button 
                        type="primary" 
                        shape="circle" 
                        icon="download"
                        @click="downloadDocument"
                        class="me-2"
                      />
                    </a-tooltip>
                    <a-tooltip title="In tài liệu">
                      <a-button 
                        shape="circle" 
                        icon="printer"
                        @click="printDocument"
                      />
                    </a-tooltip>
                  </div>
                </div>
              </template>

              <!-- Document Information -->
              <div class="document-info mb-4">
                <div class="row">
                  <div class="col-md-3">
                    <div class="info-item">
                      <label>Mã tài liệu:</label>
                      <span class="value">{{ certificateStore.certificates.code }}</span>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="info-item">
                      <label>Loại tài liệu:</label>
                      <span class="value">{{ getDocumentTypeText(certificateStore.certificates.type) }}</span>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="info-item">
                      <label>Ngày ký:</label>
                      <span class="value">{{ formatDate(certificateStore.certificates.signed_date) }}</span>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="info-item">
                      <label>Trạng thái:</label>
                      <a-tag :color="getStatusColor(certificateStore.certificates.status)" class="value">
                        {{ getStatusText(certificateStore.certificates.status) }}
                      </a-tag>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Digital Signature Information -->
              <div class="signature-section mb-4" v-if="certificateStore.certificates.signatures && certificateStore.certificates.signatures.length > 0">
                <h5 class="section-title">
                  <i class="anticon anticon-safety-certificate"></i>
                  Thông tin chữ ký số
                </h5>
                <div class="signatures-list">
                  <div 
                    v-for="signature in certificateStore.certificates.signatures" 
                    :key="signature.id" 
                    class="signature-item"
                  >
                    <div class="row align-items-center">
                      <div class="col-md-3">
                        <div class="signer-info">
                          <strong>{{ signature.signer_name }}</strong>
                          <div class="position">{{ signature.position }}</div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="sign-time">
                          <label>Thời gian ký:</label>
                          <div>{{ formatDateTime(signature.signed_at) }}</div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="certificate-info">
                          <label>Chứng thư số:</label>
                          <div class="certificate-detail">{{ signature.certificate_info }}</div>
                        </div>
                      </div>
                      <div class="col-md-2 text-end">
                        <a-tag color="green">
                          <i class="anticon anticon-check-circle"></i>
                          Hợp lệ
                        </a-tag>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- PDF Viewer -->
              <div class="pdf-section">
                <h5 class="section-title">
                  <i class="anticon anticon-file-pdf"></i>
                  Nội dung tài liệu
                </h5>
                <div class="pdf-viewer-container">
                  <PDFViewer 
                    :pdf-url="pdfUrl" 
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
                  <i class="anticon anticon-file-search"></i>
                </div>
                <h4 class="mt-3 no-result-title">Không tìm thấy tài liệu</h4>
                <p class="no-result-text">
                  Không tìm thấy tài liệu với mã "<strong>{{ searchCode }}</strong>"
                </p>
                <p class="no-result-hint">
                  Vui lòng kiểm tra lại mã tài liệu và thử lại.
                </p>
                <a-button type="primary" @click="clearSearch" class="mt-3">
                  <i class="anticon anticon-reload"></i>
                  Tìm kiếm lại
                </a-button>
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
                  <i class="anticon anticon-search"></i>
                </div>
                <h4 class="mt-3 welcome-title">Tra cứu tài liệu đã ký</h4>
                <p class="welcome-text">
                  Nhập mã tài liệu vào ô tìm kiếm phía trên để xem thông tin chi tiết và nội dung tài liệu.
                </p>
                <div class="search-tips mt-4">
                  <h6 class="tips-title">
                    <i class="anticon anticon-bulb text-warning"></i>
                    Hướng dẫn:
                  </h6>
                  <ul class="tips-list">
                    <li>Mã tài liệu thường có dạng: QĐ-123/2024, TB-456/2024</li>
                    <li>Chỉ hiển thị các tài liệu đã được ký số</li>
                    <li>Bạn có thể tải xuống hoặc in tài liệu sau khi tìm thấy</li>
                  </ul>
                </div>
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
        <i class="anticon anticon-arrow-up"></i>
      </a-button>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
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
    
    const certificateStore = useCertificateStore()

    const pdfUrl = computed(() => {
      if (certificateStore.certificates?.file_path) {
        return `${import.meta.env.VITE_API_URL}/storage/${certificateStore.certificates.file_path}`
      }
      return null
    })

    const handleSearch = async () => {
      if (!searchCode.value.trim()) {
        message.warning('Vui lòng nhập mã tài liệu')
        return
      }

      loading.value = true
      hasSearched.value = true
      
      try {
        const result = await certificateStore.findCertificateByCode(searchCode.value.trim())
        
        if (result) {
          message.success('Tìm thấy tài liệu')
        } else {
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
    }

    const scrollToTop = () => {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }

    const downloadDocument = () => {
      if (certificateStore.certificates && pdfUrl.value) {
        const link = document.createElement('a')
        link.href = pdfUrl.value
        link.download = `${certificateStore.certificates.code}.pdf`
        link.click()
        message.success('Đang tải xuống tài liệu...')
      }
    }

    const printDocument = () => {
      if (pdfUrl.value) {
        window.open(pdfUrl.value, '_blank')
        message.info('Đang mở tài liệu để in...')
      }
    }

    const formatDate = (date) => {
      if (!date) return ''
      return new Date(date).toLocaleDateString('vi-VN')
    }

    const formatDateTime = (date) => {
      if (!date) return ''
      return new Date(date).toLocaleString('vi-VN')
    }

    const getStatusColor = (status) => {
      const colors = {
        signed: 'green',
        approved: 'blue',
        published: 'purple',
        draft: 'orange',
        rejected: 'red'
      }
      return colors[status] || 'default'
    }

    const getStatusText = (status) => {
      const texts = {
        signed: 'Đã ký',
        approved: 'Đã phê duyệt',
        published: 'Đã ban hành',
        draft: 'Nháp',
        rejected: 'Từ chối'
      }
      return texts[status] || 'Không xác định'
    }

    const getDocumentTypeText = (type) => {
      const types = {
        decision: 'Quyết định',
        circular: 'Thông tư',
        directive: 'Chỉ thị',
        notification: 'Thông báo',
        letter: 'Công văn',
        report: 'Báo cáo'
      }
      return types[type] || 'Khác'
    }

    return {
      searchCode,
      loading,
      hasSearched,
      certificateStore,
      pdfUrl,
      handleSearch,
      clearSearch,
      scrollToTop,
      downloadDocument,
      printDocument,
      formatDate,
      formatDateTime,
      getStatusColor,
      getStatusText,
      getDocumentTypeText
    }
  }
}
</script>

<style scoped>
/* Màu chủ đạo phù hợp với dashboard */
:root {
  --primary-color: #1890ff;
  --primary-hover: #40a9ff;
  --primary-active: #096dd9;
  --secondary-color: #0078e8;
  --accent-color: #f0f8ff;
  --text-color: #262626;
  --text-muted: #595959;
  --text-light: #8c8c8c;
  --border-color: #d9d9d9;
  --light-bg: #fafafa;
  --white: #ffffff;
  --bg-gradient: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
  --success-color: #52c41a;
  --warning-color: #faad14;
  --error-color: #ff4d4f;
}

.document-lookup-container {
  min-height: 100vh;
  background: var(--bg-gradient);
}

.header-section {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: var(--white);
  padding: 1.5rem 0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.header-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.06)"/></svg>');
  animation: float 20s infinite linear;
}

@keyframes float {
  0% { transform: translateX(0) translateY(0); }
  100% { transform: translateX(-100px) translateY(-50px); }
}

.university-logo {
  height: 45px;
  width: 45px;
  filter: brightness(0) invert(1);
  border-radius: 8px;
}

.logo-text {
  color: var(--white);
  font-size: 1.1rem;
}

.page-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--white);
}

.page-subtitle {
  font-size: 0.95rem;
  opacity: 0.9;
  margin-bottom: 0;
  color: var(--white);
}

.header-actions .ant-btn {
  border-radius: 6px;
  font-weight: 500;
}

.search-section {
  padding: 2rem 0;
  margin-top: -1rem;
  position: relative;
  z-index: 2;
}

.search-card {
  background: var(--white);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  border-radius: 16px;
  border: none;
  backdrop-filter: blur(10px);
}

.search-card :deep(.ant-card-body) {
  padding: 2rem;
}

.search-card :deep(.ant-form-item-label > label) {
  color: var(--text-color);
  font-weight: 600;
  font-size: 1rem;
}

.search-input :deep(.ant-input) {
  border-radius: 12px;
  border: 2px solid var(--border-color);
  color: var(--text-color);
  padding: 12px 16px;
  transition: all 0.3s ease;
}

.search-input :deep(.ant-input:focus) {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 4px rgba(24, 144, 255, 0.1);
}

.search-input :deep(.ant-input::placeholder) {
  color: var(--text-light);
}

.search-input :deep(.ant-btn) {
  background: var(--primary-color);
  border-color: var(--primary-color);
  border-radius: 0 12px 12px 0;
  color: var(--white);
  font-weight: 600;
  height: 52px;
  padding: 0 24px;
  transition: all 0.3s ease;
}

.search-input :deep(.ant-btn:hover) {
  background: var(--primary-hover);
  border-color: var(--primary-hover);
  transform: translateY(-1px);
}

.document-section,
.no-results-section,
.welcome-section {
  padding: 1rem 0 3rem;
}

.document-card,
.welcome-card,
.empty-state-card {
  background: var(--white);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  border-radius: 16px;
  border: 1px solid rgba(24, 144, 255, 0.1);
  overflow: hidden;
}

.document-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--text-color);
}

.document-info {
  background: linear-gradient(135deg, #f8fcff 0%, #f0f8ff 100%);
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid rgba(24, 144, 255, 0.1);
}

.info-item {
  margin-bottom: 0.75rem;
}

.info-item label {
  font-weight: 600;
  color: var(--text-muted);
  display: block;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.info-item .value {
  color: var(--text-color);
  font-weight: 500;
  font-size: 0.95rem;
}

.section-title {
  color: var(--primary-color);
  font-weight: 700;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  border-bottom: 2px solid rgba(24, 144, 255, 0.1);
  padding-bottom: 0.75rem;
  font-size: 1.1rem;
}

.signature-section {
  background: linear-gradient(135deg, #f6ffed 0%, #f0fff0 100%);
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid #b7eb8f;
}

.signatures-list {
  max-height: 300px;
  overflow-y: auto;
}

.signature-item {
  background: var(--white);
  padding: 1.25rem;
  border-radius: 12px;
  margin-bottom: 1rem;
  border: 1px solid var(--border-color);
  transition: all 0.3s ease;
}

.signature-item:hover {
  border-color: var(--primary-color);
  box-shadow: 0 4px 15px rgba(24, 144, 255, 0.1);
  transform: translateY(-2px);
}

.signature-item:last-child {
  margin-bottom: 0;
}

.signer-info strong {
  color: var(--text-color);
  font-size: 1rem;
}

.signer-info .position {
  color: var(--text-muted);
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.sign-time label,
.certificate-info label {
  font-weight: 600;
  color: var(--text-muted);
  font-size: 0.875rem;
  display: block;
  margin-bottom: 0.25rem;
}

.sign-time div,
.certificate-info .certificate-detail {
  color: var(--text-color);
}

.certificate-detail {
  font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
  font-size: 0.875rem;
  color: var(--text-color);
  background: #f8f9fa;
  padding: 0.5rem;
  border-radius: 6px;
  border: 1px solid var(--border-color);
}

.pdf-section {
  background: var(--white);
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid var(--border-color);
}

.pdf-viewer-container {
  height: 600px;
  border: 2px solid var(--border-color);
  border-radius: 12px;
  overflow: hidden;
  background: var(--light-bg);
}

.document-actions .ant-btn {
  border-radius: 50%;
  border: 2px solid var(--primary-color);
  color: var(--primary-color);
  transition: all 0.3s ease;
}

.document-actions .ant-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(24, 144, 255, 0.3);
}

.document-actions .ant-btn[type="primary"] {
  background: var(--primary-color);
  color: var(--white);
}

/* Welcome & Empty States */
.welcome-icon,
.empty-icon {
  font-size: 4rem;
  color: var(--primary-color);
  margin-bottom: 1rem;
}

.welcome-title {
  color: var(--text-color);
  font-weight: 700;
  font-size: 1.5rem;
}

.welcome-text {
  color: var(--text-muted);
  font-size: 1.1rem;
  line-height: 1.6;
}

.no-result-title {
  color: var(--text-color);
  font-weight: 600;
}

.no-result-text {
  color: var(--text-muted);
  font-size: 1rem;
}

.no-result-hint {
  color: var(--text-light);
  font-size: 0.875rem;
}

.search-tips {
  background: linear-gradient(135deg, var(--accent-color) 0%, #e6f7ff 100%);
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid rgba(24, 144, 255, 0.2);
}

.tips-title {
  color: var(--primary-color);
  font-weight: 600;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.tips-list {
  text-align: left;
  margin-bottom: 0;
  padding-left: 1rem;
}

.tips-list li {
  margin-bottom: 0.5rem;
  color: var(--text-muted);
  font-size: 0.95rem;
  line-height: 1.5;
}

/* Floating Action Button */
.floating-actions {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 1000;
}

.fab-scroll-top {
  background: var(--primary-color);
  border: none;
  box-shadow: 0 4px 20px rgba(24, 144, 255, 0.4);
  transition: all 0.3s ease;
}

.fab-scroll-top:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 25px rgba(24, 144, 255, 0.5);
}

/* Responsive */
@media (max-width: 768px) {
  .header-section {
    padding: 1rem 0;
  }
  
  .page-title {
    font-size: 1.3rem;
  }
  
  .page-subtitle {
    font-size: 0.85rem;
  }
  
  .university-logo {
    height: 35px;
    width: 35px;
  }
  
  .logo-text {
    font-size: 0.9rem;
  }
  
  .header-actions .ant-btn {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
  }
  
  .search-card :deep(.ant-card-body) {
    padding: 1.5rem;
  }
  
  .document-info .row > div {
    margin-bottom: 1rem;
  }
  
  .signature-item .row > div {
    margin-bottom: 0.75rem;
  }
  
  .pdf-viewer-container {
    height: 400px;
  }
  
  .document-actions {
    margin-top: 1rem;
  }
}

/* Custom scrollbar */
.signatures-list::-webkit-scrollbar {
  width: 6px;
}

.signatures-list::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.signatures-list::-webkit-scrollbar-thumb {
  background: var(--primary-color);
  border-radius: 3px;
}

.signatures-list::-webkit-scrollbar-thumb:hover {
  background: var(--primary-hover);
}

/* Smooth animations */
.document-card,
.welcome-card,
.empty-state-card {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>