import { defineStore } from 'pinia';
import { ref } from "vue";
import axiosInstance from '@/lib/axios';

export const useCertificateStore = defineStore("certificate", () => { 
    const certificates = ref(null);

    async function findCertificateByCode(code) {
        try {
            const response = await axiosInstance.get(`api/document-certificates/${code}`);
            if (response.data) {
                console.log("Certificate found:", response.data);
                certificates.value = response.data;
                console.log("Current certificate set:", certificates.value);
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

    return {
        findCertificateByCode
    };
});