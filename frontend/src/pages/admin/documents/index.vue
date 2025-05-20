<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Quản Lý Văn Bản</h2>
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
                    <template #bodyCell="{ column, index, record }">
                        <template v-if="column.key === 'index'">
                            <span>{{ index + 1 }}</span>
                        </template>

                        <template v-if="column.key === 'type'">
                            <span>{{ record.type }}</span>
                        </template>

                        <template v-if="column.key === 'creator'">
                            <span>{{ record.creator_name }}</span>
                        </template>

                        <template v-if="column.key === 'status'">
                            <span v-if="record.status === 'draft'">
                                <a-tag color="default">Bản nháp</a-tag>
                            </span>
                            <span v-else-if="record.status === 'pending'">
                                <a-tag color="processing">Chờ phê duyệt</a-tag>
                            </span>
                            <span v-else-if="record.status === 'approved'">
                                <a-tag color="success">Đã phê duyệt</a-tag>
                            </span>
                            <span v-else-if="record.status === 'rejected'">
                                <a-tag color="error">Bị từ chối</a-tag>
                            </span>
                        </template>

                        <template v-if="column.key === 'created_at'">
                            <span>{{ record.created_at }}</span>
                        </template>

                        <template v-if="column.key === 'action'">
                            <a-button class="btn border">
                                <i class="bi bi-eye"></i>
                            </a-button>
                            <a-button class="btn border">
                                <i class="bi bi-eye"></i>
                            </a-button>
                        </template>

                    </template>
                </a-table>
            </div>
        </div>
    </a-card>

    <a-modal
        v-model:visible="detailVisible"
        title="Chi tiết văn bản"
        width="600px"
        >
        <div>
            <h5>📄 Thông tin văn bản</h5>
            <p><strong>Tiêu đề:</strong> {{ selectedDocument.title }}</p>
            <p><strong>Mô tả:</strong> {{ selectedDocument.description }}</p>
            <p><strong>Loại văn bản:</strong> {{ selectedDocument.type }}</p>
            <p><strong>Người đề xuất:</strong> {{ selectedDocument.creator_name }}</p>
            <p><strong>Trạng thái:</strong> 
                <span v-if="selectedDocument.status == 'draft'"> Nháp</span>
                <span v-if="selectedDocument.status == 'pending'"> Chờ phê duyệt</span>
                <span v-if="selectedDocument.status == 'approved'"> Đã phê duyệt</span>
                <span v-if="selectedDocument.status == 'rejected'"> Bị từ chối</span>
            </p>
            <p><strong>Ngày tạo:</strong> {{ selectedDocument.created_at }}</p>
            <p><strong>Ngày cập nhật:</strong> {{ selectedDocument.updated_at }}</p>
            <p>
                <strong>Tệp:</strong>
                <a :href="`http://localhost:8000/documents/${selectedDocument.file_path}`" target="_blank">
                    Xem tệp
                </a>
            </p>

            <a-divider />

            <!-- <h5>📌 Luồng phê duyệt</h5> -->
            <!-- <ol v-if="document_flow_steps.value.length > 1 || document_flow_steps.value[0].department_id !== null">
            <li v-for="step in document_flow_steps" :key="step.step">
                Bước {{ step.step }}:
                {{ step.department_name }} -
                {{ step.approver_name }} <span v-if="step.multichoice">(Cùng cấp)</span>
            </li>
            </ol> -->
            <!-- <p v-else class="text-muted fst-italic">Chưa thiết lập luồng phê duyệt</p> -->
        </div>

        <template #footer>
            <a-button @click="detailVisible = false">Đóng</a-button>
            <a-button type="primary" @click="goToEditPage(selectedDocument.id)">Sửa</a-button>
        </template>
    </a-modal>
</template>

<script>
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

import { 
    EyeOutlined,

 } from '@ant-design/icons-vue';
import { useMenu } from "@/stores/use-menu.js";
import {useDocumentStore} from "@/stores/admin/document-store.js";
import { useUserStore } from "@/stores/admin/user-store.js";

export default defineComponent ({
    components: {
        EyeOutlined,
    },
    setup() {
        useMenu().onSelectedKeys(["admin-documents"]);
        const documentStore = useDocumentStore();
        const userStore = useUserStore();
        const users = ref([]);
        const documents = ref([]);
        const document_types = ref([]);

        onMounted(async () => {
            // await documentStore.fetchAll();
            documents.value = documentStore.documents;
            document_types.value = documentStore.document_types;

            // await userStore.fetchAll();
            users.value = userStore.users;
        })

        const detailVisible = ref(false);
        const selectedDocument = ref({});
        const viewDetail = (document) => {
            selectedDocument.value = document;
            detailVisible.value = true;
            console.log(selectedDocument.value.id);
        };

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
                key: 'creator',
                dataIndex: 'creator',
                width: 200,
                sorter: (a, b) => a.creator.localeCompare(b.creator),
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

        // const getUsers = () => {
        //     axios
        //         .get('http://127.0.0.1:8000/api/users')
        //         .then(function (response) {
        //             users.value = response.data.data;
        //         })
        //         .catch(function (error) {
        //             // xử trí khi bị lỗi
        //             console.log(error);
        //         })
        //         .finally(function () {
        //             // luôn luôn được thực thi
        //         });
        //     };
        
        // getUsers();

        return {
            users,
            documents,
            columns,
            detailVisible,
            selectedDocument,

            customRow,
            viewDetail,
        };
    },
});
</script>