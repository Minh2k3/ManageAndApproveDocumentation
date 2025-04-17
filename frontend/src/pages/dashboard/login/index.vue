<template>
    <div class="container-fluid vh-100 overflow-hidden" style="background-color: aquamarine;">

        <div class="row h-100">
            <!-- Cột trái -->
            <div class="d-sm-flex col-sm-6 d-none">
                <div class="col-12">
                    <div class="row mt-3">
                        <div class="col-12 justify-content-center d-flex mb-1 align-items-center">
                            <a-avatar shape="square" :size="50" style="background-color: transparent;">
                                <template #icon>
                                    <img src="@/assets/images/logo_tlu.png" alt="logo_tlu"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                </template>
                            </a-avatar>
                            <a-avatar shape="square" :size="50" style="background-color: transparent;" class="mx-3">
                                <template #icon>
                                    <img src="@/assets/images/logo_dtn.png" alt="logo_dtn"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                </template>
                            </a-avatar>
                            <a-avatar shape="square" :size="45" style="background-color: transparent;">
                                <template #icon>
                                    <img src="@/assets/images/logo_hsv.png" alt="logo_hsv"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                </template>
                            </a-avatar>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 justify-content-center">
                            <h3 class="text-center mb-0 p-1">TRƯỜNG ĐẠI HỌC THỦY LỢI</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 justify-content-center d-flex mb-3">
                            <h4 class="text-center col-12">Đoàn Thanh niên - Hội Sinh viên</h4>
                        </div>
                    </div>

                    <div class="row h-100 ">
                        <div class="d-flex align-items-center ">
                            <h1 class="fs-1 ">Hệ Thống Quản Lý và Phê Duyệt Văn Bản Điện Tử</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="col-12 col-sm-6 d-flex justify-content-center rounded-start-5 align-items-center"
                style="background-color: #f8f9fa;">
                <div class="row w-100">
                    <div class="col-12">
                        <!-- Nội dung cột giữa -->
                        <div class="row">
                            <div class="justify-content-center">
                                <h1 class="text-center fw-bold mb-0">ĐĂNG NHẬP</h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 justify-content-center">
                                <h4 class="text-center text-secondary fst-italic">Nhập thông tin để truy cập hệ thống
                                </h4>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-8 col-sm-6 justify-content-center">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary p-2" type="button">
                                        <MailOutlined class="me-2" style="color: blue;" />
                                        Đăng nhập với Outlock
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-2">
                            <div class="col-12 col-sm-6 d-flex align-items-center my-3">
                                <div class="flex-grow-1 border-bottom"></div>
                                <div class="px-2 text-muted fw-semibold small">HOẶC ĐĂNG NHẬP VỚI</div>
                                <div class="flex-grow-1 border-bottom"></div>
                            </div>
                        </div>

                        <div class="row justify-content-center ">
                            <div class="col-sm-6">
                                <div class="col-12 col-sm-12 text-start align-self-center">
                                    <label>
                                        <span>Email</span>
                                    </label>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-12 col-sm-12 mt-1">
                                    <a-input v-model:value="email" placeholder="2151062831@e.tlu.edu.vn" allow-clear
                                        :class="{
                                            'input-danger': firstFieldError === 'email'
                                        }" />

                                    <div class="w-100"></div>
                                    <!-- 
                                <small 
                                    v-if="errors.status_id && firstFieldError === 'status_id'" 
                                    class="text-danger">
                                        {{ errors.status_id[0] }}
                                </small> -->
                                </div>
                            </div>

                        </div>

                        <!-- Mật khẩu -->
                        <div class="row justify-content-center mt-3">
                            <div class="col-sm-6">

                                <div class="col-12 col-sm-3 text-start align-self-center">
                                    <label>
                                        <span>Mật khẩu</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-12 mt-1">
                                    <a-input-password v-model:value="password" placeholder="abcILoveYou123"
                                        allow-clear />

                                    <div class="w-100"></div>

                                    <!-- <small
                                    v-if="errors.password && firstFieldError === 'password' && firstError !== 'Mật khẩu không khớp.'"
                                    class="text-danger">
                                    {{ errors.password[0] }}
                                </small> -->
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-2">
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <!-- Checkbox ghi nhớ -->
                                    <div>
                                        <a-checkbox v-model:checked="rememberMe">Ghi nhớ đăng nhập</a-checkbox>
                                    </div>
                                    <!-- Quên mật khẩu -->
                                    <div>
                                        <a href="#" class="text-decoration-none text-primary small fst-italic">Quên mật
                                            khẩu?</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-12 col-sm-6 justify-content-center">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary p-2" type="button" style="background-color: #0d6efd;
                                    border-color: #0d6efd;
                                    color: #ffffff;">
                                        Đăng nhập
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 justify-content-center d-flex">
                                <span class="text-center text-secondary mx-2">Chưa có tài khoản?</span>
                                <router-link :to="{ name: 'register' }">
                                    <span class="text-center text-secondary fst-italic">Đăng ký</span>
                                </router-link>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { MailOutlined } from "@ant-design/icons-vue";

export default {
    components: {
        MailOutlined,
    },
};
</script>

<style></style>