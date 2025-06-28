<template>
  <div class="digital-signature-manager">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">
        <i class="bi bi-shield-check me-2"></i>
        Quản lý chữ ký số
      </h2>
      <div>
        <a-button 
          type="primary" 
          @click="showCreateModal"
          class="me-2"
        >
          <i class="bi bi-plus-circle me-1"></i>
          Xin cấp mới chữ ký
        </a-button>
        <!-- <a-button 
          type="default" 
          @click="showRenewModal"
          :disabled="selectedSignatures.length === 0"
        >
          <i class="bi bi-arrow-repeat me-1"></i>
          Gia Hạn Chữ Ký
        </a-button> -->
      </div>
    </div>

    <!-- Statistics Cards -->
    <a-row :gutter="16" class="mb-4">
      <a-col :span="6">
        <a-card>
          <a-statistic
            title="Tổng Chữ Ký"
            :value="statistics.total"
            prefix-icon="bi bi-file-earmark-text"
          >
            <template #prefix>
              <i class="bi bi-file-earmark-text text-primary"></i>
            </template>
          </a-statistic>
        </a-card>
      </a-col>
      <a-col :span="6">
        <a-card>
          <a-statistic
            title="Đang Hoạt Động"
            :value="statistics.active"
            value-style="color: #3f8600"
          >
            <template #prefix>
              <i class="bi bi-check-circle text-success"></i>
            </template>
          </a-statistic>
        </a-card>
      </a-col>
      <a-col :span="6">
        <a-card>
          <a-statistic
            title="Sắp Hết Hạn"
            :value="statistics.expiring"
            value-style="color: #cf1322"
          >
            <template #prefix>
              <i class="bi bi-exclamation-triangle text-warning"></i>
            </template>
          </a-statistic>
        </a-card>
      </a-col>
      <a-col :span="6">
        <a-card>
          <a-statistic
            title="Đã Hết Hạn"
            :value="statistics.expired"
            value-style="color: #8c8c8c"
          >
            <template #prefix>
              <i class="bi bi-x-circle text-danger"></i>
            </template>
          </a-statistic>
        </a-card>
      </a-col>
    </a-row>

    <!-- Filter and Search -->
    <div class="mb-3">
      <a-row :gutter="16" align="middle">
        <a-col :span="6">
          <a-select
            v-model:value="filterStatus"
            placeholder="Lọc theo trạng thái"
            style="width: 100%"
            @change="handleFilterChange"
          >
            <a-select-option value="">Tất cả</a-select-option>
            <a-select-option value="active">Hoạt động</a-select-option>
            <a-select-option value="expiring">Sắp hết hạn</a-select-option>
            <a-select-option value="expired">Đã hết hạn</a-select-option>
          </a-select>
        </a-col>
        <a-col :span="8">
          <a-input-search
            v-model:value="searchText"
            placeholder="Tìm kiếm theo tên hoặc serial"
            @search="handleSearch"
            allow-clear
          />
        </a-col>
        <a-col :span="4">
          <a-button @click="refreshData">
            <i class="bi bi-arrow-clockwise me-1"></i>
            Làm mới
          </a-button>
        </a-col>
      </a-row>
    </div>

    <!-- Digital Signatures Table -->
    <a-table
      :columns="columns"
      :data-source="filteredSignatures"
      :loading="loading"
      :pagination="pagination"
      :row-selection="rowSelection"
      row-key="id"
      @change="handleTableChange"
    >
      <template #bodyCell="{ column, record, index }">
        <template v-if="column.key === 'id'">
          <span>{{ index + 1 }}</span>
        </template>

        <template v-if="column.key === 'status'">
          <a-tag :color="getStatusColor(record.status)">
            <i :class="getStatusIcon(record.status)" class="me-1"></i>
            {{ getStatusText(record.status) }}
          </a-tag>
        </template>
        
        <template v-if="column.key === 'expiryDate'">
          <span :class="getExpiryClass(record.expiryDate)">
            {{ record.expiryDate }}
          </span>
        </template>
        
        <template v-if="column.key === 'actions'">
          <a-space>
            <a-button 
              type="link" 
              size="small" 
              @click="viewDetails(record)"
            >
              <i class="bi bi-eye"></i>
            </a-button>
            <!-- <a-button 
              type="link" 
              size="small" 
              @click="downloadCertificate(record)"
              :disabled="record.status === 'expired'"
            >
              <i class="bi bi-download"></i>
            </a-button> -->
            <a-button 
              type="link" 
              size="small" 
              @click="renewSignature(record)"
              :disabled="record.status === 'active'"
            >
              <i class="bi bi-arrow-repeat"></i>
            </a-button>
            <a-popconfirm
              title="Bạn có chắc chắn muốn thu hồi chữ ký này?"
              @confirm="revokeSignature(record)"
              placement="topRight"
            >
              <a-button 
                type="link" 
                size="small" 
                danger
                :disabled="record.status === 'expired'"
              >
                <i class="bi bi-trash"></i>
              </a-button>
            </a-popconfirm>
          </a-space>
        </template>
      </template>
    </a-table>

    <!-- Create Modal -->
    <a-modal
      v-model:open="createModalVisible"
      title="Xin Cấp Mới Chữ Ký Số"
      width="350px"
      @ok="handleCreateSignature"
      :confirm-loading="createLoading"
      z-index="10000"
    >
      <a-form
        ref="createFormRef"
        :model="createForm"
        :rules="createFormRules"
        layout="vertical"
      >
        <!-- <a-form-item label="Tên chữ ký" name="name">
          <a-input v-model:value="createForm.name" placeholder="Nhập tên chữ ký" />
        </a-form-item>
        
        <a-form-item label="Loại chứng thư" name="type">
          <a-select v-model:value="createForm.type" placeholder="Chọn loại chứng thư">
            <a-select-option value="personal">Cá nhân</a-select-option>
            <a-select-option value="organization">Tổ chức</a-select-option>
          </a-select>
        </a-form-item> -->
        
        <a-form-item label="Thời hạn (năm)" name="validityPeriod">
          <a-select 
            v-model:value="createForm.validityPeriod" 
            placeholder="Chọn thời hạn"
            :dropdown-style="{ 
              position: 'absolute',
              zIndex: 10000,
              background: 'white',
              border: '1px solid #ccc'
              }"
            >
            <a-select-option :value="1">1 năm</a-select-option>
            <a-select-option :value="2">2 năm</a-select-option>
            <a-select-option :value="3">3 năm</a-select-option>
          </a-select>
        </a-form-item>
        
        <!-- <a-form-item label="Mô tả" name="description">
          <a-textarea 
            v-model:value="createForm.description" 
            placeholder="Nhập mô tả (tùy chọn)"
            :rows="3"
          />
        </a-form-item> -->
      </a-form>
      <template #footer>
        <a-button @click="createModalVisible = false">Đóng</a-button>
        <a-button 
          type="primary" 
          @click="handleCreateSignature"
          :loading="createLoading"
        >
          Xin Cấp Mới
        </a-button>
      </template>

    </a-modal>

    <!-- Renew Modal -->
    <a-modal
      v-model:open="renewModalVisible"
      title="Gia Hạn Chữ Ký Số"
      width="500px"
      @ok="handleRenewSignature"
      :confirm-loading="renewLoading"
    >
      <div class="mb-3">
        <p><strong>Chữ ký được chọn:</strong></p>
        <ul>
          <li v-for="sig in selectedSignatureDetails" :key="sig.id">
            {{ sig.name }} - {{ sig.serialNumber }}
          </li>
        </ul>
      </div>
      
      <a-form
        ref="renewFormRef"
        :model="renewForm"
        :rules="renewFormRules"
        layout="vertical"
      >
        <a-form-item label="Thời gian gia hạn (năm)" name="extensionPeriod">
          <a-select v-model:value="renewForm.extensionPeriod" placeholder="Chọn thời gian gia hạn">
            <a-select-option :value="1">1 năm</a-select-option>
            <a-select-option :value="2">2 năm</a-select-option>
            <a-select-option :value="3">3 năm</a-select-option>
          </a-select>
        </a-form-item>
      </a-form>
    </a-modal>

    <!-- Detail Modal -->
    <a-modal
      v-model:open="modalDetailVisible"
      title="Chi Tiết Chữ Ký Số"
      width="600px"
      @cancel="modalDetailVisible = false"
      z-index="10000"
    >
      <div v-if="selectedSignature">
        <p><strong>Người sở hữu:</strong> {{ selectedSignature.user.name }} - {{ selectedSignature.user.position_title }}</p>
        <p><strong>Trạng thái: </strong> 
          <a-tag :color="getStatusColor(selectedSignature.status)">
            <i :class="getStatusIcon(selectedSignature.status)" class="me-1"></i>
            {{ getStatusText(selectedSignature.status) }}
          </a-tag>
        </p>
        <p><strong>Số lượt ký:</strong> {{ selectedSignature.used_count }}</p>
        <p><strong>Ngày cấp:</strong> {{ (selectedSignature.issueDate) }}</p>
        <p><strong>Ngày hết hạn:</strong> {{ selectedSignature.expiryDate }}</p>  
      </div>
      <div v-else>
        <p>Không có thông tin chi tiết.</p>
      </div>

      <template #footer>
        <a-button type="primary" @click="modalDetailVisible = false">Đóng</a-button>
      </template>
    </a-modal>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, computed, onMounted } from 'vue'
