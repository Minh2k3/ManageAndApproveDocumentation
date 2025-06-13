<template>
    <div class="container-fluid">
        <h2 class="fw-bold mb-3">Chi tiết văn bản</h2>

        <div>
            <div class="container py-1">
                <div class="row justify-content-between gap-3">
                    <div class="col-xl-7">
                        <a-tabs 
                            v-model:activeKey="activeKey" 
                            type="card"
                            class="row border-1 rounded-3 p-4 mb-2 bg-light"
                            >
                            <a-tab-pane key="document" tab="Văn bản">
                                <div class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`http://localhost:8000/documents/${document.file_path}`" target="_blank" class="text-decoration-none fst-italic">
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
                            <a-tab-pane key="comment" :tab="`Nhận xét (${document_comments.total_current_comments})`" force-render>
                                <!-- Comments Section -->
                                <div v-if="document_comments.total_current_comments > 0" v-for="(comment, index) in document_comments.current_comments" :key="index">
                                    <div class="row mb-3 border-1 border border-dark rounded-3 bg-light py-1">
                                        <div class="col align-self-center">
                                            <a-avatar class="" v-if="comment.user['avatar']" :src="getAvatarUrl(comment.user['avatar'])"/>
                                            <a-avatar class="" v-else :src="randomAvatar(comment.user['id'])" alt="Random Avatar" />
                                        </div>
                                        <div class="col-10">
                                            <div class="row">
                                                <span class="fw-bold">
                                                    {{ comment.user['id'] === user['id'] ? `Tôi (${comment.user['name']})` : comment.user['name'] }}
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
                                    </div>
                                </div>

                                <div v-else class="row mb-3">
                                    <div class="col d-flex justify-content-center">
                                        <a-empty
                                            image="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTNvPNFo6a-PV4l-vMo8Hcsoj5rRr2I5XIJUQ&s"
                                            :image-style="{
                                                height: '50px',
                                            }"
                                        >
                                            <template #description>
                                            <span class="fw-bold fs-5">Chưa có bình luận nào</span>
                                            <br>
                                            <span>Hãy là người đầu tiên chia sẻ ý kiến về văn bản này</span>
                                            </template>
                                            <a-button type="primary" @click="handleFocusCommentInput">Nhận xét ngay</a-button>
                                        </a-empty>
                                    </div>
                                </div>
                            </a-tab-pane>
                            <a-tab-pane key="versions" :tab="`Phiên bản (${document.version_count})`">
                                <div class="row mb-3">
                                    <h4 class="fw-bold">Các phiên bản</h4>
                                </div>
                                <div class="row mb-3">
                                    <a-table 
                                        :dataSource="document_versions" 
                                        :columns="version_columns" 
                                        :scroll="{ x: 576 }" 
                                        bordered 
                                        :customRow="customRow"
                                        :showSorterTooltip="false"
                                        :locale="{
                                            triggerDesc: 'Nhấn để sắp xếp giảm dần',
                                            triggerAsc: 'Nhấn để sắp xếp tăng dần',
                                            cancelSort: 'Nhấn để hủy sắp xếp'
                                        }"
                                    >
                                        <template #headerCell="{ column }">
                                            <template v-if="column.key === 'title'">
                                                <a-tooltip title="Sắp xếp theo tên văn bản">
                                                    <span>{{ column.title }}</span>
                                                </a-tooltip>
                                            </template>
                                            <template v-else-if="column.key === 'status'">
                                                <a-tooltip title="Sắp xếp theo trạng thái">
                                                    <span>{{ column.title }}</span>
                                                </a-tooltip>
                                            </template>
                                            <template v-else-if="column.key === 'created_at'">
                                                <a-tooltip title="Sắp xếp theo ngày tạo">
                                                    <span>{{ column.title }}</span>
                                                </a-tooltip>
                                            </template>
                                        </template>

                                        <template #bodyCell="{ column, index, record }">
                                            <template v-if="column.key === 'version'">
                                                <span>{{ record.version }}</span>
                                            </template>

                                            <template v-if="column.key === 'title'">
                                                <a-tooltip>
                                                    <template #title>
                                                        <span class="">{{ record.document_data['description'] }}</span>
                                                    </template>
                                                    <span class="">{{ record.document_data['title'] }}</span>
                                                </a-tooltip>
                                            </template>

                                            <template v-if="column.key === 'type'">
                                                <span class="">{{ mapTypeIdToName(record.document_data['document_type_id']) }}</span>
                                            </template>

                                            <template v-if="column.key === 'status'">
                                                <a-tag v-if="record.status === 'approved'" color="green">
                                                    <span>Đã duyệt</span>
                                                </a-tag>
                                                <a-tag v-if="record.status === 'in_review'" color="orange">
                                                    <span>Chờ duyệt</span>
                                                </a-tag>
                                                <a-tag v-if="record.status === 'rejected'" color="red">
                                                    <span>Bị từ chối</span>
                                                </a-tag>
                                                <a-tag v-if="record.status === 'draft'" color="blue">
                                                    <span>Bản nháp</span>
                                                </a-tag>
                                            </template>

                                            <template v-if="column.key === 'action'">
                                                <a-space class="d-flex justify-content-center gap-3">
                                                    <a-tooltip>
                                                        <template #title>
                                                            <span class="">Xem chi tiết</span>
                                                        </template>
                                                        <a-button 
                                                            @click="changeVersion(record)"
                                                            class="bg-primary text-white"
                                                            >
                                                            <i class="bi bi-eye"></i>
                                                        </a-button>
                                                    </a-tooltip>

                                                    <a-tooltip
                                                        v-if="record.status === 'rejected' && record.version === max_version || record.status === 'draft'"
                                                        >
                                                        <template #title>
                                                            <span class="">Tạo phiên bản mới</span>
                                                        </template>
                                                        <a-button
                                                            @click="createNewVersion(record)"
                                                            class="bg-success text-white"
                                                        >
                                                            <i class="bi bi-plus-circle"></i>
                                                        </a-button>
                                                    </a-tooltip>
                                                </a-space>
                                            </template>
                                            
                                        </template>
                                    </a-table>
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
                                            :disabled="loadingSendComment"
                                            >
                                            <span v-if="loadingSendComment">
                                                <i class="spinner-border spinner-border-sm me-2"></i>
                                                Đang gửi nhận xét...
                                            </span>
                                            <span v-else>
                                                <i class="bi bi-chat-square-dots me-2"></i>Gửi nhận xét
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
    </div>
