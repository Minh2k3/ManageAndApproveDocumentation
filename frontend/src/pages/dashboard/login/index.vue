<template>
    <div class="login-container">
        <!-- Background particles -->
        <div class="particles">
            <div class="particle" v-for="n in 50" :key="n"></div>
        </div>

        <!-- Home button - Hidden -->
        <!-- <div class="home-button">
            <router-link to="/" class="text-decoration-none">
                <a-button type="primary" class="glass-button">
                    <HomeOutlined class="me-1" />
                    Trang chủ
                </a-button>
            </router-link>
        </div> -->

        <div class="main-content">
            <!-- Left side - Logo & Title (Hidden on mobile) -->
            <div class="left-side">
                <div class="logo-section fade-in-left">
                    <div class="logos-container">
                        <a-avatar type="button" @click="handleClickLogoTlu" shape="square" :size="60" class="logo-item">
                            <template #icon>
                                <img src="@/assets/images/logo_tlu.png" alt="logo_tlu">
                            </template>
                        </a-avatar>
                        <a-avatar type="button" @click="handleClickLogoDtn" shape="square" :size="60" class="logo-item">
                            <template #icon>
                                <img src="@/assets/images/logo_dtn.png" alt="logo_dtn">
                            </template>
                        </a-avatar>
                        <a-avatar type="button" @click="handleClickLogoHsv" shape="square" :size="55" class="logo-item">
                            <template #icon>
                                <img src="@/assets/images/logo_hsv.png" alt="logo_hsv">
                            </template>
                        </a-avatar>
                    </div>
                    
                    <div class="title-section">
                        <h2 class="university-name">TRƯỜNG ĐẠI HỌC THỦY LỢI</h2>
                        <h3 class="department-name">Đoàn Thanh niên - Hội Sinh viên</h3>
                    </div>
                </div>

                <div class="system-title fade-in-up">
                    <h1>
                        <span>
                            Hệ Thống Quản Lý và
                        </span>
                        <br>
                        <span>
                            Phê Duyệt Văn Bản Điện Tử
                        </span>
                    </h1>
                </div>
            </div>

            <!-- Right side - Login Form -->
            <div class="right-side">
                <div class="form-container fade-in-right">
                    <div class="form-header">
                        <h1 class="login-title">ĐĂNG NHẬP</h1>
                        <p class="login-subtitle">Nhập thông tin để truy cập hệ thống</p>
                    </div>

                    <!-- Outlook Login -->
                    <div class="outlook-login">
                        <button class="outlook-btn">
                            <i class="bi bi-envelope me-2"></i>
                            Đăng nhập với Outlook
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="divider">
                        <span>HOẶC ĐĂNG NHẬP VỚI</span>
                    </div>

                    <!-- Login Form -->
                    <form @submit.prevent="login" class="login-form">
                        <!-- Email -->
                        <div class="form-group">
                            <label>Email</label>
                            <a-input 
                                v-model:value="email" 
                                placeholder="2151062831@e.tlu.edu.vn" 
                                allow-clear
                                size="large"
                                class="form-input"
                                :class="{ 'input-danger': firstFieldError === 'email' }"
                            />
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <a-input-password 
                                v-model:value="password" 
                                placeholder="abcILoveYou123"
                                allow-clear 
                                size="large"
                                class="form-input"
                            />
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="form-options">
                            <a-checkbox v-model:checked="rememberMe">Ghi nhớ đăng nhập</a-checkbox>
                            <a href="#" class="forgot-link" @click="openModalForgotPassword">
                                Quên mật khẩu?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="button" 
                            class="login-btn"
                            :disabled="loading"
                            @click="login"
                        >
                            <span v-if="loading">
                                <i class="spinner-border spinner-border-sm me-2"></i>
                                Đang đăng nhập...
                            </span>
                            <span v-else>Đăng nhập</span>
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="register-link">
                        <span>Chưa có tài khoản?</span>
                        <router-link :to="{ name: 'register' }">
                            Đăng ký ngay
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay - Removed -->
        <!-- <div v-if="pageLoading" class="loading-overlay">
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Đang tải...</p>
            </div>
        </div> -->
    </div>

    <!-- Forgot Password Modal -->
    <a-modal
        :visible="modalForgotPassword"
        @cancel="modalForgotPassword = false"
        class="forgot-modal"
    >
        <template #title>
            <span class="modal-title">Quên mật khẩu</span>
        </template>
        <div class="modal-content">
            <p>Vui lòng điền email đã đăng ký để khôi phục mật khẩu.</p>
            <a-input
                v-model:value="email"
                placeholder="Nhập email của bạn"
                allow-clear
                size="large"
            />
        </div>
        <template #footer>
            <a-button @click="modalForgotPassword = false">Đóng</a-button>
            <a-button type="primary" @click="handleForgotPassword">
                Gửi yêu cầu
            </a-button>
        </template>
    </a-modal>
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
        const firstFieldError = ref("");

        const validateForm = ref(false);
        const loading = ref(false);
        const pageLoading = ref(false);

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
                const result = await authStore.login({ email: email.value, password: password.value, remember: rememberMe.value });
                if (result.status === 'error') {
                    message.error(result.message);
                }

                validateForm.value = false;

                const userResponse = await axiosInstance.get("api/user", {
                    withCredentials: true
                });
                message.success("Đăng nhập thành công!");
                console.log("Thông tin người dùng:", userResponse.data);
                const user = userResponse.data;
                console.log(user.id);
                router.push({ name: authStore.role });
            } catch (error) {
                console.error("Login error:", error);
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

        const modalForgotPassword = ref(false);

        const openModalForgotPassword = () => {
            modalForgotPassword.value = true;
        };

        const handleForgotPassword = async () => {
            if (!email.value) {
                message.error("Vui lòng nhập email của bạn.");
                return;
            }

            try {
                const response = await axiosInstance.post("/api/forgot-password", { email: email.value });
                message.success("Yêu cầu quên mật khẩu đã được gửi. Vui lòng kiểm tra email của bạn.");
                modalForgotPassword.value = false;
            } catch (error) {
                message.error("Đã xảy ra lỗi khi gửi yêu cầu quên mật khẩu.");
            }
        };

        const handleClickLogoTlu = () => {
            window.open("https://tlu.edu.vn", "_blank");
        };

        const handleClickLogoDtn = () => {
            window.open("https://www.facebook.com/doanthanhnienTLU", "_blank");
        };

        const handleClickLogoHsv = () => {
            window.open("https://www.facebook.com/HSVDHTL", "_blank");
        }

        return {
            email,
            password,
            rememberMe,
            firstFieldError,
            HomeOutlined,
            MailOutlined,
            loading,
            pageLoading,
            modalForgotPassword,
            openModalForgotPassword,
            handleForgotPassword,
            login,
            handleClickLogoTlu,
            handleClickLogoDtn,
            handleClickLogoHsv,
        };
    }
});
</script>

