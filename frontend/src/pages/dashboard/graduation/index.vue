<template>
  <div class="landing-page">
    <!-- Header -->
    <header class="fixed-header">
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <div class="navbar-nav mx-auto">
            <a class="nav-link" href="#gioi-thieu" @click="scrollTo('gioi-thieu')">Giới thiệu</a>
            <a class="nav-link" href="#thiep-moi" @click="scrollTo('thiep-moi')">Thiệp mời</a>
            <a class="nav-link" href="#nam-thang" @click="scrollTo('nam-thang')">Năm tháng đi qua</a>
            <a class="nav-link" href="#lien-he" @click="scrollTo('lien-he')">Liên hệ</a>
          </div>
          <a-button type="primary" class="send-wishes-btn" @click="goToSendWishes">
            Gửi lời chúc
          </a-button>
        </div>
      </nav>
    </header>

    <!-- Body -->
    <main class="main-content">
      <!-- Hero Section -->
      <section class="hero-section">
        <div class="container">
          <h1 class="hero-title">Chào mừng đến với trang của chúng tôi</h1>
          <p class="hero-subtitle">Hành trình tuyệt vời đang chờ đón bạn</p>
        </div>
      </section>

      <!-- Giới thiệu Section -->
      <section id="gioi-thieu" class="introduction-section section-padding">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="intro-content">
                <h2 class="section-title">Giới thiệu</h2>
                <p class="section-description">
                  Chào mừng bạn đến với không gian đặc biệt của chúng tôi. Đây là nơi lưu giữ những kỷ niệm đẹp,
                  những câu chuyện ý nghĩa và những khoảnh khắc quý giá trong cuộc sống. 
                  Chúng tôi mong muốn chia sẻ với bạn hành trình tuyệt vời này.
                </p>
                <p class="section-description">
                  Hãy cùng chúng tôi khám phá những điều tuyệt vời và tạo nên những kỷ niệm mới.
                </p>
              </div>
            </div>
            <div class="col-lg-6">
              <a-carousel autoplay effect="fade" class="intro-carousel">
                <div class="carousel-item">
                  <img src="https://picsum.photos/600/400?random=1" alt="Giới thiệu 1" />
                </div>
                <div class="carousel-item">
                  <img src="https://picsum.photos/600/400?random=2" alt="Giới thiệu 2" />
                </div>
                <div class="carousel-item">
                  <img src="https://picsum.photos/600/400?random=3" alt="Giới thiệu 3" />
                </div>
              </a-carousel>
            </div>
          </div>
        </div>
      </section>

      <!-- Thiệp mời Section -->
      <section id="thiep-moi" class="invitation-section section-padding">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="invitation-image">
                <img src="https://picsum.photos/600/500?random=4" alt="Thiệp mời" class="img-fluid rounded" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="invitation-content">
                <h2 class="section-title">Thiệp mời</h2>
                <p class="section-description">
                  Chúng tôi trân trọng gửi đến bạn lời mời tham gia vào sự kiện đặc biệt này.
                  Đây sẽ là một dịp tuyệt vời để chúng ta cùng nhau chia sẻ những khoảnh khắc ý nghĩa.
                </p>
                <p class="section-description">
                  Sự hiện diện của bạn sẽ làm cho sự kiện thêm phần ý nghĩa và trọn vẹn.
                  Hãy cùng chúng tôi tạo nên những kỷ niệm đẹp.
                </p>
                <a-button type="primary" size="large" class="mt-3" @click="goToSendWishes">
                  Gửi lời chúc mừng
                </a-button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Năm tháng đi qua Section -->
      <section id="nam-thang" class="timeline-section section-padding">
        <div class="container">
          <h2 class="section-title text-center mb-5">Quy Trình Hoạt Động</h2>
          <div class="timeline">
            <div class="timeline-line"></div>
            
            <div v-for="(event, index) in timelineEvents" :key="index" 
                 class="timeline-item" 
                 :class="{ 'timeline-item-right': index % 2 === 0, 'timeline-item-left': index % 2 === 1 }">
              
              <!-- Timeline Date and Number -->
              <div class="timeline-marker">
                <div class="timeline-date" :class="{ 'timeline-date-left': index % 2 === 0, 'timeline-date-right': index % 2 === 1 }">
                  {{ event.date }}
                </div>
                <div class="timeline-number">{{ index + 1 }}</div>
              </div>

              <!-- Timeline Content -->
              <div class="timeline-content">
                <h3 class="timeline-title">{{ event.title }}</h3>
                <div class="timeline-images mb-3">
                  <a-carousel v-if="event.images.length > 1" autoplay>
                    <div v-for="(image, imgIndex) in event.images" :key="imgIndex" class="timeline-carousel-item">
                      <img :src="image" :alt="`${event.title} ${imgIndex + 1}`" />
                    </div>
                  </a-carousel>
                  <img v-else :src="event.images[0]" :alt="event.title" class="single-image" />
                </div>
                <p class="timeline-description">{{ event.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer id="lien-he" class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h3 class="footer-title">Liên hệ</h3>
            <div class="contact-info">
              <p><i class="fas fa-envelope"></i> email@example.com</p>
              <p><i class="fas fa-phone"></i> +84 123 456 789</p>
              <p><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</p>
            </div>
          </div>
          <div class="col-lg-6">
            <h3 class="footer-title">Mạng xã hội</h3>
            <div class="social-links">
              <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
              <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
              <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2024 Landing Page. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import { message } from 'ant-design-vue';

export default {
  name: 'LandingPage',
  data() {
    return {
      timelineEvents: [
        {
          title: "Tạo Văn Bản",
          date: "Tháng 1",
          images: ["https://picsum.photos/400/300?random=5"],
          description: "Người dùng tạo văn bản từ mẫu có sẵn hoặc tải lên văn bản mới và thiết lập luồng phê duyệt."
        },
        {
          title: "Gửi Phê Duyệt",
          date: "Tháng 3",
          images: ["https://picsum.photos/400/300?random=6", "https://picsum.photos/400/300?random=7"],
          description: "Văn bản được gửi đến người phê duyệt đầu tiên trong luồng theo quy trình đã thiết lập."
        },
        {
          title: "Xem Xét & Phê Duyệt",
          date: "Tháng 6",
          images: ["https://picsum.photos/400/300?random=8"],
          description: "Người phê duyệt xem xét nội dung, có thể từ chối hoặc phê duyệt văn bản và chuyển đến bước tiếp theo."
        },
        {
          title: "Hoàn Thành",
          date: "Tháng 9",
          images: ["https://picsum.photos/400/300?random=9", "https://picsum.photos/400/300?random=10"],
          description: "Sau khi hoàn tất quá trình phê duyệt, văn bản được lưu trữ và có thể được truy cập bởi những người có quyền."
        },
        {
          title: "Lưu Trữ & Quản Lý",
          date: "Tháng 12",
          images: ["https://picsum.photos/400/300?random=11"],
          description: "Văn bản được lưu trữ trong hệ thống quản lý tài liệu để dễ dàng tìm kiếm và truy cập trong tương lai."
        }
      ]
    };
  },
  methods: {
    scrollTo(elementId) {
      const element = document.getElementById(elementId);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
      }
    },
    goToSendWishes() {
      message.info('Chuyển hướng đến trang gửi lời chúc...');
      // Thêm logic chuyển hướng ở đây
      // this.$router.push('/send-wishes');
    }
  }
};
</script>

<style scoped>
/* Global Styles */
.landing-page {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(-45deg, #003a69 0%, #4392ba 28%, #28b6de 67%, #00eeff 100%);
  background-size: 400% 400%;
  animation: gradientShift 15s ease infinite;
  min-height: 100vh;
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Header Styles */
.fixed-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.navbar {
  padding: 1rem 0;
}

.nav-link {
  color: #333 !important;
  font-weight: 500;
  margin: 0 1rem;
  transition: all 0.3s ease;
  text-decoration: none;
}

.nav-link:hover {
  color: #667eea !important;
  transform: translateY(-2px);
}

.send-wishes-btn {
  background: linear-gradient(45deg, #667eea, #764ba2);
  border: none;
  border-radius: 25px;
  padding: 8px 24px;
  font-weight: 500;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
}

.send-wishes-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Main Content */
.main-content {
  padding-top: 80px;
}

/* Hero Section */
.hero-section {
  padding: 100px 0;
  text-align: center;
  color: white;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-subtitle {
  font-size: 1.2rem;
  opacity: 0.9;
  margin-bottom: 2rem;
}

/* Section Styles */
.section-padding {
  padding: 80px 0;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 1.5rem;
  position: relative;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 50px;
  height: 3px;
  background: linear-gradient(45deg, #667eea, #764ba2);
  border-radius: 2px;
}

.section-description {
  font-size: 1.1rem;
  line-height: 1.7;
  color: #666;
  margin-bottom: 1rem;
}

/* Introduction Section */
.introduction-section {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
}

.intro-carousel .carousel-item img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  border-radius: 15px;
}

/* Invitation Section */
.invitation-section {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(10px);
}

.invitation-image img {
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease;
}

.invitation-image img:hover {
  transform: scale(1.05);
}

/* Timeline Section */
.timeline-section {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  position: relative;
}

.timeline {
  position: relative;
  max-width: 1200px;
  margin: 0 auto;
}

.timeline-line {
  position: absolute;
  left: 50%;
  top: 0;
  bottom: 0;
  width: 4px;
  background: linear-gradient(to bottom, #667eea, #764ba2);
  transform: translateX(-50%);
  border-radius: 2px;
}

.timeline-item {
  position: relative;
  margin: 60px 0;
  display: flex;
  align-items: center;
}

.timeline-item-right {
  justify-content: flex-start;
}

.timeline-item-left {
  justify-content: flex-end;
}

/* Timeline Marker */
.timeline-marker {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2;
}

.timeline-number {
  width: 50px;
  height: 50px;
  background: linear-gradient(45deg, #667eea, #764ba2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-size: 1.2rem;
  box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.9);
  margin: 10px 0;
}

.timeline-date {
  background: linear-gradient(45deg, #667eea, #764ba2);
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
  white-space: nowrap;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  margin-bottom: 10px;
}

.timeline-date-left {
  margin-left: -20px;
}

.timeline-date-right {
  margin-right: -20px;
}

.timeline-content {
  width: 45%;
  background: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  position: relative;
  transition: transform 0.3s ease;
}

.timeline-content:hover {
  transform: translateY(-5px);
}

.timeline-item-right .timeline-content::before {
  content: '';
  position: absolute;
  left: -15px;
  top: 30px;
  width: 0;
  height: 0;
  border-top: 15px solid transparent;
  border-bottom: 15px solid transparent;
  border-right: 15px solid white;
}

.timeline-item-left .timeline-content::before {
  content: '';
  position: absolute;
  right: -15px;
  top: 30px;
  width: 0;
  height: 0;
  border-top: 15px solid transparent;
  border-bottom: 15px solid transparent;
  border-left: 15px solid white;
}

.timeline-title {
  color: #333;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.timeline-images img,
.timeline-carousel-item img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 10px;
}

.single-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 10px;
}

.timeline-description {
  color: #666;
  line-height: 1.6;
  margin: 0;
}

/* Footer */
.footer {
  background: linear-gradient(135deg, #2c3e50, #34495e);
  color: white;
  padding: 60px 0 20px;
}

.footer-title {
  color: white;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.contact-info p {
  margin-bottom: 0.8rem;
  display: flex;
  align-items: center;
}

.contact-info i {
  margin-right: 10px;
  width: 20px;
  color: #3498db;
}

.social-links {
  display: flex;
  gap: 15px;
}

.social-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 45px;
  height: 45px;
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border-radius: 50%;
  text-decoration: none;
  transition: all 0.3s ease;
}

.social-link:hover {
  background: #3498db;
  transform: translateY(-3px);
  color: white;
}

.footer-bottom {
  text-align: center;
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  opacity: 0.7;
}

/* Responsive Design */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  /* Mobile Timeline */
  .timeline-line {
    left: 30px;
  }
  
  .timeline-marker {
    left: 30px;
  }
  
  .timeline-number {
    width: 40px;
    height: 40px;
    font-size: 1rem;
  }
  
  .timeline-date {
    font-size: 0.8rem;
    padding: 6px 12px;
    margin-left: 0;
    margin-right: 0;
    margin-bottom: 8px;
  }
  
  .timeline-content {
    width: calc(100% - 80px);
    margin-left: 70px;
    padding: 20px;
  }
  
  .timeline-item-left .timeline-content,
  .timeline-item-right .timeline-content {
    margin-left: 70px;
  }
  
  .timeline-content::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 20px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-right: 15px solid white;
  }
  
  .timeline-item-left .timeline-content::before {
    left: -15px;
    border-left: none;
    border-right: 15px solid white;
  }
  
  .timeline-title {
    font-size: 1.3rem;
  }
  
  .timeline-images img,
  .timeline-carousel-item img,
  .single-image {
    height: 150px;
  }
  
  /* Mobile Navigation */
  .navbar-nav {
    flex-direction: column;
    text-align: center;
  }
  
  .nav-link {
    margin: 0.5rem 0;
  }
  
  .send-wishes-btn {
    margin-top: 1rem;
  }
  
  /* Mobile Sections */
  .section-padding {
    padding: 60px 0;
  }
  
  .intro-carousel .carousel-item img {
    height: 250px;
  }
  
  .hero-section {
    padding: 60px 0;
  }
}

@media (max-width: 576px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .hero-subtitle {
    font-size: 1rem;
  }
  
  .section-title {
    font-size: 1.8rem;
  }
  
  .timeline-content {
    width: calc(100% - 60px);
    margin-left: 55px;
    padding: 15px;
  }
  
  .timeline-item-left .timeline-content,
  .timeline-item-right .timeline-content {
    margin-left: 55px;
  }
  
  .timeline-marker {
    left: 25px;
  }
  
  .timeline-line {
    left: 25px;
  }
  
  .timeline-number {
    width: 35px;
    height: 35px;
    font-size: 0.9rem;
  }
  
  .timeline-date {
    font-size: 0.7rem;
    padding: 4px 8px;
  }
  
  .timeline-title {
    font-size: 1.1rem;
  }
  
  .section-description {
    font-size: 1rem;
  }
}

/* Tablet Responsive */
@media (min-width: 769px) and (max-width: 1024px) {
  .timeline-content {
    width: 42%;
  }
  
  .timeline-date {
    font-size: 0.85rem;
  }
  
  .timeline-title {
    font-size: 1.4rem;
  }
}
</style>