<template>
    <div class="container py-4">
        <!-- Header section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Đề xuất văn bản</h1>
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

                <!-- Document Name -->
                <div class="row mt-3">
                    <div class="col-sm-2 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span class="text-danger me-1">*</span>
                            <span>Tên văn bản:</span>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <a-input v-model:value="document_name" placeholder="Văn bản số 1" allow-clear />

                        <div class="w-100"></div>

                        <!-- <small 
                            v-if="errors.username && firstFieldError === 'username'" 
                            class="text-danger">
                                {{ errors.username[0] }}
                        </small> -->
                    </div>
                </div>

                <!-- Document Type -->
                <div class="row mt-3">
                    <div class="col-sm-2 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span class="text-danger me-1">*</span>
                            <span>Loại văn bản:</span>
                        </label>
                    </div>
                    <div class="col-sm-4">
                        <!-- Nhóm select + button chung hàng -->
                        <div class="d-flex">
                            <a-select v-model:value="document_type" show-search placeholder="Loại" style="width: 100%"
                                :options="listDocumentTypes" :filter-option="filterOption" allow-clear></a-select>
                        </div>

                        <!-- Lỗi nằm dưới hàng mới -->
                        <!-- <small 
                            v-if="errors.department_id && firstFieldError === 'department_id'" 
                            class="text-danger mt-1 d-block">
                                {{ errors.department_id[0] }}
                        </small> -->
                    </div>
                    <div class="col align-self-center mt-3 mt-sm-0">
                        <div class="row align-items-center justify-content-sm-end">
                            <span class="col text-start text-sm-end">Hiển thị công khai:</span>
                            <a-radio-group v-model:value="document_public" class="col">
                                <a-radio :value="1">Có</a-radio>
                                <a-radio :value="2">Không</a-radio>
                            </a-radio-group>
                        </div>
                    </div>
                </div>

                <!-- Document Description -->
                <div class="row mt-3">
                    <div class="col-sm-2 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span>Mô tả:</span>
                        </label>
                    </div>
                    <div class="col-sm-10   ">
                        <a-textarea placeholder="Mô tả đơn giản" v-model:value="document_description" show-count
                            :maxlength="1000" />

                        <div class="w-100"></div>
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="row mt-3">
                    <div class="col-sm-2 text-start mb-2 mb-sm-0 align-self-top ps-3 pt-3">
                        <label>
                            <span class="text-danger me-1">*</span>
                            <span>Tải tệp lên:</span>
                        </label>
                    </div>
                    <div class="col-sm-10">
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

                <!-- Phần chọn luồng mẫu hay tự tạo -->
                <div class="row mt-3">
                    <div class="col col-sm-3 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span>Chọn luồng sẵn có:</span>
                        </label>
                    </div>
                    <div class="col-12 col-sm-6">
                        <!-- Nhóm select + button chung hàng -->
                        <div class="d-flex">
                            <a-select v-model:value="document_flow_id" show-search placeholder="Luồng mẫu"
                                style="width: 100%" :options="listDocumentFlows" :filter-option="filterOption" allow-clear>
                            </a-select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                        <!-- Nhóm select + button chung hàng -->
                        <div class="d-flex justify-content-sm-center align-items-center">
                            <span class="text-center me-2">Hoặc</span>
                            <a-button type="primary" @click="createNewWorkflow">
                                <template #icon>
                                    <PlusOutlined />
                                </template>
                                Tạo luồng mới
                            </a-button>
                        </div>
                    </div>
                </div>

                <!-- Phần form luồng phê duyệt theo mẫu -->
                <div class="row mt-3" v-if="document_flow_id">
                    <div v-for="(step, index) in current_flow_step" :key="index" class="mb-4 p-3 border rounded">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row my-2">
                                    <div
                                        class="col-md-11 col-10 d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-6 fw-bold">Cấp duyệt {{ step.step }}:</span>
                                    </div>
                                </div>

                                <div class="row my-2">
                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                        <div class="row">
                                            <div class="col-12 d-flex align-items-center mb-1">
                                                <label class="mb-md-0 mb-1">Đơn vị:</label>
                                            </div>
                                            <div class="col-12">
                                                <a-select 
                                                    v-model:value="step.department_id" 
                                                    style="width: 100%"
                                                    :options="listDepartments" 
                                                    placeholder="Chọn đơn vị" 
                                                    @change="handleDepartmentChange(step)" 
                                                    :disabled="step.multichoice === 0"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <div class="col-12 d-flex align-items-center mb-1">
                                                <label class="mb-1 mb-md-0">Người duyệt:</label>
                                            </div>
                                            <div class="col-12">
                                                <a-select 
                                                    v-model:value="step.approver_id" 
                                                    style="width: 100%"
                                                    :options="getApproversByDepartment(step.department_id)"
                                                    placeholder="Chọn người duyệt" 
                                                    allowClear 
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phần form luồng phê duyệt tự tạo -->
                <div class="row mt-3" v-else>
                    <div v-for="(step, index) in current_flow_step" :key="index" class="mb-4 p-3 border rounded">
                        <div class="row mb-2">
                            <div class="col-md-11 col-12">
                                <div class="row my-2">
                                    <div
                                        class="col-md-11 col-10 d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-6 fw-bold">Cấp duyệt {{ step.step }}:</span>
                                    </div>
                                </div>

                                <div class="row my-2">
                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                        <div class="row">
                                            <div class="col-12 d-flex align-items-center mb-1">
                                                <label class="mb-md-0 mb-1">Đơn vị:</label>
                                            </div>
                                            <div class="col-12">
                                                <a-select 
                                                    v-model:value="step.department_id" 
                                                    style="width: 100%"
                                                    :options="listDepartmentsForNewFlow" 
                                                    placeholder="Chọn đơn vị" 
                                                    @change="handleDepartmentChange(step)" 
                                                    allowClear 
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <div class="col-12 d-flex align-items-center mb-1">
                                                <label class="mb-1 mb-md-0">Người duyệt:</label>
                                            </div>
                                            <div class="col-12">
                                                <a-select 
                                                    v-model:value="step.approver_id" 
                                                    style="width: 100%"
                                                    :options="getApproversByDepartment(step.department_id)"
                                                    placeholder="Chọn người duyệt" 
                                                    allowClear 
                                                    :disabled="step.department_id === null"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md col">
                                <div class="row">
                                    <div class="col-md col mb-md-2">
                                        <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center justify-content-md-center">
                                            <a-tooltip title="Thêm cấp duyệt sau cấp hiện tại" class="mb-md-0">
                                                <span>
                                                    <a-button type="primary"
                                                        :disabled="!step.department_id || checkIfAfterHasSameStep(step.step, index)"
                                                        @click="addStep(step.step, index)"
                                                        class="text-center align-self-center bg-success">
                                                        <template #icon>
                                                            <PlusCircleOutlined />
                                                        </template>
                                                    </a-button>
                                                </span>
                                            </a-tooltip>

                                            <a-tooltip title="Thêm cấp duyệt đồng cấp với cấp hiện tại" class="mb-md-0">
                                                <span>
                                                    <a-button type="primary"
                                                        :disabled="!step.department_id"
                                                        @click="addSameStep(step.step, index)"
                                                        class="text-center align-self-center bg-warning">
                                                        <template #icon>
                                                            <DownCircleOutlined />
                                                        </template>
                                                    </a-button>
                                                </span>
                                            </a-tooltip>

                                            <a-tooltip title="Xóa cấp duyệt này">
                                                <span>
                                                    <a-button type="primary" :disabled="current_flow_step.length <= 1"
                                                        @click="removeStep(index)"
                                                        class="text-center align-self-center bg-danger">
                                                        <template #icon>
                                                            <MinusCircleOutlined />
                                                        </template>
                                                    </a-button>
                                                </span>
                                            </a-tooltip>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phần xóa toàn bộ luồng -->
                <div class="row mt-1">
                    <div class="col d-flex justify-content-end align-items-center">
                        <a-tooltip v-if="current_flow_step.length > 1 && document_flow_id === null"
                            title="Xóa toàn bộ cấp duyệt">
                            <span>
                                <a-button type="primary" @click="createNewWorkflow"
                                    class="text-center align-self-center bg-danger">
                                    <template #icon>
                                        <CloseCircleOutlined class="me-2" />
                                    </template>
                                    Xóa toàn bộ luồng
                                </a-button>
                            </span>
                        </a-tooltip>
                    </div>
                </div>

                <!-- Gửi yêu cầu và lưu nháp -->
                <div class="row mt-1">
                    <div class="col d-flex justify-content-center align-items-center gap-3">
                        <!-- Gửi yêu cầu phê duyệt -->
                        <a-tooltip
                            title="Gửi yêu cầu phê duyệt theo luồng đã tạo">
                            <span>
                                <a-button 
                                    type="primary" 
                                    @click="handleSendRequest"
                                    class="text-center align-self-center bg-primary"
                                >
                                    <span><i class="bi bi-send me-2"></i>Gửi yêu cầu</span>
                                </a-button>
                            </span>
                        </a-tooltip>

                        <!-- Lưu nháp -->
                        <a-tooltip
                            title="Lưu nháp, mai sau còn dùng lại">
                            <span>
                                <a-button 
                                    type="primary" 
                                    @click="handleSaveDraft"
                                    class="text-center align-self-center bg-secondary"
                                >
                                    <span><i class="bi bi-floppy me-2"></i>Lưu nháp</span>
                                </a-button>
                            </span>
                        </a-tooltip>
                    </div>
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
    reactive, 
    watch, 
    onMounted, 
    createVNode 
} from 'vue';

