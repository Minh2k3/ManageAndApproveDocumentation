<template>
    <div 
        class="container-fluid vh-100 overflow-hidden">
        <div class="position-absolute top-0 end-0 m-3">
            <router-link to="/" class="text-decoration-none">
                <a-button type="primary" class="d-flex align-items-center">
                    <HomeOutlined class="me-1" />
                    Trang chủ
                </a-button>
            </router-link>
        </div>
        <div class="row h-100">
            <!-- Cột trái -->
            <div class="d-sm-flex col-sm-6 d-none " :style="{      
                    backgroundImage: `url(${bgImage})`,
                    backgroundSize: 'contain',
                    backgroundRepeat: 'no-repeat',
                    backgroundPosition: 'top',
                    height: '100vh',
                }"
            >
                <div class="col-12">
                    <div class="row mt-3" >
                        <div class="col-12 justify-content-center d-flex mb-1 align-items-center">
                            <a-avatar shape="square" :size="50" style="background-color: transparent;">
                                <template #icon>
                                    <img src="@/assets/images/logo_tlu.png" alt="logo_tlu"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                </template>
                            </a-avatar>
                            <a-avatar shape="square" :size="50" style="background-color: transparent;" class="mx-3">
                                <template #icon>
                                    <img src="@/assets/images/logo_dtn.png" alt="logo_dtn"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                </template>
                            </a-avatar>
                            <a-avatar shape="square" :size="45" style="background-color: transparent;">
                                <template #icon>
                                    <img src="@/assets/images/logo_hsv.png" alt="logo_hsv"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                </template>
                            </a-avatar>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 justify-content-center">
                            <h3 class="text-center mb-0 p-1">TRƯỜNG ĐẠI HỌC THỦY LỢI</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 justify-content-center d-flex mb-3">
                            <h4 class="text-center col-12">Đoàn Thanh niên - Hội Sinh viên</h4>
                        </div>
                    </div>

                    <div class="row h-100 ">
                        <div class="d-flex align-items-center">
                            <h1 class="fs-1">Hệ Thống Quản Lý và Phê Duyệt Văn Bản Điện Tử</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="col-12 col-sm-6 d-flex justify-content-center rounded-start-5 align-items-center"
                style="background-color: #f8f9fa;">
                <div class="row w-100">
                    <div class="col-12">
                        <!-- Nội dung cột giữa -->
                        <div class="row">
                            <div class="justify-content-center">
                                <h1 class="text-center fw-bold mb-0">ĐĂNG KÝ</h1>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 justify-content-center">
                                <h4 class="text-center text-secondary fst-italic">Tạo tài khoản mới để sử dụng hệ thống
                                </h4>
                            </div>
                        </div>

                        <div class="row justify-content-center ">
                            <div class="col-sm-7">
                                <div class="col-12 col-sm-12 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1">*</span>
                                        <span class="fw-bold">Họ và tên</span>
                                    </label>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-12 col-sm-12 mt-1">
                                    <a-input v-model:value="name" placeholder="Trần Tuấn Minh" allow-clear :class="{
                                        'input-danger': firstFieldError === 'name'
                                    }" />

                                    <div class="w-100"></div>
                                    <!-- 
                                <small 
                                    v-if="errors.status_id && firstFieldError === 'status_id'" 
                                    class="text-danger">
                                        {{ errors.status_id[0] }}
                                </small> -->
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-sm-7">
                                <div class="col-12 col-sm-12 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1">*</span>
                                        <span class="fw-bold">Email</span>
                                    </label>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-12 col-sm-12 mt-1">
                                    <a-input v-model:value="email" placeholder="2151062831@e.tlu.edu.vn" allow-clear
                                        :class="{
                                            'input-danger': firstFieldError === 'email'
                                        }" />

                                    <div class="w-100"></div>
                                    <!-- 
                                <small 
                                    v-if="errors.status_id && firstFieldError === 'status_id'" 
                                    class="text-danger">
                                        {{ errors.status_id[0] }}
                                </small> -->
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-sm-4">
                                <div class="col-12 col-sm-12 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1">*</span>
                                        <span class="fw-bold">Phòng/Khoa/Đơn vị</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-12 mt-1">
                                    <div class="d-flex align-items-center">
                                        <a-select 
                                        v-model:value="selectedDepartment"
                                        show-search 
                                        placeholder="Phòng/Khoa/Đơn vị" 
                                        style="width: 100%"
                                        :options="departments" 
                                        :filter-option="filterOption" 
                                        allow-clear
                                        :list-height="96"
                                        ></a-select>
                                        
                                        <a-tooltip placement="right" title="Đơn vị của bạn không có trong danh sách? Gửi mail cho admin bằng tài khoản outlock của trường">
                                            <QuestionCircleOutlined 
                                                class="ms-2 text-primary"
                                                style="font-size: 18px; cursor: pointer;"
                                                @click="showRequestNewDepartmentModal"
                                            />
                                        </a-tooltip>
                                    </div>

                                    <div class="w-100"></div>
                                </div>
                            </div>

                            <div class="col-sm-3 mt-sm-0 mt-3">
                                <div class="col-12 col-sm-12 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1">*</span>
                                        <span class="fw-bold">Vai trò</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-12 mt-1">
                                    <div class="d-flex">
                                        <a-select 
                                        v-model:value="selectedRole"
                                        show-search 
                                        placeholder="Vai trò" 
                                        style="width: 100%"
                                        :options="roles" 
                                        :filter-option="filterOption" 
                                        allow-clear
                                        :list-height="96"
                                        ></a-select>
                                    </div>

                                    <div class="w-100"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="row justify-content-center mt-3">
                            <div class="col-sm-7">
                                <div class="col-12 col-sm-7 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1">*</span>
                                        <span class="fw-bold">Mật khẩu</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-12 mt-1">
                                    <a-input-password v-model:value="password" placeholder="abcILoveYou123"
                                        allow-clear />

                                    <div class="w-100"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu -->
                        <div class="row justify-content-center mt-3">
                            <div class="col-sm-7">
                                <div class="col-12 col-sm-7 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1">*</span>
                                        <span class="fw-bold">Xác nhận mật khẩu</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-12 mt-1">
                                    <a-input-password v-model:value="password_confirmation" placeholder="abcILoveYou123"
                                        allow-clear />

                                    <div class="w-100"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-sm-6 col-12 border border-secondary row py-2">
                                    <div class="col-sm-1 col-1">
                                        <div class="d-flex justify-content-center">
                                            <a-checkbox v-model:checked="ok" class="">
                                            </a-checkbox>
                                        </div>
                                        <div class="w-100"></div>
                                        
                                    </div>
                                    
                                    <div class="col-sm-11 col-11 px-0 ps-2">
                                        <div class="d-flex">
                                            <span class="fw-bold fst-italic">Tôi đồng ý với các điều khoản và chính sách của hệ thống</span>
                                        </div>
                                        <div class="w-100"></div>
                                        <div class="d-flex pt-2">
                                            <span class="text-secondary">Chi tiết điều khoản xem tại</span>
                                            &nbsp;
                                            <a href="#" class="text-decoration-none text-primary small fst-italic">đây</a>
                                        </div>
                                    </div>
                                
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-12 col-sm-6 justify-content-center">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary rounded-3 p-2" type="button" style="background-color: #0d6efd;
                                    border-color: #0d6efd;
                                    color: #ffffff;">
                                        Đăng ký
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 justify-content-center d-flex">
                                <span class="text-center text-secondary mx-2">Đã có tài khoản?</span>
                                <router-link :to="{ name: 'login' }">
                                    <span class="text-center text-secondary fst-italic">Đăng nhập</span>
                                </router-link>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
