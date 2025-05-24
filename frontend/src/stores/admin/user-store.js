import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useUserStore = defineStore("user", () => {
    // State
    const users = ref([]);
    const isFetched = ref(false);
    const daily_access = ref({});
    const isFetchedDailyAccess = ref(false);

    async function fetchUsers(force = false) {
        if (isFetched.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get("api/users");
            if (response.data) {
                users.value = response.data.active_users;
                isFetched.value = true;
            }
        }
        catch (error) {
            console.error("Error fetching users:", error);
        }
    }

    async function fetchDailyAccess(force = false) {
        if (isFetchedDailyAccess.value && !force) {
            return;
        }

        try {
            const response = await axiosInstance.get("api/access-statistics");
            if (response.data) {
                daily_access.value = response.data.daily_access;
                isFetchedDailyAccess.value = true;
            }
        }
        catch (error) {
            console.error("Error fetching daily access:", error);
        }
    }

    // fetch all
    async function fetchAll(force = false) {
        await fetchUsers(force);
        await fetchDailyAccess(force);
    }

    function reset() {
        users.value = [];
        isFetched.value = false;
    }

    return {
        users,
        isFetched,
        daily_access,
        isFetchedDailyAccess,

        fetchUsers,
        fetchDailyAccess,
        fetchAll,
        reset,
    };
});