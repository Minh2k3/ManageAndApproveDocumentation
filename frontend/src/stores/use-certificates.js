import { defineStore } from 'pinia';
import { ref, nextTick } from "vue";
import axiosInstance from '@/lib/axios';

export const useCertificateStore = defineStore("certificate", () => { 
    const certificates = ref(null);

    async function findCertificateByCode(code) {
        try {
            const response = await axiosInstance.get(`api/document-certificates/${code}`);
            
            if (response.data && response.data.certificate) {
                console.log("Certificate found:", response.data.certificate);
                
                // Force reactive update
                certificates.value = null; // Clear first
                await nextTick(); // Wait for reactivity
                certificates.value = response.data.certificate; // Then set
                
                console.log("Current certificate set:", certificates.value);
                return response.data.certificate;
            } else {
                certificates.value = null;
                return null;
            }
        } catch (error) {
            console.error("Error fetching certificate:", error);
            certificates.value = null;
            throw error;
        }
    }

    return {
        certificates,
        findCertificateByCode
    };
});