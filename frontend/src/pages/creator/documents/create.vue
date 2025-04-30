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

                <!-- Document Name -->
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

                <!-- Document Type -->
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

                <!-- Document Description -->
                <div class="row mt-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-center ps-3">
                        <label>
                            <span>Mô tả:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-textarea placeholder="Mô tả đơn giản" v-model:value="documentDescription" show-count
                            :maxlength="1000" />

                        <div class="w-100"></div>
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="row mt-3">
                    <div class="col-sm-3 text-start mb-2 mb-sm-0 align-self-top ps-3 pt-3">
                        <label>
                            <span>Tải tệp lên:</span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <a-upload v-model:file-list="fileList" name="file" accept=".pdf" :headers="headers"
                            :show-upload-list="true" :custom-request="handleCustomRequest" :before-upload="beforeUpload"
                            @preview="handlePreview">
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
                                style="width: 100%" :options="document_flows" :filter-option="filterOption" allow-clear>
                            </a-select>
                        </div>
                    </div>

                    <div class="col-12 col-sm mt-3 mt-sm-0">
                        <!-- Nhóm select + button chung hàng -->
                        <div class="d-flex justify-content-sm-center align-items-center">
                            <span class="text-center me-2">Hoặc</span>
                            <a-button type="primary" @click="createNewWorkflow" >
                                <template #icon>
                                    <PlusOutlined />
                                </template>
                                Tạo luồng mới
                            </a-button>
                        </div>
                    </div>
                </div>

                <div class="row mt-3" v-if="document_flow_id">
                    <div v-for="(step, index) in current_flow_step" :key="index" class="mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Cấp {{ step.step }}:</h5>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3 col-12 d-flex align-items-center">
                                <label class="mb-0">Đơn vị:</label>
                            </div>
                            <div class="col-md-8 col-10">
                                <a-select v-model:value="step.department_id" style="width: 100%" :options="departments"
                                    placeholder="Chọn đơn vị" disabled />
                            </div>
                            <div class="col-md col">
                                <a-button 
                                    type="primary" 
                                    :disabled="!step.department_id || !step.approver_id"
                                    @click="addStep(step.step)"
                                    class="text-center align-self-center bg-success" >
                                    <template #icon>
                                        <PlusCircleOutlined />
                                    </template>
                                </a-button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-12 d-flex align-items-center">
                                <label class="mb-0">Người duyệt:</label>
                            </div>
                            <div class="col-md-8 col-10">
                                <a-select v-model:value="step.approver_id" style="width: 100%" :options="getApproversByDepartment(step.department_id)"
                                    placeholder="Chọn người duyệt" />
                            </div>
                            <div class="col-md col">
                                <a-button 
                                    type="primary" 
                                    :disabled="current_flow_step.length <= 1"
                                    @click="removeStep(index)"
                                    class="text-center align-self-center bg-danger" >
                                    <template #icon>
                                        <MinusCircleOutlined />
                                    </template>
                                </a-button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3" v-else>
                    <div v-for="(step, index) in current_flow_step" :key="index" class="mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Cấp {{ step.step }}:</h5>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3 col-12 d-flex align-items-center">
                                <label class="mb-0">Đơn vị:</label>
                            </div>
                            <div class="col-md-8 col-10">
                                <a-select v-model:value="step.department_id" style="width: 100%" :options="departments"
                                    placeholder="Chọn đơn vị" allowClear />
                            </div>
                            <div class="col-md col">
                                <a-button 
                                    type="primary" 
                                    :disabled="!step.department_id || !step.approver_id"
                                    @click="addStep(step.step)"
                                    class="text-center align-self-center bg-success" >
                                    <template #icon>
                                        <PlusCircleOutlined />
                                    </template>
                                </a-button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-12 d-flex align-items-center">
                                <label class="mb-0">Người duyệt:</label>
                            </div>
                            <div class="col-md-8 col-10">
                                <a-select v-model:value="step.approver_id" style="width: 100%" :options="getApproversByDepartment(step.department_id)"
                                    placeholder="Chọn người duyệt" allowClear />
                            </div>
                            <div class="col-md col">
                                <a-button 
                                    type="primary" 
                                    :disabled="current_flow_step.length <= 1"
                                    @click="removeStep(index)"
                                    class="text-center align-self-center bg-danger" >
                                    <template #icon>
                                        <MinusCircleOutlined />
                                    </template>
                                </a-button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="false">
            <div class="container-fluid p-4">
                <h2 class="text-center mb-4">Luồng phê duyệt</h2>

                <!-- Selection buttons -->
                <div class="d-flex justify-content-center mb-4">
                    <button class="btn mx-2" :class="{ 'btn-primary': !isCreatingNew, 'btn-secondary': isCreatingNew }"
                        @click="selectExistingWorkflow">
                        Chọn luồng đã có
                    </button>
                    <button class="btn mx-2" :class="{ 'btn-primary': isCreatingNew, 'btn-secondary': !isCreatingNew }"
                        @click="createNewWorkflow">
                        Tạo luồng mới
                    </button>
                </div>

                <!-- Select existing workflow modal -->
                <a-modal v-model:visible="showExistingWorkflowModal" title="Chọn luồng phê duyệt"
                    @ok="applySelectedWorkflow">
                    <a-radio-group v-model:value="selectedWorkflow">
                        <a-radio v-for="workflow in predefinedWorkflows" :key="workflow.id" :value="workflow.id">
                            {{ workflow.name }}
                        </a-radio>
                    </a-radio-group>
                </a-modal>

                <!-- Approval levels -->
                <div v-if="isCreatingNew || workflowLevels.length > 0">
                    <div v-for="(level, index) in workflowLevels" :key="index" class="mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Cấp {{ index + 1 }}:</h5>
                            <div>
                                <a-button v-if="canAddLevel && index === workflowLevels.length - 1" type="primary"
                                    shape="circle" @click="addLevel" class="mx-1">
                                    <template #icon>
                                        <PlusOutlined />
                                    </template>
                                </a-button>
                                <a-button v-if="workflowLevels.length > 1" type="danger" shape="circle"
                                    @click="removeLevel(index)" class="mx-1">
                                    <template #icon>
                                        <MinusOutlined />
                                    </template>
                                </a-button>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3 col-12 d-flex align-items-center">
                                <label class="mb-0">Đơn vị:</label>
                            </div>
                            <div class="col-md-9 col-12">
                                <a-select v-model:value="level.unit" style="width: 100%" :options="unitOptions"
                                    placeholder="Chọn đơn vị" @change="() => checkLevelCompletion(index)" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-12 d-flex align-items-center">
                                <label class="mb-0">Người duyệt:</label>
                            </div>
                            <div class="col-md-9 col-12">
                                <a-select v-model:value="level.approver" style="width: 100%"
                                    :options="getApproverOptions(level.unit)" placeholder="Chọn người duyệt"
                                    @change="() => checkLevelCompletion(index)" />
                            </div>
                        </div>
                    </div>

                    <!-- Save button -->
                    <div class="text-center mt-4">
                        <a-button type="primary" @click="saveWorkflow" :disabled="!canSave">
                            <template #icon>
                                <SaveOutlined />
                            </template>
                            Lưu
                        </a-button>
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
import { ref, defineComponent, computed, reactive, watch } from 'vue';
import { UploadOutlined, PlusCircleOutlined, MinusCircleOutlined, SaveOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';
export default defineComponent ({
    components: {
        UploadOutlined,
        PlusCircleOutlined,
        MinusCircleOutlined,
        SaveOutlined,
    },

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

        const filterOption = (input, option) => {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        };


        const document_flow_id = ref(null);
        const documentDescription = ref('');
        const department_id = ref(null);
        const approver_id = ref(null);

        const document_flows = ref([
            { value: 1, label: 'LCĐ Khoa -> Đoàn TN -> P7' },
            { value: 2, label: 'HSV -> P7' },
            { value: 3, label: 'Khoa -> ĐTN -> P7' },
        ]);

        const document_flow_steps = ref([
            { document_flow_id: 1, step: 1, approver_id: 1, department_id: 1 },        
            { document_flow_id: 1, step: 2, approver_id: 2, department_id: 1 }, 
            { document_flow_id: 1, step: 2, approver_id: 6, department_id: 2 },
            { document_flow_id: 1, step: 3, approver_id: 3, department_id: 1 }, 
            { document_flow_id: 2, step: 1, approver_id: 5, department_id: 3 },
            { document_flow_id: 2, step: 2, approver_id: 4, department_id: 1 },
            { document_flow_id: 2, step: 3, approver_id: 8, department_id: 2 },
            { document_flow_id: 3, step: 1, approver_id: 1, department_id: 1 },
            { document_flow_id: 3, step: 2, approver_id: 7, department_id: 3 },
            { document_flow_id: 3, step: 2, approver_id: 8, department_id: 3 },
            { document_flow_id: 3, step: 3, approver_id: 3, department_id: 2 },
        ]);

        const approver = ref([
            { value: 1, label: 'Thầy Võ Tá Hoàng - BT LCD', department_id: 1 },
            { value: 2, label: 'Thầy Nguyễn Đình Trình - PP7', department_id: 1 },
            { value: 3, label: 'Anh Nguyễn Minh Tuấn - CT HSV', department_id: 1 },
            { value: 4, label: 'Cô Nguyễn Thu Nga - BT ĐTN', department_id: 2 },
            { value: 5, label: 'Chị Nguyễn Thị Hương - TP', department_id: 2 },
            { value: 6, label: 'Chị Lê Phương Thảo - PP7', department_id: 2 },
            { value: 7, label: 'Chị Phan Thị Trang Nhung - PCT HSV', department_id: 3 },
            { value: 8, label: 'Chị Chu Ngọc Thúy - PCT HSV', department_id: 3 },
            { value: 9, label: 'Thầy Đặng Ngọc Duyên - PBT ĐTN', department_id: 3 }
        ]);

        const departments = ref([
            { value: 1, label: 'Khoa CNTT' },
            { value: 2, label: 'Khoa Điện tử Viễn thông' },
            { value: 3, label: 'Khoa Cơ khí' },
        ]);

        const current_flow_step = ref([
            {
                step: 1,
                department_id: null,
                approver_id: null,
            },
        ]);

        function getCurrentFlowStep() {
            if (!document_flow_id.value) {
                current_flow_step.value = [
                    {
                        step: 1,
                        department_id: null,
                        approver_id: null,
                    },
                ];
                return;
            }

            current_flow_step.value = document_flow_steps.value.filter((step) => step.document_flow_id === document_flow_id.value);
        }

        watch(document_flow_id, () => {
            getCurrentFlowStep();
        });


        function createNewWorkflow() {
            // 1. Reset luồng mẫu đã chọn
            document_flow_id.value = null;

            // 2. Chuyển sang chế độ tạo mới (nếu có flag)
            isCreatingNew.value = true;
        }

        function getApproversByDepartment(departmentId) {
            if (!departmentId) return [];
            const approver_of_department = ref(approver.value.filter(item => item.department_id === departmentId));
            return approver_of_department.value.map(item => ({ value: item.value, label: item.label }));
        }

        function onDepartmentChange(step, index) {
            step.approver_id = null; // reset người duyệt nếu đổi đơn vị
        }

        function playSound() {
            const audio = new Audio('/sounds/meow.mp3'); // đảm bảo đường dẫn đúng
            audio.play();
        }

        function addStep(index) {
            const newStep = {
                step: current_flow_step.value.length + 1,
                department_id: null,
                approver_id: null,
            };
            current_flow_step.value.splice(index + 1, 0, newStep);
            current_flow_step.value.forEach((step, i) => {
                step.step = i + 1;
            });
            playSound();
        }

        function removeStep(index) {
            current_flow_step.value.splice(index, 1); // Xóa phần tử tại index

            // Cập nhật lại số thứ tự các bước
            current_flow_step.value.forEach((step, i) => {
                step.step = i + 1;
            });
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

            filterOption,
            document_flows,
            document_flow_id,
            documentDescription,
            document_flow_steps,
            current_flow_step,
            approver,
            departments,
            getCurrentFlowStep,
            department_id,
            approver_id,
            getApproversByDepartment,
            onDepartmentChange,
            createNewWorkflow,
            addStep,
            removeStep,

        };
    },
});
</script>

<style>
select {
  min-width: 200px;
}
</style>