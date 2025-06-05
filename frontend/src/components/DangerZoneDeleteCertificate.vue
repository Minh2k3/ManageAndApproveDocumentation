<template>
  <div class="danger-zone">
    <a-card 
      title="Vùng nguy hiểm" 
      size="small"
      :headStyle="{ backgroundColor: '#fff2f0', color: '#cf1322' }"
      :bodyStyle="{ backgroundColor: '#fff2f0' }"
    >
      <template #extra>
        <ExclamationCircleOutlined style="color: #cf1322;" />
      </template>

      <div class="danger-actions">
        <div class="action-item">
          <div class="action-info">
            <h6 class="action-title">Xóa chứng chỉ</h6>
            <p class="action-description">
              Xóa vĩnh viễn chứng chỉ này khỏi hệ thống. 
              <strong>Hành động này không thể hoàn tác!</strong>
            </p>
            <div v-if="certificate?.type === 'ca_root'" class="ca-warning">
              <a-alert
                message="Cảnh báo đặc biệt"
                description="Đây là chứng chỉ CA Root. Việc xóa sẽ ảnh hưởng đến tất cả chứng chỉ con được ký bởi CA này!"
                type="error"
                show-icon
                :closable="false"
              />
            </div>
          </div>
          <div class="action-button">
            <a-button 
              danger 
              type="primary"
              @click="initiateDelete"
              :loading="deleting"
            >
              <template #icon>
                <DeleteOutlined />
              </template>
              Xóa chứng chỉ
            </a-button>
          </div>
        </div>
      </div>
    </a-card>

    <!-- Step 1: Warning Modal -->
    <a-modal
      v-model:open="showWarningModal"
      title="Cảnh báo xóa chứng chỉ"
      :width="600"
      :closable="false"
      :maskClosable="false"
      @ok="proceedToConfirm"
      @cancel="cancelDelete"
      ok-text="Tôi hiểu, tiếp tục"
      cancel-text="Hủy bỏ"
      ok-type="danger"
    >
      <div class="warning-content">
        <div class="text-center mb-4">
          <ExclamationCircleOutlined style="font-size: 48px; color: #ff4d4f;" />
        </div>
        
        <h5 class="text-center mb-4">Bạn có chắc chắn muốn xóa chứng chỉ này?</h5>
        
        <div class="certificate-info mb-4">
          <a-descriptions :column="1" bordered size="small">
            <a-descriptions-item label="Tên chứng chỉ">
              {{ certificate?.name }}
            </a-descriptions-item>
            <a-descriptions-item label="Loại">
              <a-tag :color="getTypeColor(certificate?.type)">
                {{ getTypeText(certificate?.type) }}
              </a-tag>
            </a-descriptions-item>
            <a-descriptions-item label="Số Serial">
              {{ certificate?.serialNumber }}
            </a-descriptions-item>
            <a-descriptions-item label="Đã sử dụng">
              {{ certificate?.usageCount }} lần
            </a-descriptions-item>
          </a-descriptions>
        </div>

        <div class="consequences">
          <h6 class="text-danger">Hậu quả của việc xóa:</h6>
          <ul class="consequence-list">
            <li>Chứng chỉ sẽ bị xóa vĩnh viễn khỏi hệ thống</li>
            <li>Không thể khôi phục lại chứng chỉ đã xóa</li>
            <li>Các tài liệu đã ký bằng chứng chỉ này có thể không xác thực được</li>
            <li v-if="certificate?.type === 'ca_root'">
              <strong class="text-danger">
                Tất cả chứng chỉ con được ký bởi CA này sẽ trở nên không tin cậy
              </strong>
            </li>
            <li v-if="certificate?.usageCount > 0">
              Có {{ certificate?.usageCount }} lần sử dụng sẽ bị ảnh hưởng
            </li>
          </ul>
        </div>

        <div v-if="certificate?.type === 'ca_root'" class="mt-4">
          <a-alert
            message="Lưu ý quan trọng"
            description="Việc xóa CA Root cần được phê duyệt bởi ít nhất 2 quản trị viên cấp cao và thông báo trước 24 giờ."
            type="warning"
            show-icon
          />
        </div>
      </div>
    </a-modal>

    <!-- Step 2: Password Confirmation Modal -->
    <a-modal
      v-model:open="showPasswordModal"
      title="Xác nhận mật khẩu"
      :width="500"
      :closable="false"
      :maskClosable="false"
      @ok="proceedToFinalConfirm"
      @cancel="cancelDelete"
      ok-text="Xác nhận"
      cancel-text="Hủy bỏ"
      ok-type="danger"
      :confirm-loading="validatingPassword"
    >
      <div class="password-content">
        <div class="text-center mb-4">
          <LockOutlined style="font-size: 32px; color: #fa8c16;" />
        </div>
        
        <p class="text-center mb-4">
          Để tiếp tục, vui lòng nhập mật khẩu của bạn:
        </p>
        
        <a-form @finish="proceedToFinalConfirm">
          <a-form-item
            :rules="[{ required: true, message: 'Vui lòng nhập mật khẩu' }]"
          >
            <a-input-password
              v-model:value="adminPassword"
              placeholder="Nhập mật khẩu quản trị"
              size="large"
              @keyup.enter="proceedToFinalConfirm"
            />
          </a-form-item>
        </a-form>

        <div v-if="passwordError" class="mt-2">
          <a-alert
            :message="passwordError"
            type="error"
            show-icon
            :closable="false"
          />
        </div>
      </div>
    </a-modal>

    <!-- Step 3: Final Confirmation with Countdown -->
    <a-modal
      v-model:open="showFinalModal"
      title="Xác nhận cuối cùng"
      :width="550"
      :closable="false"
      :maskClosable="false"
      @ok="executeDelete"
      @cancel="cancelDelete"
      :ok-text="finalOkText"
      cancel-text="Hủy bỏ"
      ok-type="danger"
      :ok-button-props="{ disabled: !canConfirmDelete }"
      :confirm-loading="deleting"
    >
      <div class="final-content">
        <div class="text-center mb-4">
          <ClockCircleOutlined style="font-size: 32px; color: #ff4d4f;" />
        </div>
        
        <h5 class="text-center mb-4 text-danger">
          Xác nhận xóa chứng chỉ lần cuối!
        </h5>
        
        <div class="countdown-section mb-4" v-if="countdown > 0">
          <div class="text-center">
            <a-progress
              type="circle"
              :percent="(10 - countdown) * 10"
              :size="80"
              :stroke-color="progressColor"
            >
              <template #format>
                <span style="font-size: 16px; font-weight: bold;">{{ countdown }}</span>
              </template>
            </a-progress>
          </div>
          <p class="text-center mt-2 text-muted">
            Vui lòng đợi {{ countdown }} giây để có thể xác nhận
          </p>
        </div>

        <div class="final-checklist">
          <a-checkbox 
            v-model:checked="confirmations.understood"
            :disabled="countdown > 0"
          >
            Tôi hiểu rằng việc xóa này không thể hoàn tác
          </a-checkbox>
          <br>
          <a-checkbox 
            v-model:checked="confirmations.responsibility"
            :disabled="countdown > 0"
          >
            Tôi chịu trách nhiệm về hành động này
          </a-checkbox>
          <br>
          <a-checkbox 
            v-model:checked="confirmations.notified"
            :disabled="countdown > 0"
            v-if="certificate?.type === 'ca_root'"
          >
            Tôi đã thông báo cho các bên liên quan
          </a-checkbox>
        </div>

        <div class="type-to-confirm mt-4" v-if="countdown === 0">
          <a-form-item
            label="Để xác nhận, vui lòng gõ tên chứng chỉ:"
            :rules="[{ required: true, message: 'Vui lòng gõ chính xác tên chứng chỉ' }]"
          >
            <a-input
              v-model:value="typeToConfirm"
              :placeholder="certificate?.name"
              @input="validateTyping"
            />
          </a-form-item>
          <div v-if="typingError" class="text-danger">
            <small>{{ typingError }}</small>
          </div>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script setup>
