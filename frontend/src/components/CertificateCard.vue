<template>
  <div class="certificate-card">
    <a-card 
      :class="cardClass"
      :hoverable="true"
      size="default"
    >
      <template #title>
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <a-avatar 
              :style="{ backgroundColor: avatarColor }" 
              :icon="certificateIcon"
              size="small"
              class="me-2"
            />
            <span class="certificate-name">{{ certificate.name }}</span>
          </div>
          <a-tag :color="statusColor">
            {{ statusText }}
          </a-tag>
        </div>
      </template>

      <template #extra>
        <a-dropdown :trigger="['click']">
          <a-button type="text" size="small">
            <template #icon>
              <MoreOutlined />
            </template>
          </a-button>
          <template #overlay>
            <a-menu @click="handleMenuClick">
              <a-menu-item key="view" :icon="h(EyeOutlined)">
                Xem chi tiết
              </a-menu-item>
              <a-menu-item key="verify" :icon="h(SafetyCertificateOutlined)">
                Xác thực
              </a-menu-item>
              <a-menu-item 
                v-if="canRenew" 
                key="renew" 
                :icon="h(ReloadOutlined)"
              >
                Yêu cầu gia hạn
              </a-menu-item>
              <a-menu-item key="download" :icon="h(DownloadOutlined)">
                Tải xuống
              </a-menu-item>
              <a-menu-divider v-if="canDelete" />
              <a-menu-item 
                v-if="canDelete" 
                key="delete" 
                :icon="h(DeleteOutlined)"
                class="text-danger"
              >
                Xóa chứng chỉ
              </a-menu-item>
            </a-menu>
          </template>
        </a-dropdown>
      </template>

      <!-- Certificate Info -->
      <div class="certificate-info">
        <div class="row mb-2">
          <div class="col-sm-4">
            <small class="text-muted">Nhà phát hành:</small>
          </div>
          <div class="col-sm-8">
            <small>{{ certificate.issuer }}</small>
          </div>
        </div>
        
        <div class="row mb-2">
          <div class="col-sm-4">
            <small class="text-muted">Số serial:</small>
          </div>
          <div class="col-sm-8">
            <small class="font-monospace">{{ certificate.serialNumber }}</small>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-sm-4">
            <small class="text-muted">Hiệu lực:</small>
          </div>
          <div class="col-sm-8">
            <small>{{ formatDateRange(certificate.validFrom, certificate.validTo) }}</small>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-sm-4">
            <small class="text-muted">Đã sử dụng:</small>
          </div>
          <div class="col-sm-8">
            <small>{{ certificate.usageCount }} lần</small>
          </div>
        </div>

        <!-- Expiry Warning -->
        <div v-if="daysUntilExpiry <= 30 && daysUntilExpiry > 0" class="mt-3">
          <a-alert
            :message="`Chứng chỉ sẽ hết hạn trong ${daysUntilExpiry} ngày`"
            type="warning"
            size="small"
            show-icon
            :closable="false"
          />
        </div>

        <!-- Expired Alert -->
        <div v-if="certificate.status === 'expired'" class="mt-3">
          <a-alert
            message="Chứng chỉ đã hết hạn"
            type="error"
            size="small"
            show-icon
            :closable="false"
          />
        </div>
      </div>

      <!-- Action Buttons for Quick Actions -->
      <div class="certificate-actions mt-3">
        <div class="d-flex gap-2">
          <a-button 
            size="small" 
            @click="$emit('verify', certificate)"
            :loading="verifying"
          >
            <template #icon>
              <SafetyCertificateOutlined />
            </template>
            Xác thực
          </a-button>

          <a-button 
            v-if="canRenew"
            size="small" 
            type="primary"
            @click="$emit('renew', certificate)"
          >
            <template #icon>
              <ReloadOutlined />
            </template>
            Gia hạn
          </a-button>

          <a-button 
            size="small"
            @click="$emit('download', certificate)"
          >
            <template #icon>
              <DownloadOutlined />
            </template>
          </a-button>
        </div>
      </div>
    </a-card>
  </div>
</template>

