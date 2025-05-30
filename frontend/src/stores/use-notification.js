import { defineStore } from 'pinia';
import { ref } from "vue";
import axiosInstance from '@/lib/axios';
import { useAuth } from '@/stores/use-auth';

export const useNotificationStore = defineStore("notification", () => { 
    const notifications = ref([]);
    const isFetched = ref(false);
    
    async function fetchNotifications(user_id, force = false) {
        if (isFetched.value && !force) {
            return;
        }

        try {
            await axiosInstance
                .get(`api/notifications/${user_id}`)
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

    // Read all notifications
    async function readAllNotifications(user_id) {
        try {
            await axiosInstance
                .post(`api/notifications/read-all/${user_id}`)
                .then((response) => {
                    console.log("Response:", response.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        } catch (error) {
            console.error("Lỗi khi đánh dấu tất cả thông báo đã đọc:", error);
        }
    }

    // Read notification has id
    async function readNotificationById(notification_id) {
        try {
            await axiosInstance
                .post(`api/notifications/read/${notification_id}`)
                .then((response) => {
                    console.log("Response:", response.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        } catch (error) {
            console.error("Lỗi khi đánh dấu thông báo đã đọc:", error);
        }
    }


    function reset() {
        notifications.value = [];
        isFetched.value = false;
    }

    return {
        notifications,
        isFetched,

        fetchNotifications,
        readAllNotifications,
        readNotificationById,
        reset
    };
});