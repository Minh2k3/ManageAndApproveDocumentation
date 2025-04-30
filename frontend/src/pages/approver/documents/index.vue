<template>
    Đây là trang quản lý văn bản của người phê duyệt.
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["approver-documents"]);
    },
}
</script>