import { MailOutlined, QuestionCircleOutlined, HomeOutlined } from "@ant-design/icons-vue";
import { defineComponent, ref, reactive, toRefs } from "vue";
import axios from "axios";
import bgImage from '@/assets/images/NMT.jpg';
import { message } from 'ant-design-vue';

export default defineComponent({
    components: {
        MailOutlined,
        QuestionCircleOutlined,
        HomeOutlined
    },
    
    setup() {
        // Thông tin form đăng ký
        const name = ref('');
        const email = ref('');
        const password = ref('');
        const password_confirmation = ref('');
        const ok = ref(false);
        const selectedDepartment = ref(null);
        const selectedRole = ref(null);
        
        // Danh sách phòng ban và vai trò
        const departments = ref([]);
        const roles = ref([]);

        // Hàm lọc cho select box
        const filterOption = (input, option) => {
            return option.label.toLowerCase().includes(input.toLowerCase());
        };

        // Hàm hiển thị modal yêu cầu thêm phòng ban mới
        let lastRequestTime = 0;

        const showRequestNewDepartmentModal = () => {
            const now = Date.now();

            if (now - lastRequestTime >= 6666) {
                lastRequestTime = now;
                message.info("Vui lòng gửi email yêu cầu thêm phòng ban mới đến admin qua tài khoản Outlook của trường.");
            } else {
                console.log("Thông báo này đang trong thời gian cooldown");
            }
        };

        const getUsersRegister = () => {
            axios.get("http://127.0.0.1:8000/api/users/create")
                .then((response) => {
                    departments.value = response.data.users_department;
                    roles.value = response.data.users_role;
                    console.log(departments.value);
                    console.log(roles.value);
                })
                .catch((error) => {
                    console.error(error);
                });
        };

        // Gọi hàm lấy danh sách phòng ban và vai trò khi component được khởi tạo
        getUsersRegister();

        return {
            // Form đăng ký
            name,
            email,
            password,
            password_confirmation,
            departments,
            roles,
            ok,
            bgImage,
            selectedDepartment,
            selectedRole,
            filterOption,
            showRequestNewDepartmentModal,
        }
    }
});
</script>

<style>
.ant-tooltip {
    max-width: 300px;
}
</style>