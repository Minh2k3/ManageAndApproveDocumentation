<template>
  <div class="document-flow-container">
    <!-- <div class="flow-header" v-if="flowData">
      <h3 class="flow-title">{{ flowData.document_flow_name }}</h3>
      <div class="flow-info d-flex justify-content-center align-items-center">
        <span class="step-counter">{{ completedSteps }}/{{ flowData.total_steps }} bước hoàn thành</span>
      </div>
    </div> -->
    
    <a-steps
      direction="vertical"
      :current="currentStep"
      class="main-steps"
    >
      <a-step
        v-for="(step, index) in processedSteps"
        :key="step.id"
        :title="step.title"
        :description="step.description"
        :status="getAntdStatus(step.displayStatus)"
        :icon="getStepIcon(step.displayStatus)"
        :class="getStepClass(step.displayStatus)"
      >
        <template #description>
          <div class="step-content">
            <div class="step-description">{{ step.description }}</div>
            
            <!-- Hiển thị trạng thái -->
            <div class="step-status-badge">
              <a-tag 
                :color="getStatusColor(step.displayStatus)"
                class="status-tag"
              >
                {{ getStatusText(step.displayStatus) }}
              </a-tag>
            </div>
            
            <!-- Hiển thị thông tin người phê duyệt -->
            <div class="approver-info">
              <div class="approver-name">
                <strong>{{ step.approver.name }}</strong>
              </div>
              <div class="approver-role">
                {{ step.approver.roll_display }}
              </div>
            </div>
            
            <!-- Hiển thị thời gian ký nếu có -->
            <div v-if="step.signed_at" class="signed-time">
              <span class="time-label">Thời gian ký:</span>
              <span class="time-value">{{ step.signed_at }}</span>
            </div>
            
            <!-- Hiển thị thông tin multichoice nếu có -->
            <div v-if="step.multichoice" class="multichoice-info">
              <a-tag color="blue" size="small">
                Phê duyệt đồng thuận
              </a-tag>
            </div>
          </div>
        </template>
      </a-step>
    </a-steps>
  </div>
</template>

<script setup>
import { computed, h } from 'vue'
import { CheckCircleOutlined, CloseCircleOutlined, SyncOutlined, ClockCircleOutlined, StopOutlined } from '@ant-design/icons-vue'

// Props
const props = defineProps({
  steps: {
    type: Object,
    required: false,
    default: () => null
  }
})

// Sample data nếu không có props
const sampleData = {
  "document_flow_id": 8,
  "document_flow_name": "Luồng phê duyệt cho 'Test thêm nháp' của Hải Nam - 5/27/2025, 9:23:01 AM",
  "total_steps": 2,
  "steps": [
    {
      "id": 15,
      "step": 1,
      "department": {
        "id": 6,
        "name": "Hội Sinh viên"
      },
      "approver": {
        "id": 17,
        "name": "Ngọc Thúy",
        "department": {
          "id": 6,
          "name": "Hội Sinh viên"
        },
        "roll": {
          "id": 16,
          "name": "Phó Chủ tịch"
        },
        "roll_display": "Phó Chủ tịch - Hội Sinh viên"
      },
      "multichoice": false,
      "status": "approved", // approved, rejected, pending
      "decision": "approve", // approve, reject, not_yet
      "signed_at": "10:30:15 27/05/2025",
      "created_at": "09:23:02 27/05/2025",
      "updated_at": "10:30:15 27/05/2025"
    },
    {
      "id": 16,
      "step": 2,
      "department": {
        "id": 4,
        "name": "Phòng CT & CTSV"
      },
      "approver": {
        "id": 16,
        "name": "Diệp Hoàng Trúc Mai",
        "department": {
          "id": 4,
          "name": "Phòng CT & CTSV"
        },
        "roll": {
          "id": 2,
          "name": "Phó phòng"
        },
        "roll_display": "Phó phòng - Phòng CT & CTSV"
      },
      "multichoice": false,
      "status": "pending",
      "decision": "not_yet",
      "signed_at": null,
      "created_at": "09:23:02 27/05/2025",
      "updated_at": "09:23:02 27/05/2025"
    }
  ]
}

