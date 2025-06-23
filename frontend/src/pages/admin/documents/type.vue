<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Loại Văn Bản</h2>
        <div class="row mb-3">
            <div class="col-12">
                <div class="row g-2 d-md-flex justify-content-md-between">
                    <!-- Tìm kiếm -->
                    <div class="col-10 col-md-4">
                        <a-input-search
                        placeholder="Tìm kiếm"
                        allow-clear
                        enter-button
                        class="w-100"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a-table 
                    :dataSource="document_types" 
                    :columns="columns" 
                    :scroll="{ x: 576 }" 
                    bordered
                    :customRow="customRow"
                    >
                    <template #bodyCell="{ column, index, record }">
                        <template v-if="column.key === 'index'">
                            <span>{{ index + 1 }}</span>
                        </template>

                        <template v-if="column.key === 'action'">
                            <div class="gap-2 d-flex" data-action-column>
                                <button class="btn btn-warning" @click="handleClickEdit(record)">Sửa</button>
                                <a-popconfirm title="Bạn có chắc chắn muốn xóa?" @confirm="() => documentStore.deleteDocumentType(record.id)" placement="topRight">
                                    <button class="btn btn-danger">Xóa</button>
                                </a-popconfirm>
                            </div>
                        </template>

                        <template v-if="column.key === 'is_active'">
                            <a-tag v-if="record.is_active" color="green">Đang sử dụng</a-tag>
                            <a-tag v-else color="red">Ngừng hoạt động</a-tag>
                        </template>

                    </template>
                </a-table>
            </div>
        </div>
    </a-card>

    <!-- Detail Modal --> 
    <a-modal
        v-model:open="detailTypeModal"
        width="650px"
        z-index="10000"
        :footer="null"
        class="detail-type-modal"
        >
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h3 class="modal-title">
                    <span class="title-icon">📄</span>
                    Thông tin loại văn bản
                </h3>
            </div>

            <!-- Content -->
            <div class="modal-body">
                <!-- Basic Info Card -->
                <div class="info-card">
                    <div class="info-item">
                        <span class="info-label">Tên:</span>
                        <span class="info-value">{{ selectedType.name }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Mô tả:</span>
                        <span class="info-value">{{ selectedType.description }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Số lượng chức vụ được ký:</span>
                        <span class="info-value badge">{{ selectedType.roll_at_departments?.length || 0 }}</span>
                    </div>
                </div>

                <!-- Roles Section -->
                <div class="roles-section">
                    <h4 class="section-title">
                        <span class="section-icon">👥</span>
                        Chức vụ được ký
                    </h4>
                    
                    <div class="roles-container">
                        <div 
                            v-for="(roll, index) in selectedType.roll_at_departments" 
                            :key="index"
                            class="role-item"
                        >
                            <div class="role-name">{{ roll.name }}</div>
                            <div class="role-description">{{ roll.description }}</div>
                        </div>
                        
                        <!-- Empty state -->
                        <div v-if="!selectedType.roll_at_departments || selectedType.roll_at_departments.length === 0" class="empty-state">
                            <span class="empty-icon">📝</span>
                            <p>Chưa có chức vụ nào được phép ký</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <a-button 
                    type="primary" 
                    @click="detailTypeModal = false"
                    class="close-btn"
                >
                    Đóng
                </a-button>
            </div>
        </div>
    </a-modal>

    <!-- Edit Modal -->
    <a-modal
        v-model:open="editTypeModal"
        width="800px"
        z-index="10000"
        :footer="null"
        class="edit-type-modal"
        :maskClosable="false"
    >
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header edit-header">
                <h3 class="modal-title">
                    <span class="title-icon">✏️</span>
                    Chỉnh sửa loại văn bản
                </h3>
            </div>

            <!-- Content -->
            <div class="modal-body">
                <a-form
                    :model="editForm"
                    :rules="rules"
                    ref="editFormRef"
                    layout="vertical"
                    class="edit-form"
                >
                    <!-- Basic Info Card -->
                    <div class="info-card">
                        <h4 class="card-title">
                            <span class="card-icon">📋</span>
                            Thông tin cơ bản
                        </h4>
                        
                        <a-form-item label="Tên loại văn bản" name="name">
                            <a-input 
                                v-model:value="editForm.name" 
                                placeholder="Nhập tên loại văn bản"
                                size="large"
                            />
                        </a-form-item>
                        
                        <a-form-item label="Mô tả" name="description">
                            <a-textarea 
                                v-model:value="editForm.description" 
                                placeholder="Nhập mô tả cho loại văn bản"
                                :rows="3"
                                size="large"
                            />
                        </a-form-item>

                        <a-form-item label="Trạng thái" name="is_active">
                            <a-switch 
                                v-model:checked="editForm.is_active"
                                checked-children="Đang sử dụng"
                                un-checked-children="Ngừng hoạt động"
                            />
                        </a-form-item>
                    </div>

                    <!-- Roles Selection Section -->
                    <div class="roles-selection-section">
                        <h4 class="section-title">
                            <span class="section-icon">👥</span>
                            Chọn chức vụ được phép ký
                        </h4>
                        
                        <div class="roles-selection-container">
                            <!-- Search box -->
                            <div class="roles-search">
                                <a-input-search
                                    v-model:value="roleSearchTerm"
                                    placeholder="Tìm kiếm chức vụ..."
                                    allowClear
                                    size="large"
                                />
                            </div>

                            <!-- Select All -->
                            <div class="select-all-section">
                                <a-checkbox 
                                    :indeterminate="isIndeterminate"
                                    :checked="isAllSelected"
                                    @change="handleSelectAll"
                                >
                                    <strong>Chọn tất cả ({{ selectedRoleIds.length }}/{{ filteredRoles.length }})</strong>
                                </a-checkbox>
                            </div>

                            <!-- Roles List -->
                            <div class="roles-list">
                                <a-checkbox-group v-model:value="selectedRoleIds" class="w-100">
                                    <div 
                                        v-for="role in filteredRoles" 
                                        :key="role.id"
                                        class="role-checkbox-item"
                                    >
                                        <a-checkbox :value="role.id" class="role-checkbox">
                                            <div class="role-info">
                                                <div class="role-name">{{ role.name }}</div>
                                                <div class="role-description">{{ role.description }}</div>
                                            </div>
                                        </a-checkbox>
                                    </div>
                                </a-checkbox-group>

                                <!-- Empty state for search -->
                                <div v-if="filteredRoles.length === 0" class="empty-search">
                                    <span class="empty-icon">🔍</span>
                                    <p>Không tìm thấy chức vụ nào phù hợp</p>
                                </div>
                            </div>

                            <!-- Selected roles summary -->
                            <div v-if="selectedRoleIds.length > 0" class="selected-summary">
                                <div class="summary-header">
                                    <span class="summary-title">
                                        <CheckCircleOutlined class="summary-icon" />
                                        Đã chọn {{ selectedRoleIds.length }} chức vụ
                                    </span>
                                    <a-button type="link" size="small" @click="clearAllSelection">
                                        Bỏ chọn tất cả
                                    </a-button>
                                </div>
                                <div class="selected-roles-preview">
                                    <a-tag 
                                        v-for="roleId in selectedRoleIds.slice(0, 5)" 
                                        :key="roleId"
                                        color="blue"
                                        closable
                                        @close="removeFromSelection(roleId)"
                                    >
                                        {{ getRoleName(roleId) }}
                                    </a-tag>
                                    <a-tag v-if="selectedRoleIds.length > 5" color="default">
                                        +{{ selectedRoleIds.length - 5 }} khác...
                                    </a-tag>
                                </div>
                            </div>
                        </div>
                    </div>
                </a-form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <a-space>
                    <a-button @click="handleCancelEdit">
                        Hủy
                    </a-button>
                    <a-button 
                        type="primary" 
                        @click="handleSave"
                        :loading="saving"
                    >
                        Lưu thay đổi
                    </a-button>
                </a-space>
            </div>
        </div>
    </a-modal>

</template>

<script>
import { defineComponent, ref, onMounted, computed } from "vue";
import { useMenu } from '@/stores/use-menu.js';
import {useDocumentStore} from '@/stores/admin/document-store.js';
import { useDepartmentStore } from "@/stores/admin/department-store";
import { CheckCircleOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';

export default defineComponent({
    components: {
        CheckCircleOutlined
    },
    setup() {
        useMenu().onSelectedKeys(["admin-documents-type"]);
        const documentStore = useDocumentStore();
        const departmentStore = useDepartmentStore();
        
        const document_types = ref([]);
        const roll_at_department = ref([]);

        // Modal states
        const detailTypeModal = ref(false);
        const editTypeModal = ref(false);
        const selectedType = ref({});
        const saving = ref(false);

        // Form refs
        const editFormRef = ref();

        // Role selection
        const selectedRoleIds = ref([]);
        const roleSearchTerm = ref('');

        // Edit form
        const editForm = ref({
            id: null,
            name: '',
            description: '',
            is_active: true,
            roll_at_departments: []
        });

        // Validation rules
        const rules = {
            name: [
                { required: true, message: 'Vui lòng nhập tên loại văn bản', trigger: 'blur' },
                { min: 2, max: 100, message: 'Tên phải từ 2-100 ký tự', trigger: 'blur' }
            ],
            description: [
                { max: 500, message: 'Mô tả không được quá 500 ký tự', trigger: 'blur' }
            ]
        };

        // Computed
        const filteredRoles = computed(() => {
            if (!roll_at_department.value) return [];
            if (!roleSearchTerm.value) return roll_at_department.value;
            
            return roll_at_department.value.filter(role => 
                role.name.toLowerCase().includes(roleSearchTerm.value.toLowerCase()) ||
                role.description.toLowerCase().includes(roleSearchTerm.value.toLowerCase())
            );
        });

        const isAllSelected = computed(() => {
            return filteredRoles.value.length > 0 && 
                   selectedRoleIds.value.length === filteredRoles.value.length;
        });

        const isIndeterminate = computed(() => {
            return selectedRoleIds.value.length > 0 && 
                   selectedRoleIds.value.length < filteredRoles.value.length;
        });

        onMounted(async () => {
            await documentStore.fetchDocumentTypes();
            document_types.value = documentStore.document_types;
            console.log(document_types.value);

            await departmentStore.fetchRollAtDepartment();
            roll_at_department.value = departmentStore.roll_at_department;
            console.log(roll_at_department.value);
        });

        const columns = [
            {
                title: "Loại văn bản",
                dataIndex: "name",
                key: "name",
                fixed: "left",
                width: 200,
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: "Mô tả",
                dataIndex: "description",
                key: "description",
                width: 300,
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: "Số lượt dùng",
                dataIndex: "documents_count",
                key: "documents_count",
                width: 150,
                align: "center",
            },
            {
                title: "Trạng thái",
                dataIndex: "is_active",
                key: "is_active",
                width: 150,
                align: "center",
            },
            {
                title: "Thao tác",
                key: "action",
                fixed: "right",
                width: 100,
                align: "center",
            }
        ];

        const handleClickEdit = (record) => {
            selectedType.value = record;
            editForm.value = {
                id: record.id,
                name: record.name,
                description: record.description,
                is_active: record.is_active,
                roll_at_departments: [...(record.roll_at_departments || [])]
            };
            
            // Set selected role IDs
            selectedRoleIds.value = (record.roll_at_departments || []).map(role => role.id);
            
            editTypeModal.value = true;
        };

        const handleCancelEdit = () => {
            editTypeModal.value = false;
            resetEditForm();
        };

        const handleSave = async () => {
            try {
                await editFormRef.value.validate();
                saving.value = true;
                
                // Map selected role IDs to role objects
                const selectedRoles = roll_at_department.value.filter(role => 
                    selectedRoleIds.value.includes(role.id)
                );
                
                const formData = {
                    ...editForm.value,
                    roll_at_departments: selectedRoleIds.value
                };
                
                console.log('Saving form data:', formData);
                // editTypeModal.value = false;
                // return;

                // Call API to update document type
                await documentStore.updateDocumentType(editForm.value.id, formData);
                
                // Refresh data
                await documentStore.fetchDocumentTypes(true);
                document_types.value = documentStore.document_types;
                
                message.success('Cập nhật loại văn bản thành công!');
                editTypeModal.value = false;
                resetEditForm();
            } catch (error) {
                console.error('Save failed:', error);
                message.error('Cập nhật thất bại!');
            } finally {
                saving.value = false;
            }
        };

        const handleSelectAll = (e) => {
            if (e.target.checked) {
                selectedRoleIds.value = filteredRoles.value.map(role => role.id);
            } else {
                selectedRoleIds.value = [];
            }
        };

        const clearAllSelection = () => {
            selectedRoleIds.value = [];
        };

        const removeFromSelection = (roleId) => {
            const index = selectedRoleIds.value.indexOf(roleId);
            if (index > -1) {
                selectedRoleIds.value.splice(index, 1);
            }
        };

        const getRoleName = (roleId) => {
            const role = roll_at_department.value.find(r => r.id === roleId);
            return role ? role.name : '';
        };

        const resetEditForm = () => {
            editForm.value = {
                id: null,
                name: '',
                description: '',
                is_active: true,
                roll_at_departments: []
            };
            selectedRoleIds.value = [];
            roleSearchTerm.value = '';
        };

        const customRow = (record) => {
            return {
                style: { cursor: 'pointer' },
                onClick: (event) => {
                    // Check if click is from action column
                    const target = event.target;
                    const isActionColumn = target.closest('[data-action-column]') || 
                                        target.closest('.btn') || 
                                        target.closest('.ant-popconfirm') ||
                                        target.closest('button');
                    
                    // Only open modal if not clicking on action column
                    if (!isActionColumn) {
                        selectedType.value = record;
                        detailTypeModal.value = true;
                    }
                },
            };
        };

        return {
            document_types,
            columns,
            detailTypeModal,
            editTypeModal,
            selectedType,
            editForm,
            editFormRef,
            rules,
            selectedRoleIds,
            roleSearchTerm,
            filteredRoles,
            isAllSelected,
            isIndeterminate,
            saving,
            handleClickEdit,
            handleCancelEdit,
            handleSave,
            handleSelectAll,
            clearAllSelection,
            removeFromSelection,
            getRoleName,
            customRow,
            documentStore
        };
    },
});
</script>

<style scoped>
.detail-type-modal :deep(.ant-modal-content),
.edit-type-modal :deep(.ant-modal-content) {
    padding: 0;
    overflow: hidden;
}

.modal-content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.edit-header {
    background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%);
}

.modal-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.title-icon {
    font-size: 20px;
}

.modal-body {
    padding: 24px;
    flex: 1;
    overflow-y: auto;
    max-height: 600px;
}

.edit-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-card {
    background: #fafafa;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e8e8e8;
}

.card-title {
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-icon {
    font-size: 18px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
    gap: 12px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-weight: 600;
    color: #333;
    min-width: 180px;
    flex-shrink: 0;
}

.info-value {
    color: #666;
    flex: 1;
}

.badge {
    background: #1890ff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.roles-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.roles-selection-section {
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    overflow: hidden;
}

.section-title {
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 20px;
    background: #f9f9f9;
    border-bottom: 1px solid #e8e8e8;
}

.section-icon {
    font-size: 18px;
}

.roles-selection-container {
    padding: 20px;
}

.roles-search {
    margin-bottom: 16px;
}

.select-all-section {
    margin-bottom: 16px;
    padding: 12px;
    background: #f0f8ff;
    border-radius: 6px;
    border: 1px solid #d9ecff;
}

.roles-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    padding: 8px;
    background: white;
}

.role-checkbox-item {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.role-checkbox-item:last-child {
    border-bottom: none;
}

.role-checkbox-item:hover {
    background-color: #f9f9f9;
}

.role-checkbox {
    width: 100%;
}

.role-checkbox :deep(.ant-checkbox) {
    align-self: flex-start;
    margin-top: 2px;
}

.role-info {
    margin-left: 8px;
    flex: 1;
}

.role-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
}

.role-description {
    color: #666;
    font-size: 13px;
    line-height: 1.4;
}

.empty-search {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.empty-icon {
    font-size: 32px;
    display: block;
    margin-bottom: 12px;
}

.empty-search p {
    margin: 0;
    font-style: italic;
}

.selected-summary {
    margin-top: 16px;
    padding: 16px;
    background: #f6ffed;
    border: 1px solid #b7eb8f;
    border-radius: 6px;
}

.summary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.summary-title {
    font-weight: 600;
    color: #52c41a;
    display: flex;
    align-items: center;
    gap: 8px;
}

.summary-icon {
    font-size: 16px;
}

.selected-roles-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.roles-container {
    flex: 1;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    background: white;
}

.role-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.role-item:last-child {
    border-bottom: none;
}

.role-item:hover {
    background-color: #f9f9f9;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.empty-state p {
    margin: 0;
    font-style: italic;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
    text-align: right;
}

.close-btn {
    min-width: 80px;
}

/* Scrollbar styling */
.modal-body::-webkit-scrollbar,
.roles-list::-webkit-scrollbar,
.roles-container::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track,
.roles-list::-webkit-scrollbar-track,
.roles-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb,
.roles-list::-webkit-scrollbar-thumb,
.roles-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb:hover,
.roles-list::-webkit-scrollbar-thumb:hover,
.roles-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Form styling */
.edit-form :deep(.ant-form-item-label > label) {
    font-weight: 600;
    color: #333;
}

.edit-form :deep(.ant-input),
.edit-form :deep(.ant-select-selector) {
    border-radius: 6px;
}

.edit-form :deep(.ant-input:focus),
.edit-form :deep(.ant-select-focused .ant-select-selector) {
    border-color: #52c41a;
    box-shadow: 0 0 0 2px rgba(82, 196, 26, 0.2);
}

/* Checkbox styling */
.role-checkbox :deep(.ant-checkbox-wrapper) {
    display: flex;
    align-items: flex-start;
    width: 100%;
}
</style>