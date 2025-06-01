<template>
  <div class="container-fluid py-4">
    <!-- Header section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 fw-bold mb-1">{{ title }}</h1>
        <p class="text-secondary mb-0">{{ subtitle }}</p>
      </div>
      <a-button v-if="showAddButton" type="primary" size="large" @click="emit('add-template')">
        <template #icon>
          <PlusOutlined />
        </template>
        Thêm Mẫu Mới
      </a-button>
    </div>

    <!-- Advanced Filter Section -->
    <div class="filter-section">
      <div class="row g-3">
        <!-- Search -->
        <div class="col-md-4">
          <a-input-search 
            v-model:value="searchText"
            placeholder="Tìm kiếm mẫu văn bản..." 
            size="large"
            allow-clear 
          />
        </div>
        
        <!-- Filter buttons -->
        <div class="col-md-8">
          <div class="d-flex gap-2 flex-wrap align-items-center">
            <!-- View mode toggle -->
            <div class="view-toggle">
              <a-button 
                :type="viewMode === 'grid' ? 'primary' : 'default'"
                @click="viewMode = 'grid'"
              >
                <AppstoreOutlined />
              </a-button>
              <a-button 
                :type="viewMode === 'table' ? 'primary' : 'default'"
                @click="viewMode = 'table'"
              >
                <TableOutlined />
              </a-button>
            </div>

            <!-- Official filter -->
            <a-select 
              v-model:value="selectedFilters.official" 
              placeholder="Tất cả loại"
              style="width: 150px"
              allow-clear
            >
              <a-select-option :value="true">Chính thức</a-select-option>
              <a-select-option :value="false">Không chính thức</a-select-option>
            </a-select>

            <!-- Sort filter -->
            <a-select 
              v-model:value="selectedFilters.sortBy" 
              style="width: 150px"
            >
              <a-select-option value="newest">Mới nhất</a-select-option>
              <a-select-option value="oldest">Cũ nhất</a-select-option>
              <a-select-option value="az">A → Z</a-select-option>
              <a-select-option value="za">Z → A</a-select-option>
              <a-select-option value="mostUsed">Dùng nhiều nhất</a-select-option>
            </a-select>

            <!-- Clear filters -->
            <a-button v-if="activeFilterCount > 0" @click="clearFilters">
              Xóa bộ lọc ({{ activeFilterCount }})
            </a-button>
          </div>
        </div>
      </div>

      <!-- Active filters display -->
      <div v-if="activeFilterCount > 0 || searchText" class="mt-3">
        <span class="filter-tag" v-if="searchText">
          Tìm kiếm: "{{ searchText }}"
          <CloseOutlined class="filter-tag-close" @click="searchText = ''" />
        </span>
        <span class="filter-tag" v-if="selectedFilters.official !== null">
          {{ selectedFilters.official ? 'Chính thức' : 'Không chính thức' }}
          <CloseOutlined class="filter-tag-close" @click="removeFilter('official')" />
        </span>
        <span class="filter-tag" v-if="selectedFilters.sortBy !== 'newest'">
          Sắp xếp: {{ sortByLabels[selectedFilters.sortBy] }}
          <CloseOutlined class="filter-tag-close" @click="removeFilter('sortBy')" />
        </span>
      </div>
    </div>

    <!-- Grid View -->
    <div v-if="viewMode === 'grid'" class="row g-4">
      <div class="col-md-6 col-lg-4" v-for="template in paginatedTemplates" :key="template.id">
        <div class="template-card">
          <!-- Title and meta -->
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="flex-grow-1">
              <h5 class="fw-semibold mb-1">{{ template.title }}</h5>
              <small class="text-muted">Cập nhật: {{ template.updated }}</small>
            </div>
            <a-tag :color="template.isOfficial ? 'blue' : 'green'">
              {{ template.isOfficial ? 'Chính thức' : 'Không chính thức' }}
            </a-tag>
          </div>

          <!-- Icon Area -->
          <div class="template-icon-area">
            <FileTextOutlined class="template-icon" />
          </div>

          <!-- Description -->
          <p class="description-truncate">{{ template.description }}</p>

          <!-- Actions -->
          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex gap-2">
              <a-button shape="circle" @click="emit('toggle-favorite', template)">
                <template #icon>
                  <StarOutlined v-if="!template.isFavorite" />
                  <StarFilled v-else class="text-warning" />
                </template>
              </a-button>
              <a-button shape="circle" @click="emit('view-template', template)">
                <template #icon>
                  <EyeOutlined />
                </template>
              </a-button>
            </div>
            <div class="d-flex align-items-center gap-3">
              <small class="text-muted">Đã dùng: {{ template.used }}</small>
              <a-button type="primary" size="small" @click="emit('create-from-template', template)">
                Sử dụng
              </a-button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table View -->
    <div v-else class="table-view">
      <a-table 
        :columns="tableColumns" 
        :data-source="paginatedTemplates"
        :pagination="false"
        :row-key="record => record.id"
      >
        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'title'">
            <div>
              <div class="fw-semibold">{{ record.title }}</div>
              <small class="text-muted">Cập nhật: {{ record.updated }}</small>
            </div>
          </template>
          <template v-else-if="column.key === 'isOfficial'">
            <a-tag :color="record.isOfficial ? 'blue' : 'green'">
              {{ record.isOfficial ? 'Chính thức' : 'Không chính thức' }}
            </a-tag>
          </template>
          <template v-else-if="column.key === 'action'">
            <div class="d-flex gap-2 justify-content-center">
              <a-button size="small" shape="circle" @click="emit('toggle-favorite', record)">
                <template #icon>
                  <StarOutlined v-if="!record.isFavorite" />
                  <StarFilled v-else class="text-warning" />
                </template>
              </a-button>
              <a-button size="small" shape="circle" @click="emit('view-template', record)">
                <template #icon>
                  <EyeOutlined />
                </template>
              </a-button>
              <a-button size="small" type="primary" @click="emit('create-from-template', record)">
                Sử dụng
              </a-button>
            </div>
          </template>
        </template>
      </a-table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <a-pagination 
        v-model:current="pagination.current" 
        :total="filteredTemplates.length"
        :page-size="pagination.pageSize"
        :page-size-options="['6', '12', '24', '48']"
        show-size-changer
        show-quick-jumper
        @change="handlePageChange"
      />
      <div class="text-muted">
        Hiển thị {{ (pagination.current - 1) * pagination.pageSize + 1 }} - 
        {{ Math.min(pagination.current * pagination.pageSize, filteredTemplates.length) }} 
        trong tổng số {{ filteredTemplates.length }} mẫu
      </div>
    </div>

    <!-- Recent Templates Section -->
    <div v-if="showRecent && recentDocuments.length > 0" class="mt-5">
      <a-divider />
      <h2 class="h4 fw-semibold mb-4">Mẫu Văn Bản Gần Đây</h2>
      <div class="row g-3">
        <div class="col-md-6" v-for="recentDoc in recentDocuments" :key="recentDoc.id">
          <div class="recent-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <h5 class="fw-semibold mb-0">{{ recentDoc.title }}</h5>
              <a-tag color="green">Đã sử dụng</a-tag>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <ClockCircleOutlined class="recent-icon me-3" />
                <div>
                  <p class="mb-0 small">Sử dụng lần cuối: {{ recentDoc.lastUsed }}</p>
                  <p class="mb-0 small text-secondary">Đã tạo {{ recentDoc.createdCount }} văn bản</p>
                </div>
              </div>
              <a-button type="primary" @click="emit('create-from-template', recentDoc)">
                <template #icon>
                  <FileAddOutlined />
                </template>
                Tạo mới
              </a-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, watch } from 'vue';
