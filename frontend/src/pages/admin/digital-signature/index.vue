<template>
  <div class="admin-certificate-page">
    <!-- Page Header -->
    <div class="page-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h3 class="page-title">
            <SafetyOutlined class="me-2" />
            Quản lý chữ ký số tổ chức
          </h3>
          <p class="page-description">
            Quản lý chứng chỉ số của tổ chức và xử lý yêu cầu gia hạn
          </p>
        </div>
        <div class="header-actions">
          <a-button @click="showRenewalManagement = true">
            <template #icon>
              <BellOutlined />
            </template>
            Yêu cầu gia hạn ({{ pendingRenewalsCount }})
          </a-button>
          <a-button type="primary" @click="showUploadModal = true">
            <template #icon>
              <PlusOutlined />
            </template>
            Thêm chứng chỉ
          </a-button>
        </div>
      </div>
    </div>

    <!-- Admin Stats Dashboard -->
    <div class="admin-stats">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <a-card size="small" :bordered="false" class="stat-card total">
            <a-statistic
              title="Tổng chứng chỉ"
              :value="allCertificatesCount"
              :value-style="{ color: '#1890ff' }"
            >
              <template #prefix>
                <SafetyCertificateOutlined />
              </template>
            </a-statistic>
          </a-card>
        </div>
        <div class="col-lg-3 col-md-6">
          <a-card size="small" :bordered="false" class="stat-card active">
            <a-statistic
              title="Đang hoạt động"
              :value="activeCertificatesCount"
              :value-style="{ color: '#52c41a' }"
            >
              <template #prefix>
                <CheckCircleOutlined />
              </template>
            </a-statistic>
          </a-card>
        </div>
        <div class="col-lg-3 col-md-6">
          <a-card size="small" :bordered="false" class="stat-card warning">
            <a-statistic
              title="Cần chú ý"
              :value="warningCertificatesCount"
              :value-style="{ color: '#fa8c16' }"
            >
              <template #prefix>
                <ExclamationCircleOutlined />
              </template>
            </a-statistic>
          </a-card>
        </div>
        <div class="col-lg-3 col-md-6">
          <a-card size="small" :bordered="false" class="stat-card renewals">
            <a-statistic
              title="Yêu cầu gia hạn"
              :value="pendingRenewalsCount"
              :value-style="{ color: '#722ed1' }"
            >
              <template #prefix>
                <ReloadOutlined />
              </template>
            </a-statistic>
          </a-card>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <a-card title="Hành động nhanh" size="small">
        <div class="actions-grid">
          <a-button 
            type="dashed" 
            block 
            @click="showBulkOperations = true"
            :icon="h(SettingOutlined)"
          >
            Thao tác hàng loạt
          </a-button>
          <a-button 
            type="dashed" 
            block 
            @click="exportCertificates"
            :icon="h(ExportOutlined)"
          >
            Xuất báo cáo
          </a-button>
          <a-button 
            type="dashed" 
            block 
            @click="showAuditLog = true"
            :icon="h(AuditOutlined)"
          >
            Nhật ký audit
          </a-button>
          <a-button 
            type="dashed" 
            block 
            @click="showSystemSettings = true"
            :icon="h(ToolOutlined)"
          >
            Cài đặt hệ thống
          </a-button>
        </div>
      </a-card>
    </div>

    <!-- Advanced Filters -->
    <div class="filters-section">
      <a-card size="small" title="Bộ lọc nâng cao">
        <div class="row align-items-end">
          <div class="col-md-3">
            <a-form-item label="Tìm kiếm">
              <a-input-search
                v-model:value="searchText"
                placeholder="Tìm theo tên, serial..."
                @search="handleSearch"
                allow-clear
              >
                <template #prefix>
                  <SearchOutlined />
                </template>
              </a-input-search>
            </a-form-item>
          </div>
          <div class="col-md-2">
            <a-form-item label="Trạng thái">
              <a-select
                v-model:value="filterStatus"
                placeholder="Trạng thái"
                allow-clear
              >
                <a-select-option value="">Tất cả</a-select-option>
                <a-select-option value="active">Hoạt động</a-select-option>
                <a-select-option value="expired">Hết hạn</a-select-option>
                <a-select-option value="revoked">Thu hồi</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="col-md-2">
            <a-form-item label="Loại">
              <a-select
                v-model:value="filterType"
                placeholder="Loại"
                allow-clear
              >
                <a-select-option value="">Tất cả</a-select-option>
                <a-select-option value="personal">Cá nhân</a-select-option>
                <a-select-option value="organization">Tổ chức</a-select-option>
                <a-select-option value="ca_root">CA Root</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="col-md-2">
            <a-form-item label="Nhà phát hành">
              <a-select
                v-model:value="filterIssuer"
                placeholder="Nhà phát hành"
                allow-clear
              >
                <a-select-option value="">Tất cả</a-select-option>
                <a-select-option value="CA Trường ĐH Thủy Lợi">Trường ĐH Thủy Lợi</a-select-option>
                <a-select-option value="Self-signed">Self-signed</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="col-md-3">
            <a-form-item label="Khoảng thời gian">
              <a-range-picker
                v-model:value="dateRange"
                style="width: 100%"
                :placeholder="['Từ ngày', 'Đến ngày']"
              />
            </a-form-item>
          </div>
        </div>
      </a-card>
    </div>

    <!-- Certificates Table -->
    <div class="certificates-table">
      <a-card size="small">
        <template #title>
          <div class="d-flex justify-content-between align-items-center">
            <span>Danh sách chứng chỉ ({{ filteredCertificates.length }})</span>
            <div class="table-actions">
              <a-button-group size="small">
                <a-button 
                  :type="viewMode === 'table' ? 'primary' : 'default'"
                  @click="viewMode = 'table'"
                  :icon="h(TableOutlined)"
                >
                  Bảng
                </a-button>
                <a-button 
                  :type="viewMode === 'card' ? 'primary' : 'default'"
                  @click="viewMode = 'card'"
                  :icon="h(AppstoreOutlined)"
                >
                  Thẻ
                </a-button>
              </a-button-group>
            </div>
          </div>
        </template>

        <!-- Table View -->
        <div v-if="viewMode === 'table'">
          <a-table
            :columns="tableColumns"
            :data-source="filteredCertificates"
            :loading="loading"
            :pagination="tablePagination"
            :scroll="{ x: 1200 }"
            size="small"
            :row-selection="rowSelection"
            @change="handleTableChange"
          >
            <template #bodyCell="{ column, record }">
              <template v-if="column.key === 'name'">
                <div class="certificate-name-cell">
                  <a-avatar 
                    :style="{ backgroundColor: getCertificateColor(record.type) }" 
                    :icon="getCertificateIcon(record.type)"
                    size="small"
                    class="me-2"
                  />
                  <div>
                    <div class="font-weight-500">{{ record.name }}</div>
                    <small class="text-muted">{{ record.serialNumber }}</small>
                  </div>
                </div>
              </template>
              
              <template v-if="column.key === 'status'">
                <a-tag :color="getStatusColor(record.status)">
                  {{ getStatusText(record.status) }}
                </a-tag>
              </template>
              
              <template v-if="column.key === 'type'">
                <a-tag :color="getTypeColor(record.type)">
                  {{ getTypeText(record.type) }}
                </a-tag>
              </template>
              
              <template v-if="column.key === 'validity'">
                <div>
                  <div>{{ formatDate(record.validFrom) }}</div>
                  <div class="text-muted">{{ formatDate(record.validTo) }}</div>
                </div>
              </template>
              
              <template v-if="column.key === 'usage'">
                <a-statistic 
                  :value="record.usageCount" 
                  :value-style="{ fontSize: '14px' }"
                />
              </template>
              
              <template v-if="column.key === 'actions'">
                <a-dropdown :trigger="['click']">
                  <a-button type="text" size="small">
                    <template #icon>
                      <MoreOutlined />
                    </template>
                  </a-button>
                  <template #overlay>
                    <a-menu @click="({ key }) => handleMenuAction(key, record)">
                      <a-menu-item key="view" :icon="h(EyeOutlined)">
                        Xem chi tiết
                      </a-menu-item>
                      <a-menu-item key="verify" :icon="h(SafetyCertificateOutlined)">
                        Xác thực
                      </a-menu-item>
                      <a-menu-item key="download" :icon="h(DownloadOutlined)">
                        Tải xuống
                      </a-menu-item>
                      <a-menu-divider />
                      <a-menu-item 
                        key="revoke" 
                        :icon="h(StopOutlined)"
                        v-if="record.status === 'active'"
                      >
                        Thu hồi
                      </a-menu-item>
                      <a-menu-item 
                        key="delete" 
                        :icon="h(DeleteOutlined)"
                        class="text-danger"
                      >
                        Xóa
                      </a-menu-item>
                    </a-menu>
                  </template>
                </a-dropdown>
              </template>
            </template>
          </a-table>
        </div>

        <!-- Card View -->
        <div v-else class="certificates-grid">
          <a-spin :spinning="loading">
            <div v-if="filteredCertificates.length === 0" class="empty-state">
              <a-empty
                description="Không tìm thấy chứng chỉ nào"
                :image="Empty.PRESENTED_IMAGE_SIMPLE"
              />
            </div>
            
            <div v-else class="row">
              <div 
                v-for="certificate in paginatedCertificates" 
                :key="certificate.id"
                class="col-lg-6 col-xl-4"
              >
                <CertificateCard
                  :certificate="certificate"
                  :verifying="verifyingId === certificate.id"
                  @view="handleViewCertificate"
                  @verify="handleVerifyCertificate"
                  @renew="handleRenewCertificate"
                  @download="handleDownloadCertificate"
                  @delete="handleDeleteCertificate"
                />
              </div>
            </div>
            
            <!-- Pagination for Card View -->
            <div class="text-center mt-4" v-if="filteredCertificates.length > 0">
              <a-pagination
                v-model:current="cardCurrentPage"
                v-model:page-size="cardPageSize"
                :total="filteredCertificates.length"
                :show-size-changer="true"
                :show-quick-jumper="true"
                :show-total="(total, range) => `${range[0]}-${range[1]} của ${total} chứng chỉ`"
              />
            </div>
          </a-spin>
        </div>
      </a-card>
    </div>

    <!-- Renewal Management Modal -->
    <a-modal
      v-model:open="showRenewalManagement"
      title="Quản lý yêu cầu gia hạn"
      :width="1000"
      :footer="null"
    >
      <AdminRenewalManagement />
    </a-modal>

    <!-- Upload Certificate Modal -->
    <a-modal
      v-model:open="showUploadModal"
      title="Thêm chứng chỉ mới"
      :width="600"
      @ok="handleUploadCertificate"
      @cancel="showUploadModal = false"
      :confirm-loading="uploading"
      ok-text="Tải lên"
      cancel-text="Hủy"
    >
      <CertificateUploadForm
        ref="uploadFormRef"
        :is-admin="true"
        @upload-success="handleUploadSuccess"
      />
    </a-modal>

    <!-- Certificate Detail Modal -->
    <a-modal
      v-model:open="showDetailModal"
      :title="`Chi tiết: ${selectedCertificate?.name}`"
      :width="900"
      :footer="null"
    >
      <CertificateDetailView
        v-if="selectedCertificate"
        :certificate="selectedCertificate"
        :is-admin="true"
      />
    </a-modal>

    <!-- Bulk Operations Modal -->
    <a-modal
      v-model:open="showBulkOperations"
      title="Thao tác hàng loạt"
      :width="600"
      @ok="executeBulkOperation"
      @cancel="showBulkOperations = false"
      :confirm-loading="bulkOperating"
    >
      <BulkOperationsForm
        :selected-certificates="selectedCertificates"
        @operation-complete="handleBulkComplete"
      />
    </a-modal>

    <!-- System Settings Modal -->
    <a-modal
      v-model:open="showSystemSettings"
      title="Cài đặt hệ thống"
      :width="800"
      :footer="null"
    >
      <SystemSettings />
    </a-modal>

    <!-- Audit Log Modal -->
    <a-modal
      v-model:open="showAuditLog"
      title="Nhật ký audit"
      :width="1200"
      :footer="null"
    >
      <AuditLogViewer />
    </a-modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, h } from 'vue'
