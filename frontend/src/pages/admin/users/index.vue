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
                    <template #bodyCell="{ column, index, record }">
                    <template v-if="column.key === 'index'">
                        <span>{{ index + 1 }}</span>
                    </template>

                    <template v-if="column.key === 'status'">
                        <span v-if="record.status_id == 1" class="text-primary"> {{ record.status }}</span>
                        <span v-if="record.status_id == 2" class="text-danger"> {{ record.status }}</span>
                    </template>

                    </template>
                </a-table>
            </div>
        </div>
    </a-card>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useMenu } from "@/stores/use-menu.js";
export default defineComponent ({
    setup() {
        useMenu().onSelectedKeys(["admin-users"]);

        const users = ref([]);
        const columns = [
            {
                title: "#",
                key: "index",
                fixed: "left",
            }, 
            {
                title: "Avatar",
                key: "avatar"
            },
            {
                title: "Tài khoản",
                dataIndex: "username",
                key: "username",
            },
            {
                title: "Họ tên",
                dataIndex: "name",
                key: "name",
            },
            {
                title: "Email",
                dataIndex: "email",
                key: "email",
            },
            {
                title: "Phòng ban",
                dataIndex: "department",
                key: "department",
                responsive: ["md"],
            },
            {
                title: "Trạng thái",
                dataIndex: "status",
                key: "status",
            },
            {
                title: "Thao tác",
                key: "action",
                fixed: "right",
            }
        ]

        const getUsers = () => {
            axios
                .get('http://127.0.0.1:8000/api/users')
                .then(function (response) {
                    users.value = response.data.data;
                })
                .catch(function (error) {
                    // xử trí khi bị lỗi
                    console.log(error);
                })
                .finally(function () {
                    // luôn luôn được thực thi
                });
            };
        
        getUsers();

        return {
            users,
            columns,
        };
    },
});
</script>