<script setup>
import { computed, h } from 'vue'
import { 
  MoreOutlined, 
  EyeOutlined, 
  SafetyCertificateOutlined,
  ReloadOutlined,
  DownloadOutlined,
  DeleteOutlined,
  UserOutlined,
  TeamOutlined,
  SafetyOutlined
} from '@ant-design/icons-vue'
import { useCertificateStore } from '@/stores/certificateStore'

const props = defineProps({
  certificate: {
    type: Object,
    required: true
  },
  verifying: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['view', 'verify', 'renew', 'download', 'delete'])

const certificateStore = useCertificateStore()

// Computed properties
const statusColor = computed(() => {
  switch (props.certificate.status) {
    case 'active': return 'green'
    case 'expired': return 'red'
    case 'revoked': return 'orange'
    default: return 'default'
  }
})

const statusText = computed(() => {
  switch (props.certificate.status) {
    case 'active': return 'Hoạt động'
    case 'expired': return 'Hết hạn'
    case 'revoked': return 'Thu hồi'
    default: return 'Không xác định'
  }
})

const cardClass = computed(() => {
  const baseClass = 'certificate-card-inner'
  if (props.certificate.status === 'expired') return `${baseClass} expired`
  if (props.certificate.status === 'revoked') return `${baseClass} revoked`
  return baseClass
})

const avatarColor = computed(() => {
  switch (props.certificate.type) {
    case 'personal': return '#1890ff'
    case 'organization': return '#52c41a'
    case 'ca_root': return '#722ed1'
    default: return '#d9d9d9'
  }
})

const certificateIcon = computed(() => {
  switch (props.certificate.type) {
    case 'personal': return h(UserOutlined)
    case 'organization': return h(TeamOutlined)
    case 'ca_root': return h(SafetyOutlined)
    default: return h(SafetyCertificateOutlined)
  }
})

const daysUntilExpiry = computed(() => {
  const now = new Date()
  const expiry = new Date(props.certificate.validTo)
  const diffTime = expiry - now
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
})

const canRenew = computed(() => {
  return props.certificate.status === 'expired' || daysUntilExpiry.value <= 30
})

const canDelete = computed(() => {
  // Only admin can delete CA root certificates with special protection
  if (props.certificate.type === 'ca_root') {
    return certificateStore.currentUser.role === 'admin'
  }
  return true
})

// Methods
const formatDateRange = (from, to) => {
  const fromDate = new Date(from).toLocaleDateString('vi-VN')
  const toDate = new Date(to).toLocaleDateString('vi-VN')
  return `${fromDate} - ${toDate}`
}

const handleMenuClick = ({ key }) => {
  switch (key) {
    case 'view':
      emit('view', props.certificate)
      break
    case 'verify':
      emit('verify', props.certificate)
      break
    case 'renew':
      emit('renew', props.certificate)
      break
    case 'download':
      emit('download', props.certificate)
      break
    case 'delete':
      emit('delete', props.certificate)
      break
  }
}
</script>

<style scoped>
.certificate-card {
  margin-bottom: 16px;
}

.certificate-card-inner {
  transition: all 0.3s ease;
}

.certificate-card-inner:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.certificate-card-inner.expired {
  border-color: #ff4d4f;
  background-color: #fff2f0;
}

.certificate-card-inner.revoked {
  border-color: #fa8c16;
  background-color: #fff7e6;
}

.certificate-name {
  font-weight: 500;
  color: #262626;
}

.certificate-info {
  font-size: 13px;
}

.certificate-actions {
  border-top: 1px solid #f0f0f0;
  padding-top: 12px;
  margin-top: 12px;
}

.font-monospace {
  font-family: 'Courier New', monospace;
}

.text-danger {
  color: #ff4d4f !important;
}

.d-flex {
  display: flex;
}

.justify-content-between {
  justify-content: space-between;
}

.align-items-center {
  align-items: center;
}

.gap-2 {
  gap: 8px;
}

.me-2 {
  margin-right: 8px;
}

.mb-2 {
  margin-bottom: 8px;
}

.mt-3 {
  margin-top: 12px;
}
</style>