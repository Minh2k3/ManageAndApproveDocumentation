<template>
    <div 
        class="container-fluid vh-100 overflow-hidden backGradient">
        <div class="position-absolute top-0 end-0 mt-sm-5 me-sm-3">
            <router-link to="/" class="text-decoration-none">
                <a-button type="primary" class="d-flex align-items-center">
                    <HomeOutlined class="me-1" />
                    Trang chủ
                </a-button>
            </router-link>
        </div>
        <div class="row h-100 py-3">
            <!-- Cột trái -->
            <div class="d-sm-flex col-sm-5 d-none" :style="{      
                    // backgroundImage: `url(${bgImage})`,
                    // backgroundSize: 'contain',
                    // backgroundRepeat: 'no-repeat',
                    // backgroundPosition: 'top',
                    // height: '100vh',
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
                            <h1 class="fs-1" style="color: #005baa;">Hệ Thống Quản Lý và Phê Duyệt Văn Bản Điện Tử</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="col-12 col-sm-7 d-flex justify-content-center rounded-start-5 align-items-center"
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
                            <div class="col-sm-4">
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
                        

                            <div class="col-sm-4 mt-sm-0 mt-3">
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
                            <div class="col-sm-5">
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
                            <div class="col-sm-4">
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

                            <div class="col-sm-4 mt-sm-0 mt-3">
                                <div class="col-12 col-sm-12 text-start align-self-center">
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
                                    <button class="btn btn-primary rounded-3 py-2 my-button" 
                                        type="button" 
                                        style="background-color: #0d6efd;
                                            border-color: #0d6efd;
                                            color: #ffffff;"
                                        @click="register"
                                    >
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
import { useRoute, useRouter } from "vue-router";
import bgImage from '@/assets/images/NMT.jpg';
import { message } from 'ant-design-vue';
import axios from 'axios';

export default defineComponent({
    components: {
        MailOutlined,
        QuestionCircleOutlined,
        HomeOutlined
    },
    
    setup() {
        const router = useRouter();

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
                message.info("Vui lòng gửi email yêu cầu thêm phòng ban mới đến địa chỉ ThayDungDepTrai@gmail.com!");
            } else {
                console.log("Thông báo này đang trong thời gian cooldown");
            }
        };

        const getUsersRegister = () => {
            console.log("Fetching users register options...");
            axios
                .get("/api/register-options")
                .then((response) => {
                    console.log("Response:", response.data);
                    departments.value = response.data.departments;
                    roles.value = response.data.roles;
                    console.log(departments.value);
                    console.log(roles.value);
                })
                .catch((error) => {
                    console.error(error);
                });
        };

        // Gọi hàm lấy danh sách phòng ban và vai trò khi component được khởi tạo
        getUsersRegister();


        axios.defaults.withCredentials = true; // Đảm bảo rằng cookie được gửi cùng với yêu cầu
        axios.defaults.withXSRFToken = true; // Đảm bảo rằng CSRF token được gửi cùng với yêu cầu
        axios.defaults.baseURL = "http://127.0.0.1:8000"; // Đặt base URL cho axios
        axios.interceptors.request.use(config => {
            const token = document.head.querySelector('meta[name="csrf-token"]')?.content;
            if (token) {
                config.headers['X-CSRF-TOKEN'] = token;
            }
            return config;
        });

        const register = () => {

            console.log("Đăng ký với thông tin:", {
                name: name.value,
                email: email.value,
                password: password.value,
                password_confirmation: password_confirmation.value,
                department_id: selectedDepartment.value,
                role_id: selectedRole.value
            });

            // Kiểm tra xem người dùng đã đồng ý với điều khoản chưa
            if (!ok.value) {
                message.error("Vui lòng đồng ý với các điều khoản và chính sách của hệ thống!");
                return;
            }

            if (!name.value || !email.value || !password.value || !password_confirmation.value) {
                message.error("Vui lòng điền đầy đủ thông tin!");
                return;
            }
            if (password.value !== password_confirmation.value) {
                message.error("Mật khẩu không khớp!");
                return;
            }
            if (!selectedDepartment.value) {
                message.error("Vui lòng chọn phòng ban!");
                return;
            }
            if (!selectedRole.value) {
                message.error("Vui lòng chọn vai trò!");
                return;
            }

            axios.get("/sanctum/csrf-cookie").then(() => {
                // Gửi yêu cầu đăng ký đến API
                axios.post("/api/register", {
                    name: name.value,
                    email: email.value,
                    password: password.value,
                    password_confirmation: password_confirmation.value,
                    department_id: selectedDepartment.value,
                    role_id: selectedRole.value
                })
                .then(response => {
                    console.log("Đăng ký thành công:", response.data);
                    message.success("Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.");
                    // Chuyển hướng đến trang đăng nhập hoặc trang khác nếu cần
                    router.push({ name: "login" });
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        console.log("Lỗi validate:", error.response.data.errors);
                        alert("Đăng ký thất bại: " + JSON.stringify(error.response.data.errors));
                    } else {
                        console.error("Lỗi khác:", error);
                        message.error("Đăng ký thất bại: " + JSON.stringify(error.response.data.errors));
                    }
                });
            });
        };        

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
            register,
        }
    }
});
</script>

<style>
.ant-tooltip {
    max-width: 300px;
}

.backGradient {
    background: #003a69;
    background: linear-gradient(-45deg, rgba(0, 58, 105, 1) 0%, rgba(67, 146, 186, 1) 28%, rgba(40, 182, 222, 1) 67%, rgba(0, 238, 255, 1) 100%);
}

.my-button {
  cursor: pointer;
  transition: all 0.3s ease;
}

.my-button:hover {
  background-color: #59b54b; /* đậm hơn */
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.my-button:active {
  transform: scale(0.97);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}
</style>