import { message } from 'ant-design-vue'
import { useCertificateStore } from '@/stores/creator/certificate-store';
import { useAuth } from '@/stores/use-auth.js';

export default defineComponent({
  name: 'CertificateUser',
  setup() {
    const certificateStore = useCertificateStore();
    const auth = useAuth();

    // Reactive data
    const loading = ref(false)
    const searchText = ref('')
    const filterStatus = ref('')
    const selectedSignatures = ref([])
    
    // Modal states
    const createModalVisible = ref(false)
    const renewModalVisible = ref(false)
    const createLoading = ref(false)
    const renewLoading = ref(false)

    // Form refs
    const createFormRef = ref()
    const renewFormRef = ref()

    // Sample data
    const signatures = ref([]);
    const currentSignature = ref(null);

    onMounted(async () => {
      // Fetch signatures from store or API
      await certificateStore.fetchCertificates(false, auth.user.id);
      
      signatures.value = certificateStore.certificates.map(cert => ({
        id: cert.id,
        name: cert.user.name,
        status: cert.status,
        issueDate: cert.issued_at,
        expiryDate: cert.expires_at,
        public_key: cert.public_key,
        certificate: cert.certificate,
        private_key: cert.private_key,
        used_count: cert.used_count,
        user: cert.user,
      }));

      currentSignature.value = signatures.value[0] || null;
    });

    // Statistics
    const statistics = computed(() => {
      const total = signatures.value.length
      const active = signatures.value.filter(s => s.status === 'active').length
      const revoked = signatures.value.filter(s => s.status === 'revoked').length
      const requestNew = signatures.value.filter(s => s.status === 'request_new').length
      const requestRenewal = signatures.value.filter(s => s.status === 'request_renewal').length
      const expiring = signatures.value.filter(s => s.status === 'expiring').length
      const expired = signatures.value.filter(s => s.status === 'expired').length
      
      return { total, active, expiring, expired, revoked, requestNew, requestRenewal }
    })

    // Table columns
    const columns = [
      {
        title: 'STT',
        dataIndex: 'id',
        key: 'id',
        sorter: true,
        width: 50,
        align: 'center',
      },
      {
        title: 'Trạng thái',
        key: 'status',
        dataIndex: 'status',
        align: 'center',
        width: 150,
      },
      {
        title: 'Ngày cấp',
        dataIndex: 'issueDate',
        key: 'issueDate',
        render: (date) => formatDate(date),
        align: 'center',
        width: 180,
      },
      {
        title: 'Ngày hết hạn',
        key: 'expiryDate',
        dataIndex: 'expiryDate',
        render: (date) => formatDate(date),
        align: 'center',
        width: 180,
      },
      {
        title: 'Thao tác',
        key: 'actions',
        width: 150,
        align: 'center',
      }
    ]

    // Pagination
    const pagination = reactive({
      current: 1,
      pageSize: 10,
      total: 0,
      showSizeChanger: true,
      showQuickJumper: true
    })

    // Row selection
    const rowSelection = {
      selectedRowKeys: selectedSignatures,
      onChange: (selectedRowKeys) => {
        selectedSignatures.value = selectedRowKeys
      }
    }

    // Forms
    const createForm = reactive({
      name: '',
      type: '',
      validityPeriod: 1,
      description: ''
    })

    const renewForm = reactive({
      extensionPeriod: 1
    })

    // Form rules
    const createFormRules = {
      name: [
        { required: true, message: 'Vui lòng nhập tên chữ ký', trigger: 'blur' }
      ],
      validityPeriod: [
        { required: true, message: 'Vui lòng chọn thời hạn', trigger: 'change' }
      ]
    }

    const renewFormRules = {
      extensionPeriod: [
        { required: true, message: 'Vui lòng chọn thời gian gia hạn', trigger: 'change' }
      ]
    }

    // Computed
    const filteredSignatures = computed(() => {
      let result = signatures.value

      if (filterStatus.value) {
        result = result.filter(s => s.status === filterStatus.value)
      }

      if (searchText.value) {
        result = result.filter(s => 
          s.name.toLowerCase().includes(searchText.value.toLowerCase()) ||
          s.serialNumber.toLowerCase().includes(searchText.value.toLowerCase())
        )
      }

      pagination.total = result.length
      return result
    })

    const selectedSignatureDetails = computed(() => {
      return signatures.value.filter(s => selectedSignatures.value.includes(s.id))
    })

    // Methods
    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('vi-VN')
    }

    const getStatusColor = (status) => {
      const colors = {
        active: 'green',
        expiring: 'orange',
        expired: 'red',
        revoked: 'grey',
        request_renewal: 'blue',
        request_new: 'purple'
      }
      return colors[status] || 'default'
    }

    const getStatusIcon = (status) => {
      const icons = {
        active: 'bi bi-check-circle',
        expiring: 'bi bi-exclamation-triangle',
        expired: 'bi bi-x-circle',
        revoked: 'bi bi-trash',
        request_renewal: 'bi bi-arrow-repeat',
        request_new: 'bi bi-plus-circle'
      }
      return icons[status] || 'bi bi-question-circle'
    }

    const getStatusText = (status) => {
      const texts = {
        active: 'Hoạt động',
        expiring: 'Sắp hết hạn',
        revoked: 'Bị thu hồi',
        expired: 'Đã hết hạn',
        request_renewal: 'Yêu cầu gia hạn',
        request_new: 'Yêu cầu cấp mới'
      }
      return texts[status] || 'Không xác định'
    }

    const getExpiryClass = async (expiryDate) => {
      const today = new Date()
      const expiry = new Date(expiryDate)
      const diffTime = expiry - today
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

      if (diffDays < 0) 
      { 
        if (currentSignature.status !== 'expired') {
          await certificateStore.changeStatus(currentSignature.id, 'expired');
          signatures.value = signatures.value.map(sig => {
            if (sig.id === currentSignature.id) {
              sig.status = 'expired';
            }
            return sig;
          });
        }
        return 'text-danger'
      }  
      if (diffDays <= 30) {
        if (currentSignature.status !== 'expiring') {
          await certificateStore.changeStatus(currentSignature.id, 'expiring');
        }
        return 'text-warning'
      } 
      return 'text-success'
    }

    const handleFilterChange = () => {
      pagination.current = 1
    }

    const handleSearch = () => {
      pagination.current = 1
    }

    const handleTableChange = (pag) => {
      pagination.current = pag.current
      pagination.pageSize = pag.pageSize
    }

    const refreshData = () => {
      loading.value = true
      setTimeout(() => {
        loading.value = false
        message.success('Dữ liệu đã được cập nhật')
      }, 1000)
    }

    const showCreateModal = () => {
      createModalVisible.value = true
    }

    const showRenewModal = () => {
      if (selectedSignatures.value.length === 0) {
        message.warning('Vui lòng chọn ít nhất một chữ ký để gia hạn')
        return
      }
      renewModalVisible.value = true
    }

    const handleCreateSignature = async () => {
      try {
        await createFormRef.value.validate()
        createLoading.value = true
        
        // Simulate API call
        setTimeout(() => {
          const newSignature = {
            id: Date.now(),
            name: createForm.name,
            serialNumber: `CKS${Date.now()}`,
            type: createForm.type,
            status: 'active',
            issueDate: new Date().toISOString().split('T')[0],
            expiryDate: new Date(Date.now() + createForm.validityPeriod * 365 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
            description: createForm.description
          }
          
          signatures.value.unshift(newSignature)
          createModalVisible.value = false
          createLoading.value = false
          
          // Reset form
          Object.keys(createForm).forEach(key => {
            createForm[key] = key === 'validityPeriod' ? 1 : ''
          })
          
          message.success('Tạo chữ ký số thành công!')
        }, 2000)
      } catch (error) {
        console.log('Validation failed:', error)
      }
    }

    const handleRenewSignature = async () => {
      try {
        await renewFormRef.value.validate()
        renewLoading.value = true
        
        // Simulate API call
        setTimeout(() => {
          selectedSignatures.value.forEach(id => {
            const signature = signatures.value.find(s => s.id === id)
            if (signature) {
              const currentExpiry = new Date(signature.expiryDate)
              const newExpiry = new Date(currentExpiry.getTime() + renewForm.extensionPeriod * 365 * 24 * 60 * 60 * 1000)
              signature.expiryDate = newExpiry.toISOString().split('T')[0]
              signature.status = 'active'
            }
          })
          
          renewModalVisible.value = false
          renewLoading.value = false
          selectedSignatures.value = []
          
          // Reset form
          renewForm.extensionPeriod = 1
          
          message.success('Gia hạn chữ ký số thành công!')
        }, 2000)
      } catch (error) {
        console.log('Validation failed:', error)
      }
    }

    const modalDetailVisible = ref(false)
    const selectedSignature = ref(null)
    const viewDetails = (record) => {
      modalDetailVisible.value = true;
      selectedSignature.value = record;
    }

    const downloadCertificate = (record) => {
      message.success(`Tải xuống chứng thư: ${record.name}`)
    }

    const renewSignature = (record) => {
      selectedSignatures.value = [record.id]
      showRenewModal()
    }

    const revokeSignature = (record) => {
      record.status = 'expired'
      message.success(`Đã thu hồi chữ ký: ${record.name}`)
    }

    onMounted(() => {
      refreshData()
    })

    return {
      // Data
      loading,
      searchText,
      filterStatus,
      selectedSignatures,
      signatures,
      statistics,
      columns,
      pagination,
      rowSelection,
      
      // Modal states
      createModalVisible,
      renewModalVisible,
      modalDetailVisible,
      selectedSignature,
      createLoading,
      renewLoading,
      
      // Form refs
      createFormRef,
      renewFormRef,
      
      // Forms
      createForm,
      renewForm,
      createFormRules,
      renewFormRules,
      
      // Computed
      filteredSignatures,
      selectedSignatureDetails,
      
      // Methods
      formatDate,
      getStatusColor,
      getStatusIcon,
      getStatusText,
      getExpiryClass,
      handleFilterChange,
      handleSearch,
      handleTableChange,
      refreshData,
      showCreateModal,
      showRenewModal,
      handleCreateSignature,
      handleRenewSignature,
      viewDetails,
      downloadCertificate,
      renewSignature,
      revokeSignature
    }
  }
})
</script>

<style scoped>
.digital-signature-manager {
  padding: 20px;
}

.text-success {
  color: #52c41a !important;
}

.text-warning {
  color: #faad14 !important;
}

.text-danger {
  color: #ff4d4f !important;
}

.text-primary {
  color: #1890ff !important;
}
</style>