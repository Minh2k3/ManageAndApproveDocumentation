import { defineStore } from 'pinia';
import { ref } from "vue";
import axiosInstance from '@/lib/axios';

export const useRegisterStore = defineStore("register", () => { 
    const departments = ref([]);
    const rolls = ref([]);
    const isFetched = ref(false);
    
    async function fetchRegisterForm(force = false) {
        if (isFetched.value && !force) {
            return;
        }
        // await axiosInstance.get("sanctum/csrf-cookie");
        try {
            await axiosInstance
                .get("api/register-options")
                .then((response) => {
                    console.log("Response:", response.data);
                    departments.value = response.data.departments;
                    rolls.value = response.data.rolls;
                    isFetched.value = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        } catch (error) {
            console.error("Lỗi khi lấy dữ liệu:", error);
        }
    };


    function reset() {
        departments.value = [];
        rolls.value = [];
        isFetched.value = false;
    }

    return {
        departments,
        rolls,
        isFetched,
        
        fetchRegisterForm,
        reset,
    };
});