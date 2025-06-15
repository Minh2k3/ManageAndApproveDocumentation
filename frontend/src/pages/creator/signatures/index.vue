<template>
    <CertificateUser />
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import CertificateUser from '@/components/CertificateUser.vue';
import { ref, defineComponent } from 'vue';
export default defineComponent({
    components: {
        CertificateUser,
    },
    setup() {
        useMenu().onSelectedKeys(["creator-signatures"]);
    },
});
</script>