<template>
    Đây là trang quản lý vai trò
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-roles"]);
    },
}
</script>