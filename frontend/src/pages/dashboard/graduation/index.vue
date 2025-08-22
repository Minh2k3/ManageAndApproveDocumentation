<template>
  <div class="landing-page">
    <!-- Header -->
    <header class="fixed-header">
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <!-- Logo/Brand -->
          <div class="navbar-brand">
            <span class="brand-text">Himakevolution</span>
          </div>
          
          <!-- Desktop Navigation -->
          <div class="navbar-nav mx-auto d-none d-lg-flex">
            <a class="nav-link" href="#gioi-thieu" @click="scrollTo('gioi-thieu')">Giới thiệu</a>
            <a class="nav-link" href="#thiep-moi" @click="scrollTo('thiep-moi')">Thiệp mời</a>
            <a class="nav-link" href="#nam-thang" @click="scrollTo('nam-thang')">Năm tháng đi qua</a>
            <a class="nav-link" href="#ky-niem" @click="scrollTo('ky-niem')">Kỷ niệm</a>  
            <a class="nav-link" href="#lien-he" @click="scrollTo('lien-he')">Liên hệ</a>
          </div>
          
          <!-- Desktop Send Wishes Button -->
          <button type="primary" class="send-wishes-btn text-white d-none d-lg-block" @click="goToSendWishes">
            Gửi lời chúc
          </button>
          
          <!-- Mobile Menu Toggle Button -->
          <button 
            class="mobile-menu-toggle d-lg-none" 
            @click="toggleMobileMenu"
            :class="{ 'active': isMobileMenuOpen }"
          >
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
          </button>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div class="mobile-menu d-lg-none" :class="{ 'show': isMobileMenuOpen }">
          <div class="mobile-menu-overlay" @click="closeMobileMenu"></div>
          <div class="mobile-menu-content">
            <div class="mobile-menu-header">
              <span class="mobile-brand">Himakevolution</span>
              <button class="mobile-close-btn" @click="closeMobileMenu">
                <i class="fas fa-times"></i>
              </button>
            </div>
            
            <div class="mobile-nav-links">
              <a class="mobile-nav-link" href="#gioi-thieu" @click="scrollToMobile('gioi-thieu')">
                <i class="fas fa-info-circle"></i>
                Giới thiệu
              </a>
              <a class="mobile-nav-link" href="#thiep-moi" @click="scrollToMobile('thiep-moi')">
                <i class="fas fa-envelope"></i>
                Thiệp mời
              </a>
              <a class="mobile-nav-link" href="#nam-thang" @click="scrollToMobile('nam-thang')">
                <i class="fas fa-calendar-alt"></i>
                Năm tháng đi qua
              </a>
              <a class="mobile-nav-link" href="#ky-niem" @click="scrollToMobile('ky-niem')">
                <i class="fas fa-heart"></i>
                Kỷ niệm
              </a>
              <a class="mobile-nav-link" href="#lien-he" @click="scrollToMobile('lien-he')">
                <i class="fas fa-phone"></i>
                Liên hệ
              </a>
            </div>
            
            <div class="mobile-menu-footer">
              <button type="primary" class="mobile-send-wishes-btn text-white" @click="goToSendWishesMobile">
                <i class="fas fa-paper-plane"></i>
                Gửi lời chúc
              </button>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <!-- Body -->
    <main class="main-content">
      <!-- Hero Section -->
      <section class="hero-section">
        <div class="container">
          <h1 class="hero-title" :class="{ 'typing': isTypingTitle }">{{ displayedTitle }}</h1>
          <p class="hero-subtitle" :class="{ 'typing': isTypingSubtitle }">{{ displayedSubtitle }}</p>
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
                  Tớ là Trần Minh, sinh viên lớp 63CNTT4, khoa Công nghệ Thông tin, Trường Đại học Thủy lợi
                </p>
                <!-- <p class="section-description">
                  Hãy cùng chúng tôi khám phá những điều tuyệt vời và tạo nên những kỷ niệm mới.
                </p> -->
              </div>
            </div>
            <div class="col-lg-6">
              <a-carousel autoplay effect="fade" class="intro-carousel">
                <div class="carousel-item">
                  <img loading="lazy" :src="images['Minh_1']" alt="Giới thiệu 1" />
                </div>
                <div class="carousel-item">
                  <img loading="lazy" :src="images['Minh_2']" alt="Giới thiệu 2" />
                </div>
                <div class="carousel-item">
                  <img loading="lazy" :src="images['Minh_3']" alt="Giới thiệu 3" />
                </div>
                <div class="carousel-item">
                  <img loading="lazy" :src="images['Minh_4']" alt="Giới thiệu 4" />
                </div>
                <div class="carousel-item">
                  <img loading="lazy" :src="images['Minh_5']" alt="Giới thiệu 5" />
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
                <img loading="lazy" :src="images['Thiệp']" alt="Thiệp mời" class="img-fluid rounded" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="invitation-content">
                <h2 class="section-title">Thiệp mời</h2>
                <p class="section-description">
                  Trân quý và biết ơn sự hiện diện của cậu ở đây. Nếu có lời nào muốn gửi gắm đến tớ hay là có những bức hình chung mà cậu chưa từng cho tớ xem, hãy nhấn nút phía dưới, tớ sẽ đón nhận mọi điều tốt đẹp từ cậu.
                </p>
                <!-- <p class="section-description">
                  Sự hiện diện của bạn sẽ làm cho sự kiện thêm phần ý nghĩa và trọn vẹn.
                  Hãy cùng chúng tôi tạo nên những kỷ niệm đẹp.
                </p> -->
                <a-button type="primary" size="large" class="mt-3" @click="goToSendWishes">
                  Gửi lời chúc mừng
                </a-button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Năm tháng đi qua Section with Sidebar -->
      <section id="nam-thang" class="timeline-section section-padding">
        <div class="container-fluid">
          <div class="row">
            <!-- Sidebar Navigation - Only show on desktop -->
            <div class="col-lg-3 d-none d-lg-block">
              <div class="timeline-sidebar">
                <h3 class="sidebar-title">HÀNH TRÌNH 4 NĂM</h3>
                <div class="timeline-nav">
                  <div 
                    v-for="(period, periodIndex) in timelinePeriods" 
                    :key="periodIndex"
                    class="nav-item"
                    :class="{ 
                      'active': activePeriod === periodIndex,
                      'in-view': isPeriodInView(periodIndex)
                    }"
                    @click="scrollToPeriod(periodIndex)"
                  >
                    <div class="nav-item-content">
                      <div class="nav-period">{{ period.period }}</div>
                      <div class="nav-title">{{ period.shortTitle }}</div>
                      <div class="nav-duration">{{ period.shortDuration }}</div>
                      <div class="nav-events-count">{{ period.events.length }} sự kiện</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Main Timeline Content -->
            <div class="col-lg-9">
              <div class="timeline-main">
                <h2 class="section-title text-center mb-3">
                  Năm tháng đi qua
                </h2>
                <p class="timeline-subtitle text-center mb-5">Hành trình 4 năm tại TLU không ngắn cũng chẳng dài, nhưng sẽ là một kỉ niệm khó phai</p>
                
                <div class="timeline">
                  <div class="timeline-line"></div>
                  
                  <!-- Loop through periods -->
                  <template v-for="(period, periodIndex) in timelinePeriods" :key="periodIndex">
                    <!-- Period Header -->
                    <div 
                      :id="`period-header-${periodIndex}`"
                      class="period-header"
                      :ref="el => { if (el) periodHeaders[periodIndex] = el }"
                    >
                      <div class="period-marker">
                        <div class="period-icon">
                          <i class="fas fa-crown"></i>
                        </div>
                      </div>
                      <div class="period-info">
                        <h2 class="period-title">{{ period.period }}</h2>
                        <div class="period-duration">{{ period.duration }}</div>
                        <div class="period-description">{{ period.description }}</div>
                      </div>
                    </div>

                    <!-- Events in this period -->
                    <div 
                      v-for="(event, eventIndex) in period.events" 
                      :key="`${periodIndex}-${eventIndex}`" 
                      :id="`timeline-item-${periodIndex}-${eventIndex}`"
                      class="timeline-item" 
                      :class="{ 
                        'timeline-item-left': eventIndex % 2 === 0, 
                        'timeline-item-right': eventIndex % 2 === 1 
                      }"
                      :ref="el => { if (el) timelineItems.push(el) }"
                    >
                      <!-- Timeline Date and Number -->
                      <div class="timeline-marker">
                        <div class="timeline-number">{{ getEventNumber(periodIndex, eventIndex) }}</div>
                        <div class="timeline-date" :class="{ 
                          'timeline-date-left': eventIndex % 2 === 0, 
                          'timeline-date-right': eventIndex % 2 === 1 
                        }">
                          {{ event.date }}
                        </div>
                      </div>

                      <!-- Timeline Content -->
                      <div class="timeline-content">
                        <h3 class="timeline-title">{{ event.title }}</h3>
                        
                        <!-- Duration Badge -->
                        <div class="duration-badge" v-if="event.specificDuration">
                          {{ event.specificDuration }}
                        </div>
                        
                        <div class="timeline-images mb-3" v-if="event.images && event.images.length > 0">
                          <a-carousel v-if="event.images.length > 1" autoplay>
                            <div v-for="(image, imgIndex) in event.images" :key="imgIndex" class="timeline-carousel-item">
                              <img loading="lazy" :src="image" :alt="`${event.title} ${imgIndex + 1}`" />
                            </div>
                          </a-carousel>
                          <img loading="lazy" v-else :src="event.images[0]" :alt="event.title" class="single-image" />
                        </div>
                        
                        <p class="timeline-description">{{ event.description }}</p>
                        
                        <!-- Key Events -->
                        <div v-if="event.keyEvents && event.keyEvents.length > 0" class="key-events">
                          <h4 class="text-danger"><i class="fas fa-star"></i> Điểm nổi bật:</h4>
                          <ul>
                            <li v-for="keyEvent in event.keyEvents" :key="keyEvent">{{ keyEvent }}</li>
                          </ul>
                        </div>
                        
                        <!-- Historical Figures -->
                        <div v-if="event.figures && event.figures.length > 0" class="historical-figures">
                          <h4 class="text-danger"><i class="fas fa-crown"></i> Nhân vật liên quan:</h4>
                          <div class="figures-list">
                            <a-tag v-for="figure in event.figures" :key="figure" color="blue" class="figure-tag">{{ figure }}</a-tag>
                          </div>
                        </div>
                        
                        <!-- View Details Button -->
                        <div class="event-actions">
                          <a-button 
                            type="link" 
                            class="view-details-btn"
                            @click="viewEventDetails(event)"
                          >
                            Xem chi tiết
                            <i class="fas fa-arrow-right"></i>
                          </a-button>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section Kỷ Niệm -->
      <section id="ky-niem" class="memories-section section-padding">
        <div class="container">
          <h2 class="section-title text-center mb-3">
            <i class="fas fa-heart"></i>
            Kỷ Niệm Khó Quên
          </h2>
          <p class="memories-subtitle text-center mb-5">Mãi trong tim</p>
          
          <!-- Memories Carousel -->
          <div class="memories-carousel">
            <carousel
              :items-to-show="slidesPerView"
              :wrap-around="true"
              :autoplay="3000"
              :transition="500"
              :pause-on-hover="true"
            >
              <slide v-for="(memory, index) in memories" :key="index">
                <div class="memory-card" :class="{ 'featured': memory.featured }">
                  <div class="memory-avatar">
                    <img loading="lazy" :src="memory.avatar" :alt="memory.name" />
                    <div v-if="memory.featured" class="featured-badge">
                      <i class="fas fa-check"></i>
                    </div>
                  </div>
                  <div class="memory-content">
                    <h3 class="memory-name">{{ memory.name }}</h3>
                    <span class="memory-role" :class="memory.roleClass">{{ memory.role }}</span>
                    <p class="memory-description">{{ memory.description }}</p>
                    <button type="primary" class="contact-btn text-white" @click="contactMemory(memory)">
                      <i class="fas fa-comments"></i>
                      Liên hệ
                    </button>
                  </div>
                </div>
              </slide>

              <!-- Navigation Arrows -->
              <template #addons>
                <navigation>
                  <template #prev>
                    <button class="slider-nav prev">
                      <i class="fas fa-chevron-left"></i>
                    </button>
                  </template>
                  <template #next>
                    <button class="slider-nav next">
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </template>
                </navigation>
                <pagination />
              </template>
            </carousel>
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
              <p><i class="fas fa-envelope"></i>minhhblh31@gmail.com</p>
              <p><i class="fas fa-phone"></i>(+84) 369 525 313</p>
              <p><i class="fas fa-map-marker-alt"></i>Hà Nội, Việt Nam</p>
            </div>
          </div>
          <div class="col-lg-6">
            <h3 class="footer-title">Mạng xã hội</h3>
            <div class="social-links">
              <a href="https://www.facebook.com/TranMinhTM7/" class="social-link"><i class="fab fa-facebook-f"></i></a>
              <a href="https://www.instagram.com/himakevolution/" class="social-link"><i class="fab fa-instagram"></i></a>
              <a href="https://www.youtube.com/@Himakevolution" class="social-link"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2025 Trần Tuấn Minh - Himakevolution</p>
        </div>
      </div>
    </footer>

    <!-- Event Detail Modal -->
    <a-modal
      v-model:open="eventDetailModalVisible"
      :title="selectedEvent?.title"
      width="50%"
      :footer="null"
      class="event-detail-modal"
    >
      <div v-if="selectedEvent" class="event-detail-content">
        <div class="detail-header">
          <a-tag>
            <span class="detail-date">{{ selectedEvent.date }}</span>
          </a-tag>
        </div>
        
        <div class="detail-image" v-if="selectedEvent.images && selectedEvent.images.length > 0">
          <a-carousel autoplay>
            <div v-for="(image, imgIndex) in selectedEvent.images" :key="imgIndex">
              <img loading="lazy" :src="image" :alt="`${selectedEvent.title} ${imgIndex + 1}`" />
            </div>
          </a-carousel>
        </div>
        
        <div class="detail-description">
          <p>{{ selectedEvent.description }}</p>
          <p v-if="selectedEvent.detailedDescription">{{ selectedEvent.detailedDescription }}</p>
        </div>
        
        <div v-if="selectedEvent.keyEvents" class="detail-key-events">
          <h4><i class="fas fa-star"></i> Điểm nổi bật:</h4>
          <ul>
            <li v-for="keyEvent in selectedEvent.keyEvents" :key="keyEvent">{{ keyEvent }}</li>
          </ul>
        </div>
        
        <div v-if="selectedEvent.figures" class="detail-figures">
          <h4><i class="fas fa-crown"></i> Nhân vật liên quan:</h4>
          <div class="figures-grid">
            <a-tag v-for="figure in selectedEvent.figures" :key="figure" color="blue" class="detail-figure-tag">
              {{ figure }}
            </a-tag>
          </div>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import { message, Tag as ATag } from 'ant-design-vue'
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

