<template>
  <div class="process-step position-relative mb-5">
    <div class="row align-items-center" :class="{'flex-row-reverse': position === 'right'}">
      <div class="col-md-5" :class="position === 'left' ? 'pe-md-5' : 'ps-md-5'">
        <div class="card border-0 shadow-sm process-card" :class="{'card-left': position === 'left', 'card-right': position === 'right'}">
          <div class="card-body border-primary border border-1 rounded">
            <h3 class="mb-3 d-flex align-items-center fs-3 fw-semibold">
              {{ title }}
            </h3>
            <p class="text-muted mb-0">{{ description }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 d-flex justify-content-center">
        <div class="process-point d-none d-md-block"></div>
        <!-- Connecting lines -->
        <div class="connecting-line  d-none d-md-block" v-if="position === 'left'"></div>
        <div class="connecting-line  d-none d-md-block" v-if="position === 'right'"></div>
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
  position: relative;
  z-index: 3;
  transition: all 0.3s ease;
  box-shadow: 0 0 0 4px rgba(24, 144, 255, 0.1);
}

/* Connecting lines */
.connecting-line {
  position: absolute;
  height: 2px;
  background: linear-gradient(90deg, #1890ff, rgba(24, 144, 255, 0.3));
  top: 50%;
  transform: translateY(-50%);
  z-index: 1;
  transition: all 0.3s ease;
}

.connecting-line-left {
  right: 8px;
  width: calc(50% - 8px);
}

.connecting-line-right {
  left: 8px;
  width: calc(50% - 8px);
}

/* Card styles */
.process-card {
  position: relative;
  transition: all 0.3s ease;
  cursor: pointer;
}

.process-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.process-card:hover .card-body {
  border-color: #1890ff !important;
  background: linear-gradient(135deg, rgba(24, 144, 255, 0.05), rgba(24, 144, 255, 0.02));
}

/* Hover effects for connecting elements */
.process-step:hover .process-point {
  background-color: #40a9ff;
  transform: scale(1.2);
  box-shadow: 0 0 0 6px rgba(24, 144, 255, 0.2);
}

.process-step:hover .connecting-line {
  background: linear-gradient(90deg, #40a9ff, rgba(64, 169, 255, 0.5));
  height: 3px;
}

/* Card title hover effect */
.process-card:hover h3 {
  color: #1890ff;
  transition: color 0.3s ease;
}

/* Text animation on hover */
.process-card:hover p {
  color: #595959 !important;
  transition: color 0.3s ease;
}

/* Pulse animation for point */
@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(24, 144, 255, 0.4);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(24, 144, 255, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(24, 144, 255, 0);
  }
}

.process-step:hover .process-point {
  animation: pulse 2s infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .connecting-line {
    display: none;
  }
  
  .process-card:hover {
    transform: translateY(-3px);
  }
}

/* Additional visual enhancements */
.process-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(24, 144, 255, 0.05), transparent);
  border-radius: 0.375rem;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.process-card:hover::before {
  opacity: 1;
}
</style>