import { ref, computed, watch, onUnmounted } from 'vue'
import { message, Modal } from 'ant-design-vue'
import {
  ExclamationCircleOutlined,
  DeleteOutlined,
  LockOutlined,
  ClockCircleOutlined
} from '@ant-design/icons-vue'
import { useCertificateStore } from '@/stores/certificateStore'

const props = defineProps({
  certificate: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['deleted'])

const certificateStore = useCertificateStore()

// Modal states
const showWarningModal = ref(false)
const showPasswordModal = ref(false)
const showFinalModal = ref(false)

// Form states
const deleting = ref(false)
const validatingPassword = ref(false)
const adminPassword = ref('')
const passwordError = ref('')
const typeToConfirm = ref('')
const typingError = ref('')

// Countdown and confirmations
const countdown = ref(10)
const countdownTimer = ref(null)
const confirmations = ref({
  understood: false,
  responsibility: false,
  notified: false
})

// Computed properties
const canConfirmDelete = computed(() => {
  const basicConfirms = confirmations.value.understood && confirmations.value.responsibility
  const caConfirm = props.certificate.type !== 'ca_root' || confirmations.value.notified
  const nameMatch = typeToConfirm.value === props.certificate.name
  const timeUp = countdown.value === 0
  
  return basicConfirms && caConfirm && nameMatch && timeUp
})

const finalOkText = computed(() => {
  if (countdown.value > 0) return `Đợi ${countdown.value}s`
  if (!canConfirmDelete.value) return 'Chưa thể xác nhận'
  return 'XÓA VĨNH VIỄN'
})

const progressColor = computed(() => {
  if (countdown.value > 7) return '#ff4d4f'
  if (countdown.value > 3) return '#fa8c16'
  return '#52c41a'
})

// Methods
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

const initiateDelete = () => {
  showWarningModal.value = true
}

const proceedToConfirm = () => {
  showWarningModal.value = false
  showPasswordModal.value = true
}

const proceedToFinalConfirm = async () => {
  if (!adminPassword.value) {
    passwordError.value = 'Vui lòng nhập mật khẩu'
    return
  }

  validatingPassword.value = true
  passwordError.value = ''

  try {
    // Mock password validation
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Simulate password check (in real app, call API)
    if (adminPassword.value !== 'admin123') {
      passwordError.value = 'Mật khẩu không đúng'
      return
    }

    showPasswordModal.value = false
    showFinalModal.value = true
    startCountdown()
  } catch (error) {
    passwordError.value = 'Có lỗi xảy ra khi xác thực'
  } finally {
    validatingPassword.value = false
  }
}

const startCountdown = () => {
  countdown.value = 10
  countdownTimer.value = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) {
      clearInterval(countdownTimer.value)
    }
  }, 1000)
}

