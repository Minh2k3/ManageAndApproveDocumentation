<template>
    <News />
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
    createVNode,
    h 
} from 'vue';
import { useRouter } from 'vue-router';
import { useDocumentStore } from '@/stores/creator/document-store.js';
import { message, Modal } from 'ant-design-vue';
import { useAuth } from '@/stores/use-auth.js';
import News from '@/components/News.vue';

export default defineComponent({
    components: {
        News
    },
    setup() {
        useMenu().onSelectedKeys(["creator-dashboard"]);
        const router = useRouter();
        const documentStore = useDocumentStore();
        const auth = useAuth();

        onMounted(async () => {
            await documentStore.fetchAll(auth.user.id);
            
        });

        return {

        }
    },
});
</script>