<template>
    Đây là trang chi tiết văn bản
</template>


<script>
import { defineComponent, ref } from "vue";
import { useMenu } from '@/stores/use-menu.js';
export default defineComponent({
    setup() {
        useMenu().onSelectedKeys(["admin-documents-detail"]);
        
       
    },
});
</script>