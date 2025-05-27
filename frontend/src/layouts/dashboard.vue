<template>
    <div class="min-h-screen d-flex flex-column">
        <header class="bg-header text-white py-4">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="../assets/images/logo_tlu.png" alt="Logo ĐH Thủy Lợi" class="me-2"
                        style="height: 40px; width: 40px;" />
                    <span class="fs-4 mb-0 fw-bold">ĐH THỦY LỢI</span>
                </div>
                <nav class="d-none d-md-flex">
                    <router-link to="#" class="text-white mx-3 text-decoration-none fw-medium">Trang chủ</router-link>
                    <router-link to="#" class="text-white mx-3 text-decoration-none">Giới thiệu</router-link>
                    <a href="#features" @click.prevent="scrollToFeatures" class="text-white mx-3
                        text-decoration-none">Tính năng</a>
                    <a href="#process-flow" @click.prevent="scrollToProcessFlow" class="text-white mx-3
                        text-decoration-none">Quy trình hoạt động</a>
                    <a href="#footer" @click.prevent="scrollToFooter" class="text-white mx-3
                        text-decoration-none">Liên hệ</a>
                </nav>
                <div>
                    <router-link to="/login">
                        <a-button type="primary" ghost class="bg-white text-primary">Đăng nhập</a-button>
                    </router-link>
                </div>
            </div>
        </header>

        <main class="flex-grow-1">
            <section class="bg-primary text-white py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h2 class="display-5 fw-bold mb-4">Hệ Thống Quản Lý và Phê Duyệt Văn Bản Điện Tử</h2>
                            <p class="lead mb-4">
                                Giải pháp số hóa quy trình quản lý và phê duyệt văn bản cho các tổ chức sinh viên tại
                                trường Đại học
                                Thủy lợi.
                            </p>
                            <div class="d-flex gap-3">
                                <a-button type="primary" class="bg-white text-primary">Tìm hiểu thêm</a-button>
                                <router-link to="/register">
                                    <a-button ghost>Đăng ký</a-button>
                                </router-link>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <a-carousel autoplay>
                                <div><h3>
                                    <img src="@/assets/images/Cosette_square.jpg" alt="Em Cosette"
                                        class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                </h3></div>
                                <div><h3>
                                    <img src="@/assets/images/Retro_Punk_square.jpg" alt="Em gái Anime nằm trên giường"
                                        class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                </h3></div>
                                <div><h3>
                                    <img src="@/assets/images/YourLieInApril.jpg" alt="Minh họa hệ thống"
                                        class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                </h3></div>
                                <div><h3>
                                    <img src="@/assets/images/ZeroTwo.png" alt="Minh họa hệ thống"
                                        class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                </h3></div>
                                <div><h3>
                                    <img src="@/assets/images/Ai_Hoshino.png" alt="Minh họa hệ thống"
                                        class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                </h3></div>
                            </a-carousel>                            
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="py-5 bg-light">
                <div class="container">
                    <h2 class="text-center mb-5 fw-bold">Tính Năng Nổi Bật</h2>
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-3 py-3 border border-1 rounded-3 mx-3" v-for="feature in features"
                            :key="feature.title" style="background-color: #fff;">
                            <feature-card :title="feature.title" :description="feature.description"
                                :icon="feature.icon" />
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-5 bg-white" id="process-flow">
                <div class="container">
                    <h2 class="text-center mb-5 fw-bold">Quy Trình Hoạt Động</h2>
                    <div class="mx-auto" style="max-width: 800px;">
                        <process-flow />
                    </div>
                </div>
            </section>

            <section class="py-5 bg-light">
                <div class="container text-center">
                    <h2 class="fw-bold mb-4">Bắt Đầu Sử Dụng Ngay Hôm Nay</h2>
                    <p class="lead mb-5 mx-auto" style="max-width: 800px;">
                        Hệ thống quản lý và phê duyệt văn bản điện tử giúp tối ưu hóa quy trình, tiết kiệm thời gian và
                        nâng cao
                        hiệu quả làm việc.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <router-link to="/register">
                            <a-button type="primary" size="large">Đăng Ký Tài Khoản</a-button>
                        </router-link>
                        <a href="https://www.youtube.com/@Himakevolution" target="_blank" rel="noopener noreferrer"
                            class="border border-1 rounded-3">
                            <a-button ghost size="large" class="text-primary">Xem Demo</a-button>
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-dark text-white py-5" id="footer">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-md-3">
                        <h3 class="h5 fw-bold mb-3">ĐH Thủy Lợi</h3>
                        <p class="mb-3">Hệ thống quản lý và phê duyệt văn bản điện tử tích hợp chữ ký số</p>
                        <div class="d-flex gap-3">
                            <a href="https://www.facebook.com/TranMinhTM7" class="text-white text-decoration-none" target="_blank" rel="noopener noreferrer">Facebook</a>
                            <a href="https://www.youtube.com/@Himakevolution" class="text-white text-decoration-none" target="_blank" rel="noopener noreferrer">Youtube</a>
                            <a href="https://www.instagram.com/himakevolution/" class="text-white text-decoration-none" target="_blank" rel="noopener noreferrer">Instagram</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h3 class="h5 fw-bold mb-3">Liên Kết</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Trang chủ
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Giới thiệu
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Tính năng
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Liên hệ
                                </router-link>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h3 class="h5 fw-bold mb-3">Hỗ Trợ</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><router-link to="#"
                                    class="text-white text-decoration-none">FAQ</router-link></li>
                            <li class="mb-2"><router-link to="#" class="text-white text-decoration-none">Trung tâm hỗ
                                    trợ</router-link></li>
                            <li class="mb-2"><router-link to="#" class="text-white text-decoration-none">Hướng dẫn sử
                                    dụng</router-link></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h3 class="h5 fw-bold mb-3">Liên Hệ</h3>
                        <address>
                            <p>175 Tây Sơn, Đống Đa, Hà Nội</p>
                            <p>Email: info@tlu.edu.vn</p>
                            <p>Điện thoại: (024) 3852 2201</p>
                        </address>
                    </div>
                </div>
                <div class="border-top border-secondary mt-4 pt-4 text-center">
                    <p>&copy; {{ new Date().getFullYear() }} Đại học Thủy Lợi. Tất cả các quyền được bảo lưu.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import FeatureCard from '@/components/FeatureCard.vue';
