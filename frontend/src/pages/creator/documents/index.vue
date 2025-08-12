<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Quản Lý Văn Bản</h2>
        <div class="row my-3">
            <div class="d-flex justify-content-end align-items-center gap-2">
                <a-button type="primary" class="col col-xl-3">
                    <router-link class="text-decoration-none" to=documents/create >
                        <i class="fa-solid fa-add me-2"></i>Tạo văn bản mới
                    </router-link>
                </a-button>
                <a-button type="primary" class="col col-xl-3" @click="reloadDocuments">
                        <i class="fas fa-retweet me-2"></i>Tải lại văn bản
                </a-button>
            </div>
        </div>
            <!-- Bảng trạng thái -->
            <div class="row mb-3">
            <div v-for="item in statusList" :key="item.key"
                class="col-5 col-md mb-2 mb-md-0 border rounded-2 mx-2 p-3 cursor-pointer" :style="{
                    backgroundColor: selectedStatus === item.key
                        ? item.color
                        : hovered === item.key
                            ? '#f0f0f0'
                            : '#ffffff',
                    borderColor: selectedStatus === item.key || hovered === item.key
                        ? item.borderColor
                        : '#dee2e6',
                    borderWidth: '2px',
                    borderStyle: 'solid',
                    color: selectedStatus === item.key ? '#fff' : '#000',
                    transition: '0.2s'
                }" @click="selectedStatus = item.key" @mouseover="hovered = item.key" @mouseleave="hovered = null">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold fs-6">{{ item.label }}</span>
                    <span class="fw-bold fs-3">{{ item.count }}</span>
                </div>
            </div>
        </div>

        <!-- Bộ lọc và tìm kiếm -->
        <div class="row mb-3">
            <div class="d-flex flex-wrap gap-2 w-100 align-items-center">

                <!-- Tìm kiếm -->
                <div class="flex-grow-1" style="min-width: 250px;">
                    <a-input-search placeholder="Tìm kiếm" allow-clear enter-button class="w-100" />
                </div>

                <!-- Bộ lọc -->
                <div class="d-flex gap-2 flex-wrap justify-content-end ms-auto">
                    <a-select class="col" v-model:value="status_id" show-search placeholder="Trạng thái" :options="documents_status"
                        :filter-option="filterOption" allow-clear style="min-width: 150px" />

                    <a-select class="col" v-model:value="type_id" show-search placeholder="Loại văn bản" :options="documents_type"
                        :filter-option="filterOption" allow-clear style="min-width: 150px" />

                    <a-select class="col" v-model:value="department_id" show-search placeholder="Đơn vị đề xuất"
                        :options="documents_creator" :filter-option="filterOption" allow-clear
                        style="min-width: 150px" />

                    <!-- Nút lọc -->
                    <a-button class="col" type="primary">
                        <i class="fa-solid fa-filter"></i>
                    </a-button>
                </div>

            </div>
        </div>

        <!-- Bảng văn bản -->
        <div class="row">
            <div class="col-12">
                <a-empty
                    v-if="documents.length === 0"
                    image="https://gw.alipayobjects.com/mdn/miniapp_social/afts/img/A*pevERLJC9v0AAAAAAAAAAABjAQAAAQ/original"
                    :image-style="{
                    height: '60px',
                    }"
                >
                    <template #description>
                    <span>
                        Mạnh dạn tạo văn bản mới mà gửi đi chứ, rén làm gì.
                    </span>
                    </template>
                    <a-button type="primary">
                        <router-link class="text-decoration-none" to=documents/create >Bấm nếu bạn cần
                        </router-link>
                    </a-button>
                </a-empty>                
                <a-table 
                    v-else
                    :dataSource="documents" 
                    :columns="columns" 
                    :scroll="{ x: 576 }" 
                    bordered 
                    :customRow="customRow"
                    :showSorterTooltip="false"
                    :locale="{
                        triggerDesc: 'Nhấn để sắp xếp giảm dần',
                        triggerAsc: 'Nhấn để sắp xếp tăng dần',
                        cancelSort: 'Nhấn để hủy sắp xếp'
                    }"
                >
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'name'">
                            <a-tooltip title="Sắp xếp theo tên văn bản">
                                <span>{{ column.title }}</span>
                            </a-tooltip>
                        </template>
                        <template v-else-if="column.key === 'type'">
                            <a-tooltip title="Sắp xếp theo loại văn bản">
                                <span>{{ column.title }}</span>
                            </a-tooltip>
                        </template>
                        <template v-else-if="column.key === 'status'">
                            <a-tooltip title="Sắp xếp theo trạng thái">
                                <span>{{ column.title }}</span>
                            </a-tooltip>
                        </template>
                        <template v-else-if="column.key === 'created_at'">
                            <a-tooltip title="Sắp xếp theo ngày tạo">
                                <span>{{ column.title }}</span>
                            </a-tooltip>
                        </template>
                        <template v-else-if="column.key === 'updated_at'">
                            <a-tooltip title="Sắp xếp theo ngày cập nhật">
                                <span>{{ column.title }}</span>
                            </a-tooltip>
                        </template>
                    </template>

                    <template #bodyCell="{ column, index, record }">
                        <template v-if="column.key === 'index'">
                            <span>{{ index + 1 }}</span>
                        </template>

                        <template v-if="column.key === 'title'">
                            <a-tooltip>
                                <template #title>
                                    <span class="">{{ record.description }}</span>
                                </template>
                                <span class="">{{ record.title }}</span>
                            </a-tooltip>
                        </template>

                        <template v-if="column.key === 'type'">
                            <span v-if="record.type_id == 1"
                                class="bg-primary text-white p-1 rounded rounded-1 border border-1"> {{ record.type
                                }}</span>
                            <span v-if="record.type_id == 2"
                                class="bg-warning text-white p-1 rounded rounded-1 border border-1"> {{ record.type
                                }}</span>
                            <span v-if="record.type_id == 3"
                                class="bg-success text-white p-1 rounded rounded-1 border border-1"> {{ record.type
                                }}</span>
                        </template>

                        <template v-if="column.key === 'status'">
                            <div v-if="record.status === 'draft'">
                                <a-tag color="blue">
                                    <span>Bản nháp</span>
                                </a-tag>
                            </div>
                            <div v-else>
                                <a-tooltip>
                                    <template #title>
                                        <span class="">{{ showProcess(record) }}</span>
                                    </template>
                                    <a-tag v-if="record.status === 'approved'" color="green">
                                        <span>Đã duyệt</span>
                                    </a-tag>
                                    <a-tag v-if="record.status === 'in_review'" color="orange">
                                        <span>Chờ duyệt</span>
                                    </a-tag>
                                    <a-tag v-if="record.status === 'rejected'" color="red">
                                        <span>Bị từ chối</span>
                                    </a-tag>
                                </a-tooltip>
                            </div>

                        </template>
                    </template>
                </a-table>
            </div>
        </div>
    </a-card>

    <a-modal
        v-model:open="detailVisible"
        width="600px"
        :zIndex="10000"
        >
        <div>
            <h5>📄 Thông tin văn bản</h5>
            <p><strong>Tiêu đề:</strong> {{ selectedDocument.title }}</p>
            <p><strong>Mô tả:</strong> {{ selectedDocument.description }}</p>
            <p><strong>Loại văn bản:</strong> {{ selectedDocument.type }}</p>
            <p><strong>Số lượng phiên bản:</strong> {{ selectedDocument.version_count }}</p>
            <p><strong>Ngày tạo:</strong> {{ selectedDocument.created_at }}</p>
            <p><strong>Ngày cập nhật:</strong> {{ selectedDocument.updated_at }}</p>
            <p>
                <strong>Tệp:</strong>
                <a :href="`${apiUrl}/documents/${selectedDocument.file_path}`" target="_blank" class="text-decoration-none">
                    <span class="ms-1 fst-italic">Xem tệp</span>
                </a>
            </p>

            <a-divider />
        </div>

        <template #footer>
            <a-button @click="detailVisible = false">Đóng</a-button>
            <!-- <a-button v-if="selectedDocument.status !== 'in_review'" class="bg-warning" @click="goToEditPage(selectedDocument.id)">Sửa</a-button> -->
            <a-button type="primary" @click="goToDetailPage(selectedDocument.id)">Chi tiết</a-button>
        </template>
    </a-modal>
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { 
    ref, 
    defineComponent, 
    computed, 
    reactive, 
    watch, 
    onMounted, 
    createVNode,
    h 
} from 'vue';
import { useRouter } from 'vue-router';
import { useDocumentStore } from '@/stores/creator/document-store.js';
import { useDepartmentStore } from '@/stores/creator/department-store.js';
import { message, Modal } from 'ant-design-vue';
import { useAuth } from '@/stores/use-auth.js';
import { responsiveArray } from 'ant-design-vue/es/_util/responsiveObserve';

