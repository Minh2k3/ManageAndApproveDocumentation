import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useAuth = defineStore('auth', () => {
  // Thông tin người dùng đăng nhập
  const user = ref(null) // chứa object { id, name, role, ... }

  // Login giả lập
  function login(mockUser) {
    user.value = mockUser
  }

  // Logout
  function logout() {
    user.value = null
  }

  // Trả về vai trò hiện tại
  const role = computed(() => user.value?.role || 'creator')

  return {
    user,
    role,
    login,
    logout
  }
})
