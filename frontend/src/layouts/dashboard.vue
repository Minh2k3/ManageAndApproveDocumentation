<template>
    <div class="min-h-screen d-flex flex-column">
        <!-- Canvas cho hiệu ứng particles -->
        <canvas ref="particleCanvas" class="particle-canvas"></canvas>
        
        <header class="bg-header text-white py-4" :class="{ 'header-hidden': isHeaderHidden }">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="../assets/images/logo_tlu.png" alt="Logo ĐH Thủy Lợi" class="me-2"
                        style="height: 40px; width: 40px;" />
                    <span class="fs-4 mb-0 fw-bold">ĐH THỦY LỢI</span>
                </div>
                <nav class="d-none d-md-flex">
                    <!-- <router-link to="#" class="text-white mx-3 text-decoration-none fw-medium ">Trang chủ</router-link> -->
                    <a href="#home" @click.prevent="scrollToHome" class="text-white mx-3
                        text-decoration-none fw-bold">Trang chủ</a>
                    <router-link to="retrieve" class="text-white mx-3 text-decoration-none">Tra cứu chứng chỉ</router-link>
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

        <!-- Vùng cảm ứng để hiện header khi di chuột lên trên -->
        <div class="header-trigger" @mouseenter="showHeader"></div>

        <main class="flex-grow-1">
            <section class="bg-primary text-white py-5" style="z-index: 3;">
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
                                <a-button type="primary" id="btn-moreInformation">Tìm hiểu thêm</a-button>
                                <router-link to="/register">
                                    <a-button ghost id="btn-register">Đăng ký</a-button>
                                </router-link>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <a-carousel autoplay>
                                <div>
                                    <h3>
                                        <img src="@/assets/images/dhtl.jpg" alt="Đại học Thủy lợi"
                                            class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                    </h3>
                                </div>
                                <div>
                                    <h3>
                                        <img src="@/assets/images/Cosette_square.jpg" alt="Em Cosette"
                                            class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                    </h3>
                                </div>
                                <div>
                                    <h3>
                                        <img src="@/assets/images/Retro_Punk_square.jpg" alt="Em gái Anime nằm trên giường"
                                            class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                    </h3>
                                </div>
                                <div>
                                    <h3>
                                        <img src="@/assets/images/YourLieInApril.jpg" alt="Minh họa hệ thống"
                                            class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                    </h3>
                                </div>
                                <div>
                                    <h3>
                                        <img src="@/assets/images/ZeroTwo.png" alt="Minh họa hệ thống"
                                            class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                    </h3>
                                </div>
                                <div>
                                    <h3>
                                        <img src="@/assets/images/Ai_Hoshino.png" alt="Minh họa hệ thống"
                                            class="img-fluid rounded shadow" style="max-width: 100%; height: auto;" />
                                    </h3>
                                </div>
                            </a-carousel>                            
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="py-5 bg-light">
                <div class="container">
                    <h2 class="text-center mb-5 fw-bold">Tính Năng Nổi Bật</h2>
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-4 col-md-6 col-sm-6" v-for="feature in features"
                            :key="feature.title"
                            style="z-index: 3;"
                            >
                            <div class="feature-card-wrapper">
                                <feature-card :title="feature.title" :description="feature.description"
                                    :icon="feature.icon" 
                                    />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-5 bg-white" id="process-flow" style="z-index: 30;">
                <div class="container" >
                    <h2 class="text-center mb-5 fw-bold" >Quy Trình Hoạt Động</h2>
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

        <footer class="bg-dark text-white pt-4 pb-2" id="footer" style="z-index: 3;">
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
                            <!-- <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Trang chủ
                                </router-link>
                            </li> -->
                            <li class="mb-2">
                                <a href="#home" 
                                    @click.prevent="scrollToHome" 
                                    class="text-white text-decoration-none">
                                    Trang chủ
                                </a>
                            </li>
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Giới thiệu
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <a href="#features" 
                                    @click.prevent="scrollToFeatures" 
                                    class="text-white text-decoration-none">
                                    Tính năng
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#process-flow" 
                                    @click.prevent="scrollToProcessFlow" 
                                    class="text-white text-decoration-none">
                                    Quy trình hoạt động
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h3 class="h5 fw-bold mb-3">Hỗ Trợ</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    FAQ
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Trung tâm hỗ trợ
                                </router-link>
                                </li>
                            <li class="mb-2">
                                <router-link to="#" class="text-white text-decoration-none">
                                    Hướng dẫn sử dụng
                                </router-link>
                            </li>
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
    data() {
        return {
            isHeaderHidden: false,
            hideTimeout: null,
            lastScrollY: 0,
            particles: [],
            animationId: null,
            mouse: { x: 0, y: 0 },
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
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
        
        // Sử dụng nextTick để đảm bảo DOM đã render
        this.$nextTick(() => {
            setTimeout(() => {
                this.initParticles();
                this.animate();
            }, 100);
        });
        
        window.addEventListener('resize', this.handleResize);
        window.addEventListener('mousemove', this.handleMouseMove);
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
        window.removeEventListener('resize', this.handleResize);
        window.removeEventListener('mousemove', this.handleMouseMove);
        if (this.hideTimeout) {
            clearTimeout(this.hideTimeout);
        }
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
        }
    },
    methods: {
        handleScroll() {
            const currentScrollY = window.scrollY;
            
            // Nếu đang scroll xuống và đã scroll một khoảng nhất định
            if (currentScrollY > this.lastScrollY && currentScrollY > 100) {
                // Hủy timeout cũ nếu có
                if (this.hideTimeout) {
                    clearTimeout(this.hideTimeout);
                }
                
                // Thiết lập timeout mới để ẩn header sau 4 giây
                this.hideTimeout = setTimeout(() => {
                    this.isHeaderHidden = true;
                }, 4000);
            } else {
                // Nếu scroll lên thì hủy timeout và hiện header
                if (this.hideTimeout) {
                    clearTimeout(this.hideTimeout);
                    this.hideTimeout = null;
                }
                this.isHeaderHidden = false;
            }
            
            this.lastScrollY = currentScrollY;
        },
        showHeader() {
            // Hủy timeout ẩn header
            if (this.hideTimeout) {
                clearTimeout(this.hideTimeout);
                this.hideTimeout = null;
            }
            this.isHeaderHidden = false;
        },
        scrollToHome() {
            const element = document.getElementById("home");
            if (element) {
                element.scrollIntoView({ behavior: "smooth" });
            }
        },
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
        
        // Particle System Methods
        initParticles() {
            const canvas = this.$refs.particleCanvas;
            // console.log('Canvas element:', canvas); // Debug
            if (!canvas) {
                console.error('Canvas not found!');
                return;
            }
            
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            // console.log('Canvas size:', canvas.width, 'x', canvas.height); // Debug
            
            this.particles = [];
            const particleCount = Math.floor((canvas.width * canvas.height) / 12000); // Số lượng particles
            // console.log('Particle count:', particleCount); 
            
            for (let i = 0; i < particleCount; i++) {
                this.particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    vx: (Math.random() - 0.5) * 0.5,
                    vy: (Math.random() - 0.5) * 0.5,
                    size: Math.random() * 2 + 1,
                    opacity: Math.random() * 0.5 + 0.2,
                    originalSize: Math.random() * 2 + 1
                });
            }
            // console.log('Particles initialized:', this.particles.length); // Debug
        },
        
        animate() {
            const canvas = this.$refs.particleCanvas;
            if (!canvas) {
                console.error('Canvas not found in animate!');
                return;
            }
            
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Canvas context not found!');
                return;
            }
            
            // Clear canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // // Test: Vẽ một hình tròn lớn màu đỏ
            // ctx.fillStyle = 'red';
            // ctx.beginPath();
            // ctx.arc(100, 100, 20, 0, Math.PI * 2);
            // ctx.fill();
            
            // // Test: Vẽ một hình tròn khác
            // ctx.fillStyle = 'blue';
            // ctx.beginPath();
            // ctx.arc(200, 200, 15, 0, Math.PI * 2);
            // ctx.fill();
            
            // Update và vẽ particles với màu sáng hơn
            this.particles.forEach((particle, index) => {
                // Di chuyển particle
                particle.x += particle.vx;
                particle.y += particle.vy;
                
                // Bounce off edges
                if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
                if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;
                
                // Tương tác với chuột
                const dx = this.mouse.x - particle.x;
                const dy = this.mouse.y - particle.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 100) {
                    const force = (100 - distance) / 100;
                    particle.x -= dx * force * 0.01;
                    particle.y -= dy * force * 0.01;
                    particle.size = particle.originalSize * (1 + force);
                    particle.opacity = Math.min(1, particle.opacity + force * 0.3);
                } else {
                    particle.size = particle.originalSize;
                    particle.opacity = Math.max(0.2, particle.opacity - 0.01);
                }
                
                // Vẽ particle với màu sáng để dễ thấy
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, Math.max(particle.size, 3), 0, Math.PI * 2);
                ctx.fillStyle = `rgba(128, 125, 125, ${Math.max(particle.opacity, 0.8)})`; // Đỏ sáng
                ctx.fill();
                
                // Debug: log vị trí của particle đầu tiên
                if (index === 0) {
                    // console.log(`Particle 0 position: ${particle.x.toFixed(1)}, ${particle.y.toFixed(1)}`);
                }
            });
            
            // Vẽ connections với màu sáng hơn
            this.drawConnections(ctx);
            
            this.animationId = requestAnimationFrame(this.animate);
        },
        
        drawConnections(ctx) {
            const maxDistance = 120;
            
            for (let i = 0; i < this.particles.length; i++) {
                for (let j = i + 1; j < this.particles.length; j++) {
                    const dx = this.particles[i].x - this.particles[j].x;
                    const dy = this.particles[i].y - this.particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < maxDistance) {
                        const opacity = (maxDistance - distance) / maxDistance * 0.5; // Tăng opacity
                        ctx.beginPath();
                        ctx.moveTo(this.particles[i].x, this.particles[i].y);
                        ctx.lineTo(this.particles[j].x, this.particles[j].y);
                        ctx.strokeStyle = `rgba(128, 125, 125, ${opacity})`; // Đỏ sáng
                        ctx.lineWidth = 2; // Tăng độ dày
                        ctx.stroke();
                    }
                }
            }
        },
        
        handleResize() {
            const canvas = this.$refs.particleCanvas;
            if (!canvas) return;
            
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            this.initParticles();
        },
        
        handleMouseMove(event) {
            this.mouse.x = event.clientX;
            this.mouse.y = event.clientY;
        },
    }
});
</script>

