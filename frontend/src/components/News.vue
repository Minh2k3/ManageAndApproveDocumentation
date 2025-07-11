<template>
  <div class="public-documents">
    <!-- Header Section -->
    <div class="page-header">
      <h1>Văn bản công khai</h1>
      <div class="header-actions">
        <!-- View Mode Toggle -->
        <div class="view-toggle">
          <button 
            @click="viewMode = 'card'" 
            :class="['view-btn', { active: viewMode === 'card' }]"
            title="Hiển thị dạng thẻ"
          >
            <i class="fas fa-th-large"></i>
          </button>
          <button 
            @click="viewMode = 'table'" 
            :class="['view-btn', { active: viewMode === 'table' }]"
            title="Hiển thị dạng bảng"
          >
            <i class="fas fa-table"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-group">
        <!-- Document Type Filter -->
        <div class="filter-item">
          <label>Loại văn bản:</label>
          <select v-model="filters.documentType" @change="applyFilters" class="filter-select">
            <option value="">Tất cả loại</option>
            <option 
              v-for="type in documentTypes" 
              :key="type.id" 
              :value="type.id"
            >
              {{ type.name }}
            </option>
          </select>
        </div>

        <!-- Department Filter -->
        <div class="filter-item">
          <label>Đơn vị ban hành:</label>
          <select v-model="filters.department" @change="applyFilters" class="filter-select">
            <option value="">Tất cả đơn vị</option>
            <option 
              v-for="dept in departments" 
              :key="dept.id" 
              :value="dept.id"
            >
              {{ dept.name }}
            </option>
          </select>
        </div>

        <!-- Search Input -->
        <div class="filter-item search-box">
          <label>Tìm kiếm theo tên:</label>
          <div class="search-input-wrapper">
            <i class="fas fa-search"></i>
            <input 
              v-model="filters.search" 
              @input="debounceSearch"
              type="text" 
              placeholder="Nhập từ khóa..."
              class="search-input"
            >
          </div>
        </div>

        <!-- Clear Filters -->
        <button @click="clearFilters" class="btn-clear-filter">
          <i class="fas fa-times"></i>
          Xóa bộ lọc
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="loading-wrapper">
      <div class="spinner"></div>
      <p>Đang tải dữ liệu...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="filteredDocuments.length === 0" class="empty-state">
      <a-empty
        image="https://gw.alipayobjects.com/mdn/miniapp_social/afts/img/A*pevERLJC9v0AAAAAAAAAAABjAQAAAQ/original"
        :image-style="{
          height: '60px',
        }"
      >
        <template #description>
          <span>
            Chưa có văn bản nào được phê duyệt. Hãy tạo văn bản mới để bắt đầu.
          </span>
        </template>
        <a-button type="primary">
          <router-link class="text-decoration-none" to=documents/create >Tạo mới
          </router-link>
        </a-button>
      </a-empty>
    </div>

    <!-- Card View -->
    <div v-else-if="viewMode === 'card'" class="card-view">
      <div class="documents-grid">
        <div 
          v-for="doc in paginatedDocuments" 
          :key="doc.id" 
          class="document-card"
          @click="viewDocument(doc)"
        >
          <div class="card-header">
            <div class="document-type-badge">
              {{ doc.document_type.name }}
            </div>
            <div class="card-actions">
              <button @click.stop="downloadDocument(doc)" class="btn-icon" title="Tải xuống">
                <i class="fas fa-download"></i>
              </button>
            </div>
          </div>
          
          <div class="card-body">
            <h3 class="document-title">{{ doc.title }}</h3>
            <p class="document-description">{{ truncateText(doc.description, 100) }}</p>
            
            <div class="creator-info">
              <img 
                :src="getAvatarUrl(doc.creator)" 
                :alt="doc.creator.name"
                class="creator-avatar"
              >
              <div class="creator-details">
                <p class="creator-name">{{ doc.creator.name }}</p>
                <p class="creator-position">{{ doc.creator.position }}</p>
              </div>
            </div>
          </div>
          
          <div class="card-footer">
            <span class="document-date">
              <i class="far fa-calendar"></i>
              {{ formatDate(doc.created_at) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Table View -->
    <div v-else-if="viewMode === 'table'" class="table-view">
      <table class="documents-table">
        <thead>
          <tr>
            <th @click="sortBy('title')" class="sortable">
              Tiêu đề
              <i :class="getSortIcon('title')"></i>
            </th>
            <th @click="sortBy('document_type.name')" class="sortable">
              Loại văn bản
              <i :class="getSortIcon('document_type.name')"></i>
            </th>
            <th>Người ban hành</th>
            <th>Đơn vị</th>
            <th @click="sortBy('created_at')" class="sortable">
              Ngày ban hành
              <i :class="getSortIcon('created_at')"></i>
            </th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="doc in paginatedDocuments" 
            :key="doc.id"
            @click="viewDocument(doc)"
            class="table-row"
          >
            <td class="document-title-cell">
              <div class="title-wrapper">
                <i class="fas fa-file-alt"></i>
                {{ doc.title }}
              </div>
            </td>
            <td>
              <span class="type-badge">{{ doc.document_type.name }}</span>
            </td>
            <td>
              <div class="creator-cell">
                <img 
                  :src="getAvatarUrl(doc.creator)" 
                  :alt="doc.creator.name"
                  class="creator-avatar-small"
                >
                {{ doc.creator.name }}
              </div>
            </td>
            <td>{{ getDepartmentFromPosition(doc.creator.position) }}</td>
            <td>{{ formatDate(doc.created_at) }}</td>
            <td @click.stop>
              <div class="table-actions">
                <button @click="viewDocument(doc)" class="btn-action" title="Xem chi tiết">
                  <i class="fas fa-eye"></i>
                </button>
                <button @click="downloadDocument(doc)" class="btn-action" title="Tải xuống">
                  <i class="fas fa-download"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="pagination">
      <button 
        @click="currentPage = currentPage - 1"
        :disabled="currentPage === 1"
        class="page-btn"
      >
        <i class="fas fa-chevron-left"></i>
      </button>
      
      <div class="page-numbers">
        <button 
          v-for="page in visiblePages" 
          :key="page"
          @click="currentPage = page"
          :class="['page-number', { active: currentPage === page }]"
        >
          {{ page }}
        </button>
      </div>
      
      <button 
        @click="currentPage = currentPage + 1"
        :disabled="currentPage === totalPages"
        class="page-btn"
      >
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>

    <!-- Document Preview Modal -->
    <div v-if="selectedDocument" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <!-- Modal Header -->
        <div class="modal-header">
          <h2>Chi tiết văn bản</h2>
          <button @click="closeModal" class="btn-close">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Modal Body -->
        <div class="modal-body">
          <!-- Document Title -->
          <div class="document-info-section">
            <h3 class="document-title-modal">{{ selectedDocument.title }}</h3>
            <div class="document-type-badge-modal">
              {{ selectedDocument.document_type.name }}
            </div>
          </div>

          <!-- Document Details -->
          <div class="document-details">
            <div class="detail-row">
              <label>Người ban hành:</label>
              <div class="creator-info-modal">
                <img 
                  :src="getAvatarUrl(selectedDocument.creator)" 
                  :alt="selectedDocument.creator.name"
                  class="creator-avatar-modal"
                >
                <div>
                  <span class="creator-name-text">{{ selectedDocument.creator.name }}</span>
                  <span class="creator-position-text">{{ selectedDocument.creator.position }}</span>
                </div>
              </div>
            </div>

            <div class="detail-row">
              <label>Ngày ban hành:</label>
              <span class="detail-value">{{ formatDate(selectedDocument.created_at) }}</span>
            </div>

            <div class="detail-row description-row">
              <label>Mô tả:</label>
              <p class="description-content">{{ selectedDocument.description }}</p>
            </div>

            <div class="detail-row description-row">
              <label>Chứng chỉ số:</label>
              <a :href="`${apiUrl}/documents/certificates/${selectedDocument.certificate_path}`" target="_blank" class="text-decoration-none fst-italic">
                  Mở
              </a>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="modal-actions">
            <!-- <button @click="downloadDocument(selectedDocument)" class="btn-download">
              <i class="fas fa-download"></i>
              Tải xuống
            </button> -->
            <a 
              :href="`${apiUrl}/documents/${selectedDocument.file_path}`" 
              target="_blank"
              class="btn-view-external"
            >
              <i class="fas fa-external-link-alt"></i>
              Mở trong tab mới
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosInstance from '@/lib/axios.js';
import { format } from 'date-fns';
import { vi } from 'date-fns/locale';

export default {
  name: 'PublicDocuments',
  
  data() {
    return {
      documents: [],
      filteredDocuments: [],
      loading: false,
      viewMode: 'card', // 'card' or 'table'
      currentPage: 1,
      itemsPerPage: 12,
      selectedDocument: null,
      
      // Filters
      filters: {
        documentType: '',
        department: '',
        search: ''
      },
      
      // Filter options
      documentTypes: [],
      departments: [],
      
      // Sorting
      sortField: 'created_at',
      sortOrder: 'desc',
      
      // Debounce timer
      searchTimer: null,

      apiUrl: import.meta.env.VITE_API_URL || 'http://localhost:8000'
    };
  },
  
  computed: {
    paginatedDocuments() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredDocuments.slice(start, end);
    },
    
    totalPages() {
      return Math.ceil(this.filteredDocuments.length / this.itemsPerPage);
    },
    
    visiblePages() {
      const pages = [];
      const range = 2; // Pages to show on each side
      
      let start = Math.max(1, this.currentPage - range);
      let end = Math.min(this.totalPages, this.currentPage + range);
      
      // Adjust if at the beginning
      if (this.currentPage <= range) {
        end = Math.min(this.totalPages, range * 2 + 1);
      }
      
      // Adjust if at the end
      if (this.currentPage > this.totalPages - range) {
        start = Math.max(1, this.totalPages - range * 2);
      }
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      
      return pages;
    }
  },
  
  mounted() {
    this.fetchDocuments();
    this.loadViewMode();
  },
  
  methods: {
    async fetchDocuments() {
      this.loading = true;
      try {
        const response = await axiosInstance.get('/api/documents/public');
        this.documents = response.data.documents || [];
        this.filteredDocuments = [...this.documents];
        
        // Extract unique document types and departments
        this.extractFilterOptions();
        
      } catch (error) {
        console.error('Error fetching documents:', error);
        this.$toast.error('Không thể tải danh sách văn bản');
      } finally {
        this.loading = false;
      }
    },
    
    extractFilterOptions() {
      // Extract document types
      const typesMap = new Map();
      this.documents.forEach(doc => {
        if (doc.document_type && doc.document_type.id) {
          typesMap.set(doc.document_type.id, doc.document_type);
        }
      });
      this.documentTypes = Array.from(typesMap.values());
      
      // Extract departments from creator positions
      const deptsSet = new Set();
      this.documents.forEach(doc => {
        const dept = this.getDepartmentFromPosition(doc.creator.position);
        if (dept) {
          deptsSet.add(dept);
        }
      });
      this.departments = Array.from(deptsSet).map((name, index) => ({
        id: index + 1,
        name: name
      }));
    },
    
    applyFilters() {
      let filtered = [...this.documents];
      
      // Filter by document type
      if (this.filters.documentType) {
        filtered = filtered.filter(doc => 
          doc.document_type.id === parseInt(this.filters.documentType)
        );
      }
      
      // Filter by department
      if (this.filters.department) {
        const selectedDept = this.departments.find(d => d.id === parseInt(this.filters.department));
        if (selectedDept) {
          filtered = filtered.filter(doc => 
            doc.creator.position.includes(selectedDept.name)
          );
        }
      }
      
      // Filter by search
      if (this.filters.search) {
        const searchLower = this.filters.search.toLowerCase();
        filtered = filtered.filter(doc => 
          doc.title.toLowerCase().includes(searchLower) ||
          doc.description.toLowerCase().includes(searchLower) ||
          doc.creator.name.toLowerCase().includes(searchLower)
        );
      }
      
      // Apply sorting
      this.filteredDocuments = this.sortDocuments(filtered);
      
      // Reset to first page
      this.currentPage = 1;
    },
    
    sortDocuments(docs) {
      return docs.sort((a, b) => {
        let aVal = this.getNestedValue(a, this.sortField);
        let bVal = this.getNestedValue(b, this.sortField);
        
        if (this.sortField === 'created_at') {
          aVal = new Date(aVal);
          bVal = new Date(bVal);
        }
        
        if (this.sortOrder === 'asc') {
          return aVal > bVal ? 1 : -1;
        } else {
          return aVal < bVal ? 1 : -1;
        }
      });
    },
    
    getNestedValue(obj, path) {
      return path.split('.').reduce((o, p) => o && o[p], obj);
    },
    
    sortBy(field) {
      if (this.sortField === field) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortField = field;
        this.sortOrder = 'asc';
      }
      this.applyFilters();
    },
    
    getSortIcon(field) {
      if (this.sortField !== field) {
        return 'fas fa-sort';
      }
      return this.sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
    },
    
    debounceSearch() {
      clearTimeout(this.searchTimer);
      this.searchTimer = setTimeout(() => {
        this.applyFilters();
      }, 300);
    },
    
    clearFilters() {
      this.filters = {
        documentType: '',
        department: '',
        search: ''
      };
      this.applyFilters();
    },
    
    viewDocument(doc) {
      this.selectedDocument = doc;
    },
    
    closeModal() {
      this.selectedDocument = null;
    },
    
    async downloadDocument(doc) {
      try {
        const response = await axiosInstance.get(`/api/documents/${doc.id}/download`, {
          responseType: 'blob'
        });
        
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', doc.file_path.split('/').pop());
        document.body.appendChild(link);
        link.click();
        link.remove();
        
        this.$toast.success('Đã tải xuống văn bản');
      } catch (error) {
        console.error('Error downloading document:', error);
        this.$toast.error('Không thể tải xuống văn bản');
      }
    },
    
    viewFullDocument(doc) {
      window.open(`/documents/${doc.id}/view`, '_blank');
    },
    
    formatDate(date) {
      return date;
      return format(new Date(date), 'dd/MM/yyyy', { locale: vi });
    },
    
    truncateText(text, length) {
      if (!text || text.length <= length) return text;
      return text.substring(0, length) + '...';
    },
    
    getDepartmentFromPosition(position) {
      // Extract department from position string "Role - Department"
      const parts = position.split(' - ');
      return parts.length > 1 ? parts[1] : '';
    },
    
    loadViewMode() {
      const saved = localStorage.getItem('documentViewMode');
      if (saved) {
        this.viewMode = saved;
      }
    },
    
    saveViewMode() {
      localStorage.setItem('documentViewMode', this.viewMode);
    },

    randomAvatar(userId) {
        // Sử dụng DiceBear API với nhiều style khác nhau
        const styles = [
            'adventurer',
            'avataaars',
            'big-ears',
            'bottts',
            'croodles',
            'fun-emoji',
            'identicon',
            'initials',
            'lorelei',
            'micah',
            'miniavs',
            'open-peeps',
            'personas',
            'pixel-art'
        ];
        
        // Chọn ngẫu nhiên một style dựa trên userId để đảm bảo tính nhất quán
        const styleIndex = userId % styles.length;
        const style = styles[styleIndex];
        
        // Tạo seed dựa trên userId để avatar luôn giống nhau cho cùng một user
        const seed = `user-${userId}`;
        
        return `https://api.dicebear.com/7.x/${style}/svg?seed=${seed}&size=150`;
    },

    getAvatarUrl(user){
        const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
        if (user.avatar === null) return this.randomAvatar(user.id);
        return `${API_BASE_URL}/images/avatars/${user.avatar}`
    },
  },
  
  watch: {
    viewMode() {
      this.saveViewMode();
    }
  }
};
</script>

