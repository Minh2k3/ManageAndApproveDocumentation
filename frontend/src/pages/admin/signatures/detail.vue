<template>
    Đây là trang chữ ký chi tiết
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-signatures-detail"]);

    },
}
</script>