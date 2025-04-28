<template>
    <div>
        <h2 class="fw-bold mb-3">Chi tiết văn bản</h2>

        <div v-if="document">
            <p><strong>ID:</strong> {{ document.id }}</p>
            <p><strong>Tên văn bản:</strong> {{ document.name }}</p>
            <p><strong>Loại:</strong> {{ document.type }}</p>
            <p><strong>Trạng thái:</strong> {{ document.status }}</p>
            <p><strong>Ngày tạo:</strong> {{ document.created_at }}</p>
            <p><strong>Ngày cập nhật:</strong> {{ document.updated_at }}</p>
        </div>
        <div v-else>
            <p>Không tìm thấy văn bản.</p>
        </div>
    </div>
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';
export default {
    setup() {
        useMenu().onSelectedKeys(["creator-documents-detail"]);

        const route = useRoute();
        const documents = ref([
            {
                id: 1,
                name: 'Văn bản 1',
                type_id: 1,
                type: 'Văn bản đi',
                status_id: 1,
                status: 'Đã duyệt',
                created_at: '2023-10-01',
                updated_at: '2023-10-01',
            },
            {
                id: 2,
                name: 'Văn bản 2',
                type_id: 2,
                type: 'Văn bản đến',
                status_id: 2,
                status: 'Chờ duyệt',
                created_at: '2023-10-02',
                updated_at: '2023-10-02',
            }, 
            {
                id: 3,
                name: 'Văn bản 3',
                type_id: 3,
                type: 'Văn bản nội bộ',
                status_id: 1,
                status: 'Đã duyệt',
                created_at: '2023-10-03',
                updated_at: '2023-10-03',
            },
            {
                id: 4,
                name: 'Văn bản 4',
                type_id: 1,
                type: 'Văn bản đi',
                status_id: 2,
                status: 'Chờ duyệt',
                created_at: '2023-10-04',
                updated_at: '2023-10-04',
            },
            {
                id: 5,
                name: 'Văn bản 5',
                type_id: 2,
                type: 'Văn bản đến',
                status_id: 1,
                status: 'Đã duyệt',
                created_at: '2023-10-05',
                updated_at: '2023-10-05',
            },
        ]);

        const document = computed(() => {
            const id = parseInt(route.params.id);
            return documents.value.find(doc => doc.id === id) || null;
        });

        return {
            document,
        };

    },
}
</script>