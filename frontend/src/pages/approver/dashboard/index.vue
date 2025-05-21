<template>
    Đây là trang chung của người phê duyệt.
    
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { useDocumentStore } from '@/stores/approver/document-store';
import { useAuth } from '@/stores/use-auth.js';

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
        useMenu().onSelectedKeys(["approver-dashboard"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();

        return {

        }
    },
});
</script>