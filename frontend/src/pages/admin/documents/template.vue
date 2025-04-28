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
</template>

<script>
import { defineComponent, ref } from "vue";
import { useMenu } from '@/stores/use-menu.js';
export default defineComponent({
    setup() {
        useMenu().onSelectedKeys(["admin-documents-template"]);

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
        const columns = [
            {
                title: "#",
                key: "index",
                fixed: "left",
                width: 50,
                align: "center",
            },
            {
                title: "Tên văn bản",
                dataIndex: "name",
                key: "name",
                fixed: "left",
                width: 200,
            },
            {
                title: "Loại văn bản",
                dataIndex: "type",
                key: "type",
                fixed: "left",
                width: 200,
            },
            {
                title: "Mô tả",
                dataIndex: "description",
                key: "description",
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
        };
    },
});
</script>