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
        token: localStorage.getItem('access_token') || null,
        rememberToken: localStorage.getItem('remember_token') || null,
        role: null,
        roll: null,
        department_id: null,
        isAuthenticated: false,
        isInitialized: false, // ✅ THÊM FLAG ĐỂ TRACK INITIALIZATION
    }),

    getters: {
        isLoggedIn: (state) => !!state.token && !!state.user && state.isAuthenticated,
    },

    actions: {
        async login(credentials) {
            try {
                const response = await axiosInstance.post('/api/login', credentials);
                const { user, access_token, remember_token } = response.data.data;

                this.token = access_token;
                this.user = user;
                this.isAuthenticated = true;
                this.isInitialized = true; // ✅ SET INITIALIZED

                localStorage.setItem('access_token', access_token);
                
                // ✅ SET AXIOS TOKEN NGAY LẬP TỨC
                axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;

                if (remember_token) {
                    this.rememberToken = remember_token
                    localStorage.setItem('remember_token', remember_token)
                    this.setCookie('remember_token', remember_token, 30)
                }

                // Set role
                this.setUserRole(user);

                // ✅ BROADCAST CHANGE TO OTHER TABS
                this.broadcastAuthChange('login', { user, token: access_token });
                
                return { status: 'success' };
            } catch (error) {
                console.error('Login error:', error);
                return { status: 'error', message: error.response?.data?.message || 'Login failed' };
            }
        },

        async loginWithRememberToken() {
            const rememberToken = this.rememberToken || this.getCookie('remember_token')
            
            if (!rememberToken) {
                return { success: false, message: 'No remember token found' }
            }

            try {
                const response = await axiosInstance.post('/api/login-remember', {
                    remember_token: rememberToken // ✅ SỬA LỖI TYPO: "emember_token" → "remember_token"
                })
                
                if (response.data.success) {
                    const { user, access_token } = response.data.data
                    
                    this.token = access_token
                    this.user = user
                    this.isAuthenticated = true
                    this.isInitialized = true // ✅ SET INITIALIZED

                    // Set role
                    this.setUserRole(user);

                    localStorage.setItem('access_token', access_token)
                    axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${access_token}`
                    
                    // ✅ BROADCAST CHANGE TO OTHER TABS
                    this.broadcastAuthChange('remember_login', { user, token: access_token });
                    
                    return { success: true, data: response.data }
                }
                
                this.clearRememberToken()
                return { success: false, message: response.data.message }
            } catch (error) {
                console.error('Remember login error:', error)
                this.clearRememberToken()
                return { success: false, message: 'Remember token không hợp lệ' }
            }
        },        

        async logout() {
            try {
                // Reset other stores based on role
                if (this.role === 'admin') {
                    const adminDocumentStore = useAdminDocumentStore();
                    adminDocumentStore.reset();
                    const adminUserStore = useAdminUserStore();
                    adminUserStore.reset();
                } else if (this.role === 'creator') {
                    const creatorDocumentStore = useCreatorDocumentStore();
                    creatorDocumentStore.reset();
                }

                if (this.token) {
                    await axiosInstance.post('/api/logout')
                }

                // ✅ BROADCAST LOGOUT TO OTHER TABS BEFORE CLEARING
                this.broadcastAuthChange('logout');
                
                this.clearAuth();
                
                // Clean up XSRF token
                document.cookie = "XSRF-TOKEN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                
                // Redirect to login
                window.location.href = '/login';
            } catch (error) {
                console.error('Logout error:', error);
            }
        },

        clearAuth() {
            this.user = null
            this.user_details = null
            this.token = null
            this.rememberToken = null
            this.role = null
            this.roll = null
            this.department_id = null
            this.isAuthenticated = false
            // ✅ KHÔNG RESET isInitialized ở đây để tránh loop
            
            localStorage.removeItem('access_token')
            localStorage.removeItem('remember_token')
            this.deleteCookie('remember_token')
            
            delete axiosInstance.defaults.headers.common['Authorization']
        }, 
        
        clearRememberToken() {
            this.rememberToken = null
            localStorage.removeItem('remember_token')
            this.deleteCookie('remember_token')
        },        

        async initAuth() {
            // ✅ PREVENT MULTIPLE INITIALIZATION
            if (this.isInitialized) {
                return this.isLoggedIn;
            }

            console.log('🔄 Initializing auth...');

            try {
                const token = localStorage.getItem('access_token')
                const rememberToken = localStorage.getItem('remember_token') || this.getCookie('remember_token')
                
                if (token) {
                    this.token = token
                    axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${token}` // ✅ SỬA: dùng axiosInstance thay vì axios
                    
                    try {
                        // Verify token
                        const response = await axiosInstance.get('/api/user')
                        if (response.data && response.data.success) { 
                            this.user = response.data.data;
                            this.isAuthenticated = true;
                            this.isInitialized = true;
                            
                            // Set role and fetch user details
                            await this.setUserRoleAndDetails(this.user);
                            
                            console.log('✅ Auth initialized with valid token');
                            return true;
                        } else {
                            throw new Error('Invalid user response')
                        }
                    } catch (error) {
                        console.error('Token verification failed:', error);
                        // Token không hợp lệ, thử remember token
                        if (rememberToken) {
                            const result = await this.loginWithRememberToken()
                            this.isInitialized = true;
                            return result.success
                        }
                        throw error;
                    }
                } else if (rememberToken) {
                    // Không có access token nhưng có remember token
                    console.log('🔑 Trying remember token...');
                    const result = await this.loginWithRememberToken()
                    this.isInitialized = true;
                    return result.success
                } else {
                    // Không có token nào
                    console.log('❌ No tokens found');
                    this.clearAuth()
                    this.isInitialized = true;
                    return false
                }
            } catch (error) {
                console.error('❌ Auth initialization failed:', error);
                this.clearAuth()
                this.isInitialized = true;
                return false
            }
        },

        // ✅ HELPER METHOD ĐỂ SET ROLE
        setUserRole(user) {
            if (user.role_id === 1) {
                this.role = 'admin';
                this.roll = 'Admin';
            } else if (user.role_id === 2) {
                this.role = 'creator';
                this.roll = 'Creator';
                this.department_id = user.department_id;
            } else if (user.role_id === 3) {
                this.role = 'approver';
            }
        },

        // ✅ HELPER METHOD ĐỂ SET ROLE VÀ FETCH DETAILS
        async setUserRoleAndDetails(user) {
            this.setUserRole(user);
            
            try {
                if (user.role_id === 2) {
                    const creator_response = await axiosInstance.get('/api/users/' + user.id + '/creator');
                    this.user_details = creator_response.data.creator;
                } else if (user.role_id === 3) {
                    const approver_response = await axiosInstance.get('/api/users/' + user.id + '/approver');
                    this.user_details = approver_response.data.approver;
                }
            } catch (error) {
                console.error('Failed to fetch user details:', error);
            }
        },

        // ✅ BROADCAST METHODS ĐỂ ĐỒNG BỘ GIỮA TABS
        broadcastAuthChange(type, data = {}) {
            const message = {
                type: 'AUTH_CHANGE',
                action: type,
                timestamp: Date.now(),
                data
            }
            
            // Sử dụng localStorage event
            localStorage.setItem('auth_broadcast', JSON.stringify(message))
            localStorage.removeItem('auth_broadcast') // Trigger event
            
            // Sử dụng BroadcastChannel nếu browser hỗ trợ
            if (window.BroadcastChannel) {
                const channel = new BroadcastChannel('auth_channel')
                channel.postMessage(message)
            }
        },

        setupAuthListener() {
            // Listen for localStorage changes
            window.addEventListener('storage', (e) => {
                if (e.key === 'auth_broadcast' && e.newValue) {
                    const message = JSON.parse(e.newValue)
                    this.handleAuthBroadcast(message)
                }
            })

            // Listen for BroadcastChannel
            if (window.BroadcastChannel) {
                const channel = new BroadcastChannel('auth_channel')
                channel.onmessage = (event) => {
                    this.handleAuthBroadcast(event.data)
                }
            }

            console.log('👂 Auth listener setup completed');
        },

        handleAuthBroadcast(message) {
            console.log('📡 Received auth broadcast:', message);
            
            switch (message.action) {
                case 'login':
                case 'remember_login':
                    if (!this.isAuthenticated) {
                        this.token = message.data.token
                        this.user = message.data.user
                        this.isAuthenticated = true
                        this.isInitialized = true
                        this.setUserRole(message.data.user)
                        axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${message.data.token}`
                        localStorage.setItem('access_token', message.data.token)
                        console.log('✅ Synced login from other tab');
                    }
                    break
                    
                case 'logout':
                    if (this.isAuthenticated) {
                        this.clearAuth()
                        console.log('✅ Synced logout from other tab');
                    }
                    break
            }
        },

        // Cookie helpers
        setCookie(name, value, days) {
            const expires = new Date()
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000))
            document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/;SameSite=Lax`
        },

        getCookie(name) {
            const nameEQ = name + "="
            const ca = document.cookie.split(';')
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i]
                while (c.charAt(0) === ' ') c = c.substring(1, c.length)
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length)
            }
            return null
        },

        deleteCookie(name) {
            document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`
        },        

        // ✅ SỬA FETCHUSER METHOD
        async fetchUser() {
            try {
                const response = await axiosInstance.get('/api/user');
                this.user = response.data;
                await this.setUserRoleAndDetails(this.user);
                this.isAuthenticated = true;
            } catch (error) {
                console.error('Fetch user failed:', error);
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

            try {
                await axiosInstance.post(`api/users/${this.user.id}/change-password`, {
                    current_password: current_password,
                    new_password: new_password,
                    new_password_confirmation: new_password_confirmation,
                });
                return { status: 'success', message: 'Đổi mật khẩu thành công' };
            } catch (error) {
                console.error('Change password error:', error);
                return { status: 'error', message: error.response?.data?.message || 'Đổi mật khẩu thất bại' };
            }
        },
    },
});
