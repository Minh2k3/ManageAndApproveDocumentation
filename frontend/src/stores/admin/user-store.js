import { defineStore } from "pinia";
import { ref } from "vue";
import axiosInstance from "@/lib/axios.js";

export const useUserStore = defineStore("user", () => {
    // State
    const users = ref([]);
    const isFetched = ref(false);

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

    // fetch all
    async function fetchAll(force = false) {
        await fetchUsers(force);
    }

    function reset() {
        users.value = [];
        isFetched.value = false;
    }

    return {
        users,
        isFetched,
        fetchUsers,
        fetchAll,
        reset,
    };
});