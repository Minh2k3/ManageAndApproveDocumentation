import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useCertificateStore = defineStore("certificate", () => {
    // State
    const certificates = ref([]);
    const isFetchedCertificates = ref(false);

    // Actions
    async function fetchCertificates(force = false) {
        if (isFetchedCertificates.value && !force) {
            return;
        }
        try {
            const response = await axiosInstance.get("/api/certificates");
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

    async function issueCertificate(user_id) {
        try {
            const response = await axiosInstance.post("/api/certificates/issue-certificate", { user_id });
            console.log("response issue certificate: ", response.data);
            if (response.data) {
                return response.data;
            }
        } catch (error) {
            console.error("Error issuing certificate:", error);
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

        issueCertificate,

        resetCertificates,
    };
});