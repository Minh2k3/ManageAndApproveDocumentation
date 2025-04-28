<template>
    Dashboard cho Creator
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
export default {
    setup() {
        useMenu().onSelectedKeys(["creator-dashboard"]);

    },
}
</script>