// Router
const router = useRouter()

// Mobile menu state
const isMobileMenuOpen = ref(false)

// Reactive data
const eventDetailModalVisible = ref(false)
const selectedEvent = ref(null)
const activePeriod = ref(0)
const periodHeaders = ref([])
const timelineItems = ref([])

// Hero typewriter effect
const displayedTitle = ref('')
const displayedSubtitle = ref('')
const isTypingTitle = ref(false)
const isTypingSubtitle = ref(false)
const fullTitle = 'Chào cậu, tớ là Himakevolution'
const fullSubtitle = 'Thật vui khi cậu có mặt ở đây cùng tớ'

// Responsive slides per view
const slidesPerView = computed(() => {
  if (window.innerWidth <= 576) return 1
  if (window.innerWidth <= 768) return 1
  if (window.innerWidth <= 1024) return 2
  return 3
})

// Images path array
const images = {
  'Thiệp': '/images/another/Thiệp.png',
  'Minh_1': '/images/another/Minh_1.jpg',
  'Minh_2': '/images/another/Minh_2.jpg',
  'Minh_3': '/images/another/Minh_3.jpg',
  'Minh_4': '/images/another/Minh_4.jpg',
  'Minh_5': '/images/another/Minh_5.jpg',
}

// Timeline periods data
const timelinePeriods = ref([
  {
    period: "Năm nhất nè",
    shortTitle: "Thuở chập chững vào trường",
    duration: "9/2021 - 8/2022",
    shortDuration: "9/2021 - 8/2022",
    description: "Những bước chân đầu tiên - Bỡ ngỡ, lạ lẫm nhưng không mất thời gian để làm quen",
    events: [
      {
        title: "Sinh hoạt đầu khóa",
        date: "04/10/2021",
        specificDuration: "Sáng 04/10/2021",
        images: ["/images/freshman/SinhHoatDauKhoa_1.png", "/images/freshman/SinhHoatDauKhoa_2.png"],
        description: "Buổi gặp mặt đầu tiên sau khi nhập học lại bằng hình thức trực tuyến.",
        detailedDescription: "Sinh hoạt đầu khóa là một trong những nội dung quan trọng khi mình bước vào môi trường đại học. Vì nó cho mình cái nhìn tổng quan về ngôi trường theo học, giúp mình giải đáp những thắc mắc trong quá trình học. Tuy vậy, thời đấy mình vẫn còn ngây ngô nên cứ treo máy chứ không thực sự tập trung. Cũng bởi thế nên mình đã bỏ phí rất nhiều thời gian để tìm hiểu chúng vào sau này.",
        keyEvents: [
          "Giới thiệu về trường",
          "Bài giảng đầu tiên",
        ],
        figures: ["Thầy Nguyễn Trung Việt - Hiệu trưởng"]
      },
      {
        title: "Chào tân K63 - Khoa CNTT",
        date: "07/10/2021",
        specificDuration: "Sáng 07/10/2021",
        images: ["/images/freshman/ChaoTanK63_1.png", "/images/freshman/ChaoTanK63_2.png"],
        description: "Chương trình chào tân sinh viên K63",
        detailedDescription: "Là lễ chào tân duy nhất tớ góp mặt với tư cách người tham dự. Trong 3 lần tổ chức tiếp theo, tớ luôn nằm trong BTC, và mỗi lần là một vai trò khác nhau.",
        keyEvents: [
          "Chào tân",
        ],
        figures: [
          "Thầy Tùng - Nguyên Trưởng khoa CNTT",
          "Anh Nguyễn Tuấn Vũ",
          "Đặng Hải Sơn"
        ]
      },
      {
        title: "Sinh hoạt lớp đầu tiên",
        date: "11/10/2021",
        specificDuration: "Sáng 11/10/2021",
        images: ["/images/freshman/SinhHoatLopDauTien_1.png"],
        description: "Lần đầu gặp cô chủ nhiệm cũ",
        detailedDescription: "Sinh hoạt đầu khóa là một trong những nội dung quan trọng khi mình bước vào môi trường đại học. Vì nó cho mình cái nhìn tổng quan về ngôi trường theo học, giúp mình giải đáp những thắc mắc trong quá trình học. Tuy vậy, thời đấy mình vẫn còn ngây ngô nên cứ treo máy chứ không thực sự tập trung. Cũng bởi thế nên mình đã bỏ phí rất nhiều thời gian để tìm hiểu chúng vào sau này.",
        keyEvents: [
          "Họp lớp",
        ],
        figures: [
          "Cô Trần Minh Hoàn",
          "Tập thể lớp 63CNTT4"
        ]
      },
      {
        title: "Lễ khai giảng năm học 2021-2022",
        date: "11/10/2021",
        specificDuration: "Sáng 11/10/2021",
        images: [
          "/images/freshman/KhaiGiang_1.png", 
          "/images/freshman/KhaiGiang_2.png"
        ],
        description: "Lễ khai giảng năm học đầu tiên tại TLU",
        detailedDescription: "Lễ khai giảng năm học đầu tiên tại TLU là một trong những kỉ niệm đáng nhớ nhất của tớ. Đây là lần đầu tiên tớ được tham dự một buổi lễ khai giảng chính thức và được gặp gỡ các thầy cô, bạn bè mới.",
        keyEvents: [
          "Khai giảng năm học mới",
          "Gặp gỡ các thầy cô, bạn bè mới"
        ],
        figures: [
          "Thầy Nguyễn Trung Việt - Hiệu trưởng",
          "Thầy Nguyễn Thanh Tùng - Nguyên Trưởng khoa CNTT"
        ]
      },
      {
        title: "Buổi học đầu tiên",
        date: "15/10/2021",
        specificDuration: "15/10/2021",
        images: [
          "/images/freshman/BuoiHocDauTien_1.png",
        ],
        description: "Buổi học đầu tiên tại TLU",
        detailedDescription: "Nhập môn lập trình, môn học mở màn cho chuỗi hành trình 4 năm tại TLU",
        keyEvents: [
          "Buổi học đầu tiên",
          "Nhập môn lập trình"
        ],
        figures: [
          "Cô Nguyễn Quỳnh Diệp",
        ]
      },
      {
        title: "Sinh hoạt cùng SIC",
        date: "05/12/2021",
        specificDuration: "05/12/2021",
        images: [
          "/images/freshman/SinhHoatCungSIC_1.jpg",
        ],
        description: "Buổi sinh hoạt cùng câu lạc bộ SIC",
        detailedDescription: "Buổi sinh hoạt đầu tiên cùng câu lạc bộ SIC, nơi mình được làm quen với nhiều bạn mới và tìm hiểu về các hoạt động của câu lạc bộ.",
        keyEvents: [
          "Buổi sinh hoạt cùng SIC"
        ],
        figures: [
          "SIC",
        ]
      },   
      {
        title: "Đợt thi hết môn đầu tiên",
        date: "05/12/2021",
        specificDuration: "05/12/2021",
        images: [
          "/images/freshman/ThiHetMon_1.jpg",
          "/images/freshman/ThiHetMon_2.jpg",
        ],
        description: "Không ngờ lần đầu thi hết môn lại thi bằng hình thức online",
        detailedDescription: "Lần đầu thi cuối kỳ, mình thực sự còn chưa hiểu rõ về cái điểm tổng kết nó tính thế nào, GPA là gì mà cứ thế thi. Kì đầu tiên này, trừ môn Linux ra thì mình nghĩ chắc đứa nào cũng 9+. Riêng cái nhập môn lập trình thì mình làm có mỗi gần 10p, đâm ra các thầy cô giám thị nghĩ mình cheat. Nói chung, đợt đấy vừa học vừa chill.",
        keyEvents: [
          "Thi cuối kỳ"
        ],
        figures: [
          "Himakevolution",
        ]
      },    
      {
        title: "Học bổng đầu tiên",
        date: "05/12/2021",
        specificDuration: "05/12/2021",
        images: [
          "/images/freshman/HocBongKy1.jpg",
        ],
        description: "Học bổng lần đầu tiên tại TLU",
        detailedDescription: "Đợt đấy, cứ nghĩ chỉ cần điểm cao là sẽ được học bổng, còn chưa hiểu điểm rèn luyện thực chất là gì. Tuy nhiên, may mắn là mình có quen 1 anh ở trong SIC. Anh í bảo cứ điền láo lên 90+ đi. Mình nghe theo thật, và điền ",
        keyEvents: [
          "Học bổng"
        ],
        figures: [
          "Himakevolution",
        ]
      },        
    ]
  },
  {
    period: "Năm haiiii ✌️✌️✌️",
    shortTitle: "Thức tỉnh",
    duration: "9/2022 - 8/2023",
    shortDuration: "9/2022 - 8/2023",
    description: "Vấp ngã và đứng dậy",
    events: [
      {
        title: "Một buổi học ĐSTT",
        date: "22/9/2022",
        specificDuration: "22/9/2022",
        images: ["/images/sophomore/DSTT_1.jpg"],
        description: "Buổi gặp mặt đầu tiên sau khi nhập học lại bằng hình thức trực tuyến.",
        detailedDescription: "Sinh hoạt đầu khóa là một trong những nội dung quan trọng khi mình bước vào môi trường đại học. Vì nó cho mình cái nhìn tổng quan về ngôi trường theo học, giúp mình giải đáp những thắc mắc trong quá trình học. Tuy vậy, thời đấy mình vẫn còn ngây ngô nên cứ treo máy chứ không thực sự tập trung. Cũng bởi thế nên mình đã bỏ phí rất nhiều thời gian để tìm hiểu chúng vào sau này.",
        keyEvents: [
          "Giới thiệu về trường",
          "Bài giảng đầu tiên",
        ],
        figures: [
          "Thầy Vũ Nam Phong",
          "Nguyễn Việt Hoàng aka Gấu Bắc Cực - Lớp trưởng",
          "Phan Thị Quỳnh - Bí thư - và là đồng hương"
        ]
      },
      {
        title: "Họp chi bộ CNTT4",
        date: "26/9/2022",
        specificDuration: "26/9/2022",
        images: ["/images/sophomore/HopChiBo_1.jpg"],
        description: "Buổi sinh hoạt chi bộ thường kỳ trực tiếp đầu tiên tại TLU",
        detailedDescription: "Là lễ chào tân duy nhất tớ góp mặt với tư cách người tham dự. Trong 3 lần tổ chức tiếp theo, tớ luôn nằm trong BTC, và mỗi lần là một vai trò khác nhau.",
        keyEvents: [
          "Sinh hoạt chi bộ thường kỳ",
        ],
        figures: [
          "Chi bộ CNTT4",
          "Liên Chi đoàn khoa CNTT"
        ]
      },
      {
        title: "Tổng kết năm học 2021-2022 khối sinh viên",
        date: "15/11/2022",
        specificDuration: "15/11/2022",
        images: [
          "/images/sophomore/TongKetNamHoc_1.jpg"
        ],
        description: "Lần đầu gặp cô chủ nhiệm cũ",
        detailedDescription: "Sinh hoạt đầu khóa là một trong những nội dung quan trọng khi mình bước vào môi trường đại học. Vì nó cho mình cái nhìn tổng quan về ngôi trường theo học, giúp mình giải đáp những thắc mắc trong quá trình học. Tuy vậy, thời đấy mình vẫn còn ngây ngô nên cứ treo máy chứ không thực sự tập trung. Cũng bởi thế nên mình đã bỏ phí rất nhiều thời gian để tìm hiểu chúng vào sau này.",
        keyEvents: [
          "Tổng kết năm học",
          "Học bổng Nguyễn Nhung"
        ],
        figures: [
          "Himakevolution"
        ]
      },
      {
        title: " Olympic Toán Sinh viên lần thứ 29 ",
        date: "04/2023",
        specificDuration: "05-08/04/2023",
        images: [
          "/images/sophomore/ToanHoc_1.jpg", 
          "/images/sophomore/ToanHoc_2.jpg",
          "/images/sophomore/ToanHoc_3.jpg",
        ],
        description: "Đội tuyển Toán TLU",
        detailedDescription: ".",
        keyEvents: [
          "Olympic Toán học ",
        ],
        figures: [
          "Thầy Nguyễn Hữu Thọ",
          "Cô Nguyễn Thị Lý",
          "Anh em đội toán"
        ]
      },
      {
        title: " OLP Triết năm 2023",
        date: "18/05/2023",
        specificDuration: " Tối 18/05/2023",
        images: [
          "/images/sophomore/OLP_Triet_1.png", 
          "/images/sophomore/OLP_Triet_2.png",
          "/images/sophomore/OLP_Triet_3.png",
        ],
        description: "Đội tuyển OLP Triết học",
        detailedDescription: ".",
        keyEvents: [
          "OLP Triết ",
          "Giải Ba "
        ],
        figures: [
          "Đội tuyển….",
        ]
      },
      {
        title: "Sinh hoạt cùng SIC",
        date: "05/12/2021",
        specificDuration: "05/12/2021",
        images: [
          "/images/sophomore/SinhHoatCungSIC_1.jpg",
        ],
        description: "Buổi sinh hoạt cùng câu lạc bộ SIC",
        detailedDescription: "Buổi sinh hoạt đầu tiên cùng câu lạc bộ SIC, nơi mình được làm quen với nhiều bạn mới và tìm hiểu về các hoạt động của câu lạc bộ.",
        keyEvents: [
          "Buổi sinh hoạt cùng SIC"
        ],
        figures: [
          "SIC",
        ]
      },   
    ]
  },
  {
    period: "Năm Ba ",
    shortTitle: "Thức tỉnh",
    duration: "9/2022 - 8/2023",
    shortDuration: "9/2022 - 8/2023",
    description: "Vấp ngã và đứng dậy",
    events: [
      {
        title: "Một buổi học ĐSTT",
        date: "22/9/2022",
        specificDuration: "22/9/2022",
        images: ["/images/sophomore/DSTT_1.jpg"],
        description: "Buổi gặp mặt đầu tiên sau khi nhập học lại bằng hình thức trực tuyến.",
        detailedDescription: "Sinh hoạt đầu khóa là một trong những nội dung quan trọng khi mình bước vào môi trường đại học. Vì nó cho mình cái nhìn tổng quan về ngôi trường theo học, giúp mình giải đáp những thắc mắc trong quá trình học. Tuy vậy, thời đấy mình vẫn còn ngây ngô nên cứ treo máy chứ không thực sự tập trung. Cũng bởi thế nên mình đã bỏ phí rất nhiều thời gian để tìm hiểu chúng vào sau này.",
        keyEvents: [
          "Giới thiệu về trường",
          "Bài giảng đầu tiên",
        ],
        figures: [
          "Thầy Vũ Nam Phong",
          "Nguyễn Việt Hoàng aka Gấu Bắc Cực - Lớp trưởng",
          "Phan Thị Quỳnh - Bí thư - và là đồng hương"
        ]
      },
      {
        title: "Họp chi bộ CNTT4",
        date: "26/9/2022",
        specificDuration: "26/9/2022",
        images: ["/images/sophomore/HopChiBo_1.jpg"],
        description: "Buổi sinh hoạt chi bộ thường kỳ trực tiếp đầu tiên tại TLU",
        detailedDescription: "Là lễ chào tân duy nhất tớ góp mặt với tư cách người tham dự. Trong 3 lần tổ chức tiếp theo, tớ luôn nằm trong BTC, và mỗi lần là một vai trò khác nhau.",
        keyEvents: [
          "Sinh hoạt chi bộ thường kỳ",
        ],
        figures: [
          "Chi bộ CNTT4",
          "Liên Chi đoàn khoa CNTT"
        ]
      },
      {
        title: "Tổng kết năm học 2021-2022 khối sinh viên",
        date: "15/11/2022",
        specificDuration: "15/11/2022",
        images: [
          "/images/sophomore/TongKetNamHoc_1.jpg"
        ],
        description: "Lần đầu gặp cô chủ nhiệm cũ",
        detailedDescription: "Sinh hoạt đầu khóa là một trong những nội dung quan trọng khi mình bước vào môi trường đại học. Vì nó cho mình cái nhìn tổng quan về ngôi trường theo học, giúp mình giải đáp những thắc mắc trong quá trình học. Tuy vậy, thời đấy mình vẫn còn ngây ngô nên cứ treo máy chứ không thực sự tập trung. Cũng bởi thế nên mình đã bỏ phí rất nhiều thời gian để tìm hiểu chúng vào sau này.",
        keyEvents: [
          "Tổng kết năm học",
          "Học bổng Nguyễn Nhung"
        ],
        figures: [
          "Himakevolution"
        ]
      },
      {
        title: " Olympic Toán Sinh viên lần thứ 29 ",
        date: "04/2023",
        specificDuration: "05-08/04/2023",
        images: [
          "/images/sophomore/ToanHoc_1.jpg", 
          "/images/sophomore/ToanHoc_2.jpg",
          "/images/sophomore/ToanHoc_3.jpg",
        ],
        description: "Đội tuyển Toán TLU",
        detailedDescription: ".",
        keyEvents: [
          "Olympic Toán học ",
        ],
        figures: [
          "Thầy Nguyễn Hữu Thọ",
          "Cô Nguyễn Thị Lý",
          "Anh em đội toán"
        ]
      },
      {
        title: " OLP Triết năm 2023",
        date: "18/05/2023",
        specificDuration: " Tối 18/05/2023",
        images: [
          "/images/sophomore/OLP_Triet_1.png", 
          "/images/sophomore/OLP_Triet_2.png",
          "/images/sophomore/OLP_Triet_3.png",
        ],
        description: "Đội tuyển OLP Triết học",
        detailedDescription: ".",
        keyEvents: [
          "OLP Triết ",
          "Giải Ba "
        ],
        figures: [
          "Đội tuyển….",
        ]
      },
      {
        title: "Sinh hoạt cùng SIC",
        date: "05/12/2021",
        specificDuration: "05/12/2021",
        images: [
          "/images/sophomore/SinhHoatCungSIC_1.jpg",
        ],
        description: "Buổi sinh hoạt cùng câu lạc bộ SIC",
        detailedDescription: "Buổi sinh hoạt đầu tiên cùng câu lạc bộ SIC, nơi mình được làm quen với nhiều bạn mới và tìm hiểu về các hoạt động của câu lạc bộ.",
        keyEvents: [
          "Buổi sinh hoạt cùng SIC"
        ],
        figures: [
          "SIC",
        ]
      },   
    ]
  },
  {
    period: "Năm cuối rồi",
    shortTitle: "Cũng đã đến lúc nghẹn ngào",
    duration: "9/2024 - 8/2025",
    shortDuration: "9/2024 - 8/2025",
    description: "Năm của những lần cuối cùng, là thời khắc hạ cánh, Là giây phút tạm biệt, và cũng là lúc mình sẽ viết tiếp một chương mới trong cuộc đời",
    events: [
      {
        title: "Chào tân K66",
        date: "21/9/2024",
        specificDuration: "Sáng 21/9/2024",
        images: [
          "/images/senior/ChaoTanK66_1.png", 
          "/images/senior/ChaoTanK66_2.png",
        ],
        description: "Chào tân sinh K66.",
        detailedDescription: "Lần cuối cùng mình tham gia vào BTC chương trình chào tân, ",
        keyEvents: [
          "Đại hội"
        ],
        figures: [
          "BCH LCĐ Khoa CNTT",
          "4 Phó Bí thư"
        ]
      },   
      {
        title: "CSE Got Talent",
        date: "27/9/2024",
        specificDuration: "27/9/2024",
        images: [
          "/images/senior/CSEGotTalent_1.jpg",
          "/images/senior/CSEGotTalent_2.jpg"
        ],
        description: "Nhóm mình được giải Ba nè olala.",
        detailedDescription: "Bài này chúng mình tự viết trên một nền nhạc có sẵn và quả thật mình rap hay vãi ò. Mình nghe đi nghe lại mấy lần đoạn của bạn Phú.",
        keyEvents: [
          "Chung kết CSE Got Talent",
          "Lời xin lỗi muộn màng",
        ],
        figures: [
          "Thầy Đỗ Lân – Phó trưởng khoa",
          "Vân Đình và Trương Huy Phú"
        ]
      },
      {
        title: "Tổng kết Khoa",
        date: "26/10/2024",
        specificDuration: "26/10/2024",
        images: ["/images/senior/TKKhoa_1.jpg"],
        description: "Buổi tổng kết năm học cuối cùng trong quãng thanh xuân sinh viên của mình.",
        detailedDescription: "Vẫn là cảm xúc khó diễn tả bằng lời vì mình lại được nhận giấy khen như mọi năm.",
        keyEvents: [
          "Tổng kết ",
          "Sinh viên xuất sắc ",
        ],
        figures: [
          "Thầy Đỗ Lân – Phó trưởng khoa",
          "Thầy Võ Tá Hoàng – Bí thư LCĐ",
          "Các bạn sinh viên trong khoa"
        ]
      },
      {
        title: "Đại Hội LCĐ Khoa CNTT 2024-2027",
        date: "15/10/2024",
        specificDuration: "Sáng 15/10/2024",
        images: [
          "/images/senior/DHLCDCNTT_1.jpg",
          "/images/senior/DHLCDCNTT_2.jpg",
          "/images/senior/DHLCDCNTT_3.jpg",
        ],
        description: "Đây có thể là nơi mà mình hạ cánh, tuy nhiên chuyến bay cùng với BCH LCĐ Khoa CNTT vẫn đã tiếp tục.",
        detailedDescription: "Sau Đại hội, mình vẫn là Phó Bí thư, nhưng chỉ đóng vai như một người bạn đồng hành. Những sự kiện sau đấy trong kì 1, mình vẫn thi thoảng hỗ trợ các em. Còn tới kì 2, gần như mọi việc đều do Hà Trang và Lan Anh chịu trách nhiệm chính. Hai đứa làm rất tốt, tốt hơn nhiều so với mình và H.Anh. Nhờ đó, trong khoảng thời gian này, phong trào sinh viên trong khoa có những bước nhảy vọt, sôi nổi hơn, năng lượng hơn. Mong là sau này, mọi người vẫn giữ vững được cái tinh thần ấy.",
        keyEvents: [
          "Đại hội"
        ],
        figures: [
          "BCH LCĐ Khoa CNTT",
          "4 Phó Bí thư"
        ]
      },      
      {
        title: "Đại Hội BCH Đoàn TNCS 24-27",
        date: "15/12/2024",
        specificDuration: "Sáng 15/12/2024",
        images: [
          "/images/senior/DHDoanTruong_1.png", 
          "/images/senior/DHDoanTruong_2.png",
          "/images/senior/DHDoanTruong_3.png",
        ],
        description: "Đại hội Đại biểu BCH Đoàn trường",
        detailedDescription: "Thêm một nhiệm kỳ, nhưng đóng góp của mình chẳng là bao, dù mình là một UV BTV.",
        keyEvents: [
          "Đại hội"
        ],
        figures: [
          "Đoàn Trường",
        ]
      },
      {
        title: " Đêm nhạc hội SS66",
        date: "22/03/2025",
        specificDuration: "Tối 22/03/2025",
        images: [
          "/images/senior/SS66_1.png", 
          "/images/senior/SS66_2.png",
          "/images/senior/SS66_3.png",
        ],
        description: "Đêm nhạc hội SS66 cùng lễ tổng kết tháng thanh niên tại TLU",
        detailedDescription: "Hoàng Yến Chibi tới thăm TLU.",
        keyEvents: [
          "SS66",
          "Tổng kết Đoàn - Tháng Thanh niên"
        ],
        figures: [
          "Hoàng Yến Chibi",
        ]
      },

      {
        title: " Olympic Toán Sinh viên lần thứ 29 ",
        date: "04/2023",
        specificDuration: "05-08/04/2023",
        images: [
          "/images/sophomore/ToanHoc_1.jpg", 
          "/images/sophomore/ToanHoc_2.jpg",
          "/images/sophomore/ToanHoc_3.jpg",
        ],
        description: "Đội tuyển Toán TLU",
        detailedDescription: ".",
        keyEvents: [
          "Olympic Toán học ",
        ],
        figures: [
          "Thầy Nguyễn Hữu Thọ",
          "Cô Nguyễn Thị Lý",
          "Anh em đội toán"
        ]
      },
      {
        title: " OLP Triết năm 2023",
        date: "18/05/2023",
        specificDuration: " Tối 18/05/2023",
        images: [
          "/images/sophomore/OLP_Triet_1.png", 
          "/images/sophomore/OLP_Triet_2.png",
          "/images/sophomore/OLP_Triet_3.png",
        ],
        description: "Đội tuyển OLP Triết học",
        detailedDescription: ".",
        keyEvents: [
          "OLP Triết ",
          "Giải Ba "
        ],
        figures: [
          "Đội tuyển….",
        ]
      },
      {
        title: "CSE Cup 2025",
        date: "05/12/2025",
        specificDuration: "05/12/2025",
        images: [
          "/images/senior/CSECup_1.jpg",
          "/images/senior/CSECup_2.jpg",
        ],
        description: "Tham gia giải bóng đá CSE Cup 2025",
        detailedDescription: "Rút kinh nghiệm từ lần tham gia trước đó, giải này, đội bóng chọn những anh em thường xuyên chinh chiến cùng nhau, xây dựng lối chơi vì tập thể chứ không phụ thuộc cá nhân. Hơn nữa, đội còn được bổ sung 4 cầu chất lượng từ lớp 63TTNT. Khỏi phải nói, mình cảm giác như đây là tinh túy nhất của FC TT5. Vào giải, gặp bảng đấu khá khó nhằn, nhưng thực sự mình thấy cơ hội vào sâu là khá cao. Trận đầu gặp 63CNTT3, á quân mùa giải trước, và lịch sử đấu thì bọn mình cũng chưa bao giờ thắng. Tuy nhiên, như đã nói, ... (dài quá kể sau nhe, tóm lại không có happy ending)",
        keyEvents: [
          "Giải bóng"
        ],
        figures: [
          "63CNTT4",
        ]
      },       
      {
        title: "Bảo vệ đồ án",
        date: "19/07/2025",
        specificDuration: "19/07/2025",
        images: [
          "/images/senior/BVDA_1.jpg",
          "/images/senior/BVDA_2.jpg",
          "/images/senior/BVDA_3.jpg",
          "/images/senior/BVDA_4.jpg",
          "/images/senior/BVDA_5.jpg",
          "/images/senior/BVDA_6.jpg",
          "/images/senior/BVDA_7.jpg",
          "/images/senior/BVDA_8.jpg",
        ],
        description: "Show diễn cuối của Himakevolution tại TLU",
        detailedDescription: "Bảo vệ đồ án, mình muốn điểm A. Ngặt nỗi, đề tài mình chọn lại thuần web, nên càng gần ngày bảo vệ, mình lại càng thấy lo. May thay, hôm nộp quyển, mình được vào hội đồng có vẻ nhẹ nhàng nhất nên lại tự tin hơn trước. Đến ngày bảo vệ, mình có một đội cổ động đông như quân Nguyên, khá chắc là nhất nhì trường đấy. Không khí vui vẻ, mình cực thoải mái, và khi không có một chút áp lực nào thì việc trình bày đối với mình chỉ như cái búng tay. Những câu hỏi phản biện mình đều trả lời suôn sẻ, có điều đôi phần chưa sát với nghiệp vụ thực tế. Nhưng như vậy là đã đủ, mình được 8.8, thành công thực hiện lời hứa đạt A. Thực sự cảm ơn mọi người hôm đó đã tới động viên tinh thần cho mình, dù có một vài thứ mình muốn làm mà chưa làm được.",
        keyEvents: [
          "Bảo vệ đồ án"
        ],
        figures: [
          "Himakevolution",
        ]
      },        
      {
        title: "Tổng kết lớp 63CNTT4",
        date: "05/12/2025",
        specificDuration: "05/12/2025",
        images: [
          "/images/senior/TongKetLop_1.jpg",
          "/images/senior/TongKetLop_2.jpg",
        ],
        description: "Liên hoan tổng kết 4 năm đồng hành với nhau",
        detailedDescription: "4 năm đồng hành cùng nhau, và giờ là lúc để nhìn lại.",
        keyEvents: [
          "Liên hoan"
        ],
        figures: [
          "63CNTT4",
        ]
      },   
    ]
  },
])

