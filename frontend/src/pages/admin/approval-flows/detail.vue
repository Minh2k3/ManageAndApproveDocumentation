<template>
    <div class="container card p-3">
        <!-- Header section với màu xanh của trường TLU -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">
                    Chi tiết luồng phê duyệt mẫu
                </h2>
                <span class="text-secondary fst-italic">Thông tin chi tiết luồng phê duyệt mẫu và các bước phê duyệt</span>
            </div>
            <div>
                <a-button 
                    type="primary" 
                    @click="goBack"
                    size="large"
                >
                    <template #icon>
                        <i class="bi bi-arrow-left me-2"></i>
                    </template>
                    Quay lại
                </a-button>
            </div>
        </div>

        <!-- Loading state -->
        <div v-if="isLoading" class="text-center my-5 py-5">
            <a-spin tip="Đang tải thông tin...">
                <a-icon type="loading" style="font-size: 24px;" />
            </a-spin>
        </div>

        <div v-else>
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
                                <span>Tên luồng mẫu:</span>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <div class="border rounded p-3 bg-light">
                                {{ templateData.name }}
                            </div>
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
                            <div class="border rounded p-3 bg-light" style="min-height: 80px">
                                {{ templateData.description || 'Không có mô tả' }}
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="row">
                        <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center">
                            <label class="form-label fw-semibold">
                                <span>Thông tin bổ sung:</span>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <div class="d-flex flex-wrap gap-3">
                                <div class="badge bg-primary p-2">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Ngày tạo: {{ formatDate(templateData.created_at) }}
                                </div>
                                <div class="badge bg-success p-2">
                                    <i class="bi bi-person me-1"></i>
                                    Người tạo: {{ templateData.created_by?.name || 'Không xác định' }}
                                </div>
                                <div class="badge bg-info p-2">
                                    <i class="bi bi-layers me-1"></i>
                                    Số cấp duyệt: {{ templateData.process }}
                                </div>
                                <div class="badge" :class="templateData.is_active ? 'bg-success' : 'bg-warning'" p-2>
                                    <i class="bi" :class="templateData.is_active ? 'bi-check-circle' : 'bi-pause-circle'" me-1></i>
                                    Trạng thái: {{ templateData.is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cấu hình luồng phê duyệt -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-flow-chart me-2"></i>
                        Các bước trong luồng phê duyệt
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Danh sách các bước phê duyệt - CHỈ HIỂN THỊ, KHÔNG CHỈNH SỬA -->
                    <div class="workflow-steps">
                        <div v-for="(step, index) in templateData.document_flow_steps" :key="index" 
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
                                <!-- Đơn vị phê duyệt -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="bi bi-building me-1"></i>
                                        Đơn vị phê duyệt:
                                    </label>
                                    <div class="border rounded p-3 bg-white">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-building-fill me-2 text-secondary"></i>
                                            <span>{{ getDepartmentName(step.department_id) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mũi tên chỉ xuống (nếu không phải bước cuối) -->
                            <div v-if="index < templateData.document_flow_steps.length - 1" class="text-center mt-3">
                                <i class="bi bi-arrow-down fs-3 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Các nút hành động chính -->
            <div class="d-flex justify-content-center gap-3 pt-4 border-top">
                <a-button 
                    type="primary" 
                    @click="handleEdit"
                    size="large"
                    class="px-4 py-2"
                >
                    <template #icon>
                        <i class="bi bi-pencil-square me-2"></i>
                    </template>
                    Chỉnh sửa luồng
                </a-button>

                <a-button 
                    type="primary"
                    :class="templateData.is_active ? 'bg-warning border-warning' : 'bg-success border-success'"
                    @click="handleToggleStatus"
                    size="large"
                    class="px-4 py-2"
                >
                    <template #icon>
                        <i class="bi me-2" :class="templateData.is_active ? 'bi-pause-circle' : 'bi-check-circle'"></i>
                    </template>
                    {{ templateData.is_active ? 'Tạm dừng luồng' : 'Kích hoạt luồng' }}
                </a-button>

                <a-button 
                    type="primary"
                    class="bg-danger border-danger px-4 py-2"
                    @click="handleDelete"
                    size="large"
                >
                    <template #icon>
                        <i class="bi bi-trash me-2"></i>
                    </template>
                    Xóa luồng
                </a-button>
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
    ExclamationCircleOutlined,
} from '@ant-design/icons-vue';

import { message, Modal } from 'ant-design-vue';
import axiosInstance from '@/lib/axios.js';
import { useRouter, useRoute } from 'vue-router';

// Import stores
import { useMenu } from '@/stores/use-menu.js';
import { useDepartmentStore } from '@/stores/admin/department-store.js';
import { useDocumentStore } from '@/stores/admin/document-store.js';
import { useAuth } from '@/stores/use-auth.js';

export default defineComponent({
    setup() {
        const router = useRouter();
        const route = useRoute();
        const authStore = useAuth();
        const departmentStore = useDepartmentStore();
        const documentStore = useDocumentStore();
        
        // Set active menu
        useMenu().onSelectedKeys(["admin-approval-flows-template"]);

        // Template ID từ route
        const templateId = route.params.id;
        console.log("Template ID:", templateId);
        
        // Loading state
        const isLoading = ref(true);

        // Dữ liệu luồng mẫu
        const document_templates = ref([]);
        const templateData = computed(() => {
            return document_templates.value.find(template => template.id == templateId) || {};
        });

        // Danh sách đơn vị
        const departments = ref([]);
        
        onMounted(async () => {
            try {
                await departmentStore.fetchDepartments();
                departments.value = departmentStore.departments;
                console.log("Danh sách đơn vị:", departments.value);

                await documentStore.fetchDocumentFlowTemplates();
                document_templates.value = documentStore.document_flow_templates;
                console.log("Danh sách luồng mẫu:", document_templates.value);
                
                isLoading.value = false;
            } catch (error) {
                console.error("Lỗi khi tải dữ liệu:", error);
                message.error("Đã xảy ra lỗi khi tải thông tin luồng mẫu");
                isLoading.value = false;
            }
        });

        // Format date
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        }

        // Lấy tên đơn vị từ id
        function getDepartmentName(departmentId) {
            const department = departments.value.find(dept => dept.id === departmentId);
            return department ? department.name : 'Không xác định';
        }

        // Quay lại trang danh sách
        function goBack() {
            router.push({ name: 'admin-approval-flows' });
        }

        // Chỉnh sửa luồng
        function handleEdit() {
            console.log("Chỉnh sửa luồng mẫu:", templateData.value.id);
        }

        // Thay đổi trạng thái luồng
        async function handleToggleStatus() {
            console.log("Thay đổi trạng thái luồng mẫu:", templateData.value.id);
            return;
            try {
                Modal.confirm({
                    title: `${templateData.value.is_active ? 'Tạm dừng' : 'Kích hoạt'} luồng mẫu`,
                    icon: createVNode(ExclamationCircleOutlined),
                    content: `Bạn có chắc chắn muốn ${templateData.value.is_active ? 'tạm dừng' : 'kích hoạt'} luồng mẫu "${templateData.value.name}"?`,
                    okText: 'Đồng ý',
                    cancelText: 'Hủy',
                    async onOk() {
                        const newStatus = !templateData.value.active;
                        await axiosInstance.patch(`/api/flow-templates/${templateId}/toggle-status`, {
                            active: newStatus
                        });
                        templateData.value.active = newStatus;
                        message.success(`Đã ${newStatus ? 'kích hoạt' : 'tạm dừng'} luồng mẫu`);
                    },
                });
            } catch (error) {
                console.error("Lỗi khi thay đổi trạng thái:", error);
                message.error("Không thể thay đổi trạng thái luồng mẫu");
            }
        }

        // Xóa luồng
        function handleDelete() {
            console.log("Xóa luồng mẫu:", templateData.value.id);
            return;
            Modal.confirm({
                title: 'Xóa luồng mẫu',
                icon: createVNode(ExclamationCircleOutlined),
                content: `Bạn có chắc chắn muốn xóa luồng mẫu "${templateData.value.name}"? Hành động này không thể hoàn tác.`,
                okText: 'Xóa',
                okType: 'danger',
                cancelText: 'Hủy',
                async onOk() {
                    try {
                        await axiosInstance.delete(`/api/flow-templates/${templateId}`);
                        message.success("Đã xóa luồng mẫu thành công");
                        router.push({ name: 'admin-workflow-templates' });
                    } catch (error) {
                        console.error("Lỗi khi xóa luồng mẫu:", error);
                        message.error("Không thể xóa luồng mẫu");
                    }
                },
            });
        }

        return {
            isLoading,
            templateData,
            formatDate,
            getDepartmentName,
            goBack,
            handleEdit,
            handleToggleStatus,
            handleDelete,
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
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
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

.card-header.bg-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
}

.text-primary {
    color: #0d6efd !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .step-card {
        margin-bottom: 2rem;
    }
}
</style>