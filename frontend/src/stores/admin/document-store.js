import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useDocumentStore = defineStore("document", () => {
    // State
    const document_types = ref([])
    const document_flow_templates = ref([])
    const document_templates = ref([])
    const document_flow_steps = ref([])
    const documents = ref([])

    // Actions
    async function fetchDocumentTypes() {
        try {
            const response = await axiosInstance.get("/api/document-types");
            if (response.data) {
                console.log("response document types: ", response.data);
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

    async function fetchDocumentFlowSteps(documentFlowId) {
        try {
            const response = await axiosInstance.get(`api/document-flow-steps/${documentFlowId}/`);
            if (response.data) {
                console.log("Document_flow_step: " + JSON.stringify(response.data, null, 2));
                document_flow_steps.value = response.data.map(step => ({
                    document_flow_id: step.document_flow_id,
                    step: step.step,
                    multichoice: step.multichoice,
                    department_id: step.department_id,
                }));
            }
        } catch (error) {
            console.error("Error fetching document flow steps:", error);
        }
    }

    async function fetchDocuments() {
        try {
            const response = await axiosInstance.get(`api/documents`);
            if (response.data) {
                console.log("response.data", response.data);
                documents.value = response.data.documents;
            }
        }
        catch (error) {
            console.error("Error fetching documents:", error);
        }
    }

    // fetch all
    async function fetchAll() {
        await fetchDocumentTypes();
        await fetchDocumentFlowTemplates();
        await fetchDocumentTemplates();
        await fetchDocuments();
    }

    return {
        document_types,
        document_flow_templates,
        document_templates,
        document_flow_steps,
        documents,
        
        fetchDocumentTypes,
        fetchDocumentFlowTemplates,
        fetchDocumentTemplates,
        fetchDocumentFlowSteps,
        fetchDocuments,
        fetchAll,
    };
});