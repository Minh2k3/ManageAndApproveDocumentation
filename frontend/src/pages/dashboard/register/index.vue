<template>
    <div class="register-container">
        <!-- Background particles -->
        <div class="particles">
            <div class="particle" v-for="n in 50" :key="n"></div>
        </div>

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

            <!-- Right side - Register Form -->
            <div class="right-side">
                <div class="form-container fade-in-right">
                    <div class="form-header">
                        <h1 class="register-title">ĐĂNG KÝ</h1>
                        <p class="register-subtitle">Tạo tài khoản mới để sử dụng hệ thống</p>
                    </div>

                    <!-- Register Form -->
                    <form @submit.prevent="register" class="register-form">
                        <!-- Name & Email Row -->
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label>
                                    <span class="required">*</span>
                                    Họ và tên
                                </label>
                                <a-input 
                                    v-model:value="name" 
                                    placeholder="Trần Tuấn Minh" 
                                    allow-clear
                                    size="large"
                                    class="form-input"
                                />
                            </div>
                            <div class="form-group half-width">
                                <label>
                                    <span class="required">*</span>
                                    Email
                                </label>
                                <a-input 
                                    v-model:value="email" 
                                    placeholder="2151062831@e.tlu.edu.vn" 
                                    allow-clear
                                    size="large"
                                    class="form-input"
                                />
                            </div>
                        </div>

                        <!-- Department & Role Row -->
                        <div class="form-row">
                            <div class="form-group department-width">
                                <label>
                                    <span class="required">*</span>
                                    Phòng/Khoa/Đơn vị
                                </label>
                                <div class="department-input">
                                    <a-select 
                                        v-model:value="selectedDepartment"
                                        show-search 
                                        placeholder="Chọn phòng/khoa/đơn vị" 
                                        style="width: 100%"
                                        :options="departments" 
                                        :filter-option="filterOption" 
                                        allow-clear
                                        size="large"
                                        :dropdown-match-select-width="false"
                                        :list-height="200"
                                    />
                                    <a-tooltip placement="right" title="Đơn vị của bạn không có trong danh sách? Gửi mail cho admin bằng tài khoản outlook của trường">
                                        <QuestionCircleOutlined 
                                            class="question-icon"
                                            @click="showRequestNewDepartmentModal"
                                        />
                                    </a-tooltip>
                                </div>
                            </div>
                            <div class="form-group role-width">
                                <label>
                                    <span class="required">*</span>
                                    Vai trò
                                </label>
                                <a-select 
                                    v-model:value="selectedRoll"
                                    show-search 
                                    placeholder="Chọn vai trò" 
                                    style="width: 100%"
                                    :options="rolls" 
                                    :filter-option="filterOption" 
                                    allow-clear
                                    size="large"
                                    :dropdown-match-select-width="false"
                                    :list-height="200"
                                />
                            </div>
                        </div>

                        <!-- Password Row -->
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label>
                                    <span class="required">*</span>
                                    Mật khẩu
                                </label>
                                <a-input-password 
                                    v-model:value="password" 
                                    placeholder="abcILoveYou123"
                                    allow-clear 
                                    size="large"
                                    class="form-input"
                                />
                            </div>
                            <div class="form-group half-width">
                                <label>
                                    <span class="required">*</span>
                                    Xác nhận mật khẩu
                                </label>
                                <a-input-password 
                                    v-model:value="password_confirmation" 
                                    placeholder="abcILoveYou123"
                                    allow-clear 
                                    size="large"
                                    class="form-input"
                                />
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="terms-section">
                            <div class="terms-checkbox">
                                <a-checkbox v-model:checked="ok" class="custom-checkbox">
                                    <span class="terms-text">
                                        Tôi đồng ý với các điều khoản và chính sách của hệ thống
                                    </span>
                                </a-checkbox>
                            </div>
                            <div class="terms-link">
                                <span class="text-secondary">Chi tiết điều khoản xem </span>
                                <a href="#" class="link-primary">tại đây</a>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <button 
                            type="button" 
                            class="register-btn"
                            :disabled="loading"
                            @click="register"
                        >
                            <span v-if="loading">
                                <i class="spinner-border spinner-border-sm me-2"></i>
                                Đang đăng ký...
                            </span>
                            <span v-else>Đăng ký</span>
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="login-link">
                        <span>Đã có tài khoản?</span>
                        <router-link :to="{ name: 'login' }">
                            Đăng nhập ngay
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { 
    MailOutlined, 
    QuestionCircleOutlined, 
    HomeOutlined
} from "@ant-design/icons-vue";