import { message, Empty } from 'ant-design-vue'
import {
  SafetyOutlined,
  BellOutlined,
  PlusOutlined,
  SafetyCertificateOutlined,
  CheckCircleOutlined,
  ExclamationCircleOutlined,
  ReloadOutlined,
  SettingOutlined,
  ExportOutlined,
  AuditOutlined,
  ToolOutlined,
  SearchOutlined,
  TableOutlined,
  AppstoreOutlined,
  MoreOutlined,
  EyeOutlined,
  DownloadOutlined,
  StopOutlined,
  DeleteOutlined,
  UserOutlined,
  TeamOutlined
} from '@ant-design/icons-vue'
import { useCertificateStore } from '@/stores/certificateStore'

// Component imports (these would be separate files)
import CertificateCard from '@/components/CertificateCard.vue'
import CertificateUploadForm from '@/components/CertificateUploadForm.vue'
import CertificateDetailView from '@/components/CertificateDetailView.vue'
import AdminRenewalManagement from '@/components/AdminRenewalManagement.vue'
import BulkOperationsForm from '@/components/BulkOperationsForm.vue'
import SystemSettings from '@/components/SystemSettings.vue'
import AuditLogViewer from '@/components/AuditLogViewer.vue'

const certificateStore = useCertificateStore()

