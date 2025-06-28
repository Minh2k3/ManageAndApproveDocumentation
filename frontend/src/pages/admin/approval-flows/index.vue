<template>
    <div class="document-flows-management">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Quản lý luồng phê duyệt mẫu</h1>
    </div>

    <!-- Add Flow Button -->
    <div class="mb-3 d-flex justify-content-end">
        <button class="btn btn-primary" @click="handleClickAddFlow">
            <i class="bi bi-plus-circle me-2"></i>
            Thêm luồng mới
        </button>
    </div>
            

    <!-- Search and Filter -->
    <div class="row mb-3">
        <div class="col-md-6">
            <a-input-search
            v-model:value="searchText"
            placeholder="Tìm kiếm theo tên luồng..."
            @search="handleSearch"
            allow-clear
            />
        </div>
        <div class="col-md-6">
            <a-select
            v-model:value="statusFilter"
            placeholder="Lọc theo trạng thái"
            style="width: 200px"
            @change="handleStatusFilter"
            >
            <a-select-option value="">Tất cả</a-select-option>
            <a-select-option value="1">Hoạt động</a-select-option>
            <a-select-option value="0">Không hoạt động</a-select-option>
            </a-select>
        </div>
    </div>

    <!-- Table -->
    <a-table
      :columns="columns"
      :data-source="filteredFlowsData"
      :loading="loading"
      :pagination="pagination"
      @change="handleTableChange"
      :scroll="{ x: 1000 }"
      row-key="id"
    >
      <template #bodyCell="{ column, record, index }">
        <!-- Tên luồng -->
        <template v-if="column.key === 'name'">
            <a-typography-text strong>{{ record.name }}</a-typography-text>
        </template>

        <!-- Mô tả -->
        <template v-else-if="column.key === 'description'">
            <a-typography-text>
                {{ record.description || 'Không có mô tả' }}
            </a-typography-text>
        </template>

        <!-- Người tạo -->
        <template v-else-if="column.key === 'created_by'">
            <a-tooltip>
                <span>{{ record.created_by.name || 'Không xác định' }}</span>
                <template #title>
                    <span>{{ record.created_by.position_title || 'Không xác định' }}</span>
                </template>
            </a-tooltip>
        </template>

        <!-- Số bước -->
        <template v-else-if="column.key === 'process'">
            <a-badge 
                :count="record.process || 0" 
                :number-style="{ backgroundColor: '#0099bf' }" 
                show-zero
            />
        </template>

        <!-- Ngày tạo -->
        <template v-else-if="column.key === 'created_at'">
            <span>{{ formatDate(record.created_at) }}</span>
        </template>

        <!-- Trạng thái -->
        <template v-else-if="column.key === 'is_active'">
            <a-tag :color="record.is_active ? 'success' : 'default'">
                {{ record.is_active ? 'Hoạt động' : 'Không hoạt động' }}
            </a-tag>
        </template>

        <!-- Thao tác -->
        <template v-else-if="column.key === 'action'">
            <a-space>
                <a-tooltip title="Xem chi tiết">
                    <a-button type="text" size="small" @click="viewFlow(record)">
                        <i class="bi bi-eye text-primary"></i>
                    </a-button>
                </a-tooltip>
            </a-space>
        </template>
      </template>
    </a-table>
  </div>
</template>

<script>
import { ref, reactive, onMounted, computed } from 'vue';
import { message } from 'ant-design-vue';
import { useRouter } from 'vue-router';
import { useMenu } from '@/stores/use-menu.js';
import { useDocumentStore } from '@/stores/admin/document-store.js';
import { useAuth } from '@/stores/use-auth.js';