import { 
    defineComponent, 
    ref, 
    onMounted,
    watch,
    onUnmounted
} from "vue";
import { useRouter } from "vue-router";
import { message } from 'ant-design-vue';
import axiosInstance from '@/lib/axios.js';
import { useRegisterStore } from "@/stores/use-register";

export default defineComponent({
    components: {
        MailOutlined,
        QuestionCircleOutlined,
        HomeOutlined
    },
    
    setup() {
        const router = useRouter();
        const useRegister = useRegisterStore();

        // Thông tin form đăng ký
        const name = ref('');
        const email = ref('');
        const password = ref('');
        const password_confirmation = ref('');
        const ok = ref(false);
        const selectedDepartment = ref(null);
        const selectedRoll = ref(null);
        
        // Danh sách phòng ban và vai trò
        const departments = ref([]);
        const rolls = ref([]);

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

        // Gọi hàm lấy danh sách phòng ban và vai trò khi component được khởi tạo
        onMounted(async () => {
            await useRegister.fetchRegisterForm();
            departments.value = useRegister.departments;
            rolls.value = useRegister.rolls;
            console.log("Đã gọi hàm getUsersRegister()");
        });

        const validateFrontend = ref(false);
        const loading = ref(false);

        const register = () => {
            console.log("Đăng ký với thông tin:", {
                name: name.value,
                email: email.value,
                password: password.value,
                password_confirmation: password_confirmation.value,
                department_id: selectedDepartment.value,
                roll_at_department_id: selectedRoll.value
            });

            if (!name.value.trim()) {
                message.error("Vui lòng nhập họ và tên!");
                validateFrontend.value = false;
                return;
            }

            if (!email.value.trim()) {
                message.error("Vui lòng nhập email!");
                validateFrontend.value = false;
                return;
            }

            if (selectedDepartment.value === null) {
                message.error("Phòng ban không được để trống.");
                validateFrontend.value = false;
                return;
            }

            if (selectedRoll.value === null) {
                message.error("Vai trò không được để trống.");
                validateFrontend.value = false;
                return;
            }

            if (!password.value.trim()) {
                message.error("Mật khẩu không được để trống.");
                validateFrontend.value = false;
                return;
            }

            if (password.value !== password_confirmation.value) {
                message.error("Mật khẩu không khớp!");
                validateFrontend.value = false;
                return;
            }
            
            if (!ok.value) {
                message.error("Vui lòng đồng ý với các điều khoản và chính sách của hệ thống!");
                validateFrontend.value = false;
                return;
            }

            validateFrontend.value = true;
            loading.value = true;
        };       

        watch(validateFrontend, async (newValue) => {
            if (newValue) {
                console.log("Đăng ký người dùng mới");
                await registerUser();
                validateFrontend.value = false;
            }
        });
        
        const registerUser = async () => {
            console.log("Đăng ký người dùng mới");
            try {
                const response = await axiosInstance.post("api/register", {
                    name: name.value,
                    email: email.value,
                    password: password.value,
                    password_confirmation: password_confirmation.value,
                    department_id: selectedDepartment.value,
                    roll_at_department_id: selectedRoll.value
                });
                console.log("Đăng ký thành công:", response.data);
                validateFrontend.value = false;
                message.success("Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.");
                router.push({ name: "login" });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    console.log("Lỗi validate:", error.response.data.errors);
                    alert("Đăng ký thất bại: " + JSON.stringify(error.response.data.errors));
                } else {
                    console.error("Lỗi khác:", error);
                    message.error("Đăng ký thất bại: " + JSON.stringify(error.response.data.message));
                }
            } finally {
                loading.value = false;
            }
        }
        
        const handleGlobalKeydown = (event) => {
            if (event.key === 'Enter') {
                register();
            }
        };

        onMounted(() => {
            document.addEventListener('keydown', handleGlobalKeydown);
        });

        onUnmounted(() => {
            document.removeEventListener('keydown', handleGlobalKeydown);
        });

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
            // Form đăng ký
            name,
            email,
            password,
            password_confirmation,
            departments,
            rolls,
            ok,
            selectedDepartment,
            selectedRoll,
            loading,

            filterOption,
            showRequestNewDepartmentModal,
            register,
            handleClickLogoTlu,
            handleClickLogoDtn,
            handleClickLogoHsv,
        }
    }
});
</script>

<style scoped>
/* Main Container */
.register-container {
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
    padding: 20px;
    color: white;
}

.logo-section {
    text-align: center;
    margin-bottom: 40px;
}

