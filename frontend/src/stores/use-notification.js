import { defineStore } from 'pinia';
import { ref } from "vue";
import axiosInstance from '@/lib/axios';
import { useAuth } from '@/stores/use-auth';

export const useNotificationStore = defineStore("notification", () => { 
    const authStore = useAuth();
    const notifications = ref([]);
    const isFetched = ref(false);
    
    async function fetchNotification(force = false) {
        if (isFetched.value && !force) {
            return;
        }

        try {
            await axiosInstance
                .get("api/user/{}")
                .then((response) => {
                    console.log("Response:", response.data);
                    notifications.value = response.data.notifications;
                    isFetched.value = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        } catch (error) {
            console.error("Lỗi khi lấy dữ liệu:", error);
        }
    };


    function reset() {
        departments.value = [];
        rolls.value = [];
        isFetched.value = false;
    }

    return {
        departments,
        rolls,
        isFetched,
        
        fetchRegisterForm,
        reset,
    };
});