// Memories data (giữ nguyên như code gốc, giả sử có sẵn)
const memories = ref([
  {
    id: 1,
    name: 'Thi toán',
    role: 'Huế 2023',
    roleClass: 'Test',
    avatar: 'images/something/GiaiBaOLPToan.jpg',
    description: 'Giải Ba OLP Toán Sinh viên',
    featured: false
  },
  {
    id: 2,
    name: 'Giấy khen',
    role: 'Giấy khen',
    roleClass: 'role-developer',
    avatar: 'images/something/GiayKhenDaiHoiLCDKhoa.jpg',
    description: 'Giấy khen Đại hội Liên Chi đoàn Khoa CNTT nhiệm kỳ 2024-2027',
    featured: false
  },
  {
    id: 3,
    name: 'Bằng khen HSV TP Hà Nội',
    role: 'Bằng khen',
    roleClass: 'role-editor',
    avatar: 'images/something/HSVTPHN2324.jpg',
    description: 'Bằng khen HSV TPHN vì có nhiều đóng góp cho hoạt động hội nhiệm kỳ 2023-2024',
    featured: false
  },
  {
    id: 4,
    name: 'Giấy khen HSV',
    role: 'Giấy khen',
    roleClass: 'role-designer',
    avatar: 'images/something/HSVTruong2123.jpg',
    description: 'Giấy khen HSV Trường vì có nhiều đóng góp nhiệm kỳ 2021-2023',
    featured: false
  },
  {
    id: 5,
    name: 'Có gì',
    role: 'Khen thưởng',
    roleClass: 'role-researcher',
    avatar: 'images/something/SVXuatSacNhatNganh2223.jpg',
    description: 'Sinh viên Xuất sắc nhất ngành năm học 2022-2023',
    featured: false
  },
  {
    id: 6,
    name: 'Gì gì đó',
    role: 'Khen thưởng',
    roleClass: 'role-researcher',
    avatar: 'images/something/SVXuatSacToanDien2122.jpg',
    description: 'Sinh viên Xuất sắc toàn diện năm học 2021-2022',
    featured: false
  },
])