<style scoped>
/* Main Container */
.login-container {
    height: 100vh;
    background: linear-gradient(-45deg, #003a69 0%, #4392ba 28%, #28b6de 67%, #00eeff 100%);
    background-size: 400% 400%;
    animation: gradientShift 8s ease infinite;
    position: relative;
    overflow: hidden;
}

/* Background Animation */
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Particles */
.particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.particle:nth-child(odd) {
    animation-delay: -2s;
    animation-duration: 8s;
}

.particle:nth-child(even) {
    animation-delay: -4s;
    animation-duration: 10s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
}

/* Distribute particles randomly */
.particle:nth-child(1) { left: 10%; }
.particle:nth-child(2) { left: 20%; }
.particle:nth-child(3) { left: 30%; }
.particle:nth-child(4) { left: 40%; }
.particle:nth-child(5) { left: 50%; }
.particle:nth-child(6) { left: 60%; }
.particle:nth-child(7) { left: 70%; }
.particle:nth-child(8) { left: 80%; }
.particle:nth-child(9) { left: 90%; }
.particle:nth-child(10) { left: 15%; }

/* Home Button - Hidden */
.home-button {
    display: none;
}

/* Main Content */
.main-content {
    display: flex;
    height: 100vh;
    align-items: center;
    padding: 15px;
}

/* Left Side */
.left-side {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 40px;
    color: white;
}

.logo-section {
    text-align: center;
    margin-bottom: 60px;
}

.logos-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    margin-bottom: 30px;
}

