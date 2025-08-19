// stores/use-wish.js
import { defineStore } from 'pinia';
import { ref, computed } from "vue";
import axiosInstance from '@/lib/axios';
import { message } from 'ant-design-vue'

export const useWishStore = defineStore('wish', () => {
  // 📊 State
  const wishes = ref([])
  const loading = ref(false)
  const submitting = ref(false)
  const lastFetchTime = ref(null)
  const currentUser = ref('') // ✅ Your login

  // ⚙️ Configuration
  const CACHE_DURATION = 5 * 60 * 1000 // 5 minutes

  // 🧮 Computed
  const wishesCount = computed(() => wishes.value.length)
  
  const isDataFresh = computed(() => {
    if (!lastFetchTime.value) return false
    return Date.now() - lastFetchTime.value < CACHE_DURATION
  })

  // 🔄 Helper Functions
  const transformWishFromAPI = (apiWish) => ({
    id: apiWish.id,
    code: apiWish.code,
    senderName: apiWish.senderName,
    content: {
      text: apiWish.content.text,
      images: apiWish.content.images || [],
      audio: apiWish.content.audio || null
    },
    createdAt: new Date(apiWish.createdAt),
    position: {
      x: apiWish.position.x || 50,
      y: apiWish.position.y || 50,
      rotation: apiWish.position.rotation || 0
    }
  })

  const generateRandomPosition = () => ({
    x: Math.random() * 80 + 5,
    y: Math.random() * 75 + 5,
    rotation: (Math.random() - 0.5) * 40
  })

  // 📥 Fetch All Wishes - Simplified
  const fetchWishes = async (forceRefresh = false) => {
    if (!forceRefresh && isDataFresh.value && wishes.value.length > 0) {
      console.log('📋 Using cached data')
      return { success: true, fromCache: true }
    }

    loading.value = true

    try {
      console.log('🔄 Fetching wishes from API...')
      const response = await axiosInstance.get('/api/wishes', {
        params: {
          per_page: 100,
          _t: Date.now()
        }
      })

      if (response.data.success) {
        const transformedWishes = response.data.data.map(transformWishFromAPI)
        wishes.value = transformedWishes.sort((a, b) => 
          new Date(b.createdAt) - new Date(a.createdAt)
        )
        lastFetchTime.value = Date.now()
        console.log(`✅ Loaded ${wishes.value.length} wishes`)
        return { 
          success: true, 
          count: wishes.value.length,
          fromCache: false
        }
      } else {
        throw new Error(response.data.message || 'Failed to fetch wishes')
      }
      
    } catch (error) {
      console.error('❌ Error fetching wishes:', error)
      let errorMessage = 'Không thể tải danh sách lời chúc!'
      
      if (error.response?.status === 404) {
        errorMessage = 'API endpoint không tìm thấy!'
      } else if (error.response?.status >= 500) {
        errorMessage = 'Lỗi server, vui lòng thử lại!'
      } else if (error.code === 'ERR_NETWORK') {
        errorMessage = 'Lỗi kết nối mạng!'
      }
      
      message.error(errorMessage)
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  // 📤 Send New Wish - Super simplified (no CSRF handling)
  const sendWish = async (wishData) => {
    submitting.value = true
    
    try {
      // Validate input
      if (!wishData.content?.text?.trim()) {
        throw new Error('Vui lòng nhập nội dung lời chúc!')
      }

      if (!wishData.senderName?.trim()) {
        throw new Error('Vui lòng nhập tên người gửi!')
      }

      console.log('📤 Sending wish to API...')

      // Prepare FormData
      const formData = new FormData()
      formData.append('sender_name', wishData.senderName.trim())
      formData.append('content', wishData.content.text.trim())
      
      const position = wishData.position || generateRandomPosition()
      formData.append('position_x', position.x)
      formData.append('position_y', position.y)
      formData.append('rotation', position.rotation)

      // Add images
      if (wishData.content.images?.length > 0) {
        wishData.content.images.forEach((imageBase64, index) => {
          formData.append(`base64_images[${index}]`, imageBase64)
        })
      }

      // Add audio
      if (wishData.content.audioBlob) {
        try {
          const audioBase64 = await blobToBase64(wishData.content.audioBlob)
          formData.append('audio_blob', audioBase64)
        } catch (audioError) {
          console.warn('⚠️ Audio processing failed:', audioError)
        }
      }

      try {
        await axiosInstance.get('/sanctum/csrf-cookie');
        // Optional: Small delay to ensure cookie is set (rarely needed)
        await new Promise(resolve => setTimeout(resolve, 100));
      } catch (csrfError) {
        console.error('Failed to fetch CSRF cookie:', csrfError);
        throw new Error('Không thể lấy token bảo mật. Vui lòng thử lại.');
      }

      // ✅ Simple POST request (no CSRF token needed)
      const response = await axiosInstance.post('/api/wishes', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })

      console.log('✅ API Response:', response.data)

      if (response.data.success) {
        const newWish = transformWishFromAPI(response.data.data)
        wishes.value.unshift(newWish) // Add to top of list
        console.log(`✅ Created wish with code: ${newWish.code}`)
        
        return {
          success: true,
          wish: newWish,
          message: response.data.message || 'Lời chúc đã được gửi thành công!'
        }
      } else {
        throw new Error(response.data.message || 'Cannot send wish!')
      }
      
    } catch (error) {
      console.error('❌ Error sending wish:', error)
      
      let errorMessage = 'Có lỗi xảy ra khi gửi lời chúc!'
      
      if (error.response?.data?.errors) {
        const errors = error.response.data.errors
        const firstError = Object.values(errors)[0]
        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError
      } else if (error.response?.data?.message) {
        errorMessage = error.response.data.message
      } else if (error.message) {
        errorMessage = error.message
      } else if (error.code === 'ERR_NETWORK') {
        errorMessage = 'Lỗi kết nối mạng!'
      }
      
      return {
        success: false,
        error: errorMessage
      }
      
    } finally {
      submitting.value = false
    }
  }

  // 🔧 Utility Functions
  const blobToBase64 = (blob) => {
    return new Promise((resolve, reject) => {
      const reader = new FileReader()
      reader.onload = () => resolve(reader.result)
      reader.onerror = reject
      reader.readAsDataURL(blob)
    })
  }

  // 🗑️ Clear Cache
  const clearCache = () => {
    wishes.value = []
    lastFetchTime.value = null
    console.log('🗑️ Cache cleared')
  }

  // 📊 Get Basic Stats
  const getStats = computed(() => {
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    
    return {
      total: wishes.value.length,
      today: wishes.value.filter(w => new Date(w.createdAt) >= today).length,
      withImages: wishes.value.filter(w => w.content.images?.length > 0).length,
      withAudio: wishes.value.filter(w => w.content.audio).length,
      lastFetch: lastFetchTime.value ? new Date(lastFetchTime.value).toLocaleTimeString('vi-VN') : null
    }
  })

  // 🎯 Initialize Store
  const initialize = async () => {
    console.log('🚀 Initializing wish store...')
    console.log(`👤 Current user: ${currentUser.value}`)
    console.log(`📅 Current time: ${new Date().toLocaleString('vi-VN')}`)
    return await fetchWishes()
  }

  return {
    wishes,
    loading,
    submitting,
    currentUser,
    lastFetchTime,
    wishesCount,
    isDataFresh,
    getStats,
    fetchWishes,
    sendWish,
    clearCache,
    initialize,
    generateRandomPosition
  }
})