</template>

<script>
import { 
    ref, 
    defineComponent, 
    computed, 
    watch, 
    onMounted, 
    nextTick,
    toRaw,
    shallowRef,
} from 'vue';
import { useMenu } from '@/stores/use-menu.js';
import { useRoute, useRouter } from 'vue-router';
import { useAuth } from '@/stores/use-auth.js';
import { useDocumentStore } from '@/stores/creator/document-store';
import PDFViewer from '@/components/PDFViewer.vue'
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { message, Modal } from 'ant-design-vue';
import NestedProgressSteps from '@/components/ProgressDocument.vue';
import axiosInstance from '@/lib/axios.js';

export default defineComponent({
    components: {
        PDFViewer,
        NestedProgressSteps,
    },
    setup() {
        dayjs.extend(relativeTime);
        const activeKey = ref('versions');
        const commentSection = ref(false);
        useMenu().onSelectedKeys(["creator-documents-detail"]);
        const documentStore = useDocumentStore();
        const authStore = useAuth();
        const user = authStore.user;

        const route = useRoute();
        const router = useRouter();
        const documents = ref([]);
        let documentData = ref([]);
        const pdfUrl = ref('')
        const process_of_document = ref(0);
        const document_types = ref([]);
        const document_comments = ref([]);
        const document_flow_steps = ref([]);
        const document_versions = shallowRef([]);
        const max_version = computed(() => {
            return document_versions.value.length > 0 ? document_versions.value[0].version + 1 : 1;
        });
        const document_steps = 
        [
            {
                title: 'Bước 1',
                description: 'Mô tả bước 1',
                status: 'approved', // hoặc 'rejected', 'in_review', 'waiting'
            },
            {
                title: 'Bước 2',
                description: 'Mô tả bước 2',
                status: 'in_review',
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
        onMounted(async () => {
            await documentStore.fetchDocuments(user.id);
            documents.value = documentStore.documents;
            const id = parseInt(route.params.id);
            documentData.value = documents.value.find(doc => doc.id === id) || null;

            await documentStore.getDocumentVersions(id);
            document_versions.value = toRaw(documentStore.current_document_versions);
            for (let i = 0; i < document_versions.value.length; i++) {
                document_versions.value[i].document_data = JSON.parse(document_versions.value[i].document_data);
            }
            console.log('Document versions: ', document_versions.value);

            await documentStore.fetchDocumentComments(id);
            document_comments.value = documentStore.document_comments;

            console.log(document_comments.value);
            console.log(documentData.value);

            pdfUrl.value = documentData.value.file_path;
            process_of_document.value = documentData.value.process / documentData.value.step_count * 100;

            await documentStore.fetchStepsByDocumentFlowId(documentData.value.document_flow_id);
            document_flow_steps.value = documentStore.current_document_flow_steps;
            console.log(document_flow_steps.value);

            await documentStore.fetchDocumentTypes();
            document_types.value = documentStore.document_types;
            console.log(document_types.value);
        });

        const comment = ref('');

        const API_BASE_URL = 'http://localhost:8000'

        const getAvatarUrl = (avatar) => {
            if (!avatar) return null
            return `${API_BASE_URL}/images/avatars/${avatar}`
        }

        const commentTextarea = ref(null)
        const btnCommentText = ref('Nhận xét');
        const handleClickComment = async () => {
            commentSection.value = !commentSection.value;
            if (activeKey.value !== 'comment') {
                activeKey.value = 'comment';
                btnCommentText.value = 'Văn bản';
                await nextTick();   
                commentTextarea.value.focus();
            } else {    
                activeKey.value = 'document';
                btnCommentText.value = 'Nhận xét';
            }
        };

        watch(activeKey, (newValue) => {
            if (newValue !== 'comment') {
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
                
                const downloadUrl = `http://localhost:8000/documents/${filePath}`;
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

        const loadingSendComment = ref(false);
        const handleSendComment = async () => {
            if (comment.value === '') {
                message.error('Vui lòng nhập bình luận');
                return;
            }

            loadingSendComment.value = true;
            try {
                const id = parseInt(route.params.id);
                await axiosInstance.post(`/api/documents/${id}/comments`, {
                    // creator_id: documentData.value.creator_id,
                    comment: comment.value,
                    // document_flow_step_id: parseInt(documentData.value['document_flow_step_id']),
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

        const randomAvatar = (id) => {
            if (id > 100 || id == null) {
                return `https://avatar.iran.liara.run/public`;
            }
            return `https://avatar.iran.liara.run/public/${id}`;
        };

        const handleFocusCommentInput = async () => {
            commentTextarea.value.focus();
        };

        const version_columns = [
            {
                title: 'Bản',
                dataIndex: 'version',
                key: 'version',
                width: 60,
                customRender: ({ version }) => version + 1,
                align: 'center',
            },
            {
                title: 'Tên văn bản',
                dataIndex: 'title',
                key: 'title',
                width: 200,
                customHeaderCell: () => {
                    return { style: { textAlign: 'center' } };
                }
            },
            {
                title: 'Loại văn bản',
                dataIndex: 'type',
                key: 'type',
                width: 150,
                align: 'center',
                responsive: ['xxl'],
            },
            {
                title: 'Trạng thái',
                dataIndex: 'status',
                key: 'status',
                width: 100,
                align: 'center',
            },
            {
                title: 'Ngày tạo',
                dataIndex: 'created_at',
                key: 'created_at',
                width: 150,
                align: 'center',
            },
            {
                title: 'Thao tác',
                key: 'action',
                width: 150,
                align: 'center',
                fixed: 'right',
            }
        ];

        const mapTypeIdToName = (typeId) => {
            const type = document_types.value.find(t => t.value === typeId);
            return type ? type.label : 'Không xác định';
        };

        const version_data = ref([]);
        const changeVersion = (record) => {
            console.log('Changing version to:', record);
            version_data.value = JSON.parse(JSON.stringify(record.document_data));
            console.log('Version data:', version_data.value);

        };

        const customRow = (record) => {
            return {
                onClick: () => {
                    changeVersion(record);
                },
                style: {
                    cursor: 'pointer'
                }
            };
        };

        const createNewVersion = (record) => {
            Modal.confirm({
                title: 'Tạo phiên bản mới',
                content: 'Bạn có chắc chắn muốn tạo phiên bản mới từ phiên bản này?',
                okText: 'Tạo mới',  
                cancelText: 'Hủy',
                onOk: () => {
                    try {
                        console.log(record.document_data);
                        record.document_data['version'] = record.status === 'draft' ? 0 : record.version;
                        documentStore.setCurrentDocumentData(record.document_data);
                        // let test = documentStore.getCurrentDocumentData();
                        // console.log('Giá trị test:', test);
                        // return;
                        router.push({
                            name: 'creator-documents-create',
                        });
                    } catch (error) {
                        console.error('Error creating new version:', error);
                        message.error('Có lỗi xảy ra khi tạo phiên bản mới');
                    }
                },
                onCancel: () => {
                    console.log('Create new version cancelled');
                }
            });
        };

        return {
            user,
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
            document_steps,
            document_flow_steps,
            document_versions,
            version_columns,
            max_version,

            getAvatarUrl,
            randomAvatar,
            handleClickComment,
            handleClickDownload,
            handleSendComment,
            handleFocusCommentInput,
            customRow,
            mapTypeIdToName,
            changeVersion,
            createNewVersion,
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

</style>