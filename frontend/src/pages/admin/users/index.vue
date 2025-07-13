<template>
    <a-card title="" style="width: 100%" class="rounded-0 mt-1">
        <h2 class="">Quản lý người dùng</h2>
        <span class="text-secondary fst-italic">Quản lý thông tin tài khoản người dùng</span>
        <div class="row mb-3">
            <div class="col-12 align-items-center justify-content-end d-flex">
                <a-button type="primary">
                   <router-link :to="{name: 'admin-users-create' }" class="text-white">
                    <i class="fa-solid fa-plus"></i>
                   </router-link>
                </a-button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <div class="row g-2">
                <!-- Tìm kiếm -->
                <div class="col-12 col-md-4 align-items-center">
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
                    <div class="col-6 col-md-4">
                        <a-select
                        v-model:value="status_id"
                        show-search
                        placeholder="Tất cả trạng thái"
                        :options="users_status"
                        :filter-option="filterOption"
                        allow-clear
                        class="w-100"
                        />
                    </div>
                    <div class="col-6 col-md-4">
                        <a-select
                        v-model:value="role_id"
                        show-search
                        placeholder="Tất cả vai trò"
                        :options="users_role"
                        :filter-option="filterOption"
                        allow-clear
                        class="w-100"
                        />
                    </div>
                    <div class="col-6 col-md-3">
                        <a-select
                        v-model:value="department_id"
                        show-search
                        placeholder="Tất cả đơn vị"
                        :options="users_department"
                        :filter-option="filterOption"
                        allow-clear
                        class="w-100"
                        />
                    </div>
                    <div class="col-6 col-md-1 d-flex align-items-center justify-content-end">
                        <a-button type="primary" class="">
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
                <a-table :dataSource="users" :columns="columns" :scroll="{ x: 576 }">
                    <!-- <template #headerCell="{ column }">
                        <span class="fw-bold align-items-center">{{ column.title }}</span>
                    </template> -->
                    <template #bodyCell="{ column, index, record }">

                        <template v-if="column.key === 'index'">
                            <span>{{ index + 1 }}</span>
                        </template>

                        <template v-if="column.key === 'name'">
                            <a-tooltip>
                                <template #title>
                                    <span class="">{{ record.roll }}</span>
                                </template>
                                <span>{{ record.name }}</span>
                            </a-tooltip>
                        </template>

                        <template v-if="column.key === 'email'">
                            <a-tooltip>
                                <template #title>
                                    <span>Click để gửi mail</span>
                                </template>
                                <a
                                    :href="`mailto:${record.email}?subject=Gửi tới ${record.name}&body=Chào ${record.name},%0D%0A%0D%0A`"
                                    class="text-decoration-none fst-italic"
                                >
                                    {{ record.email }}
                                </a>
                            </a-tooltip>
                        </template>

                        <template v-if="column.key === 'status'">
                            <a-tag v-if="record.status === 'active'" color="green">
                                <span>Hoạt động</span>
                            </a-tag>
                            <a-tag v-if="record.status === 'inactive'" color="gray">
                                <span>Chưa kích hoạt mail</span>
                            </a-tag>
                            <a-tag v-if="record.status === 'pending'" color="orange">
                                <span>Chờ duyệt</span>
                            </a-tag>
                            <a-tag v-if="record.status === 'banned'" color="red">
                                <span>Bị cấm</span>
                            </a-tag>
                        </template>
                        <template v-if="column.key === 'action'">
                            <a-space class="d-flex justify-content-center gap-3">
                                <a-tooltip>
                                    <template #title>
                                        <span class="">Xem chi tiết</span>
                                    </template>
                                    <a-button 
                                        @click="viewDetail(record)"
                                        class="bg-primary text-white"
                                        >
                                        <i class="bi bi-eye"></i>
                                    </a-button>
                                </a-tooltip>

                                <a-tooltip
                                    v-if="record.status === 'pending'"
                                    >
                                    <template #title>
                                        <span class="">Kích hoạt tài khoản</span>
                                    </template>
                                    <a-button
                                        @click="showConfirm(record, 'activate')"
                                        class="bg-success text-white"
                                    >
                                        <i class="bi bi-check"></i>
                                    </a-button>
                                </a-tooltip>

                                <a-tooltip
                                    v-if="record.status === 'active'"
                                    >
                                    <template #title>
                                        <span class="">Khóa tài khoản</span>
                                    </template>
                                    <a-button
                                        @click="showConfirm(record, 'ban')"
                                        class="bg-danger text-white"
                                    >
                                        <i class="bi bi-ban"></i>
                                    </a-button>
                                </a-tooltip>

                                <a-tooltip
                                    v-if="record.status === 'banned'"
                                    >
                                    <template #title>
                                        <span class="">Gỡ khóa tài khoản</span>
                                    </template>
                                    <a-button
                                        @click="showConfirmUnban(record)"
                                        class="bg-danger-subtle border-danger text-white"
                                    >
                                        <i class="bi bi-ban"></i>
                                    </a-button>
                                </a-tooltip>

                            </a-space>
                        </template>

                    </template>
                </a-table>
            </div>
        </div>
    </a-card>

    <a-modal
        v-model:visible="showModalDetail"
        title="Thông tin chi tiết người dùng"
        width="80%"
        :footer="null"
        @cancel="showModalDetail = false"
        :zIndex="10000"
    >
        <div v-if="currentUser">
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <span class="fw-bold">Họ tên:</span>
                    <span>{{ currentUser.name }}</span>
                </div>
                <div class="col-12 col-md-6">
                    <span class="fw-bold">Email:</span>
                    <span>{{ currentUser.email }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <span class="fw-bold">Vai trò:</span>
                    <span>{{ currentUser.roll }}</span>
                </div>
                <div class="col-12 col-md-6">
                    <span class="fw-bold">Trạng thái:</span>
                    <span>
                        <a-tag v-if="currentUser.status === 'active'" color="green">Hoạt động</a-tag>
                        <a-tag v-if="currentUser.status === 'inactive'" color="gray">Chưa kích hoạt mail</a-tag>
                        <a-tag v-if="currentUser.status === 'pending'" color="orange">Chờ duyệt</a-tag>
                        <a-tag v-if="currentUser.status === 'banned'" color="red">Bị cấm</a-tag>
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <span class="fw-bold">Đơn vị:</span>
                    <span>{{ currentUser.department }}</span>
                </div>
                <div class="col-12 col-md-6">
                    <span class="fw-bold">Số điện thoại:</span>
                    <span>{{ currentUser.phone }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <span class="fw-bold">Địa chỉ:</span>
                    <span>{{ currentUser.address }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <span class="fw-bold">Ngày đăng ký:</span>
                    <span>{{ currentUser.created_at }}</span>
                </div>
            </div>
        </div>
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
import { useRouter } from 'vue-router';
import { Modal, message } from "ant-design-vue";
import { useMenu } from "@/stores/use-menu.js";
import axiosInstance from "@/lib/axios.js";
import { useUserStore } from "@/stores/admin/user-store";
import { useCertificateStore } from "@/stores/admin/certificate-store";


export default defineComponent ({
    setup() {
        useMenu().onSelectedKeys(["admin-users"]);
        const userStore = useUserStore();
        const certificateStore = useCertificateStore();
        const router = useRouter();
        // useMenu().onOpenKeys(["admin"]);

        let users = ref([]);
        const columns = [
            {
                title: "Họ tên",
                dataIndex: "name",
                key: "name",
                width: 200,
                sorter: (a, b) => {
                    const nameA = a.name.split(' ').slice(-1).join(' ');
                    const nameB = b.name.split(' ').slice(-1).join(' ');

                    return nameA.localeCompare(nameB); // Sắp xếp theo tên
                },
                sortDirections: ['ascend', 'descend'],
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: "Email",
                dataIndex: "email",
                key: "email",
                width: 200,
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: "Chức vụ",
                dataIndex: "roll",
                key: "roll",
                responsive: ["xl"],
                width: 250,
                align: "center",
            },
            {
                title: "Trạng thái",
                dataIndex: "status",
                key: "status",
                width: 150,
                sorter: (a, b) => {
                    const statusOrder = {
                        'active': 1,
                        'banned': 2,
                        'pending': 3,
                        'inactive': 4
                    };
                    return statusOrder[a.status] - statusOrder[b.status];
                },
                sortDirections: ['ascend', 'descend'],                
                align: "center",
            },
            {
                title: "Thao tác",
                key: "action",
                fixed: "right",
                width: 100,
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            }
        ]

        onMounted(async () => {
            await userStore.fetchUsers();
            users.value = userStore.users;
            // console.log(users.value);
        });

        const showModalDetail = ref(false);
        const currentUser = ref(null);

        const viewDetail = (user) => {
            showModalDetail.value = true;
            currentUser.value = user;
        };

        const showConfirm = (user, action) => {
            Modal.confirm({
                title: `Bạn có chắc chắn muốn ${action === 'ban' ? 'cấm' : 'kích hoạt tài khoản cho'} ${user.name}?`,
                async onOk() {
                    if (action === 'ban') {
                        await axiosInstance
                            .post('/api/users/banned', {
                                id: user.id,
                            })
                        message.success(`Đã cấm ${user.name}!`);
                    } else {
                        await axiosInstance
                            .post('/api/users/active', {
                                id: user.id,
                            });

                        await certificateStore.issueCertificate(user.id);
                        
                        message.success(`Đã kích hoạt ${user.name}!`);
                    }
                    const index = users.value.findIndex(u => u.id === user.id);
                    if (index !== -1) {
                        users.value[index].status = (action === 'ban' ? 'banned' : 'active');
                    }
                },
                onCancel() {},
            });
        };

        const showConfirmUnban = (user) => {
            Modal.confirm({
                title: `Bạn có chắc chắn muốn bỏ cấm cho ${user.name}?`,
                async onOk() {
                    await axiosInstance
                        .post('/api/users/unban', {
                            id: user.id,
                        })
                    const index = users.value.findIndex(u => u.id === user.id);
                    if (index !== -1) {
                        users.value[index].status = 'active';
                    }
                    message.success(`Bỏ cám thành công!`);
                },
                onCancel() {},
            });
        };

        return {
            users,
            columns,

            showModalDetail,
            currentUser,

            viewDetail,
            showConfirm,
            showConfirmUnban,
        };
    },
});
</script>