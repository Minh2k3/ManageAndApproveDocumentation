<template>
    <div class="card h-100 shadow-sm border-0 transition-hover" style="z-index: 2;">
      <div class="card-body text-center">
        <a-icon :type="icon" class="text-primary mb-3" style="font-size: 36px;" />
        <h3 class="h5 fw-semibold mb-3">{{ title }}</h3>
        <p class="text-muted">{{ description }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue';
  
  export default defineComponent({
    name: 'FeatureCard',
    props: {
      title: {
        type: String,
        required: true
      },
      description: {
        type: String,
        required: true
      },
      icon: {
        type: String,
        required: true
      }
    }
  });
  </script>
  
  <style scoped>
  .transition-hover {
    transition: all 0.3s ease;
    z-index: 2;
  }
  .transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    z-index: 2;
  }
  </style>