<style scoped>
.public-documents {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
}

/* Header */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.page-header h1 {
  font-size: 28px;
  font-weight: 600;
  color: #333;
}

.header-actions {
  display: flex;
  gap: 20px;
  align-items: center;
}

/* View Toggle */
.view-toggle {
  display: flex;
  background: #f0f0f0;
  border-radius: 6px;
  padding: 2px;
}

.view-btn {
  background: transparent;
  border: none;
  padding: 8px 16px;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.3s;
  color: #666;
}

.view-btn.active {
  background: white;
  color: #2563eb;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.view-btn:hover:not(.active) {
  color: #333;
}

/* Filter Section */
.filter-section {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.filter-group {
  display: flex;
  gap: 20px;
  align-items: flex-end;
  flex-wrap: wrap;
}

.filter-item {
  flex: 1;
  min-width: 200px;
}

.filter-item label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #555;
}

.filter-select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  background: white;
}

.search-box {
  flex: 2;
}

.search-input-wrapper {
  position: relative;
}

.search-input-wrapper i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
}

.search-input {
  width: 100%;
  padding: 10px 10px 10px 35px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
}

.btn-clear-filter {
  background: #f44336;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background 0.3s;
  white-space: nowrap;
}

.btn-clear-filter:hover {
  background: #d32f2f;
}

