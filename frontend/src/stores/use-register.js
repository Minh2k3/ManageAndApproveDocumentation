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

    async function addDepartment(department) {
        try {
            // await axiosInstance.get("sanctum/csrf-cookie"); // Ensure CSRF token is set
            const response = await axiosInstance.post("api/departments", department);
            if (response.data) {
                console.log("Dữ liệu trả về: " + JSON.stringify(response.data.id, null, 2));
            }
            return response.data.id; 
        } catch (error) {
            console.error("Error adding department:", error);
        }
    }

    async function addRoll(roll) {
        try {
            const response = await axiosInstance.post("api/rolls", roll);
            rolls.value.push(response.data);
        } catch (error) {
            console.error("Error adding roll:", error);
        }
    }


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
        addDepartment,
        addRoll,
        reset,
    };
});