// Reactive state
const loading = ref(false)
const uploading = ref(false)
const bulkOperating = ref(false)
const verifyingId = ref(null)
const searchText = ref('')
const filterStatus = ref('')
const filterType = ref('')
const filterIssuer = ref('')
const dateRange = ref([])
const viewMode = ref('table')

// Pagination
const cardCurrentPage = ref(1)
const cardPageSize = ref(12)

// Modal states
const showRenewalManagement = ref(false)
const showUploadModal = ref(false)
const showDetailModal = ref(false)
const showBulkOperations = ref(false)
const showSystemSettings = ref(false)
const showAuditLog = ref(false)

// Selected items
const selectedCertificate = ref(null)
const selectedRowKeys = ref([])

// Refs
const uploadFormRef = ref()

// Computed properties
const allCertificatesCount = computed(() => certificateStore.certificates.length)
const activeCertificatesCount = computed(() => 
  certificateStore.certificates.filter(cert => cert.status === 'active').length
)
const warningCertificatesCount = computed(() => {
  const now = new Date()
  return certificateStore.certificates.filter(cert => {
    if (cert.status === 'expired' || cert.status === 'revoked') return true
    if (cert.status === 'active') {
      const expiry = new Date(cert.validTo)
      const diffTime = expiry - now
      const daysUntilExpiry = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
      return daysUntilExpiry <= 30
    }
    return false
  }).length
})
const pendingRenewalsCount = computed(() => certificateStore.pendingRenewals.length)

