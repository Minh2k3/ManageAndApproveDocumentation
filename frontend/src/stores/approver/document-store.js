import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useDocumentStore = defineStore("document", () => {
    // State
    const document_types = ref([])
    const isFetchedDocumentTypes = ref(false)
    const document_flow_templates = ref([])
    const isFetchedDocumentFlowTemplates = ref(false)
    const document_templates = ref([])
    const isFetchedDocumentTemplates = ref(false)
    const template_user = ref([])
    const isFetchedTemplateUser = ref(false)
    const document_flow_steps = ref([])
    const isFetchedDocumentFlowSteps = ref(false)
    const documents = ref([])
    const isFetchedDocuments = ref(false)
    const document_comments = ref([])

    // Actions
    async function fetchDocumentTypes(force = false) {
        if (isFetchedDocumentTypes.value && !force) {
            return;
        }

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

    async function fetchDocumentFlowTemplates(force = false) {
        if (isFetchedDocumentFlowTemplates.value && !force) {
            return;
        }

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

    async function fetchDocumentTemplates(force = false) {
        if (isFetchedDocumentTemplates.value && !force) {
            return;
        }

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

    async function fetchTemplateUser(id, force = false) {
        if (isFetchedTemplateUser.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get(`api/template-user/${id}/`);
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
                console.log("Document_flow_step: " + JSON.stringify(response.data, null, 2));
                document_flow_steps.value = response.data.map(step => ({
                    document_flow_id: step.document_flow_id,
                    step: step.step,
                    multichoice: step.multichoice,
                    department_id: step.department_id,  
                    approver_id: step.approver_id,
                }));
            }
        } catch (error) {
            console.error("Error fetching document flow steps:", error);
        }
    }

    async function fetchDocuments(id, force = false) {
        if (isFetchedDocuments.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get(`api/approvers/${id}/documents`);
            if (response.data) {
                isFetchedDocuments.value = true;
                console.log("response.data", response.data);
                documents.value = response.data.documents;
            }
        }
        catch (error) {
            console.error("Error fetching documents:", error);
        }
    }

    async function fetchDocumentComments(documentId) {
        try {
            const response = await axiosInstance.get(`api/documents/${documentId}/comments`);
            if (response.data) {
                console.log("Document_comments: " + JSON.stringify(response.data, null, 2));
                document_comments.value = response.data;
            }
        } catch (error) {
            console.error("Error fetching document comments:", error);
        }
    }

    async function fetchDocumentById(id) {
        try {
            const response = await axiosInstance.get(`api/documents/${id}`);
            if (response.data) {
                console.log("Document: " + JSON.stringify(response.data, null, 2));
                return response.data;
            }
        } catch (error) {
            console.error("Error fetching document by ID:", error);
        }
    }

    async function rejectDocument(id, data) {
        try {
            const response = await axiosInstance.post(`api/documents/${id}/reject`, data);
            if (response.data) {
                console.log("Reject document response: ", response.data);
                return response.data;
            }
        } catch (error) {
            console.error("Error rejecting document:", error);
        }
    }

    function reset() {
        document_types.value = []
        isFetchedDocumentTypes.value = false
        document_flow_templates.value = []
        isFetchedDocumentFlowTemplates.value = false
        document_templates.value = []
        isFetchedDocumentTemplates.value = false
        template_user.value = []
        isFetchedTemplateUser.value = false
        document_flow_steps.value = []
        isFetchedDocumentFlowSteps.value = false
        documents.value = []
        isFetchedDocuments.value = false
        document_comments.value = []
    }

    // fetch all
    async function fetchAll(id) {
        await fetchDocumentTypes();
        await fetchDocumentFlowTemplates();
        // await fetchDocumentTemplates();
        // await fetchTemplateUser();
        await fetchDocuments(id);
    }

    return {
        document_types,
        isFetchedDocumentTypes,
        document_flow_templates,
        isFetchedDocumentFlowTemplates,
        document_templates,
        isFetchedDocumentTemplates,
        template_user,
        isFetchedTemplateUser,
        document_flow_steps,
        isFetchedDocumentFlowSteps,
        documents,
        isFetchedDocuments,
        document_comments,

        fetchDocumentTypes,
        fetchDocumentFlowTemplates,
        fetchDocumentTemplates,
        fetchTemplateUser,
        fetchDocumentFlowSteps,
        fetchDocuments,
        fetchDocumentComments,
        // fetchDocumentById,
        rejectDocument,
        fetchAll,
        reset,
    };
});