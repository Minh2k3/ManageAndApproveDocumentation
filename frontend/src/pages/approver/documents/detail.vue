<template>
    <div class="container-fluid">
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
                <div class="row justify-content-between gap-3">
                    <div class="col-xl-7">
                        <!-- Information Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Tên tài liệu:</span>
                                        &nbsp;
                                        <span class="fs-5 fw-bold fst-italic ">{{ document.title }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- File -->
                            <div>
                                <PDFViewer 
                                    :pdf-url="pdfUrl" 
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-xl">
                        <!-- Information Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Thông tin văn bản</span>
                                    </div>
                                </div>

                                <!-- Document Name -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-center ps-3">
                                        <label>
                                            <span>Tên văn bản:</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <a-input v-model:value="document.title" placeholder="Văn bản số 1" disabled />
                                        <div class="w-100"></div>
                                    </div>
                                </div>

                                <!-- Document Type -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-center ps-3">
                                        <label>
                                            <span>Loại văn bản:</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <!-- Nhóm select + button chung hàng -->
                                        <div class="d-flex">
                                            <a-input v-model:value="document.type" placeholder="Văn bản số 1" disabled />
                                        </div>
                                    </div>
                                </div>

                                <!-- Document Description -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-center ps-3">
                                        <label>
                                            <span>Mô tả:</span>
                                        </label>
                                    </div>
                                    <div class="col-12 mt-1">
                                        <a-textarea 
                                            placeholder="Mô tả đơn giản" 
                                            v-model:value="document.description" 
                                            show-count
                                            :maxlength="1000" 
                                            disabled
                                            />
                                    </div>
                                </div>

                                <!-- Document Upload -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-top ps-3 pt-3">
                                        <label>
                                            <span>Tệp đính kèm:</span>
                                            <a :href="`http://localhost:8000/documents/${document.file_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Mở trong tab mới
                                            </a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <!-- Process Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="text-center">
                                        <span class="fs-5 fw-bold">Luồng phê duyệt</span>
                                        <br>
                                        <span>Tiến độ: {{  }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>                        

                        <!-- Approve/Reject Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Phê duyệt văn bản</span>
                                    </div>
                                </div>
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
import { useAuth } from '@/stores/use-auth.js';
import { useDocumentStore } from '@/stores/approver/document-store';
import PDFViewer from '@/components/PDFViewer.vue'

export default defineComponent({
    components: {
        PDFViewer,
    },
    setup() {
        useMenu().onSelectedKeys(["approver-documents-detail"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();
        const user = authStore.user;

        const route = useRoute();
        const documents = ref([]);
        let document = ref([]);
        const pdfUrl = ref('')
        onMounted(async () => {
            await documentStore.fetchDocuments(user.id);
            documents.value = documentStore.documents
            const id = parseInt(route.params.id)
            document.value = documents.value.find(doc => doc.id === id) || null;
            console.log(document.value);
            pdfUrl.value = document.value.file_path;
        });

        return {
            document,
            pdfUrl,
        };
    },
});
</script>

<style scoped>
.document-viewer-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  display: flex;
  flex-direction: column;
}

.header {
  background-color: #fff;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header h1 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 24px;
}

.document-info {
  display: flex;
  gap: 20px;
  color: #666;
  font-size: 14px;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 6px;
}

.info-item svg {
  opacity: 0.5;
}

.loading-container,
.error-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px;
}

.spinner {
  border: 3px solid #f3f3f3;
  border-top: 3px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-container {
  color: #d32f2f;
}

.error-container svg {
  opacity: 0.3;
  margin-bottom: 20px;
}

.error-container p {
  font-size: 16px;
  margin-bottom: 20px;
}

.retry-button {
  padding: 10px 20px;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.2s;
}

.retry-button:hover {
  background-color: #2980b9;
}

.viewer-container {
  flex: 1;
  margin: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  min-height: calc(100vh - 120px);
}

@media (max-width: 768px) {
  .header {
    padding: 15px;
  }
  
  .header h1 {
    font-size: 20px;
  }
  
  .document-info {
    flex-direction: column;
    gap: 10px;
  }
  
  .viewer-container {
    margin: 10px;
    min-height: calc(100vh - 140px);
  }
}
</style>