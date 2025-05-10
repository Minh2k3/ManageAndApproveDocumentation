import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useApproverStore = defineStore("approver", () => {
    // State
    const approvers = ref([]);
    const approvers_with_roll = ref([]);
    const approves_by_department = ref([]);

    // Actions
    async function fetchApprovers() {
        await axiosInstance.get("/sanctum/csrf-cookie");
        try {
            const response = await axiosInstance.get("/api/approvers");
            if (response.data) {
                console.log("response approvers: ", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                approvers.value = response.data.approvers;
            }
        } catch (error) {
            console.error("Error fetching approvers:", error);
        }
    }

    async function fetchApproversWithRoll() {
        await axiosInstance.get("/sanctum/csrf-cookie");
        try {
            const response = await axiosInstance.get("/api/approvers/with_roll");
            if (response.data) {
                console.log("response approvers with roll: ", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                approvers_with_roll.value = response.data.approvers;
            }
        } catch (error) {
            console.error("Error fetching approvers with roll:", error);
        }
    }

    async function fetchApproverByDepartmentId(department_id) {
        await axiosInstance.get("/sanctum/csrf-cookie");
        try {
            const response = await axiosInstance.get(`api/approvers/${department_id}/`);
            if (response.data) {
                // console.log("response.data", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                approves_by_department.value = response.data.approves_by_department;
            }
        }
        catch (error) {
            console.error("Error fetching template user:", error);
        }
    }

    return {
        approvers,
        fetchApprovers,

        approvers_with_roll,
        fetchApproversWithRoll,

        approves_by_department,
        fetchApproverByDepartmentId,

    };
});