import ProcessFlow from '@/components/ProcessFlow.vue';

export default defineComponent({
    name: 'App',
    components: {
        FeatureCard,
        ProcessFlow
    },
    methods: {
        scrollToFeatures() {
            const element = document.getElementById("features");
            if (element) {
                element.scrollIntoView({ behavior: "smooth" });
            }
        },
        scrollToFooter() {
            const element = document.getElementById("footer");
            if (element) {
                element.scrollIntoView({ behavior: "smooth" });
            }
        },
        scrollToProcessFlow() {
            const element = document.getElementById("process-flow");
            if (element) {
                element.scrollIntoView({ behavior: "smooth" });
            }
        },
    },
    data() {
        return {
            features: [
                {
                    title: "Quản Lý Văn Bản",
                    description: "Tạo, lưu trữ và quản lý văn bản theo yêu cầu với hệ thống phiên bản khi có sự chỉnh sửa.",
                    icon: "file-text"
                },
                {
                    title: "Luồng Phê Duyệt",
                    description: "Thiết lập và quản lý quy trình phê duyệt linh hoạt theo nhu cầu của từng loại văn bản.",
                    icon: "apartment"
                },
                {
                    title: "Chữ Ký Số",
                    description: "Tích hợp chữ ký số đảm bảo tính pháp lý và an toàn cho các văn bản được phê duyệt.",
                    icon: "edit"
                },
                {
                    title: "Thông Báo Tự Động",
                    description: "Hệ thống gửi thông báo cho các bên liên quan khi có thay đổi trạng thái văn bản.",
                    icon: "bell"
                },
                {
                    title: "Tìm Kiếm & Lọc",
                    description: "Tìm kiếm và lọc văn bản nhanh chóng theo nhiều tiêu chí khác nhau.",
                    icon: "search"
                },
                {
                    title: "Phân Quyền",
                    description: "Quản lý người dùng và phân quyền chi tiết theo vai trò trong hệ thống.",
                    icon: "team"
                }
            ]
        };
    }
});
</script>

<style>
/* You can add global styles here */
.bg-primary {
    background-color: #1890ff !important;
}

.feature-card {
    background-color: #fff;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    padding: 2rem;
    transition: all 0.3s ease-in-out;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
}

.image-wrapper { 
    width: 100%; 
    height: auto; /* hoặc chiều cao bạn muốn */ 
    overflow: hidden; border-radius: 8px; 
} 

.full-cover { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    object-position: center; 
}

.bg-header {
    background-color: #0078e8; /* Màu nền của header */
    position: sticky;
    top: 0;
    z-index: 1000; /* Đảm bảo header luôn ở trên cùng */
}

</style>