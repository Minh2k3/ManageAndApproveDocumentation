<template>
    Đây là trang lịch sử văn bản
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-documents-history"]);

    },
}
</script>