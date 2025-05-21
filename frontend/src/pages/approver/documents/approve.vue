<template>
    Đây là trang phê duyệt văn bản của người phê duyệt.
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { 
    ref, 
    defineComponent, 
    computed, 
    reactive, 
    watch, 
    onMounted, 
    createVNode 
} from 'vue';

export default defineComponent ({
    setup() {
        useMenu().onSelectedKeys(["approver-documents-approve"]);

        return {
            
        }
    },
});
</script>