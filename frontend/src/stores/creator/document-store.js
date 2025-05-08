import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useDocumentStore = defineStore("document", () => {
    // State
    const document_types = ref([])
    const document_flow_templates = ref([])
    const document_templates = ref([])
    const template_user = ref([])
    const document_flow_steps = ref([])

    // Actions
    async function fetchDocumentTypes() {
        try {
            const response = await axiosInstance.get("/api/document-types");
            if (response.data) {
                console.log("response.data", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                document_types.value = response.data.document_types;
            }
        } catch (error) {
            console.error("Error fetching document types:", error);
        }
    }

    async function fetchDocumentFlowTemplates() {
        try {
            const response = await axiosInstance.get("api/document-flows");
            if (response.data) {
                console.log("response.data", response.data);
                document_flow_templates.value = response.data.document_flow_templates;
            }
        }
        catch (error) {
            console.error("Error fetching document flow templates:", error);
        }
    }

    async function fetchDocumentTemplates() {
        try {
            const response = await axiosInstance.get("api/document-templates");
            if (response.data) {
                console.log("response.data", response.data);
                document_templates.value = response.data.document_templates;
            }
        }
        catch (error) {
            console.error("Error fetching document templates:", error);
        }
    }

    async function fetchTemplateUser(userId) {
        try {
            const response = await axiosInstance.get(`api/template-user/${userId}/`);
            template_user.value = response.data;
            if (response.data) {
                this.template_user = response.data;
            } else {
                this.template_user = null;
            }
        }
        catch (error) {
            console.error("Error fetching template user:", error);
        }
    }

    async function fetchDocumentFlowSteps(documentFlowId) {
        try {
            const response = await axiosInstance.get(`api/document-flow-steps/${documentFlowId}/`);
            if (response.data) {
                // console.log("response.data", response.data);
                const format_data = response.data.map((item) => ({
                    document_flow_id: item.document_flow_id,
                    step: item.step,
                    department_id: item.department_id,
                }));
                console.log("format_data", format_data);

                document_flow_steps.value = format_data;
            }
        } catch (error) {
            console.error("Error fetching document flow steps:", error);
        }
    }

    // fetch all
    async function fetchAll() {
        await fetchDocumentTypes();
        await fetchDocumentFlowTemplates();
        await fetchDocumentTemplates();
    }

    return {
        document_types,
        document_flow_templates,
        document_templates,
        template_user,
        document_flow_steps,
        fetchDocumentTypes,
        fetchDocumentFlowTemplates,
        fetchDocumentTemplates,
        fetchTemplateUser,
        fetchDocumentFlowSteps,
    };
});