import { 
  PlusOutlined, 
  AppstoreOutlined, 
  TableOutlined,
  StarOutlined,
  StarFilled,
  EyeOutlined,
  FileTextOutlined,
  CloseOutlined,
  ClockCircleOutlined,
  FileAddOutlined
} from '@ant-design/icons-vue';

// Props
const props = defineProps({
  title: {
    type: String,
    default: 'Mẫu Văn Bản'
  },
  subtitle: {
    type: String,
    default: 'Sử dụng và quản lý các mẫu văn bản để tạo văn bản nhanh chóng'
  },
  templates: {
    type: Array,
    required: true
  },
  recentDocuments: {
    type: Array,
    default: () => []
  },
  showRecent: {
    type: Boolean,
    default: true
  },
  showAddButton: {
    type: Boolean,
    default: true
  },
  defaultPageSize: {
    type: Number,
    default: 6
  }
});

// Emits
const emit = defineEmits(['add-template', 'toggle-favorite', 'view-template', 'create-from-template']);

// State
const searchText = ref('');
const viewMode = ref('grid'); // 'grid' or 'table'
const selectedFilters = reactive({
  official: null,
  sortBy: 'newest',
  category: null
});

const pagination = ref({
  current: 1,
  pageSize: props.defaultPageSize
});

