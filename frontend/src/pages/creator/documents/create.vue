<template>
    <div class="container py-4">
        <!-- Header section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Đề Xuất Văn Bản</h1>
                <p class="text-secondary">Thêm văn bản và tạo luồng phê duyệt</p>
            </div>
        </div>

        <!-- Information Section -->
        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
            <div class="col">
                <div class="row mb-3">
                    <div class="col d-flex justify-content-center">
                        <span class="fs-5 fw-bold ">Thông tin văn bản</span>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span class="text-danger me-1">*</span>
                            <span>Tên văn bản:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-input v-model:value="documentName" placeholder="Văn bản số 1" allow-clear />

                        <div class="w-100"></div>

                        <!-- <small 
                            v-if="errors.username && firstFieldError === 'username'" 
                            class="text-danger">
                                {{ errors.username[0] }}
                        </small> -->
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span class="text-danger me-1">*</span>
                            <span>Loại văn bản:</span>
                        </label>
                    </div>
                    <div class="col-sm-3">
                        <!-- Nhóm select + button chung hàng -->
                        <div class="d-flex">
                            <a-select v-model:value="documentType" show-search placeholder="Loại" style="width: 100%"
                                :options="documentTypes" :filter-option="filterOption" allow-clear></a-select>
                        </div>

                        <!-- Lỗi nằm dưới hàng mới -->
                        <!-- <small 
                            v-if="errors.department_id && firstFieldError === 'department_id'" 
                            class="text-danger mt-1 d-block">
                                {{ errors.department_id[0] }}
                        </small> -->
                    </div>
                    <div class="col align-self-center mt-3 mt-sm-0">
                        <div class="row align-items-center ">
                            <span class="col text-start text-sm-end ps-3">Hiển thị công khai:</span>
                            <a-radio-group v-model:value="documentPublic" class="col">
                                <a-radio :value="1">Có</a-radio>
                                <a-radio :value="2">Không</a-radio>
                            </a-radio-group>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span>Mô tả:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-textarea 
                            placeholder="Mô tả đơn giản" 
                            v-model:value="documentDescription" 
                            show-count 
                            :maxlength="1000" />

                        <div class="w-100"></div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-top ps-3 pt-3">
                        <label>
                            <span>Tải tệp lên:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-upload 
                            v-model:file-list="fileList" 
                            name="file" 
                            accept=".pdf"
                            :headers="headers"
                            :show-upload-list="true"
                            :custom-request="handleCustomRequest"
                            :before-upload="beforeUpload"
                            @preview="handlePreview"
                        >
                            <a-button>
                                <UploadOutlined />
                                Chọn file PDF
                            </a-button>
                        </a-upload>

                        <div class="w-100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Section -->
        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
            <div class="col">
                <div class="row mb-3">
                    <div class="col d-flex justify-content-center">
                        <span class="fs-5 fw-bold ">Luồng phê duyệt</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col d-flex justify-content-center">

                    </div>
                </div>
            </div>
        </div>

        <!-- Document Section -->
        <!-- <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
            <div class="col">
                <div class="row mb-3">
                    <div class="col d-flex justify-content-center">
                        <span class="fs-5 fw-bold ">Phiên bản</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col d-flex justify-content-center">
                        <div class="" v-for="version in versions">

                        </div>
                    </div>
                </div>
            </div>
        </div> -->

    </div>
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { ref, defineComponent } from 'vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';
export default defineComponent ({
    setup() {
        useMenu().onSelectedKeys(["creator-documents-create"]);

        const documentName = ref('');
        const documentType = ref(null);
        const documentTypes = ref([
            { value: 'type1', label: 'Loại 1' },
            { value: 'type2', label: 'Loại 2' },
            { value: 'type3', label: 'Loại 3' },
        ]);

        const documentPublic = ref(1); // 1: Có, 2: Không

        const fileList = ref([]);
        const headers = {
            authorization: 'authorization-text',
        };

        function handleChange(info) {
            fileList.value = [...info.fileList];
            if (info.file.status === 'done') {
                message.success(`${info.file.name} upload thành công.`);
            } else if (info.file.status === 'error') {
                message.error(`${info.file.name} upload thất bại.`);
            }
        }

        function handleCustomRequest({ onSuccess }) {
            setTimeout(() => {
                onSuccess('ok'); // Giả vờ thành công để fileList cập nhật
            }, 0);
        }

        function beforeUpload(file) {
            const isPDF = file.type === 'application/pdf';
            if (!isPDF) {
                message.error('Bạn chỉ có thể upload file PDF!');
            }
            return isPDF;
        }

        function handlePreview(file) {
            let fileBlob;
            if (file.originFileObj) {
                fileBlob = file.originFileObj;
            } else if (file.url) {
                // Nếu đã upload rồi có URL thật
                window.open(file.url, '_blank');
                return;
            }

            const blobUrl = URL.createObjectURL(fileBlob);
            window.open(blobUrl, '_blank');
        }


        return {
            documentName,
            documentType,
            documentTypes,
            documentPublic,
            headers,
            fileList,
            handleChange,
            beforeUpload,
            handleCustomRequest,
            handlePreview,
        };
    },
});
</script>