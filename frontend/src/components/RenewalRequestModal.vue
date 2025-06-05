<template>
  <a-modal
    v-model:open="visible"
    title="Yêu cầu gia hạn chứng chỉ"
    :width="600"
    :confirm-loading="loading"
    @ok="handleSubmit"
    @cancel="handleCancel"
    ok-text="Gửi yêu cầu"
    cancel-text="Hủy"
  >
    <div class="renewal-request-form">
      <!-- Certificate Info -->
      <div class="certificate-summary mb-4">
        <a-card size="small" :bordered="false" class="bg-light">
          <div class="d-flex align-items-center">
            <a-avatar 
              :style="{ backgroundColor: avatarColor }" 
              :icon="certificateIcon"
              size="default"
              class="me-3"
            />
            <div class="flex-grow-1">
              <h6 class="mb-1">{{ certificate?.name }}</h6>
              <div class="certificate-details">
                <small class="text-muted">
                  <strong>Nhà phát hành:</strong> {{ certificate?.issuer }}
                </small>
                <br>
                <small class="text-muted">
                  <strong>Hết hạn:</strong> 
                  <span :class="{ 'text-danger': isExpired, 'text-warning': isExpiringSoon }">
                    {{ formatDate(certificate?.validTo) }}
                  </span>
                </small>
                <br>
                <small class="text-muted">
                  <strong>Trạng thái:</strong> 
                  <a-tag :color="statusColor" size="small">{{ statusText }}</a-tag>
                </small>
              </div>
            </div>
          </div>
        </a-card>
      </div>

      <!-- Form Fields -->
      <a-form
        ref="formRef"
        :model="formData"
        :rules="rules"
        layout="vertical"
        @finish="handleSubmit"
      >
        <!-- Renewal Type -->
        <a-form-item 
          label="Loại gia hạn" 
          name="renewalType"
          :rules="[{ required: true, message: 'Vui lòng chọn loại gia hạn' }]"
        >
          <a-radio-group v-model:value="formData.renewalType">
            <a-radio value="extend">Gia hạn với cùng thông tin</a-radio>
            <a-radio value="reissue">Cấp lại với thông tin mới</a-radio>
          </a-radio-group>
        </a-form-item>

        <!-- Duration -->
        <a-form-item 
          label="Thời hạn mong muốn" 
          name="duration"
          :rules="[{ required: true, message: 'Vui lòng chọn thời hạn' }]"
        >
          <a-select v-model:value="formData.duration" placeholder="Chọn thời hạn">
            <a-select-option value="1">1 năm</a-select-option>
            <a-select-option value="2">2 năm</a-select-option>
            <a-select-option value="3">3 năm</a-select-option>
          </a-select>
        </a-form-item>

        <!-- Priority -->
        <a-form-item 
          label="Mức độ ưu tiên" 
          name="priority"
          :rules="[{ required: true, message: 'Vui lòng chọn mức độ ưu tiên' }]"
        >
          <a-select v-model:value="formData.priority" placeholder="Chọn mức độ ưu tiên">
            <a-select-option value="low">
              <div class="d-flex align-items-center">
                <a-tag color="blue" size="small" class="me-2">Thấp</a-tag>
                <span>Không khẩn cấp</span>
              </div>
            </a-select-option>
            <a-select-option value="medium">
              <div class="d-flex align-items-center">
                <a-tag color="orange" size="small" class="me-2">Trung bình</a-tag>
                <span>Cần xử lý trong tuần</span>
              </div>
            </a-select-option>
            <a-select-option value="high">
              <div class="d-flex align-items-center">
                <a-tag color="red" size="small" class="me-2">Cao</a-tag>
                <span>Cần xử lý ngay</span>
              </div>
            </a-select-option>
          </a-select>
        </a-form-item>

        <!-- Reason -->
        <a-form-item 
          label="Lý do gia hạn" 
          name="reason"
          :rules="[
            { required: true, message: 'Vui lòng nhập lý do gia hạn' },
            { min: 10, message: 'Lý do phải có ít nhất 10 ký tự' }
          ]"
        >
          <a-textarea
            v-model:value="formData.reason"
            :rows="4"
            placeholder="Nhập lý do cần gia hạn chứng chỉ..."
            show-count
            :maxlength="500"
          />
        </a-form-item>

        <!-- Additional Documents -->
        <a-form-item label="Tài liệu đính kèm (tùy chọn)">
          <a-upload
            v-model:file-list="fileList"
            :before-upload="beforeUpload"
            :remove="removeFile"
            :multiple="true"
            :max-count="3"
          >
            <a-button>
              <template #icon>
                <UploadOutlined />
              </template>
              Tải lên tài liệu
            </a-button>
          </a-upload>
          <div class="mt-2">
            <small class="text-muted">
              Tối đa 3 file, mỗi file không quá 5MB. Hỗ trợ: PDF, DOC, DOCX, JPG, PNG
            </small>
          </div>
        </a-form-item>

        <!-- Contact Info -->
        <a-form-item 
          label="Thông tin liên hệ" 
          name="contactInfo"
        >
          <a-input
            v-model:value="formData.contactInfo"
            placeholder="Số điện thoại hoặc email liên hệ"
          />
        </a-form-item>

        <!-- Terms Agreement -->
        <a-form-item 
          name="agree"
          :rules="[{ required: true, message: 'Vui lòng đồng ý với các điều khoản' }]"
        >
          <a-checkbox v-model:checked="formData.agree">
            Tôi đồng ý với <a href="#" @click.prevent="showTerms">các điều khoản và điều kiện</a> 
            về việc gia hạn chứng chỉ số
          </a-checkbox>
        </a-form-item>
      </a-form>

      <!-- Warning for CA Root -->
      <div v-if="certificate?.type === 'ca_root'" class="mt-3">
        <a-alert
          message="Chứng chỉ CA Root"
          description="Đây là chứng chỉ CA Root, việc gia hạn cần được xem xét đặc biệt và có thể mất nhiều thời gian hơn."
          type="warning"
          show-icon
        />
      </div>
    </div>
  </a-modal>

  <!-- Terms Modal -->
  <a-modal
    v-model:open="termsVisible"
    title="Điều khoản và điều kiện"
    :width="700"
    :footer="null"
  >
    <div class="terms-content">
      <h6>Điều khoản gia hạn chứng chỉ số</h6>
      <ol>
        <li>Yêu cầu gia hạn chỉ được chấp nhận khi chứng chỉ còn hiệu lực hoặc đã hết hạn không quá 30 ngày.</li>
        <li>Thời gian xử lý yêu cầu từ 3-7 ngày làm việc đối với chứng chỉ thường, 7-14 ngày đối với CA Root.</li>
        <li>Người yêu cầu chịu trách nhiệm về tính chính xác của thông tin cung cấp.</li>
        <li>Phí gia hạn sẽ được thông báo qua email sau khi yêu cầu được duyệt.</li>
        <li>Chứng chỉ mới sẽ có hiệu lực từ ngày hết hạn của chứng chỉ cũ.</li>
        <li>Trường Đại học Thủy lợi có quyền từ chối yêu cầu gia hạn nếu không đáp ứng yêu cầu kỹ thuật.</li>
      </ol>
    </div>
  </a-modal>
