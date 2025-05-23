<template>
    <div>
        <h2 class="fw-bold mb-3">Chi tiết văn bản</h2>

        <div v-if="document.from_me">
            Văn bản tôi tạo
            <div>
                <p><strong>ID:</strong> {{ document.id }}</p>
                <p><strong>Tên văn bản:</strong> {{ document.title }}</p>
                <p><strong>Loại:</strong> {{ document.type }}</p>
                <p><strong>Trạng thái:</strong> {{ document.status }}</p>
                <p><strong>Ngày tạo:</strong> {{ document.created_at }}</p>
                <p><strong>Ngày cập nhật:</strong> {{ document.updated_at }}</p>
            </div>
        </div>
        <div v-else>
            Văn bản cần tôi duyệt
            <div class="container py-4">
                <!-- Information Section -->
                <div class="row border-1 rounded-3 p-4 mb-4 bg-light col-xl-8">
                    <div class="col">
                        <div class="row mb-3">
                            <div class="col d-flex justify-content-center">
                                <span class="fs-5 fw-bold ">Thông tin văn bản</span>
                            </div>
                        </div>

                        <!-- Document Name -->
                        <div class="row mt-3">
                            <div class="col-xl-3 text-start mb-2 mb-xl-0 align-self-center ps-3">
                                <label>
                                    <span>Tên văn bản:</span>
                                </label>
                            </div>
                            <div class="col-xl-10">
                                <a-input v-model:value="document.title" placeholder="Văn bản số 1" disabled />
                                <div class="w-100"></div>
                            </div>
                        </div>

                        <!-- Document Type -->
                        <div class="row mt-3">
                            <div class="col-xl-3 text-start mb-2 mb-xl-0 align-self-center ps-3">
                                <label>
                                    <span>Loại văn bản:</span>
                                </label>
                            </div>
                            <div class="col-xl-4">
                                <!-- Nhóm select + button chung hàng -->
                                <div class="d-flex">
                                    <a-input v-model:value="document.type" placeholder="Văn bản số 1" disabled />
                                </div>
                            </div>
                        </div>

                        <!-- Document Description -->
                        <div class="row mt-3">
                            <div class="col-xl-3 text-start mb-2 mb-xl-0 align-self-center ps-3">
                                <label>
                                    <span>Mô tả:</span>
                                </label>
                            </div>
                            <div class="col-xl-10">
                                <a-textarea 
                                    placeholder="Mô tả đơn giản" 
                                    v-model:value="document.description" 
                                    show-count
                                    :maxlength="1000" 
                                    disabled
                                    />

                                <div class="w-100"></div>
                            </div>
                        </div>

                        <!-- Document Upload -->
                        <div class="row mt-3">
                            <div class="col-xl-3 text-start mb-2 mb-xl-0 align-self-top ps-3 pt-3">
                                <label>
                                    <span>Tệp đính kèm:</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

</template>

<script>
import { 
    ref, 
    defineComponent, 
    computed, 
    reactive, 
    watch, 
    onMounted, 
    createVNode 
} from 'vue';
import { useMenu } from '@/stores/use-menu.js';
import { useRoute } from 'vue-router';
import { useDocumentStore } from '@/stores/approver/document-store';
import { useAuth } from '@/stores/use-auth.js';

export default {
    setup() {
        useMenu().onSelectedKeys(["approver-documents-detail"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();
        const user = authStore.user;

        const route = useRoute();
        const documents = ref([]);
        let document = ref([]);
        onMounted(async () => {
            await documentStore.fetchDocuments(user.id);
            documents.value = documentStore.documents
            document = computed(() => {
                const id = parseInt(route.params.id)
                return documents.value.find(doc => doc.id === id) || null
            })
            console.log(documents.value);
        })


        return {
            document,
        };
    },
}
</script>