export default defineComponent({
    setup() {
        const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000';
        useMenu().onSelectedKeys(["approver-documents"]);

        const authStore = useAuth();
        const user = authStore.user;
        const departmentStore = useDepartmentStore();

        const detailVisible = ref(false);
        const selectedDocument = ref({});
        const viewDetail = (document) => {
            selectedDocument.value = document;
            detailVisible.value = true;
            console.log(selectedDocument.value.id);
        };

        const documentStore = useDocumentStore();
        let documents = ref([]);
        let document_flow_steps = ref([]);
        watch(selectedDocument, async (newDocument) => {
            if (newDocument) {
                await documentStore.fetchDocumentFlowSteps(newDocument.id);
                document_flow_steps.value = documentStore.document_flow_steps.filter(step => step.document_id === newDocument.id);
            } else {
                document_flow_steps.value = [];
            }
        });

        onMounted(async () => {
            await documentStore.fetchDocuments(user.id);
            documents.value = documentStore.documents;
        });

        const columns = [   
            {
                title: 'Tên văn bản',
                key: 'title',
                dataIndex: 'title',
                width: 200,
                sorter: (a, b) => a.title.localeCompare(b.title),
                sortDirections: ['ascend', 'descend'],
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: 'Loại văn bản',
                key: 'type',
                dataIndex: 'type',
                width: 150,
                sorter: (a, b) => a.type.localeCompare(b.type),
                sortDirections: ['ascend', 'descend'],
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: 'Trạng thái',
                key: 'status',
                dataIndex: 'status',
                width: 120,
                sorter: (a, b) => {
                    const statusOrder = {
                        'draft': 1,
                        'pending': 2,
                        'approved': 3,
                        'rejected': 4
                    };
                    return statusOrder[a.status] - statusOrder[b.status];
                },
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Số phiên bản',
                key: 'version_count',
                dataIndex: 'version_count',
                width: 120,
                sorter: (a, b) => a.version_count - b.version_count,
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Ngày tạo',
                key: 'created_at',
                dataIndex: 'created_at',
                width: 150,
                responsive: ['xl'],
                sorter: (a, b) => a.created_at.localeCompare(b.created_at),
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Ngày cập nhật',
                key: 'updated_at',
                dataIndex: 'updated_at',
                width: 150,
                sorter: (a, b) => a.updated_at.localeCompare(b.updated_at),
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            // {
            //     title: "Thao tác",
            //     key: "action",
            //     responsive: ["xl"],
            //     customHeaderCell: () => {
            //         return { style: { textAlign: 'center' } };
            //     }
            // }
        ];

        const router = useRouter();

        // Hàm xem chi tiết văn bản khi click vào dòng
        const customRow = (record) => {
            return {
                onClick: () => {
                    viewDetail(record);
                },
                style: {
                    cursor: 'pointer'
                }
            };
        };

        // Hàm điều hướng đến trang sửa văn bản
        const goToEditPage = (id) => {
            router.push(`documents/${id}/edit`);
        };

        // Hàm điều hướng đến trang chi tiết văn bản
        const goToDetailPage = (id) => {
            router.push({
                name: 'creator-documents-detail',
                params: { id: id },
            });
        };

        const showProcess = (record) => {
            return record.process + '/' + record.step_count + ' bước';
        }

        const reloadDocuments = async () => {
            await documentStore.fetchDocuments(user.id, true);
            documents.value = documentStore.documents;
            message.success('Đã tải lại danh sách văn bản');

            await departmentStore.fetchApproverHasPermissions(true);
        };

        return {
            apiUrl,
            documents,
            columns,
            document_flow_steps,
            detailVisible,
            selectedDocument,

            customRow,
            viewDetail,
            goToEditPage,
            goToDetailPage,
            showProcess,
            reloadDocuments,
        };
    },
});
</script>

<style>
.ant-table-column-sorters .ant-tooltip {
  display: none !important;
}
</style>