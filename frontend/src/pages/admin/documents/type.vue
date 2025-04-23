<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Loại Văn Bản</h2>
        <div class="row mb-3">
            <div class="col-12">
                <div class="row g-2 d-md-flex justify-content-md-between">
                    <!-- Tìm kiếm -->
                    <div class="col-10 col-md-4">
                        <a-input-search
                        placeholder="Tìm kiếm"
                        allow-clear
                        enter-button
                        class="w-100"
                        />
                    </div>
                    <div class="col-2 col-md-2 align-items-center justify-content-end d-flex">
                        <a-button type="primary">
                        <router-link :to="{name: 'admin-documents-type' }" class="text-white">
                            <i class="fa-solid fa-plus"></i>
                        </router-link>
                        </a-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a-table :dataSource="document_types" :columns="columns" :scroll="{ x: 576 }" bordered>
                    <template #bodyCell="{ column, index, record }">
                    <template v-if="column.key === 'index'">
                        <span>{{ index + 1 }}</span>
                    </template>

                    <template v-if="column.key === 'type'">
                        <span v-if="record.type_id == 1" class="bg-primary text-white p-1 rounded rounded-1 border border-1"> {{ record.type }}</span>
                        <span v-if="record.type_id == 2" class="bg-warning text-white p-1 rounded rounded-1 border border-1"> {{ record.type }}</span>
                        <span v-if="record.type_id == 3" class="bg-success text-white p-1 rounded rounded-1 border border-1"> {{ record.type }}</span>
                    </template>

                    <template v-if="column.key === 'status'">
                        <span v-if="record.status_id == 1" class="bg-primary text-white p-1 rounded rounded-1 border border-1"> {{ record.status }}</span>
                        <span v-if="record.status_id == 2" class="bg-warning text-white p-1 rounded rounded-1 border border-1"> {{ record.status }}</span>
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
        useMenu().onSelectedKeys(["admin-documents-type"]);
        
        const document_types = ref([
            {
                type_id: 1,
                type: "Biên bản",
                description: "Biên bản họp",
                created_by: "Nguyễn Văn A",
                status_id: 1,
                status: "Đang sử dụng",
            },
            {
                type_id: 2,
                type: "Hợp đồng",
                description: "Hợp đồng lao động",
                created_by: "Nguyễn Văn B",
                status_id: 2,
                status: "Ngừng sử dụng",
            },
            {
                type_id: 3,
                type: "Quyết định",
                description: "Quyết định bổ nhiệm",
                created_by: "Nguyễn Văn C",
                status_id: 1,
                status: "Đang sử dụng",
            },
            {
                type_id: 4,
                type: "Thông báo",
                description: "Thông báo nghỉ lễ",
                created_by: "Nguyễn Văn D",
                status_id: 2,
                status: "Ngừng sử dụng",
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
                title: "Trạng thái",
                dataIndex: "status",
                key: "status",
                width: 150,
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
            document_types,
            columns,
        };
    },
});
</script>