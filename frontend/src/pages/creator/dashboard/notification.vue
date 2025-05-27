
<template>

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
export default defineComponent({
    setup() {
        useMenu().onSelectedKeys(["creator-notification"]);
        const router = useRouter();

    },
});
</script>