const filteredCertificates = computed(() => {
  let filtered = [...certificateStore.certificates]

  // Filter by search text
  if (searchText.value) {
    const search = searchText.value.toLowerCase()
    filtered = filtered.filter(cert =>
      cert.name.toLowerCase().includes(search) ||
      cert.issuer.toLowerCase().includes(search) ||
      cert.serialNumber.toLowerCase().includes(search)
    )
  }

  // Filter by status
  if (filterStatus.value) {
    filtered = filtered.filter(cert => cert.status === filterStatus.value)
  }

  // Filter by type
  if (filterType.value) {
    filtered = filtered.filter(cert => cert.type === filterType.value)
  }

  // Filter by issuer
  if (filterIssuer.value) {
    filtered = filtered.filter(cert => cert.issuer === filterIssuer.value)
  }

  // Filter by date range
  if (dateRange.value && dateRange.value.length === 2) {
    const [startDate, endDate] = dateRange.value
    filtered = filtered.filter(cert => {
      const certDate = new Date(cert.createdAt)
      return certDate >= startDate && certDate <= endDate
    })
  }

  return filtered
})

const paginatedCertificates = computed(() => {
  const start = (cardCurrentPage.value - 1) * cardPageSize.value
  const end = start + cardPageSize.value
  return filteredCertificates.value.slice(start, end)
})

