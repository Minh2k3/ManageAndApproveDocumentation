<template>
    <a-card title="" style="width: 100%">
        <h2 class="fw-bold mb-3">Quản Lý Văn Bản</h2>
        <div class="row mb-3">
            <div class="col-12">
                <div class="row g-2">
                    <!-- Tìm kiếm -->
                    <div class="col-12 col-lg-4">
                        <a-input
                            placeholder="Tìm kiếm"
                            v-model:value="search"
                            allow-clear
                            enter-button
                            class="w-100"
                        >
                        </a-input>  
                    </div>

                    <!-- Bộ lọc -->
                    <div class="col-12 col-lg-8">
                        <div class="row g-2">
                            <div class="col-6 col-lg-4">
                                <a-select
                                v-model:value="status_filter"
                                show-search
                                placeholder="Trạng thái"
                                :options="document_status"
                                :filter-option="filterOption"
                                allow-clear
                                class="w-100"
                                @change="handleStatusFilter"
                                />
                            </div>
                            <div class="col-6 col-lg-4">
                                <a-select
                                v-model:value="type_filter"
                                show-search
                                placeholder="Loại văn bản"
                                :options="document_types"
                                :filter-option="filterOption"
                                allow-clear
                                class="w-100"
                                />
                            </div>
                            <div class="col-6 col-lg-4">
                                <a-select
                                v-model:value="department_filter"
                                show-search
                                placeholder="Đơn vị đề xuất"
                                :options="departments"
                                :filter-option="filterOption"
                                allow-clear
                                class="w-100"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a-table 
                    :data-source="filterData" 
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
                        <template v-else-if="column.key === 'creator_name'">
                            <a-tooltip title="Sắp xếp theo người đề xuất">
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
                            <a-tooltip title="Sắp xếp theo lần duyệt cuối">
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
                            <span>{{ record.type }}</span>
                        </template>

                        <template v-if="column.key === 'status'">
                            <a-tag v-if="record.status === 'approved'" color="green">
                                <span>Đã duyệt</span>
                            </a-tag>
                            <a-tag v-if="record.status === 'draft'" color="blue">
                                <span>Bản nháp</span>
                            </a-tag>
                            <a-tag v-if="record.status === 'in_review'" color="orange">
                                <span>Chờ duyệt</span>
                            </a-tag>
                            <a-tag v-if="record.status === 'rejected'" color="red">
                                <span>Bị từ chối</span>
                            </a-tag>
                            <!-- <span v-if="record.status === 'draft'" class="text-secondary">Bản nháp</span>
                            <span v-if="record.status === 'in_review'" class="text-primary">Chờ duyệt</span>
                            <span v-if="record.status === 'approved'" class="text-success">Đã duyệt</span>
                            <span v-if="record.status === 'rejected'" class="text-danger">Bị từ chối</span> -->
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
        v-model:open="detailVisible"
        title="Chi tiết văn bản"
        width="600px"
        z-index="10000"
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
            <a-button type="primary" @click="goToDetailPage(selectedDocument.id)">Chi tiết</a-button>
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
import { useRouter } from 'vue-router';
import {useDocumentStore} from "@/stores/admin/document-store.js";
import { useUserStore } from "@/stores/admin/user-store.js";
import { useDepartmentStore } from "@/stores/admin/department-store.js";
export default defineComponent ({
    components: {
        EyeOutlined,
    },
    setup() {
        useMenu().onSelectedKeys(["admin-documents"]);
        const router = useRouter();
        const documentStore = useDocumentStore();
        const userStore = useUserStore();
        const departmentStore = useDepartmentStore();
        const users = ref([]);
        const departments = ref([]);
        const documents = ref([]);
        const document_types = ref([]);
        const document_status = [
            { label: 'Bản nháp', value: 'draft' },
            { label: 'Chờ duyệt', value: 'in_review' },
            { label: 'Đã duyệt', value: 'approved' },
            { label: 'Bị từ chối', value: 'rejected' }
        ];

        onMounted(async () => {
            await documentStore.fetchAll();
            documents.value = documentStore.documents;
            document_types.value = documentStore.document_types;
            console.log(documents.value);
            console.log(document_types.value);

            // await userStore.fetchAll();
            users.value = userStore.users;

            await departmentStore.fetchDepartments();
            departments.value = departmentStore.departments
                .filter(department => department.group !== 'Khác');
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

        const search = ref('');
        const status_filter = ref(undefined);
        const type_filter = ref(undefined);
        const department_filter = ref(undefined);

        const filterData = computed(() => {
            let data = [...documents.value];
            if (search.value) {
                const searchText = search.value.toLowerCase();
                data = data.filter(doc => 
                    doc.title.toLowerCase().includes(searchText) ||
                    doc.type.toLowerCase().includes(searchText) ||
                    doc.creator_name.toLowerCase().includes(searchText)
                );
            }

            if (status_filter.value) {
                data = data.filter(doc => doc.status === status_filter.value);
            }

            if (type_filter.value) {
                data = data.filter(doc => doc.type_id === type_filter.value);
            }

            if (department_filter.value) {
                data = data.filter(doc => doc.department_id === department_filter.value);
            }

            return data;
        })

        const goToDetailPage = (id) => {
            router.push({
                name: 'admin-documents-detail',
                params: { id: id },
            });
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
            document_types,
            document_status,
            departments,
            columns,
            detailVisible,
            selectedDocument,
            search,
            status_filter,
            type_filter,
            department_filter,
            filterData,

            customRow,
            viewDetail,
            goToDetailPage,
        };
    },
});
</script>