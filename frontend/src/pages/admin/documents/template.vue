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
                    <div class="col-6 col-md-3">
                        <a-select
                        v-model:value="department_id"
                        show-search
                        placeholder="Đơn vị đề xuất"
                        :options="documents_creator"
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
                <a-table :dataSource="documents" :columns="columns" :scroll="{ x: 576 }" bordered>
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
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-documents-template"]);

    },
}
</script>