<template>
    <div>
        <div class="sticky-header">
            <TheHeader />
        </div>
        <div class="container-fluid mt-3">
            <div class="row">
                <!-- Sidebar Menu with Toggle Button -->
                <div 
                    :class="['sidebar-wrapper', { 'collapsed': !isMenuVisible }]"
                >
                    <!-- Menu Content -->
                    <div class="sidebar-content" v-show="isMenuVisible">
                        <a-list bordered style="width: 100%;">
                            <TheMenu />
                            <template #header>
                                <div>BẢNG ĐIỀU KHIỂN</div>
                            </template>
                        </a-list>
                    </div>
                    
                    <!-- Toggle Button attached to menu -->
                    <button 
                        @click="toggleMenu"
                        class="menu-toggle-btn ms-3 mt-3 d-none d-lg-block"
                        type="button"
                        :title="isMenuVisible ? 'Ẩn menu' : 'Hiện menu'"
                    >
                        <i :class="isMenuVisible ? 'fas fa-chevron-left' : 'fas fa-chevron-right'"></i>
                    </button>
                </div>
                
                <!-- Main Content -->
                <div
                    :class="contentClasses"
                    class="bg-right bg-gradient pb-3 p-4 main-content"
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
    },
    mounted() {
        // Lưu và khôi phục trạng thái menu
        const savedState = localStorage.getItem('menuVisible')
        if (savedState !== null) {
            this.isMenuVisible = savedState === 'true'
        }
    },
    watch: {
        isMenuVisible(newVal) {
            localStorage.setItem('menuVisible', newVal)
        }
    }
}
</script>

<style scoped>
.bg-right {
    background-color: rgba(74, 72, 72, 0.174);
}

.sticky-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Sidebar Wrapper */
.sidebar-wrapper {
    position: relative;
    transition: all 0.3s ease-in-out;
}

.sidebar-wrapper.collapsed {
    width: 0;
}

.sidebar-content {
    width: 100%;
    transition: all 0.3s ease-in-out;
}

/* Toggle Button Styles */
.menu-toggle-btn {
    position: fixed;
    top: 70px; /* Adjust based on your header height */
    left: calc(25% - 20px); /* Default position for desktop */
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease-in-out;
    z-index: 1001; /* Higher than header */
    box-shadow: 2px 0 4px rgba(0,0,0,0.1);
}

/* Adjust button position based on sidebar state */
.sidebar-wrapper:not(.collapsed) .menu-toggle-btn {
    left: calc(25% - 20px);
}

@media (min-width: 1400px) {
    .sidebar-wrapper:not(.collapsed) .menu-toggle-btn {
        left: calc(16.666667% - 20px);
    }
}

.menu-toggle-btn:hover {
    background: #f0f0f0;
    box-shadow: 2px 0 6px rgba(0,0,0,0.15);
}

.menu-toggle-btn i {
    font-size: 14px;
    color: #666;
    transition: transform 0.3s ease-in-out;
}

/* When menu is collapsed */
.sidebar-wrapper.collapsed .menu-toggle-btn {
    left: -20px;
}

/* Main content transitions */
.main-content {
    transition: all 0.3s ease-in-out;
}

/* Desktop styles */
@media (min-width: 576px) {
    .sidebar-wrapper {
        flex: 0 0 auto;
        width: auto;
    }
    
    .sidebar-wrapper:not(.collapsed) {
        width: 25%; /* col-sm-3 */
    }
    
    @media (min-width: 1400px) {
        .sidebar-wrapper:not(.collapsed) {
            width: 16.666667%; /* col-xxl-2 */
        }
    }
}

/* Mobile responsive */
@media (max-width: 575px) {
    .sidebar-wrapper {
        position: fixed;
        top: 60px; /* Adjust based on header height */
        left: 0;
        height: calc(100vh - 60px);
        z-index: 1050;
        background: white;
        width: 280px;
        transform: translateX(0);
    }
    
    .sidebar-wrapper.collapsed {
        transform: translateX(-280px);
    }
    
    .sidebar-content {
        height: 100%;
        overflow-y: auto;
    }
    
    .menu-toggle-btn {
        position: fixed;
        top: 70px; /* Same as desktop, adjust based on header */
        left: 260px; /* At the edge of sidebar */
        border-radius: 0 5px 5px 0;
    }
    
    .sidebar-wrapper.collapsed .menu-toggle-btn {
        left: -20px;
    }
    
    /* Overlay for mobile */
    .sidebar-wrapper:not(.collapsed)::after {
        content: '';
        position: fixed;
        top: 0;
        left: 280px;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: -1;
    }
    
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
}

/* Animation for smooth transitions */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.sidebar-content {
    animation: slideIn 0.3s ease-out;
}

/* Custom scrollbar for sidebar */
.sidebar-content::-webkit-scrollbar {
    width: 6px;
}

.sidebar-content::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.sidebar-content::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.sidebar-content::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>