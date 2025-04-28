<template>
    Đây là trang lịch sử
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["creator-documents-history"]);

    },
}
</script>