<template>
    <div class="col-12">
        <div class="row justify-content-around mt-2">
            <div class="bg-light col-lg col-9 border border-1 border-dark rounded-3 p-2 mx-2">
                <div class="row mt-1">
                    <div class="col col-lg d-flex d-lg-flex justify-content-between">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-5">Tổng số người dùng</span>
                        </div>
                        <div class="col-lg-3 col d-flex d-lg-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-users" style="color: #00ff11; font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <span class="fs-1 fw-bold ">{{ number_of_users }}</span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-lg-12">
                        <span class="fs-5 text-success">+12% so với tháng trước</span>
                    </div>
                </div>
            </div>

            <div class="bg-light col-lg col-9 border border-1 border-dark rounded-3 p-2 mx-2 mt-lg-0 mt-3">
                <div class="row mt-1">
                    <div class="col col-lg d-flex d-lg-flex justify-content-between">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-5">Tổng số văn bản</span>
                        </div>
                        <div class="col-lg-3 col d-flex d-lg-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-file-invoice" style="color: #74C0FC; font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <span class="fs-1 fw-bold ">{{ number_of_documents }}</span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-lg-12">
                        <span class="fs-5 text-success">+12% so với tháng trước</span>
                    </div>
                </div>
            </div>

            <div class="bg-light col-lg col-9 border border-1 border-dark rounded-3 p-2 mx-2 mt-lg-0 mt-3">
                <div class="row mt-1">
                    <div class="col col-lg d-flex d-lg-flex">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-5">Vai trò & Quyền hạn</span>
                        </div>
                        <div class="col-lg-3 col d-flex d-lg-flex align-items-center justify-content-end">
                            <i class="fa-solid fa-shield-halved fs-1" style="color: #B197FC;"></i>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <span class="fs-1 fw-bold ">6</span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-lg-12">
                        <span class="fs-5 text-success">+2 vai trò mới</span>
                    </div>
                </div>
            </div>

            <div class="bg-light col-lg col-9 border border-1 border-dark rounded-3 p-2 mx-2 mt-lg-0 mt-3">
                <div class="row mt-1">
                    <div class="col col-lg d-flex d-lg-flex">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-5">Luồng phê duyệt mẫu</span>
                        </div>
                        <div class="col-lg-3 col d-flex d-lg-flex align-items-center justify-content-end">
                            <i class="fa-solid fa-code-branch fs-1" style="color: #ff7300;"></i>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <span class="fs-1 fw-bold ">10</span>
                    </div>
                </div>
                <!-- <div class="row mt-1">
                    <div class="col-lg-12">
                        <span class="fs-5 text-success">+1 luồng mới</span>
                    </div>
                </div> -->
            </div>
        </div>

        <div class="row mt-2 justify-content-around">
            <!-- Hoạt động hệ thống -->
            <div class="bg-light col-xxl col-9 col-lg border border-1 border-dark rounded-3 pt-3 ps-3 mx-2 pb-3">
                <div class="row">
                    <span class="fs-4 fw-bold">Hoạt Động Hệ Thống</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Các hoạt động gần đây trên hệ thống</span>
                </div>
               <div v-for="(notification, index) in new_notifications" 
                    :key="index"
                    class="row border border-1 border-dark mt-3 p-2 mx-1"
                >
                    <div class="col-lg-2 col-2 d-flex d-lg-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img v-if="notification.sender?.avatar != null" :src="getAvatarUrl(notification.sender?.avatar)" class="w-100 h-100" style="object-fit: cover;" />
                            <img v-else :src="randomAvatar(notification.sender?.id)" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-lg col align-items-center d-flex d-lg-flex">
                        <div class="row">
                            <span class="fw-bold">{{ notification.sender?.name }}</span>
                            <span class="text-secondary">{{ notification.title }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Người dùng mới -->
            <div class="bg-light col-xxl col-9 col-lg border border-1 border-dark rounded-3 pt-3 mx-2 mt-3 mt-lg-0">
                <div class="row">
                    <span class="fs-4 fw-bold">Người Dùng Mới</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Người dùng mới đăng ký</span>
                </div>
                
                <div v-for="(user, index) in new_registered_users" 
                    :key="index"
                    class="row border border-1 border-dark mt-3 p-2 mx-1"
                    >

                    <!-- Avatar -->
                    <div class="col-lg-2 col-2 d-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img v-if="user?.avatar != null" :src="getAvatarUrl(user?.avatar)" class="w-100 h-100" style="object-fit: cover;" />
                            <img v-else :src="randomAvatar(user?.id)" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <!-- Name & Email -->
                    <div class="col-lg col align-items-center d-flex">
                        <div class="row">
                        <span class="fw-bold">{{ user.name }}</span>
                        <span class="text-secondary">{{ user.email }}</span>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-3 col-2 align-items-center d-flex border border-1 border-dark rounded-2 p-1 justify-content-center"
                        :style="getStatusStyle(user.status)">
                        <span class="text-center">{{ getStatusText(user.status) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2 justify-content-around">
            <!-- Thống kê văn bản -->
            <div class="bg-light col-xl-8 col-9 col-lg border border-1 border-dark rounded-3 pt-3 ps-3 mx-2 pb-3">
                <div class="row">
                    <span class="fs-4 fw-bold">Thống Kê Văn Bản</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Thống kê văn bản theo trạng thái</span>
                </div>
                <div class="row mt-5">
                    <div class="mb-4">
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-lg-3 text-lg-end text-start">
                                <strong class="text-size-document">Đã phê duyệt</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-lg-7">
                                <div class="progress" role="progressbar" aria-label="Success example" 
                                    :aria-valuenow="number_of_documents_by_status.get('approved').percentage"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar bg-success" 
                                        :style="{
                                            width: number_of_documents_by_status.get('approved').percentage + '%',
                                            height: '1.2rem'
                                        }"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-lg-2 text-lg-start text-end">
                                <span class="fw-semibold">{{ number_of_documents_by_status.get("approved").percentage }} %</span>
                            </div>
                        </div>

                    </div>

                    <div class="mb-4">
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-lg-3 text-lg-end text-start">
                                <strong class="text-size-document">Đang chờ duyệt</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-lg-7">
                                <div class="progress" role="progressbar" aria-label="Success example" 
                                    :aria-valuenow="number_of_documents_by_status.get('in_review').percentage"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar bg-info" 
                                        :style="{
                                            width: number_of_documents_by_status.get('in_review').percentage + '%',
                                            height: '1.2rem'
                                        }"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-lg-2 text-lg-start text-end">
                                <span class="fw-semibold">{{ number_of_documents_by_status.get("in_review").percentage }} %</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-lg-3 text-lg-end text-start">
                                <strong class="text-size-document">Từ chối duyệt</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-lg-7">
                                <div class="progress" role="progressbar" aria-label="Success example" 
                                    :aria-valuenow="number_of_documents_by_status.get('reject').percentage"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar bg-danger" 
                                        :style="{
                                            width: number_of_documents_by_status.get('reject').percentage + '%',
                                            height: '1.2rem'
                                        }"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-lg-2 text-lg-start text-end">
                                <span class="fw-semibold">{{ number_of_documents_by_status.get("reject").percentage }} %</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-lg-3 text-lg-end text-start">
                                <strong class="text-size-document">Bản nháp</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-lg-7">
                                <div class="progress" role="progressbar" aria-label="Success example" 
                                    :aria-valuenow="number_of_documents_by_status.get('draft').percentage"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar bg-secondary" 
                                        :style="{
                                            width: number_of_documents_by_status.get('draft').percentage + '%',
                                            height: '1.2rem'
                                        }"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-lg-2 text-lg-start text-end">
                                <span class="fw-semibold">{{ number_of_documents_by_status.get("draft").percentage }} %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê truy cập -->
            <div class="bg-light col-xl col-9 col-lg-5 border border-1 border-dark rounded-3 pt-3 mx-2 mt-3 mt-lg-0">
                <div class="row">
                    <span class="fs-4 fw-bold">Thống Kê Truy Cập</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Số lượng truy cập trong 7 ngày qua</span>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="chart-container">
                            <Bar 
                                v-if="chartData.labels.length > 0"
                                id="my-chart-id" 
                                :options="chartOptions" 
                                :data="chartData" 
                            />
                            <div v-else class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Đang tải dữ liệu...</p>
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
    createVNode,
    h 
} from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from "@/stores/admin/user-store";
import { useAuth } from '@/stores/use-auth';
import { useMenu } from '@/stores/use-menu.js';
import { useDocumentStore } from "@/stores/admin/document-store";
import { useNotificationStore } from '@/stores/use-notification';
import avatarUrl from '@/assets/images/Cosette.jpg';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default defineComponent({
    name: "AdminDashboard",
    components: {
        Bar,
    },
    setup() {
        const router = useRouter();
        const userStore = useUserStore();
        const authStore = useAuth();
        const documentStore = useDocumentStore();
        const notificationStore = useNotificationStore();

        // Reactive data
        const users = ref([]);
        const documents = ref([]);
        const notifications = ref([]);
        
        // Chart reactive data
        const chartData = reactive({
            labels: [],
            datasets: [
                {
                    label: 'Số lượng truy cập',
                    backgroundColor: '#42A5F5',
                    barThickness: 25,
                    maxBarThickness: 30,
                    data: []
                }
            ]
        });

        const chartOptions = reactive({
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Biểu đồ thống kê lượng truy cập',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        });

        // Computed properties
        const number_of_users = computed(() => users.value.length);
        const number_of_documents = computed(() => documents.value.length);

        const number_of_documents_by_status = computed(() => {
            const total = number_of_documents.value;

            const counts = documents.value.reduce((acc, doc) => {
                if (acc.has(doc.status)) {
                    acc.set(doc.status, acc.get(doc.status) + 1);
                } else {
                    acc.set(doc.status, 1);
                }
                return acc;
            }, new Map([
                ["approved", 0],
                ["reject", 0],
                ["in_review", 0],
                ["draft", 0]
            ]));

            const result = new Map();
            for (const [status, count] of counts.entries()) {
                const percentage = total > 0 ? ((count / total) * 100).toFixed(2) : "0.00";
                result.set(status, {
                    count,
                    percentage: Number(percentage)
                });
            }

            return result;
        });

        const new_notifications = computed(() => {
            return [...notifications.value]
                .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
                .slice(0, 5)
                .map(notification => ({
                    title: notification.title,
                    from: notification.from_user_id,
                    receiver: notification.receiver_id,
                    content: notification.content,
                    created_at: notification.created_at,
                    sender: {
                        id: notification.sender?.id,
                        name: notification.sender?.name,
                        avatar: notification.sender?.avatar,
                    },
                }));
        });

        const new_registered_users = computed(() => {
            return [...users.value]
                .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
                .slice(0, 5)
                .map(user => ({
                    id: user.id,
                    name: user.name,
                    avatar: user.avatar,
                    email: user.email,
                    status: user.status,
                }));
        });

        // Methods
        const API_BASE_URL = 'http://localhost:8000';

        const getAvatarUrl = (avatar) => {
            if (!avatar) return null;
            return `${API_BASE_URL}/images/avatars/${avatar}`;
        };

        const getStatusStyle = (status) => {
            switch (status) {
                case 'active':
                    return {
                        backgroundColor: '#81FF90',
                        color: '#009938',
                    };
                case 'banned':
                    return {
                        backgroundColor: '#FFD1D1',
                        color: '#B10000',
                    };
                case 'pending':
                    return {
                        backgroundColor: '#FFF781',
                        color: '#C47900',
                    };
                default:
                    return {
                        backgroundColor: '#E0E0E0',
                        color: '#333',
                    };
            }
        };

        const getStatusText = (status) => {
            switch (status) {
                case 'active':
                    return 'Hoạt động';
                case 'banned':
                    return 'Tạm cấm';
                case 'pending':
                    return 'Chờ duyệt';
                default:
                    return 'Chưa kích hoạt';
            }
        };

        const randomAvatar = (id) => {
            if (id > 100 || id == null) {
                return `https://avatar.iran.liara.run/public`;
            }
            return `https://avatar.iran.liara.run/public/${id}`;
        };

        // Lifecycle
        onMounted(async () => {
            try {
                // Fetch users
                await userStore.fetchAll();
                users.value = userStore.users;

                // Fetch documents
                await documentStore.fetchAll();
                documents.value = documentStore.documents;

                // Fetch notifications
                await notificationStore.fetchNotifications(authStore.user.id);
                notifications.value = notificationStore.notifications;

                // Update chart data
                const accessStats = userStore.daily_access || {};
                
                // Đảm bảo có dữ liệu trước khi cập nhật
                if (Object.keys(accessStats).length > 0) {
                    chartData.labels = Object.keys(accessStats).map(date => {
                        const [y, m, d] = date.split('-');
                        return `Ngày ${d}/${m}`;
                    });
                    
                    chartData.datasets[0].data = Object.values(accessStats);
                } else {
                    // Dữ liệu mẫu nếu không có dữ liệu từ API
                    console.warn('No access stats data, using sample data');
                    chartData.labels = ['Ngày 1', 'Ngày 2', 'Ngày 3', 'Ngày 4', 'Ngày 5', 'Ngày 6', 'Ngày 7'];
                    chartData.datasets[0].data = [10, 20, 15, 25, 30, 22, 18];
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                // Xử lý lỗi nếu cần
            }
        });

        // Set menu
        useMenu().onSelectedKeys(["admin-dashboard"]);

        return {
            // Data
            avatarUrl,
            chartData,
            chartOptions,
            
            // Computed
            number_of_users,
            number_of_documents,
            new_registered_users,
            number_of_documents_by_status,
            new_notifications,

            // Methods
            getStatusStyle,
            getStatusText,
            getAvatarUrl,
            randomAvatar,
        };
    },
});
</script>

<style scoped>
.chart-container {
    position: relative;
    width: 100%;
    min-height: 300px;
}

.text-size-document {
    font-size: 0.8rem;
}

/* Loading spinner styles */
.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>