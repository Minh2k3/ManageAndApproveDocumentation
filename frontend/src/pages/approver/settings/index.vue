<template>
    <Settings />
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import Settings from '@/components/Settings.vue';
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
export default defineComponent({
    components: {
        Settings,
    },
    setup() {
        useMenu().onSelectedKeys(["approver-settings"]);
    },
});
</script>