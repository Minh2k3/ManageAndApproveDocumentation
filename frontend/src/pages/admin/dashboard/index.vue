<template>
    <div class="col-12">
        <div class="row justify-content-around mt-2">
            <div class="bg-light col-sm col-9 border border-2 border-dark rounded-3 p-2 mx-2">
                <div class="row mt-1">
                    <div class="col col-sm d-flex d-sm-flex justify-content-between">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-4">Tổng số người dùng</span>
                        </div>
                        <div class="col-sm-3 col d-flex d-sm-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-users" style="color: #00ff11; font-size: 3rem;"></i>
                        </div>


                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <span class="fs-1 fw-bold ">100</span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-12">
                        <span class="fs-5 text-success">+12% so với tháng trước</span>
                    </div>
                </div>
            </div>

            <div class="bg-light col-sm col-9 border border-2 border-dark rounded-3 p-2 mx-2 mt-sm-0 mt-3">
                <div class="row mt-1">
                    <div class="col col-sm d-flex d-sm-flex justify-content-between">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-4">Tổng số văn bản</span>
                        </div>
                        <div class="col-sm-3 col d-flex d-sm-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-file-invoice" style="color: #74C0FC; font-size: 3rem;"></i>
                        </div>


                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <span class="fs-1 fw-bold ">200</span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-12">
                        <span class="fs-5 text-success">+12% so với tháng trước</span>
                    </div>
                </div>
            </div>

            <div class="bg-light col-sm col-9 border border-2 border-dark rounded-3 p-2 mx-2 mt-sm-0 mt-3">
                <div class="row mt-1">
                    <div class="col col-sm d-flex d-sm-flex">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-4">Vai trò & Quyền hạn</span>
                        </div>
                        <div class="col-sm-3 col d-flex d-sm-flex align-items-center justify-content-end">
                            <i class="fa-solid fa-shield-halved fs-1" style="color: #B197FC;"></i>
                        </div>


                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <span class="fs-1 fw-bold ">6</span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-12">
                        <span class="fs-5 text-success">+2 vai trò mới</span>
                    </div>
                </div>
            </div>

            <div class="bg-light col-sm col-9 border border-2 border-dark rounded-3 p-2 mx-2 mt-sm-0 mt-3">
                <div class="row mt-1">
                    <div class="col col-sm d-flex d-sm-flex">
                        <div class="d-flex align-items-center justify-content-start">
                            <span class="fw-bold fs-4">Luồng phê duyệt</span>
                        </div>
                        <div class="col-sm-3 col d-flex d-sm-flex align-items-center justify-content-end">
                            <i class="fa-solid fa-code-branch fs-1" style="color: #ff7300;"></i>
                        </div>


                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <span class="fs-1 fw-bold ">10</span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-12">
                        <span class="fs-5 text-success">+1 luồng mới</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2 justify-content-around">
            <div class="bg-light col-sm-8 col-9 col-md border border-2 border-dark rounded-3 pt-3 ps-3 mx-2 pb-3">
                <div class="row">
                    <span class="fs-2 fw-bold">Hoạt Động Hệ Thống</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Các hoạt động gần đây trên hệ thống</span>
                </div>
                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-1 col-1 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">Đã tạo một văn bản mới</span>
                        </div>
                    </div>
                </div>

                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-1 col-1 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">Đã tạo một văn bản mới</span>
                        </div>
                    </div>
                </div>

                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-1 col-1 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">Đã tạo một văn bản mới</span>
                        </div>
                    </div>
                </div>

                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-1 col-1 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">Đã tạo một văn bản mới</span>
                        </div>
                    </div>
                </div>

                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-1 col-1 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">Đã tạo một văn bản mới</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bg-light col-sm col-9 col-md border border-2 border-dark rounded-3 p-2 mx-2 mt-3 mt-sm-0">
                <div class="row">
                    <span class="fs-2 fw-bold">Người Dùng Mới</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Người dùng mới đăng ký</span>
                </div>
                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-2 col-2 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">2151062831@e.tlu.edu.vn</span>
                        </div>
                    </div>

                    <div class="col-sm-2 col-2 align-items-center d-flex d-sm-flex border border-1 border-dark rounded-2 p-1 justify-content-center"
                        style="background-color: #81FF90; color: #009938;">
                        <span class="text-center">Hoạt động</span>
                    </div>
                </div>

                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-2 col-2 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn B</span>
                            <span class="text-secondary">2151062831@e.tlu.edu.vn</span>
                        </div>
                    </div>

                    <div class="col-sm-2 col-2 align-items-center d-flex d-sm-flex border border-1 border-dark rounded-2 p-1 justify-content-center"
                        style="background-color: #81FF90; color: #009938;">
                        <span class="text-center">Hoạt động</span>
                    </div>
                </div>

                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-2 col-2 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">2151062831@e.tlu.edu.vn</span>
                        </div>
                    </div>

                    <div class="col-sm-2 col-2 align-items-center d-flex d-sm-flex border border-1 border-dark rounded-2 p-1 justify-content-center"
                        style="background-color: #FFF781; color: #C47900;">
                        <span class="text-center">Chờ duyệt</span>
                    </div>
                </div>

                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-2 col-2 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">2151062831@e.tlu.edu.vn</span>
                        </div>
                    </div>

                    <div class="col-sm-2 col-2 align-items-center d-flex d-sm-flex border border-1 border-dark rounded-2 p-1 justify-content-center"
                        style="background-color: #81FF90; color: #009938;">
                        <span class="text-center">Hoạt động</span>
                    </div>
                </div>


                <div class="row border border-1 border-dark mt-3 p-2 mx-1">
                    <div class="col-sm-2 col-2 d-flex d-sm-flex justify-content-center align-items-center px-0">
                        <div class="rounded-circle overflow-hidden border border-1 border-dark"
                            style="width: 3.5rem; height: 3.5rem;">
                            <img :src="avatarUrl" class="w-100 h-100" style="object-fit: cover;" />
                        </div>
                    </div>

                    <div class="col-sm col align-items-center d-flex d-sm-flex">
                        <div class="row">
                            <span class="fw-bold">Nguyễn Văn A</span>
                            <span class="text-secondary">2151062831@e.tlu.edu.vn</span>
                        </div>
                    </div>

                    <div class="col-sm-2 col-2 align-items-center d-flex d-sm-flex border border-1 border-dark rounded-2 p-1 justify-content-center"
                        style="background-color: #FFA581; color: #DF0000;">
                        <span class="text-center">Tạm cấm</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="row mt-2 justify-content-around">
            <div class="bg-light col-sm-6 col-9 col-md border border-2 border-dark rounded-3 pt-3 ps-3 mx-2 pb-3">
                <div class="row">
                    <span class="fs-2 fw-bold">Thống Kê Văn Bản</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Thống kê văn bản theo trạng thái</span>
                </div>
                <div class="row mt-5">
                    <div class="mb-4">
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-sm-3 text-sm-end text-start">
                                <strong>Đã phê duyệt</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-sm-7">
                                <div class="progress" role="progressbar" aria-label="Success example" aria-valuenow="65"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar bg-success" style="width: 65%; height: 1.2rem;"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-sm-2 text-sm-start text-end">
                                <span class="fw-semibold">65%</span>
                            </div>
                        </div>

                    </div>

                    <div class="mb-4">
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-sm-3 text-sm-end text-start">
                                <strong>Đang chờ duyệt</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-sm-7">
                                <div class="progress" role="progressbar" aria-label="Loading" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar bg-warning" style="width: 25%; height: 1.2rem;"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-sm-2 text-sm-start text-end">
                                <span class="fw-semibold">25%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-sm-3 text-sm-end text-start">
                                <strong>Từ chối duyệt</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-sm-7">
                                <div class="progress" role="progressbar" aria-label="Reject" aria-valuenow="5"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar bg-danger" style="width: 5%; height: 1.2rem;"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-sm-2 text-sm-start text-end">
                                <span class="fw-semibold">5%</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="row align-items-center mb-3">
                            <!-- Label -->
                            <div class="col-sm-3 text-sm-end text-start">
                                <strong>Bản nháp</strong>
                            </div>

                            <!-- Progress bar -->
                            <div class="col-sm-7">
                                <div class="progress" role="progressbar" aria-label="draft" aria-valuenow="5"
                                    aria-valuemin="0" aria-valuemax="100" style="background-color: #d7d7d9;">
                                    <div class="progress-bar"
                                        style="width: 5%; height: 1.2rem; background-color: #8c8c8c;"></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div class="col-sm-2 text-sm-start text-end">
                                <span class="fw-semibold">65%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-light col-sm col-9 col-md border border-2 border-dark rounded-3 p-2 mx-2 mt-3 mt-sm-0">
                <div class="row">
                    <span class="fs-2 fw-bold">Thống Kê Truy Cập</span>
                </div>
                <div class="row mt-1">
                    <span class="text-secondary">Số lượng truy cập trong 7 ngày qua</span>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="chart-container">
                            <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs } from "vue";
import { useMenu } from '@/stores/use-menu.js';
import avatarUrl from '@/assets/images/Cosette.jpg';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);
export default defineComponent({

    name: "BarChart",
    components: {
        Bar,
    },
    data() {
        return {
            chartData: {
                labels: ['Ngày 07/4', 'Ngày 08/4', 'Ngày 09/4', 'Ngày 10/4', 'Ngày 11/4', 'Ngày 12/4', 'Ngày 13/4'],
                datasets: [
                    {
                        label: 'Số lượng truy cập',
                        backgroundColor: '#42A5F5',
                        barThickness: 25,
                        maxBarThickness: 30,
                        data: [40, 20, 12, 39, 10, 60, 25]
                    }
                ]
            },
            chartOptions: {
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
            },
        };
    },

    setup() {

        useMenu().onSelectedKeys(["admin-dashboard"]);

        return {
            avatarUrl,
        }
    },


})
</script>

<style>
.chart-container {
  position: relative;
  width: 100%;
  min-height: 300px;
}
</style>