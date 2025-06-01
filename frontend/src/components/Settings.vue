<template>
    <div class="d-flex row">
        <span class="fs-4 col-12">Cài đặt tài khoản</span>
        <span class="col-12 text-secondary fst-italic">Quản lý thông tin cá nhân và tùy chỉnh cài đặt tài khoản của bạn</span>
    </div>

    <div class="d-flex row mt-3">
        <a-tabs 
            v-model:activeKey="activeKey" 
            type="card"
            class="row"
            >
            <a-tab-pane key="information" tab="Thông tin cá nhân">
                <a-card class="mt-2">
                    <h3 class="fw-bold">Thông tin cá nhân</h3>
                    <span class="text-secondary">Cập nhật thông tin cá nhân của bạn</span>

                    <div class="row mt-3">
                        <div class="col-12 col-md-2 align-items-center d-flex flex-column">
                            <!-- Sử dụng Ant Design Avatar -->
                            <a-avatar 
                                :size="120" 
                                :src="getAvatarUrl(user)"
                                class="user-avatar"
                            >
                                <template #icon>
                                    <UserOutlined />
                                </template>
                            </a-avatar>
                            
                            <!-- Upload button với icon -->
                            <a-upload
                                :show-upload-list="false"
                                :before-upload="beforeUpload"
                                accept="image/*"
                            >
                                <a-button type="primary" class="mt-2">
                                    <span><i class="bi bi-upload me-2"></i>Đổi ảnh</span>    
                                </a-button>
                            </a-upload>
                        </div>
                        <form @submit.prevent="changeInformation" class="col-12 col-md-10 mt-3 mt-md-0">
                            <div class="row justify-content-center ">
                                <div class="col-12 col-md-4">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Họ và tên</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-1">
                                        <a-input v-model:value="name" :placeholder="user.name" allow-clear/>
                                        <div class="w-100"></div>
                                        <!-- 
                                    <small 
                                        v-if="errors.status_id && firstFieldError === 'status_id'" 
                                        class="text-danger">
                                            {{ errors.status_id[0] }}
                                    </small> -->
                                    </div>
                                </div>

                                <div class="col-12 col-md-5 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Phòng/Khoa/Đơn vị</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-sm-12 mt-1">
                                        <div class="d-flex align-items-center">
                                            <a-select 
                                            v-model:value="selectedDepartment"
                                            show-search 
                                            placeholder="Phòng/Khoa/Đơn vị" 
                                            style="width: 100%"
                                            :options="departments" 
                                            :filter-option="filterOption" 
                                            allow-clear
                                            :list-height="160"
                                            ></a-select>
                                            
                                            <a-tooltip placement="right" title="Đơn vị của bạn không có trong danh sách? Gửi mail cho admin bằng tài khoản outlock của trường">
                                                <QuestionCircleOutlined 
                                                    class="ms-2 text-primary"
                                                    style="font-size: 18px; cursor: pointer;"
                                                    @click="showRequestNewDepartmentModal"
                                                />
                                            </a-tooltip>
                                        </div>

                                        <div class="w-100"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Vai trò</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-sm-12 mt-1">
                                        <div class="d-flex">
                                            <a-select 
                                            v-model:value="selectedRoll"
                                            show-search 
                                            placeholder="Vai trò" 
                                            style="width: 100%"
                                            :options="rolls" 
                                            :filter-option="filterOption" 
                                            allow-clear
                                            :list-height="160"
                                            ></a-select>
                                        </div>

                                        <div class="w-100"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-start mt-0 mt-md-3">
                                <div class="col-12 col-md-4 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Email</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-1">
                                        <a-tooltip  placement="topRight">
                                            <template #title>
                                                <span class="">Email không thể thay đổi</span>
                                            </template>
                                            <a-input 
                                                v-model:value="email" 
                                                :placeholder="user.email"
                                                disabled    
                                            />
                                        </a-tooltip>

                                        <div class="w-100"></div>
                                        <!-- 
                                    <small 
                                        v-if="errors.status_id && firstFieldError === 'status_id'" 
                                        class="text-danger">
                                            {{ errors.status_id[0] }}
                                    </small> -->
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Số điện thoại</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-1">
                                        <a-input 
                                            v-model:value="phone" 
                                            :placeholder="user.phone || 'Cập nhật số điện thoại'"
                                            type="tel"
                                            pattern="[0-9]{10}"
                                            allow-clear    
                                        />
                                        <div class="w-100"></div>
                                    </div>
                                </div>       
                                
                                <div class="col-12 col-md-4 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Giới tính</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-2">
                                        <a-radio-group v-model:value="sex" name="radioGroup">
                                            <a-radio value="Male">Nam</a-radio>
                                            <a-radio value="Female">Nữ</a-radio>
                                        </a-radio-group>
                                        <div class="w-100"></div>
                                    </div>
                                </div>                                      
                            </div>
                            
                            <a-divider style="border-color: #7cb305" dashed />

                            <div class="row justify-content-start mt-3">
                                <div class="col-12 col-sm-12 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1"></span>
                                        <span class="fw-bold">Giới thiệu</span>
                                    </label>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-12 col-sm-12 mt-1">
                                    <a-textarea 
                                        v-model:value="description" 
                                        :placeholder="user.description || 'Cập nhật mô tả bản thân'"
                                        rows="3"
                                        :show-count="true"
                                        :maxlength="500"
                                        allow-clear    
                                    />
                                    <div class="w-100"></div>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-3">
                                <div class="col-12 col-sm-12 text-end">
                                    <a-button 
                                        type="primary" 
                                        @click="changeInformation"
                                        class="mt-2"
                                    >
                                        <span><i class="bi bi-save me-2"></i>Lưu thay đổi</span>
                                    </a-button>
                                </div>
                                <div class="w-100"></div>
                            </div>
                        </form>
                    </div>
                </a-card>
            </a-tab-pane>
            <a-tab-pane key="notification" tab="Thông báo" force-render>
                <a-card class="mt-2">
                    <h3 class="fw-bold">Tùy</h3>
                    <span class="text-secondary">Cập nhật thông tin cá nhân của bạn</span>

                    <div class="row mt-3">
                        <div class="col-12 col-md-2 align-items-center d-flex flex-column">
                            <!-- Sử dụng Ant Design Avatar -->
                            <a-avatar 
                                :size="120" 
                                :src="getAvatarUrl(user)"
                                class="user-avatar"
                            >
                                <template #icon>
                                    <UserOutlined />
                                </template>
                            </a-avatar>
                            
                            <!-- Upload button với icon -->
                            <a-upload
                                :show-upload-list="false"
                                :before-upload="beforeUpload"
                                accept="image/*"
                            >
                                <a-button type="primary" class="mt-2">
                                    <span><i class="bi bi-upload me-2"></i>Đổi ảnh</span>    
                                </a-button>
                            </a-upload>
                        </div>
                        <form @submit.prevent="changeInformation" class="col-12 col-md-10 mt-3 mt-md-0">
                            <div class="row justify-content-center ">
                                <div class="col-12 col-md-4">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Họ và tên</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-1">
                                        <a-input v-model:value="name" :placeholder="user.name" allow-clear/>
                                        <div class="w-100"></div>
                                        <!-- 
                                    <small 
                                        v-if="errors.status_id && firstFieldError === 'status_id'" 
                                        class="text-danger">
                                            {{ errors.status_id[0] }}
                                    </small> -->
                                    </div>
                                </div>

                                <div class="col-12 col-md-5 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Phòng/Khoa/Đơn vị</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-sm-12 mt-1">
                                        <div class="d-flex align-items-center">
                                            <a-select 
                                            v-model:value="selectedDepartment"
                                            show-search 
                                            placeholder="Phòng/Khoa/Đơn vị" 
                                            style="width: 100%"
                                            :options="departments" 
                                            :filter-option="filterOption" 
                                            allow-clear
                                            :list-height="160"
                                            ></a-select>
                                            
                                            <a-tooltip placement="right" title="Đơn vị của bạn không có trong danh sách? Gửi mail cho admin bằng tài khoản outlock của trường">
                                                <QuestionCircleOutlined 
                                                    class="ms-2 text-primary"
                                                    style="font-size: 18px; cursor: pointer;"
                                                    @click="showRequestNewDepartmentModal"
                                                />
                                            </a-tooltip>
                                        </div>

                                        <div class="w-100"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Vai trò</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-sm-12 mt-1">
                                        <div class="d-flex">
                                            <a-select 
                                            v-model:value="selectedRoll"
                                            show-search 
                                            placeholder="Vai trò" 
                                            style="width: 100%"
                                            :options="rolls" 
                                            :filter-option="filterOption" 
                                            allow-clear
                                            :list-height="160"
                                            ></a-select>
                                        </div>

                                        <div class="w-100"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-start mt-0 mt-md-3">
                                <div class="col-12 col-md-4 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1">*</span>
                                            <span class="fw-bold">Email</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-1">
                                        <a-tooltip  placement="topRight">
                                            <template #title>
                                                <span class="">Email không thể thay đổi</span>
                                            </template>
                                            <a-input 
                                                v-model:value="email" 
                                                :placeholder="user.email"
                                                disabled    
                                            />
                                        </a-tooltip>

                                        <div class="w-100"></div>
                                        <!-- 
                                    <small 
                                        v-if="errors.status_id && firstFieldError === 'status_id'" 
                                        class="text-danger">
                                            {{ errors.status_id[0] }}
                                    </small> -->
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">SĐT:</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-1">
                                        <a-input 
                                            v-model:value="phone" 
                                            :placeholder="user.phone || 'Cập nhật số điện thoại'"
                                            type="tel"
                                            pattern="[0-9]{10}"
                                            allow-clear    
                                        />
                                        <div class="w-100"></div>
                                    </div>
                                </div>       
                                
                                <div class="col-12 col-md-4 mt-2 mt-md-0">
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Giới tính:</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-2">
                                        <a-radio-group v-model:value="sex" name="radioGroup">
                                            <a-radio value="Male">Nam</a-radio>
                                            <a-radio value="Female">Nữ</a-radio>
                                        </a-radio-group>
                                        <div class="w-100"></div>
                                    </div>
                                </div>                                      
                            </div>
                            
                            <a-divider style="border-color: #7cb305" dashed />

                            <div class="row justify-content-start mt-3">
                                <div class="col-12 col-sm-12 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1"></span>
                                        <span class="fw-bold">Giới thiệu:</span>
                                    </label>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-12 col-sm-12 mt-1">
                                    <a-textarea 
                                        v-model:value="description" 
                                        :placeholder="user.description || 'Cập nhật mô tả bản thân'"
                                        rows="3"
                                        :show-count="true"
                                        :maxlength="500"
                                        allow-clear    
                                    />
                                    <div class="w-100"></div>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-3">
                                <div class="col-12 col-sm-12 text-end">
                                    <a-button 
                                        type="primary" 
                                        @click="changeInformation"
                                        class="mt-2"
                                    >
                                        <span><i class="bi bi-save me-2"></i>Lưu thay đổi</span>
                                    </a-button>
                                </div>
                                <div class="w-100"></div>
                            </div>
                        </form>
                    </div>
                </a-card>
            </a-tab-pane>
            <a-tab-pane key="user_interface" tab="Giao diện" force-render>
                <a-card class="mt-2">
                    <h3 class="fw-bold">Tùy chỉnh giao diện</h3>
                    <span class="text-secondary">Điều chỉnh giao diện theo sở thích</span>

                    <div class="row mt-3">
                        <form @submit.prevent="changeInformation" class="col-12 mt-3">
                            <div class="row justify-content-center ">
                                <div class="col-12">
                                    <div class="col-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Chế độ hiển thị</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 mt-1">
                                        <a-radio-group v-model:value="theme_color" name="radioGroup" class="row" size="large">
                                            <a-radio-button class="col mx-2 text-center" value="bright">
                                                <span>
                                                    <i class="bi bi-brightness-high me-2"></i>Sáng
                                                </span>
                                            </a-radio-button>
                                            <a-radio-button class="col mx-2 text-center" value="dark">
                                                <span>
                                                    <i class="bi bi-moon me-2"></i>Tối
                                                </span>
                                            </a-radio-button>
                                            <a-radio-button class="col mx-2 text-center" value="system">
                                                <span>
                                                    <i class="bi bi-laptop me-2"></i>Hệ thống
                                                </span>
                                            </a-radio-button>
                                        </a-radio-group>
                                        <div class="w-100"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <a-divider style="border-color: #b3b3b3" />

                            <div class="row justify-content-start mt-3">
                                <div class="col-12 text-start align-self-center">
                                    <label>
                                        <span class="text-danger me-1"></span>
                                        <span class="fw-bold">Ngôn ngữ</span>
                                    </label>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-3 mt-1">
                                    <div class="row align-items-center">
                                        <span class="col-2 text-center pe-0"><i class="bi bi-globe"></i></span>
                                        <a-select 
                                            v-model:value="language" 
                                            :options="languages" 
                                            style="width: 100%"
                                            placeholder="Chọn ngôn ngữ"
                                            allow-clear
                                            class="col px-0"
                                        />
                                    </div>
                                    <div class="w-100"></div>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-3">
                                <div class="col-12 col-sm-12 text-end">
                                    <a-button 
                                        type="primary" 
                                        @click="changeUserInterface"
                                        class="mt-2"
                                    >
                                        <span><i class="bi bi-save me-2"></i>Lưu thay đổi</span>
                                    </a-button>
                                </div>
                                <div class="w-100"></div>
                            </div>
                        </form>
                    </div>
                </a-card>
            </a-tab-pane>
            <a-tab-pane key="security" tab="Bảo mật" force-render>
                <a-card class="mt-2">
                    <h3 class="fw-bold">Bảo mật tài khoản</h3>
                    <span class="text-secondary">Quản lý mật khẩu và bảo mật tài khoản của bạn</span>

                    <div class="row mt-3">
                        <div class="col-12 col-md-2 align-items-center">
                            <span>Thay đổi mật khẩu</span>
                        </div>
                        <form @submit.prevent="changeInformation" class="col-12 mt-3">
                            <div class="row justify-content-center ">
                                <div class="col-12 col-md-4">
                                    <div class="col-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Mật khẩu hiện tại</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 mt-1">
                                        <a-input-password 
                                            v-model:value="current_password" 
                                            :placeholder="'Nhập mật khẩu hiện tại'" 
                                            allow-clear
                                            class="mb-2"
                                        >
                                            <template #addonBefore>
                                                <i class="bi bi-key"></i>
                                            </template>
                                        </a-input-password>
                                        <div class="w-100"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="col-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Mật khẩu mới</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 mt-1">
                                        <a-input-password 
                                            v-model:value="new_password" 
                                            :placeholder="'Nhập mật khẩu mới'"
                                            class="mb-2"
                                        >
                                            <template #addonBefore>
                                                <i class="bi bi-key"></i>
                                            </template>
                                        </a-input-password>
                                        <div class="w-100"></div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-4">
                                    <div class="col-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Xác nhận mật khẩu mới</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 mt-1">
                                        <a-input-password 
                                            v-model:value="confirm_password" 
                                            :placeholder="'Xác nhận mật khẩu mới'" 
                                            allow-clear
                                            class="mb-2"
                                        >
                                            <template #addonBefore>
                                                <i class="bi bi-key"></i>
                                            </template>
                                        </a-input-password>
                                        <div class="w-100"></div>
                                    </div>
                                </div>                                
                            </div>

                            <a-divider style="border-color: #b3b3b3" />

                            <div class="row justify-content-end mt-3">
                                <div class="col-12 col-sm-12 text-end">
                                    <a-button 
                                        type="primary" 
                                        @click="changeSecurity"
                                        class="mt-2"
                                    >
                                        <span><i class="bi bi-save me-2"></i>Lưu thay đổi</span>
                                    </a-button>
                                </div>
                                <div class="w-100"></div>
                            </div>
                        </form>
                    </div>
                </a-card>
            </a-tab-pane>
        </a-tabs>
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

