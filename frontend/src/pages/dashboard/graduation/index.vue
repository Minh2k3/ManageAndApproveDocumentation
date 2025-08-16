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
            <a class="nav-link" href="#ky-niem" @click="scrollTo('ky-niem')">Kỷ niệm</a>  
            <a class="nav-link" href="#lien-he" @click="scrollTo('lien-he')">Liên hệ</a>
          </div>
          <button type="primary" class="send-wishes-btn text-white" @click="goToSendWishes">
            Gửi lời chúc
          </button>
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
                      :class="{ 'timeline-item-right': eventIndex % 2 === 0, 'timeline-item-left': eventIndex % 2 === 1 }"
                      :ref="el => { if (el) timelineItems.push(el) }"
                    >
                      
                      <!-- Timeline Date and Number -->
                      <div class="timeline-marker">
                        <div class="timeline-date" :class="{ 'timeline-date-left': eventIndex % 2 === 0, 'timeline-date-right': eventIndex % 2 === 1 }">
                          {{ event.date }}
                        </div>
                        <div class="timeline-number">{{ getEventNumber(periodIndex, eventIndex) }}</div>
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
                              <img :src="image" :alt="`${event.title} ${imgIndex + 1}`" />
                            </div>
                          </a-carousel>
                          <img v-else :src="event.images[0]" :alt="event.title" class="single-image" />
                        </div>
                        
                        <p class="timeline-description">{{ event.description }}</p>
                        
                        <!-- Key Events -->
                        <div v-if="event.keyEvents && event.keyEvents.length > 0" class="key-events">
                          <h4><i class="fas fa-star"></i> Điểm nổi bật:</h4>
                          <ul>
                            <li v-for="keyEvent in event.keyEvents" :key="keyEvent">{{ keyEvent }}</li>
                          </ul>
                        </div>
                        
                        <!-- Historical Figures -->
                        <div v-if="event.figures && event.figures.length > 0" class="historical-figures">
                          <h4><i class="fas fa-crown"></i> Nhân vật liên quan:</h4>
                          <div class="figures-list">
                            <span v-for="figure in event.figures" :key="figure" class="figure-tag">{{ figure }}</span>
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
          
          <!-- Memories Slider -->
          <div class="memories-slider">
            <div class="slider-container" ref="sliderContainer">
              <div class="slider-track" :style="{ transform: `translateX(-${currentSlide * slideWidth}px)` }">
                <div 
                  v-for="(memory, index) in lastSlides" 
                  :key="`duplicate-end-${index}`"
                  class="memory-card"
                  :class="{ 'featured': memory.featured }"
                >
                  <div class="memory-avatar">
                    <img :src="memory.avatar" :alt="memory.name" />
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

                <!-- Original slides -->
                <div 
                  v-for="(memory, index) in memories" 
                  :key="`original-${index}`"
                  class="memory-card"
                  :class="{ 'featured': memory.featured }"
                >
                  <div class="memory-avatar">
                    <img :src="memory.avatar" :alt="memory.name" />
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

                <!-- Duplicate slides đầu vào cuối (để scroll từ phải sang trái) -->
                <div 
                  v-for="(memory, index) in firstSlides" 
                  :key="`duplicate-start-${index}`"
                  class="memory-card"
                  :class="{ 'featured': memory.featured }"
                >
                  <div class="memory-avatar">
                    <img :src="memory.avatar" :alt="memory.name" />
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
              </div>
            </div>
            
            <!-- Navigation Arrows -->
            <button 
              class="slider-nav prev" 
              @click="prevSlide"
            >
              <i class="fas fa-chevron-left"></i>
            </button>
            <button 
              class="slider-nav next" 
              @click="nextSlide"
            >
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          
          <!-- Slider Indicators -->
          <div class="slider-indicators">
            <button 
              v-for="(indicator, index) in Math.ceil(memories.length / slidesPerView)" 
              :key="index"
              class="indicator"
              :class="{ active: index === getCurrentIndicatorIndex() }"
              @click="goToSlide(index * slidesPerView)"
            ></button>
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
      width="900px"
      :footer="null"
      class="event-detail-modal"
    >
      <div v-if="selectedEvent" class="event-detail-content">
        <div class="detail-header">
          <span class="detail-date">{{ selectedEvent.date }}</span>
        </div>
        
        <div class="detail-image" v-if="selectedEvent.images && selectedEvent.images.length > 0">
          <a-carousel autoplay>
            <div v-for="(image, imgIndex) in selectedEvent.images" :key="imgIndex">
              <img :src="image" :alt="`${selectedEvent.title} ${imgIndex + 1}`" />
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
            <span v-for="figure in selectedEvent.figures" :key="figure" class="detail-figure-tag">
              {{ figure }}
            </span>
          </div>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import { message } from 'ant-design-vue'
