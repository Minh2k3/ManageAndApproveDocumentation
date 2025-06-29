import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useCertificateStore = defineStore("certificate", () => {
    // State
    const certificates = ref([]);
    const isFetchedCertificates = ref(false);
    const currentCertificate = ref(null);

    // Actions
    async function fetchCertificates(force = false, user_id = null) {
        if (isFetchedCertificates.value && !force) {
            return;
        }
        try {
            const response = await axiosInstance.get(`/api/users/${user_id}/certificates`);
            if (response.data) {
                // console.log("response certificate: ", response.data);
                // Đảm bảo dữ liệu trả về có đúng định dạng { label, value }
                certificates.value = response.data.certificates;
                isFetchedCertificates.value = true; // Đánh dấu đã fetch thành công
            }
        } catch (error) {
            console.error("Error fetching certificate:", error);
        }
    }

    async function requestIssueCertificate(user_id) {
        try {
            const response = await axiosInstance.post("/api/certificates/request-issue-certificate", { user_id });
            console.log("response request issue certificate: ", response.data);
            if (response.data) {
                return response.data;
            }
        } catch (error) {
            console.error("Error requesting issue certificate:", error);
        }
    }

    async function requestExtendCertificate(certificate_id) {
        try {
            const response = await axiosInstance.post("/api/certificates/request-extend-certificate", { certificate_id });
            console.log("response request extend certificate: ", response.data);
            if (response.data) {
                return response.data;
            }
        } catch (error) {
            console.error("Error requesting extend certificate:", error);
        }
    }

    async function findCertificateByCode(code) {
        try {
            const response = await axiosInstance.get(`api/document-certificate/${code}`);
            if (response.data) {
                console.log("Certificate found:", response.data);
                currentCertificate.value = response.data;
                return response.data;
            } else {
                console.warn("No certificate found with code:", code);
                return null;
            }
        } catch (error) {
            console.error("Error fetching certificate by code:", error);
            throw error;
        }
    }    
    
    // Reset state if needed
    function resetCertificates() {
        certificates.value = [];
        isFetchedCertificates.value = false;
    }

    return {
        certificates,
        fetchCertificates,

        currentCertificate,
        findCertificateByCode,

        requestIssueCertificate,
        requestExtendCertificate,

        resetCertificates,
    };
});