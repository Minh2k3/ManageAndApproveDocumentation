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

        <!-- Bảng văn bản -->
        <div class="row">
            <div class="col-12">
                <a-table 
                    :dataSource="documents" 
                    :columns="columns" 
                    :scroll="{ x: 576 }" 
                    bordered 
                    :customRow="customRow"
                    :showSorterTooltip="false"
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
                            <span v-if="record.status === 'pending'" class="text-primary">Chờ duyệt</span>
                            <span v-if="record.status === 'approved'" class="text-success">Đã duyệt</span>
                            <span v-if="record.status === 'rejected'" class="text-danger">Bị từ chối</span>
                        </template>

                    </template>
                </a-table>
            </div>
        </div>
    </a-card>
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
        useMenu().onSelectedKeys(["approver-documents"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();

        const documents = ref([]);

        onMounted(async () => {
            await documentStore.fetchDocuments(authStore.user.id);
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
                title: 'Ngày cập nhật',
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

        const router = useRouter();

        const customRow = (record) => {
            return {
                onClick: () => {
                    router.push(`documents/${record.id}`);
                },
                style: {
                    cursor: 'pointer'
                }
            };
        };

        return {
            documents,
            columns,
            customRow,

        };
    },
});
</script>

<style>
.ant-table-column-sorters .ant-tooltip {
  display: none !important;
}
</style>