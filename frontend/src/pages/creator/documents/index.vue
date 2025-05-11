<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Quản Lý Văn Bản</h2>
        <div class="row my-3">
            <div class="d-flex justify-content-end align-items-center">
                <a-button type="primary" class="col-6 col-sm-4">
                    <router-link to=documents/create>
                        <i class="fa-solid fa-add  me-2"></i>Tạo văn bản mới
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
                            <span v-if="record.status_id == 1"
                                class="bg-success text-white p-1 rounded rounded-1 border border-1"> {{ record.status
                                }}</span>
                            <span v-if="record.status_id == 2"
                                class="bg-warning text-white p-1 rounded rounded-1 border border-1"> {{ record.status
                                }}</span>
                        </template>

                    </template>
                </a-table>
            </div>
        </div>
    </a-card>
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { ref, defineComponent, h } from 'vue';
import { useRouter } from 'vue-router';
export default defineComponent({
    setup() {
        useMenu().onSelectedKeys(["approver-documents"]);

        const documents = ref([
            {
                id: 1,
                name: 'Văn bản 1',
                type_id: 1,
                type: 'Văn bản đi',
                status_id: 1,
                status: 'Đã duyệt',
                created_at: '2023-10-01',
                updated_at: '2023-10-01',
            },
            {
                id: 2,
                name: 'Văn bản 2',
                type_id: 2,
                type: 'Văn bản đến',
                status_id: 2,
                status: 'Chờ duyệt',
                created_at: '2023-10-02',
                updated_at: '2023-10-02',
            }, 
            {
                id: 3,
                name: 'Văn bản 3',
                type_id: 3,
                type: 'Văn bản nội bộ',
                status_id: 1,
                status: 'Đã duyệt',
                created_at: '2023-10-03',
                updated_at: '2023-10-03',
            },
            {
                id: 4,
                name: 'Văn bản 4',
                type_id: 1,
                type: 'Văn bản đi',
                status_id: 2,
                status: 'Chờ duyệt',
                created_at: '2023-10-04',
                updated_at: '2023-10-04',
            },
            {
                id: 5,
                name: 'Văn bản 5',
                type_id: 2,
                type: 'Văn bản đến',
                status_id: 1,
                status: 'Đã duyệt',
                created_at: '2023-10-05',
                updated_at: '2023-10-05',
            },
            {
                id: 6,
                name: 'Văn bản 6',
                type_id: 3,
                type: 'Văn bản nội bộ',
                status_id: 1,
                status: 'Đã duyệt',
                created_at: '2023-10-06',
                updated_at: '2023-10-06',
            },

        ]);

        const columns = [
            // {
            //     title: 'STT',
            //     key: 'index',
            //     dataIndex: 'index',
            //     width: 50,
            // },   
            {
                title: 'Tên văn bản',
                key: 'name',
                dataIndex: 'name',
                width: 200,
                sorter: (a, b) => a.name.localeCompare(b.name),
                sortDirections: ['ascend', 'descend'],
            },
            {
                title: 'Loại văn bản',
                key: 'type',
                dataIndex: 'type',
                width: 150,
                sorter: (a, b) => a.type.localeCompare(b.type),
                sortDirections: ['ascend', 'descend'],
            },
            {
                title: 'Trạng thái',
                key: 'status',
                dataIndex: 'status',
                width: 150,
                sorter: (a, b) => a.status_id - b.status_id,
                sortDirections: ['ascend', 'descend'],
            },
            {
                title: 'Ngày tạo',
                key: 'created_at',
                dataIndex: 'created_at',
                width: 150,
                sorter: (a, b) => a.created_at.localeCompare(b.created_at),
                sortDirections: ['ascend', 'descend'],
            },
            {
                title: 'Ngày cập nhật',
                key: 'updated_at',
                dataIndex: 'updated_at',
                width: 150,
                sorter: (a, b) => a.updated_at.localeCompare(b.updated_at),
                sortDirections: ['ascend', 'descend'],
            },
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