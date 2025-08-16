import { defineStore } from 'pinia';
import { ref } from "vue";
import axiosInstance from '@/lib/axios';

export const useWishStore = defineStore('wish', () => {
  // 📊 State
  const wishes = ref([])
  const loading = ref(false)
  const submitting = ref(false)
  const lastFetchTime = ref(null)
  const currentUser = ref('Minh2k3')

  // ⚙️ Configuration
  const CACHE_DURATION = 5 * 60 * 1000 // 5 minutes cache
  const API_BASE = '/api/wishes'

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
    x: Math.random() * 80 + 5,      // 5% to 85%
    y: Math.random() * 75 + 5,      // 5% to 80%
    rotation: (Math.random() - 0.5) * 40  // -20° to +20°
  })

  // 📥 Fetch All Wishes
  const fetchWishes = async (forceRefresh = false) => {
    // Skip if data is fresh and not forcing refresh
    if (!forceRefresh && isDataFresh.value && wishes.value.length > 0) {
      console.log('📋 Using cached wishes data')
      return { success: true, fromCache: true }
    }

    loading.value = true
    
    try {
      console.log('🔄 Fetching wishes from API...')
      const response = await axios.get(API_BASE, {
        params: {
          per_page: 100, // Get more wishes
          _t: Date.now() // Cache busting
        }
      })

      if (response.data.success) {
        const transformedWishes = response.data.data.map(transformWishFromAPI)
        
        // Sort by creation date (newest first)
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
        errorMessage = 'Lỗi server, vui lòng thử lại sau!'
      } else if (error.code === 'NETWORK_ERROR') {
        errorMessage = 'Lỗi kết nối mạng!'
      }
      
      message.error(errorMessage)
      
      return { success: false, error: error.message }
      
    } finally {
      loading.value = false
    }
  }

  // 📤 Send New Wish
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
      
      // Add position (generate if not provided)
      const position = wishData.position || generateRandomPosition()
      formData.append('position_x', position.x)
      formData.append('position_y', position.y)
      formData.append('rotation', position.rotation)

      // Add base64 images
      if (wishData.content.images?.length > 0) {
        wishData.content.images.forEach((imageBase64, index) => {
          formData.append(`base64_images[${index}]`, imageBase64)
        })
      }

      // Add audio blob
      if (wishData.content.audioBlob) {
        try {
          // Convert blob to base64
          const audioBase64 = await blobToBase64(wishData.content.audioBlob)
          formData.append('audio_blob', audioBase64)
        } catch (audioError) {
          console.warn('⚠️ Failed to process audio:', audioError)
          // Continue without audio if processing fails
        }
      }

      // Send to API
      const response = await axios.post(API_BASE, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
        timeout: 30000 // 30 seconds timeout
      })

      if (response.data.success) {
        // Transform and add to store
        const newWish = transformWishFromAPI(response.data.data)
        
        // Add to beginning of array (newest first)
        wishes.value.unshift(newWish)
        
        console.log(`✅ Wish created with code: ${newWish.code}`)
        
        return {
          success: true,
          wish: newWish,
          message: response.data.message || 'Lời chúc đã được gửi thành công!'
        }
        
      } else {
        throw new Error(response.data.message || 'Không thể gửi lời chúc!')
      }
      
    } catch (error) {
      console.error('❌ Error sending wish:', error)
      
      let errorMessage = 'Có lỗi xảy ra khi gửi lời chúc!'
      
      if (error.response?.data?.errors) {
        // Laravel validation errors
        const errors = error.response.data.errors
        const firstError = Object.values(errors)[0]
        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError
      } else if (error.response?.data?.message) {
        errorMessage = error.response.data.message
      } else if (error.message) {
        errorMessage = error.message
      } else if (error.code === 'NETWORK_ERROR') {
        errorMessage = 'Lỗi kết nối mạng!'
      } else if (error.code === 'TIMEOUT') {
        errorMessage = 'Gửi lời chúc quá lâu, vui lòng thử lại!'
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

  // 🗑️ Clear Cache (useful for testing)
  const clearCache = () => {
    wishes.value = []
    lastFetchTime.value = null
    console.log('🗑️ Wishes cache cleared')
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
    // State
    wishes,
    loading,
    submitting,
    currentUser,
    lastFetchTime,
    
    // Computed
    wishesCount,
    isDataFresh,
    getStats,
    
    // Main Actions
    fetchWishes,
    sendWish,
    
    // Utility Actions
    clearCache,
    initialize,
    generateRandomPosition
  }
})