// Functions
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  document.body.style.overflow = isMobileMenuOpen.value ? 'hidden' : 'auto'
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
  document.body.style.overflow = 'auto'
}

const scrollTo = (sectionId) => {
  const element = document.getElementById(sectionId)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' })
  }
}

const scrollToMobile = (sectionId) => {
  closeMobileMenu()
  scrollTo(sectionId)
}

const goToSendWishes = () => {
  router.push('/yearbook')
}

const goToSendWishesMobile = () => {
  closeMobileMenu()
  goToSendWishes()
}

// Typewriter effect function
const typeText = () => {
  displayedTitle.value = ''
  displayedSubtitle.value = ''
  isTypingTitle.value = true
  isTypingSubtitle.value = false

  let titleIndex = 0
  const titleInterval = setInterval(() => {
    if (titleIndex < fullTitle.length) {
      displayedTitle.value += fullTitle[titleIndex]
      titleIndex++
    } else {
      clearInterval(titleInterval)
      isTypingTitle.value = false
      isTypingSubtitle.value = true

      let subtitleIndex = 0
      const subtitleInterval = setInterval(() => {
        if (subtitleIndex < fullSubtitle.length) {
          displayedSubtitle.value += fullSubtitle[subtitleIndex]
          subtitleIndex++
        } else {
          clearInterval(subtitleInterval)
          isTypingSubtitle.value = false
          // Lặp lại sau 15 giây
          setTimeout(typeText, 15000)
        }
      }, 100) // Tốc độ đánh máy cho subtitle
    }
  }, 100) // Tốc độ đánh máy cho title
}