<style>
/* Particle Canvas */
.particle-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    background: transparent;
    border: 2px solid rgb(255, 255, 255); /* Tạm thời để test */
}

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

.feature-card-wrapper {
    background-color: #fff;
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    padding: 2rem;
    height: 100%;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

.feature-card-wrapper:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
}

.image-wrapper { 
    width: 100%; 
    height: auto;
    overflow: hidden; 
    border-radius: 8px; 
} 

.full-cover { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    object-position: center; 
}

.bg-header {
    background-color: #0078e8;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
}

.header-hidden {
    transform: translateY(-100%);
}

.header-trigger {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 60px;
    z-index: 999;
    pointer-events: auto;
}

/* Đảm bảo nội dung không bị che bởi fixed header */
main {
    padding-top: 80px;
}

/* Cross-browser compatibility cho features grid */
.row.g-4 {
    display: flex;
    flex-wrap: wrap;
    margin: -0.75rem;
}

.row.g-4 > * {
    padding: 0.75rem;
    flex: 0 0 auto;
}

#btn-register:hover {
    border: #1890ff 1px solid;
    background-color: #ffffff;
    color: #1890ff;
}

#btn-moreInformation {
    border: #1890ff 1px solid;
    background-color: #ffffff;
    color: #1890ff;
}

#btn-moreInformation:hover {
    border: #ffffff 1px solid;
    background-color: #1890ff;
    color: #ffffff;
}

/* Responsive breakpoints để đảm bảo layout nhất quán */
@media (min-width: 992px) {
    .col-lg-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
}

@media (min-width: 768px) and (max-width: 991.98px) {
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (min-width: 576px) and (max-width: 767.98px) {
    .col-sm-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 575.98px) {
    main {
        padding-top: 70px;
    }
    
    .feature-card-wrapper {
        margin-bottom: 1rem;
    }
}
</style>