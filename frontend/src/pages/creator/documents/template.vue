<template>
    <div class="container py-4">
        <!-- Header section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Mẫu Văn Bản</h1>
                <p class="text-secondary">Sử dụng và quản lý các mẫu văn bản để tạo văn bản nhanh chóng</p>
            </div>
            <a-button type="primary">
                <template #icon>
                    <PlusOutlined />
                </template>
                Thêm Mẫu Mới
            </a-button>
        </div>

        <!-- Search and filter section -->
        <div class="row mb-4">
            <div class="col-md-8 mb-3 mb-md-0">
                <a-input-search placeholder="Tìm kiếm mẫu văn bản..." enter-button allowClear />
            </div>
            <div class="col-md-4">
                <a-dropdown>
                    <a-button class="w-100">
                        <FilterOutlined />
                        <span class="ms-2">Bộ lọc</span>
                        <DownOutlined />
                    </a-button>
                    <template #overlay>
                        <a-menu>
                            <a-menu-item key="1">Mới nhất</a-menu-item>
                            <a-menu-item key="2">Cũ nhất</a-menu-item>
                            <a-menu-item key="3">A-Z</a-menu-item>
                            <a-menu-item key="4">Z-A</a-menu-item>
                        </a-menu>
                    </template>
                </a-dropdown>
            </div>
        </div>

        <!-- Template documents -->
        <div class="row">
            <div class="col-md-4 mb-4" v-for="template in paginatedTemplates" :key="template.id">
                <div class="col px-4 py-3 rounded-3" style="background-color: #fff;">
                    <!-- Title -->
                    <div class="row">
                        <span class="fs-6 fw-bold mb-1">{{ template.title }}</span>
                    </div>

                    <!-- Updated Time -->
                    <div class="row mb-2">
                        <small class="text-muted">Cập nhật: {{ template.updated }}</small>
                    </div>

                    <!-- Official Tag -->
                    <div class="row">
                        <div class="col d-flex align-items-center justify-content-end">
                            <span v-if="template.isOfficial" class="bg-secondary-subtle p-1 rounded-2">Chính thức</span>
                            <span v-else class="bg-secondary-subtle p-1 rounded-2">Không chính thức</span>
                        </div>
                    </div>

                    <!-- Icon Area -->
                    <div class="row my-2 bg-secondary-subtle py-3 d-flex justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-file-text h-12 w-12 text-muted-foreground">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                            <path d="M10 9H8"></path>
                            <path d="M16 13H8"></path>
                            <path d="M16 17H8"></path>
                        </svg>
                    </div>

                    <!-- Description -->
                    <div class="row">
                        <span class="description-truncate">
                            {{ template.description }}
                        </span>
                    </div>

                    <!-- Buttons -->
                    <div class="row mt-3">
                        <div class="col">
                            <!-- Star Favorite Button -->
                            <a-button class="me-2" shape="circle" @click="toggleFavorite(template)">
                                <i :class="template.isFavorite ? 'bi bi-star-fill text-warning' : 'bi bi-star'"></i>
                            </a-button>

                            <!-- Eye View Button -->
                            <a-button shape="circle" @click="openPdf(template)">
                                <i class="bi bi-eye"></i>
                            </a-button>
                        </div>

                        <!-- Used Count -->
                        <div class="col justify-content-end d-flex align-items-center">
                            <span>Đã dùng: {{ template.used }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination and Page Size -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a-pagination 
                v-model:current="pagination.current" 
                :total="templates.length"
                :page-size="pagination.pageSize" 
                :show-quick-jumper="true"
                @change="handlePageChange" />
            <div class="d-flex align-items-center">
                <span class="me-2">Hiển thị:</span>
                <a-select v-model:value="pagination.pageSize" style="width: 100px" @change="handlePageSizeChange">
                    <a-select-option :value="3">3</a-select-option>
                    <a-select-option :value="6">6</a-select-option>
                    <a-select-option :value="9">9</a-select-option>
                </a-select>
            </div>
        </div>

        <a-divider />

        <!-- Recent Templates Section -->
        <div class="my-4">
            <h2 class="h4 fw-semibold mb-3">Mẫu Văn Bản Gần Đây</h2>
            <div class="row">
                <div class="col-md-6 mb-3" v-for="recentDoc in recentDocuments" :key="recentDoc.id">
                    <a-card>
                        <template #title>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ recentDoc.title }}</span>
                                <a-tag color="green">Đã sử dụng</a-tag>
                            </div>
                        </template>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <CheckSquareOutlined style="font-size: 1.2rem; margin-right: 10px; color: #666;" />
                                <div>
                                    <p class="mb-0 small">Sử dụng lần cuối: {{ recentDoc.lastUsed }}</p>
                                    <p class="mb-0 small text-secondary">Đã tạo {{ recentDoc.createdCount }} văn bản</p>
                                </div>
                            </div>
                            <a-button>
                                <template #icon>
                                    <FileAddOutlined />
                                </template>
                                Tạo mới
                            </a-button>
                        </div>
                    </a-card>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { defineComponent, ref, computed, reactive } from 'vue';
import { Pagination, Select } from 'ant-design-vue';
import {
    PlusOutlined,
    FilterOutlined,
    DownOutlined,
    FileAddOutlined,
    CheckSquareOutlined
} from '@ant-design/icons-vue';
import pdfFile from '@/assets/files/ĐCĐA_TrầnTuấnMinh_63CNTT4_2151062831.pdf';

export default defineComponent({
    components: {
        PlusOutlined,
        FilterOutlined,
        DownOutlined,
        FileAddOutlined,
        CheckSquareOutlined
    },
    setup() {
        const pagination = ref({
            current: 1,
            total: 0,
            pageSize: 6
        });

        const templates = ref([
            { id: 1, title: 'Đơn xin nghỉ phép', updated: '15/04/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin nghỉ phép cho sinh viên, giảng viên.', used: 5 },
            { id: 2, title: 'Đơn xin cấp bảng điểm', updated: '20/03/2023', isOfficial: true, isFavorite: false, description: 'Mẫu đơn xin cấp bảng điểm.', used: 5 },
            { id: 3, title: 'Đề xuất mua thiết bị', updated: '05/05/2023', isOfficial: false, isFavorite: true, description: 'Mẫu đề xuất mua thiết bị cho phòng lab.', used: 5 },
            { id: 4, title: 'Báo cáo kết quả học tập', updated: '10/02/2023', isOfficial: true, isFavorite: false, description: 'Mẫu báo cáo kết quả học tập.', used: 5 },
            { id: 5, title: 'Đơn xin chuyển ngành', updated: '25/01/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin chuyển ngành học.', used: 5 },
            { id: 6, title: 'Đề xuất tổ chức sự kiện', updated: '18/03/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tổ chức sự kiện.', used: 5 },
            { id: 7, title: 'Đơn xin gia hạn thời gian học', updated: '12/04/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin gia hạn thời gian học.', used: 5 },
            { id: 8, title: 'Đơn xin miễn giảm học phí', updated: '30/03/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đơn xin miễn giảm học phí.', used: 5 },
            { id: 9, title: 'Đề xuất tham gia hội thảo', updated: '22/02/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đề xuất tham gia hội thảo.', used: 5 },
            { id: 10, title: 'Đơn xin nghỉ học', updated: '28/01/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đơn xin nghỉ học.', used: 5 },
            { id: 11, title: 'Đơn xin cấp thẻ sinh viên', updated: '15/03/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp thẻ sinh viên.', used: 5 },
            { id: 12, title: 'Đề xuất tham gia nghiên cứu khoa học', updated: '10/04/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tham gia nghiên cứu khoa học.', used: 5 },
            { id: 13, title: 'Đơn xin chuyển lớp', updated: '05/02/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin chuyển lớp học.', used: 5 },
            { id: 14, title: 'Đề xuất tổ chức buổi học ngoại khóa', updated: '20/03/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tổ chức buổi học ngoại khóa.', used: 5 },
            { id: 15, title: 'Đơn xin cấp giấy chứng nhận tốt nghiệp', updated: '12/04/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp giấy chứng nhận tốt nghiệp.', used: 5 },
            { id: 16, title: 'Đề xuất tham gia chương trình trao đổi sinh viên', updated: '30/03/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tham gia chương trình trao đổi sinh viên.', used: 5 },
            { id: 17, title: 'Đơn xin cấp giấy phép thực tập', updated: '22/02/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp giấy phép thực tập.', used: 5 },
            { id: 18, title: 'Đề xuất tổ chức buổi hội thảo', updated: '28/01/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tổ chức buổi hội thảo.', used: 5 },
            { id: 19, title: 'Đơn xin cấp giấy chứng nhận tham gia hoạt động ngoại khóa', updated: '15/03/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp giấy chứng nhận tham gia hoạt động ngoại khóa.', used: 5 },
            { id: 20, title: 'Đề xuất tham gia chương trình học bổng', updated: '10/04/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tham gia chương trình học bổng.', used: 5 },
            { id: 21, title: 'Đơn xin cấp giấy chứng nhận tham gia nghiên cứu khoa học', updated: '05/02/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp giấy chứng nhận tham gia nghiên cứu khoa học.', used: 5 },
            { id: 22, title: 'Đề xuất tổ chức buổi giao lưu sinh viên', updated: '20/03/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tổ chức buổi giao lưu sinh viên.', used: 5 },
            { id: 23, title: 'Đơn xin cấp giấy chứng nhận tham gia hoạt động tình nguyện', updated: '12/04/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp giấy chứng nhận tham gia hoạt động tình nguyện.', used: 5 },
            { id: 24, title: 'Đề xuất tham gia chương trình thực tập sinh', updated: '30/03/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tham gia chương trình thực tập sinh.', used: 5 },
            { id: 25, title: 'Đơn xin cấp giấy chứng nhận tham gia hoạt động thể thao', updated: '22/02/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp giấy chứng nhận tham gia hoạt động thể thao.', used: 5 },
            { id: 26, title: 'Đề xuất tổ chức buổi hội thảo khoa học', updated: '28/01/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tổ chức buổi hội thảo khoa học.', used: 5 },
            { id: 27, title: 'Đơn xin cấp giấy chứng nhận tham gia hoạt động văn hóa', updated: '15/03/2023', isOfficial: true, isFavorite: true, description: 'Mẫu đơn xin cấp giấy chứng nhận tham gia hoạt động văn hóa.', used: 5 },
            { id: 28, title: 'Đề xuất tham gia chương trình giao lưu văn hóa', updated: '10/04/2023', isOfficial: false, isFavorite: false, description: 'Mẫu đề xuất tham gia chương trình giao lưu văn hóa.', used: 5 }
        ]);

        const recentDocuments = ref([
            { id: 1, title: 'Đơn xin nghỉ phép', lastUsed: '10/04/2023', createdCount: 5 },
            { id: 2, title: 'Đề xuất mua thiết bị', lastUsed: '05/05/2023', createdCount: 2 }
        ]);

        const paginatedTemplates = computed(() => {
            const start = (pagination.value.current - 1) * pagination.value.pageSize;
            const end = start + pagination.value.pageSize;
            return templates.value.slice(start, end);
        });

        const handlePageChange = (page) => {
            pagination.value.current = page;
        };

        const handlePageSizeChange = (size) => {
            pagination.value.pageSize = size;
            pagination.value.current = 1; // Reset về trang 1 khi đổi pageSize
        };

        pagination.value.total = templates.value.length; // gán tổng số lượng

        function toggleFavorite(template) {
            template.isFavorite = !template.isFavorite;
        }

        function openPdf() {
            console.log('Opening PDF file...');
            window.open(pdfFile, '_blank');
        }

        return {
            pagination,
            templates,
            recentDocuments,
            paginatedTemplates,
            handlePageChange,
            handlePageSizeChange,
            openPdf,
            toggleFavorite,
            Pagination,
            Select
        };
    }
});
</script>



<style scoped>
.description-truncate {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 2.5em;
}
</style>