import { UserOutlined, UploadOutlined, QuestionCircleOutlined } from '@ant-design/icons-vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/stores/use-auth.js';
import { useRegisterStore } from '@/stores/use-register.js';
export default defineComponent({
    components: {
        UserOutlined,
        UploadOutlined,
        QuestionCircleOutlined,
    },
    setup() {
        const activeKey = ref('information'); // Default active tab

        const authStore = useAuth();
        const registerStore = useRegisterStore();
        registerStore.fetchRegisterForm();
        const user = computed(() => authStore.user);
        const user_detail = computed(() => authStore.user_details);
        console.log('User Details:', user_detail.value);
        const router = useRouter();

        // For information tab
        const name = ref(user.value.name || '');
        const email = ref(user.value.email || '');
        const phone = ref(user.value.phone || '');
        const description = ref(user.value.description || '');
        const role = authStore.role;
        console.log('Role:', role);
        const selectedDepartment = ref(user_detail.value.department_id || null);
        const selectedRoll = ref(user_detail.value.roll_at_department_id || null);
        const sex = ref(user.value.sex || 'Male');
        const departments = computed(() => registerStore.departments);
        const rolls = computed(() => registerStore.rolls);

        const randomAvatar = (id) => {
            if (id > 100 || id == null) {
                return `https://avatar.iran.liara.run/public`;
            }
            return `https://avatar.iran.liara.run/public/${id}`;
        };

        const getAvatarUrl = (user) => {
            if (!user) return randomAvatar(null);
            const API_BASE_URL = 'http://localhost:8000';
            if (user.avatar === null) return randomAvatar(user.id);
            return `${API_BASE_URL}/images/avatars/${user.avatar}`;
        };

        const handleClickChangeAvatar = () => {
            // Logic to change avatar
            console.log('Change avatar clicked');
            // You can implement a modal or file input to change the avatar
        };

        const changeInformation = () => {
            // Logic to change user information
            console.log('Change information clicked');
        };

        // For notification tab

        // For user interface tab
        const theme_color = ref('system'); // Default theme color
        const languages = ref([
            { value: 'en', label: 'English' },
            { value: 'vi', label: 'Tiếng Việt' },
            // Add more languages as needed
        ]);
        const language = ref('vi'); // Default language

        const changeUserInterface = () => {
            // Logic to change user interface settings
            console.log('Change user interface clicked');
        };

        // For security tab
        const current_password = ref('');
        const new_password = ref('');
        const confirm_password = ref('');

        const changeSecurity = () => {
            // Logic to change security settings
            console.log('Change security clicked');
        };

        return {
            activeKey,
            user,
            name,
            email,
            phone,
            description,
            selectedDepartment,
            selectedRoll,
            sex,
            departments,
            rolls,

            theme_color,
            languages,
            language,

            current_password,
            new_password,
            confirm_password,

            getAvatarUrl,
            handleClickChangeAvatar,
            changeInformation,    

            changeUserInterface,

            changeSecurity,
        };
    },
});
</script>