const selectedCertificates = computed(() => 
  certificateStore.certificates.filter(cert => selectedRowKeys.value.includes(cert.id))
)

// Table configuration
const tableColumns = [
  {
    title: 'Chứng chỉ',
    key: 'name',
    dataIndex: 'name',
    width: 300,
    sorter: (a, b) => a.name.localeCompare(b.name),
  },
  {
    title: 'Trạng thái',
    key: 'status',
    dataIndex: 'status',
    width: 100,
    filters: [
      { text: 'Hoạt động', value: 'active' },
      { text: 'Hết hạn', value: 'expired' },
      { text: 'Thu hồi', value: 'revoked' },
    ],
  },
  {
    title: 'Loại',
    key: 'type',
    dataIndex: 'type',
    width: 120,
    filters: [
      { text: 'Cá nhân', value: 'personal' },
      { text: 'Tổ chức', value: 'organization' },
      { text: 'CA Root', value: 'ca_root' },
    ],
  },
  {
    title: 'Nhà phát hành',
    key: 'issuer',
    dataIndex: 'issuer',
    width: 200,
    ellipsis: true,
  },
  {
    title: 'Thời hạn',
    key: 'validity',
    width: 180,
    sorter: (a, b) => new Date(a.validTo) - new Date(b.validTo),
  },
  {
    title: 'Sử dụng',
    key: 'usage',
    dataIndex: 'usageCount',
    width: 100,
    sorter: (a, b) => a.usageCount - b.usageCount,
  },
  {
    title: 'Thao tác',
    key: 'actions',
    width: 100,
    fixed: 'right',
  },
]