import 'vue3-carousel/carousel.css'

// Router
const router = useRouter()

// Reactive data
const eventDetailModalVisible = ref(false)
const selectedEvent = ref(null)
const activePeriod = ref(0)
const periodHeaders = ref([])
const timelineItems = ref([])

// Slider data
const currentSlide = ref(0)
const slideWidth = ref(400)
const slidesPerView = ref(3)
const isTransitioning = ref(false)
const sliderContainer = ref(null)

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
          "Thầy Nguyễn Thanh Tùng - Nguyên Trưởng khoa CNTT",
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
        date: "15/10/2021",
        specificDuration: "Sáng 15/10/2021",
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
          "Cô Nguyễn Quỳnh Diệp - Trưởng Bộ môn Tin học và KTTT",
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
          "/images/sophomore/ToanHoc_1.png", 
          "/images/sophomore/ToanHoc_2.png",
          "/images/sophomore/ToanHoc_3.png",
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
])

// Memories data
const memories = ref([
  {
    id: 1,
    name: 'Hoàng Cường Thịnh',
    role: 'Lập trình viên',
    roleClass: 'role-developer',
    avatar: 'https://picsum.photos/200/200?random=1',
    description: 'Hỗ trợ xây dựng website để đưa vào hoạt động',
    featured: false
  },
  {
    id: 2,
    name: 'Tạ Thanh An',
    role: 'Lập trình viên',
    roleClass: 'role-developer',
    avatar: 'https://picsum.photos/200/200?random=2',
    description: 'Tôi xây dựng website với mục đích mang lại cho người dùng dòng chảy lịch sử Việt Nam xuyên suốt, dễ dàng tìm kiếm, tìm hiểu!',
    featured: false
  },
  {
    id: 3,
    name: 'Trần Yến Vy',
    role: 'Biên soạn',
    roleClass: 'role-editor',
    avatar: 'https://picsum.photos/200/200?random=3',
    description: 'Tinh nguyện viên hỗ trợ biên soạn đóng góp nội dung cho website',
    featured: false
  },
  {
    id: 4,
    name: 'Nguyễn Minh Đức',
    role: 'Thiết kế',
    roleClass: 'role-designer',
    avatar: 'https://picsum.photos/200/200?random=4',
    description: 'Thiết kế giao diện và trải nghiệm người dùng cho trang web lịch sử',
    featured: false
  },
  {
    id: 5,
    name: 'Lê Thị Hương',
    role: 'Nghiên cứu',
    roleClass: 'role-researcher',
    avatar: 'https://picsum.photos/200/200?random=5',
    description: 'Nghiên cứu và sưu tầm tài liệu lịch sử Việt Nam',
    featured: false
  }
])

// Computed properties
const maxSlide = computed(() => memories.value.length - 1)
const indicators = computed(() => Math.ceil(memories.value.length / slidesPerView.value))

const firstSlides = computed(() => {
  const count = slidesPerView.value
  return memories.value.slice(0, count)
})

const lastSlides = computed(() => {
  const count = slidesPerView.value
  return memories.value.slice(-count)
})

const slideOffset = computed(() => slidesPerView.value)

// Methods
const scrollTo = (elementId) => {
  const element = document.getElementById(elementId)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' })
  }
}

const goToSendWishes = () => {
  router.push('/graduation-yearbook')
}

const viewEventDetails = (event) => {
  selectedEvent.value = event
  eventDetailModalVisible.value = true
}

