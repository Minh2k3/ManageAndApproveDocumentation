<template>
    Đây là trang chung của người phê duyệt.
    
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["approver-dashboard"]);
    },
}
</script>