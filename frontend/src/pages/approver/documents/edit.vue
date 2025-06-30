<template>
    <div class="container-fluid">
        <h2 class="fw-bold mb-3">Sửa văn bản</h2>

        <!-- Giao diện khi văn bản là của tôi -->
        <div v-if="document.from_me">
            <div class="container py-1">
                <div class="row justify-content-between gap-3">
                    <div class="col-xl-7">
                        <a-tabs 
                            v-model:activeKey="activeKey" 
                            type="card"
                            class="row border-1 rounded-3 p-4 mb-2 bg-light"
                            >
                            <a-tab-pane key="1" tab="Văn bản">
                                <div class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/${document.file_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Mở tệp trong tab mới
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <!-- Information Section -->
                                <div >
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
                            </a-tab-pane>
                            <a-tab-pane key="2" :tab="`Nhận xét (${document_comments.total_comments})`" force-render>
                                <!-- Comments Section -->
                                <div v-if="document_comments.total_comments > 0" v-for="(comment, index) in document_comments.current_comments" :key="index">
                                    <div class="row mb-3 border-1 border border-dark rounded-3 bg-light py-1">
                                        <div class="col align-self-center">
                                            <a-avatar class="" v-if="comment.user['avatar']" :src="getAvatarUrl(comment.user['avatar'])"/>
                                            <a-avatar class="" v-else :src="randomAvatar(comment.user['id'])" alt="Random Avatar" />
                                        </div>
                                        <div class="col-10">
                                            <div class="row">
                                                <span class="fw-bold">
                                                    {{ comment.user['name'] }}
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-muted">
                                                    {{ comment.content }}
                                                </span>
                                            </div>
                                            <div class="row text-end">
                                                <span class="text-muted">
                                                    {{ comment.commented_at }}
                                                </span>
                                            </div>
                                        </div>
                                        <!-- <div class="col d-flex justify-content-center">
                                            <span class="fs-5 fw-bold ">Người bình luận:</span>
                                            &nbsp;
                                            <span class="fs-5 fw-bold fst-italic ">{{ comment.user_name }}</span>
                                        </div> -->
                                    </div>
                                </div>

                                <div v-else class="row mb-3">
                                    <div class="col d-flex">
                                        <span class="">Chưa có nhận xét nào</span>
                                    </div>
                                </div>
                            </a-tab-pane>
                        </a-tabs>
                    </div>
                    <div class="col-xl">
                        <!-- Comment Section -->
                        <div v-if="commentSection" class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Nhận xét</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <a-textarea 
                                            placeholder="Nhập nhận xét của bạn" 
                                            v-model:value="comment" 
                                            show-count
                                            :maxlength="1000" 
                                            ref="commentTextarea"
                                            />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 w-100 py-2 bg-secondary text-white button-click-effect" 
                                            style="--bs-bg-opacity: .9;"
                                            @click="handleSendComment"
                                            >
                                            <span>
                                                <i class="bi bi-chat-square-dots me-2"></i>Gửi bình luận
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Thông tin văn bản</span>
                                    </div>
                                </div>

                                <!-- Creator Name -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-center ps-3">
                                        <label>
                                            <span>Người tạo:</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <a-input v-model:value="document.creator_name" placeholder="Văn bản số 1" disabled />
                                        <div class="w-100"></div>
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
                                            <a-input v-model:value="document.type" placeholder="Love" disabled />
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

                                <!-- Document Created -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-center ps-3">
                                        <label>
                                            <span>Ngày tạo:</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <a-input v-model:value="document.created_at" placeholder="Ngày chờ, tháng nhớ, năm mong" disabled />
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
                                        <span v-if="document.status === 'approved'" class="fst-italic text-success">Đã được duyệt</span>
                                        <span v-else-if="document.status === 'rejected'" class="fst-italic text-danger">Bị từ chối</span>
                                        <span v-else class="fst-italic text-secondary">Tiến độ: {{ document_flow_steps.progress_percentage }}%</span>
                                    </div>
                                    <NestedProgressSteps 
                                        v-if="document_flow_steps && Object.keys(document_flow_steps).length > 0" 
                                        :steps="document_flow_steps" 
                                    />
                                </div>
                            </div>
                        </div>                        

                    </div>
                </div>               
            </div>
        </div>

        <!-- Giao diện khi văn bản cần tôi phê duyệt -->
        <div v-else>
            <div class="container py-1">
                <div class="row justify-content-between gap-3">
                    <div class="col-xl-7">
                        <a-tabs 
                            v-model:activeKey="activeKey" 
                            type="card"
                            class="row border-1 rounded-3 p-4 mb-2 bg-light"
                            >
                            <a-tab-pane key="1" tab="Văn bản">
                                <div class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/${document.file_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Mở tệp trong tab mới
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <!-- Information Section -->
                                <div >
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
                            </a-tab-pane>
                            <a-tab-pane key="2" :tab="`Nhận xét (${document_comments.total_comments})`" force-render>
                                <!-- Comments Section -->
                                <div v-if="document_comments.total_comments > 0" v-for="(comment, index) in document_comments.current_comments" :key="index">
                                    <div class="row mb-3 border-1 border border-dark rounded-3 bg-light py-1">
                                        <div class="col align-self-center">
                                            <a-avatar class="" v-if="comment.user['avatar']" :src="getAvatarUrl(comment.user['avatar'])"/>
                                            <a-avatar class="" v-else :src="randomAvatar(comment.user['id'])" alt="Random Avatar" />
                                        </div>
                                        <div class="col-10">
                                            <div class="row">
                                                <span class="fw-bold">
                                                    {{ comment.user['name'] }}
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-muted">
                                                    {{ comment.content }}
                                                </span>
                                            </div>
                                            <div class="row text-end">
                                                <span class="text-muted">
                                                    {{ comment.commented_at }}
                                                </span>
                                            </div>
                                        </div>
                                        <!-- <div class="col d-flex justify-content-center">
                                            <span class="fs-5 fw-bold ">Người bình luận:</span>
                                            &nbsp;
                                            <span class="fs-5 fw-bold fst-italic ">{{ comment.user_name }}</span>
                                        </div> -->
                                    </div>
                                </div>

                                <div v-else class="row mb-3">
                                    <div class="col d-flex">
                                        <span class="">Chưa có nhận xét nào</span>
                                    </div>
                                </div>
                            </a-tab-pane>
                        </a-tabs>
                    </div>
                    <div class="col-xl">
                        <!-- Approve/Reject Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Phê duyệt văn bản</span>
                                    </div>
                                </div>

                                <div v-if="document.step_status === 'in_review'" class="row mb-3">
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 text-white bg-success w-100 py-2 button-click-effect" 
                                            style="--bs-bg-opacity: .9;"
                                            @click="handleClickApprove"
                                            :disabled="btnApproveDisabled"
                                            >
                                            <span>
                                                <i class="bi bi-check-lg me-1"></i>Chấp thuận
                                            </span>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 text-white bg-danger w-100 py-2 button-click-effect" 
                                            style="--bs-bg-opacity: .9;"
                                            @click="handleClickReject"
                                            :disabled="btnRejectDisabled"
                                            >
                                            <span>
                                                <i class="bi bi-x-lg me-2"></i>Từ chối
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div v-else-if="document.step_status ==='pending'" class="row mb-3">
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 text-white bg-warning w-100 py-2 button-click-effect" 
                                            style="--bs-bg-opacity: 1;"
                                            @click="handleClickNotYourTurn"
                                            >
                                            <span>
                                                <i class="bi bi-hourglass-split me-2"></i>Chưa đến lượt bạn đâu
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div v-else-if="document.step_status === 'approved'" class="row mb-3">
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 text-white bg-success w-100 py-2 button-click-effect" 
                                            style="--bs-bg-opacity: 1;"
                                            @click="handleClickApproved"
                                            >
                                            <span>
                                                Bạn đã đồng ý văn bản này rồi!
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div v-else-if="document.step_status === 'rejected'" class="row mb-3">
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 text-white bg-danger w-100 py-2 button-click-effect" 
                                            style="--bs-bg-opacity: 1;"
                                            @click="handleClickRejected"
                                            >
                                            <span>
                                                Bạn đã từ chối văn bản này rồi!
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 w-100 py-2 bg-secondary text-white button-click-effect" 
                                            style="--bs-bg-opacity: .9;"
                                            @click="handleClickComment"
                                            >
                                            <span>
                                                <i class="bi bi-chat-square-dots me-2"></i>{{ btnCommentText }}
                                            </span>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 w-100 py-2 text-white bg-primary button-click-effect" 
                                            style="--bs-bg-opacity: .9;"
                                            @click="handleClickDownload(document.file_path, document.title)"
                                            :disabled="isDownloading"
                                        >
                                            <span v-if="!isDownloading">
                                                <i class="bi bi-download me-2"></i>Tải xuống
                                            </span>
                                            <span v-else>
                                                <i class="bi bi-hourglass-split me-2"></i>Đang tải...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comment Section -->
                        <div v-if="commentSection" class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Nhận xét</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <a-textarea 
                                            placeholder="Nhập nhận xét của bạn" 
                                            v-model:value="comment" 
                                            show-count
                                            :maxlength="1000" 
                                            ref="commentTextarea"
                                            />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <button 
                                            class="border border-2 rounded-2 w-100 py-2 bg-secondary text-white button-click-effect" 
                                            style="--bs-bg-opacity: .9;"
                                            @click="handleSendComment"
                                            >
                                            <span>
                                                <i class="bi bi-chat-square-dots me-2"></i>Gửi bình luận
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Section -->
                        <div class="row border-1 rounded-3 p-4 mb-4 bg-light">
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <span class="fs-5 fw-bold ">Thông tin văn bản</span>
                                    </div>
                                </div>

                                <!-- Creator Name -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-center ps-3">
                                        <label>
                                            <span>Người tạo:</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <a-input v-model:value="document.creator_name" placeholder="Văn bản số 1" disabled />
                                        <div class="w-100"></div>
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
                                            <a-input v-model:value="document.type" placeholder="Love" disabled />
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

                                <!-- Document Created -->
                                <div class="row mt-3">
                                    <div class="col text-start mb-2 mb-xl-0 align-self-center ps-3">
                                        <label>
                                            <span>Ngày tạo:</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <a-input v-model:value="document.created_at" placeholder="Ngày chờ, tháng nhớ, năm mong" disabled />
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
                                        <span v-if="document.document_status === 'approved'" class="fst-italic text-success">Đã được duyệt</span>
                                        <span v-else-if="document.document_status === 'rejected'" class="fst-italic text-danger">Bị từ chối</span>
                                        <span v-else class="fst-italic text-secondary">Tiến độ: {{ document_flow_steps.progress_percentage }}%</span>
                                    </div>
                                    <NestedProgressSteps 
                                        v-if="document_flow_steps && Object.keys(document_flow_steps).length > 0" 
                                        :steps="document_flow_steps" 
                                    />
                                </div>
                            </div>
                        </div>                        

                    </div>
                </div>               
            </div>
        </div>
    </div>
    
    <!-- Modal từ chối văn bản -->
    <a-modal
        v-model:visible="rejectVisible"
        width="600px"
        >
        <div>
            <h5>❌ Từ chối văn bản</h5>
            <a-divider />
            <div>
                <span>Lý do chia tay là gì, em có biết không???</span>
                <a-textarea 
                    v-model:value="reasonReject" 
                    placeholder="Vì em không yêu anh như anh yêu em, vì em xem anh chỉ là nhất thời." 
                    show-count
                    :maxlength="1000"
                    rows="4"
                    class="mb-5"
                />
            </div>
            <a-divider />
        </div>

        <template #footer>
            <a-button @click="rejectVisible = false">Hủy</a-button>
            <a-button type="primary" @click="handleRejectDocument">Từ chối</a-button>
        </template>
    </a-modal>

</template>

<script>
import { 
    ref, 
    defineComponent, 
    computed, 
    reactive, 
    watch, 
    onMounted, 
    createVNode, 
    h,
    nextTick
} from 'vue';
import { useMenu } from '@/stores/use-menu.js';
import { useRoute } from 'vue-router';
import { useAuth } from '@/stores/use-auth.js';
import { useDocumentStore } from '@/stores/approver/document-store';
import PDFViewer from '@/components/PDFViewer.vue'
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { message, Modal } from 'ant-design-vue';
import NestedProgressSteps from '@/components/ProgressDocument.vue';


export default defineComponent({
    components: {
        PDFViewer,
        NestedProgressSteps,
    },
    setup() {
        dayjs.extend(relativeTime);
        const activeKey = ref('1');
        const commentSection = ref(false);
        useMenu().onSelectedKeys(["approver-documents-edit"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();
        const user = authStore.user;

        const route = useRoute();
        const documents = ref([]);
        let documentData = ref([]);
        const pdfUrl = ref('')
        const process_of_document = ref(0);
        const document_comments = ref([]);
        const document_steps = 
        [
            {
                title: 'Bước 1',
                description: 'Mô tả bước 1',
                status: 'approved', // hoặc 'rejected', 'in_review', 'waiting'
                subSteps: [
                    {
                        title: 'Bước con 1.1',
                        description: 'Mô tả bước con 1.1',
                        status: 'approved'
                    },
                    {
                        title: 'Bước con 1.2', 
                        description: 'Mô tả bước con 1.2',
                        status: 'in_review'
                    }
                ]
            },
            {
                title: 'Bước 2',
                description: 'Mô tả bước 2',
                status: 'waiting',
                subSteps: [
                    {
                        title: 'Bước con 2.1',
                        description: 'Mô tả bước con 2.1', 
                        status: 'waiting'
                    },
                    {
                        title: 'Bước con 2.2',
                        description: 'Mô tả bước con 2.2',
                        status: 'waiting'
                    }
                ]
            },
            {
                title: 'Bước 3',
                description: 'Mô tả bước 3',
                status: 'waiting',
                subSteps: [
                    {
                        title: 'Bước con 3.1',
                        description: 'Mô tả bước con 3.1', 
                        status: 'waiting'
                    },
                    {
                        title: 'Bước con 3.2',
                        description: 'Mô tả bước con 3.2',
                        status: 'waiting'
                    }
                ]
            }
        ]
        const document_flow_steps = ref([]);

        onMounted(async () => {
            await documentStore.fetchDocuments(user.id);
            documents.value = documentStore.documents;
            const id = parseInt(route.params.id);
            const from_me = route.query.from_me === '1';
            if (from_me) {
                documentData.value = documents.value.documents_of_me.find(doc => doc.id === id && doc.from_me) || null;
            } else {
                documentData.value = documents.value.documents_need_me.find(doc => doc.id === id && !doc.from_me) || null;
            }

            await documentStore.fetchDocumentComments(id);
            document_comments.value = documentStore.document_comments;

            console.log(document_comments.value);
            console.log(documentData.value);

            pdfUrl.value = documentData.value.file_path;
            process_of_document.value = documentData.value.process / documentData.value.step_count * 100;

            await documentStore.fetchStepsByDocumentFlowId(documentData.value.document_flow_id);
            document_flow_steps.value = documentStore.current_document_flow_steps;
        });

        const comment = ref('');

        const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

        const getAvatarUrl = (avatar) => {
            if (!avatar) return null
            return `${API_BASE_URL}/images/avatars/${avatar}`
        }

        const btnApproveDisabled = ref(false);
        const btnRejectDisabled = ref(false);

        const handleClickApprove = async () => {
            const step_id = parseInt(documentData.value['document_flow_step_id']);
            console.log('Step ID:', step_id);
            btnApproveDisabled.value = true;
            btnRejectDisabled.value = true;
            // setTimeout(() => {
            //     btnApproveDisabled.value = false;
            //     btnRejectDisabled.value = false;
            // }, 2000); // Disable buttons for 2 seconds
            // return;
            try {
                await documentStore.approveDocument(step_id);
                documentData.value.status = 'approved';
                message.success('Bạn vừa đồng ý phê duyệt');
                await documentStore.fetchStepsByDocumentFlowId(documentData.value.document_flow_id);
                document_flow_steps.value = documentStore.current_document_flow_steps;
            } catch (error) {
                message.error('Có lỗi xảy ra khi đồng ý phê duyệt văn bản');
                console.error('Error approving document:', error);
            } finally {
                btnApproveDisabled.value = false;
                btnRejectDisabled.value = false;
            }
        };

        const rejectVisible = ref(false);
        const reasonReject = ref('');

        const handleRejectDocument = async () => {
            if (reasonReject.value.trim() === '') {
                message.error('Vui lòng nhập lý do từ chối');
                return;
            }

            const data = {
                document_version_id: documentData.value['version_id'],
                reason: reasonReject.value,
            };
            const step_id = parseInt(documentData.value['document_flow_step_id']);

            console.log('Rejecting document with data:', data);
            console.log('Step ID:', step_id);
            btnApproveDisabled.value = true;
            btnRejectDisabled.value = true;
            // return;
            try {
                await documentStore.rejectDocument(step_id, data);
                documentData.value.status = 'rejected';
                message.success('Bạn vừa từ chối văn bản với lý do: ' + reasonReject.value);
            } catch (error) {
                message.error('Có lỗi xảy ra khi từ chối văn bản');
                console.error('Error rejecting document:', error);
            } finally {
                rejectVisible.value = false;
                btnApproveDisabled.value = false;
                btnRejectDisabled.value = false;
            }
        };

        const handleClickReject = async () => {
            rejectVisible.value = true;
        };

        const commentTextarea = ref(null)
        const btnCommentText = ref('Nhận xét');
        const handleClickComment = async () => {
            console.log('Comment clicked');
            commentSection.value = !commentSection.value;
            if (activeKey.value === '1') {
                activeKey.value = '2';
                btnCommentText.value = 'Văn bản';
                await nextTick();   
                commentTextarea.value.focus();
            } else {    
                activeKey.value = '1';
                btnCommentText.value = 'Nhận xét';
            }
        };

        watch(activeKey, (newValue) => {
            if (newValue === '1') {
                commentSection.value = false;
                btnCommentText.value = 'Nhận xét';
            } else {
                commentSection.value = true;
                btnCommentText.value = 'Văn bản';
            }
        });

        const isDownloading = ref(false);

        const handleClickDownload = (filePath, fileName = null) => {
            try {
                isDownloading.value = true;
                
                const downloadUrl = `${API_BASE_URL}/documents/${filePath}`;
                const link = document.createElement('a');
                
                link.href = downloadUrl;
                link.download = fileName || filePath.split('/').pop();
                link.style.display = 'none';
                
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                message.success('Đang tải file...');
                
                setTimeout(() => {
                    isDownloading.value = false;
                }, 2000);
                
            } catch (error) {
                console.error('Error downloading file:', error);
                message.error('Có lỗi xảy ra khi tải file!');
                isDownloading.value = false;
            }
        };

        const handleSendComment = async () => {
            if (comment.value === '') {
                message.error('Vui lòng nhập bình luận');
                return;
            }

            // console.log(documentData.value.creator_id);
            // console.log(comment.value);
            // console.log(parseInt(documentData.value['document_flow_step_id']));
            // return;
            try {
                const id = parseInt(route.params.id);
                await axios.post(`/api/documents/${id}/comments`, {
                    creator_id: documentData.value.creator_id,
                    comment: comment.value,
                    document_flow_step_id: parseInt(documentData.value['document_flow_step_id']),
                });
                message.success('Nhận xét gửi thành công');

                await documentStore.fetchDocumentComments(id);
                document_comments.value = documentStore.document_comments;
                comment.value = '';
            } catch (error) {
                message.error('Có lỗi xảy ra khi gửi nhận xét');
                console.error('Error sending comment:', error);
            }
        };

        const handleClickNotYourTurn = () => {
            Modal.info({
                title: 'Thông báo',
                content: createVNode('p', null, 'Chưa đến lượt bạn phê duyệt văn bản này.'),
                okText: 'Đóng',
            });
        };

        const handleClickApproved = () => {
            Modal.info({
                title: 'Thông báo',
                content: createVNode('p', null, 'Bạn đã phê duyệt văn bản này rồi!'),
                okText: 'Đóng',
            });
        };

        const handleClickRejected = () => {
            Modal.info({
                title: 'Thông báo',
                content: createVNode('p', null, 'Bạn đã từ chối văn bản này rồi!'),
                okText: 'Đóng',
            });
        };
        
        const randomAvatar = (id) => {
            if (id > 100 || id == null) {
                return `https://avatar.iran.liara.run/public`;
            }
            return `https://avatar.iran.liara.run/public/${id}`;
        };

        return {
            apiUrl: API_BASE_URL,
            document: documentData,
            pdfUrl,
            process_of_document,
            activeKey,
            commentSection,
            comment,
            document_comments,
            dayjs,
            btnCommentText,
            commentTextarea,
            isDownloading,
            btnApproveDisabled,
            btnRejectDisabled,
            rejectVisible,
            reasonReject,
            document_steps,
            document_flow_steps,

            getAvatarUrl,
            randomAvatar,

            handleClickApprove,
            handleClickReject,
            handleClickComment,
            handleClickDownload,
            handleSendComment,
            handleClickNotYourTurn,
            handleRejectDocument,
            handleClickApproved,
            handleClickRejected,
        };
    },
});
</script>

<style scoped>

.button-click-effect:active {
    transform: scale(0.9);
    transition: transform 0.1s ease;
}

.button-click-effect:hover {
    filter: brightness(1.1);
    transition: transform 0.1s ease;
}

.button-click-effect:disabled {
    background-color: #6c757d !important; /* Màu xám */
    border-color: #6c757d !important;
    color: #fff !important;
    cursor: not-allowed !important;
    opacity: 0.65;
    transform: none !important;
    box-shadow: none !important;
    pointer-events: none; /* Loại bỏ hoàn toàn các sự kiện chuột */
}
</style>