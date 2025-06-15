<template>
    <a-card class="" style="width: 100%">
        <div class="row">
            <div class="col-12">
                <h1>Quản lý chữ ký</h1>
                <p>Code nhanh còn nghỉ khỏe, đi ăn chơi xả láng</p>
            </div>
        </div>

        <a-tabs 
            v-model:activeKey="activeKey" 
            type="card"
            class="row mt-3"
            >
            <a-tab-pane key="1" tab="Chữ ký người dùng">
                <div class="row mt-2">
                    <div class="col">
                        <div class="border border-1 border-primary rounded-2 bg-light p-3">
                            <div class="row">
                                <span class="text-primary">Tổng số chữ ký</span>
                            </div>
                            <div class="row">
                                <span class="fw-bold fs-4 text-primary">{{ totalUserSignatures }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="border border-1 border-warning rounded-2 bg-light p-3">
                            <div class="row">
                                <span class="text-warning">Đang xin cấp</span>
                            </div>
                            <div class="row">
                                <span class="fw-bold fs-4 text-warning">{{ renewalCount }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="border border-1 border-danger rounded-2 bg-light p-3">
                            <div class="row">
                                <span class="text-danger">Bị thu hồi</span>
                            </div>
                            <div class="row">
                                <span class="fw-bold fs-4 text-danger">{{ revokedCount }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="border border-1 border-dark rounded-2 bg-light p-3">
                            <div class="row">
                                <span class="text-dark">Hết hạn</span>
                            </div>
                            <div class="row">
                                <span class="fw-bold fs-4 text-dark">{{ expiredCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary mt-3" @click="showGenerateSignatureUserModal = true">
                            <i class="fas fa-plus"></i> Tạo chữ ký mới
                        </button>
                        <button class="btn btn-secondary mt-3 ms-2" @click="exportAllSignatures">
                            <i class="fas fa-download"></i> Export tất cả
                        </button>
                        <button class="btn btn-info mt-3 ms-2" @click="showImportModal = true">
                            <i class="fas fa-upload"></i> Import chữ ký
                        </button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="signature-list-container">
                        <!-- Header với Search và Filter -->
                        <div class="mb-4 row">
                            <div class="col">
                            <a-input-search
                                v-model:value="searchText"
                                placeholder="Tìm kiếm theo tên, email..."
                                allow-clear
                                @search="handleSearch"
                            />
                            </div>
                            <div class="col">
                            <a-select
                                v-model:value="statusFilter"
                                placeholder="Lọc theo trạng thái"
                                allow-clear
                                style="width: 100%"
                                @change="handleFilter"
                            >
                                <a-select-option value="renewal">Xin cấp lại</a-select-option>
                                <a-select-option value="active">Đang sử dụng</a-select-option>
                                <a-select-option value="revoked">Bị thu hồi</a-select-option>
                                <a-select-option value="expired">Hết hạn</a-select-option>
                            </a-select>
                            </div>
                            <div class="col">
                                <a-button type="primary" @click="resetFilters">
                                    Đặt lại
                                </a-button>
                            </div>
                        </div>

                        <!-- Bảng danh sách -->
                        <a-table
                            :columns="columns"
                            :data-source="filteredData"
                            :pagination="pagination"
                            :loading="loading"
                            :scroll="{ x: 1200 }"
                            row-key="id"
                            @change="handleTableChange"
                            >
                            <template #headerCell="{ column }">
                                <span class="text-uppercase">{{ column.title }}</span>
                            </template>

                            <template #bodyCell="{ column, index, record }">
                                <template v-if="column.key === 'user'">
                                    <div class="user-info">
                                    <a-avatar :size="40" class="mr-3" :src="getAvatarUrl(record.user.avatar, record.user.id)">
                                        <!-- {{ getCharOfName(record.user.name) }} -->
                                    </a-avatar>
                                    <div>
                                        <div class="font-weight-bold">{{ record.user.name }}</div>
                                        <div class="text-muted small">{{ record.user.email }}</div>
                                        <div class="text-muted small">{{ record.user.department }}</div>
                                    </div>
                                    </div>
                                </template>

                                <!-- Cột chữ ký -->
                                <template v-if="column.key === 'signature'">
                                    <div class="signature-info">
                                    <div class="font-weight-bold text-primary">{{ record.signature.name }}</div>
                                    <div class="text-muted small">
                                        Public Key: {{ record.signature.publicKey.substring(0, 20) }}...
                                    </div>
                                    </div>
                                </template>

                                <!-- Cột trạng thái -->
                                <template v-if="column.key === 'status'">
                                    <a-tag v-if="record.status === 'active'" color="green">
                                        <span>
                                            Đang sử dụng
                                        </span>
                                    </a-tag>

                                    <a-tag v-else-if="record.status === 'renewal'" color="orange">
                                        <span>
                                            Xin cấp lại
                                        </span>
                                    </a-tag>

                                    <a-tag v-else-if="record.status === 'revoked'" color="red">
                                        <span>
                                            Bị thu hồi
                                        </span>
                                    </a-tag>

                                    <a-tag v-else-if="record.status === 'expired'" color="gray">
                                        <span>
                                            Hết hạn
                                        </span>
                                    </a-tag>
                                </template>

                                <template v-if="column.key === 'used_count'">
                                    <span class="text-primary">{{ record.used_count }}</span>
                                    <span> lần</span>
                                </template>


                                <!-- Cột ngày cấp -->
                                <template v-if="column.key === 'createdAt'">
                                    <span class="">
                                        {{ record.issued_at }} 
                                    </span>
                                </template>

                                <!-- Cột ngày hết hạn -->
                                <template v-if="column.key === 'expires_at'">
                                    <span class="">
                                        {{ record.expires_at }}
                                    </span>
                                </template>

                                <!-- Cột thao tác -->
                                <template v-if="column.key === 'action'">
                                    <a-space class="d-flex justify-content-center gap-3">
                                        <a-tooltip>
                                            <template #title>
                                                <span class="">Xem chi tiết</span>
                                            </template>
                                            <a-button 
                                                @click="viewDetail(record, index)"
                                                class="bg-primary text-white"
                                                >
                                                <i class="bi bi-eye"></i>
                                            </a-button>
                                        </a-tooltip>

                                        <a-popconfirm v-if="record.status === 'active'" placement="topRight" ok-text="Yes" cancel-text="No" @confirm="handleConfirmRevoke(record)">
                                            <template #title>
                                                <span class="">Bạn có chắc chắn thu hồi chữ ký này?</span>
                                            </template>
                                            <a-button
                                                class="bg-danger text-white"
                                                @click.stop
                                            >
                                                <i class="bi bi-dash-circle"></i>
                                            </a-button>
                                        </a-popconfirm>
                                    </a-space>
                                </template>

                            </template>
                        
                            
                        </a-table>
                    </div>
                </div>
            </a-tab-pane>

            <a-tab-pane key="2" tab="Chứng chỉ tổ chức" force-render>
                <!-- Root CA Management Content -->
                <div class="root-ca-content">
                    <!-- Thống kê Root CA -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="border border-1 border-primary rounded-2 bg-light p-3">
                                <div class="row">
                                    <span class="text-primary">Root CA đang hoạt động</span>
                                </div>
                                <div class="row">
                                    <span class="fw-bold fs-4 text-primary">{{ activeRootCAs }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-1 border-success rounded-2 bg-light p-3">
                                <div class="row">
                                    <span class="text-success">Chứng chỉ con đã cấp</span>
                                </div>
                                <div class="row">
                                    <span class="fw-bold fs-4 text-success">{{ totalIssuedCerts }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-1 border-warning rounded-2 bg-light p-3">
                                <div class="row">
                                    <span class="text-warning">Sắp hết hạn</span>
                                </div>
                                <div class="row">
                                    <span class="fw-bold fs-4 text-warning">{{ expiringSoon }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-1 border-danger rounded-2 bg-light p-3">
                                <div class="row">
                                    <span class="text-danger">Đã thu hồi</span>
                                </div>
                                <div class="row">
                                    <span class="fw-bold fs-4 text-danger">{{ revokedCerts }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nút thao tác Root CA -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <button class="btn btn-primary me-2" @click="showGenerateModal = true">
                                <i class="fas fa-plus"></i> Tạo Root CA mới
                            </button>
                            <button class="btn btn-info me-2" @click="exportAllCerts">
                                <i class="fas fa-download"></i> Export tất cả
                            </button>
                            <button class="btn btn-secondary" @click="showImportModal = true">
                                <i class="fas fa-upload"></i> Import Root CA
                            </button>
                        </div>
                    </div>

                    <!-- Bảng Root CA -->
                    <div class="row">
                        <div class="col-12">
                            <a-table
                                :columns="rootCAColumns"
                                :data-source="rootCAs"
                                :pagination="false"
                                :loading="rootCALoading"
                                row-key="id"
                                class="root-ca-table"
                            >
                                <!-- Cột thông tin chứng chỉ -->
                                <template #certificate="{ record }">
                                    <div class="cert-info">
                                        <div class="fw-bold text-primary">{{ record.subject.commonName }}</div>
                                        <div class="text-muted small">Organization: {{ record.subject.organization }}</div>
                                        <div class="text-muted small">Serial: {{ record.serialNumber }}</div>
                                        <div class="text-muted small">Thumbprint: {{ record.thumbprint.substring(0, 20) }}...</div>
                                    </div>
                                </template>

                                <!-- Cột trạng thái Root CA -->
                                <template #rootStatus="{ record }">
                                    <span :class="getRootStatusClass(record.status)">
                                        <i :class="getRootStatusIcon(record.status)"></i>
                                        {{ getRootStatusText(record.status) }}
                                    </span>
                                </template>

                                <!-- Cột thời hạn -->
                                <template #validity="{ record }">
                                    <div class="validity-info">
                                        <div class="small"><strong>Từ:</strong> {{ formatDate(record.validFrom) }}</div>
                                        <div class="small"><strong>Đến:</strong> {{ formatDate(record.validTo) }}</div>
                                        <div :class="getExpiryClass(record.validTo)">
                                            <i class="fas fa-clock"></i>
                                            {{ getExpiryText(record.validTo) }}
                                        </div>
                                    </div>
                                </template>

                                <!-- Cột thống kê -->
                                <template #stats="{ record }">
                                    <div class="stats-info">
                                        <div class="small text-success">
                                            <i class="fas fa-certificate"></i>
                                            Đã cấp: <strong>{{ record.issuedCerts }}</strong>
                                        </div>
                                        <div class="small text-danger">
                                            <i class="fas fa-ban"></i>
                                            Đã thu hồi: <strong>{{ record.revokedCerts }}</strong>
                                        </div>
                                    </div>
                                </template>

                                <!-- Cột thao tác Root CA -->
                                <template #rootAction="{ record }">
                                    <a-dropdown :trigger="['click']" placement="bottomRight">
                                            <a-menu @click="handleRootMenuClick($event, record)">
                                                <a-menu-item key="view">
                                                    👁️ Xem chi tiết
                                                </a-menu-item>
                                                <a-menu-item key="export">
                                                    📥 Export
                                                </a-menu-item>
                                                <a-menu-item key="renew" :disabled="record.status !== 'active'">
                                                    🔄 Gia hạn
                                                </a-menu-item>
                                                <a-menu-item key="revoke" :disabled="record.status !== 'active'">
                                                    🚫 Thu hồi
                                                </a-menu-item>
                                                <a-menu-divider />
                                                <a-menu-item key="delete" class="text-danger">
                                                    🗑️ Xóa vĩnh viễn
                                                </a-menu-item>
                                            </a-menu>
                                    </a-dropdown>
                                </template>
                            </a-table>
                        </div>
                    </div>
                </div>
            </a-tab-pane>
        </a-tabs>

        <!-- Modal cảnh báo xóa Root CA - Cấp 1 -->
        <a-modal
            v-model:open="showDeleteWarning1"
            title="⚠️ Cảnh báo: Xóa Root CA"
            :width="600"
            :footer="null"
            :closable="false"
        >
            <div class="alert alert-danger">
                <h5><i class="fas fa-exclamation-triangle"></i> Thao tác CỰC KỲ NGUY HIỂM!</h5>
                <p>Bạn đang cố gắng xóa Root CA: <strong>{{ selectedCA?.subject?.commonName }}</strong></p>
                <p>Điều này sẽ ảnh hưởng nghiêm trọng đến toàn bộ hệ thống!</p>
            </div>
            
            <div class="text-center mt-4">
                <button class="btn btn-secondary me-3" @click="cancelDelete">
                    <i class="fas fa-times"></i> Hủy bỏ
                </button>
                <button class="btn btn-warning" @click="proceedToWarning2">
                    <i class="fas fa-forward"></i> Tôi hiểu, tiếp tục
                </button>
            </div>
        </a-modal>

        <!-- Modal cảnh báo xóa Root CA - Cấp 2 -->
        <a-modal
            v-model:open="showDeleteWarning2"
            title="🚨 Xác nhận tác động hệ thống"
            :width="700"
            :footer="null"
            :closable="false"
        >
            <div class="alert alert-danger">
                <h5>Tác động khi xóa Root CA này:</h5>
                <ul class="mt-3">
                    <li><strong>{{ selectedCA?.issuedCerts || 0 }}</strong> chứng chỉ con sẽ mất hiệu lực</li>
                    <li>Tất cả chữ ký đã tạo bằng các chứng chỉ con sẽ <strong>KHÔNG THỂ XÁC THỰC</strong></li>
                    <li>Các văn bản đã ký có thể mất giá trị pháp lý</li>
                    <li>Hệ thống có thể ngưng hoạt động cho đến khi tạo Root CA mới</li>
                </ul>
            </div>

            <div class="alert alert-info">
                <h6>Danh sách chứng chỉ con gần đây:</h6>
                <ul class="small">
                    <li v-for="cert in recentChildCerts" :key="cert.id">
                        {{ cert.commonName }} - {{ cert.email }} ({{ formatDate(cert.issuedDate) }})
                    </li>
                </ul>
            </div>
            
            <div class="text-center mt-4">
                <button class="btn btn-secondary me-3" @click="backToWarning1">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
                <button class="btn btn-danger" @click="proceedToWarning3">
                    <i class="fas fa-forward"></i> Tôi chấp nhận rủi ro
                </button>
            </div>
        </a-modal>

        <!-- Modal cảnh báo xóa Root CA - Cấp 3 -->
        <a-modal
            v-model:open="showDeleteWarning3"
            title="🔐 Xác thực quyền admin"
            :width="500"
            :footer="null"
            :closable="false"
        >
            <div class="alert alert-warning">
                <p><strong>Bước cuối cùng:</strong> Nhập thông tin xác thực để xác nhận bạn là admin có thẩm quyền</p>
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu admin:</label>
                <input 
                    type="password" 
                    class="form-control" 
                    v-model="adminPassword"
                    placeholder="Nhập mật khẩu admin"
                    @keyup.enter="proceedToFinalWarning"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Lý do xóa:</label>
                <textarea 
                    class="form-control" 
                    rows="3" 
                    v-model="deleteReason"
                    placeholder="Mô tả lý do tại sao cần xóa Root CA này"
                ></textarea>
            </div>

            <div class="form-check mb-3">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="confirmUnderstand"
                    v-model="confirmUnderstand"
                >
                <label class="form-check-label text-danger" for="confirmUnderstand">
                    <strong>Tôi hiểu rằng thao tác này KHÔNG THỂ HOÀN TÁC</strong>
                </label>
            </div>
            
            <div class="text-center mt-4">
                <button class="btn btn-secondary me-3" @click="backToWarning2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
                <button 
                    class="btn btn-danger" 
                    @click="proceedToFinalWarning"
                    :disabled="!adminPassword || !deleteReason || !confirmUnderstand"
                >
                    <i class="fas fa-key"></i> Xác thực và tiếp tục
                </button>
            </div>
        </a-modal>

        <!-- Modal cảnh báo cuối cùng - Cấp 4 -->
        <a-modal
            v-model:open="showFinalWarning"
            title="⏰ Countdown cuối cùng"
            :width="450"
            :footer="null"
            :closable="false"
        >
            <div class="text-center">
                <div class="alert alert-danger">
                    <h4><i class="fas fa-skull-crossbones"></i> CẢNH BÁO CUỐI CÙNG</h4>
                    <p>Root CA sẽ bị xóa vĩnh viễn sau:</p>
                    <div class="display-1 text-danger fw-bold">{{ countdown }}</div>
                    <div class="progress mt-3">
                        <div 
                            class="progress-bar bg-danger" 
                            role="progressbar" 
                            :style="{ width: progressPercent + '%' }"
                        ></div>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-success me-3" @click="cancelDelete" :disabled="countdown === 0">
                        <i class="fas fa-shield-alt"></i> HỦY BỎ AN TOÀN
                    </button>
                    <button 
                        class="btn btn-danger" 
                        @click="confirmDelete"
                        :disabled="countdown > 0"
                    >
                        <i class="fas fa-trash"></i> XÓA VĨNH VIỄN
                    </button>
                </div>
            </div>
        </a-modal>

        <!-- Modal tạo Root CA mới -->
        <a-modal
            v-model:open="showGenerateModal"
            title="Tạo Root CA mới"
            :width="600"
            @ok="generateNewRootCA"
            ok-text="Tạo Root CA"
            cancel-text="Hủy"
        >
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Common Name (CN):</label>
                        <input type="text" class="form-control" v-model="newCA.commonName" placeholder="Trường Đại học Thủy lợi Root CA">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Organization (O):</label>
                        <input type="text" class="form-control" v-model="newCA.organization" placeholder="Truong Dai hoc Thuy loi">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Country (C):</label>
                        <input type="text" class="form-control" v-model="newCA.country" placeholder="VN" maxlength="2">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Thời hạn (năm):</label>
                        <select class="form-select" v-model="newCA.validityYears">
                            <option value="5">5 năm</option>
                            <option value="10">10 năm</option>
                            <option value="20">20 năm</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Key Size:</label>
                <select class="form-select" v-model="newCA.keySize">
                    <option value="2048">2048 bit</option>
                    <option value="4096">4096 bit (Khuyến nghị)</option>
                </select>
            </div>
        </a-modal>

        <!-- Modal cấp chữ ký cho người dùng -->
        <a-modal
            v-model:open="showGenerateSignatureUserModal"
            title="Cấp chữ ký cho người dùng"
            :width="800"
            @ok="generateNewUserSignature"
            ok-text="Cấp chữ ký"
            cancel-text="Hủy"
            :confirm-loading="isGenerating"
            :z-index="3103"
        >
            <div class="signature-grant-form">
                <!-- Lựa chọn loại cấp chữ ký -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Chọn loại cấp chữ ký:</label>
                    <a-radio-group v-model:value="grantType" class="d-block mt-2">
                        <a-radio value="user" class="d-block mb-2">
                            <span class="ms-2">Cấp cho người dùng cụ thể (theo ID)</span>
                        </a-radio>
                        <a-radio value="department" class="d-block mb-2">
                            <span class="ms-2">Cấp cho tổ chức/khoa</span>
                        </a-radio>
                        <a-radio value="all" class="d-block">
                            <span class="ms-2">Cấp cho tất cả người dùng</span>
                        </a-radio>
                    </a-radio-group>
                </div>

                <!-- Form cấp cho người dùng cụ thể -->
                <div v-if="grantType === 'user'" class="user-specific-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tìm kiếm người dùng:</label>
                                <a-select
                                    v-model:value="selectedUserId"
                                    show-search
                                    placeholder="Nhập tên hoặc email để tìm kiếm"
                                    :filter-option="false"
                                    :loading="userSearchLoading"
                                    @search="handleUserSearch"
                                    @change="handleUserSelect"
                                    style="width: 100%"
                                    :dropdown-style="{ 
                                        position: 'absolute',
                                        zIndex: 10000,
                                        background: 'white',
                                        border: '1px solid #ccc'
                                    }"
                                >
                                    <a-select-option 
                                        v-for="user in searchedUsers" 
                                        :key="user.id" 
                                        :value="user.id"
                                    >
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="">{{ user.name }}</div>
                                            </div>
                                        </div>
                                    </a-select-option>
                                </a-select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hoặc nhập User ID:</label>
                                <a-input 
                                    v-model:value="manualUserId" 
                                    placeholder="Nhập ID người dùng"
                                    @change="handleManualUserIdChange"
                                />
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hiển thị thông tin người dùng đã chọn -->
                    <div v-if="selectedUserInfo" class="selected-user-info">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <a-avatar :size="40" class="me-3">
                                    {{ getCharOfName(selectedUserInfo.name) }}
                                </a-avatar>
                                <div>
                                    <div class="fw-bold">{{ selectedUserInfo.name }} ({{ selectedUserInfo.roll }})</div>
                                    <div class="text-muted">{{ selectedUserInfo.email }}</div>
                                    <div class="text-muted small">{{ selectedUserInfo.department }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form cấp cho tổ chức -->
                <div v-if="grantType === 'department'" class="department-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Chọn tổ chức/khoa:</label>
                                <a-select
                                    v-model:value="selectedDepartment"
                                    placeholder="Chọn tổ chức"
                                    style="width: 100%"
                                    @change="handleDepartmentSelect"
                                    :dropdown-style="{ 
                                        position: 'absolute',
                                        zIndex: 10000,
                                        background: 'white',
                                        border: '1px solid #ccc'
                                    }"                                    
                                >
                                    <a-select-option 
                                        v-for="dept in departments" 
                                        :key="dept.id" 
                                        :value="dept.id"
                                    >
                                        {{ dept.name }}
                                    </a-select-option>
                                </a-select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Số lượng người dùng:</label>
                                <a-input 
                                    :value="departmentUserCount" 
                                    disabled
                                    class="text-center fw-bold"
                                />
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hiển thị danh sách người dùng trong tổ chức -->
                    <div v-if="departmentUsers.length > 0" class="department-users">
                        <label class="form-label">Danh sách người dùng sẽ được cấp chữ ký:</label>
                        <div class="user-preview-list">
                            <div 
                                v-for="user in departmentUsers.slice(0, 5)" 
                                :key="user.id"
                                class="user-preview-item"
                            >
                                <a-avatar :size="24" class="me-2">{{ getCharOfName(user.name) }}</a-avatar>
                                <span>{{ user.name }} - {{ user.email }}</span>
                            </div>
                            <div v-if="departmentUsers.length > 5" class="text-muted small">
                                ... và {{ departmentUsers.length - 5 }} người dùng khác
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form cấp cho tất cả -->
                <div v-if="grantType === 'all'" class="all-users-form">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Cảnh báo</h6>
                        <p class="mb-2">Bạn đang chọn cấp chữ ký cho <strong>TẤT CẢ</strong> người dùng trong hệ thống.</p>
                        <p class="mb-0">Thao tác này sẽ tạo chữ ký cho <strong>{{ totalUsersInSystem }}</strong> người dùng.</p>
                    </div>
                    
                    <div class="confirmation-section">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="confirmGrantAll"
                                v-model="confirmGrantAll"
                            >
                            <label class="form-check-label fw-bold text-danger" for="confirmGrantAll">
                                Tôi xác nhận muốn cấp chữ ký cho tất cả người dùng
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Cấu hình chữ ký -->
                <!-- <div class="signature-config mt-4">
                    <h6 class="fw-bold">Cấu hình chữ ký:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Thuật toán mã hóa:</label>
                                <a-select v-model:value="signatureConfig.algorithm" style="width: 100%">
                                    <a-select-option value="RSA-2048">RSA 2048 bit</a-select-option>
                                    <a-select-option value="RSA-4096">RSA 4096 bit (Khuyến nghị)</a-select-option>
                                    <a-select-option value="ECDSA-P256">ECDSA P-256</a-select-option>
                                </a-select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Thời hạn hiệu lực:</label>
                                <a-select v-model:value="signatureConfig.validityPeriod" style="width: 100%">
                                    <a-select-option value="1">1 năm</a-select-option>
                                    <a-select-option value="2">2 năm</a-select-option>
                                    <a-select-option value="3">3 năm (Khuyến nghị)</a-select-option>
                                    <a-select-option value="5">5 năm</a-select-option>
                                </a-select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Loại chữ ký:</label>
                                <a-select v-model:value="signatureConfig.signatureType" style="width: 100%">
                                    <a-select-option value="document">Ký văn bản</a-select-option>
                                    <a-select-option value="email">Ký email</a-select-option>
                                    <a-select-option value="universal">Đa năng</a-select-option>
                                </a-select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mức độ bảo mật:</label>
                                <a-select v-model:value="signatureConfig.securityLevel" style="width: 100%">
                                    <a-select-option value="standard">Tiêu chuẩn</a-select-option>
                                    <a-select-option value="high">Cao</a-select-option>
                                    <a-select-option value="enterprise">Doanh nghiệp</a-select-option>
                                </a-select>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Tùy chọn thông báo
                <div class="notification-options mt-3">
                    <h6 class="fw-bold">Tùy chọn thông báo:</h6>
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="emailNotification"
                            v-model="notificationOptions.email"
                        >
                        <label class="form-check-label" for="emailNotification">
                            Gửi email thông báo cho người dùng
                        </label>
                    </div>
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="smsNotification"
                            v-model="notificationOptions.sms"
                        >
                        <label class="form-check-label" for="smsNotification">
                            Gửi SMS thông báo (nếu có số điện thoại)
                        </label>
                    </div>
                </div> -->

                <!-- Tóm tắt -->
                <!-- <div class="summary-section mt-4">
                    <div class="alert alert-light border">
                        <h6 class="fw-bold mb-3">Tóm tắt:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="summary-item">
                                    <span class="text-muted">Loại cấp:</span>
                                    <span class="fw-bold ms-2">{{ getGrantTypeText() }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="text-muted">Số lượng:</span>
                                    <span class="fw-bold ms-2 text-primary">{{ getTargetUserCount() }} người dùng</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="summary-item">
                                    <span class="text-muted">Thuật toán:</span>
                                    <span class="fw-bold ms-2">{{ signatureConfig.algorithm }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="text-muted">Thời hạn:</span>
                                    <span class="fw-bold ms-2">{{ signatureConfig.validityPeriod }} năm</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </a-modal>

    </a-card>
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { MoreOutlined } from '@ant-design/icons-vue'
import { useCertificateStore } from '@/stores/admin/certificate-store';
import { useAuth } from '@/stores/use-auth.js';
import {useDepartmentStore} from '@/stores/admin/department-store.js';
import { useUserStore } from '@/stores/admin/user-store';
import { message } from 'ant-design-vue';
import { 
    ref, 
    defineComponent, 
    computed, 
    watch, 
    onMounted, 
    onUnmounted,
} from 'vue';

export default defineComponent({
    components: {
        MoreOutlined
    },
    setup() {
        useMenu().onSelectedKeys(["admin-signatures"]);
        const authStore = useAuth();
        const certificateStore = useCertificateStore();
        const departmentStore = useDepartmentStore();
        const userStore = useUserStore();
        
        const activeKey = ref("1");

        // User signatures data
        const loading = ref(false)
        const searchText = ref('')
        const statusFilter = ref(undefined)
        const typeFilter = ref(undefined)

        const showGenerateSignatureUserModal = ref(false)
        
        // Chữ ký người dùng
        const originalData = ref([]);
        const departments = ref([]);
        const allUsers = ref([]);
        onMounted(async() => {
            await certificateStore.fetchCertificates();
            originalData.value = certificateStore.certificates;
            console.log("Đã fetch xong");

            await departmentStore.fetchDepartments();
            departments.value = departmentStore.departments.filter(dept => dept.group !== 'Khác');
            console.log("Đã fetch xong departments: ", departments.value);

            await userStore.fetchUsers();
            allUsers.value = userStore.users;
            console.log("Đã fetch xong users: ", allUsers.value);
        });

        const grantType = ref('user'); // 'user', 'department', 'all'
        const selectedUserId = ref(null);
        const manualUserId = ref('');
        const selectedUserInfo = ref(null);
        const selectedDepartment = ref(null);
        const selectedDepartmentName = ref('');
        const departmentUserCount = ref(0);
        const departmentUsers = ref([]);
        const confirmGrantAll = ref(false);
        const totalUsersInSystem = computed(() => allUsers.value.length);
        const isGenerating = ref(false);

        // Search states
        const userSearchLoading = ref(false);
        const searchedUsers = ref([]);

        // Functions to Modal Issue User Certificate
        const handleUserSearch = (searchValue) => {
            if (!searchValue) {
                searchedUsers.value = []
                return
            }
            
            userSearchLoading.value = true
            
            // Simulate API call
            setTimeout(() => {
                searchedUsers.value = allUsers.value.filter(user => 
                    user.name.toLowerCase().includes(searchValue.toLowerCase()) ||
                    user.email.toLowerCase().includes(searchValue.toLowerCase())
                )
                userSearchLoading.value = false
            }, 300)
        }

        const handleUserSelect = (userId) => {
            selectedUserInfo.value = allUsers.value.find(user => user.id === userId)
            manualUserId.value = userId.toString()
        }

        const handleManualUserIdChange = () => {
            if (manualUserId.value) {
                const userId = parseInt(manualUserId.value)
                selectedUserInfo.value = allUsers.value.find(user => user.id === userId)
                selectedUserId.value = userId
            } else {
                selectedUserInfo.value = null
                selectedUserId.value = null
            }
        }

        const handleDepartmentSelect = () => {
            departmentUsers.value = allUsers.value.filter(user => user.department_id === selectedDepartment.value);
            selectedDepartmentName.value = departments.value.find(dept => dept.id === selectedDepartment.value)?.name || '';
            console.log("Department users: ", departmentUsers);
            departmentUserCount.value = departmentUsers.value.length;
        }

        const getGrantTypeText = () => {
            const typeMap = {
                user: 'Người dùng cụ thể',
                department: 'Theo tổ chức',
                all: 'Tất cả người dùng'
            }
            return typeMap[grantType.value]
        }

        const getTargetUserCount = () => {
            if (grantType.value === 'user') {
                return selectedUserInfo.value ? 1 : 0;
            } else if (grantType.value === 'department') {
                return departmentUserCount.value;
            } else {
                return totalUsersInSystem.value;
            }
        }

        // Cập nhật hàm generateNewUserSignature
        const generateNewUserSignature = async () => {
            // Validation
            if (grantType.value === 'user' && !selectedUserInfo.value) {
                message.error('Vui lòng chọn người dùng', 2);
                return;
            }
            
            if (grantType.value === 'department' && !selectedDepartment.value) {
                message.error('Vui lòng chọn tổ chức', 2);
                return;
            }
            
            if (grantType.value === 'all' && !confirmGrantAll.value) {
                message.error('Vui lòng xác nhận cấp chữ ký cho tất cả người dùng', 2);
                return;
            }
            
            isGenerating.value = true
            
            // const requestData = {
            //     grantType: grantType.value,
            //     targetUserId: grantType.value === 'user' ? selectedUserId.value : null,
            //     targetDepartmentId: grantType.value === 'department' ? selectedDepartment.value : null,
            // }
            
            // console.log('Request data:', requestData);
            try {
                
                if (grantType.value === 'user') {
                    const response = await certificateStore.issueCertificate(selectedUserInfo.value.id);
                    if (response.success === true) {
                        isGenerating.value = false;
                        showGenerateSignatureUserModal.value = false;
                        message.success(`Đã cấp chữ ký thành công cho ${getTargetUserCount()} người dùng`)
                        resetSignatureForm();
                        await certificateStore.fetchCertificates();
                    }
                } else if (grantType.value === 'department') {
                    const count = ref(0);
                    for (const user of departmentUsers.value) {
                        const response = await certificateStore.issueCertificate(user.id);
                        if (response.success === true) {
                            count.value++;
                        }
                    }
                    isGenerating.value = false;
                    showGenerateSignatureUserModal.value = false;
                    message.success(`Đã cấp chữ ký thành công cho ${count.value} người dùng trong ${selectedDepartmentName.value}`);
                } else {
                    const count = ref(0);
                    for (const user of allUsers.value) {
                        const response = await certificateStore.issueCertificate(user.id);
                        if (response.success === true) {
                            count.value++;
                        }
                    }
                    isGenerating.value = false;
                    showGenerateSignatureUserModal.value = false;
                    message.success(`Đã cấp chữ ký thành công cho ${getTargetUserCount()} người dùng trong hệ thống`);
                    resetSignatureForm();
                    await certificateStore.fetchCertificates(true);
                }
            } catch (error) {
                message.error('Đã xảy ra lỗi khi cấp chữ ký. Vui lòng thử lại sau.');
            } finally {
                isGenerating.value = false
            }
        }

        const resetSignatureForm = () => {
            grantType.value = 'user'
            selectedUserId.value = null
            manualUserId.value = ''
            selectedUserInfo.value = null
            selectedDepartment.value = null
            departmentUserCount.value = 0
            departmentUsers.value = []
            confirmGrantAll.value = false
            searchedUsers.value = []
        }

        const getCharOfName = (name) => {
            const parts = name.split(' ');
            if (parts.length === 0) return '';
            const lastPart = parts[parts.length - 1];
            return lastPart.charAt(0).toUpperCase();
        }

        const API_BASE_URL = 'http://localhost:8000';

        const randomAvatar = (id) => {
            if (id > 100 || id == null) {
                return `https://avatar.iran.liara.run/public`;
            }
            return `https://avatar.iran.liara.run/public/${id}`;
        };

        const getAvatarUrl = (avatar, id) => {
            if (!avatar) return randomAvatar(id);
            return `${API_BASE_URL}/images/avatars/${avatar}`;
        };        

        // Root CA data
        const rootCALoading = ref(false)
        const rootCAs = ref([
            {
                id: 1,
                subject: {
                    commonName: 'Trường Đại học Thủy lợi Root CA',
                    organization: 'Truong Dai hoc Thuy loi',
                    country: 'VN'
                },
                serialNumber: '1A2B3C4D5E6F7890',
                thumbprint: 'SHA1: A1B2C3D4E5F67890ABCDEF1234567890ABCDEF12',
                validFrom: '2020-01-01',
                validTo: '2030-01-01',
                status: 'active',
                issuedCerts: 245,
                revokedCerts: 12,
                keySize: '2048'
            }
        ])

        // Mock data cho chứng chỉ con
        const recentChildCerts = ref([
            { id: 1, commonName: 'Nguyễn Văn An', email: 'an.nguyen@tlu.edu.vn', issuedDate: '2024-01-15' },
            { id: 2, commonName: 'Trần Thị Bình', email: 'binh.tran@tlu.edu.vn', issuedDate: '2024-01-10' },
            { id: 3, commonName: 'Lê Minh Châu', email: 'chau.le@tlu.edu.vn', issuedDate: '2024-01-08' }
        ])

        // Root CA Modal states
        const showDeleteWarning1 = ref(false)
        const showDeleteWarning2 = ref(false)
        const showDeleteWarning3 = ref(false)
        const showFinalWarning = ref(false)
        const showGenerateModal = ref(false)
        const showImportModal = ref(false)

        // Root CA Form data
        const selectedCA = ref(null)
        const adminPassword = ref('')
        const deleteReason = ref('')
        const confirmUnderstand = ref(false)
        const countdown = ref(10)
        const countdownInterval = ref(null)

        // Form tạo CA mới
        const newCA = ref({
            commonName: '',
            organization: 'Truong Dai hoc Thuy loi',
            country: 'VN',
            validityYears: '10',
            keySize: '4096'
        })

        // User signatures computed
        const totalUserSignatures = computed(() => originalData.value.length)
        const renewalCount = computed(() => originalData.value.filter(item => item.status === 'renewal').length)
        const revokedCount = computed(() => originalData.value.filter(item => item.status === 'revoked').length)
        const expiredCount = computed(() => originalData.value.filter(item => item.status === 'expired').length)

        // Root CA computed
        const activeRootCAs = computed(() => rootCAs.value.filter(ca => ca.status === 'active').length)
        const totalIssuedCerts = computed(() => rootCAs.value.reduce((sum, ca) => sum + ca.issuedCerts, 0))
        const expiringSoon = computed(() => rootCAs.value.filter(ca => ca.status === 'expiring_soon').length)
        const revokedCerts = computed(() => rootCAs.value.reduce((sum, ca) => sum + ca.revokedCerts, 0))
        const progressPercent = computed(() => ((10 - countdown.value) / 10) * 100)

        // User signatures columns
        const columns = [
        {
            title: 'Người dùng',
            dataIndex: 'user',
            key: 'user',
            width: 150,
            customHeaderCell: () => {
                return { style: { textAlign: 'center' } };
            },
            responsive: ['xs', 'sm', 'md', 'lg', 'xl']
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            width: 100,
            responsive: ['xs', 'sm', 'md', 'lg', 'xl'],
            align: 'center'
        },
        {
            title: 'Sử dụng',
            dataIndex: 'used_count',
            key: 'used_count',
            width: 100,
            responsive: ['lg', 'xl'],
            align: 'center'
        },
        {
            title: 'Ngày tạo',
            dataIndex: 'createdAt',
            key: 'createdAt',
            width: 120,
            responsive: ['xl'],
            align: 'center'
        },
        {
            title: 'Ngày hết hạn',
            dataIndex: 'expires_at',
            key: 'expires_at',
            width: 150,
            responsive: ['md', 'lg', 'xl'],
            align: 'center',
        },
        {
            title: 'Thao tác',
            key: 'action',
            width: 150,
            responsive: ['xs', 'sm', 'md', 'lg', 'xl'],
            align: 'center',
            fixed: 'right',
        }
        ]

        // Root CA columns
        const rootCAColumns = [
            {
                title: 'Thông tin chứng chỉ',
                dataIndex: 'certificate',
                key: 'certificate',
                width: 350,
                slots: { customRender: 'certificate' }
            },
            {
                title: 'Trạng thái',
                dataIndex: 'status',
                key: 'status',
                width: 140,
                slots: { customRender: 'rootStatus' }
            },
            {
                title: 'Thời hạn hiệu lực',
                dataIndex: 'validity',
                key: 'validity',
                width: 200,
                slots: { customRender: 'validity' }
            },
            {
                title: 'Thống kê',
                dataIndex: 'stats',
                key: 'stats',
                width: 150,
                slots: { customRender: 'stats' }
            },
            {
                title: 'Thao tác',
                key: 'action',
                width: 100,
                slots: { customRender: 'rootAction' }
            }
        ]

        // Phân trang cho user signatures
        const pagination = ref({
            current: 1,
            pageSize: 10,
            total: 0,
            showSizeChanger: true,
            showQuickJumper: true,
            showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} mục`
        })

        // Dữ liệu đã lọc cho user signatures
        const filteredData = computed(() => {
            let data = [...originalData.value]
            
            // Lọc theo tìm kiếm
            if (searchText.value) {
                const search = searchText.value.toLowerCase()
                data = data.filter(item => 
                item.user.name.toLowerCase().includes(search) ||
                item.user.email.toLowerCase().includes(search) ||
                item.user.department.toLowerCase().includes(search)
                )
            }
            
            // Lọc theo trạng thái
            if (statusFilter.value) {
                data = data.filter(item => item.status === statusFilter.value)
            }
            
            // Lọc theo loại
            if (typeFilter.value) {
                data = data.filter(item => item.type === typeFilter.value)
            }
            
            pagination.value.total = data.length
            return data
        })

        // Helper functions cho user signatures
        const getStatusText = (status) => {
            const statusMap = {
                active: 'Đang sử dụng',
                revoked: 'Bị thu hồi',
                expired: 'Hết hạn',
                renewal: 'Yêu cầu làm mới'
            }
            return statusMap[status] || status
        }

        const getStatusClass = (status) => {
            const classMap = {
                active: 'text-success font-weight-bold',
                expired: 'text-warning font-weight-bold',
                revoked: 'text-danger font-weight-bold',
                renewal: 'text-secondary font-weight-bold'
            }
            return classMap[status] || ''
        }

        // Helper functions cho Root CA
        const getRootStatusText = (status) => {
            const statusMap = {
                active: 'Đang hoạt động',
                expiring_soon: 'Sắp hết hạn',
                expired: 'Đã hết hạn',
                revoked: 'Đã thu hồi'
            }
            return statusMap[status] || status
        }

        const getRootStatusClass = (status) => {
            const classMap = {
                active: 'text-success fw-bold',
                expiring_soon: 'text-warning fw-bold',
                expired: 'text-danger fw-bold',
                revoked: 'text-muted fw-bold'
            }
            return classMap[status] || ''
        }

        const getRootStatusIcon = (status) => {
            const iconMap = {
                active: 'fas fa-check-circle',
                expiring_soon: 'fas fa-exclamation-triangle',
                expired: 'fas fa-times-circle',
                revoked: 'fas fa-ban'
            }
            return iconMap[status] || 'fas fa-question-circle'
        }

        const formatDate = (dateStr) => {
            return new Date(dateStr).toLocaleDateString('vi-VN')
        }

        const getExpiryText = (validTo) => {
            const now = new Date()
            const expiry = new Date(validTo)
            const diffTime = expiry - now
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
            
            if (diffDays < 0) return 'Đã hết hạn'
            if (diffDays < 30) return `Còn ${diffDays} ngày`
            if (diffDays < 365) return `Còn ${Math.ceil(diffDays/30)} tháng`
            return `Còn ${Math.ceil(diffDays/365)} năm`
        }

        const getExpiryClass = (validTo) => {
            const now = new Date()
            const expiry = new Date(validTo)
            const diffTime = expiry - now
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
            
            if (diffDays < 0) return 'text-danger small fw-bold'
            if (diffDays < 30) return 'text-danger small fw-bold'
            if (diffDays < 90) return 'text-warning small fw-bold'
            return 'text-success small'
        }

        // Event handlers cho user signatures
        const handleSearch = () => {
            pagination.value.current = 1
        }

        const handleFilter = () => {
            pagination.value.current = 1
        }

        const resetFilters = () => {
            searchText.value = ''
            statusFilter.value = undefined
            typeFilter.value = undefined
            pagination.value.current = 1
        }

        const handleTableChange = (pag) => {
            pagination.value = pag
        }

        const handleMenuClick = (event, record) => {
            console.log('User signature menu clicked:', event.key, record)
            // Xử lý logic menu cho user signatures
        }

        // Event handlers cho Root CA
        const handleRootMenuClick = (event, record) => {
            const { key } = event
            selectedCA.value = record

            switch (key) {
                case 'view':
                    console.log('View CA details:', record)
                    break
                case 'export':
                    exportCA(record)
                    break
                case 'renew':
                    renewCA(record)
                    break
                case 'revoke':
                    revokeCA(record)
                    break
                case 'delete':
                    startDeleteProcess(record)
                    break
            }
        }

        const startDeleteProcess = (ca) => {
            selectedCA.value = ca
            showDeleteWarning1.value = true
        }

        const proceedToWarning2 = () => {
            showDeleteWarning1.value = false
            showDeleteWarning2.value = true
        }

        const proceedToWarning3 = () => {
            showDeleteWarning2.value = false
            showDeleteWarning3.value = true
        }

        const proceedToFinalWarning = () => {
            if (!adminPassword.value || !deleteReason.value || !confirmUnderstand.value) {
                return
            }
            
            // Giả lập xác thực admin password
            if (adminPassword.value !== 'admin123') {
                alert('Mật khẩu admin không chính xác!')
                return
            }
            
            showDeleteWarning3.value = false
            showFinalWarning.value = true
            startCountdown()
        }

        const startCountdown = () => {
            countdown.value = 10
            countdownInterval.value = setInterval(() => {
                countdown.value--
                if (countdown.value <= 0) {
                    clearInterval(countdownInterval.value)
                }
            }, 1000)
        }

        const cancelDelete = () => {
            // Reset tất cả
            showDeleteWarning1.value = false
            showDeleteWarning2.value = false
            showDeleteWarning3.value = false
            showFinalWarning.value = false
            selectedCA.value = null
            adminPassword.value = ''
            deleteReason.value = ''
            confirmUnderstand.value = false
            
            if (countdownInterval.value) {
                clearInterval(countdownInterval.value)
            }
        }

        const confirmDelete = () => {
            if (countdown.value > 0) return

            // Thực hiện xóa
            const index = rootCAs.value.findIndex(ca => ca.id === selectedCA.value.id)
            if (index !== -1) {
                rootCAs.value.splice(index, 1)
                console.log('Đã xóa Root CA:', selectedCA.value.subject.commonName)
                console.log('Lý do:', deleteReason.value)
            }
            
            cancelDelete()
        }

        const backToWarning1 = () => {
            showDeleteWarning2.value = false
            showDeleteWarning1.value = true
        }

        const backToWarning2 = () => {
            showDeleteWarning3.value = false
            showDeleteWarning2.value = true
            adminPassword.value = ''
            deleteReason.value = ''
            confirmUnderstand.value = false
        }

        const exportCA = (ca) => {
            console.log('Export CA:', ca.subject.commonName)
            // Logic export
        }

        const renewCA = (ca) => {
            console.log('Renew CA:', ca.subject.commonName)
            // Logic gia hạn
        }

        const revokeCA = (ca) => {
            console.log('Revoke CA:', ca.subject.commonName)
            // Logic thu hồi
        }

        const exportAllCerts = () => {
            console.log('Export all certificates')
            // Logic export tất cả
        }

        const generateNewRootCA = () => {
            // Logic tạo Root CA mới
            const newId = Math.max(...rootCAs.value.map(ca => ca.id)) + 1
            const now = new Date()
            const validTo = new Date(now.getFullYear() + parseInt(newCA.value.validityYears), now.getMonth(), now.getDate())
            
            const newRootCA = {
                id: newId,
                subject: {
                    commonName: newCA.value.commonName,
                    organization: newCA.value.organization,
                    country: newCA.value.country
                },
                serialNumber: Math.random().toString(16).substring(2, 18).toUpperCase(),
                thumbprint: 'SHA1: ' + Math.random().toString(16).substring(2, 42).toUpperCase(),
                validFrom: now.toISOString().split('T')[0],
                validTo: validTo.toISOString().split('T')[0],
                status: 'active',
                issuedCerts: 0,
                revokedCerts: 0,
                keySize: newCA.value.keySize
            }
            
            rootCAs.value.unshift(newRootCA)
            showGenerateModal.value = false
            
            // Reset form
            newCA.value = {
                commonName: '',
                organization: 'Truong Dai hoc Thuy loi',
                country: 'VN',
                validityYears: '10',
                keySize: '4096'
            }
        }

        onMounted(() => {
            pagination.value.total = originalData.value.length
        })

        onUnmounted(() => {
            if (countdownInterval.value) {
                clearInterval(countdownInterval.value)
            }
        })

        return {
            activeKey,

            // User signatures
            loading,
            searchText,
            statusFilter,
            typeFilter,
            columns,
            filteredData,
            pagination,
            totalUserSignatures,
            renewalCount,
            revokedCount,
            expiredCount,

            // User signatures modals
            // Signature grant form
            allUsers,
            grantType,
            selectedUserId,
            manualUserId,
            selectedUserInfo,
            selectedDepartment,
            departmentUserCount,
            departmentUsers,
            confirmGrantAll,
            totalUsersInSystem,
            isGenerating,
            userSearchLoading,
            searchedUsers,
            departments,
            showGenerateSignatureUserModal,
            
            // Methods
            handleUserSearch,
            handleUserSelect,
            handleManualUserIdChange,
            handleDepartmentSelect,
            getGrantTypeText,
            getTargetUserCount,
            resetSignatureForm,
            generateNewUserSignature,
            getCharOfName,
            getAvatarUrl,

            // Root CA
            rootCALoading,
            rootCAs,
            rootCAColumns,
            recentChildCerts,
            activeRootCAs,
            totalIssuedCerts,
            expiringSoon,
            revokedCerts,

            // Root CA Modals
            showDeleteWarning1,
            showDeleteWarning2,
            showDeleteWarning3,
            showFinalWarning,
            showGenerateModal,
            showImportModal,
            
            // Root CA Form data
            selectedCA,
            adminPassword,
            deleteReason,
            confirmUnderstand,
            countdown,
            progressPercent,
            newCA,

            // User signature methods
            getStatusText,
            getStatusClass,
            handleSearch,
            handleFilter,
            resetFilters,
            handleTableChange,
            handleMenuClick,

            // Root CA methods
            getRootStatusText,
            getRootStatusClass,
            getRootStatusIcon,
            formatDate,
            getExpiryText,
            getExpiryClass,
            handleRootMenuClick,
            startDeleteProcess,
            proceedToWarning2,
            proceedToWarning3,
            proceedToFinalWarning,
            cancelDelete,
            confirmDelete,
            backToWarning1,
            backToWarning2,
            exportCA,
            renewCA,
            revokeCA,
            exportAllCerts,
            generateNewRootCA
        }
    },
});
</script>

<style>
/* Global message z-index override - KHÔNG scoped */
.ant-message {
    z-index: 10000 !important;
}

.ant-message-notice {
    z-index: 10000 !important;
}

.ant-message-notice-wrapper {
    z-index: 10000 !important;
}
</style>

<style scoped>
.user-info {
  display: flex;
  align-items: center;
}

.signature-info {
  max-width: 180px;
}

.root-ca-content {
    padding: 0;
}

.root-ca-table {
    margin-top: 20px;
}

.cert-info {
    max-width: 330px;
}

.validity-info {
    font-size: 0.875em;
}

.stats-info {
    font-size: 0.875em;
}

.alert {
    border-radius: 8px;
}

.progress {
    height: 10px;
    border-radius: 5px;
}

.display-1 {
    font-size: 4rem;
    line-height: 1;
}

.text-primary { color: #0d6efd !important; }
.text-success { color: #198754 !important; }
.text-warning { color: #ffc107 !important; }
.text-danger { color: #dc3545 !important; }
.text-muted { color: #6c757d !important; }
.text-info { color: #1890ff !important; }
.text-secondary { color: #8c8c8c !important; }

.font-weight-bold, .fw-bold { font-weight: bold !important; }
.small { font-size: 0.875em; }

.btn {
    border-radius: 6px;
}

.form-control, .form-select {
    border-radius: 6px;
}

.border-primary { border-color: #0d6efd !important; }
.border-success { border-color: #198754 !important; }
.border-warning { border-color: #ffc107 !important; }
.border-danger { border-color: #dc3545 !important; }

.mr-3 {
  margin-right: 12px;
}

.mb-3 {
  margin-bottom: 16px;
}

.mb-4 {
  margin-bottom: 24px;
}

/* Dropdown menu z-index fix */
.ant-dropdown {
    z-index: 9999 !important;
}

.ant-dropdown-menu {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    border-radius: 6px !important;
}

.ant-dropdown-menu-item {
    padding: 8px 16px !important;
}

.ant-dropdown-menu-item:hover {
    background-color: #f5f5f5 !important;
}

.signature-grant-form {
    max-height: 70vh;
    overflow-y: auto;
}

.selected-user-info {
    margin-top: 10px;
}

.user-preview-list {
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    padding: 10px;
    background-color: #fafafa;
}

.user-preview-item {
    display: flex;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f0f0f0;
}

.user-preview-item:last-child {
    border-bottom: none;
}

.summary-section {
    border-top: 1px solid #e8e8e8;
    padding-top: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.confirmation-section {
    margin-top: 15px;
    padding: 15px;
    background-color: #fff3cd;
    border-radius: 6px;
    border: 1px solid #ffeaa7;
}

.signature-config,
.notification-options {
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
}

/* Custom select dropdown styling */
.ant-select-dropdown {
    z-index: 9999 !important;
}

.ant-select-item-option-content {
    height: auto !important;
}

/* Loading spinner */
.ant-spin-container {
    min-height: 40px;
}

@media (max-width: 768px) {
  .signature-list-container {
    padding: 10px;
  }
  
  .user-info {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .mr-3 {
    margin-right: 0;
    margin-bottom: 8px;
  }

  .cert-info {
    max-width: 200px;
  }
  
  .display-1 {
    font-size: 2.5rem;
  }
}
</style>