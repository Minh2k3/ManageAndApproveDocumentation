<template>
    Đây là trang tạo mới tài liệu của người phê duyệt.
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["approver-documents-create"]);
    },
}
</script>