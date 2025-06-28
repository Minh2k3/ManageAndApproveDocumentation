<template>
    <div class="container py-4">
        <!-- Header section với màu xanh của trường TLU -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Chi tiết luồng phê duyệt mẫu
                </h1>
                <p class="text-secondary">Chi tiết luồng phê duyệt có thể tái sử dụng cho các văn bản</p>
            </div>
        </div>

        <!-- Thông tin luồng mẫu -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin luồng mẫu
                </h5>
            </div>
            <div class="card-body">
                <!-- Tên luồng mẫu -->
                <div class="row mb-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center">
                        <label class="form-label fw-semibold">
                            <span class="text-danger me-1">*</span>
                            <span>Tên luồng mẫu:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-input 
                            v-model:value="workflow_name" 
                            placeholder="VD: Luồng phê duyệt hoạt động sinh viên cấp khoa" 
                            allow-clear 
                            size="large"
                        />
                    </div>
                </div>

                <!-- Mô tả luồng mẫu -->
                <div class="row mb-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center">
                        <label class="form-label fw-semibold">
                            <span>Mô tả luồng:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-textarea 
                            v-model:value="workflow_description" 
                            placeholder="Mô tả chi tiết về luồng phê duyệt này được sử dụng cho mục đích gì..."
                            :rows="3"
                            show-count
                            allow-clear
                            :maxlength="500"
                            size="large"
                        />
                    </div>
                </div>

                <!-- Trạng thái luồng -->
                <!-- <div class="row">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center">
                        <label class="form-label fw-semibold">
                            <span>Trạng thái:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-radio-group v-model:value="workflow_status" size="large">
                            <a-radio :value="1" class="text-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Hoạt động
                            </a-radio>
                            <a-radio :value="0" class="text-warning">
                                <i class="bi bi-pause-circle me-1"></i>
                                Tạm dừng
                            </a-radio>
                        </a-radio-group>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Cấu hình luồng phê duyệt -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-flow-chart me-2"></i>
                    Cấu hình luồng phê duyệt
                </h5>
            </div>
            <div class="card-body">
                <!-- Hướng dẫn sử dụng -->
                <div class="alert alert-info border-0 mb-4">
                    <h6 class="alert-heading fw-bold">
                        <i class="bi bi-lightbulb me-2"></i>
                        Hướng dẫn tạo luồng:
                    </h6>
                    <ul class="mb-0 small">
                        <li><strong>Nút xanh lá:</strong> Thêm cấp duyệt tiếp theo</li>
                        <li><strong>Nút vàng:</strong> Thêm cấp duyệt đồng cấp (song song)</li>
                        <li><strong>Nút đỏ:</strong> Xóa cấp duyệt</li>
                        <li><strong>Chú ý:</strong>
                            <ul>
                                <li>Ở cấp duyệt song song, chỉ có thể thêm cấp mới ở hàng cuối</li>
                                <li>Phải chọn xong đơn vị mới được xử lý thêm, xóa</li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <!-- Danh sách các bước phê duyệt -->
                <div class="workflow-steps">
                    <div v-for="(step, index) in current_flow_step" :key="index" 
                         class="step-card mb-4 p-4 border rounded-3 bg-light">
                        
                        <!-- Header của bước -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="step-header">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="bi bi-bookmark me-1"></i>
                                    Cấp duyệt {{ step.step }}
                                </span>
                                <span v-if="step.multichoice" class="badge bg-warning ms-2">
                                    <i class="bi bi-arrows-expand me-1"></i>
                                    Song song
                                </span>
                            </div>
                        </div>

                        <!-- Nội dung bước -->
                        <div class="row">
                            <!-- Chọn đơn vị -->
                            <div class="col-12 col-lg-8 mb-3 mb-md-0">
                                <label class="form-label fw-semibold text-primary">
                                    <i class="bi bi-building me-1"></i>
                                    Đơn vị phê duyệt:
                                </label>
                                <a-select 
                                    v-model:value="step.department_id" 
                                    style="width: 100%"
                                    :options="listDepartmentsForNewFlow" 
                                    placeholder="Chọn đơn vị phê duyệt" 
                                    size="large"
                                    show-search
                                    :filter-option="filterOption"
                                    allow-clear 
                                />
                            </div>

                            <!-- Các nút thao tác -->
                            <div class="col-lg-4 align-self-lg-end justify-content-lg-center d-flex col-12 align-items-center justify-content-center mt-3 mt-lg-0">
                                <!-- <label class="form-label fw-semibold text-primary">
                                    <i class="bi bi-gear me-1"></i>
                                    Thao tác:
                                </label> -->
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                    <!-- Thêm cấp tiếp theo -->
                                    <a-tooltip title="Thêm cấp duyệt sau cấp hiện tại">
                                        <a-button 
                                            type="primary"
                                            :disabled="!step.department_id || checkIfAfterHasSameStep(step.step, index)"
                                            @click="addStep(step.step, index)"
                                            class="bg-success border-success"
                                            size="large"
                                        >
                                            <i class="bi bi-plus-circle"></i>
                                        </a-button>
                                    </a-tooltip>

                                    <!-- Thêm cấp đồng cấp -->
                                    <a-tooltip title="Thêm cấp duyệt đồng cấp (song song)">
                                        <a-button 
                                            type="primary"
                                            :disabled="!step.department_id"
                                            @click="addSameStep(step.step, index)"
                                            class="bg-warning border-warning"
                                            size="large"
                                        >
                                            <i class="bi bi-arrow-down-circle"></i>
                                        </a-button>
                                    </a-tooltip>

                                    <!-- Xóa cấp -->
                                    <a-tooltip title="Xóa cấp duyệt này">
                                        <a-button 
                                            type="primary"
                                            :disabled="current_flow_step.length <= 1"
                                            @click="removeStep(index)"
                                            class="bg-danger border-danger"
                                            size="large"
                                        >
                                            <i class="bi bi-dash-circle"></i>
                                        </a-button>
                                    </a-tooltip>
                                </div>
                            </div>
                        </div>

                        <!-- Mũi tên chỉ xuống (nếu không phải bước cuối) -->
                        <div v-if="index < current_flow_step.length - 1" class="text-center mt-3">
                            <i class="bi bi-arrow-down fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>

                <!-- Nút reset luồng -->
                <div v-if="current_flow_step.length > 1" class="text-end mb-4">
                    <a-button 
                        type="primary" 
                        @click="resetWorkflow"
                        class="bg-danger border-danger"
                        size="large"
                    >
                        <template #icon>
                            <i class="bi bi-arrow-clockwise me-2"></i>
                        </template>
                        Reset toàn bộ
                    </a-button>
                </div>

                <!-- Các nút hành động chính -->
                <div class="d-flex justify-content-center gap-3 pt-4 border-top">
                    <a-button 
                        type="primary" 
                        @click="handleSaveTemplate"
                        size="large"
                        class="px-4 py-2"
                    >
                        <template #icon>
                            <i class="bi bi-floppy me-2"></i>
                        </template>
                        Lưu luồng mẫu
                    </a-button>

                    <a-button 
                        @click="handleCancel"
                        size="large"
                        class="px-4 py-2"
                    >
                        <template #icon>
                            <i class="bi bi-x-circle me-2"></i>
                        </template>
                        Hủy bỏ
                    </a-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { 
    ref, 
    defineComponent, 
    computed, 
    onMounted, 
    createVNode 
} from 'vue';

