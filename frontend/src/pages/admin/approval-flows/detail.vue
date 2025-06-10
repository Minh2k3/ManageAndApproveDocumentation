<template>
    Detail
</template>

<script>
import { defineComponent } from 'vue';
import { useMenu } from '@/stores/use-menu.js';
import { useAuth } from '@/stores/use-auth.js';

export default defineComponent({
    name: 'ApprovalFlowsDetail',
    setup() {
        useMenu().setCurrentMenu('admin-approval-flows-detail');
    }
});
</script>