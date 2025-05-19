<template>
    <div class="container-fluid">
        <div class="row" style="background-color: #007cba; padding: 1rem;">
            <div class="col-1 d-flex d-sm-none align-items-center justify-content-center">
                <span @click="showDrawer()"><i class="fa-solid fa-indent fs-1"></i></span>
            </div>

            <div class="col-10 col-sm-6 d-flex align-items-center justify-content-center justify-content-sm-start">
                <img src="../assets/images/logo_tlu.svg" alt="logo" height="32" width="34">
                <span class="d-none d-sm-flex text-white ms-3 me-3 fs-4">QUẢN TRỊ HỆ THỐNG</span>
            </div>

            <div  class="col-sm-6 d-none d-sm-flex align-items-center justify-content-sm-end">
                <div class="dropdown me-3">
                    <button class="btn btn-secondary border" type="button" data-bs-toggle="dropdown" aria-expanded="false" @click="toggleNotifications" style="background-color: #007cba;">
                        <i class="fa-solid fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ unreadMessagesCount > 9 ? 9 : unreadMessagesCount }}{{ unreadMessagesCount > 9 ? '+' : '' }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header row d-flex align-items-center">
                            <button>Đánh dấu tất cả đã đọc</button>
                        </h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <div v-if="showNotifications">
                            <li v-if="notifications.length === 0" class="dropdown-item">Không có thông báo nào</li>
                            <li v-else v-for="notification in notifications" :key="notification.id">
                                <a href="#" @click="viewNotification(notification.id)" class="dropdown-item">{{ notification.content }}</a>
                            </li>
                        </div>
                    </ul>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle text-capitalize" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ user_name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header row d-flex align-items-center">
                            <div class="col-3"><i class="fa-solid fa-user me-2"></i></div>
                            <div class="col-9">
                                <div class="row fs-6">{{ user_name }}</div>
                                <div class="row text-capitalize">{{ role }}</div>
                            </div>
                            
                        </h6></li>
                        <li><a class="dropdown-item" href="#">Thông báo</a></li>
                        <li><a class="dropdown-item" href="#">Cài đặt cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" @click.prevent="handleLogout"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-1 d-flex d-sm-none align-items-center justify-content-center">
                <span @click="showDrawerUser()"><i class="fa-solid fa-user fs-1"></i></span>
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
    } from 'vue';
    import { useRoute } from 'vue-router';
    import { useAuth } from '@/stores/use-auth';
    import { 
        Modal, 
        message 
    } from 'ant-design-vue';
    import {
        ExclamationCircleOutlined
    } from '@ant-design/icons-vue';

    const route = useRoute();
    const authStore = useAuth();
    const user = authStore.user;
    const role = authStore.role;

    const user_name = user.name;
    const visible = ref(false);
    const visible_user = ref(false);
    const direction = ref("left");

    const notifications = ref([
        { id: 1, content: 'Thông báo 1' },
        { id: 2, content: 'Thông báo 2' },
        { id: 3, content: 'Thông báo 3' },
    ]);

    const loadNotifications = () => {
        window.Echo.channel('user.' + this.user.id) 
                .listen('new-notification', (event) => {
                const notification = {
                    id: event.notification.id,
                    content: event.notification.content,
                    document_id: event.document_id,
                };

                this.notifications.push(notification);
                this.unreadMessagesCount++;
                });
    }
    loadNotifications();

    const unreadMessagesCount = ref(3);
    const showNotifications = ref(false);
    const toggleNotifications = () => {
        showNotifications.value = !showNotifications.value;
    };

    function viewNotification(notificationId) {
        unreadMessagesCount.value--;
        notifications.value = notifications.value.filter(n => n.id !== notificationId);
    }

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
</script>