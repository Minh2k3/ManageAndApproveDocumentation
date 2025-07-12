<template>
    <div class="container-fluid">
        <div class="row" style="background-color: #007cba; padding: 1rem;">
            <div class="col-1 d-flex d-lg-none align-items-center justify-content-center">
                <span @click="showDrawer()"><i class="fa-solid fa-indent fs-1"></i></span>
            </div>

            <div class="col-8 col-lg-6 d-flex align-items-center justify-content-center justify-content-lg-start">
                <img src="../assets/images/logo_tlu.svg" alt="logo" height="32" width="34">
                <span class="d-none d-lg-flex text-white ms-3 me-3 fs-4">QUẢN TRỊ HỆ THỐNG</span>
            </div>

            <div  class="col-lg-6 d-none d-lg-flex align-items-center justify-content-lg-end">
                <div class="dropdown me-4">
                    <!-- Notification Button -->
                    <button id="btn-notification" class="btn btn-secondary border" type="button" data-bs-toggle="dropdown" aria-expanded="false" @click="toggleNotifications" style="background-color: #007cba;">
                        <i class="fa-solid fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger px-1">
                            {{ unreadMessagesCount > 9 ? 9 : unreadMessagesCount }}{{ unreadMessagesCount > 9 ? '+' : '' }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                    
                    <!-- Notification Dropdown Menu -->
                    <ul class="dropdown-menu notification-dropdown">
                        <li class="dropdown-header">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <a-button class="col" @click="maskAsAllRead">Đánh dấu tất cả đã đọc</a-button>
                                <a-button class="col" @click="viewAllNotifications">Xem tất cả</a-button>
                            </div>
                        </li>
                        
                        <!-- Loading state -->
                        <li v-if="loading" class="text-center p-3">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </li>
                        
                        <!-- Empty state -->
                        <li v-else-if="notifications.length === 0" class="text-center text-muted p-3">
                            Không có thông báo chưa đọc nào
                        </li>
                        
                        <!-- Notification Items -->
                        <li v-else v-for="notification in notifications" :key="notification.id">
                            <a href="#" 
                            @click="viewNotification(notification.id)" 
                            class="dropdown-item notification-item d-flex text-decoration-none">
                                <div class="notification-icon">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div class="notification-body">
                                    <div class="notification-content">
                                        {{ notification.content }}
                                    </div>
                                    <small class="notification-time">
                                        {{ notification.created_at }}
                                    </small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle text-capitalize" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ user_name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" 
                        style="min-width: 200px; /* Độ rộng tối thiểu */
                            width: max-content; /* Tự động điều chỉnh theo nội dung */
                            max-width: 350px;"
                            >
                        <li><h6 class="dropdown-header row d-flex align-items-center">
                            <div class="col-3"><i class="fa-solid fa-user me-2"></i></div>
                            <div class="col-9">
                                <div class="row fs-6">{{ user_name }}</div>
                                <div class="row text-capitalize">{{ role }}</div>
                            </div>
                        </h6></li>
                        <li><a class="dropdown-item" href="" @click="handleClickNotifications">Thông báo</a></li>
                        <li><a class="dropdown-item" href="" @click="handleClickSettings">Cài đặt cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="" @click.prevent="handleLogout"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-3 d-flex d-lg-none align-items-center justify-content-center ">
                <div class="dropdown me-4">
                    <!-- Notification Button -->
                    <button class="btn btn-secondary border" type="button" data-bs-toggle="dropdown" aria-expanded="false" @click="toggleNotifications" style="background-color: #007cba;">
                        <i class="fa-solid fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger px-1">
                            {{ unreadMessagesCount > 9 ? 9 : unreadMessagesCount }}{{ unreadMessagesCount > 9 ? '+' : '' }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                    
                    <!-- Notification Dropdown Menu -->
                    <ul class="dropdown-menu notification-dropdown">
                        <li class="dropdown-header">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <a-button class="col" @click="maskAsAllRead">Đánh dấu tất cả đã đọc</a-button>
                                <a-button class="col" @click="viewAllNotifications">Xem tất cả</a-button>
                            </div>
                        </li>
                        
                        <!-- Loading state -->
                        <li v-if="loading" class="text-center p-3">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </li>
                        
                        <!-- Empty state -->
                        <li v-else-if="notifications.length === 0" class="text-center text-muted p-3">
                            Không có thông báo chưa đọc nào
                        </li>
                        
                        <!-- Notification Items -->
                        <li v-else v-for="notification in notifications" :key="notification.id">
                            <a href="#" 
                            @click="viewNotification(notification.id)" 
                            class="dropdown-item notification-item d-flex text-decoration-none">
                                <div class="notification-icon">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div class="notification-body">
                                    <div class="notification-content">
                                        {{ notification.content }}
                                    </div>
                                    <small class="notification-time">
                                        {{ notification.created_at }}
                                    </small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle text-capitalize" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header row d-flex align-items-center">
                            <div class="col-3"><i class="fa-solid fa-user me-2"></i></div>
                            <div class="col-9">
                                <div class="row fs-6">{{ user_name }}</div>
                                <div class="row text-capitalize">{{ role }}</div>
                            </div>
                            
                        </h6></li>
                        <li><a class="dropdown-item" href="#" @click.prevent="handleClickNotifications">Thông báo</a></li>
                        <li><a class="dropdown-item" href="#" @click.prevent="handleClickSettings">Cài đặt cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" @click.prevent="handleLogout"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <a-drawer
        title="DANH MỤC"
        :placement="direction"
        :visible="visible"
        @close="onClose"
    >
        <TheMenu/>
    </a-drawer>

    <a-drawer
        title="ADMIN"
        :placement="direction"
        :visible="visible_user"
        @close="onClose"
    >
        <p>Some contents...</p>
        <p>Some contents...</p>
        <p>Some contents...</p>
    </a-drawer>
</template>

<script setup>
    import TheMenu from '@/components/TheMenu.vue';
    import { 
        ref, 
        createVNode,
        onMounted,
        onUnmounted,
        computed
    } from 'vue';
    import { useRouter } from 'vue-router';
    import { useAuth } from '@/stores/use-auth';
    import { useNotificationStore } from '@/stores/use-notification';
    import { useMenu } from '@/stores/use-menu.js';

    import { 
        Modal, 
        message 
    } from 'ant-design-vue';
    import {
        ExclamationCircleOutlined
    } from '@ant-design/icons-vue';

    const router = useRouter();
    const selectedKeys = computed(() => useMenu().selectedKeys);
    const authStore = useAuth();
    const notificationStore = useNotificationStore();
    const user = authStore.user;
    const role = authStore.role;

    const user_name = user.name;
    const visible = ref(false);
    const visible_user = ref(false);
    const direction = ref("left");
    const loading = ref(false);

    const notifications = ref([]);

    // Hàm linh tinh
    function playSound() {
        const audio = new Audio('/sounds/meow.mp3'); // đảm bảo đường dẫn đúng
        audio.play();
    }    

    const unreadMessagesCount = ref(3);
    const showNotifications = ref(false);
    const toggleNotifications = () => {
        showNotifications.value = !showNotifications.value;
    };

    function pushToNotificationPage() {
        if (JSON.stringify(selectedKeys) === JSON.stringify([role + '-notification'])) {
            console.log('Already on notification page');
            return;
        }
        console.log('Navigating to notification page for role:', role + '-notification');
        router.push({ name: role + '-notification' });
    };

    async function viewNotification(notificationId) {
        unreadMessagesCount.value--;
        notifications.value = notifications.value.filter(n => n.id !== notificationId);
        pushToNotificationPage();
        return;
        await notificationStore.readNotificationById(notificationId)
            .then(() => {
                console.log('Notification marked as read:', notificationId);
                pushToNotificationPage();
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
    }

    async function fetchNotifications(force = false) {
      await notificationStore.fetchNotifications(user.id, force);
      notifications.value = notificationStore.notifications.filter(notification => notification.is_read === false);
      unreadMessagesCount.value = notifications.value.length;
    };

    // Fetch thông báo ban đầu khi component được mounted
    onMounted(async () => {
        await fetchNotifications();
        loadNotifications();
    });

    const loadNotifications = () => {
        window.Echo.channel('user.' + user.id) 
            .listen('.new-notification', (event) => {
                console.log(event);
                const notification = {
                    id: event.notification.id,
                    content: event.notification.content,
                };

                notifications.value.splice(0, 0, notification);
                unreadMessagesCount.value++;
                playSound();
            }
        );
        // window.Echo.channel('user.' + user.id) 
        //     .listen('new-notification', (event) => {
        //         console.log(event);
        //         // const notification = {
        //         //     id: event.notification.id,
        //         //     content: event.notification.content,
        //         // };

        //         // notifications.push(notification);
        //         // unreadMessagesCount++;
        //         playSound();
        //     }
        // );
        // window.Echo.channel('user.' + user.id) 
        //     .listen('NewNotification', (event) => {
        //         console.log(event);
        //         // const notification = {
        //         //     id: event.notification.id,
        //         //     content: event.notification.content,
        //         // };

        //         // notifications.push(notification);
        //         // unreadMessagesCount++;
        //         playSound();
        //     }
        // );
    }

    const maskAsAllRead = async () => {
        // notificationStore.markAsAllRead(user.id);
        try {
            await notificationStore.markAsAllRead(user.id);
            notifications.value = [];
            unreadMessagesCount.value = 0;
            message.success('Đã đánh dấu tất cả thông báo là đã đọc');
        } catch (error) {
            console.error('Error marking notifications as read:', error);
            message.error('Đánh dấu tất cả thông báo là đã đọc thất bại');
        }
    };

    const viewAllNotifications = () => {
        pushToNotificationPage();
    };
    

    const showDrawer = () => {
        visible.value = true;
        direction.value = "left";
    };

    const onClose = () => {
        visible.value = false;
        visible_user.value = false;
    };

    const showDrawerUser = () => {
        visible_user.value = true;
        direction.value = "right";
    };

    // Hàm xác nhận đăng xuất
    function showConfirmLogout() {
        Modal.confirm({
            title: 'Bạn có chắc chắn đăng xuất không?',
            icon: createVNode(ExclamationCircleOutlined),
            content: createVNode(
                'div',
                {
                    style: 'color:red;',
                },
            ),
            onOk() {
                authStore.logout();
                message.success('Đăng xuất thành công');
            },
            onCancel() {
                return;
            },
        });
    };

    // Hàm xử lý sự kiện đăng xuất
    function handleLogout() {
        showConfirmLogout();
    }

    function handleClickSettings() {
        console.log('Cài đặt cá nhân clicked');
        if (useMenu().selectedKeys[0] === role + '-settings') {
            console.log('Already on settings page');
            return;
        }
        // Xử lý logic cài đặt cá nhân ở đây
        useMenu().onSelectedKeys([role + '-settings']);
        console.log('On selected keys:', useMenu().selectedKeys);
        router.push({ name: role + '-settings' });
    }

    function handleClickNotifications() {
        console.log('Thông báo clicked');
        // Xử lý logic thông báo ở đây
        pushToNotificationPage();
    }
</script>

<style scoped>
/* Dropdown Container */
.notification-dropdown {
    max-width: 350px;
    min-width: 300px;
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 0;
}

/* Responsive */
@media (max-width: 576px) {
    .notification-dropdown {
        max-width: 280px;
        min-width: 250px;
    }
}

/* Header */
.dropdown-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
    padding: 12px 16px;
    margin: 0;
}

/* Notification Item */
.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    white-space: normal;
    word-wrap: break-word;
    transition: background-color 0.2s ease;
    color: inherit;
}

.notification-item:hover {
    background-color: #f8f9fa;
    color: inherit;
}

.notification-item:last-child {
    border-bottom: none;
}

/* Icon */
.notification-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e3f2fd;
    border-radius: 50%;
    flex-shrink: 0;
    margin-right: 12px;
    color: #1976d2;
}

/* Content Body */
.notification-body {
    flex: 1;
    min-width: 0;
}

.notification-content {
    font-size: 14px;
    line-height: 1.4;
    color: #333;
    margin-bottom: 4px;
    word-wrap: break-word;
    word-break: break-word;
    display: block;
}

.notification-time {
    font-size: 12px;
    color: #6c757d;
    display: block;
    margin-top: 4px;
}

/* Scrollbar styling */
.notification-dropdown::-webkit-scrollbar {
    width: 6px;
}

.notification-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notification-dropdown::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.notification-dropdown::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Loading và Empty states */
.text-center {
    text-align: center;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Badge cho notification count */
.badge {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 10px;
    min-width: 18px;
    height: 18px;
    border-radius: 9px;
}

/* Notification button */
.btn-link {
    position: relative;
    color: #333;
    text-decoration: none;
}

.btn-link:hover {
    color: #0d6efd;
}

#btn-notification:hover {
    background-color: #77d67c;
    color: #d5d4d4; 
}
</style>