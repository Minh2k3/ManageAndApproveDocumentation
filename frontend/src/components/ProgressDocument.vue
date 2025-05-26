<template>
  <div class="nested-steps-container">
    <a-steps
      direction="vertical"
      :current="currentMainStep"
      class="main-steps"
    >
      <a-step
        v-for="(step, index) in stepsWithStatus"
        :key="index"
        :title="step.title"
        :description="step.description"
        :status="getAntdStatus(step.status)"
        :icon="getStepIcon(step.status)"
        :class="getStepClass(step.status, step.isDisabled)"
      >
        <template #description>
          <div :class="{ 'disabled-content': step.isDisabled }">
            <div class="step-description">{{ step.description }}</div>
            
            <!-- Hiển thị trạng thái của bước cha -->
            <div class="step-status-badge">
              <a-tag 
                :color="getStatusColor(step.originalStatus || step.status)"
                class="status-tag"
              >
                {{ getStatusText(step.originalStatus || step.status) }}
              </a-tag>
            </div>
            
            <!-- Sub-steps nếu có -->
            <div v-if="step.subSteps && step.subSteps.length > 0" class="sub-steps-container">
              <div class="sub-steps-header">
                <span class="sub-steps-title">Chi tiết các bước:</span>
              </div>
              
              <!-- Hiển thị từng bước con dưới dạng list thay vì a-steps -->
              <div class="sub-steps-list">
                <div 
                  v-for="(subStep, subIndex) in step.subSteps"
                  :key="subIndex"
                  class="sub-step-item"
                  :class="{ 'disabled-sub-step': step.isDisabled }"
                  :data-status="step.isDisabled ? 'disabled' : subStep.status"
                >
                  <!-- Icon và title của sub-step -->
                  <div class="sub-step-header">
                    <div class="sub-step-icon">
                      <component 
                        :is="getStepIcon(step.isDisabled ? 'disabled' : subStep.status)"
                        class="sub-step-icon-component"
                      />
                    </div>
                    <div class="sub-step-content">
                      <div class="sub-step-title">{{ subStep.title }}</div>
                      <div class="sub-step-description">{{ subStep.description }}</div>
                      
                      <!-- Status badge cho sub-step -->
                      <div class="step-status-badge">
                        <a-tag 
                          :color="getStatusColor(step.isDisabled ? 'disabled' : subStep.status)"
                          class="status-tag-small"
                          size="small"
                        >
                          {{ getStatusText(step.isDisabled ? 'disabled' : subStep.status) }}
                        </a-tag>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Connector line trừ item cuối -->
                  <div 
                    v-if="subIndex < step.subSteps.length - 1" 
                    class="sub-step-connector"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </a-step>
    </a-steps>
  </div>
</template>

<script setup>
import { computed, h } from 'vue'
import { CheckCircleOutlined, CloseCircleOutlined, SyncOutlined, ClockCircleOutlined } from '@ant-design/icons-vue'

// Props
const props = defineProps({
  steps: {
    type: Array,
    required: true,
    default: () => []
  }
})

// Sample data structure
const sampleSteps = [
  {
    title: 'Khởi tạo dự án',
    description: 'Thiết lập môi trường và cấu hình ban đầu',
    status: 'approved',
    subSteps: [
      {
        title: 'Tạo repository',
        description: 'Khởi tạo Git repository',
        status: 'approved'
      },
      {
        title: 'Cài đặt dependencies',
        description: 'Cài đặt các package cần thiết',
        status: 'approved'
      }
    ]
  },
  {
    title: 'Phát triển tính năng',
    description: 'Xây dựng các tính năng chính',
    status: 'rejected',
    subSteps: [
      {
        title: 'Frontend Development',
        description: 'Phát triển giao diện người dùng',
        status: 'approved'
      },
      {
        title: 'Backend API',
        description: 'Xây dựng API backend',
        status: 'rejected'
      },
      {
        title: 'Database Design',
        description: 'Thiết kế cơ sở dữ liệu',
        status: 'waiting'
      }
    ]
  },
  {
    title: 'Testing & Deployment',
    description: 'Kiểm thử và triển khai ứng dụng',
    status: 'waiting',
    subSteps: [
      {
        title: 'Unit Testing',
        description: 'Viết và chạy unit tests',
        status: 'waiting'
      },
      {
        title: 'Integration Testing',
        description: 'Kiểm thử tích hợp',
        status: 'waiting'
      },
      {
        title: 'Production Deployment',
        description: 'Triển khai lên production',
        status: 'waiting'
      }
    ]
  }
]

