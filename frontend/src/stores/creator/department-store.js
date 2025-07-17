import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useDepartmentStore = defineStore("department", () => {
    // State
    const departments = ref([]);
    const isFetchedDepartments = ref(false);
    const departments_can_approve = ref([]);
    const isFetchedDepartmentsCanApprove = ref(false);
    const my_department = ref([]);
    const isFetchedMyDepartment = ref(false);
    const approver_has_permissions = ref([]);
    const isFetchedApproverHasPermissions = ref(false);


    // Actions
    async function fetchDepartments(force = false) 
    {
        // Kiểm tra nếu đã fetch và không cần force thì không gọi lại API
        if (isFetchedDepartments.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get("/api/departments");
            if (response.data) {
                // console.log("response department: ", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                departments.value = response.data.departments;
                isFetchedDepartments.value = true;
            }
        } catch (error) {
            console.error("Error fetching department:", error);
        }
    }

    async function fetchDepartmentsCanApprove(force = false) 
    {
        // Kiểm tra nếu đã fetch và không cần force thì không gọi lại API
        if (isFetchedDepartmentsCanApprove.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get("/api/departments/can_approve");
            if (response.data) {
                console.log("response department can approve: ", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                departments_can_approve.value = response.data.departments;
                isFetchedDepartmentsCanApprove.value = true;
            }
        } catch (error) {
            console.error("Error fetching department can approve:", error);
        }
    } 

    async function fetchMyDepartment($id, force = false) 
    {
        // Kiểm tra nếu đã fetch và không cần force thì không gọi lại API
        if (isFetchedMyDepartment.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get(`/api/departments/${$id}`);
            if (response.data) {
                console.log("response my department: ", response.data);
                my_department.value = response.data;
                isFetchedMyDepartment.value = true;
            }
        } catch (error) {
            console.error("Error fetching my department:", error);
        }
    }

    async function updateApproverPermissions(approverId, permissions) 
    {
        try {
            const response = await axiosInstance.post(`/api/approver-has-permissions/${approverId}/update`, {
                permissions: permissions,
            });
            if (response.data.success) {
                return response.data;
            }
        } catch (error) {
            console.error("Error updating approver permissions:", error);
        }
    }

    async function fetchApproverHasPermissions(force = false)
    {
        // Kiểm tra nếu đã fetch và không cần force thì không gọi lại API
        if (isFetchedApproverHasPermissions.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get("/api/approver-has-permissions");
            if (response.data) {
                console.log("response approver has permissions: ", response.data);
                approver_has_permissions.value = response.data.approver_has_permissions;
                isFetchedApproverHasPermissions.value = true;
            }
        } catch (error) {
            console.error("Error fetching approver has permissions:", error);
        }
    }
    

    return {
        departments,
        fetchDepartments,

        departments_can_approve,
        fetchDepartmentsCanApprove,

        my_department,
        fetchMyDepartment,

        updateApproverPermissions,

        approver_has_permissions,
        fetchApproverHasPermissions,
    };
});