import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'

export const useAuth = defineStore('auth', () => {
    // Thông tin người dùng đăng nhập
    const user = ref(null) // chứa object { id, name, role, ... }
    const role = ref('guest') // chứa string 'admin' hoặc 'user'
    // if (user.value === 'admin') {
    //     role.value = 'admin'
    // } else if (user.value.role === 'approver') {
    //     role.value = 'approver'
    // } else {
    //     role.value = 'creator'
    // }

    function login(mockUser) {
        user.value = mockUser
        role.value = mockUser?.role || 'guest'  // Đồng bộ
    }

    function logout() {
        user.value = null
        role.value = 'admin'
    }

    // Nếu muốn tự động đồng bộ user.role → role
    watch(user, (newVal) => {
        role.value = newVal?.role || 'guest'
    })

    const isAdmin = computed(() => role.value === 'admin')
    const isApprover = computed(() => role.value === 'approver')
    const isCreator = computed(() => role.value === 'creator')
    const isGuest = computed(() => role.value === 'guest')

    return {
        user,
        role,
        login,
        logout,
        isAdmin,
        isApprover,
        isCreator,
        isGuest
    }
})
