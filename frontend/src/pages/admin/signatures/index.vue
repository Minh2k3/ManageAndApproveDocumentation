<template>
    <div class="container-fluid">
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
                            <!-- Cột người dùng -->
                            <template #user="{ record }">
                                <div class="user-info">
                                <a-avatar :size="40" class="mr-3">
                                    {{ record.user.name.charAt(0) }}
                                </a-avatar>
                                <div>
                                    <div class="font-weight-bold">{{ record.user.name }}</div>
                                    <div class="text-muted small">{{ record.user.email }}</div>
                                    <div class="text-muted small">{{ record.user.department }}</div>
                                </div>
                                </div>
                            </template>

                            <!-- Cột chữ ký -->
                            <template #signature="{ record }">
                                <div class="signature-info">
                                <div class="font-weight-bold text-primary">{{ record.signature.name }}</div>
                                <div class="text-muted small">
                                    Public Key: {{ record.signature.publicKey.substring(0, 20) }}...
                                </div>
                                </div>
                            </template>

                            <!-- Cột trạng thái -->
                            <template #status="{ record }">
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

                            <!-- Cột thao tác -->
                            <template #action="{ record }">
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
                        </a-table>
                    </div>
                </div>
                    <a-modal
                        v-model:open="showGenerateSignatureUserModal"
                        title="Tạo chữ ký người dùng mới"
                        :width="600"
                        @ok="generateNewUserSignature"
                        ok-text="Tạo chữ ký"
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

    </div>
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { MoreOutlined } from '@ant-design/icons-vue'
import { fi } from 'date-fns/locale';
import { 
    ref, 
    defineComponent, 
    computed, 
    reactive, 
    watch, 
    onMounted, 
    onUnmounted,
    createVNode,
    h 
} from 'vue';

export default defineComponent({
    components: {
        MoreOutlined
    },
    setup() {
        useMenu().onSelectedKeys(["admin-signatures"]);
        
        const activeKey = ref("1");

        // User signatures data
        const loading = ref(false)
        const searchText = ref('')
        const statusFilter = ref(undefined)
        const typeFilter = ref(undefined)

        const showGenerateSignatureUserModal = ref(false)

        const generateNewUserSignature = () => {
            // Logic to generate new user signature
            console.log("Generating new user signature...");
            showGenerateSignatureUserModal.value = false;
        }
        
        // Mock data cho user signatures
        const originalData = ref([
        {
            id: 1,
            user: {
            name: 'Nguyễn Văn An',
            email: 'an.nguyen@tlu.edu.vn',
            department: 'Khoa Công nghệ thông tin'
            },
            type: 'document',
            signature: {
            name: 'Nguyễn Văn An',
            publicKey: 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7QjK...',
            privateKey: '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w...'
            },
            status: 'active',
            usageCount: 15,
            createdAt: '2024-01-15',
            lastUsed: '2024-01-20'
        },
        {
            id: 2,
            user: {
            name: 'Trần Thị Bình',
            email: 'binh.tran@tlu.edu.vn',
            department: 'Khoa Kinh tế'
            },
            type: 'personal',
            signature: {
            name: 'Trần Thị Bình',
            publicKey: 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8RmL...',
            privateKey: '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w...'
            },
            status: 'renewal',
            usageCount: 0,
            createdAt: '2024-01-18',
            lastUsed: null
        },
        {
            id: 3,
            user: {
            name: 'Lê Minh Châu',
            email: 'chau.le@tlu.edu.vn',
            department: 'Khoa Xây dựng'
            },
            type: 'digital',
            signature: {
            name: 'Lê Minh Châu',
            publicKey: 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA9TnP...',
            privateKey: '-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w...'
            },
            status: 'renewal',
            usageCount: 3,
            createdAt: '2024-01-10',
            lastUsed: '2024-01-15'
        },
        {
            id: 4,
            user: {
            name: 'Phạm Hoàng Dũng',
            email: 'dung.pham@tlu.edu.vn',
            department: 'Khoa Môi trường'
            },
            type: 'document',
            signature: {
            name: 'Phạm Hoàng Dũng',
            publicKey: 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6KlM...',
            privateKey: '-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w...'
            },
            status: 'revoked',
            usageCount: 8,
            createdAt: '2024-01-05',
            lastUsed: '2024-01-12'
        },
        {
            id: 5,
            user: {
            name: 'Võ Thị Hoa',
            email: 'hoa.vo@tlu.edu.vn',
            department: 'Khoa Ngoại ngữ'
            },
            type: 'personal',
            signature: {
            name: 'Võ Thị Hoa',
            publicKey: 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5NmQ...',
            privateKey: '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w...'
            },
            status: 'renewal',
            usageCount: 12,
            createdAt: '2024-01-01',
            lastUsed: '2024-01-18'
        }
        ])

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
            width: 180,
            slots: { customRender: 'user' },
            customHeaderCell: () => {
                return { style: { textAlign: 'center' } };
            },
            responsive: ['xs', 'sm', 'md', 'lg', 'xl']
        },
        // {
        //     title: 'Chữ ký',
        //     dataIndex: 'signature',
        //     key: 'signature',
        //     width: 200,
        //     slots: { customRender: 'signature' },
        //     responsive: ['md', 'lg', 'xl']
        // },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            width: 140,
            slots: { customRender: 'status' },
            responsive: ['xs', 'sm', 'md', 'lg', 'xl'],
            align: 'center'
        },
        {
            title: 'Sử dụng',
            dataIndex: 'usageCount',
            key: 'usageCount',
            width: 100,
            customRender: ({ text }) => `${text} lần`,
            responsive: ['lg', 'xl'],
            align: 'center'
        },
        {
            title: 'Ngày tạo',
            dataIndex: 'createdAt',
            key: 'createdAt',
            width: 120,
            customRender: ({ text }) => formatDate(text),
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
            slots: { customRender: 'action' },
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
            showGenerateSignatureUserModal,

            // User signatures methods
            generateNewUserSignature,

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