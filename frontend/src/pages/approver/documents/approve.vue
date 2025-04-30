<template>
    Đây là trang phê duyệt văn bản của người phê duyệt.
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["approver-documents-approve"]);
    },
}
</script>