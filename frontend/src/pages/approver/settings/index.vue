<template>
    Đây là trang cài đặt của người phê duyệt.
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["approver-settings"]);
    },
}
</script>