</template>

<script setup>
import { ref, computed, watch, h } from 'vue'
import { message } from 'ant-design-vue'
import { 
  UploadOutlined,
  UserOutlined,
  TeamOutlined,
  SafetyOutlined
} from '@ant-design/icons-vue'
import { useCertificateStore } from '@/stores/certificateStore'

const props = defineProps({
  visible: {
    type: Boolean,
    default: false
  },
  certificate: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['update:visible', 'success'])

const certificateStore = useCertificateStore()
const formRef = ref()
const loading = ref(false)
const termsVisible = ref(false)
const fileList = ref([])

// Form data
const formData = ref({
  renewalType: 'extend',
  duration: '1',
  priority: 'medium',
  reason: '',
  contactInfo: '',
  agree: false
})

// Form rules
const rules = {
  renewalType: [{ required: true, message: 'Vui lòng chọn loại gia hạn' }],
  duration: [{ required: true, message: 'Vui lòng chọn thời hạn' }],
  priority: [{ required: true, message: 'Vui lòng chọn mức độ ưu tiên' }],
  reason: [
    { required: true, message: 'Vui lòng nhập lý do gia hạn' },
    { min: 10, message: 'Lý do phải có ít nhất 10 ký tự' }
  ],
  agree: [{ required: true, message: 'Vui lòng đồng ý với các điều khoản' }]
}

// Computed properties
const visible = computed({
  get: () => props.visible,
  set: (value) => emit('update:visible', value)
})

const avatarColor = computed(() => {
  switch (props.certificate?.type) {
    case 'personal': return '#1890ff'
    case 'organization': return '#52c41a'
    case 'ca_root': return '#722ed1'
    default: return '#d9d9d9'
  }
})

const certificateIcon = computed(() => {
  switch (props.certificate?.type) {
    case 'personal': return h(UserOutlined)
    case 'organization': return h(TeamOutlined)
    case 'ca_root': return h(SafetyOutlined)
    default: return h(SafetyOutlined)
  }
})

const statusColor = computed(() => {
  switch (props.certificate?.status) {
    case 'active': return 'green'
    case 'expired': return 'red'
    case 'revoked': return 'orange'
    default: return 'default'
  }
})

const statusText = computed(() => {
  switch (props.certificate?.status) {
    case 'active': return 'Hoạt động'
    case 'expired': return 'Hết hạn'
    case 'revoked': return 'Thu hồi'
    default: return 'Không xác định'
  }
})

const isExpired = computed(() => {
  return props.certificate?.status === 'expired'
})

const isExpiringSoon = computed(() => {
  if (!props.certificate?.validTo) return false
  const now = new Date()
  const expiry = new Date(props.certificate.validTo)
  const diffTime = expiry - now
  const daysUntilExpiry = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  return daysUntilExpiry <= 30 && daysUntilExpiry > 0
})

// Methods
const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('vi-VN')
}

