<template>
    Đây là trang quản lý chữ ký của người dùng.
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["creator-signatures"]);
    },
}
</script>