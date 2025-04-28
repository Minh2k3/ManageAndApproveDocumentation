<template>
    Đây là trang tạo mới văn bản
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["creator-documents-create"]);

    },
}
</script>