// Sử dụng sample data nếu không có props
const currentSteps = computed(() => props.steps.length > 0 ? props.steps : sampleSteps)

// Hàm tính toán trạng thái của bước cha dựa trên bước con
const calculateParentStatus = (subSteps) => {
  if (!subSteps || subSteps.length === 0) return 'waiting'
  
  const statuses = subSteps.map(step => step.status)
  
  // Nếu có bước con bị rejected thì cha bị rejected
  if (statuses.includes('rejected')) return 'rejected'
  
  // Nếu có bước con đang in_review thì cha đang in_review
  if (statuses.includes('in_review')) return 'in_review'
  
  // Nếu tất cả bước con đều approved thì cha approved
  if (statuses.every(status => status === 'approved')) return 'approved'
  
  // Nếu có bước con waiting thì cha waiting
  if (statuses.includes('waiting')) return 'waiting'
  
  return 'waiting'
}

// Tính toán lại trạng thái cho các bước với logic disable
const stepsWithStatus = computed(() => {
  let hasRejectedBefore = false
  
  return currentSteps.value.map((step, index) => {
    // Tính trạng thái hiện tại của bước
    const currentStatus = step.subSteps && step.subSteps.length > 0 
      ? calculateParentStatus(step.subSteps)
      : step.status
    
    // Nếu bước trước đó bị rejected, các bước sau sẽ bị disabled
    const finalStatus = hasRejectedBefore ? 'disabled' : currentStatus
    
    // Cập nhật flag nếu bước hiện tại bị rejected
    if (currentStatus === 'rejected') {
      hasRejectedBefore = true
    }
    
    return {
      ...step,
      status: finalStatus,
      originalStatus: currentStatus, // Lưu trạng thái gốc
      isDisabled: hasRejectedBefore && currentStatus !== 'rejected'
    }
  })
})

// Tính current step cho main steps
const currentMainStep = computed(() => {
  const steps = stepsWithStatus.value
  for (let i = 0; i < steps.length; i++) {
    if (steps[i].status === 'in_review' || steps[i].status === 'waiting') {
      return i
    }
  }
  return steps.length - 1
})

// Tính current step cho sub steps
const getCurrentSubStep = (subSteps) => {
  for (let i = 0; i < subSteps.length; i++) {
    if (subSteps[i].status === 'in_review' || subSteps[i].status === 'waiting') {
      return i
    }
  }
  return subSteps.length - 1
}

// Hàm lấy icon cho từng trạng thái
const getStepIcon = (status) => {
  switch (status) {
    case 'approved':
      return h(CheckCircleOutlined, { style: { color: '#52c41a' } })
    case 'rejected':
      return h(CloseCircleOutlined, { style: { color: '#ff4d4f' } })
    case 'in_review':
      return h(SyncOutlined, { style: { color: '#1890ff' }, spin: true })
    case 'waiting':
      return h(ClockCircleOutlined, { style: { color: '#d9d9d9' } })
    case 'disabled':
      return h(ClockCircleOutlined, { style: { color: '#bfbfbf', opacity: 0.5 } })
    default:
      return undefined
  }
}

// Hàm lấy class CSS cho trạng thái
const getStepClass = (status, isDisabled = false) => {
  if (isDisabled || status === 'disabled') {
    return 'step-disabled'
  }
  return `step-${status}`
}

// Hàm chuyển đổi status thành status của Ant Design
const getAntdStatus = (status) => {
  switch (status) {
    case 'approved':
      return 'finish'
    case 'rejected':
      return 'error'
    case 'in_review':
      return 'process'
    case 'waiting':
      return 'wait'
    case 'disabled':
      return 'wait'
    default:
      return 'wait'
  }
}

// Hàm lấy màu cho status tag
const getStatusColor = (status) => {
  switch (status) {
    case 'approved':
      return 'success'
    case 'rejected':
      return 'error'
    case 'in_review':
      return 'processing'
    case 'waiting':
      return 'default'
    case 'disabled':
      return ''
    default:
      return 'default'
  }
}

// Hàm lấy text hiển thị cho status
const getStatusText = (status) => {
  switch (status) {
    case 'approved':
      return 'Đã duyệt'
    case 'rejected':
      return 'Từ chối'
    case 'in_review':
      return 'Đang xem xét'
    case 'waiting':
      return 'Chờ xử lý'
    case 'disabled':
      return 'Bị vô hiệu'
    default:
      return 'Không xác định'
  }
}
</script>

<style scoped>
.nested-steps-container {
  padding: 20px;
  background: #fafafa;
  border-radius: 8px;
}

