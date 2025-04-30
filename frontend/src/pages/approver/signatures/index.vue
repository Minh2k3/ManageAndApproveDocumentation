<template>
    Đây là trang quản lý chữ ký của người phê duyệt.
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["approver-signatures"]);
    },
}
</script>