<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Mẫu Văn Bản</h2>
        <div class="row mb-3">
            <div class="col-12">
                <div class="row g-2">
                    <!-- Tìm kiếm -->
                    <div class="col-12 col-md-4">
                        <a-input-search
                        placeholder="Tìm kiếm"
                        allow-clear
                        enter-button
                        class="w-100"
                        />
                    </div>

                    <!-- Bộ lọc -->
                    <div class="col-12 col-md-8">
                        <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <a-select
                            v-model:value="status_id"
                            show-search
                            placeholder="Trạng thái"
                            :options="documents_status"
                            :filter-option="filterOption"
                            allow-clear
                            class="w-100"
                            />
                        </div>
                        <div class="col-6 col-md-3">
                            <a-select
                            v-model:value="type_id"
                            show-search
                            placeholder="Loại văn bản"
                            :options="documents_type"
                            :filter-option="filterOption"
                            allow-clear
                            class="w-100"
                            />
                        </div>
                        <!-- Nút tạo -->
                        <div class="col-6 col-md-1 d-flex align-items-center justify-content-end">
                            <a-button type="primary" class="w-100 w-md-auto">
                                <i class="fa-solid fa-filter "></i>
                            </a-button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3 d-flex justify-content-end">
            <div class="col-3">
                <a-button type="primary" class="w-100 w-md-auto" @click="showModalAddTemplate">
                    <i class="fa-solid fa-plus me-2"></i> Thêm mẫu mới
                </a-button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a-table 
                    :dataSource="document_templates" 
                    :columns="columns" 
                    :scroll="{ x: 576 }" 
                    bordered
                    :customRow="customRow"
                    >
                    <template #bodyCell="{ column, index, record }">
                        <template v-if="column.key === 'type'">
                            <a-tooltip>
                                <template #title>
                                    <span class="">{{ record.document_type.description }}</span>
                                </template>
                                <span>{{ record.document_type.name }}</span>
                            </a-tooltip>
                        </template>

                        <template v-if="column.key === 'status'">
                            <a-tag v-if="record.status === 'active'" color="green">
                                {{ 'Hoạt động'}}
                            </a-tag>
                            <a-tag v-else-if="record.status === 'pending'" color="orange">
                                {{ 'Chờ duyệt'}}
                            </a-tag>
                            <a-tag v-else-if="record.status === 'inactive'" color="red">
                                {{ 'Không hoạt động'}}
                            </a-tag>
                        </template>

                        <template v-else-if="column.key === 'created_by'">
                            <a-tooltip>
                                <template #title>
                                    <span class="">{{ record.creator.position_title }}</span>
                                </template>
                                <span>{{ record.creator.name }}</span>
                            </a-tooltip>
                        </template>

                        <template v-if="column.key === 'action'">
                            <a-space class="d-flex justify-content-center gap-3">
                                <a-tooltip>
                                    <template #title>
                                        <span class="">Xem chi tiết</span>
                                    </template>
                                    <a-button 
                                        @click="viewDetail(record, index)"
                                        class="bg-primary text-white"
                                        >
                                        <i class="bi bi-eye"></i>
                                    </a-button>
                                </a-tooltip>

                                <a-popconfirm v-if="record.status === 'pending'" placement="topRight" ok-text="Yes" cancel-text="No" @confirm="showConfirmActive(record)">
                                    <template #title>
                                        <span class="">Bạn có chắc chắn muốn đưa mẫu văn này vào sử dụng?</span>
                                    </template>
                                    <a-button
                                        class="bg-success text-white"
                                    >
                                        <i class="bi bi-check"></i>
                                    </a-button>
                                </a-popconfirm>

                                <a-popconfirm v-if="record.status === 'active'" placement="topRight" ok-text="Yes" cancel-text="No" @confirm="showConfirmInactive(record)">
                                    <template #title>
                                        <span class="">Bạn có chắc chắn tạm ngưng sử dụng mẫu văn bản này?</span>
                                    </template>
                                    <a-button
                                        class="bg-danger text-white"
                                    >
                                        <i class="bi bi-ban"></i>
                                    </a-button>
                                </a-popconfirm>                                

                                <a-popconfirm v-if="record.status === 'inactive'" placement="topRight" ok-text="Yes" cancel-text="No" @confirm="showConfirmActive(record)">
                                    <template #title>
                                        <span class="">Bạn có chắc chắn muốn kích hoạt lại mẫu văn bản này?</span>
                                    </template>
                                    <a-button
                                        class="bg-danger-subtle border-danger text-white"
                                    >
                                        <i class="bi bi-ban"></i>
                                    </a-button>
                                </a-popconfirm>
                            </a-space>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>
    </a-card>

    <!-- Modal add new template -->
    <a-modal 
        v-model:open="templateModalVisible" 
        width="500px" 
        centered
        :z-index="1003"
        > 
        <template #title>
            <span class="fw-bold">Thêm mẫu mới</span>
        </template>
        <template #footer>
            <div class="row d-flex justify-content-end g-2">
                <a-button 
                    type="primary" 
                    class="col-3"
                    @click="handleAddTemplate"
                    :loading="loading"
                    >
                    <i class="fa-solid fa-plus me-2"></i> Thêm mới
                </a-button>
                <a-button 
                    type="default" 
                    class="col-2"
                    @click="templateModalVisible = false"
                    >
                    <i class="fa-solid fa-xmark me-2"></i> Đóng
                </a-button>
            </div>
            
        </template>
        <div class="row">
            <div class="col-12">
                <a-form
                ref="formRef"
                :model="formData"
                :rules="rules"
                layout="vertical"
                >
                <a-form-item 
                    label="Tên mẫu văn bản" 
                    name="name"
                    has-feedback
                >
                    <a-input
                    v-model:value="formData.name"
                    allow-clear
                    placeholder="Nhập tên mẫu văn bản"
                    />
                </a-form-item>

                <a-form-item 
                    label="Loại văn bản" 
                    name="document_type_id"
                >
                    <a-select
                    v-model:value="formData.document_type_id"
                    show-search
                    placeholder="Chọn loại văn bản"
                    :options="document_types"
                    :filter-option="filterOption"
                    allow-clear
                    :list-height="200"
                    :dropdown-style="{ 
                        position: 'absolute',
                        zIndex: 10000,
                        background: 'white',
                        border: '1px solid #ccc'
                    }"
                    />
                </a-form-item>

                <a-form-item 
                    label="Mô tả" 
                    name="description"
                >
                    <a-textarea 
                    v-model:value="formData.description"
                    rows="4" 
                    placeholder="Nhập mô tả cho mẫu văn bản" 
                    />
                </a-form-item>
                </a-form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h6>Tệp tin đính kèm</h6>
                <a-form
                ref="uploadFormRef"
                :model="uploadData"
                :rules="uploadRules"
                >
                <a-form-item name="file">
                    <a-upload
                        v-model:file-list="uploadData.file"
                        name="file"
                        accept=".pdf"
                        :headers="headers"
                        :show-upload-list="true"
                        :custom-request="handleCustomRequest"
                        :before-upload="beforeUpload"
                        :max-count="1"
                        @preview="handlePreview"
                        @change="handleFileChange"
                        >
                        <a-button>
                            <span>
                            <i class="bi bi-upload me-2"></i>Chọn file PDF
                            </span>
                        </a-button>
                    </a-upload>
                </a-form-item>
                </a-form>
            </div>
        </div>
    </a-modal>

    <!-- Modal detail template -->
    <a-modal 
        v-model:open="detailModalVisible" 
        width="600px" 
        centered
        :z-index="1003"
    >
        <template #title>
            <span class="fw-bold">Chi tiết mẫu văn bản</span>
        </template>
        
        <template #footer>
            <div class="row d-flex justify-content-end g-2">
                <a-button 
                    type="primary" 
                    class="col-3"
                    @click="detailModalVisible = false"
                >
                    <i class="fa-solid fa-check me-2"></i> Đóng
                </a-button>
            </div>
        </template>

        <!-- Modal Content -->
        <div v-if="selectedTemplate" class="template-detail">
            <!-- Tên mẫu văn bản -->
            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="text-primary mb-2">
                        <i class="fa-solid fa-file-text me-2"></i>
                        {{ selectedTemplate.name }}
                    </h5>
                </div>
            </div>

            <!-- Thông tin cơ bản -->
            <div class="row mb-3">
                <div class="col-6">
                    <div class="info-item">
                        <label class="fw-bold text-muted">Loại văn bản:</label>
                        <div class="mt-1">
                            <a-tag 
                                class="px-2 py-1"
                            >
                                {{ selectedTemplate.document_type?.name }}
                            </a-tag>
                        </div>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="info-item">
                        <label class="fw-bold text-muted">Trạng thái:</label>
                        <div class="mt-1">
                            <a-tag 
                                :color="getStatusColor(selectedTemplate.status)"
                                class="px-2 py-1"
                            >
                                {{ getStatusText(selectedTemplate.status) }}
                            </a-tag>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="info-item">
                        <label class="fw-bold text-muted">Mô tả:</label>
                        <div class="mt-2 p-3 bg-light rounded">
                            <p class="mb-0" v-if="selectedTemplate.description">
                                {{ selectedTemplate.description }}
                            </p>
                            <p class="mb-0 text-muted" v-else>
                                <i>Chưa có mô tả</i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File đính kèm -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="info-item">
                        <label class="fw-bold text-muted">File đính kèm:</label>
                        <div class="mt-2">
                            <div v-if="selectedTemplate.file_path" 
                                class="file-item d-flex align-items-center justify-content-between p-3 border rounded bg-white">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-file-pdf text-danger me-2 fs-4"></i>
                                    <div>
                                        <div class="fw-medium">{{ getFileName(selectedTemplate.file_path) }}</div>
                                        <small class="text-muted">{{ formatFileSize(selectedTemplate.file_size) }}</small>
                                    </div>
                                </div>
                                <div class="file-actions">
                                    <a-button 
                                        type="link" 
                                        size="small"
                                        @click="previewFile(selectedTemplate)"
                                        class="me-2"
                                    >
                                        <i class="fa-solid fa-eye me-1"></i> Xem
                                    </a-button>
                                    <!-- <a-button 
                                        type="link" 
                                        size="small"
                                        @click="fetchFileUrl(selectedTemplate)"
                                    >
                                        <i class="fa-solid fa-download me-1"></i> Tải xuống
                                    </a-button> -->
                                </div>
                            </div>
                            <div v-else class="text-muted text-center p-3 border rounded bg-light">
                                <i class="fa-solid fa-file-slash me-2"></i>
                                Không có file đính kèm
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="row mb-3">
                <div class="col-6">
                    <div class="stat-card text-center p-3 border rounded bg-primary bg-opacity-10">
                        <div class="stat-number text-primary fs-4 fw-bold">
                            {{ selectedTemplate.downloaded || 0 }}
                        </div>
                        <div class="stat-label text-muted">
                            <span class="text-primary">
                                <i class="fa-solid fa-eye me-1"></i>
                                Lượt xem
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="stat-card text-center p-3 border rounded bg-opacity-10" style="background-color: rgba(247, 82, 123, 0.3);">
                        <div class="stat-number fs-4 fw-bold" style="color: #f7527b;">
                            {{ selectedTemplate.liked || 0 }}
                        </div>
                        <div class="stat-label text-muted">
                            <span style="color: #f7527b;">
                                <i class="fa-solid fa-heart me-1"></i>
                                Lượt thích
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin người tạo và thời gian -->
            <div class="row">
                <div class="col-12">
                    <div class="creator-info p-3 border rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a-avatar 
                                    :src="getAvatarUrl(selectedTemplate.creator?.avatar, selectedTemplate.creator?.id)" 
                                    :size="40"
                                    class="me-3"
                                >
                                    {{ selectedTemplate.creator?.name?.charAt(0) }}
                                </a-avatar>
                            </div>
                            <div class="col">
                                <div class="fw-medium">
                                    <span>{{ selectedTemplate.creator?.name }} - {{ selectedTemplate.creator?.full_name_with_role }}</span>
                                </div>
                                <small class="text-muted">
                                    Tạo lúc: {{ formatDateTime(selectedTemplate.created_at) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading state -->
        <div v-else class="text-center py-5">
            <a-spin size="large" />
            <div class="mt-3 text-muted">Đang tải thông tin...</div>
        </div>
    </a-modal>
</template>

<script>
import { defineComponent, ref, onMounted, computed, watch } from "vue";
import { useMenu } from '@/stores/use-menu.js';
import { UploadOutlined } from '@ant-design/icons-vue';
import {useDocumentStore} from '@/stores/admin/document-store.js';
import { useAuth } from '@/stores/use-auth.js';
import axiosInstance from "@/lib/axios.js";
import { message } from "ant-design-vue";

export default defineComponent({
    components: {
        UploadOutlined,
    },
    setup() {
        useMenu().onSelectedKeys(["admin-documents-template"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();

        const templateModalVisible = ref(false);
        const upload_files = ref([]);
        const headers = {
            authorization: 'authorization-text',
        };

        const document_templates = ref([]);

        const document_types = ref([]);    

        const formRef = ref();
        const uploadFormRef = ref();
        const loading = ref(false);

        const formData = ref({
            name: "",
            document_type_id: null,
            description: "",
        });

        const uploadData = ref({
            file: null,
        });

        onMounted(async () => {
            await documentStore.fetchDocumentTemplates();
            document_templates.value = documentStore.document_templates;

            await documentStore.fetchDocumentTypes();
            document_types.value = documentStore.document_types;
            console.log("Document Types:", document_types.value);
        });

        const rules = {
            name: [
                { required: true, message: "Vui lòng nhập tên mẫu văn bản" },
                { max: 100, message: "Tên mẫu văn bản không được quá 100 ký tự" },
            ],
            document_type_id: [
                { required: true, message: "Vui lòng chọn loại văn bản" },
            ],
        };

        const uploadRules = {
        file: [
            // Uncomment nếu muốn bắt buộc upload file
            { 
              required: true, 
              message: 'Vui lòng chọn file PDF', 
              trigger: 'blur' 
            }
        ]
        }        

        const filterOption = (input, option) => {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        };        

        const showModalAddTemplate = () => {
            templateModalVisible.value = true;
        };

        function handleCustomRequest({ onSuccess }) {
            setTimeout(() => {
                onSuccess('ok'); // Giả vờ thành công để upload_files cập nhật
            }, 0);
        };

        function beforeUpload(file) {
            const isPDF = file.type === 'application/pdf';
            if (!isPDF) {
                message.error('Bạn chỉ có thể upload file PDF!');
            }
            return isPDF;
        };

        function handlePreview(file) {
            let fileBlob;
            if (file.originFileObj) {
                fileBlob = file.originFileObj;
            } else if (file.url) {
                window.open(file.url, '_blank');
                return;
            }

            const blobUrl = URL.createObjectURL(fileBlob);
            window.open(blobUrl, '_blank');
        };        

        const handleFileChange = (info) => {
            uploadData.file = info.fileList.length > 0 ? info.fileList[0] : null
            
            // Trigger validation cho upload form
            uploadFormRef.value?.validateFields(['file'])
        }      
        
        // Hàm upload file
        const handleUploadFile = async ({ file, documentTemplateId }) => {
            try {
                const formData = new FormData();
                formData.append('upload_file', file);
                formData.append('document_template_id', documentTemplateId);

                const res = await axiosInstance.post('/api/document-templates/upload-file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                return {
                    status: 200,
                    message: "File uploaded successfully",
                    data: res.data,
                };

                // console.log('Đường dẫn file:', res.data.file_url);
            } catch (error) {
                // console.error(error);
                return {
                    status: 500,
                    message: "Lỗi khi upload file",
                };
            }
        };

        const handleAddTemplate = async () => {
            try {
                await formRef.value.validateFields();
                await uploadFormRef.value.validateFields(['file']);

                loading.value = true;

                const status = authStore.role === 'admin' ? 'active' : 'pending';
                // Giả lập thêm mẫu văn bản
                const newTemplate = {
                    ...formData.value,
                    created_by: authStore.user.id,
                    document_type_id: formData.value.document_type_id,
                    status: status,
                };

                console.log("New Template Data:", newTemplate);
                // return;

                const response = await axiosInstance.post('/api/document-templates', newTemplate)
                if (response.status !== 201) {
                    throw new Error("Failed to create document template");
                }

                const fileObj = uploadData.file.originFileObj;
                // console.log("File Object:", fileObj);
                // console.log("Response Data:", response.data.document_template.id);
                // return;
                const newTemplateId = response.data.document_template.id;
                const responseUpload = await handleUploadFile({
                    file: fileObj,
                    documentTemplateId: newTemplateId
                });

                if (responseUpload.status !== 200) {
                    throw new Error("Failed to upload file");
                }
                message.success("Thêm mẫu văn bản thành công!");
                document_templates.value.push(newTemplate);
                formData.value = {
                    name: "",
                    document_type_id: null,
                    description: "",
                };
                uploadData.value = {
                    file: null,
                };
                templateModalVisible.value = false;
            } catch (error) {
                console.error("Validation failed:", error);
                message.error("Đã xảy ra lỗi khi thêm mẫu văn bản. Vui lòng kiểm tra lại thông tin.");
            } finally {
                loading.value = false;
            }
        };

        const sendNotification = async (notification) => {
            try {
                const response = await axiosInstance.post('/api/notifications', notification);
                if (response.status === 201) {
                    console.log("Notification sent successfully:", response.data);
                } else {
                    console.error("Failed to send notification:", response);
                }
            } catch (error) {
                console.error("Error sending notification:", error);
            }
        };

        const handleChangeStatus = async (record, action) => {
            try {
                loading.value = true;
                const old_status = record.status;
                const response = await axiosInstance.post(`/api/document-templates/${record.id}/change-status`, {
                    status: action,
                });

                if (response.status === 200) {
                    if (old_status === 'pending') {
                        await sendNotification({
                            notification_category_id: 1, 
                            from_user_id: authStore.user.id,
                            receiver_id: record.creator.id,
                            title: `Mẫu văn bản "${record.name}" của bạn đã được chấp thuận`,
                            content: `Mẫu văn bản "${record.name}" đã được chấp thuận và có thể sử dụng.`,
                        });
                        message.success(`Mẫu văn bản "${record.name}" đã được chấp thuận để sử dụng.`);
                    } else if (action === 'active') {
                        message.success(`Mẫu văn bản "${record.name}" đã có thể kích hoạt.`);
                    } else if (action === 'inactive') {
                        message.success(`Mẫu văn bản "${record.name}" đã tạm ngừng sử dụng.`);
                    }
                    // Cập nhật lại trạng thái mẫu văn bản trong danh sách
                    const index = document_templates.value.findIndex(item => item.id === record.id);
                    if (index !== -1) {
                        document_templates.value[index].status = action;
                    }
                } else {
                    message.error('Đã xảy ra lỗi khi thay đổi trạng thái mẫu văn bản.');
                }
            } catch (error) {
                console.error('Error changing status:', error);
                message.error('Không thể thay đổi trạng thái mẫu văn bản. Vui lòng thử lại sau.');
            } finally {
                loading.value = false;
            }
        };

        const showConfirmActive = (record) => {
            // Hiển thị modal xác nhận kích hoạt mẫu văn bản
            console.log("Showing confirm active for:", record);
            handleChangeStatus(record, 'active');
        };

        const showConfirmInactive = (record) => {
            // Hiển thị modal xác nhận tạm ngừng mẫu văn bản
            console.log("Showing confirm inactive for:", record);
            handleChangeStatus(record, 'inactive');
        };

        const detailModalVisible = ref(false);

        const selectedTemplate = ref(null);
        const selectedTemplateIndex = ref(null);

        const viewDetail = async (record, index) => {
            // Hiển thị chi tiết mẫu văn bản
            console.log("Viewing details for:", record);
            detailModalVisible.value = true;
            selectedTemplateIndex.value = index;
            try {
                loading.value = true;
                detailModalVisible.value = true;
                
                // Nếu template đã có đầy đủ thông tin
                if (record.creator && record.document_type) {
                    selectedTemplate.value = record;
                } else {
                    // Fetch chi tiết từ API
                    const response = await axiosInstance.get(`/api/document-templates/${record.id}`);
                    selectedTemplate.value = response.data.document_template;
                }
            } catch (error) {
                console.error('Error loading template details:', error);
                message.error('Không thể tải thông tin chi tiết');
            } finally {
                loading.value = false;
            }
        };

        const getStatusColor = (status) => {
            const colors = {
                'active': 'green',
                'pending': 'orange',
                'inactive': 'red'
            };
            return colors[status] || 'default';
        };        

        const getStatusText = (status) => {
            const texts = {
                'active': 'Được sử dụng',
                'pending': 'Chờ duyệt',
                'inactive': 'Tạm ngừng sử dụng'
            };
            return texts[status] || status;
        };     
        
        const getFileName = (filePath) => {
            if (!filePath) return '';
            return filePath.split('/').pop() || filePath;
        };

        // Format file size
        const formatFileSize = (bytes) => {
            if (!bytes) return '';
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
        };        

        const previewFile = async (template) => {
            if (template.file_path) {
                const fileUrl = `http://localhost:8000/documents/${template.file_path}`;
                const response = await axiosInstance.get(`/api/document-templates/${template.id}/download`);
                window.open(fileUrl, '_blank');
                document_templates.value[selectedTemplateIndex.value].downloaded += 1; // Tăng lượt xem
            } else {
                message.warning('Không có file để xem');
            }
        };     

        const formatDateTime = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleString('vi-VN', {
                year: 'numeric',
                month: '2-digit', 
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        };        

        const downloadUrl = ref('');
        const fetchFileUrl = async (template) => {
            try {
                const response = await axiosInstance.get(`/api/document-templates/${template.id}/download`);
                downloadUrl.value = response.data.download_url; // Giả sử API trả về { download_url: "..." }
                await axiosInstance.get(`/documents/${downloadUrl.value}`)
            } catch (error) {
                message.error('Không thể tải file');
            }
        };

        const downloadFile = async (url) => {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Không thể tải file');
                const blob = await response.blob();
                const fileUrl = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = fileUrl;
                link.download = 'document.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(fileUrl);
            } catch (err) {
                console.error('Download error:', err);
                message.error('Không thể tải file. Vui lòng thử lại sau.');
            }
        }

        const randomAvatar = (id) => {
            if (id > 100 || id == null) {
                return `https://avatar.iran.liara.run/public`;
            }
            return `https://avatar.iran.liara.run/public/${id}`;
        };

        const API_BASE_URL = 'http://localhost:8000';

        const getAvatarUrl = (avatar, id) => {
            if (!avatar) return randomAvatar(id);
            return `${API_BASE_URL}/images/avatars/${avatar}`
        }        

        // using watch to download file when downloadUrl changes
        // watch(downloadUrl, (newUrl) => {
        //     if (newUrl) {
        //         downloadFile(newUrl);
        //     }
        // });

        // Hàm xem chi tiết văn bản khi click vào dòng
        const customRow = (record, index) => {
            return {
                onClick: () => {
                    viewDetail(record, index);
                },
                style: {
                    cursor: 'pointer'
                }
            };
        };         

        const columns = [
            {
                title: "Tên văn bản mẫu",
                dataIndex: "name",
                key: "name",
                width: 200,
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: "Loại văn bản",
                dataIndex: "type",
                key: "type",
                width: 150,
                align: "center",
            },
            {
                title: "Mô tả",
                dataIndex: "description",
                key: "description",
                width: 200,
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: "Trạng thái",
                dataIndex: "status",
                key: "status",
                width: 100,
                align: "center",
            },
            {
                title: "Người tạo",
                dataIndex: "created_by",
                key: "created_by",
                width: 150,
                align: "center",
                align: "center",
            },
            {
                title: "Thao tác",
                key: "action",
                fixed: "right",
                width: 150,
                align: "center",
            }
        ];

        return {
            document_templates,
            document_types,
            columns,
            templateModalVisible,
            upload_files,
            headers,
            rules,
            uploadRules,
            formRef,
            uploadFormRef,
            formData,
            uploadData,
            loading,

            filterOption,
            showModalAddTemplate,
            handleCustomRequest,
            beforeUpload,
            handlePreview,
            handleFileChange,
            handleAddTemplate,
            showConfirmActive,
            showConfirmInactive,
            customRow,

            detailModalVisible,
            selectedTemplate,
            viewDetail,
            getStatusColor,
            getStatusText,
            previewFile,
            getFileName,
            formatFileSize,
            formatDateTime,
            fetchFileUrl,
            getAvatarUrl,
        };
    },
});
</script>