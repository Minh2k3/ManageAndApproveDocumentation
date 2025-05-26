<template>
    <div class="container-fluid vh-100 overflow-hidden backGradient" style="background-color: aquamarine;">
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
            <div class="d-sm-flex col-sm-6 d-none">
                <div class="col-12">
                    <div class="row mt-3">
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
                        <div class="d-flex align-items-center ">
                            <h1 class="fs-1 ">Hệ Thống Quản Lý và Phê Duyệt Văn Bản Điện Tử</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="col-12 col-sm-6 d-flex rounded-start-5 " style="background-color: #f8f9fa;">
                <div class="row w-100 h-100 justify-content-center align-items-center">
                    <div class="col-12">
                        <!-- Nội dung cột giữa -->
                        <div class="row">
                            <div class="justify-content-center">
                                <h1 class="text-center fw-bold mb-0">ĐĂNG NHẬP</h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 justify-content-center">
                                <h4 class="text-center text-secondary fst-italic">Nhập thông tin để truy cập hệ thống
                                </h4>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-8 col-sm-6 justify-content-center">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="button">
                                        <span class="text-center"><i class="bi bi-envelope me-2"></i>Đăng nhập với Outlock</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-2">
                            <div class="col-12 col-sm-6 d-flex align-items-center my-3">
                                <div class="flex-grow-1 border-bottom"></div>
                                <div class="px-2 text-muted fw-semibold small">HOẶC ĐĂNG NHẬP VỚI</div>
                                <div class="flex-grow-1 border-bottom"></div>
                            </div>
                        </div>

                        <form @submit.prevent="login">
                            <!-- Email -->
                            <div class="row justify-content-center ">
                                <div class="col-sm-6">
                                    <div class="text-start align-self-center">
                                        <label>
                                            <span>Email</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="mt-1">
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

                            <!-- Mật khẩu -->
                            <div class="row justify-content-center mt-3">
                                <div class="col-sm-6">
                                    <div class="text-start align-self-center">
                                        <label>
                                            <span>Mật khẩu</span>
                                        </label>
                                    </div>
                                    <div class="mt-1">
                                        <a-input-password v-model:value="password" placeholder="abcILoveYou123"
                                            allow-clear />

                                        <div class="w-100"></div>

                                        <!-- <small
                                        v-if="errors.password && firstFieldError === 'password' && firstError !== 'Mật khẩu không khớp.'"
                                        class="text-danger">
                                        {{ errors.password[0] }}
                                    </small> -->
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center mt-2">
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <!-- Checkbox ghi nhớ -->
                                        <div>
                                            <a-checkbox v-model:checked="rememberMe">Ghi nhớ đăng nhập</a-checkbox>
                                        </div>
                                        <!-- Quên mật khẩu -->
                                        <div>
                                            <a href="#" class="text-decoration-none text-primary small fst-italic">Quên mật
                                                khẩu?</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center mt-3">
                                <div class="col-12 col-sm-6 justify-content-center">
                                    <div class="d-grid gap-2">
                                        <button 
                                            class="btn btn-primary p-2 my-button" 
                                            type="button" 
                                            style="
                                                background-color: #0d6efd;
                                                border-color: #0d6efd;
                                                color: #ffffff;
                                            "
                                            :disabled="loading"
                                            @click="login"
                                        >
                                            <span v-if="loading">
                                                <i class="spinner-border spinner-border-sm me-2"></i>
                                                Đang đăng nhập...
                                            </span>
                                            <span v-else>Đăng nhập</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row mt-4">
                            <div class="col-12 justify-content-center d-flex">
                                <span class="text-center text-secondary mx-2">Chưa có tài khoản?</span>
                                <router-link :to="{ name: 'register' }" class="text-decoration-none">
                                    <span class="text-center text-primary fst-italic">Đăng ký</span>
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
import { HomeOutlined, MailOutlined } from "@ant-design/icons-vue";
import { defineComponent, ref, watch, onMounted, onUnmounted } from "vue";
import { message } from 'ant-design-vue';
import { useRouter } from "vue-router";
import axiosInstance from "@/lib/axios.js";
import { useAuth } from "@/stores/use-auth.js";

export default defineComponent({
    components: {
        MailOutlined,
        HomeOutlined,
    },

    setup() {
        const router = useRouter();
        const authStore = useAuth();

        const email = ref("");
        const password = ref("");
        const rememberMe = ref(false);

        const validateForm = ref(false);
        const loading = ref(false);

        console.log("user", email.value);

        const login = () => {
            if (email.value === "" || password.value === "") {
                message.error("Vui lòng nhập đầy đủ thông tin!");
                validateForm.value = false;
                return;
            }

            validateForm.value = true;
            loading.value = true;
        }


        watch(validateForm, async (newValue) => {
            if (newValue) {
                console.log("Form đã xác thực ở phía frontend.");
                await loginUser();
                validateForm.value = false;
            }
        });

        const loginUser = async () => {
            await axiosInstance.get("/sanctum/csrf-cookie");

            try {
                // Gửi thông tin đăng nhập
                const result = await authStore.login({ email: email.value, password: password.value });
                if (result.status === 'error') {
                    message.error(result.message);
                }

                validateForm.value = false;

                // Chắc chắn đăng nhập thành công thì mới gọi API /user
                const userResponse = await axiosInstance.get("api/user", {
                    withCredentials: true
                });
                message.success("Đăng nhập thành công!");
                console.log("Thông tin người dùng:", userResponse.data);
                const user = userResponse.data;
                console.log(user.id);
                router.push({ name: authStore.role });
            } catch (error) {

            } finally {
                loading.value = false;
            }
        };

        const handleGlobalKeydown = (event) => {
            if (event.key === 'Enter') {
                login();
            }
        };

        onMounted(() => {
            document.addEventListener('keydown', handleGlobalKeydown);
        });

        onUnmounted(() => {
            document.removeEventListener('keydown', handleGlobalKeydown);
        });

        return {
            email,
            password,
            rememberMe,
            HomeOutlined,
            MailOutlined,
            loading,

            login,
        };
    }
});
</script>

<style>
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