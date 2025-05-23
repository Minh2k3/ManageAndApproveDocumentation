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
                <div class="row justify-content-between gap-2">
                    <div class="col-xl-7">
                        <!-- Information Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">{{ document.title }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- File -->
  <div class="document-viewer-page">
    <div class="header">
      <h1>Trình xem tài liệu PDF</h1>
      
      <div class="document-selector">
        <label for="document-select">Chọn tài liệu:</label>
        <select id="document-select" v-model="selectedDocument" @change="onDocumentChange">
          <option value="">-- Chọn file PDF --</option>
          <option v-for="file in pdfFiles" :key="file.name" :value="file.name">
            {{ file.name }} ({{ formatFileSize(file.size) }})
          </option>
        </select>
      </div>
    </div>

    <div v-if="selectedDocument" class="viewer-container">
      <PDFViewer 
        :pdf-url="selectedDocument"
        :initial-scale="1.2"
        @page-change="onPageChange"
      />
    </div>

    <div v-else class="no-document">
      <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-width="2"/>
        <polyline points="14 2 14 8 20 8" stroke-width="2"/>
        <line x1="16" y1="13" x2="8" y2="13" stroke-width="2"/>
        <line x1="16" y1="17" x2="8" y2="17" stroke-width="2"/>
        <polyline points="10 9 9 9 8 9" stroke-width="2"/>
      </svg>
      <p>Vui lòng chọn một tài liệu PDF để xem</p>
    </div>
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
        // const pdfUrl = ref(null);
        // // const { pdf } = usePDF('sample.pdf');
        // const pdf = ref(null);
        const documentFilename = ref('3374b893-4fe4-4f5c-8f31-c75ef448bc9c.pdf');
        const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
        onMounted(async () => {
            await documentStore.fetchDocuments(user.id);
            documents.value = documentStore.documents
            const id = parseInt(route.params.id)
            document.value = documents.value.find(doc => doc.id === id) || null;
            console.log(document.value);
            // pdfUrl.value = document.value ? document.value.file_path : null;
            // pdfUrl.value = 'http://localhost:8000/documents/' + pdfUrl.value;
            // pdf.value = pdfUrl.value;
            // console.log('pdf: ' + pdf.value);
            // console.log('pdfUrl: ' + pdfUrl.value);
        });
        const pdfUrl = ref('http://localhost:8000/documents/3374b893-4fe4-4f5c-8f31-c75ef448bc9c.pdf')

        const onPdfLoaded = (info) => {
        console.log('PDF loaded:', info.filename)
        console.log('View URL:', info.url) // http://localhost:8000/documents/filename.pdf/view
        }

        const onPdfError = (error) => {
        console.error('PDF error:', error.message)
        }

        return {
            document,
            // pdfUrl,
            // pdf,
            documentFilename,
            apiBaseUrl,
            pdfUrl,

            onPdfLoaded,
            onPdfError,
        };
    },
});
</script>