export default {
  name: 'DocumentFlowsManagement',
  setup() {
    const router = useRouter();
    useMenu().onSelectedKeys(['admin-approval-flows']);
    const documentStore = useDocumentStore();
    const authStore = useAuth();
    
    // Reactive data
    const loading = ref(false);
    const flowsData = ref([]);
    const searchText = ref('');
    const statusFilter = ref('');

    // Computed filtered data
    const filteredFlowsData = computed(() => {
      let filtered = [...flowsData.value];
      
      // Search filter
      if (searchText.value) {
        const searchLower = searchText.value.toLowerCase();
        filtered = filtered.filter(flow => 
          flow.name?.toLowerCase().includes(searchLower) ||
          flow.description?.toLowerCase().includes(searchLower)
        );
      }
      
      // Status filter
      if (statusFilter.value !== '') {
        const isActive = statusFilter.value === '1';
        filtered = filtered.filter(flow => flow.is_active === isActive);
      }
      
      // Update pagination total
      pagination.total = filtered.length;
      
      return filtered;
    });

    // Fetch flows function
    const fetchFlows = async () => {
      try {
        loading.value = true;
        await documentStore.fetchDocumentFlowTemplates();
        flowsData.value = documentStore.document_flow_templates;
        console.log('Document Flow Templates:', flowsData.value);
      } catch (error) {
        console.error('Error fetching flows:', error);
        message.error('Không thể tải danh sách luồng phê duyệt');
      } finally {
        loading.value = false;
      }
    };

    onMounted(async() => {
      await fetchFlows();
    });

    // Pagination
    const pagination = reactive({
      current: 1,
      pageSize: 10,
      total: 0,
      showSizeChanger: true,
      showQuickJumper: true,
      showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} bản ghi`
    });

    // Table columns
    const columns = [
      {
        title: 'Tên luồng',
        dataIndex: 'name',
        key: 'name',
        sorter: true,
        ellipsis: true
      },
      {
        title: 'Mô tả',
        dataIndex: 'description',
        key: 'description',
        ellipsis: true,
        width: 200
      },
      {
        title: 'Người tạo',
        dataIndex: 'created_by',
        key: 'created_by',
        width: 120,
        align: 'center'
      },
      {
        title: 'Số bước',
        dataIndex: 'process',
        key: 'process',
        width: 80,
        align: 'center',
        sorter: true
      },
      {
        title: 'Trạng thái',
        dataIndex: 'is_active',
        key: 'is_active',
        width: 100,
        align: 'center'
      },
      {
        title: 'Ngày tạo',
        dataIndex: 'created_at',
        key: 'created_at',
        width: 120,
        align: 'center',
        sorter: true
      },
      {
        title: 'Thao tác',
        key: 'action',
        width: 180,
        align: 'center',
        fixed: 'right'
      }
    ];

    // Methods
    const handleSearch = (value) => {
      pagination.current = 1;
      // Filter logic is handled by computed property
    };

    const handleStatusFilter = (value) => {
      pagination.current = 1;
      // Filter logic is handled by computed property
    };

    const handleTableChange = (pag, filters, sorter) => {
      pagination.current = pag.current;
      pagination.pageSize = pag.pageSize;
      
      const params = {
        page: pag.current,
        pageSize: pag.pageSize,
        search: searchText.value,
        status: statusFilter.value
      };
      
      if (sorter.field) {
        params.sortField = sorter.field;
        params.sortOrder = sorter.order;
      }
    };

    const formatDate = (dateString) => {
      if (!dateString) return '';
      try {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
      } catch (error) {
        return '';
      }
    };

    const handleClickAddFlow = () => {
      router.push({ name: 'admin-approval-flows-create' });
    };

    const viewFlow = (record) => {
      router.push({ name: 'admin-approval-flows-detail', params: { id: record.id } });
    };


    return {
      loading,
      flowsData,
      filteredFlowsData,
      searchText,
      statusFilter,
      pagination,
      columns,
      fetchFlows,
      handleSearch,
      handleStatusFilter,
      handleTableChange,
      formatDate,
      handleClickAddFlow,
      viewFlow
    };
  }
};
</script>

<style scoped>
.document-flows-management {
  padding: 20px;
  background: #fff;
  border-radius: 8px;
}

.ant-table-tbody > tr > td {
  vertical-align: middle;
}

.bi {
  font-size: 16px;
}

.text-primary {
  color: #1890ff !important;
}

.text-warning {
  color: #faad14 !important;
}

.text-info {
  color: #13c2c2 !important;
}

.text-success {
  color: #52c41a !important;
}

@media (max-width: 768px) {
  .document-flows-management {
    padding: 15px;
  }
  
  .ant-table {
    font-size: 12px;
  }
  
  .bi {
    font-size: 14px;
  }
}
</style>