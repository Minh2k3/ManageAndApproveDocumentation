<template>
    <div class="container-fluid">
        <h2 class="fw-bold mb-3">Chi tiết văn bản</h2>

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
                            <a-tab-pane key="document" tab="Văn bản">
                                <div class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/${document.file_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Mở tệp trong tab mới
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div v-if="show_certificate" class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/certificates/${document.certificate_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Chứng chỉ số
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div v-if="show_certificate" class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/certificates/${certificate_file_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Văn bản đã ký số
                                            </a>
                                        </label>
                                    </div>
                                </div>    

                                <!-- Information Section -->
                                <div >
                                    <div class="col">
                                        <div class="row mb-3">
                                            <div class="d-flex justify-content-center">
                                                <span class="fs-5 ">Tên tài liệu:</span>
                                                &nbsp;
                                                <span class="fs-5 fw-bold">{{ document.title }}</span>
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
                            <a-tab-pane key="document" tab="Văn bản">
                                <div class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/${document.file_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Mở tệp trong tab mới
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div v-if="show_certificate" class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/certificates/${document.certificate_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Chứng chỉ số
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div v-if="show_certificate" class="row">
                                    <div class="col text-end mb-2 mb-xl-0 align-self-top ps-3 pt-1">
                                        <label>
                                            <a :href="`${apiUrl}/documents/certificates/${certificate_file_path}`" target="_blank" class="text-decoration-none fst-italic">
                                                Văn bản đã ký số
                                            </a>
                                        </label>
                                    </div>
                                </div>    

                                <!-- Information Section -->
                                <div >
                                    <div class="col">
                                        <div class="row mb-3">
                                            <div class="d-flex justify-content-center">
                                                <span class="fs-5 ">Tên tài liệu:</span>
                                                &nbsp;
                                                <span class="fs-5 fw-bold ">{{ document.title }}</span>
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
                                                        <span class="">{{ record.document_data['description'] ?? "Không có mô tả" }}</span>
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
        v-model:open="rejectVisible"
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

    <!-- Modal chi tiết -->
    <a-modal 
        v-model:open="detailVersion.visible" 
        width="480px" 
        :zIndex="10000"
        :footer="null"
        centered
        class="document-modal"
    >
        <div class="modal-content">
            <!-- Header with icon and title -->
            <div class="modal-header">
                <div class="header-content">
                    <span class="header-icon">📄</span>
                    <h3 class="modal-title">Thông tin văn bản</h3>
                </div>
            </div>

            <!-- Content -->
            <div class="modal-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">📝 Tiêu đề</div>
                        <div class="info-value">{{ detailVersion.title }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">📋 Mô tả</div>
                        <div class="info-value">{{ detailVersion.description ?? "Không có mô tả" }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-item half">
                            <div class="info-label">🏷️ Loại văn bản</div>
                            <div class="info-value">
                                <span class="document-type-badge">{{ detailVersion.type }}</span>
                            </div>
                        </div>

                        <div class="info-item half">
                            <div class="info-label">🔢 Phiên bản</div>
                            <div class="info-value">
                                <span class="version-badge">v{{ detailVersion.version }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">📎 Tệp đính kèm</div>
                        <div class="info-value">
                            <a 
                                :href="`${apiUrl}/documents/${detailVersion.file_path}`" 
                                target="_blank" 
                                class="file-link"
                            >
                                📂 Xem tệp 🔗
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <a-button 
                    type="primary" 
                    @click="detailVersion.visible = false"
                    class="close-button"
                >
                    Đóng
                </a-button>
            </div>
        </div>
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
    nextTick,
    shallowRef,
    toRaw
} from 'vue';
import { useMenu } from '@/stores/use-menu.js';
import { useRoute } from 'vue-router';
import { useAuth } from '@/stores/use-auth.js';
import { useDocumentStore } from '@/stores/approver/document-store';
import {useCertificateStore} from '@/stores/approver/certificate-store';
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
        const activeKey = ref('document');
        const commentSection = ref(false);
        useMenu().onSelectedKeys(["approver-documents-detail"]);
        const documentStore = useDocumentStore();
        const certificateStore = useCertificateStore();
        const authStore = useAuth();
        const user = authStore.user;

        const route = useRoute();
        const documentData = ref([]);
        const pdfUrl = ref('')
        const document_types = ref([]);
        const document_comments = ref([]);
        const document_flow_steps = ref([]);
        const document_versions = shallowRef([]);
        const max_version = computed(() => {
            return document_versions.value.length > 0 ? document_versions.value[0].version + 1 : 1;
        });
        const show_certificate = ref(false);
        const certificate_file_path = ref('');

        const document_id = parseInt(route.params.id);
        onMounted(async () => {
            const from_me = route.query.from_me === '1';
            await documentStore.fetchDocumentById(document_id, from_me);
            documentData.value = documentStore.current_document;
            console.log('Document Data:', documentData.value);

            await documentStore.getDocumentVersions(document_id);
            document_versions.value = toRaw(documentStore.current_document_versions);
            for (let i = 0; i < document_versions.value.length; i++) {
                document_versions.value[i].document_data = JSON.parse(document_versions.value[i].document_data);
            }
            console.log('Document versions: ', document_versions.value);

            pdfUrl.value = documentData.value.file_path;

            await documentStore.fetchDocumentComments(document_id);
            document_comments.value = documentStore.document_comments;

            await documentStore.fetchStepsByDocumentFlowId(documentData.value.document_flow_id);
            document_flow_steps.value = documentStore.current_document_flow_steps;
            if (document_flow_steps.value.is_completed) {
                await documentStore.createCertificate(document_id);
                show_certificate.value = true;

                const responseCertificate = await certificateStore.findCertificateByDocumentId(document_id);
                console.log('Certificate found:', responseCertificate);
                certificate_file_path.value = responseCertificate ? responseCertificate.file_path : '';
            }

            await documentStore.fetchDocumentTypes();
            document_types.value = documentStore.document_types;
            console.log(document_types.value);            
        });

        const comment = ref('');

        const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

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
                console.log('Approve response version:', document_versions.value[0].id);
                // return;
                await documentStore.signDocument(document_versions.value[0].id, step_id, document_id);
                await documentStore.approveDocument(step_id);
                message.success('Bạn vừa đồng ý phê duyệt');
                window.location.reload();
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
                window.location.reload();            
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

        const loadingSendComment = ref(false);
        const handleSendComment = async () => {
            if (comment.value === '') {
                message.error('Vui lòng nhập bình luận');
                return;
            }

            loadingSendComment.value = true;
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
            } finally {
                loadingSendComment.value = false;
                commentTextarea.value.focus();
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
        const detailVersion = ref({
            visible: false,
            title: '',
            description: '',
            type: '',
            version: 0,
            file_path: ''
        });
        const changeVersion = (record) => {
            console.log('Changing version to:', record);
            version_data.value = JSON.parse(JSON.stringify(record.document_data));
            console.log('Version data:', version_data.value);
            detailVersion.value = {
                visible: true,
                title: version_data.value.title,
                description: version_data.value.description,
                type: mapTypeIdToName(version_data.value.document_type_id),
                version: record.version,
                file_path: version_data.value.file_path
            };
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
                            name: 'approver-documents-create',
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
            apiUrl: API_BASE_URL,
            user,
            document: documentData,
            pdfUrl,
            activeKey,
            commentSection,
            comment,
            document_comments,
            dayjs,
            btnCommentText,
            commentTextarea,
            loadingSendComment,
            isDownloading,
            btnApproveDisabled,
            btnRejectDisabled,
            rejectVisible,
            reasonReject,
            document_flow_steps,
            show_certificate,
            certificate_file_path,

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
            handleFocusCommentInput,

            version_columns,
            max_version,
            document_versions,
            detailVersion,
            mapTypeIdToName,
            changeVersion,
            customRow,
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

.document-modal :deep(.ant-modal-content) {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.document-modal :deep(.ant-modal-body) {
    padding: 0;
}

.modal-content {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modal-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    padding: 16px 24px;
    text-align: center;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.header-icon {
    font-size: 20px;
}

.modal-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.modal-body {
    padding: 20px;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-row {
    display: flex;
    gap: 12px;
}

.info-item {
    background: white;
    border-radius: 8px;
    padding: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}

.info-item.half {
    flex: 1;
}

.info-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.info-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 4px;
    font-size: 12px;
}

.info-value {
    color: #1e293b;
    font-size: 14px;
}

.document-type-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.version-badge {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.file-link {
    display: inline-block;
    padding: 6px 12px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.file-link:hover {
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}

.modal-footer {
    padding: 16px 20px;
    text-align: center;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.close-button {
    background: #6b7280;
    border: none;
    border-radius: 6px;
    padding: 6px 20px;
    font-size: 14px;
}

.close-button:hover {
    background: #4b5563;
}

/* Responsive design */
@media (max-width: 768px) {
    .modal-header {
        padding: 12px 16px;
    }
    
    .modal-body {
        padding: 16px;
    }
    
    .modal-footer {
        padding: 12px 16px;
    }
    
    .info-item {
        padding: 10px;
    }
    
    .info-row {
        flex-direction: column;
        gap: 8px;
    }
}
</style>