.main-steps {
  background: white;
  padding: 16px;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.step-description {
  margin-bottom: 12px;
  color: #666;
}

.sub-steps-container {
  margin-top: 16px;
  padding: 16px;
  background: #f8f9fa;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.sub-steps-list {
  margin-top: 12px;
}

.sub-step-item {
  position: relative;
  margin-bottom: 16px;
}

.sub-step-item:last-child {
  margin-bottom: 0;
}

.sub-step-header {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.sub-step-icon {
  flex-shrink: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  border-radius: 50%;
  border: 2px solid #d9d9d9;
  margin-top: 2px;
}

.sub-step-icon-component {
  font-size: 14px;
}

.sub-step-content {
  flex: 1;
  min-width: 0;
}

.sub-step-title {
  font-weight: 600;
  font-size: 14px;
  color: #262626;
  margin-bottom: 4px;
  line-height: 1.4;
}

.sub-step-description {
  font-size: 12px;
  color: #8c8c8c;
  margin-bottom: 8px;
  line-height: 1.5;
}

.sub-step-connector {
  position: absolute;
  left: 11px;
  top: 32px;
  width: 2px;
  height: 24px;
  background: #f0f0f0;
  z-index: 1;
}

.disabled-sub-step {
  opacity: 0.5;
}

.disabled-sub-step .sub-step-title {
  color: #bfbfbf !important;
}

.disabled-sub-step .sub-step-description {
  color: #d9d9d9 !important;
}

.disabled-sub-step .sub-step-icon {
  background: #f5f5f5;
  border-color: #d9d9d9;
}

/* Color coding cho sub-step icon dựa theo status */
.sub-step-item[data-status="approved"] .sub-step-icon {
  border-color: #52c41a;
  background: #f6ffed;
}

.sub-step-item[data-status="rejected"] .sub-step-icon {
  border-color: #ff4d4f;
  background: #fff2f0;
}

.sub-step-item[data-status="in_review"] .sub-step-icon {
  border-color: #1890ff;
  background: #f0f8ff;
}

.sub-step-item[data-status="waiting"] .sub-step-icon {
  border-color: #d9d9d9;
  background: white;
}

/* Custom styles cho các trạng thái */
:deep(.ant-steps-item-finish .ant-steps-item-icon) {
  background-color: #52c41a;
  border-color: #52c41a;
}

:deep(.ant-steps-item-process .ant-steps-item-icon) {
  background-color: #1890ff;
  border-color: #1890ff;
}

:deep(.ant-steps-item-wait .ant-steps-item-icon) {
  background-color: #f5f5f5;
  border-color: #d9d9d9;
}

/* Custom styles cho rejected status */
:deep(.ant-steps-item-error .ant-steps-item-icon) {
  background-color: #ff4d4f;
  border-color: #ff4d4f;
}

:deep(.ant-steps-item-error .ant-steps-item-title) {
  color: #ff4d4f;
}

/* Styles cho disabled steps */
.step-disabled {
  opacity: 0.4;
  pointer-events: none;
}

.step-disabled :deep(.ant-steps-item-title) {
  color: #bfbfbf !important;
}

.step-disabled :deep(.ant-steps-item-description) {
  color: #d9d9d9 !important;
}

.step-disabled :deep(.ant-steps-item-icon) {
  background-color: #f5f5f5 !important;
  border-color: #d9d9d9 !important;
  opacity: 0.5;
}

.disabled-content {
  opacity: 0.6;
  color: #bfbfbf;
}

/* Status badge styles */
.step-status-badge {
  margin: 8px 0;
}

.status-tag {
  font-size: 12px;
  padding: 2px 8px;
  border-radius: 12px;
  font-weight: 500;
}

.status-tag-small {
  font-size: 10px;
  padding: 1px 6px;
  border-radius: 8px;
}

/* Sub-steps header */
.sub-steps-header {
  margin-bottom: 8px;
  padding-bottom: 4px;
  border-bottom: 1px solid #f0f0f0;
}

.sub-steps-title {
  font-size: 13px;
  font-weight: 600;
  color: #666;
}

.sub-step-description {
  font-size: 12px;
  color: #888;
  margin-bottom: 4px;
}

/* Responsive */
@media (max-width: 768px) {
  .nested-steps-container {
    padding: 12px;
  }
  
  .main-steps {
    padding: 12px;
  }
  
  .sub-steps-container {
    padding-left: 12px;
  }
  
  .sub-steps {
    padding: 8px;
  }
}
</style>