const scrollToPeriod = (periodIndex) => {
  console.log('Scrolling to period:', periodIndex)
  
  let element = periodHeaders.value[periodIndex]
  
  if (!element) {
    element = document.getElementById(`period-header-${periodIndex}`)
  }
  
  if (!element) {
    console.error('Period header element not found for index:', periodIndex)
    return
  }
  
  console.log('Found element:', element)
  
  const headerHeight = 80
  const extraOffset = 50
  
  const elementRect = element.getBoundingClientRect()
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop
  const elementTop = elementRect.top + scrollTop
  
  const targetPosition = elementTop - headerHeight - extraOffset
  
  console.log('Scrolling to position:', targetPosition)
  
  window.scrollTo({
    top: Math.max(0, targetPosition),
    behavior: 'smooth'
  })
  
  activePeriod.value = periodIndex
}

const getEventNumber = (periodIndex, eventIndex) => {
  let number = 1
  
  for (let i = 0; i < periodIndex; i++) {
    number += timelinePeriods.value[i].events.length
  }
  
  number += eventIndex
  
  return number
}

const handleScroll = () => {
  updateActivePeriod()
}

const updateActivePeriod = () => {
  if (periodHeaders.value.length === 0) return
  
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop
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

// Slider methods với infinite loop
const nextSlide = () => {
  if (isTransitioning.value) return
  
  isTransitioning.value = true
  currentSlide.value++
  
  setTimeout(() => {
    if (currentSlide.value > slideOffset.value + maxSlide.value) {
      disableTransition()
      currentSlide.value = slideOffset.value
      
      nextTick(() => {
        enableTransition()
      })
    }
    isTransitioning.value = false
  }, 500)
}

const prevSlide = () => {
  if (isTransitioning.value) return
  
  isTransitioning.value = true
  currentSlide.value--
  
  setTimeout(() => {
    if (currentSlide.value < slideOffset.value) {
      disableTransition()
      currentSlide.value = slideOffset.value + maxSlide.value
      
      nextTick(() => {
        enableTransition()
      })
    }
    isTransitioning.value = false
  }, 500)
}

const disableTransition = () => {
  const sliderTrack = document.querySelector('.slider-track')
  if (sliderTrack) {
    sliderTrack.style.transition = 'none'
  }
}

const enableTransition = () => {
  const sliderTrack = document.querySelector('.slider-track')
  if (sliderTrack) {
    sliderTrack.style.transition = 'transform 0.5s ease'
  }
}

const goToSlide = (slideIndex) => {
  if (isTransitioning.value) return
  currentSlide.value = slideOffset.value + slideIndex
}

const updateSlideWidth = () => {
  if (sliderContainer.value) {
    const containerWidth = sliderContainer.value.offsetWidth
    if (window.innerWidth <= 768) {
      slidesPerView.value = 1
      slideWidth.value = containerWidth
    } else if (window.innerWidth <= 1024) {
      slidesPerView.value = 2
      slideWidth.value = containerWidth / 2
    } else {
      slidesPerView.value = 3
      slideWidth.value = containerWidth / 3
    }
    
    currentSlide.value = slideOffset.value
  }
}

const getCurrentIndicatorIndex = () => {
  const realIndex = (currentSlide.value - slideOffset.value) % memories.value.length
  return Math.floor(Math.max(0, realIndex) / slidesPerView.value)
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
  updateSlideWidth()
  window.addEventListener('resize', updateSlideWidth)
  
  nextTick(() => {
    currentSlide.value = slideOffset.value
  })
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', updateSlideWidth)
})
</script>

<style scoped>
/* All previous styles remain the same, just adding scroll-margin-top for better scroll positioning */
/* Memories Section */
.memories-section {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  position: relative;
}

.memories-subtitle {
  color: #666;
  font-size: 1.1rem;
  font-style: italic;
  margin-bottom: 3rem;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
}

/* Memories Slider */
.memories-slider {
  position: relative;
  overflow: hidden;
  margin: 0 60px;
}

.slider-container {
  overflow: hidden;
  border-radius: 15px;
}

