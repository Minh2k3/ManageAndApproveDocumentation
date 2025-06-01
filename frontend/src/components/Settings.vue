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
                                    <div class="col-12 col-sm-12 text-start align-self-center">
                                        <label>
                                            <span class="text-danger me-1"></span>
                                            <span class="fw-bold">Chế độ hiển thị</span>
                                        </label>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 col-sm-12 mt-1">
                                        <a-radio-group v-model:value="theme_color" name="radioGroup" class="row d-flex gap-2">
                                            <a-radio-button class="col rounded border border-1 border-dark" value="bright">Sáng</a-radio-button>
                                            <a-radio-button class="col rounded border border-1 border-dark" value="dark">Tối</a-radio-button>
                                            <a-radio-button class="col rounded border border-1 border-dark" value="system">Hệ thống</a-radio-button>
                                        </a-radio-group>
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
            <a-tab-pane key="security" tab="Bảo mật" force-render>
                Bảo mật
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
export default defineComponent({
    components: {
        UserOutlined,
        UploadOutlined,
        QuestionCircleOutlined,
    },
    setup() {
        const activeKey = ref('information');

        const authStore = useAuth();
        const user = computed(() => authStore.user);
        const router = useRouter();

        const sex = ref(user.value.sex || 'Male');

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

        return {
            activeKey,
            user,
            sex,

            getAvatarUrl,
            handleClickChangeAvatar,
            changeInformation,    
        };
    },
});
</script>