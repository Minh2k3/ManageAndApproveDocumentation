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
                <a-table :dataSource="document_templates" :columns="columns" :scroll="{ x: 576 }" bordered>
                    <template #bodyCell="{ column, index, record }">
                        <template v-if="column.key === 'index'">
                            <span>{{ index + 1 }}</span>
                        </template>

                        <template v-if="column.key === 'type'">
                            <span v-if="record.type_id == 1" class="bg-primary text-white p-1 rounded rounded-1 border border-1"> {{ record.type }}</span>
                            <span v-if="record.type_id == 2" class="bg-warning text-white p-1 rounded rounded-1 border border-1"> {{ record.type }}</span>
                            <span v-if="record.type_id == 3" class="bg-success text-white p-1 rounded rounded-1 border border-1"> {{ record.type }}</span>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>
    </a-card>

    <a-modal v-model:visible="templateModalVisible" width="800px" centered> 
        <template #title>
            <span class="fw-bold">Thêm mẫu mới</span>
        </template>
        <template #footer>
            <div class="row d-flex justify-content-end g-2">
                <a-button 
                    type="primary" 
                    class="col-2"
                    @click="handleAddTemplate"
                    >
                    <i class="fa-solid fa-plus"></i> Thêm mới
                </a-button>
                <a-button 
                    type="default" 
                    class="col-2"
                    @click="templateModalVisible = false"
                    >
                    <i class="fa-solid fa-xmark"></i> Đóng
                </a-button>
            </div>
            
        </template>
        <div class="row">
            <div class="col-12">
                <a-form layout="vertical">
                    <a-form-item label="Tên mẫu văn bản">
                        <a-input placeholder="Nhập tên mẫu văn bản" />
                    </a-form-item>
                    <a-form-item label="Loại văn bản">
                        <a-select
                            show-search
                            placeholder="Chọn loại văn bản"
                            :options="documents_type"
                            :filter-option="filterOption"
                            allow-clear
                        />
                    </a-form-item>
                    <a-form-item label="Mô tả">
                        <a-textarea rows="4" placeholder="Nhập mô tả cho mẫu văn bản" />
                    </a-form-item>
                </a-form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h6 class="">Tệp tin đính kèm</h6>
                <a-upload 
                    v-model:file-list="upload_files" 
                    name="file" 
                    accept=".pdf" 
                    :headers="headers"
                    :show-upload-list="true" 
                    :custom-request="handleCustomRequest" 
                    :before-upload="beforeUpload"
                    :max-count="1"
                    @preview="handlePreview"
                    >
                    <a-button>
                        <span>
                            <i class="bi bi-upload me-2"></i>Chọn file PDF
                        </span>
                    </a-button>
                </a-upload>
            </div>
        </div>
    </a-modal>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { useMenu } from '@/stores/use-menu.js';
import { UploadOutlined } from '@ant-design/icons-vue';
import {useDocumentStore} from '@/stores/admin/document-store.js';

export default defineComponent({
    components: {
        UploadOutlined,
    },
    setup() {
        useMenu().onSelectedKeys(["admin-documents-template"]);
        const documentStore = useDocumentStore();
        const templateModalVisible = ref(false);
        const upload_files = ref([]);
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
        };

        const document_templates = ref([
            {
                id: 1,
                name: "Văn bản 1",
                type: "Đề xuất",
                description: "Mô tả văn bản 1",
                created_by: "Nguyễn Văn A",
                type_id: 1,
            }, 
            {
                id: 2,
                name: "Văn bản 2",
                type: "Báo cáo",
                description: "Mô tả văn bản 2",
                created_by: "Nguyễn Văn B",
                type_id: 2,
            },
            {
                id: 3,
                name: "Văn bản 3",
                type: "Thông báo",
                description: "Mô tả văn bản 3",
                created_by: "Nguyễn Văn C",
                type_id: 3,
            },
            {
                id: 4,
                name: "Văn bản 4",
                type: "Đề xuất",
                description: "Mô tả văn bản 4",
                created_by: "Nguyễn Văn D",
                type_id: 1,
            },
            {
                id: 5,
                name: "Văn bản 5",
                type: "Báo cáo",
                description: "Mô tả văn bản 5",
                created_by: "Nguyễn Văn E",
                type_id: 2,
            },
        ]);

        onMounted(async () => {
            await documentStore.fetchDocumentTemplates();
            document_templates.value = documentStore.document_templates;
        });

        const showModalAddTemplate = () => {
            templateModalVisible.value = true;
        };

        const columns = [
            {
                title: "Tên văn bản mẫu",
                dataIndex: "name",
                key: "name",
                width: 200,
            },
            {
                title: "Loại văn bản",
                dataIndex: "type",
                key: "type",
                width: 150,
            },
            {
                title: "Mô tả",
                dataIndex: "description",
                key: "description",
                width: 200,
            },
            {
                title: "Người tạo",
                dataIndex: "created_by",
                key: "created_by",
                width: 200,
                align: "center",
            },
            {
                title: "Thao tác",
                key: "action",
                fixed: "right",
                width: 100,
            }
        ]
        return {
            document_templates,
            columns,
            templateModalVisible,
            showModalAddTemplate,
        };
    },
});
</script>