// Timeline functions (giữ nguyên như code gốc)
const getEventNumber = (periodIndex, eventIndex) => {
  let total = 1
  for (let i = 0; i < periodIndex; i++) {
    total += timelinePeriods.value[i].events.length
  }
  return total + eventIndex
}

const viewEventDetails = (event) => {
  selectedEvent.value = event
  eventDetailModalVisible.value = true
}

const scrollToPeriod = (periodIndex) => {
  const header = periodHeaders.value[periodIndex]
  if (header) {
    header.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const handleScroll = () => {
  updateActivePeriod()
}

const updateActivePeriod = () => {
  const scrollTop = document.documentElement.scrollTop
  const windowHeight = window.innerHeight
  const centerPoint = scrollTop + windowHeight / 2
  
  let activePeriodIndex = 0
  let minDistance = Infinity
  
  periodHeaders.value.forEach((header, index) => {
    if (header) {
      const headerRect = header.getBoundingClientRect()
      const headerTop = headerRect.top + scrollTop
      const distance = Math.abs(centerPoint - headerTop)
      
      if (distance < minDistance) {
        minDistance = distance
        activePeriodIndex = index
      }
    }
  })
  
  activePeriod.value = activePeriodIndex
}

const isPeriodInView = (periodIndex) => {
  const header = periodHeaders.value[periodIndex]
  if (!header) return false
  
  const rect = header.getBoundingClientRect()
  const windowHeight = window.innerHeight
  
  return rect.top < windowHeight && rect.bottom > 0
}

const contactMemory = (memory) => {
  message.info(`Liên hệ với ${memory.name} - ${memory.role}`)
}

// Lifecycle hooks
onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  nextTick(() => {
    updateActivePeriod()
  })
  // Bắt đầu hiệu ứng đánh máy
  typeText()
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll)
  document.body.style.overflow = 'auto'
})
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
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar {
  padding: 0.8rem 0;
}

