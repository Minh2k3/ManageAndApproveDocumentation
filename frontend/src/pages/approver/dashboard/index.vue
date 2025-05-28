<template>
    <News />
    
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { useDocumentStore } from '@/stores/approver/document-store';
import { useAuth } from '@/stores/use-auth.js';
import News from '@/components/News.vue';

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
    components: {
        News
    },
    setup() {
        useMenu().onSelectedKeys(["approver-dashboard"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();

        return {

        }
    },
});
</script>