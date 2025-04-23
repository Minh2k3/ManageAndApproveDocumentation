<template>
    Đây là trang thêm mới luồng phê duyệt
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-approval-flows-create"]);

    },
}
</script>