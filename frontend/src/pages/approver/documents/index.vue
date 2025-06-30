<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Quản Lý Văn Bản</h2>
        <div class="row my-3">
            <div class="d-flex justify-content-end align-items-center">
                <a-button type="primary" class="col-6 col-sm-4">
                    <router-link to=documents/create class="text-decoration-none">
                        <i class="fa-solid fa-add me-2"></i>Tạo văn bản mới
                    </router-link>
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

        <a-tabs 
            v-model:activeKey="activeKey" 
            type="card"
            class="row"
            >
            <a-tab-pane key="1" tab="Văn bản gửi đi">
                <!-- Bảng văn bản gửi đến tôi -->
                <div class="row">
                    <div class="col-12">
                        <a-empty
                            v-if="documents_of_me.length === 0"
                            image="https://gw.alipayobjects.com/mdn/miniapp_social/afts/img/A*pevERLJC9v0AAAAAAAAAAABjAQAAAQ/original"
                            :image-style="{
                            height: '60px',
                            }"
                        >
                            <template #description>
                            <span>
                                Bạn thuộc dạng đẳng cấp nên chả cần tạo văn bản phê duyệt nhỉ.
                            </span>
                            </template>
                            <a-button type="primary">Bấm nếu bạn cần</a-button>
                        </a-empty>
                        <a-table 
                            v-else
                            :dataSource="documents_of_me" 
                            :columns="columns_of_me" 
                            :scroll="{ x: 576 }" 
                            bordered 
                            :customRow="customRow"
                            :showSorterTooltip="false"
                        >
                            <template #headerCell="{ column }">
                                <template v-if="column.key === 'title'">
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

                                <template v-if="column.key === 'creator_name'">
                                    <a-tooltip>
                                        <template #title>
                                            <span class="">{{ record.roll }}</span>
                                        </template>
                                        <span>{{ record.creator_name }}</span>
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
                                    <span v-if="record.status === 'draft'" class="text-secondary">Bản nháp</span>
                                    <span v-if="record.status === 'in_review'" class="text-primary">Chờ duyệt</span>
                                    <span v-if="record.status === 'approved'" class="text-success">Đã duyệt</span>
                                    <span v-if="record.status === 'rejected'" class="text-danger">Bị từ chối</span>
                                </template>

                            </template>
                        </a-table>
                    </div>
                </div>
            </a-tab-pane>
            <a-tab-pane key="2" tab="Văn bản đến tôi" force-render>
                <!-- Bảng văn bản cần tôi phê duyệt -->
                <div class="row">
                    <div class="col-12">
                        <a-empty
                            v-if="documents_need_me.length === 0"
                            image="https://gw.alipayobjects.com/mdn/miniapp_social/afts/img/A*pevERLJC9v0AAAAAAAAAAABjAQAAAQ/original"
                            :image-style="{
                            height: '60px',
                            }"
                        >
                            <template #description>
                            <span>
                                Có quyền phê duyệt mà đếch ai cần đến bạn, tội ghê gớm!
                            </span>
                            </template>
                        </a-empty>
                        <a-table 
                            v-else
                            :dataSource="documents_need_me" 
                            :columns="columns_need_me" 
                            :scroll="{ x: 576 }" 
                            bordered 
                            :customRow="customRow"
                            :showSorterTooltip="false"
                        >
                            <template #headerCell="{ column }">
                                <template v-if="column.key === 'title'">
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

                                <template v-if="column.key === 'creator_name'">
                                    <a-tooltip>
                                        <template #title>
                                            <span class="">{{ record.roll }}</span>
                                        </template>
                                        <span>{{ record.creator_name }}</span>
                                    </a-tooltip>
                                </template>                        

                                <template v-if="column.key === 'type'">
                                    <span
                                        class=""> {{ record.type }}</span>
                                </template>

                                <template v-if="column.key === 'status'">
                                    <a-tooltip>
                                        <template #title>
                                            <span class="">{{ showProcess(record) }}</span>
                                        </template>
                                    
                                        <div v-if="record.document_status === 'in_review'">
                                            <span v-if="record.step_status === 'pending'" class="text-secondary">Chưa tới bạn</span>
                                            <span v-if="record.step_status === 'in_review'" class="text-primary">Chờ bạn duyệt</span>
                                            <span v-if="record.step_status === 'approved'" class="text-success">Bạn đã duyệt</span>
                                            <span v-if="record.step_status === 'rejected'" class="text-danger">Bạn từ chối</span>
                                        </div>
                                        <div v-else-if="record.document_status === 'approved'">
                                            <span class="text-success">Đã được duyệt</span>
                                        </div>
                                        <div v-else-if="record.document_status === 'rejected'">
                                            <span class="text-danger">Bị từ chối</span>
                                        </div>
                                    </a-tooltip>
                                </template>

                            </template>
                        </a-table>
                    </div>
                </div>
            </a-tab-pane>
        </a-tabs>
    </a-card>

    <a-modal
        v-model:open="detailVisible"
        width="600px"
        z-index="10000"
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
                <a :href="`${apiUrl}/documents/${selectedDocument.file_path}`" target="_blank">
                    Xem tệp
                </a>
            </p>

            <a-divider />
        </div>

        <template #footer>
            <a-button @click="detailVisible = false">Đóng</a-button>
            <!-- <a-button v-if="selectedDocument.status !== 'in_review' && selectedDocument.from_me === true" class="bg-warning" @click="goToEditPage(selectedDocument.id)">Sửa</a-button> -->
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
    createVNode 
} from 'vue';
import { useRouter } from 'vue-router';
import { useDocumentStore } from '@/stores/approver/document-store';
import { useAuth } from '@/stores/use-auth.js';
export default defineComponent({
    setup() {
        const activeKey = ref("1");
        useMenu().onSelectedKeys(["approver-documents"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();

        const documents_need_me = ref([]);
        const documents_of_me = ref([]);

        onMounted(async () => {
            await documentStore.fetchDocuments(authStore.user.id);
            documents_need_me.value = documentStore.documents.documents_need_me;
            documents_of_me.value = documentStore.documents.documents_of_me;
        });

        const columns_need_me = [   
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
                title: 'Người đề xuất',
                key: 'creator_name',
                dataIndex: 'creator_name',
                width: 200,
                sorter: (a, b) => a.creator_name.localeCompare(b.creator_name),
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Trạng thái',
                key: 'status',
                dataIndex: 'status',
                width: 120,
                sorter: (a, b) => {
                    const statusOrder = {
                        'in_review': 1,
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
                title: 'Ngày tạo',
                key: 'created_at',
                dataIndex: 'created_at',
                width: 150,
                sorter: (a, b) => {
                    // Chuyển đổi định dạng 'HH:mm:ss DD/MM/YYYY' thành 'YYYY-MM-DD HH:mm:ss' để dễ dàng so sánh
                    const dateA = a.created_at.split(' ')[1].split('/').reverse().join('-') + ' ' + a.created_at.split(' ')[0];
                    const dateB = b.created_at.split(' ')[1].split('/').reverse().join('-') + ' ' + b.created_at.split(' ')[0];

                    return dateA.localeCompare(dateB);
                },
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Lần duyệt cuối',
                key: 'updated_at',
                dataIndex: 'updated_at',
                width: 150,
                sorter: (a, b) => {
                    // Chuyển đổi định dạng 'HH:mm:ss DD/MM/YYYY' thành 'YYYY-MM-DD HH:mm:ss' để dễ dàng so sánh
                    const dateA = a.updated_at.split(' ')[1].split('/').reverse().join('-') + ' ' + a.updated_at.split(' ')[0];
                    const dateB = b.updated_at.split(' ')[1].split('/').reverse().join('-') + ' ' + b.updated_at.split(' ')[0];

                    return dateA.localeCompare(dateB);
                },
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            // {
            //     title: "Thao tác",
            //     key: "action",
            //     responsive: ["xl"],
            //     width: 150,
            //     customHeaderCell: () => {
            //         return { style: { textAlign: 'center' } };
            //     }
            // }
        ];

        const columns_of_me = [   
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
                title: 'Người đề xuất',
                key: 'creator_name',
                dataIndex: 'creator_name',
                width: 200,
                sorter: (a, b) => a.creator_name.localeCompare(b.creator_name),
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Trạng thái',
                key: 'status',
                dataIndex: 'status',
                width: 120,
                sorter: (a, b) => {
                    const statusOrder = {
                        'draft': 1,
                        'in_review': 2,
                        'approved': 3,
                        'rejected': 4
                    };
                    return statusOrder[a.status] - statusOrder[b.status];
                },
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Ngày tạo',
                key: 'created_at',
                dataIndex: 'created_at',
                width: 150,
                sorter: (a, b) => {
                    const dateA = a.created_at.split(' ')[1].split('/').reverse().join('-') + ' ' + a.created_at.split(' ')[0];
                    const dateB = b.created_at.split(' ')[1].split('/').reverse().join('-') + ' ' + b.created_at.split(' ')[0];

                    return dateA.localeCompare(dateB);
                },
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
            {
                title: 'Lần duyệt cuối',
                key: 'updated_at',
                dataIndex: 'updated_at',
                width: 150,
                sorter: (a, b) => {
                    const dateA = a.updated_at.split(' ')[1].split('/').reverse().join('-') + ' ' + a.updated_at.split(' ')[0];
                    const dateB = b.updated_at.split(' ')[1].split('/').reverse().join('-') + ' ' + b.updated_at.split(' ')[0];

                    return dateA.localeCompare(dateB);
                },
                sortDirections: ['ascend', 'descend'],
                align: 'center',
            },
        ];

        const router = useRouter();

        const showProcess = (record) => {
            return record.process + '/' + record.step_count + ' bước';
        }

        const detailVisible = ref(false);
        const selectedDocument = ref({});
        const viewDetail = (document) => {
            selectedDocument.value = document;
            detailVisible.value = true;
            console.log(selectedDocument.value.id);
        };

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
                name: 'approver-documents-detail',
                params: { id: id },
                query: { from_me: activeKey.value === '1' ? '1' : '0' }
            });
        };

        const VITE_API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

        return {
            apiUrl: VITE_API_BASE_URL,
            documents_need_me,
            documents_of_me,
            columns_need_me,
            columns_of_me,
            activeKey,
            detailVisible,
            selectedDocument,

            customRow,
            showProcess,
            goToEditPage,
            goToDetailPage,

        };
    },
});
</script>

<style>
.ant-table-column-sorters .ant-tooltip {
  display: none !important;
}
</style>