const tablePagination = ref({
  current: 1,
  pageSize: 10,
  showSizeChanger: true,
  showQuickJumper: true,
  showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} chứng chỉ`,
})

const rowSelection = {
  selectedRowKeys: selectedRowKeys,
  onChange: (selectedKeys) => {
    selectedRowKeys.value = selectedKeys
  },
  getCheckboxProps: (record) => ({
    disabled: false,
    name: record.name,
  }),
}

// Methods
const getCertificateColor = (type) => {
  switch (type) {
    case 'personal': return '#1890ff'
    case 'organization': return '#52c41a'
    case 'ca_root': return '#722ed1'
    default: return '#d9d9d9'
  }
}

const getCertificateIcon = (type) => {
  switch (type) {
    case 'personal': return h(UserOutlined)
    case 'organization': return h(TeamOutlined)
    case 'ca_root': return h(SafetyOutlined)
    default: return h(SafetyCertificateOutlined)
  }
}

const getStatusColor = (status) => {
  switch (status) {
    case 'active': return 'green'
    case 'expired': return 'red'
    case 'revoked': return 'orange'
    default: return 'default'
  }
}

const getStatusText = (status) => {
  switch (status) {
    case 'active': return 'Hoạt động'
    case 'expired': return 'Hết hạn'
    case 'revoked': return 'Thu hồi'
    default: return 'Không xác định'
  }
}

const getTypeColor = (type) => {
  switch (type) {
    case 'personal': return 'blue'
    case 'organization': return 'green'
    case 'ca_root': return 'purple'
    default: return 'default'
  }
}

const getTypeText = (type) => {
  switch (type) {
    case 'personal': return 'Cá nhân'
    case 'organization': return 'Tổ chức'
    case 'ca_root': return 'CA Root'
    default: return 'Không xác định'
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('vi-VN')
}

const fetchData = async () => {
  loading.value = true
  try {
    await Promise.all([
      certificateStore.fetchCertificates(),
      certificateStore.fetchRenewalRequests()
    ])
  } catch (error) {
    message.error('Có lỗi xảy ra khi tải dữ liệu')
  } finally {
    loading.value = false
  }
}

const handleSearch = (value) => {
  searchText.value = value
}

const handleTableChange = (pagination, filters, sorter) => {
  tablePagination.value = pagination
}

const handleMenuAction = (key, record) => {
  selectedCertificate.value = record
  
  switch (key) {
    case 'view':
      handleViewCertificate(record)
      break
    case 'verify':
      handleVerifyCertificate(record)
      break
    case 'download':
      handleDownloadCertificate(record)
      break
    case 'revoke':
      handleRevokeCertificate(record)
      break
    case 'delete':
      handleDeleteCertificate(record)
      break
  }
}

const handleViewCertificate = (certificate) => {
  selectedCertificate.value = certificate
  showDetailModal.value = true
}

const handleVerifyCertificate = async (certificate) => {
  verifyingId.value = certificate.id
  try {
    const result = await certificateStore.verifyCertificate(certificate.id)
    if (result.valid) {
      message.success('Chứng chỉ hợp lệ')
    } else {
      message.warning('Chứng chỉ không hợp lệ')
    }
  } catch (error) {
    message.error('Có lỗi xảy ra khi xác thực chứng chỉ')
  } finally {
    verifyingId.value = null
  }
}

const handleDownloadCertificate = (certificate) => {
  message.success(`Đang tải xuống: ${certificate.name}`)
  // Implement download logic
}

const handleRevokeCertificate = (certificate) => {
  // Show revocation confirmation
  message.info('Chức năng thu hồi chứng chỉ')
}

const handleDeleteCertificate = (certificate) => {
  selectedCertificate.value = certificate
  showDetailModal.value = true
}

const handleRenewCertificate = (certificate) => {
  message.info('Admin có thể xử lý yêu cầu gia hạn trong phần quản lý')
}

const handleUploadCertificate = async () => {
  if (!uploadFormRef.value) return
  
  uploading.value = true
  try {
    const result = await uploadFormRef.value.submit()
    if (result.success) {
      message.success('Chứng chỉ đã được tải lên thành công!')
      showUploadModal.value = false
      await fetchData()
    }
  } catch (error) {
    message.error('Có lỗi xảy ra khi tải lên chứng chỉ')
  } finally {
    uploading.value = false
  }
}

const handleUploadSuccess = () => {
  showUploadModal.value = false
  fetchData()
}

const executeBulkOperation = async () => {
  if (selectedRowKeys.value.length === 0) {
    message.warning('Vui lòng chọn ít nhất một chứng chỉ')
    return
  }
  
  bulkOperating.value = true
  try {
    // Implement bulk operations logic
    await new Promise(resolve => setTimeout(resolve, 2000))
    message.success(`Đã thực hiện thao tác cho ${selectedRowKeys.value.length} chứng chỉ`)
    showBulkOperations.value = false
    selectedRowKeys.value = []
    await fetchData()
  } catch (error) {
    message.error('Có lỗi xảy ra khi thực hiện thao tác hàng loạt')
  } finally {
    bulkOperating.value = false
  }
}

const handleBulkComplete = () => {
  showBulkOperations.value = false
  selectedRowKeys.value = []
  fetchData()
}

const exportCertificates = () => {
  // Export certificates to CSV/Excel
  message.success('Đang xuất báo cáo...')
  
  const csvContent = [
    ['Tên', 'Loại', 'Trạng thái', 'Nhà phát hành', 'Ngày tạo', 'Hết hạn'],
    ...filteredCertificates.value.map(cert => [
      cert.name,
      getTypeText(cert.type),
      getStatusText(cert.status),
      cert.issuer,
      formatDate(cert.createdAt),
      formatDate(cert.validTo)
    ])
  ].map(row => row.join(',')).join('\n')
  
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  link.href = URL.createObjectURL(blob)
  link.download = `certificates_report_${new Date().toISOString().split('T')[0]}.csv`
  link.click()
}

// Lifecycle
onMounted(() => {
  fetchData()
})
</script>

<style scoped>
.admin-certificate-page {
  padding: 24px;
  background-color: #f5f5f5;
  min-height: 100vh;
}

.page-header {
  margin-bottom: 24px;
}

.page-title {
  margin-bottom: 8px;
  color: #262626;
  font-weight: 600;
  display: flex;
  align-items: center;
}

.page-description {
  margin-bottom: 0;
  color: #8c8c8c;
}

.header-actions {
  display: flex;
  gap: 12px;
}

.admin-stats {
  margin-bottom: 24px;
}

.stat-card {
  height: 100%;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.stat-card.total {
  background: linear-gradient(135deg, #e6f7ff 0%, #bae7ff 100%);
}

.stat-card.active {
  background: linear-gradient(135deg, #f6ffed 0%, #d9f7be 100%);
}

.stat-card.warning {
  background: linear-gradient(135deg, #fff7e6 0%, #ffd591 100%);
}

.stat-card.renewals {
  background: linear-gradient(135deg, #f9f0ff 0%, #d3adf7 100%);
}

.quick-actions {
  margin-bottom: 24px;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
}

.filters-section {
  margin-bottom: 24px;
}

.certificates-table {
  margin-bottom: 24px;
}

.certificate-name-cell {
  display: flex;
  align-items: center;
}

.font-weight-500 {
  font-weight: 500;
}

.text-muted {
  color: #8c8c8c;
}

.text-danger {
  color: #ff4d4f !important;
}

.certificates-grid {
  margin-top: 16px;
}

.empty-state {
  text-align: center;
  padding: 48px 24px;
}

.table-actions {
  display: flex;
  gap: 8px;
}

/* Bootstrap-like utilities */
.d-flex {
  display: flex;
}

.justify-content-between {
  justify-content: space-between;
}

.align-items-center {
  align-items: center;
}

.align-items-end {
  align-items: flex-end;
}

.text-center {
  text-align: center;
}

.me-2 {
  margin-right: 8px;
}

.mt-4 {
  margin-top: 24px;
}

.row {
  display: flex;
  flex-wrap: wrap;
  margin: 0 -12px;
}

.col-lg-3, .col-lg-6, .col-md-2, .col-md-3, .col-md-6, .col-xl-4 {
  padding: 0 12px;
  margin-bottom: 24px;
}

.col-lg-3 {
  flex: 0 0 25%;
  max-width: 25%;
}

.col-lg-6 {
  flex: 0 0 50%;
  max-width: 50%;
}

.col-md-2 {
  flex: 0 0 16.666667%;
  max-width: 16.666667%;
}

.col-md-3 {
  flex: 0 0 25%;
  max-width: 25%;
}

.col-md-6 {
  flex: 0 0 50%;
  max-width: 50%;
}

.col-xl-4 {
  flex: 0 0 33.333333%;
  max-width: 33.333333%;
}

@media (max-width: 1200px) {
  .col-xl-4 {
    flex: 0 0 50%;
    max-width: 50%;
  }
}

@media (max-width: 992px) {
  .col-lg-3, .col-lg-6 {
    flex: 0 0 50%;
    max-width: 50%;
  }
}

@media (max-width: 768px) {
  .col-md-2, .col-md-3, .col-md-6, .col-lg-3, .col-lg-6 {
    flex: 0 0 100%;
    max-width: 100%;
  }
  
  .admin-certificate-page {
    padding: 16px;
  }
  
  .header-actions {
    margin-top: 16px;
    flex-direction: column;
  }
  
  .actions-grid {
    grid-template-columns: 1fr;
  }
}
</style>