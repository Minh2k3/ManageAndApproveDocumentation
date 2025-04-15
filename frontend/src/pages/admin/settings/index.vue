<template>
    Đây là trang cai dat
</template>

<script>
import { useMenu } from '../../../stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-settings"]);

    },
}
</script>