const validateTyping = () => {
  typingError.value = ''
  if (typeToConfirm.value && typeToConfirm.value !== props.certificate.name) {
    typingError.value = 'Tên chứng chỉ không khớp'
  }
}

const executeDelete = async () => {
  if (!canConfirmDelete.value) return

  deleting.value = true
  try {
    const result = await certificateStore.deleteCertificate(props.certificate.id)
    
    if (result.success) {
      message.success('Chứng chỉ đã được xóa thành công')
      emit('deleted', props.certificate)
      cancelDelete()
    } else {
      message.error('Có lỗi xảy ra khi xóa chứng chỉ')
    }
  } catch (error) {
    message.error('Có lỗi xảy ra khi xóa chứng chỉ')
  } finally {
    deleting.value = false
  }
}

const cancelDelete = () => {
  // Close all modals
  showWarningModal.value = false
  showPasswordModal.value = false
  showFinalModal.value = false
  
  // Reset states
  adminPassword.value = ''
  passwordError.value = ''
  typeToConfirm.value = ''
  typingError.value = ''
  confirmations.value = {
    understood: false,
    responsibility: false,
    notified: false
  }
  
  // Clear countdown
  if (countdownTimer.value) {
    clearInterval(countdownTimer.value)
    countdownTimer.value = null
  }
  countdown.value = 10
}

// Cleanup on unmount
onUnmounted(() => {
  if (countdownTimer.value) {
    clearInterval(countdownTimer.value)
  }
})
</script>

<style scoped>
.danger-zone {
  margin-top: 24px;
}

.danger-actions {
  padding: 0;
}

.action-item {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
}

.action-info {
  flex: 1;
}

.action-title {
  margin-bottom: 8px;
  font-weight: 600;
  color: #cf1322;
}

.action-description {
  margin-bottom: 0;
  color: #8c8c8c;
  line-height: 1.5;
}

.ca-warning {
  margin-top: 12px;
}

.action-button {
  flex-shrink: 0;
}

.warning-content,
.password-content,
.final-content {
  padding: 16px 0;
}

.certificate-info {
  background-color: #fafafa;
  padding: 16px;
  border-radius: 6px;
}

.consequences {
  background-color: #fff2f0;
  padding: 16px;
  border-radius: 6px;
  border-left: 4px solid #ff4d4f;
}

.consequence-list {
  margin: 8px 0 0 0;
  padding-left: 20px;
}

.consequence-list li {
  margin-bottom: 4px;
  line-height: 1.4;
}

.countdown-section {
  background-color: #f6f6f6;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
}

.final-checklist {
  background-color: #f6f6f6;
  padding: 16px;
  border-radius: 6px;
}

.final-checklist .ant-checkbox-wrapper {
  display: block;
  margin-bottom: 8px;
  line-height: 1.5;
}

.type-to-confirm {
  background-color: #fff2f0;
  padding: 16px;
  border-radius: 6px;
  border-left: 4px solid #ff4d4f;
}

.text-center {
  text-align: center;
}

.text-danger {
  color: #ff4d4f;
}

.text-muted {
  color: #8c8c8c;
}

.mb-4 {
  margin-bottom: 24px;
}

.mt-2 {
  margin-top: 8px;
}

.mt-4 {
  margin-top: 24px;
}
</style>