const beforeUpload = (file) => {
  const isValidType = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'image/jpeg',
    'image/png'
  ].includes(file.type)
  
  if (!isValidType) {
    message.error('Chỉ hỗ trợ file PDF, DOC, DOCX, JPG, PNG!')
    return false
  }
  
  const isLt5M = file.size / 1024 / 1024 < 5
  if (!isLt5M) {
    message.error('File phải nhỏ hơn 5MB!')
    return false
  }
  
  return false // Prevent auto upload
}

const removeFile = (file) => {
  const index = fileList.value.indexOf(file)
  const newFileList = fileList.value.slice()
  newFileList.splice(index, 1)
  fileList.value = newFileList
}

const showTerms = () => {
  termsVisible.value = true
}

const handleSubmit = async () => {
  try {
    await formRef.value.validate()
    loading.value = true
    
    const requestData = {
      certificateId: props.certificate.id,
      renewalType: formData.value.renewalType,
      duration: formData.value.duration,
      priority: formData.value.priority,
      reason: formData.value.reason,
      contactInfo: formData.value.contactInfo,
      attachments: fileList.value.map(f => f.name)
    }
    
    const result = await certificateStore.requestRenewal(
      props.certificate.id, 
      formData.value.reason
    )
    
    if (result.success) {
      message.success('Yêu cầu gia hạn đã được gửi thành công!')
      emit('success', result.data)
      handleCancel()
    } else {
      message.error('Có lỗi xảy ra khi gửi yêu cầu!')
    }
  } catch (error) {
    console.error('Validation failed:', error)
  } finally {
    loading.value = false
  }
}

const handleCancel = () => {
  visible.value = false
  resetForm()
}

const resetForm = () => {
  formData.value = {
    renewalType: 'extend',
    duration: '1',
    priority: 'medium',
    reason: '',
    contactInfo: '',
    agree: false
  }
  fileList.value = []
  if (formRef.value) {
    formRef.value.resetFields()
  }
}

// Watch for certificate changes to pre-fill contact info
watch(() => props.certificate, (newCert) => {
  if (newCert && certificateStore.currentUser) {
    formData.value.contactInfo = certificateStore.currentUser.email
  }
}, { immediate: true })
</script>

<style scoped>
.renewal-request-form {
  max-height: 60vh;
  overflow-y: auto;
}

.certificate-summary {
  padding: 16px;
  border-radius: 6px;
  background-color: #fafafa;
}

.certificate-details {
  line-height: 1.4;
}

.terms-content {
  max-height: 400px;
  overflow-y: auto;
}

.terms-content h6 {
  margin-bottom: 16px;
  font-weight: 600;
}

.terms-content ol {
  padding-left: 20px;
}

.terms-content li {
  margin-bottom: 8px;
  line-height: 1.5;
}

.bg-light {
  background-color: #f8f9fa !important;
}

.d-flex {
  display: flex;
}

.align-items-center {
  align-items: center;
}

.flex-grow-1 {
  flex-grow: 1;
}

.me-2 {
  margin-right: 8px;
}

.me-3 {
  margin-right: 12px;
}

.mb-1 {
  margin-bottom: 4px;
}

.mb-4 {
  margin-bottom: 24px;
}

.mt-2 {
  margin-top: 8px;
}

.mt-3 {
  margin-top: 12px;
}

.text-danger {
  color: #ff4d4f;
}

.text-warning {
  color: #fa8c16;
}

.text-muted {
  color: #6c757d;
}
</style>