import {
    UploadOutlined,
    PlusCircleOutlined,
    DownCircleOutlined,
    MinusCircleOutlined,
    SaveOutlined,
    CloseCircleOutlined,
    ExclamationCircleOutlined,
} from '@ant-design/icons-vue';

import { message, Modal } from 'ant-design-vue';

import axiosInstance from '@/lib/axios.js';

import { useMenu } from '@/stores/use-menu.js';
import { useDocumentStore } from '@/stores/creator/document-store.js';
import { useDepartmentStore } from '@/stores/creator/department-store.js';
import { useApproverStore } from '@/stores/approver/approver-store.js';
import { useAuth } from '@/stores/use-auth.js';

import { useRouter } from 'vue-router';

export default defineComponent({
    components: {
        UploadOutlined,
        PlusCircleOutlined,
        DownCircleOutlined,
        MinusCircleOutlined,
        SaveOutlined,
        CloseCircleOutlined,
    },

    setup() {
        // Router
        const router = useRouter();

        // Store
        const authStore = useAuth();
        const user = authStore.user;

        useMenu().onSelectedKeys(["creator-documents-create"]);
        const documentStore = useDocumentStore();
        const departmentStore = useDepartmentStore();
        const approverStore = useApproverStore();

        // Form Value
        const document_name = ref('');
        const document_type = ref(null);
        const document_public = ref(1);
        const upload_files = ref([]);

        // Form Options
        let listDocumentTypes = ref([]);
        let listDocumentFlows = ref([]);
        let listApprovers = ref([]);
        let listDepartments = ref([]);
        onMounted(async () => {
            await documentStore.fetchDocumentTypes();
            listDocumentTypes.value = documentStore.document_types;

            await documentStore.fetchDocumentFlowTemplates();
            listDocumentFlows.value = documentStore.document_flow_templates;

            await approverStore.fetchApproversWithRoll();
            listApprovers.value = approverStore.approvers_with_roll;
            // console.log("Approvers: " + JSON.stringify(approver.value, null, 2));

            await departmentStore.fetchDepartmentsCanApprove();
            listDepartments.value = departmentStore.departments_can_approve;
            // console.log("Departments: " + JSON.stringify(listDepartments.value, null, 2));
            // console.log("Departments: " + JSON.stringify(listDepartmentsForNewFlow.value, null, 2));
        });

        const listDepartmentsForNewFlow = computed(() => {
            return listDepartments.value.slice(3);
        });

        const headers = {
            authorization: 'authorization-text',
        };

        function handleChange(info) {
            upload_files.value = [...info.upload_files];
            if (info.file.status === 'done') {
                message.success(`${info.file.name} upload thành công.`);
            } else if (info.file.status === 'error') {
                message.error(`${info.file.name} upload thất bại.`);
            }
            console.log(upload_files);
        }

        function handleCustomRequest({ onSuccess }) {
            setTimeout(() => {
                onSuccess('ok'); // Giả vờ thành công để upload_files cập nhật
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
                window.open(file.url, '_blank');
                return;
            }

            const blobUrl = URL.createObjectURL(fileBlob);
            window.open(blobUrl, '_blank');
        }

        const filterOption = (input, option) => {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        };

        const document_flow_id = ref(null);
        const document_description = ref('');
        const department_id = ref(null);
        const approver_id = ref(null);

        let document_flow_steps = ref([]);
        // console.log("Document_flow_step: " + JSON.stringify(document_flow_steps.value, null, 2));

        let current_flow_step = ref([
            {
                step: 1,
                multichoice: false,
                department_id: null,
                approver_id: null,
            },
        ]);

        const isUseTemplate = ref(false);
        watch(document_flow_id, () => {
            if (!document_flow_id.value) {
                current_flow_step.value = [
                    {
                        step: 1,
                        multichoice: false,
                        department_id: null,
                        approver_id: null,
                    },
                ];
                return;
            }
            isUseTemplate.value = true;
        });

        // Hàm lấy ra các bước phê duyệt trong luồng mẫu
        async function getCurrentFlowSteps(documentFlowId) {
            console.log("Đang lấy dữ liệu từ API...");
            try {
                const response = await axiosInstance.get(`api/document-flow-steps/${documentFlowId}/`, {
                    withCredentials: true,
                });

                console.log("Response:", response.data);

                // Kiểm tra nếu response trả về đúng cấu trúc
                if (response.data.document_flow_steps) {
                    document_flow_steps.value = response.data.document_flow_steps.map(step => ({
                        step: step.step,
                        multichoice: step.multichoice,
                        department_id: step.department_id,
                        approver_id: null,
                    }));
                    console.log("Đã cập nhật document_flow_steps:", document_flow_steps.value);
                } else {
                    console.error("Dữ liệu trả về không có document_flow_steps:", response.data);
                }
            } catch (error) {
                console.error("Lỗi khi lấy dữ liệu:", error);
            }
        };

        watch(isUseTemplate, async (newValue) => {
            if (newValue) {
                // console.log("Luồng mẫu đã chọn: " + document_flow_id.value);
                await getCurrentFlowSteps(document_flow_id.value);
                // console.log("document_flow_steps: " + JSON.stringify(document_flow_steps.value, null, 2));
                current_flow_step.value = [...document_flow_steps.value];
                // console.log("current_flow_step: " + JSON.stringify(current_flow_step.value, null, 2));
                // console.log("Trước: " + isUseTemplate.value);
                isUseTemplate.value = false; // Đặt lại trạng thái sử dụng mẫu
                // console.log("Sau: " + isUseTemplate.value);
            }
        });

        // Hàm tạo luồng phê duyệt mới khi người dùng chọn "Tạo luồng mới"
        function createNewWorkflow() {
            current_flow_step.value = [
                {
                    step: 1,
                    department_id: null,
                    approver_id: null,
                },
            ];
            document_flow_id.value = null;
            isUseTemplate.value = false;
        }

        // Hàm lấy ra các người phê duyệt trong một đơn vị
        function getApproversByDepartment(departmentId) {
            if (!departmentId) return [];
            const approver_of_department = ref(listApprovers.value.filter(item => item.department_id === departmentId));
            return approver_of_department.value.map(item => ({ value: item.value, label: item.label }));
        }

        // Hàm reset lại người phê duyệt khi chọn đơn vị
        function handleDepartmentChange(step) {
            step.approver_id = null;
            console.log("multichoice: " + step.multichoice);
        }

        // Hàm linh tinh
        function playSound() {
            const audio = new Audio('/sounds/meow.mp3'); // đảm bảo đường dẫn đúng
            audio.play();
        }

        // Thêm 1 bước phê duyệt vào sau
        function addStep(step, index) {
            const newStep = {
                step: step + 1,
                department_id: null,
                approver_id: null,
                multichoice: false,
            };

            current_flow_step.value.splice(index + 1, 0, newStep);

            const current_step = newStep.step;

            for (let i = index + 2; i < current_flow_step.value.length; ++i) {
                if (current_flow_step.value[i].step >= current_step) {
                    current_flow_step.value[i].step += 1;
                }
            }

            playSound();
        }

        // Kiểm tra bước phía sau có cùng cấp với bước hiện tại không
        function checkIfAfterHasSameStep(step, index) {
            if (index === current_flow_step.value.length - 1) {
                return false;
            }
            return current_flow_step.value[index + 1].step === step;
        }

        // Thêm một bước cùng cấp với bước hiện tại
        function addSameStep(step, index) {
            current_flow_step.value[index].multichoice = true;
            const newStep = {
                step: step,
                department_id: null,
                approver_id: null,
                multichoice: true,
            };
            current_flow_step.value.splice(index + 1, 0, newStep);

            for (let i = index; i < current_flow_step.value.length; ++i) {
                if (current_flow_step.value[i].step == step) {
                    multichoice = true;
                } else {
                    current_flow_step.value[i].step += 1;
                }
            }
        }

        // Xóa bước hiện tại
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
            const count_step = new Map();
            for (cur_step of current_flow_step) {
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
        }

        // Hàm upload file
        const handleUploadFile = async ({ file, documentId }) => {
            try {
                const formData = new FormData();
                formData.append('upload_file', file);
                formData.append('document_id', documentId);

                const res = await axiosInstance.post('/api/documents/upload-file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                message.success('Upload file thành công');
                // console.log('Đường dẫn file:', res.data.file_url);
            } catch (error) {
                // console.error(error);
                message.error('Lỗi khi upload file');
            }
        };

        // Validate cho gửi yêu cầu
        function validateSendRequest() {
            if (!document_name.value.trim()) {
                message.error("Hãy điền tên văn bản!");
                return false;
            }

            if (document_type.value === null) {
                message.error("Không được để trống");
                return false;
            }

            if (upload_files.value.length === 0) {
                message.error("Chưa có văn bản đưa lên");
                return false;
            }

            if (current_flow_step.value[0].department_id === null) {
                message.error("Chưa có đơn vị phê duyệt nào!");
                return false;
            }

            return true;
        }

        // Hàm xử lý gửi yêu cầu phê duyệt (Hàm gửi thông tin lên server)
        async function sendRequest() {
            const documentData = {
                title: document_name.value,
                document_type_id: document_type.value,
                description: document_description.value,
                created_by: user.id,
                is_public: document_public.value,
            }

            const documentFlowData = {
                document_flow_name: "Luồng phê duyệt cho '" + document_name.value + "' của " + user.name + " - " + new Date().toLocaleString(),
                created_by: user.id,
                current_flow_step: current_flow_step.value,
            }

            const requestData = {
                document: documentData,
                document_flow: documentFlowData,
            };

            console.log("Request data: " + JSON.stringify(requestData, null, 2));

            try {
                const res = await axiosInstance.post('/api/documents/request', requestData);
                console.log(res);
                const documentId = res.data.id;
                await handleUploadFile({
                    file: upload_files.value[0]?.originFileObj,
                    documentId: documentId,
                });
                message.success("Gửi yêu cầu phê duyệt thành công");
                router.push({ name: 'creator-documents' });    
            } catch (error) {
                message.error("Gửi yêu cầu phê duyệt thất bại");
                console.error("Lỗi khi gửi yêu cầu phê duyệt:", error);
            }
        }

        // Hàm xác nhận gửi yêu cầu phê duyệt
        function showConfirmSendRequest() {
            Modal.confirm({
                title: 'Bạn có chắc chắn gửi phê duyệt văn bản này?',
                icon: createVNode(ExclamationCircleOutlined),
                content: createVNode(
                    'div',
                    {
                        style: 'color:red;',
                    },
                    // 'Some descriptions',
                ),
                onOk() {
                    sendRequest();
                },
                onCancel() {
                    return;
                },
                
            });
        };

        // Hàm bắt sự kiện gửi yêu cầu phê duyệt
        function handleSendRequest() {
            if (validateSendRequest()) {
                showConfirmSendRequest();
            }
        }

        // Validate cho lưu nháp: Yêu cầu phải điền đủ ở phần form văn bản
        function validateSaveDraft() {
            if (!document_name.value.trim()) {
                message.error("Không thể lưu nháp khi chưa đủ thông tin văn bản");
                return false;
            }

            if (document_type.value === null) {
                message.error("Không thể lưu nháp khi chưa đủ thông tin văn bản");
                return false;
            }

            if (upload_files.value.length === 0) {
                message.error("Không thể lưu nháp khi chưa đủ thông tin văn bản");
                return false;
            }

            return true;
        }

        // Hàm xử lý lưu nháp (Hàm gửi thông tin lên server)
        async function saveDraft() {
            const documentData = {
                title: document_name.value,
                document_type_id: document_type.value,
                description: document_description.value,
                created_by: user.id,
                is_public: document_public.value,
            }

            const documentFlowData = {
                document_flow_name: "Luồng phê duyệt cho '" + document_name.value + "' của " + user.name + " - " + new Date().toLocaleString(),
                created_by: user.id,
                current_flow_step: current_flow_step.value,
            }

            const draftData = {
                document: documentData,
                document_flow: documentFlowData,
            };

            console.log("Draft data: " + JSON.stringify(draftData, null, 2));
            try {
                const res = await axiosInstance.post('/api/documents/draft', draftData);
                console.log(res);
                const documentId = res.data.id;
                await handleUploadFile({
                    file: upload_files.value[0]?.originFileObj,
                    documentId: documentId,
                });
                message.success("Lưu nháp thành công");
                router.push({ name: 'creator-documents' });
            } catch (error) {
                message.error("Lưu nháp thất bại");
                console.error("Lỗi khi lưu nháp:", error);
            }
        }

        // Hàm xác nhận lưu nháp
        function showConfirmSaveDraft() {
            Modal.confirm({
                title: 'Bạn có chắc chắn lưu nháp văn bản này?',
                icon: createVNode(ExclamationCircleOutlined),
                content: createVNode(
                    'div',
                    {
                        style: 'color:red;',
                    },
                ),
                onOk() {
                    saveDraft();
                },
                onCancel() {
                    return;
                },
            });
        };

        // Hàm bắt sự kiện lưu nháp
        function handleSaveDraft() {
            if (validateSaveDraft()) {
                showConfirmSaveDraft();
            }
        }

        return {
            // Form Value
            document_name,
            document_type,
            document_public,
            document_description,
            upload_files,
            current_flow_step,

            // Form Options
            listDocumentTypes,
            listApprovers,
            listDepartments,
            listDepartmentsForNewFlow,
            listDocumentFlows,

            headers,

            // Current Form Value
            document_flow_id,
            document_flow_steps,
            department_id,
            approver_id,

            // Form Functions
            handleChange,
            beforeUpload,
            handleCustomRequest,
            handlePreview,
            filterOption,
            getApproversByDepartment,
            handleDepartmentChange,
            createNewWorkflow,
            addStep,
            checkIfAfterHasSameStep,
            addSameStep,
            removeStep,
            handleSendRequest,
            handleSaveDraft,
        };
    },
});
</script>

<style>
select {
    min-width: 200px;
}
</style>