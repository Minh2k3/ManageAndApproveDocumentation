// src/stores/useAuth.js

import { defineStore } from 'pinia';
import axiosInstance from '@/lib/axios';

// Stores of Admin
import { useUserStore as useAdminUserStore } from './admin/user-store';
import {useDocumentStore as useAdminDocumentStore} from './admin/document-store';

// Stores of Creator
import {useDocumentStore as useCreatorDocumentStore} from './creator/document-store';

export const useAuth = defineStore('auth', {
    state: () => ({
        user: null,
        user_details: null,
        role: null,
        roll: null,
        isAuthenticated: false,
    }),

    actions: {
        async login(credentials) {
            try {
                const response = await axiosInstance.post('/api/login', credentials);
                this.user = response.data.user;
                if (this.user.role_id === 1) {
                    this.role = 'admin';
                    this.roll = 'Admin';
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
                if (this.role === 'admin') {
                    const adminDocumentStore = useAdminDocumentStore();
                    adminDocumentStore.reset();

                    const adminUserStore = useAdminUserStore();
                    adminUserStore.reset();
                } else if (this.role === 'creator') {
                    const creatorDocumentStore = useCreatorDocumentStore();
                    creatorDocumentStore.reset();
                } else if (this.role === 'approver') {
                    // Stores of Approver
                }

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
                    this.roll = 'Admin';
                } else if (this.user.role_id === 2) {
                    this.role = 'creator';
                    const creator_response = await axiosInstance.get('/api/users/' + this.user.id + '/creator');
                    this.user_details = creator_response.data.creator;
                } else if (this.user.role_id === 3) {
                    this.role = 'approver';
                    const approver_response = await axiosInstance.get('/api/users/' + this.user.id + '/approver');
                    this.user_details = approver_response.data.approver;
                }
                this.isAuthenticated = true;
            } catch (error) {
                this.user = null;
                this.user_details = null;
                this.role = null;
                this.roll = null;
                this.isAuthenticated = false;
            }
        },

        async changePassword(current_password, new_password, new_password_confirmation) {
            if (this.user === null) {
                return { status: 'error', message: 'Chưa đăng nhập' };
            }
            
            // console.log('Changing password for user:', this.user);

            // console.log(typeof current_password, typeof new_password, typeof new_password_confirmation);

            try {
                await axiosInstance
                .post(`api/users/${this.user.id}/change-password`, {
                    current_password: current_password,
                    new_password: new_password,
                    new_password_confirmation: new_password_confirmation,
                });
            } catch (error) {
                console.error(error);
                return { status: 'error', message: error.response?.data?.message || 'Đổi mật khẩu thất bại ở frontend' };
            }
        },
    },
});
