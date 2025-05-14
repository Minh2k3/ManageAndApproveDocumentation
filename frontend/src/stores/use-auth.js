// src/stores/useAuth.js

import { defineStore } from 'pinia';
import axiosInstance from '@/lib/axios';

export const useAuth = defineStore('auth', {
    state: () => ({
        user: null,
        role: null,
        isAuthenticated: false,
    }),

    actions: {
        async login(credentials) {
            try {
                const response = await axiosInstance.post('/api/login', credentials);
                this.user = response.data.user;
                if (this.user.role_id === 1) {
                    this.role = 'admin';
                } else if (this.user.role_id === 2) {
                    this.role = 'creator';
                } else if (this.user.role_id === 3) {
                    this.role = 'approver';
                }
                this.isAuthenticated = true;
                return { status: 'success' };
            } catch (error) {
                console.error(error);
                return { status: 'error', message: error.response?.data?.message || 'Login failed' };
            }
        },

        async logout() {
            // await axiosInstance.get("/sanctum/csrf-cookie");
            try {
                await axiosInstance.post('/api/logout');
                this.$reset();
                document.cookie = "XSRF-TOKEN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                window.location.href = '/login';
            } catch (error) {
                console.error(error);
            }
        },

        async fetchUser() {
            try {
                const response = await axiosInstance.get('/api/user');
                this.user = response.data;
                if (this.user.role_id === 1) {
                    this.role = 'admin';
                } else if (this.user.role_id === 2) {
                    this.role = 'creator';
                } else if (this.user.role_id === 3) {
                    this.role = 'approver';
                }
                this.isAuthenticated = true;
            } catch (error) {
                this.user = null;
                this.role = null;
                this.isAuthenticated = false;
            }
        }
    },
});
