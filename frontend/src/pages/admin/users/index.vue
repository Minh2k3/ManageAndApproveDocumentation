<template>
    <a-card title="" style="width: 100%" class="rounded-0 mt-1">
        <h2 class="fw-bold mb-5">Quản Lý Người Dùng</h2>
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
                            <span v-if="record.status === 'inactive'" class="text-secondary">Chưa kích hoạt</span>
                            <span v-if="record.status === 'pending'" class="text-primary">Chờ kích hoạt</span>
                            <span v-if="record.status === 'active'" class="text-success">Hoạt động</span>
                            <span v-if="record.status === 'banned'" class="text-danger">Bị cấm</span>
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
</template>

<script>
import { defineComponent, ref } from "vue";
import { Modal, message } from "ant-design-vue";
import { useMenu } from "@/stores/use-menu.js";
import axiosInstance from "@/lib/axios.js";
export default defineComponent ({
    setup() {
        useMenu().onSelectedKeys(["admin-users"]);

        const users = ref([]);
        const columns = [
            // {
            //     title: "#",
            //     key: "index",
            //     fixed: "left",
            // }, 
            {
                title: "Họ tên",
                dataIndex: "name",
                key: "name",
                width: 200,
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
                align: "center",
            },
            {
                title: "Thao tác",
                key: "action",
                fixed: "right",
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            }
        ]

        const getUsers = () => {
            axios
                .get('http://127.0.0.1:8000/api/users')
                .then(function (response) {
                    users.value = response.data.active_users;
                    console.log(users.value);
                })
                .catch(function (error) {
                    // xử trí khi bị lỗi
                    console.log(error);
                    message.error("Có lỗi xảy ra trong quá trình lấy danh sách người dùng");
                })
                .finally(function () {
                    // luôn luôn được thực thi
                });
            };
        
        getUsers();

        const viewDetail = (user) => {
            console.log("Chi tiết:", user);
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
                            })
                        message.success(`Đã kích hoạt ${user.name}!`);
                    }
                    getUsers();
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
                    message.success(`Bỏ cám thành công!`);
                    getUsers();
                },
                onCancel() {},
            });
        };

        return {
            users,
            columns,
            getUsers,
            viewDetail,
            showConfirm,
            showConfirmUnban,
        };
    },
});
</script>