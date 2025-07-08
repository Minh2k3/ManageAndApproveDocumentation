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
    const current_document_flow_steps = ref([]);
    const current_document = ref([]);
    const current_document_versions = ref([]);
    const current_document_data = ref([]);

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
            const response = await axiosInstance.get("api/user/document-templates");
            if (response.data) {
                console.log("response.data", response.data);
                document_templates.value = response.data.document_templates;
                isFetchedDocumentTemplates.value = true;
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
                isFetchedTemplateUser.value = true;
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

    async function fetchDocumentById(id, from_me) {
        try {
            let response;
            if (from_me) {
                response = await axiosInstance.get(`api/document/${id}/fm`);
            } else {
                response = await axiosInstance.get(`api/document/${id}/nm`);
            }
            if (response.data) {
                current_document.value = response.data.document;
                console.log("Current document: ", current_document.value);
            }
        } catch (error) {
            console.error("Error fetching document by ID:", error);
        }
    }

    async function fetchStepsByDocumentFlowId(documentFlowId) {
        try {
            const response = await axiosInstance.get(`api/document-flows/${documentFlowId}/steps`);
            if (response.data) {
                console.log("Document_flow_step: " + JSON.stringify(response.data, null, 2));
                current_document_flow_steps.value = response.data;
            }
        } catch (error) {
            console.error("Error fetching document flow steps:", error);
        }
    }

    async function createCertificate(documentId) {
        try {
            const response = await axiosInstance.get(`create-certificate/${documentId}`);
            if (response.data) {
                console.log("Create certificate response: ", response.data);
                return response.data;
            }
        } catch (error) {
            console.error("Error creating certificate:", error);
        }
    }
    
    async function approveDocument(id) {
        try {
            const response = await axiosInstance.post(`api/document-steps/${id}/approve`);
            if (response.data) {
                console.log("Approve document response: ", response.data);
                return response.data;
            }
        } catch (error) {
            console.error("Error approving document:", error);
        }   
    }

    async function rejectDocument(id, data) {
        try {
            const response = await axiosInstance.post(`api/document-steps/${id}/reject`, data);
            if (response.data) {
                console.log("Reject document response: ", response.data);
                return response.data;
            }
        } catch (error) {
            console.error("Error rejecting document:", error);
        }
    }

    async function getDocumentVersions(documentId) {
        try {
            const response = await axiosInstance.get(`api/documents/${documentId}/versions`);
            if (response.data) {
                console.log("Document version: " + JSON.stringify(response.data.versions, null, 2));
                current_document_versions.value = [...response.data.versions];
                console.log("Current document versions: ", current_document_versions.value);
            }
        } catch (error) {
            console.error("Error fetching document version:", error);
        }
    }

    function setCurrentDocumentData(data) {
        console.log('data bên nhận:', data);
        console.log('typeof data:', typeof data);
        
        current_document_data.value = JSON.parse(JSON.stringify(data));
        
        console.log('sau gán:', current_document_data.value);
    }

    function getCurrentDocumentData() {
        const data = current_document_data.value;
        current_document_data.value = [];
        return data;
    }    

    async function signDocument(version_id, step_id, document_id) {
        try {
            const response = await axiosInstance.post(`api/documents/sign-document`, { 
                version_id: version_id,
                step_id: step_id,
                document_id: document_id
            });
            if (response.data) {
                console.log("Sign document response: ", response.data);
                return response.data;
            }
        } catch (error) {
            console.error("Error signing document:", error);
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
        document_flow_templates,
        document_templates,
        template_user,
        document_flow_steps,
        documents,
        isFetchedDocuments,
        document_comments,
        current_document_flow_steps,
        current_document,
        current_document_versions,
        current_document_data,

        fetchDocumentTypes,
        fetchDocumentFlowTemplates,
        fetchDocumentTemplates,
        fetchTemplateUser,
        fetchDocumentFlowSteps,
        fetchDocuments,
        fetchDocumentComments,
        fetchDocumentById,
        fetchStepsByDocumentFlowId,
        approveDocument,
        rejectDocument,
        getDocumentVersions,
        setCurrentDocumentData, 
        getCurrentDocumentData,
        signDocument,
        createCertificate,
        fetchAll,
        reset,
    };
});