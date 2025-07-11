import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useDepartmentStore = defineStore("department", () => {
    // State
    const departments = ref([]);
    const departments_can_approve = ref([]);

    // Actions
    async function fetchDepartments() {
        try {
            const response = await axiosInstance.get("/api/departments");
            if (response.data) {
                // console.log("response department: ", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                departments.value = response.data.departments;
            }
        } catch (error) {
            console.error("Error fetching department:", error);
        }
    }

    async function fetchDepartmentsCanApprove() {
        try {
            const response = await axiosInstance.get("/api/departments/can_approve");
            if (response.data) {
                console.log("response department can approve: ", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                departments_can_approve.value = response.data.departments;
            }
        } catch (error) {
            console.error("Error fetching department can approve:", error);
        }
    } 

    return {
        departments,
        fetchDepartments,

        departments_can_approve,
        fetchDepartmentsCanApprove,
    };
});