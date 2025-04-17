<template>
    <div class="process-step position-relative mb-5">
      <div class="row align-items-center" :class="{'flex-row-reverse': position === 'right'}">
        <div class="col-md-5" :class="position === 'left' ? 'pe-md-5' : 'ps-md-5'">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h3 class="mb-3 d-flex align-items-center fs-3 fw-semibold">
                <a-badge :count="number" :style="{ backgroundColor: '#1890ff' }" class="" />
                {{ title }}
              </h3>
              <p class="text-muted mb-0">{{ description }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-2 d-flex justify-content-center">
          <div class="process-point d-none d-md-block"></div>
        </div>
        <div class="col-md-5"></div>
      </div>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue';
  
  export default defineComponent({
    name: 'ProcessStep',
    props: {
      number: {
        type: Number,
        required: true
      },
      title: {
        type: String,
        required: true
      },
      description: {
        type: String,
        required: true
      },
      position: {
        type: String,
        required: true,
        validator: (value) => ['left', 'right'].includes(value)
      }
    }
  });
  </script>
  
  <style scoped>
  .process-point {
    width: 16px;
    height: 16px;
    background-color: #1890ff;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
  }
  </style>