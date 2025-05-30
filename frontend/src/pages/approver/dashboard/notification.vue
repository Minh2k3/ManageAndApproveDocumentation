<template>
    <NotificationsPage />
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import NotificationsPage from '@/components/NotificationsPage.vue';
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
import { useDocumentStore } from '@/stores/approver/document-store.js';
import { message, Modal } from 'ant-design-vue';
import { useAuth } from '@/stores/use-auth.js';
export default defineComponent({
    components: {
        NotificationsPage
    },
    setup() {
        useMenu().onSelectedKeys(["approver-notification"]);
        const router = useRouter();

    },
});
</script>