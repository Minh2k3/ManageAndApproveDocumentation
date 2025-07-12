<template>
  <div class="terms-container">
    <div class="container-fluid mt-5">
      <div class="row">
        <!-- Mục lục bên trái (4 cột) với khả năng scroll theo -->
        <div class="col-md-4 toc-column">
          <a-affix :offset-top="20">
            <a-card title="Mục lục" class="toc-card">
              <a-menu
                mode="inline"
                :selectedKeys="[activeSection]"
                style="height: calc(100vh - 150px); overflow-y: auto"
              >
                <a-menu-item v-for="section in sections" :key="section.id" @click="scrollToSection(section.id)">
                  {{ section.title }}
                </a-menu-item>
              </a-menu>
            </a-card>
          </a-affix>
        </div>

        <!-- Nội dung chính bên phải (8 cột) -->
        <div class="col-md-8 content-column">
          <div class="card shadow-sm">
            <div class="card-body">
              <h1 class="mb-4 text-center">Điều khoản dịch vụ</h1>
              <p class="text-muted text-center">Cập nhật lần cuối: {{ currentDate }}</p>
              
              <a-divider />
              
              <!-- Main content -->
              <div class="terms-content">
                <div v-for="section in sections" :key="section.id" :id="section.id" class="mb-5 section">
                    <div v-if="section.id === 'owner-info'">
                    </div>
                    <div v-else>
                        <h2 class="section-title">{{ section.title }}</h2>
                        <div v-html="section.content"></div>
                    </div>
                </div>
              </div>
              
              <!-- Owner information section -->
              <a-divider />
              <div :id="'owner-info'" class="mb-5 section">
                <h2 class="section-title">Thông tin chủ sở hữu</h2>
                <div class="owner-info">
                  <p><strong>Dự án thuộc về:</strong> Trần Tuấn Minh</p>
                  <p><strong>Địa chỉ:</strong> Không cho biết</p>
                  <p><strong>Email liên hệ:</strong> minhhblh31@gmail.com</p>
                  <p><strong>Số điện thoại:</strong> (+84) 369 525 ***</p>
                  <p><strong>Giấy phép kinh doanh:</strong> Số #191024 cấp ngày 19/11/2024</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Footer with user info -->
          <div class="mt-4 text-center text-muted">
            <p>~~~{{ currentUser }}~~~</p>
            <p>{{ currentDate }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Term',
  data() {
    return {
      activeSection: 'section-1',
      currentUser: 'Trần Tuấn Minh',
      currentDate: '2025-07-12 08:13:26',
      sections: [
        {
          id: 'section-1',
          title: '1. Giới thiệu',
          content: `
            <p>Chào mừng bạn đến với ứng dụng của chúng tôi. Những Điều khoản Dịch vụ này ("Điều khoản") quy định việc truy cập và sử dụng trang web, dịch vụ và ứng dụng của chúng tôi (gọi chung là "Dịch vụ"). Bằng việc truy cập hoặc sử dụng Dịch vụ, bạn đồng ý bị ràng buộc bởi các Điều khoản này.</p>
            <p>Vui lòng đọc kỹ các Điều khoản này trước khi sử dụng Dịch vụ của chúng tôi. Nếu bạn không đồng ý với các Điều khoản này, bạn không được phép truy cập hoặc sử dụng Dịch vụ.</p>
          `
        },
        {
          id: 'section-2',
          title: '2. Sử dụng dịch vụ',
          content: `
            <p>Bạn phải tuân thủ mọi chính sách được cung cấp cho bạn trong Dịch vụ.</p>
            <p>Chúng tôi có thể tạm ngừng hoặc ngừng cung cấp Dịch vụ cho bạn nếu bạn không tuân thủ các điều khoản hoặc chính sách của chúng tôi, hoặc nếu chúng tôi đang điều tra hành vi sai phạm đáng nghi ngờ.</p>
            <p>Việc sử dụng Dịch vụ của chúng tôi không cấp cho bạn quyền sở hữu bất kỳ quyền sở hữu trí tuệ nào đối với Dịch vụ của chúng tôi hoặc nội dung bạn truy cập. Bạn không được sử dụng nội dung từ Dịch vụ của chúng tôi trừ khi bạn được chủ sở hữu nội dung đó cho phép hoặc được pháp luật cho phép.</p>
            <a-alert type="warning" show-icon class="my-3">
              <template #message>Không được sử dụng sai Dịch vụ của chúng tôi</template>
              <template #description>
                Bạn chỉ có thể sử dụng Dịch vụ của chúng tôi theo cách được cho phép bởi các Điều khoản này và bất kỳ luật hiện hành nào. Chúng tôi có thể tạm ngừng hoặc ngừng cung cấp Dịch vụ cho bạn nếu bạn không tuân thủ các điều khoản hoặc chính sách của chúng tôi hoặc nếu chúng tôi đang điều tra hành vi sai phạm đáng nghi ngờ.
              </template>
            </a-alert>
          `
        },
        {
          id: 'section-3',
          title: '3. Chính sách bảo mật',
          content: `
            <p>Chính sách Bảo mật của chúng tôi giải thích cách chúng tôi xử lý dữ liệu cá nhân của bạn và bảo vệ quyền riêng tư của bạn khi bạn sử dụng Dịch vụ của chúng tôi. Bằng cách sử dụng Dịch vụ của chúng tôi, bạn đồng ý rằng chúng tôi có thể sử dụng dữ liệu đó theo chính sách bảo mật của chúng tôi.</p>
            <a-collapse class="my-3">
              <a-collapse-panel key="1" header="Thu thập dữ liệu">
                Chúng tôi thu thập một số thông tin nhất định về thiết bị của bạn, hành động duyệt web và các mẫu. Thông tin này có thể bao gồm địa chỉ IP của thiết bị của bạn, loại trình duyệt, phiên bản trình duyệt, hệ điều hành, các trang Dịch vụ của chúng tôi mà bạn truy cập, thời gian và ngày bạn truy cập, thời gian dành cho các trang đó, định danh thiết bị duy nhất và dữ liệu chẩn đoán khác.
              </a-collapse-panel>
              <a-collapse-panel key="2" header="Sử dụng Cookie">
                Chúng tôi sử dụng cookie và các công nghệ theo dõi tương tự để theo dõi hoạt động trên Dịch vụ của chúng tôi và lưu giữ một số thông tin nhất định. Bạn có thể hướng dẫn trình duyệt của mình từ chối tất cả cookie hoặc cho biết khi nào cookie được gửi đi.
              </a-collapse-panel>
            </a-collapse>
          `
        },
        {
          id: 'section-4',
          title: '4. Nội dung trong dịch vụ',
          content: `
            <p>Bạn hiểu rằng tất cả thông tin (như tệp dữ liệu, văn bản viết, phần mềm máy tính, âm nhạc, tệp âm thanh hoặc âm thanh khác, hình ảnh, video hoặc hình ảnh khác) mà bạn có thể truy cập như một phần của, hoặc thông qua việc sử dụng Dịch vụ là trách nhiệm duy nhất của người mà nội dung đó bắt nguồn.</p>
            <div class="my-3">
              <a-steps direction="vertical" current="1">
                <a-step title="Trách nhiệm của bạn" description="Bạn chịu trách nhiệm về nội dung của riêng mình." />
                <a-step title="Giám sát của chúng tôi" description="Chúng tôi có quyền xem xét nội dung để xác định xem nó có bất hợp pháp hoặc vi phạm chính sách của chúng tôi hay không." />
                <a-step title="Quyền gỡ bỏ" description="Chúng tôi có quyền xóa hoặc từ chối hiển thị nội dung mà chúng tôi tin tưởng một cách hợp lý rằng vi phạm chính sách của chúng tôi hoặc pháp luật." />
              </a-steps>
            </div>
          `
        },
        {
          id: 'section-5',
          title: '5. Thay đổi và chấm dứt dịch vụ',
          content: `
            <p>Chúng tôi liên tục thay đổi và cải thiện Dịch vụ của mình. Chúng tôi có thể thêm hoặc xóa chức năng hoặc tính năng, và chúng tôi có thể tạm ngừng hoặc dừng Dịch vụ hoàn toàn.</p>
            <p>Bạn có thể ngừng sử dụng Dịch vụ của chúng tôi bất cứ lúc nào. Chúng tôi cũng có thể ngừng cung cấp Dịch vụ cho bạn, hoặc thêm hoặc tạo giới hạn mới cho Dịch vụ của chúng tôi bất cứ lúc nào.</p>
            <a-timeline class="my-3">
              <a-timeline-item>Chúng tôi cung cấp thông báo về các sửa đổi khi luật pháp yêu cầu</a-timeline-item>
              <a-timeline-item>Việc bạn tiếp tục sử dụng Dịch vụ sau khi thay đổi có nghĩa là bạn chấp nhận các điều khoản mới</a-timeline-item>
              <a-timeline-item>Chúng tôi có thể chấm dứt tài khoản vi phạm chính sách của chúng tôi</a-timeline-item>
            </a-timeline>
          `
        },
        {
          id: 'section-6',
          title: '6. Bảo đảm và tuyên bố từ chối trách nhiệm',
          content: `
            <p>Chúng tôi cung cấp Dịch vụ của mình bằng cách sử dụng mức độ kỹ năng và chăm sóc hợp lý về mặt thương mại. Nhưng có những điều nhất định mà chúng tôi không hứa hẹn về Dịch vụ của mình:</p>
            <div class="my-3">
              <a-alert type="info" class="mb-2">
                <template #message>DỊCH VỤ ĐƯỢC CUNG CẤP "NGUYÊN TRẠNG"</template>
                <template #description>
                  DỊCH VỤ ĐƯỢC CUNG CẤP "NGUYÊN TRẠNG" MÀ KHÔNG CÓ BẤT KỲ BẢO ĐẢM NÀO, DÙ LÀ RÕ RÀNG HAY NGỤ Ý.
                </template>
              </a-alert>
              <a-alert type="info" class="mb-2">
                <template #message>KHÔNG BẢO HÀNH</template>
                <template #description>
                  CHÚNG TÔI KHÔNG ĐẢM BẢO RẰNG DỊCH VỤ SẼ KHÔNG BỊ GIÁN ĐOẠN, KỊP THỜI, AN TOÀN HOẶC KHÔNG CÓ LỖI.
                </template>
              </a-alert>
              <a-alert type="info">
                <template #message>KHÔNG CHỊU TRÁCH NHIỆM</template>
                <template #description>
                  CHÚNG TÔI SẼ KHÔNG CHỊU TRÁCH NHIỆM CHO BẤT KỲ THIỆT HẠI GIÁN TIẾP, NGẪU NHIÊN, ĐẶC BIỆT, DO HẬU QUẢ HOẶC MANG TÍNH TRỪNG PHẠT NÀO.
                </template>
              </a-alert>
            </div>
          `
        },
        {
          id: 'section-7',
          title: '7. Luật áp dụng',
          content: `
            <p>Các Điều khoản này sẽ được chi phối và giải thích theo luật pháp Việt Nam, không xét đến các quy định về xung đột pháp luật của nó.</p>
            <p>Việc chúng tôi không thực thi bất kỳ quyền hoặc điều khoản nào trong các Điều khoản này sẽ không được coi là từ bỏ các quyền đó. Nếu bất kỳ điều khoản nào của các Điều khoản này bị tòa án cho là không hợp lệ hoặc không thể thực thi, các điều khoản còn lại của các Điều khoản này sẽ vẫn có hiệu lực.</p>
            <a-card class="my-3 border-primary">
              <p class="mb-0">Đối với bất kỳ câu hỏi nào về các Điều khoản này, vui lòng liên hệ với chúng tôi tại:</p>
              <p class="mb-0 fw-bold">minhhblh31@gmail.com</p>
            </a-card>
          `
        }
      ]
    };
  },
  mounted() {
    // Thêm phần thông tin chủ sở hữu vào danh sách sections cho menu
    this.sections.push({
      id: 'owner-info',
      title: 'Thông tin chủ sở hữu'
    });
    
    // Thiết lập sự kiện cuộn để cập nhật menu active
    window.addEventListener('scroll', this.handleScroll);
  },
  beforeDestroy() {
    // Dọn dẹp sự kiện khi component bị hủy
    window.removeEventListener('scroll', this.handleScroll);
  },
  methods: {
    scrollToSection(sectionId) {
      const element = document.getElementById(sectionId);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        this.activeSection = sectionId;
      }
    },
    handleScroll() {
      // Xác định section đang hiển thị và cập nhật active section
      const sections = document.querySelectorAll('.section');
      let currentSectionId = this.activeSection;
      
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const scrollY = window.scrollY;
        
        if (scrollY >= sectionTop - 200 && scrollY < sectionTop + sectionHeight - 200) {
          currentSectionId = section.id;
        }
      });
      
      if (currentSectionId !== this.activeSection) {
        this.activeSection = currentSectionId;
      }
    }
  }
};
</script>

<style scoped>
.terms-container {
  padding-bottom: 50px;
}

/* Styles cho layout cột */
.toc-column {
  position: relative;
}

.toc-card {
  width: 100%;
}

.content-column {
  margin-bottom: 2rem;
}

/* Styles cho nội dung */
.section-title {
  margin-bottom: 1.5rem;
  color: #1890ff;
  border-bottom: 1px solid #f0f0f0;
  padding-bottom: 0.5rem;
}

.owner-info p {
  margin-bottom: 0.5rem;
}

.section {
  scroll-margin-top: 20px;
}

/* Responsive styles */
@media (max-width: 768px) {
  .toc-column {
    margin-bottom: 2rem;
  }
  
  .card-body {
    padding: 1rem;
  }
}
</style>