.logo-item {
    background: transparent !important;
    border: none;
    transition: all 0.3s ease;
}

.logo-item:hover {
    transform: scale(1.1);
}

.logo-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.title-section {
    text-align: center;
}

.university-name {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.department-name {
    font-size: 1.2rem;
    font-weight: 500;
    opacity: 0.9;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.system-title h1 {
    font-size: 2.5rem;
    font-weight: bold;
    text-align: center;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    line-height: 1.2;
}

/* Right Side */
.right-side {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.form-container {
    background: rgba(248, 249, 250, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 30px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.form-header {
    text-align: center;
    margin-bottom: 25px;
}

.login-title {
    font-size: 1.8rem;
    font-weight: bold;
    color: #003a69;
    margin-bottom: 8px;
}

.login-subtitle {
    color: #6c757d;
    font-style: italic;
    font-size: 0.95rem;
}

/* Outlook Button */
.outlook-login {
    margin-bottom: 20px;
}

.outlook-btn {
    width: 100%;
    padding: 12px;
    background: #0066cc;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.outlook-btn:hover {
    background: #0052a3;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 102, 204, 0.4);
}

/* Divider */
.divider {
    display: flex;
    align-items: center;
    margin: 25px 0;
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #dee2e6;
    margin: 0 15px;
}

/* Form */
.login-form {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #003a69;
    font-weight: 500;
}

.form-input {
    border-radius: 8px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s ease !important;
}

.form-input:hover {
    border-color: #4392ba !important;
}

.form-input:focus,
.form-input.ant-input-focused {
    border-color: #003a69 !important;
    box-shadow: 0 0 0 2px rgba(0, 58, 105, 0.1) !important;
}

.input-danger {
    border-color: #dc3545 !important;
}

/* Form Options */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.forgot-link {
    color: #003a69;
    text-decoration: none;
    font-size: 0.9rem;
    font-style: italic;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #4392ba;
}

/* Login Button */
.login-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #003a69 0%, #4392ba 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.login-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 58, 105, 0.3);
}

.login-btn:active {
    transform: translateY(0);
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Register Link */
.register-link {
    text-align: center;
    color: #6c757d;
}

.register-link a {
    color: #003a69;
    text-decoration: none;
    font-style: italic;
    font-weight: 500;
    margin-left: 5px;
    transition: color 0.3s ease;
}

.register-link a:hover {
    color: #4392ba;
}

/* Animations */
.fade-in-left {
    animation: fadeInLeft 1s ease-out;
}

.fade-in-right {
    animation: slideInFromRight 1.2s ease-out;
}

.fade-in-up {
    animation: fadeInUp 1s ease-out 0.5s both;
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInFromRight {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading Overlay - Removed */
/* .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(-45deg, #003a69 0%, #4392ba 28%, #28b6de 67%, #00eeff 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    animation: fadeOut 0.5s ease-out 1s forwards;
}

@keyframes fadeOut {
    to {
        opacity: 0;
        visibility: hidden;
    }
}

.loading-spinner {
    text-align: center;
    color: white;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
} */

/* Modal Styles */
.modal-title {
    color: #003a69;
    font-size: 1.5rem;
    font-weight: bold;
}

.modal-content p {
    color: #6c757d;
    margin-bottom: 15px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .left-side {
        display: none;
    }
    
    .main-content {
        justify-content: center;
        padding: 20px 15px;
    }
    
    .right-side {
        flex: none;
        width: 100%;
        max-width: 400px;
        padding: 20px;
    }
    
    .form-container {
        padding: 30px 25px;
    }
    
    .login-title {
        font-size: 1.8rem;
    }
    
    .home-button {
        top: 15px;
        right: 15px;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 25px 20px;
        margin: 0 10px;
    }
    
    .login-title {
        font-size: 1.6rem;
    }
    
    .university-name {
        font-size: 1.4rem;
    }
    
    .system-title h1 {
        font-size: 2rem;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>