.nav-link {
  color: #333 !important;
  font-weight: 500;
  margin: 0 0.8rem;
  font-size: 0.95rem;
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
  border-radius: 20px;
  padding: 6px 20px;
  font-weight: 500;
  font-size: 0.9rem;
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
}

.send-wishes-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

/* Brand/Logo Styles */
.navbar-brand {
  display: flex;
  align-items: center;
}

.brand-text {
  font-size: 1.3rem;
  font-weight: 700;
  color: #333;
  background: linear-gradient(45deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Mobile Menu Toggle Button */
.mobile-menu-toggle {
  background: none;
  border: none;
  padding: 6px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 35px;
  height: 35px;
  border-radius: 5px;
  transition: all 0.3s ease;
}

.mobile-menu-toggle:hover {
  background: rgba(102, 126, 234, 0.1);
}

.hamburger-line {
  width: 20px;
  height: 2px;
  background: #333;
  margin: 2px 0;
  transition: all 0.3s ease;
  border-radius: 1px;
}

.mobile-menu-toggle.active .hamburger-line:nth-child(1) {
  transform: rotate(45deg) translate(4px, 4px);
}

.mobile-menu-toggle.active .hamburger-line:nth-child(2) {
  opacity: 0;
}

.mobile-menu-toggle.active .hamburger-line:nth-child(3) {
  transform: rotate(-45deg) translate(5px, -5px);
}

/* Mobile Menu Overlay and Content */
.mobile-menu {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  z-index: 9999;
  visibility: hidden;
  opacity: 0;
  transition: all 0.3s ease;
}

.mobile-menu.show {
  visibility: visible;
  opacity: 1;
}

.mobile-menu-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
}

.mobile-menu-content {
  position: absolute;
  top: 0;
  right: 0;
  width: 100%;
  max-width: 300px;
  height: 100%;
  background: white;
  box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
  transform: translateX(100%);
  transition: transform 0.3s ease;
  display: flex;
  flex-direction: column;
}

.mobile-menu.show .mobile-menu-content {
  transform: translateX(0);
}

/* Mobile Menu Header */
.mobile-menu-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  border-bottom: 1px solid #f0f0f0;
  background: linear-gradient(45deg, #667eea, #764ba2);
  color: white;
}

.mobile-brand {
  font-size: 1.1rem;
  font-weight: 700;
}

.mobile-close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1rem;
  cursor: pointer;
  padding: 5px;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.3s ease;
}

