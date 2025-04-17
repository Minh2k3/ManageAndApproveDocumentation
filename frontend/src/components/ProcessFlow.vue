<template>
    <div class="process-flow position-relative">
      <div class="process-timeline d-none d-md-block"></div>
      
      <div class="process-steps">
        <process-step 
          v-for="(step, index) in processSteps"
          :key="index"
          :number="step.number"
          :title="step.title"
          :description="step.description"
          :position="index % 2 === 0 ? 'left' : 'right'"
        />
      </div>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue';
  import ProcessStep from './ProcessStep.vue';
  
  export default defineComponent({
    name: 'ProcessFlow',
    components: {
      ProcessStep
    },
    data() {
      return {
        processSteps: [
          {
            number: 1,
            title: "Tạo Văn Bản",
            description: "Người dùng tạo văn bản từ mẫu có sẵn hoặc tải lên văn bản mới và thiết lập luồng phê duyệt."
          },
          {
            number: 2,
            title: "Gửi Phê Duyệt",
            description: "Văn bản được gửi đến người phê duyệt đầu tiên trong luồng theo quy trình đã thiết lập."
          },
          {
            number: 3,
            title: "Xem Xét & Phê Duyệt",
            description: "Người phê duyệt xem xét nội dung, có thể phê duyệt, từ chối hoặc yêu cầu chỉnh sửa."
          },
          {
            number: 4,
            title: "Ký Số",
            description: "Sau khi phê duyệt, người có thẩm quyền thực hiện ký số để xác nhận tính pháp lý của văn bản."
          },
          {
            number: 5,
            title: "Hoàn Thành",
            description: "Văn bản được phê duyệt hoàn chỉnh, người tạo nhận thông báo và có thể tải về văn bản đã được ký."
          }
        ]
      };
    }
  });
  </script>
  
  <style scoped>
  .process-flow {
    padding: 20px 0;
  }
  
  .process-timeline {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    height: 100%;
    width: 2px;
    background-color: #e6f7ff;
    z-index: 0;
  }
  
  .process-steps {
    position: relative;
    z-index: 1;
  }
  </style>