// Constants
const sortByLabels = {
  newest: 'Mới nhất',
  oldest: 'Cũ nhất',
  az: 'A→Z',
  za: 'Z→A',
  mostUsed: 'Dùng nhiều'
};

// Computed
const filteredTemplates = computed(() => {
  let result = [...props.templates];
  
  // Search filter
  if (searchText.value) {
    const search = searchText.value.toLowerCase();
    result = result.filter(t => 
      t.title.toLowerCase().includes(search) ||
      t.description.toLowerCase().includes(search)
    );
  }
  
  // Official filter
  if (selectedFilters.official !== null) {
    result = result.filter(t => t.isOfficial === selectedFilters.official);
  }
  
  // Sort
  switch (selectedFilters.sortBy) {
    case 'newest':
      result.sort((a, b) => new Date(b.updated.split('/').reverse().join('-')) - new Date(a.updated.split('/').reverse().join('-')));
      break;
    case 'oldest':
      result.sort((a, b) => new Date(a.updated.split('/').reverse().join('-')) - new Date(b.updated.split('/').reverse().join('-')));
      break;
    case 'az':
      result.sort((a, b) => a.title.localeCompare(b.title, 'vi'));
      break;
    case 'za':
      result.sort((a, b) => b.title.localeCompare(a.title, 'vi'));
      break;
    case 'mostUsed':
      result.sort((a, b) => b.used - a.used);
      break;
  }
  
  return result;
});

const paginatedTemplates = computed(() => {
  const start = (pagination.value.current - 1) * pagination.value.pageSize;
  const end = start + pagination.value.pageSize;
  return filteredTemplates.value.slice(start, end);
});

const activeFilterCount = computed(() => {
  let count = 0;
  if (selectedFilters.official !== null) count++;
  if (selectedFilters.sortBy !== 'newest') count++;
  if (selectedFilters.category) count++;
  return count;
});

// Table columns configuration
const tableColumns = [
  {
    title: 'Tên mẫu',
    dataIndex: 'title',
    key: 'title',
    sorter: true
  },
  {
    title: 'Mô tả',
    dataIndex: 'description',
    key: 'description',
    ellipsis: true
  },
  {
    title: 'Loại',
    dataIndex: 'isOfficial',
    key: 'isOfficial',
    width: 120,
    filters: [
      { text: 'Chính thức', value: true },
      { text: 'Không chính thức', value: false }
    ]
  },
  {
    title: 'Đã dùng',
    dataIndex: 'used',
    key: 'used',
    width: 100,
    sorter: true,
    align: 'center'
  },
  {
    title: 'Thao tác',
    key: 'action',
    width: 150,
    align: 'center'
  }
];

// Methods
const handlePageChange = (page, pageSize) => {
  pagination.value.current = page;
  if (pageSize) pagination.value.pageSize = pageSize;
};

const clearFilters = () => {
  selectedFilters.official = null;
  selectedFilters.sortBy = 'newest';
  selectedFilters.category = null;
  searchText.value = '';
};

const removeFilter = (filterType) => {
  if (filterType === 'official') selectedFilters.official = null;
  if (filterType === 'sortBy') selectedFilters.sortBy = 'newest';
  if (filterType === 'category') selectedFilters.category = null;
};

// Watch for filter changes to reset pagination
watch([searchText, selectedFilters], () => {
  pagination.value.current = 1;
});
</script>

<style scoped>
.template-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  height: 100%;
  transition: all 0.3s ease;
  border: 1px solid #e8e8e8;
}

.template-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.template-icon-area {
  background: #f7f9fb;
  border-radius: 8px;
  padding: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 16px 0;
}

.template-icon {
  font-size: 48px;
  color: #8c8c8c;
}

.description-truncate {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #666;
  font-size: 14px;
  line-height: 1.5;
  min-height: 42px;
}

.filter-section {
  background: white;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.view-toggle {
  display: flex;
  gap: 8px;
}

.table-view {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

:deep(.ant-table-wrapper) {
  background: white;
}

.recent-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.3s ease;
  border: 1px solid #e8e8e8;
}

.recent-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.recent-icon {
  font-size: 24px;
  color: #666;
}

.filter-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  background: #f0f2f5;
  border-radius: 20px;
  font-size: 14px;
  margin-right: 8px;
  margin-bottom: 8px;
}

.filter-tag-close {
  cursor: pointer;
  color: #666;
  font-size: 12px;
}

.filter-tag-close:hover {
  color: #ff4d4f;
}

.text-warning {
  color: #faad14 !important;
}
</style>