import {
    PlusCircleOutlined,
    DownCircleOutlined,
    MinusCircleOutlined,
    CloseCircleOutlined,
    ExclamationCircleOutlined,
} from '@ant-design/icons-vue';

import { message, Modal } from 'ant-design-vue';
import axiosInstance from '@/lib/axios.js';
import { useRouter } from 'vue-router';

// Import stores (giả sử có sẵn)
import { useMenu } from '@/stores/use-menu.js';
import { useDepartmentStore } from '@/stores/admin/department-store.js';
import { useDocumentStore } from '@/stores/admin/document-store.js';
import { useAuth } from '@/stores/use-auth.js';

export default defineComponent({
    components: {
        PlusCircleOutlined,
        DownCircleOutlined,
        MinusCircleOutlined,
        CloseCircleOutlined,
    },

    setup() {
        const router = useRouter();
        const authStore = useAuth();
        const user = authStore.user;
        const departmentStore = useDepartmentStore();

        // Set active menu
        useMenu().onSelectedKeys(["admin-approval-flows-detail"]);

        // Form data cho thông tin luồng mẫu
        const workflow_name = ref('');
        const workflow_description = ref('');
        // const workflow_status = ref(1);

        // Data cho luồng phê duyệt
        const current_flow_step = ref([
            {
                step: 1,
                multichoice: false,
                department_id: null,
            },
        ]);

        // Danh sách đơn vị
        const listDepartments = ref([]);
        
        onMounted(async () => {
            await departmentStore.fetchDepartmentsCanApprove();
            listDepartments.value = departmentStore.departments_can_approve;
        });

        const listDepartmentsForNewFlow = computed(() => {
            return listDepartments.value.filter(department => {
                return department.group !== 'Khác';
            });

        });

        // Filter function cho select
        const filterOption = (input, option) => {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        };

        // Các function xử lý luồng (copy từ code gốc)
        function addStep(step, index) {
            const newStep = {
                step: step + 1,
                multichoice: false,
                department_id: null,
            };

            current_flow_step.value.splice(index + 1, 0, newStep);

            const current_step = newStep.step;

            for (let i = index + 2; i < current_flow_step.value.length; ++i) {
                if (current_flow_step.value[i].step >= current_step) {
                    current_flow_step.value[i].step += 1;
                }
            }

            playSound();
            message.success('Đã thêm cấp duyệt mới');
        }

        function checkIfAfterHasSameStep(step, index) {
            if (index === current_flow_step.value.length - 1) {
                return false;
            }
            return current_flow_step.value[index + 1].step === step;
        }

        function addSameStep(step, index) {
            current_flow_step.value[index].multichoice = true;
            const newStep = {
                step: step,
                department_id: null,
                multichoice: true,
            };
            current_flow_step.value.splice(index + 1, 0, newStep);

            // Cập nhật multichoice cho tất cả các bước cùng cấp
            for (let i = 0; i < current_flow_step.value.length; ++i) {
                if (current_flow_step.value[i].step == step) {
                    current_flow_step.value[i].multichoice = true;
                }
            }

            message.success('Đã thêm cấp duyệt song song');
        }

        function removeStep(index) {
            const curStep = current_flow_step.value[index].step; 
            current_flow_step.value.splice(index, 1);

            for (let i = index; i < current_flow_step.value.length; ++i) {
                if (current_flow_step.value[i].step == curStep) {
                    break;
                } else {
                    current_flow_step.value[i].step -= 1;
                }
            }
            
            // Update multichoice status
            const count_step = new Map();
            for (let cur_step of current_flow_step.value) {
                if (count_step.has(cur_step.step)) {
                    count_step.set(cur_step.step, count_step.get(cur_step.step) + 1);
                } else {
                    count_step.set(cur_step.step, 1);
                }
            }
            
            for (let i = 0; i < current_flow_step.value.length; ++i) {
                if (count_step.get(current_flow_step.value[i].step) > 1) {
                    current_flow_step.value[i].multichoice = true;
                } else {
                    current_flow_step.value[i].multichoice = false;
                }
            }

            message.success('Đã xóa cấp duyệt');
        }

        function resetWorkflow() {
            Modal.confirm({
                title: 'Xác nhận reset luồng',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'Bạn có chắc chắn muốn xóa toàn bộ luồng và bắt đầu lại?',
                okText: 'Đồng ý',
                cancelText: 'Hủy',
                onOk() {
                    current_flow_step.value = [
                        {
                            step: 1,
                            department_id: null,
                            multichoice: false,
                        },
                    ];
                    message.success('Đã reset luồng phê duyệt');
                },
            });
        }

        // Validation
        function validateForm() {
            if (!workflow_name.value.trim()) {
                message.error("Vui lòng nhập tên luồng mẫu");
                return false;
            }

            if (current_flow_step.value[0].department_id === null) {
                message.error("Vui lòng chọn ít nhất một đơn vị phê duyệt");
                return false;
            }

            // Kiểm tra tất cả các bước đều có đơn vị
            for (let step of current_flow_step.value) {
                if (!step.department_id) {
                    message.error("Vui lòng chọn đơn vị cho tất cả các cấp duyệt");
                    return false;
                }
            }

            return true;
        }

        // Hàm lưu luồng mẫu
        async function saveTemplate() {
            const templateData = {
                name: workflow_name.value,
                description: workflow_description.value,
                created_by: user.id,
                process: current_flow_step.value.length,
                steps: current_flow_step.value,
            };

            console.log("Template data: " + JSON.stringify(templateData, null, 2));
            // return;

            try {
                const response = await axiosInstance.post('/api/flow-templates', templateData);
                message.success("Tạo luồng mẫu thành công");
                router.push({ name: 'admin-approval-flows-template' });
            } catch (error) {
                message.error("Lỗi khi tạo luồng mẫu");
                console.error("Lỗi:", error);
            }
        }

        function handleSaveTemplate() {
            if (validateForm()) {
                Modal.confirm({
                    title: 'Xác nhận tạo luồng mẫu',
                    icon: createVNode(ExclamationCircleOutlined),
                    content: `Bạn có chắc chắn muốn tạo luồng mẫu "${workflow_name.value}"?`,
                    okText: 'Đồng ý',
                    cancelText: 'Hủy',
                    onOk() {
                        saveTemplate();
                    },
                });
            }
        }

        function handleCancel() {
            router.push({ name: 'admin-workflow-templates' });
        }

        function playSound() {
            const audio = new Audio('/sounds/meow.mp3'); // đảm bảo đường dẫn đúng
            audio.play();
        }

        return {
            // Form data
            workflow_name,
            workflow_description,
            current_flow_step,

            // Options
            listDepartmentsForNewFlow,

            // Functions
            filterOption,
            addStep,
            checkIfAfterHasSameStep,
            addSameStep,
            removeStep,
            resetWorkflow,
            handleSaveTemplate,
            handleCancel,
        };
    },
});
</script>

<style scoped>
.step-card {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef !important;
}

.step-card:hover {
    border-color: #0d6efd !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.workflow-steps {
    position: relative;
}

.step-header .badge {
    font-size: 0.9rem;
}

.card-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
}

.card-header.bg-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%) !important;
}

.alert-info {
    background: linear-gradient(135deg, #cff4fc 0%, #b3e5fc 100%);
}

.text-primary {
    color: #0d6efd !important;
}

.btn-group .btn {
    border-radius: 0.375rem !important;
    margin: 0 2px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .step-card {
        margin-bottom: 2rem;
    }
    
    .d-flex.gap-2 {
        justify-content: center !important;
    }
}
</style>