.slider-track {
  display: flex;
  transition: transform 0.5s ease;
  gap: 20px;
}

.slider-track.no-transition {
  transition: none !important;
}

.memory-card {
  flex: 0 0 calc(33.333% - 14px);
  background: white;
  border-radius: 20px;
  padding: 30px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  border: 2px solid #f0f0f0;
  position: relative;
}

.memory-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.memory-card.featured {
  border-color: #dc3545;
  background: linear-gradient(135deg, #fff, #fff8f8);
}

.memory-card.featured::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(45deg, #dc3545, #c82333);
  border-radius: 20px 20px 0 0;
}

/* Memory Avatar */
.memory-avatar {
  position: relative;
  margin-bottom: 20px;
  display: inline-block;
}

.memory-avatar img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #f0f0f0;
  transition: all 0.3s ease;
}

.memory-card.featured .memory-avatar img {
  border-color: #dc3545;
  box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.2);
}

.featured-badge {
  position: absolute;
  bottom: 5px;
  right: 5px;
  width: 30px;
  height: 30px;
  background: #dc3545;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.8rem;
  border: 3px solid white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

/* Memory Content */
.memory-name {
  font-size: 1.4rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 8px;
}

.memory-role {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 15px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.role-developer {
  background: linear-gradient(45deg, #007bff, #0056b3);
  color: white;
}

.role-editor {
  background: linear-gradient(45deg, #28a745, #1e7e34);
  color: white;
}

.role-designer {
  background: linear-gradient(45deg, #ff6b9d, #c44569);
  color: white;
}

.role-researcher {
  background: linear-gradient(45deg, #6f42c1, #5a2d91);
  color: white;
}

.memory-description {
  color: #666;
  line-height: 1.6;
  margin-bottom: 20px;
  font-size: 0.95rem;
  min-height: 60px;
}

.contact-btn {
  background: linear-gradient(45deg, #dc3545, #c82333);
  border: none;
  border-radius: 25px;
  padding: 8px 20px;
  font-weight: 600;
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
  transition: all 0.3s ease;
}

.contact-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

/* Navigation Arrows */
.slider-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: white;
  border: 2px solid #dc3545;
  color: #dc3545;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 2;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.slider-nav:hover {
  background: #dc3545;
  color: white;
  transform: translateY(-50%) scale(1.1);
}

/* .slider-nav:disabled {
  opacity: 0.5;
  cursor: not-allowed;
} */

.slider-nav.prev {
  left: 10px;
}

.slider-nav.next {
  right: 10px;
}

/* Slider Indicators */
.slider-indicators {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 30px;
}

.indicator {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: none;
  background: #ddd;
  cursor: pointer;
  transition: all 0.3s ease;
}

.indicator.active {
  background: #dc3545;
  transform: scale(1.2);
}

.indicator:hover {
  background: #c82333;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .memory-card {
    flex: 0 0 calc(50% - 10px);
  }
  
  .memories-slider {
    margin: 0 40px;
  }
}

@media (max-width: 768px) {
  .memory-card {
    flex: 0 0 100%;
  }
  
  .memories-slider {
    margin: 0 20px;
  }
  
  .memory-avatar img {
    width: 100px;
    height: 100px;
  }
  
  .memory-name {
    font-size: 1.2rem;
  }
  
  .memory-description {
    min-height: auto;
  }
  
  .slider-nav {
    width: 40px;
    height: 40px;
  }
  
  .slider-nav.prev {
    left: 5px;
  }
  
  .slider-nav.next {
    right: 5px;
  }
}

@media (max-width: 576px) {
  .memories-subtitle {
    font-size: 1rem;
  }
  
  .memory-card {
    padding: 20px;
  }
  
  .memory-avatar img {
    width: 80px;
    height: 80px;
  }
  
  .memory-name {
    font-size: 1.1rem;
  }
  
  .contact-btn {
    font-size: 0.9rem;
    padding: 6px 16px;
  }
}
/* Period Header Enhancement */
.period-header {
  position: relative;
  margin: 100px 0 60px 0;
  scroll-margin-top: 150px;
  text-align: center;
}

.period-marker {
  position: absolute;
  left: 50%;
  top: -40px; /* Dịch lên trên 40px thay vì top: 0 */
  transform: translateX(-50%);
  z-index: 3;
}

.period-icon {
  width: 60px;
  height: 60px;
  background: linear-gradient(45deg, #B8860B, #DAA520);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  box-shadow: 0 0 0 6px rgba(255, 255, 255, 1), 0 6px 20px rgba(184, 134, 11, 0.3);
  border: 4px solid white;
}

.period-info {
  background: white;
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  margin-top: 10px; /* Giảm margin-top từ 30px xuống 10px */
  border: 2px solid #f0f0f0;
  position: relative;
  z-index: 1; /* Đảm bảo nội dung không bị che */
}

.period-title {
  color: #B8860B;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.period-duration {
  color: #dc3545;
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 15px;
  font-style: italic;
}

.period-description {
  color: #555;
  font-size: 1rem;
  line-height: 1.6;
  margin: 0;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
  .period-marker {
    left: 30px;
    top: -30px; /* Điều chỉnh cho mobile */
  }
  
  .period-icon {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
  }
  
  .period-info {
    margin-left: 70px;
    margin-top: 5px;
  }
  
  .period-title {
    font-size: 1.5rem;
  }
}

@media (max-width: 576px) {
  .period-marker {
    top: -25px;
  }
  
  .period-title {
    font-size: 1.3rem;
  }
}

/* Tất cả CSS khác giữ nguyên... */
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

/* Timeline Sidebar */
.timeline-sidebar {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 120px;
  max-height: calc(100vh - 140px);
  overflow-y: auto;
}

.sidebar-title {
  color: #8B4513;
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 10px;
}

.timeline-nav {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.nav-item {
  padding: 15px;
  background: #f8f9fa;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  position: relative;
}

.nav-item:hover {
  background: #e9ecef;
  transform: translateX(5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.nav-item.active {
  background: linear-gradient(45deg, #dc3545, #c82333);
  color: white;
  border-color: #dc3545;
  transform: translateX(8px);
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
}

.nav-item.in-view {
  border-color: #dc3545;
  background: rgba(220, 53, 69, 0.1);
}

.nav-item-content {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.nav-period {
  font-size: 0.8rem;
  font-weight: 600;
  color: #B8860B;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.nav-item.active .nav-period {
  color: #FFD700;
}

.nav-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: #333;
  line-height: 1.3;
}

.nav-item.active .nav-title {
  color: white;
}

.nav-duration {
  font-size: 0.75rem;
  color: #666;
  font-style: italic;
}

.nav-item.active .nav-duration {
  color: rgba(255, 255, 255, 0.9);
}

.nav-events-count {
  font-size: 0.7rem;
  color: #999;
  background: rgba(0, 0, 0, 0.05);
  padding: 2px 6px;
  border-radius: 8px;
  text-align: center;
  margin-top: 3px;
}

.nav-item.active .nav-events-count {
  color: rgba(255, 255, 255, 0.8);
  background: rgba(255, 255, 255, 0.2);
}

/* Timeline Main Content */
.timeline-main {
  position: relative;
  padding-left: 20px;
}

.timeline-subtitle {
  color: #666;
  font-size: 1.2rem;
  font-style: italic;
  margin-bottom: 3rem;
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
  background: linear-gradient(to bottom, #dc3545, #c82333);
  transform: translateX(-50%);
  border-radius: 2px;
}

/* Timeline Items */
.timeline-item {
  position: relative;
  margin: 80px 0;
  display: flex;
  align-items: center;
  scroll-margin-top: 150px;
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
  background: linear-gradient(45deg, #dc3545, #c82333);
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
  background: linear-gradient(45deg, #dc3545, #c82333);
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
  white-space: nowrap;
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
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
  border: 1px solid #f0f0f0;
}

.timeline-content:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
  color: #dc3545;
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 10px;
  line-height: 1.3;
}

/* Duration Badge */
.duration-badge {
  display: inline-block;
  background: linear-gradient(45deg, #B8860B, #DAA520);
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 15px;
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
  color: #444;
  line-height: 1.7;
  margin-bottom: 20px;
  font-size: 0.95rem;
}

/* Key Events */
.key-events {
  margin: 20px 0;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
  border-left: 4px solid #dc3545;
}

.key-events h4 {
  color: #dc3545;
  font-size: 1rem;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.key-events ul {
  margin: 0;
  padding-left: 20px;
}

.key-events li {
  color: #555;
  margin-bottom: 5px;
  line-height: 1.5;
}

/* Historical Figures */
.historical-figures {
  margin: 20px 0;
}

.historical-figures h4 {
  color: #dc3545;
  font-size: 1rem;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.figures-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.figure-tag {
  background: linear-gradient(45deg, #dc3545, #c82333);
  color: white;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 500;
}

/* Event Actions */
.event-actions {
  margin-top: 20px;
  padding-top: 15px;
  border-top: 1px solid #f0f0f0;
}

.view-details-btn {
  color: #dc3545;
  font-weight: 600;
  padding: 0;
  height: auto;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.view-details-btn:hover {
  color: #c82333;
  transform: translateX(5px);
}

/* Event Detail Modal */
.event-detail-modal .ant-modal-content {
  border-radius: 15px;
  overflow: hidden;
}

.event-detail-content {
  padding: 10px;
}

.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
  flex-wrap: wrap;
  gap: 10px;
}

.detail-date {
  background: linear-gradient(45deg, #dc3545, #c82333);
  color: white;
  padding: 5px 12px;
  border-radius: 15px;
  font-size: 0.9rem;
  font-weight: 600;
}

.detail-image {
  margin: 20px 0;
  border-radius: 10px;
  overflow: hidden;
}

.detail-image img {
  width: 100%;
  height: auto;
  max-height: 300px;
  object-fit: cover;
}

.detail-description p {
  color: #444;
  line-height: 1.7;
  margin-bottom: 15px;
}

.detail-key-events {
  margin: 25px 0;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 10px;
  border-left: 4px solid #dc3545;
}

.detail-key-events h4 {
  color: #dc3545;
  font-size: 1.1rem;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.detail-key-events ul {
  margin: 0;
  padding-left: 20px;
}

.detail-key-events li {
  color: #555;
  margin-bottom: 8px;
  line-height: 1.6;
}

.detail-figures h4 {
  color: #dc3545;
  font-size: 1.1rem;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.figures-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.detail-figure-tag {
  background: linear-gradient(45deg, #dc3545, #c82333);
  color: white;
  padding: 8px 15px;
  border-radius: 15px;
  font-size: 0.9rem;
  font-weight: 500;
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
@media (max-width: 1199px) {
  .timeline-sidebar {
    position: relative;
    top: 0;
    margin-bottom: 30px;
    max-height: none;
  }
  
  .timeline-nav {
    flex-direction: row;
    overflow-x: auto;
    gap: 15px;
    padding-bottom: 10px;
  }
  
  .nav-item {
    min-width: 200px;
    flex-shrink: 0;
  }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .period-title {
    font-size: 1.5rem;
  }
  
  .period-info {
    padding: 20px;
  }
  
  /* Mobile Timeline */
  .timeline-line {
    left: 30px;
  }
  
  .timeline-marker {
    left: 30px;
  }
  
  .period-marker {
    left: 30px;
  }
  
  .timeline-number {
    width: 40px;
    height: 40px;
    font-size: 1rem;
  }
  
  .period-icon {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
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
  
  .period-info {
    margin-left: 70px;
    margin-top: 15px;
  }
  
  .timeline-title {
    font-size: 1.3rem;
  }
  
  .timeline-images img,
  .timeline-carousel-item img,
  .single-image {
    height: 150px;
  }
  
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
  
  .period-title {
    font-size: 1.3rem;
  }
  
  .nav-item {
    min-width: 160px;
  }
  
  .nav-title {
    font-size: 0.8rem;
  }
  
  .nav-duration {
    font-size: 0.7rem;
  }
  
  .nav-events-count {
    font-size: 0.65rem;
  }
}
</style>