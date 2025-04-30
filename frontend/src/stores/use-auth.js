import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'

export const useAuth = defineStore('auth', () => {
    // Thông tin người dùng đăng nhập
    const user = ref(null) // chứa object { id, name, role, ... }
    const role = ref('admin') // chứa string 'admin' hoặc 'user'

    function login(mockUser) {
        user.value = mockUser
        role.value = mockUser?.role || 'admin'  // Đồng bộ
    }

    function logout() {
        user.value = null
        role.value = 'admin'
    }

    // Nếu muốn tự động đồng bộ user.role → role
    watch(user, (newVal) => {
        role.value = newVal?.role || 'admin'
    })

    return {
        user,
        role,
        login,
        logout
    }
})