.mobile-close-btn:hover {
  background: rgba(255, 255, 255, 0.2);
}

/* Mobile Navigation Links */
.mobile-nav-links {
  flex: 1;
  padding: 15px 0;
  overflow-y: auto;
}

.mobile-nav-link {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  color: #333;
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.3s ease;
  border-bottom: 1px solid #f5f5f5;
}

.mobile-nav-link:hover {
  background: #f8f9fa;
  color: #667eea;
  padding-left: 25px;
}

.mobile-nav-link i {
  margin-right: 10px;
  width: 18px;
  color: #667eea;
  font-size: 0.85rem;
}

/* Mobile Menu Footer */
.mobile-menu-footer {
  padding: 15px;
  border-top: 1px solid #f0f0f0;
  background: #f8f9fa;
}

.mobile-send-wishes-btn {
  width: 100%;
  background: linear-gradient(45deg, #667eea, #764ba2);
  border: none;
  border-radius: 20px;
  padding: 10px 20px;
  font-weight: 600;
  font-size: 0.9rem;
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

.mobile-send-wishes-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

/* Main Content */
.main-content {
  padding-top: 60px;
}

/* Hero Section */
.hero-section {
  padding: 60px 0;
  text-align: center;
  color: white;
  background: linear-gradient(-45deg, #2b8edf 0%, #329acd 28%, #28b6de 67%, #00eeff 100%);
  background-size: 400% 400%;
  animation: gradientShift 15s ease infinite;
}

.hero-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.8rem;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  white-space: pre-wrap;
  word-wrap: break-word;
}

.hero-subtitle {
  font-size: 1rem;
  opacity: 0.9;
  margin-bottom: 1.5rem;
  white-space: pre-wrap;
  word-wrap: break-word;
}

/* Typewriter Cursor Effect */
.typing::after {
  content: '|';
  animation: blink 0.7s step-end infinite;
  margin-left: 2px;
}

@keyframes blink {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0;
  }
}

/* Section Styles */
.section-padding {
  padding: 40px 0;
}

.section-title {
  font-size: 1.8rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 1rem;
  position: relative;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 40px;
  height: 2px;
  background: linear-gradient(45deg, #667eea, #764ba2);
  border-radius: 2px;
}

.section-description {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #666;
  margin-bottom: 0.8rem;
}

/* Introduction Section */
.introduction-section {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
}

.intro-carousel .carousel-item img {
  width: 100%;
  height: 380px;
  object-fit: cover;
  border-radius: 10px;
}

/* Invitation Section */
.invitation-section {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(10px);
}

.invitation-image img {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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

/* Timeline Sidebar */
.timeline-sidebar {
  background: white;
  border-radius: 10px;
  padding: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 80px;
  max-height: calc(100vh - 100px);
  overflow-y: auto;
}

.sidebar-title {
  color: #8B4513;
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 15px;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 8px;
}

.timeline-nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.nav-item {
  padding: 10px;
  background: #f8f9fa;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.nav-item:hover {
  background: #e9ecef;
  transform: translateX(3px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.nav-item.active {
  background: linear-gradient(45deg, #dc3545, #c82333);
  color: white;
  border-color: #dc3545;
  transform: translateX(5px);
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.nav-item.in-view {
  border-color: #dc3545;
  background: rgba(220, 53, 69, 0.1);
}

.nav-item-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.nav-period {
  font-size: 0.7rem;
  font-weight: 600;
  color: #B8860B;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}

.nav-item.active .nav-period {
  color: #FFD700;
}

.nav-title {
  font-size: 0.8rem;
  font-weight: 600;
  color: #333;
  line-height: 1.2;
}

.nav-item.active .nav-title {
  color: white;
}

.nav-duration {
  font-size: 0.65rem;
  color: #666;
  font-style: italic;
}

.nav-item.active .nav-duration {
  color: rgba(255, 255, 255, 0.9);
}

.nav-events-count {
  font-size: 0.6rem;
  color: #999;
  background: rgba(0, 0, 0, 0.05);
  padding: 2px 5px;
  border-radius: 6px;
  text-align: center;
  margin-top: 2px;
}

.nav-item.active .nav-events-count {
  color: rgba(255, 255, 255, 0.8);
  background: rgba(255, 255, 255, 0.2);
}

/* Timeline Main Content */
.timeline-main {
  position: relative;
  padding: 0 15px;
}

.timeline-subtitle {
  color: #666;
  font-size: 1rem;
  font-style: italic;
  margin-bottom: 2rem;
}

.timeline {
  position: relative;
  max-width: 1000px;
  margin: 0 auto;
}

.timeline-line {
  position: absolute;
  left: 50%;
  top: 0;
  bottom: 0;
  width: 3px;
  background: linear-gradient(to bottom, #dc3545, #c82333);
  transform: translateX(-50%);
  border-radius: 2px;
}

/* Timeline Items */
.timeline-item {
  position: relative;
  margin: 40px 0;
  display: flex;
  align-items: center;
  scroll-margin-top: 100px;
}

.timeline-item-left .timeline-content {
  width: calc(50% - 60px);
  margin-right: 10px;
  margin-left: auto;
}

.timeline-item-right .timeline-content {
  width: calc(50% - 60px);
  margin-left: 10px;
  margin-right: auto;
}

.timeline-item-left .timeline-marker,
.timeline-item-right .timeline-marker {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2;
}

.timeline-number {
  position: absolute;
  top: -40px;
  left: 50%;
  transform: translateX(-50%);
  width: 35px;
  height: 35px;
  background: linear-gradient(45deg, #dc3545, #c82333);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-size: 0.9rem;
  box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.9);
}

.timeline-date {
  background: linear-gradient(45deg, #dc3545, #c82333);
  color: white;
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 0.65rem;
  font-weight: 600;
  white-space: nowrap;
  box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
  margin-top: 8px;
}

.timeline-date-left {
  margin-right: 10px;
  text-align: right;
}

.timeline-date-right {
  margin-left: 10px;
  text-align: left;
}

.timeline-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  border: 1px solid #f0f0f0;
  position: relative;
}

.timeline-content:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.timeline-item-left .timeline-content::before {
  content: '';
  position: absolute;
  right: -10px;
  top: 20px;
  width: 0;
  height: 0;
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
  border-left: 10px solid white;
}

.timeline-item-right .timeline-content::before {
  content: '';
  position: absolute;
  left: -10px;
  top: 20px;
  width: 0;
  height: 0;
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
  border-right: 10px solid white;
}

.timeline-title {
  color: #dc3545;
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 8px;
  line-height: 1.3;
}

/* Duration Badge */
.duration-badge {
  display: inline-block;
  background: linear-gradient(45deg, #B8860B, #DAA520);
  color: white;
  padding: 3px 10px;
  border-radius: 10px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-bottom: 10px;
}

.timeline-images img,
.timeline-carousel-item img,
.single-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
}

/* Period Header */
.period-header {
  position: relative;
  margin: 60px 0 40px 0;
  scroll-margin-top: 100px;
  text-align: center;
}

.period-marker {
  position: absolute;
  left: 50%;
  top: -20px;
  transform: translateX(-50%);
}

.period-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(45deg, #B8860B, #DAA520);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
  box-shadow: 0 0 0 4px rgba(255, 255, 255, 1);
  border: 3px solid white;
}

.period-info {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  margin: 0 auto;
  max-width: 600px;
  border: 2px solid #f0f0f0;
}

.period-title {
  color: #B8860B;
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 8px;
  text-transform: uppercase;
}

.period-duration {
  color: #dc3545;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 10px;
  font-style: italic;
}

.period-description {
  color: #555;
  font-size: 0.9rem;
  line-height: 1.5;
}

/* Memories Section */
.memories-section {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
}

.memories-subtitle {
  color: #666;
  font-size: 0.9rem;
  font-style: italic;
  margin-bottom: 2rem;
}

.memories-carousel {
  position: relative;
  margin: 0 30px;
}

.carousel__viewport {
  overflow: hidden;
  border-radius: 10px;
}

.carousel__slide {
  padding: 0 7.5px;
}

.memory-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  border: 2px solid #f0f0f0;
}

.memory-card.featured {
  border-color: #dc3545;
  background: linear-gradient(135deg, #fff, #fff8f8);
}

.memory-avatar {
  position: relative;
  margin-bottom: 15px;
}

.memory-avatar img {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #f0f0f0;
}

.memory-card.featured .memory-avatar img {
  border-color: #dc3545;
}

.featured-badge {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 25px;
  height: 25px;
  background: #dc3545;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.7rem;
  border: 2px solid white;
}

.memory-name {
  font-size: 1.1rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 6px;
}

.memory-role {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-bottom: 10px;
  text-transform: uppercase;
}

.memory-description {
  color: #666;
  line-height: 1.5;
  font-size: 0.85rem;
  min-height: 50px;
}

.contact-btn {
  background: linear-gradient(45deg, #dc3545, #c82333);
  border: none;
  border-radius: 20px;
  padding: 6px 16px;
  font-weight: 600;
  font-size: 0.85rem;
  box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
}

.slider-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: white;
  border: 2px solid #dc3545;
  color: #dc3545;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 2;
}

.slider-nav:hover {
  background: #dc3545;
  color: white;
}

.slider-nav.prev {
  left: -45px;
}

.slider-nav.next {
  right: -45px;
}

.carousel__pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 20px;
}

.carousel__pagination-button {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: none;
  background: #ddd;
  cursor: pointer;
  transition: all 0.3s ease;
}

.carousel__pagination-button--active {
  background: #dc3545;
  transform: scale(1.2);
}

/* Footer */
.footer {
  background: linear-gradient(135deg, #2c3e50, #34495e);
  color: white;
  padding: 40px 0 20px;
}

.footer-title {
  color: white;
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.contact-info p {
  margin-bottom: 0.6rem;
  font-size: 0.9rem;
}

.contact-info i {
  margin-right: 8px;
  width: 18px;
  color: #3498db;
}

.social-links {
  display: flex;
  gap: 12px;
}

.social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  font-size: 1rem;
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
}

.social-link:hover {
  background: #3498db;
  transform: translateY(-2px);
  color: white;
}

.footer-bottom {
  text-align: center;
  margin-top: 20px;
  padding-top: 15px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  font-size: 0.85rem;
}

/* Event Detail Modal */
.event-detail-modal .ant-modal-content {
  border-radius: 10px;
  overflow: hidden;
}

.event-detail-content {
  padding: 10px;
}

.detail-header {
  margin-bottom: 15px;
  padding-bottom: 10px;
}

.detail-date {
  padding: 4px 10px;
  font-size: 0.8rem;
}

.detail-image img {
  max-height: 200px;
}

.detail-description p {
  font-size: 0.9rem;
  line-height: 1.5;
}

.detail-key-events {
  padding: 15px;
}

.detail-key-events h4 {
  font-size: 0.95rem;
}

.detail-key-events li {
  font-size: 0.85rem;
}

.detail-figures h4 {
  font-size: 0.95rem;
}

.detail-figure-tag {
  padding: 6px 12px;
  font-size: 0.8rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .container {
    padding: 0 10px;
  }

  .hero-section {
    padding: 40px 0;
  }

  .hero-title {
    font-size: 1.8rem;
  }

  .hero-subtitle {
    font-size: 0.9rem;
  }

  .section-padding {
    padding: 30px 0;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .section-description {
    font-size: 0.85rem;
  }

  .intro-carousel .carousel-item img {
    height: 150px;
  }

  .invitation-image img {
    height: 200px;
  }

  .timeline-subtitle {
    font-size: 0.9rem;
  }

  .timeline-line {
    left: 20px;
    transform: none;
  }

  .timeline-item-left .timeline-content,
  .timeline-item-right .timeline-content {
    width: calc(100% - 60px);
    margin-left: 60px;
    margin-right: 0;
  }

  .timeline-item-left .timeline-content::before,
  .timeline-item-right .timeline-content::before {
    right: auto;
    left: -10px;
    border-right: 10px solid white;
    border-left: none;
  }

  .timeline-item-left .timeline-marker,
  .timeline-item-right .timeline-marker {
    left: 20px;
    transform: none;
  }

  .timeline-number {
    top: -35px;
    left: 20px;
    transform: none;
    width: 30px;
    height: 30px;
    font-size: 0.8rem;
  }

  .timeline-date {
    font-size: 0.6rem;
    padding: 3px 6px;
    margin-top: 6px;
  }

  .timeline-date-left,
  .timeline-date-right {
    margin-left: 0;
    margin-right: 0;
    text-align: left;
  }

  .timeline-title {
    font-size: 1.1rem;
  }

  .timeline-images img,
  .timeline-carousel-item img,
  .single-image {
    height: 120px;
  }

  .timeline-description {
    font-size: 0.85rem;
  }

  .key-events h4,
  .historical-figures h4 {
    font-size: 0.9rem;
  }

  .key-events li {
    font-size: 0.8rem;
  }

  .figure-tag {
    font-size: 0.75rem;
    padding: 3px 8px;
  }

  .period-header {
    margin: 40px 0 30px 0;
    text-align: left;
  }

  .period-marker {
    left: 20px;
    transform: none;
  }

  .period-icon {
    width: 35px;
    height: 35px;
    font-size: 0.9rem;
  }

  .period-info {
    margin-left: 50px;
    margin-right: 15px;
    padding: 15px;
  }

  .period-title {
    font-size: 1.3rem;
  }

  .period-duration {
    font-size: 0.8rem;
  }

  .period-description {
    font-size: 0.85rem;
  }

  .memories-carousel {
    margin: 0 15px;
  }

  .memory-card {
    padding: 15px;
  }

  .memory-avatar img {
    width: 70px;
    height: 70px;
  }

  .memory-name {
    font-size: 1rem;
  }

  .memory-role {
    font-size: 0.7rem;
    padding: 4px 8px;
  }

  .memory-description {
    font-size: 0.8rem;
    min-height: 40px;
  }

  .contact-btn {
    font-size: 0.8rem;
    padding: 5px 14px;
  }

  .slider-nav {
    width: 30px;
    height: 30px;
  }

  .slider-nav.prev {
    left: -35px;
  }

  .slider-nav.next {
    right: -35px;
  }

  .footer {
    padding: 30px 0 15px;
  }

  .footer-title {
    font-size: 1.1rem;
  }

  .contact-info p {
    font-size: 0.85rem;
  }

  .social-link {
    width: 35px;
    height: 35px;
    font-size: 0.9rem;
  }

  .footer-bottom {
    font-size: 0.8rem;
  }
}
</style>