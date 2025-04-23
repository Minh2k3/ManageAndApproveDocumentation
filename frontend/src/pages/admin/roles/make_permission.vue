<template>
    Đây là trang gán quyền cho vai trò
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-roles-make_permission"]);
    },
}
</script>