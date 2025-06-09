<template>
    <form @submit.prevent="createUsers">
        <a-card title="Tạo mới tài khoản">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 justify-content-center d-flex mb-3">
                            <a-avatar shape="square" :size="200">
                                <template #icon>
                                    <img src="../../../assets/images/Cosette.jpg" alt="Avatar">
                                </template>
                            </a-avatar>
                        </div>

                        <div class="col-12 justify-content-center d-flex ">
                            <a-button type="primary">
                                <i class="fa-solid fa-plus me-2"></i>
                                Chọn ảnh
                            </a-button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <!-- Họ và tên -->
                    <div class="row mt-3">
                        <div class="col-12 col-lg-3 text-start text-lg-end align-self-center">
                            <label>
                                <span class="text-danger me-1">*</span>
                                <span>Họ và tên</span>
                            </label>
                        </div>
                        <div class="col-12 col-lg-5">
                            <a-input 
                                v-model:value="name"
                                placeholder="Trần Tuấn Minh" 
                                allow-clear 
                                :class="{
                                    'input-danger': firstFieldError === 'name'
                                }"
                            />

                            <div class="w-100"></div>
                            
                            <small 
                                v-if="errors.name && firstFieldError === 'name'" 
                                class="text-danger">
                                    {{ errors.name[0] }}
                            </small>

                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row mt-3">
                        <div class="col-12 col-lg-3 text-start text-lg-end align-self-center">
                            <label>
                                <span class="text-danger me-1">*</span>
                                <span>Email</span>
                            </label>
                        </div>
                        <div class="col-12 col-lg-5">
                            <a-input 
                                v-model:value="email"
                                placeholder="2151062831@e.tlu.edu.vn" 
                                allow-clear
                                :class="{
                                    'input-danger': firstFieldError === 'email'
                                }"
                            />
                            
                            <div class="w-100"></div>
                            
                            <small 
                                v-if="errors.email && firstFieldError === 'email'" 
                                class="text-danger">
                                    {{ errors.email[0] }}
                            </small>

                        </div>
                    </div>

                    <!-- Phòng ban -->
                    <div class="row mt-3">
                        <div class="col-12 col-lg-3 text-start text-lg-end align-self-center">
                            <label>
                                <span class="text-danger me-1">*</span>
                                <span>Phòng ban</span>
                            </label>
                        </div>
                        <div class="col-12 col-lg-5">
                            <!-- Nhóm select + button chung hàng -->
                            <div class="d-flex">
                                <a-select 
                                v-model:value="department_id"
                                show-search 
                                placeholder="Phòng ban" 
                                style="width: 100%"
                                :options="departments" 
                                :filter-option="filterOption" 
                                allow-clear
                                :list-height="150"
                                />

                                <a-button 
                                    type="default" 
                                    class="ms-2" 
                                    @click="handleAddDepartment"
                                    style="background-color: #d9d9d9;">
                                <i class="fa-solid fa-plus"></i>
                                </a-button>
                            </div>

                            <!-- Lỗi nằm dưới hàng mới -->
                            <small 
                                v-if="errors.department_id && firstFieldError === 'department_id'" 
                                class="text-danger mt-1 d-block">
                                    {{ errors.department_id[0] }}
                            </small>
                            </div>
                    </div>  
                    
                    <!-- Vai trò -->
                    <div class="row mt-3">
                        <div class="col-12 col-lg-3 text-start text-lg-end align-self-center">
                            <label>
                                <span class="text-danger me-1">*</span>
                                <span>Vai trò</span>
                            </label>
                        </div>
                        <div class="col-12 col-lg-5">
                            <!-- Nhóm select + button chung hàng -->
                            <div class="d-flex">
                                <a-select 
                                    v-model:value="selectedRoll"
                                    show-search 
                                    placeholder="Chọn vai trò" 
                                    style="width: 100%"
                                    :options="rolls" 
                                    :filter-option="filterOption" 
                                    allow-clear
                                    :dropdown-match-select-width="false"
                                    :list-height="150"
                                />

                                <a-button 
                                    type="default" 
                                    class="ms-2" 
                                    @click="handleAddRoll"
                                    style="background-color: #d9d9d9;">
                                <i class="fa-solid fa-plus"></i>
                                </a-button>
                            </div>

                            <!-- Lỗi nằm dưới hàng mới -->
                            <small 
                                v-if="errors.department_id && firstFieldError === 'department_id'" 
                                class="text-danger mt-1 d-block">
                                    {{ errors.department_id[0] }}
                            </small>
                            </div>
                    </div>  

                    <div class="row mt-5">
                        <div class="col-12 d-grid justify-content-lg-end d-lg-flex mx-auto">
                            <a-button type="default" class="" style="background-color: #d9d9d9;">
                                <router-link :to="{ name: 'admin-users' }">
                                    <span>Hủy</span>
                                </router-link>
                            </a-button>

                            <a-button type="primary" class="ms-0 ms-lg-2 mt-2 mt-lg-0" html-type="submit">
                                Lưu
                            </a-button>
                        </div>
                    </div>
                </div>
            </div>
        </a-card>
    </form>
</template>

<script>
import { 
    defineComponent, 
    ref, 
    reactive, 
    toRefs, 
    onMounted,
    watch,
    onUnmounted 
} from "vue";
import { useMenu } from "@/stores/use-menu.js";
import axios from "axios";
import {useRegisterStore} from "@/stores/use-register.js";
export default defineComponent({
    setup() {
        useMenu().onSelectedKeys(["admin-users"]);
        const useRegister = useRegisterStore();
        
        // Danh sách phòng ban và vai trò
        const departments = ref([]);
        const rolls = ref([]);

        onMounted(async () => {
            await useRegister.fetchRegisterForm();
            departments.value = useRegister.departments;
            rolls.value = useRegister.rolls;
            console.log("Đã gọi hàm getUsersRegister()");
        });

        const users = reactive({
            username: "",
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            department_id: [],
            status_id: [],
        });

        const errors = ref({});

        const filterOption = (input, option) => {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        };

        const firstFieldError = ref(null);
        const firstError = ref(null);

        const createUsers = () => {
            axios.post("http://127.0.0.1:8000/api/users", users)
                .then((response) => {
                    console.log(response.data);

                    errors.value = {};
                    firstFieldError.value = null;
                    firstError.value = null;

                    alert("Tạo tài khoản thành công!");

                    users.username = "";
                    users.name = "";
                    users.email = "";
                    users.password = "";
                    users.password_confirmation = "";
                    users.department_id = [];
                    users.status_id = [];
                })
                .catch((error) => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        errors.value = error.response.data.errors;
                        console.log(errors.value);
                        const field = Object.keys(errors.value)[0];
                        firstFieldError.value = field;
                        firstError.value = errors.value[field][0];
                        console.log("trường bị lỗi: " + firstFieldError.value);
                        console.log("tên lỗi: " + firstError.value);
                    }
                });
        }

        const handleAddDepartment = () => {
            // Logic to add a new department
            console.log("Thêm phòng ban mới");
        };

        const handleAddRoll = () => {
            // Logic to add a new roll
            console.log("Thêm vai trò mới");
        };

        return {
            departments,
            rolls,
            ...toRefs(users),
            errors,
            filterOption,
            createUsers,
            firstFieldError,
            firstError,
            handleAddDepartment,
            handleAddRoll,
        }
    },

})
</script>

<style>
    .select-danger {
    border: 1px solid red;
    }

    .input-danger {
    border-color: red;
    }
</style>