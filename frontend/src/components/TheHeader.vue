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

            <div class="col-sm-6 d-none d-sm-flex align-items-center justify-content-sm-end">
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
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a></li>
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
    import { ref } from 'vue';
    import { useRoute } from 'vue-router';
    import { useAuth } from '@/stores/use-auth';

    const authStore = useAuth();
    const user = authStore.user;
    const role = authStore.role;

    const user_name = user.name;

    console.log(user.value);
    console.log(role.value);

    const visible = ref(false);
    const visible_user = ref(false);
    const direction = ref("left");

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
</script>