/* Loading & Empty States */
.loading-wrapper,
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 100px 0;
  color: #999;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #2563eb;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.empty-state i {
  font-size: 60px;
  margin-bottom: 20px;
  opacity: 0.5;
}

/* Card View */
.documents-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
}

.document-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: all 0.3s;
  cursor: pointer;
  overflow: hidden;
}

.document-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card-header {
  padding: 15px 20px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.document-type-badge {
  background: #e3f2fd;
  color: #1976d2;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.card-actions {
  display: flex;
  gap: 8px;
}

.btn-icon {
  background: transparent;
  border: none;
  padding: 8px;
  cursor: pointer;
  border-radius: 4px;
  color: #666;
  transition: all 0.3s;
}

.btn-icon:hover {
  background: #f5f5f5;
  color: #333;
}

.card-body {
  padding: 20px;
}

.document-title {
  font-size: 18px;
  font-weight: 600;
  margin: 0 0 10px 0;
  color: #333;
  line-height: 1.4;
}

.document-description {
  color: #666;
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 15px;
}

.creator-info {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 15px;
}

.creator-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.creator-details {
  flex: 1;
}

.creator-name {
  font-weight: 500;
  color: #333;
  margin: 0;
  font-size: 14px;
}

.creator-position {
  color: #666;
  font-size: 12px;
  margin: 0;
}

.card-footer {
  padding: 15px 20px;
  background: #f8f9fa;
  border-top: 1px solid #eee;
}

.document-date {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #666;
  font-size: 13px;
}

/* Table View */
.table-view {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.documents-table {
  width: 100%;
  border-collapse: collapse;
}

.documents-table thead {
  background: #f8f9fa;
}

.documents-table th {
  padding: 15px;
  text-align: left;
  font-weight: 600;
  color: #333;
  border-bottom: 2px solid #eee;
}

.documents-table th.sortable {
  cursor: pointer;
  user-select: none;
}

.documents-table th.sortable:hover {
  background: #f0f0f0;
}

.documents-table th i {
  margin-left: 8px;
  font-size: 12px;
  color: #999;
}

.table-row {
  cursor: pointer;
  transition: background 0.2s;
}

.table-row:hover {
  background: #f8f9fa;
}

.documents-table td {
  padding: 15px;
  border-bottom: 1px solid #eee;
}

.document-title-cell {
  max-width: 300px;
}

.title-wrapper {
  display: flex;
  align-items: center;
  gap: 8px;
}

.title-wrapper i {
  color: #666;
}

.type-badge {
  background: #e3f2fd;
  color: #1976d2;
  padding: 4px 10px;
  border-radius: 4px;
  font-size: 12px;
  white-space: nowrap;
}

.creator-cell {
  display: flex;
  align-items: center;
  gap: 8px;
}

.creator-avatar-small {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  object-fit: cover;
}

.table-actions {
  display: flex;
  gap: 8px;
}

.btn-action {
  background: transparent;
  border: 1px solid #ddd;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
  color: #666;
  transition: all 0.3s;
}

.btn-action:hover {
  background: #f5f5f5;
  color: #333;
  border-color: #999;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 30px;
  gap: 10px;
}

.page-btn {
  background: white;
  border: 1px solid #ddd;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s;
}

.page-btn:hover:not(:disabled) {
  background: #f5f5f5;
  border-color: #999;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-numbers {
  display: flex;
  gap: 5px;
}

.page-number {
  background: white;
  border: 1px solid #ddd;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s;
  min-width: 40px;
  text-align: center;
}

.page-number:hover {
  background: #f5f5f5;
}

.page-number.active {
  background: #2563eb;
  color: white;
  border-color: #2563eb;
}

/* Simple Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-header {
  background: #007cba;
  color: white;
  padding: 20px 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
  font-size: 20px;
  font-weight: 600;
  margin: 0;
}

.btn-close {
  background: transparent;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
  padding: 5px;
  border-radius: 4px;
  transition: background 0.3s;
}

.btn-close:hover {
  background: rgba(255, 255, 255, 0.1);
}

.modal-body {
  padding: 25px;
  overflow-y: auto;
  max-height: calc(90vh - 100px);
}

.document-info-section {
  margin-bottom: 25px;
  padding-bottom: 20px;
  border-bottom: 1px solid #e5e7eb;
}

.document-title-modal {
  font-size: 22px;
  font-weight: 600;
  color: #1f2937;
  margin: 0 0 15px 0;
  line-height: 1.4;
}

.document-type-badge-modal {
  display: inline-block;
  background: #EBF4FF;
  color: #1E3A8A;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 500;
}

.document-details {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 25px;
}

.detail-row {
  display: flex;
  align-items: flex-start;
  gap: 15px;
}

.detail-row label {
  font-weight: 600;
  color: #374151;
  min-width: 120px;
  font-size: 14px;
}

.detail-value {
  color: #1f2937;
  font-size: 14px;
}

.creator-info-modal {
  display: flex;
  align-items: center;
  gap: 12px;
}

.creator-avatar-modal {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

.creator-name-text {
  display: block;
  font-weight: 600;
  color: #1f2937;
  font-size: 14px;
  margin-bottom: 2px;
}

.creator-position-text {
  display: block;
  color: #6b7280;
  font-size: 13px;
}

.description-row {
  align-items: flex-start;
}

.description-content {
  color: #1f2937;
  font-size: 14px;
  line-height: 1.6;
  margin: 0;
  text-align: justify;
  flex: 1;
}

.modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 20px;
  border-top: 1px solid #e5e7eb;
}

.btn-download,
.btn-view-external {
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s;
  text-decoration: none;
  border: 1px solid transparent;
}

.btn-download {
  background: #1E3A8A;
  color: white;
  border: none;
}

.btn-download:hover {
  background: #1E40AF;
}

.btn-view-external {
  background: #007cba;
  color: white;
}

.btn-view-external:hover {
  background: #005c89;
  color: white;
  text-decoration: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .documents-grid {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  }
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
  }
  
  .filter-group {
    flex-direction: column;
  }
  
  .filter-item {
    width: 100%;
  }
  
  .documents-grid {
    grid-template-columns: 1fr;
  }
  
  .table-view {
    overflow-x: auto;
  }
  
  .documents-table {
    min-width: 700px;
  }
  
  .modal-content {
    width: 95%;
    margin: 20px;
    max-height: 95vh;
  }
  
  .modal-header {
    padding: 15px 20px;
  }
  
  .modal-body {
    padding: 10px;
  }
  
  .detail-row {
    flex-direction: column;
    gap: 8px;
  }
  
  .detail-row label {
    min-width: auto;
  }
  
  .modal-actions {
    flex-direction: column;
    gap: 10px;
  }
  
  .btn-download,
  .btn-view-external {
    width: 100%;
    justify-content: center;
  }
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.document-card,
.table-row {
  animation: fadeIn 0.3s ease-out;
}

/* Custom scrollbar for modal */
.modal-body::-webkit-scrollbar {
  width: 6px;
}

.modal-body::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>