.logos-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 25px;
    margin-bottom: 20px;
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
    font-size: 1.6rem;
    font-weight: bold;
    margin-bottom: 8px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.department-name {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.9;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.system-title h1 {
    font-size: 2.2rem;
    font-weight: bold;
    text-align: center;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    line-height: 1.2;
}

/* Right Side */
.right-side {
    flex: 1.2;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.form-container {
    background: rgba(248, 249, 250, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 25px;
    width: 100%;
    max-width: 550px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.form-header {
    text-align: center;
    margin-bottom: 20px;
}

.register-title {
    font-size: 1.8rem;
    font-weight: bold;
    color: #003a69;
    margin-bottom: 8px;
}

.register-subtitle {
    color: #6c757d;
    font-style: italic;
    font-size: 0.95rem;
}

/* Form Styles */
.register-form {
    margin-bottom: 15px;
}

.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.half-width {
    flex: 1;
}

.department-width {
    flex: 2;
}

.role-width {
    flex: 1;
}

.form-group label {
    margin-bottom: 6px;
    color: #003a69;
    font-weight: 500;
    font-size: 0.9rem;
}

.required {
    color: #dc3545;
    margin-right: 4px;
}

.form-input,
.form-select {
    border-radius: 8px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s ease !important;
}

.form-input:hover,
.form-select:hover {
    border-color: #4392ba !important;
}

.form-input:focus,
.form-input.ant-input-focused,
.form-select:focus,
.form-select.ant-select-focused {
    border-color: #003a69 !important;
    box-shadow: 0 0 0 2px rgba(0, 58, 105, 0.1) !important;
}

/* Department Input with Question Icon */
.department-input {
    display: flex;
    align-items: center;
    gap: 8px;
}

.question-icon {
    color: #003a69;
    font-size: 18px;
    cursor: pointer;
    transition: color 0.3s ease;
    flex-shrink: 0;
}

.question-icon:hover {
    color: #4392ba;
}

/* Terms Section */
.terms-section {
    background: rgba(255, 255, 255, 0.3);
    border: 1px solid rgba(0, 58, 105, 0.2);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}

.terms-checkbox {
    margin-bottom: 8px;
}

.custom-checkbox {
    font-weight: 500;
    font-style: italic;
}

.terms-text {
    color: #003a69;
}

.terms-link {
    font-size: 0.9rem;
}

.link-primary {
    color: #003a69;
    text-decoration: none;
    font-style: italic;
    transition: color 0.3s ease;
}

.link-primary:hover {
    color: #4392ba;
}

/* Register Button */
.register-btn {
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
    margin-bottom: 15px;
}

.register-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 58, 105, 0.3);
}

.register-btn:active {
    transform: translateY(0);
}

.register-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Login Link */
.login-link {
    text-align: center;
    color: #6c757d;
    font-size: 0.9rem;
}

.login-link a {
    color: #003a69;
    text-decoration: none;
    font-style: italic;
    font-weight: 500;
    margin-left: 5px;
    transition: color 0.3s ease;
}

.login-link a:hover {
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

/* Responsive Design */
@media (max-width: 768px) {
    .left-side {
        display: none;
    }
    
    .main-content {
        justify-content: center;
    }
    
    .right-side {
        flex: none;
        width: 100%;
        max-width: 500px;
        padding: 15px;
    }
    
    .form-container {
        padding: 20px 15px;
        max-width: 100%;
    }
    
    .form-row {
        flex-direction: column;
        gap: 12px;
    }
    
    .register-title {
        font-size: 1.6rem;
    }
    
    .department-input {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    
    .question-icon {
        align-self: center;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 18px 12px;
        margin: 0 8px;
    }
    
    .register-title {
        font-size: 1.5rem;
    }
    
    .form-row {
        gap: 10px;
    }
    
    .terms-section {
        padding: 12px;
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

/* Ant Design Select Customization */
:deep(.ant-select-selector) {
    border-radius: 8px !important;
    border: 2px solid #e9ecef !important;
    height: 40px !important;
    align-items: center !important;
}

:deep(.ant-select-selection-placeholder) {
    color: #6c757d !important;
    font-size: 14px !important;
}

:deep(.ant-select:hover .ant-select-selector) {
    border-color: #4392ba !important;
}

:deep(.ant-select-focused .ant-select-selector) {
    border-color: #003a69 !important;
    box-shadow: 0 0 0 2px rgba(0, 58, 105, 0.1) !important;
}

:deep(.ant-select-arrow) {
    color: #6c757d !important;
}

:deep(.ant-select-clear) {
    background: rgba(0, 0, 0, 0.06) !important;
}
</style>