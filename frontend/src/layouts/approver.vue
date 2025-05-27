<template>
    <div>
        <div class="sticky-header">
            <TheHeader />
        </div>
        <div class="container-fluid mt-3">
            <div class="row">
                <!-- Toggle Button -->
                <div class="col-12 mb-2">
                    <button 
                        @click="toggleMenu" 
                        class="btn btn-outline-primary btn-sm"
                        type="button"
                    >
                        <i :class="isMenuVisible ? 'fas fa-times' : 'fas fa-bars'"></i>
                        {{ isMenuVisible ? 'Ẩn menu' : 'Hiện menu' }}
                    </button>
                </div>
                
                <!-- Sidebar Menu -->
                <div 
                    v-if="isMenuVisible" 
                    class="col-sm-3 col-xxl-2 d-none d-sm-flex sidebar-transition"
                >
                    <a-list bordered style="width: 100%;">
                        <TheMenu />
                        <template #header>
                            <div>BẢNG ĐIỀU KHIỂN</div>
                        </template>
                    </a-list>
                </div>
                
                <!-- Main Content -->
                <div 
                    :class="contentClasses"
                    class="bg-right bg-gradient pb-3 p-4 "
                >
                    <div style="height: 100%">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a-tooltip placement="left">
        <template #title>
            <span>Về đầu trang</span>
        </template>
        <a-back-top />
    </a-tooltip>
</template>

<script>
import TheHeader from '@/components/TheHeader.vue'
import TheMenu from '@/components/TheMenu.vue'

export default {
    components: {
        TheHeader,
        TheMenu
    },
    data() {
        return {
            isMenuVisible: true
        }
    },
    computed: {
        contentClasses() {
            return this.isMenuVisible 
                ? 'col-12 col-sm-9 col-xxl-10' 
                : 'col-12'
        }
    },
    methods: {
        toggleMenu() {
            this.isMenuVisible = !this.isMenuVisible
        }
    }
}
</script>

<style>
.bg-right {
    background-color: rgba(74, 72, 72, 0.174);
}

.sidebar-transition {
    transition: all 0.3s ease-in-out;
}

/* Mobile responsive */
@media (max-width: 576px) {
    .d-none.d-sm-flex {
        display: block !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        height: 100vh;
        z-index: 1050;
        background: white;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }
    
    .d-none.d-sm-flex.show {
        transform: translateX(0);
    }
}

.sticky-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: white; /* Hoặc màu nền phù hợp với header của bạn */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Tùy chọn: thêm shadow để tạo độ sâu */
}
</style>