// Sử dụng data từ props hoặc sample data
const flowData = computed(() => {
  console.log('Props received:', props.steps)
  return props.steps || sampleData
})

// Chuyển đổi status từ API sang status hiển thị
const convertApiStatus = (apiStatus, decision) => {
  switch (apiStatus) {
    case 'approved':
      return 'approved'
    case 'rejected':
      return 'rejected'
    case 'pending':
      return 'waiting'
    case 'in_review':
      return 'in_review'
    default:
      return 'waiting'
  }
}

// Xử lý logic multichoice và disabled steps
const processedSteps = computed(() => {
  if (!flowData.value?.steps) return []
  
  const steps = flowData.value.steps
  let hasRejectedStep = false
  
  return steps.map((step, index) => {
    // Chuyển đổi status
    let displayStatus = convertApiStatus(step.status, step.decision)
    
    // Logic disable: nếu có bước trước bị reject, các bước sau sẽ bị disable
    if (hasRejectedStep && displayStatus !== 'rejected') {
      displayStatus = 'disabled'
    }
    
    // Cập nhật flag nếu bước hiện tại bị rejected
    if (step.status === 'rejected' || step.decision === 'reject') {
      hasRejectedStep = true
      displayStatus = 'rejected'
    }

    return {
      ...step,
      title: `Bước ${step.step}: ${step.department.name}`,
      description: `Phê duyệt bởi: ${step.approver?.name ?? 'Bất kỳ ai'}`,
      displayStatus: displayStatus,
      originalStatus: step.status
    }
  })
})

// Tính current step
const currentStep = computed(() => {
  const steps = processedSteps.value
  for (let i = 0; i < steps.length; i++) {
    if (steps[i].displayStatus === 'in_review' || steps[i].displayStatus === 'waiting') {
      return i
    }
  }
  return steps.length - 1
})

// Đếm số bước đã hoàn thành
const completedSteps = computed(() => {
  return processedSteps.value.filter(step => 
    step.displayStatus === 'approved'
  ).length
})

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
      return h(StopOutlined, { style: { color: '#bfbfbf', opacity: 0.5 } })
    default:
      return undefined
  }
}

// Hàm lấy class CSS cho trạng thái
const getStepClass = (status) => {
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
.document-flow-container {
  padding: 20px;
  background: #fafafa;
  border-radius: 8px;
}

.flow-header {
  margin-bottom: 20px;
  padding: 16px;
  background: white;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.flow-title {
  margin: 0 0 8px 0;
  font-size: 16px;
  font-weight: 600;
  color: #262626;
  line-height: 1.4;
}

.flow-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.step-counter {
  font-size: 14px;
  color: #666;
  background: #f0f2f5;
  padding: 4px 12px;
  border-radius: 12px;
}

.main-steps {
  background: white;
  padding: 16px;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.step-content {
  padding: 8px 0;
}

.step-description {
  margin-bottom: 12px;
  color: #666;
  font-size: 14px;
}

.step-status-badge {
  margin: 8px 0;
}

.status-tag {
  font-size: 12px;
  padding: 2px 8px;
  border-radius: 12px;
  font-weight: 500;
}

.approver-info {
  margin: 12px 0;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 6px;
  border-left: 3px solid #1890ff;
}

.approver-name {
  font-size: 14px;
  margin-bottom: 4px;
}

.approver-role {
  font-size: 12px;
  color: #666;
}

.signed-time {
  margin: 8px 0;
  font-size: 12px;
  color: #666;
}

.time-label {
  margin-right: 8px;
}

.time-value {
  font-weight: 500;
  color: #262626;
}

.multichoice-info {
  margin: 8px 0;
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

.step-disabled .step-content {
  opacity: 0.6;
  color: #bfbfbf;
}

/* Responsive */
@media (max-width: 768px) {
  .document-flow-container {
    padding: 12px;
  }
  
  .flow-header {
    padding: 12px;
  }
  
  .main-steps {
    padding: 12px;
  }
  
  .flow-title {
    font-size: 14px;
  }
  
  .approver-info {
    padding: 8px;
  }
}
</style>