<template>
  <div class="wishes-page">
    <!-- Header -->
    <header class="page-header">
      <div class="container">
        <h1 class="page-title">
          <i class="fas fa-heart"></i>
          <span>
            Cậu có lời nào chia sẻ với tớ không
          </span>
          <i class="fas fa-heart ms-2"></i>
        </h1>
        <p class="page-subtitle">Viết ra những điều mà bạn chưa kịp nói cho tớ nghe</p>
      </div>
    </header>

    <!-- Search and Add Button -->
    <div class="search-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <a-input-search
              v-model:value="searchId"
              placeholder="Tìm kiếm lời chúc theo ID (VD: 1, 2, 3...)"
              size="large"
              class="search-input"
              @search="searchWishById"
              enter-button="Tìm kiếm"
            >
              <template #prefix>
                <i class="fas fa-search"></i>
              </template>
            </a-input-search>
          </div>
          <div class="col-lg-4 text-end">
            <a-button 
              type="primary" 
              size="large" 
              class="add-wish-btn"
              @click="showAddWishModal"
            >
              <i class="fas fa-plus me-2"></i>
              Thêm lời chúc
            </a-button>
          </div>
        </div>
      </div>
    </div>

    <!-- Canvas Board -->
    <div class="canvas-section">
      <div class="canvas-container">
        <div class="canvas-board" ref="canvasBoard">
          <div class="canvas-grid"></div>
          
          <!-- Loading State -->
          <div v-if="loading" class="canvas-loading">
            <a-spin size="large" />
            <p>Đang tải lời chúc...</p>
          </div>
          
          <!-- Wishes as Sticky Notes -->
          <div 
            v-for="wish in wishes" 
            :key="wish.id" 
            class="sticky-note"
            :class="[
              `note-color-${(wish.id % 6) + 1}`,
              { 'dragging': isDragging && dragNoteId === wish.id }
            ]"
            :style="getNoteStyle(wish)"
            @mousedown="startDragOrClick(wish.id, $event)"
            @click="handleNoteClick(wish, $event)"
          >
            <div class="note-pin"></div>
            <div class="note-header">
              <span class="note-id">#{{ wish.id }}</span>
              <span class="note-date">{{ formatShortDate(wish.createdAt) }}</span>
            </div>
            <div class="note-content">
              <h3 class="note-sender">{{ wish.senderName }}</h3>
              <p class="note-text">
                {{ truncateText(wish.content.text, 80) }}
              </p>
              <div class="note-media" v-if="hasMedia(wish)">
                <span v-if="wish.content.images && wish.content.images.length > 0" class="media-badge">
                  <i class="fas fa-image"></i>
                  {{ wish.content.images.length }}
                </span>
                <span v-if="wish.content.audio" class="media-badge">
                  <i class="fas fa-microphone"></i>
                </span>
              </div>
            </div>
            <div class="note-fold"></div>
          </div>
          
          <!-- Empty State -->
          <div v-if="!loading && wishes.length === 0" class="canvas-empty-state">
            <div class="empty-note">
              <i class="fas fa-sticky-note"></i>
              <h3>Bảng trống</h3>
              <p>Hãy thêm lời chúc đầu tiên lên bảng!</p>
              <button type="primary" @click="showAddWishModal" class="add-first-note-btn text-white text-center align-items-center">
                <span class="d-flex align-items-center p-2">
                  <i class="fas fa-plus mb-0 fs-3 me-2"></i>
                  Thêm note đầu tiên
                </span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Wish Modal -->
    <a-modal
      v-model:open="addWishModalVisible"
      title="Thêm Lời Chúc Mới"
      width="600px"
      :footer="null"
      class="add-wish-modal"
    >
      <a-form
        :model="newWish"
        layout="vertical"
        @finish="submitWish"
        class="wish-form"
      >
        <a-form-item
          label="Tên người gửi"
          name="senderName"
          :rules="[{ required: true, message: 'Vui lòng nhập tên của bạn!' }]"
        >
          <a-input
            v-model:value="newWish.senderName"
            placeholder="Nhập tên của bạn"
            size="large"
          >
            <template #prefix>
              <i class="fas fa-user"></i>
            </template>
          </a-input>
        </a-form-item>

        <!-- Lời chúc (Bắt buộc) -->
        <a-form-item
          label="Lời chúc"
          name="text"
        >
          <a-textarea
            v-model:value="newWish.content.text"
            placeholder="Nhập lời chúc của bạn... (Bắt buộc)"
            :rows="6"
            show-count
            :maxlength="500"
          />
        </a-form-item>

        <!-- Nội dung bổ sung (Tùy chọn) -->
        <a-form-item label="Nội dung bổ sung (Tùy chọn)">
          <a-tabs v-model:activeKey="activeContentTab" class="content-tabs">
            <a-tab-pane key="images" tab="Ảnh">
              <template #tab>
                <i class="fas fa-image"></i>
                Ảnh
              </template>
              <div class="image-upload-section">
                <a-upload
                  v-model:file-list="imageFileList"
                  list-type="picture-card"
                  :before-upload="beforeImageUpload"
                  @remove="removeImage"
                  :multiple="true"
                  accept="image/*"
                >
                  <div v-if="imageFileList.length < 5">
                    <i class="fas fa-plus"></i>
                    <div class="upload-text">Tải ảnh lên</div>
                  </div>
                </a-upload>
                <p class="upload-tip">Tối đa 5 ảnh, mỗi ảnh không quá 2MB</p>
              </div>
            </a-tab-pane>
            
            <a-tab-pane key="audio" tab="Ghi âm">
              <template #tab>
                <i class="fas fa-microphone"></i>
                Ghi âm
              </template>
              <div class="audio-section">
                <div v-if="!isRecording && !audioBlob" class="record-start">
                  <button
                    type="primary"
                    size="large"
                    @click="startRecording"
                    class="record-btn text-white"
                  >
                    <i class="fas fa-microphone"></i>
                    Bắt đầu ghi âm
                  </button>
                  <p>Nhấn để bắt đầu ghi âm lời chúc của bạn</p>
                </div>
                
                <div v-if="isRecording" class="recording-active">
                  <div class="recording-indicator">
                    <i class="fas fa-circle recording-dot"></i>
                    Đang ghi âm... {{ recordingTime }}s
                  </div>
                  <a-button
                    type="danger"
                    size="large"
                    @click="stopRecording"
                    class="stop-btn"
                  >
                    <i class="fas fa-stop"></i>
                    Dừng ghi âm
                  </a-button>
                </div>
                
                <div v-if="audioBlob && !isRecording" class="audio-preview">
                  <audio :src="audioUrl" controls class="audio-player"></audio>
                  <div class="audio-actions">
                    <a-button @click="startRecording" type="default">
                      <i class="fas fa-redo"></i>
                      Ghi lại
                    </a-button>
                    <a-button @click="removeAudio" type="danger">
                      <i class="fas fa-trash"></i>
                      Xóa
                    </a-button>
                  </div>
                </div>
              </div>
            </a-tab-pane>
          </a-tabs>
        </a-form-item>

        <a-form-item class="form-actions">
          <a-space>
            <a-button @click="closeAddWishModal" size="large">
              Hủy
            </a-button>
            <a-button type="primary" html-type="submit" size="large" :loading="submitting">
              <i class="fas fa-paper-plane me-2"></i>
              Gửi lời chúc
            </a-button>
          </a-space>
        </a-form-item>
      </a-form>
    </a-modal>

    <!-- View Wish Detail Modal -->
    <a-modal
      v-model:open="viewWishModalVisible"
      :title="`Lời chúc #${selectedWish?.id || ''}`"
      width="700px"
      :footer="null"
      class="view-wish-modal"
    >
      <div v-if="selectedWish" class="wish-detail">
        <div class="wish-detail-header">
          <h3>{{ selectedWish.senderName }}</h3>
          <div class="wish-meta">
            <span class="wish-id-badge">#{{ selectedWish.id }}</span>
            <span class="wish-detail-date">{{ formatDate(selectedWish.createdAt) }}</span>
          </div>
        </div>
        
        <div class="wish-detail-content">
          <div class="text-content">
            <h4><i class="fas fa-quote-left"></i> Lời chúc</h4>
            <p>{{ selectedWish.content.text }}</p>
          </div>
          
          <div v-if="selectedWish.content.images && selectedWish.content.images.length > 0" class="images-content">
            <h4><i class="fas fa-images"></i> Hình ảnh</h4>
            <div class="images-gallery">
              <img
                v-for="(image, index) in selectedWish.content.images"
                :key="index"
                :src="image"
                :alt="`Ảnh ${index + 1}`"
                @click="previewImage(image)"
                class="gallery-image"
              />
            </div>
          </div>
          
          <div v-if="selectedWish.content.audio" class="audio-content">
            <h4><i class="fas fa-volume-up"></i> Ghi âm</h4>
            <audio :src="selectedWish.content.audio" controls class="audio-player"></audio>
          </div>
        </div>
      </div>
    </a-modal>

    <!-- Success Modal -->
    <a-modal
      v-model:open="successModalVisible"
      title="Gửi lời chúc thành công!"
      width="500px"
      :footer="null"
      class="success-modal"
    >
      <div class="success-content">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h3>Lời chúc đã được gửi!</h3>
        <p>Mã lời chúc của bạn là:</p>
        <div class="wish-code-display">
          <span class="code-text">{{ generatedCode }}</span>
          <a-button type="link" @click="copyCodeToClipboard" class="copy-btn">
            <i class="fas fa-copy"></i>
            Sao chép
          </a-button>
        </div>
        <p class="code-note">Lưu mã này để tìm kiếm lời chúc của bạn sau này!</p>
        <a-button type="primary" @click="successModalVisible = false" class="close-success-btn">
          Đóng
        </a-button>
      </div>
    </a-modal>

    <!-- Image Preview Modal -->
    <a-modal
      v-model:open="imagePreviewVisible"
      :footer="null"
      width="80%"
      class="image-preview-modal"
    >
      <img :src="previewImageUrl" alt="Preview" class="preview-image" />
    </a-modal>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount, computed } from 'vue'
import { message } from 'ant-design-vue'
import { useWishStore } from '@/stores/use-wish'

// 🏪 Initialize Store
const wishStore = useWishStore()

// 📊 Reactive data from store
const wishes = computed(() => wishStore.wishes)
const loading = computed(() => wishStore.loading)
const submitting = computed(() => wishStore.submitting)

// 🎯 Local reactive data (UI state only)
const searchId = ref('')

// Modals
const addWishModalVisible = ref(false)
const viewWishModalVisible = ref(false)
const successModalVisible = ref(false)
const imagePreviewVisible = ref(false)

// Form data - ✅ Fixed: Use computed for currentUser
const newWish = reactive({
  senderName: '', // Will be set in resetForm
  content: {
    text: '',
    images: [],
    audio: null
  }
})

// Form state
const activeContentTab = ref('images')
const imageFileList = ref([])
const generatedCode = ref('')

// Audio recording
const isRecording = ref(false)
const recordingTime = ref(0)
const recordingTimer = ref(null)
const mediaRecorder = ref(null)
const audioBlob = ref(null)
const audioUrl = ref('')

// View details
const selectedWish = ref(null)
const previewImageUrl = ref('')

// Drag functionality
const isDragging = ref(false)
const hasDragged = ref(false)
const dragStartX = ref(0)
const dragStartY = ref(0)
const dragNoteId = ref(null)
const canvasBoard = ref(null)
const dragStartTime = ref(0)
const mouseDownPos = ref({ x: 0, y: 0 })

// Drag thresholds
const DRAG_THRESHOLD = 5
const DRAG_TIME_THRESHOLD = 150

// 🌐 API Methods using store
const submitWish = async () => {
  try {
    // Validate required fields
    if (!newWish.content.text.trim()) {
      message.error('Vui lòng nhập lời chúc!')
      return
    }

    if (!newWish.senderName.trim()) {
      message.error('Vui lòng nhập tên người gửi!')
      return
    }

    console.log('📤 Bắt đầu gửi lời chúc qua store...')

    // Prepare wish data for store
    const wishData = {
      senderName: newWish.senderName.trim(),
      content: {
        text: newWish.content.text.trim(),
        images: [...newWish.content.images], // Copy array
        audioBlob: audioBlob.value // Pass the blob directly
      },
      position: wishStore.generateRandomPosition() // Use store method
    }

    // Send via store
    const result = await wishStore.sendWish(wishData)

    if (result.success) {
      // Show success modal with generated code
      generatedCode.value = result.wish.code
      addWishModalVisible.value = false
      successModalVisible.value = true
      resetForm()

      // ✅ No need to refresh - store already updated the list
      
      // Show success message
      message.success(result.message)
      console.log(`✅ Đã tạo lời chúc với mã: ${result.wish.code}`)
    } else {
      // Handle specific errors
      let errorMessage = result.error
      if (result.error.includes('CSRF') || result.error.includes('XSRF-TOKEN')) {
        errorMessage += ' Vui lòng làm mới trang và thử lại!'
      } else if (result.error.includes('kết nối') || result.error.includes('TIMEOUT')) {
        errorMessage += ' Vui lòng kiểm tra mạng và thử lại.'
      }
      message.error(errorMessage)
      console.error('❌ Lỗi khi tạo lời chúc:', result.error)
    }
    
  } catch (error) {
    // Handle unexpected errors with more detail
    const errorMessage = error.response?.status === 419 
      ? 'Lỗi CSRF token không hợp lệ, vui lòng làm mới trang!'
      : 'Có lỗi không mong muốn xảy ra khi gửi lời chúc!'
    message.error(errorMessage)
    console.error('💥 Lỗi không mong muốn:', error)
  }
}

// 🔍 Search Methods (Local search in store data)
const searchWishById = () => {
  if (!searchId.value.trim()) {
    message.warning('Vui lòng nhập ID để tìm kiếm!')
    return
  }
  
  // Search in store data
  const wish = wishes.value.find(w => w.id.toString() === searchId.value.trim())
  if (wish) {
    viewWishDetail(wish)
    searchId.value = ''
    message.success('Tìm thấy lời chúc!')
  } else {
    message.error('Không tìm thấy lời chúc với ID này!')
  }
}

// 🔄 Refresh data manually (optional)
const refreshWishes = async () => {
  console.log('🔄 Manual refresh requested...')
  const result = await wishStore.fetchWishes(true) // Force refresh
  
  if (result.success) {
    if (result.fromCache) {
      message.info('Dữ liệu đã được tải từ cache')
    } else {
      message.success(`Đã tải ${result.count} lời chúc mới`)
    }
  } else {
    message.error('Không thể tải dữ liệu mới')
  }
}

// 🎨 Helper Methods
const getNoteStyle = (wish) => {
  const baseZIndex = wishes.value.length - wishes.value.findIndex(w => w.id === wish.id)
  
  return {
    position: 'absolute',
    left: `${wish.position.x}%`,
    top: `${wish.position.y}%`,
    transform: `rotate(${wish.position.rotation}deg)`,
    zIndex: isDragging.value && dragNoteId.value === wish.id ? 999 : baseZIndex,
    transition: isDragging.value && dragNoteId.value === wish.id ? 'none' : 'all 0.3s ease'
  }
}

// 🖱️ Drag functionality
const startDragOrClick = (noteId, event) => {
  event.preventDefault()
  event.stopPropagation()
  
  hasDragged.value = false
  isDragging.value = false
  dragNoteId.value = noteId
  dragStartTime.value = Date.now()
  
  mouseDownPos.value = { x: event.clientX, y: event.clientY }
  
  const rect = canvasBoard.value.getBoundingClientRect()
  const wish = wishes.value.find(w => w.id === noteId)
  
  if (wish) {
    dragStartX.value = event.clientX - (wish.position.x / 100 * rect.width)
    dragStartY.value = event.clientY - (wish.position.y / 100 * rect.height)
  }
  
  document.addEventListener('mousemove', onMouseMove)
  document.addEventListener('mouseup', onMouseUp)
  document.body.style.userSelect = 'none'
}

const onMouseMove = (event) => {
  const deltaX = Math.abs(event.clientX - mouseDownPos.value.x)
  const deltaY = Math.abs(event.clientY - mouseDownPos.value.y)
  const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY)
  const timeElapsed = Date.now() - dragStartTime.value
  
  if (!isDragging.value && (distance > DRAG_THRESHOLD || timeElapsed > DRAG_TIME_THRESHOLD)) {
    isDragging.value = true
    hasDragged.value = true
  }
  
  if (isDragging.value && canvasBoard.value) {
    const rect = canvasBoard.value.getBoundingClientRect()
    const x = event.clientX - dragStartX.value
    const y = event.clientY - dragStartY.value
    
    const xPercent = Math.max(0, Math.min(85, (x / rect.width) * 100))
    const yPercent = Math.max(0, Math.min(80, (y / rect.height) * 100))
    
    const wish = wishes.value.find(w => w.id === dragNoteId.value)
    if (wish) {
      wish.position.x = xPercent
      wish.position.y = yPercent
    }
  }
}

const onMouseUp = () => {
  document.removeEventListener('mousemove', onMouseMove)
  document.removeEventListener('mouseup', onMouseUp)
  document.body.style.userSelect = ''
  
  setTimeout(() => {
    isDragging.value = false
    dragNoteId.value = null
  }, 10)
}

const handleNoteClick = (wish, event) => {
  if (hasDragged.value) {
    hasDragged.value = false
    return
  }
  
  viewWishDetail(wish)
}

// 🎛️ Modal controls
const showAddWishModal = () => {
  addWishModalVisible.value = true
}

const closeAddWishModal = () => {
  addWishModalVisible.value = false
  resetForm()
}

const viewWishDetail = (wish) => {
  selectedWish.value = wish
  viewWishModalVisible.value = true
}

// 📝 Form handling - ✅ Fixed: Proper initialization
const resetForm = () => {
  Object.assign(newWish, {
    senderName: wishStore.currentUser || '', // ✅ Fixed: Fallback value
    content: {
      text: '',
      images: [],
      audio: null
    }
  })
  imageFileList.value = []
  activeContentTab.value = 'images'
  removeAudio()
}

// 🖼️ Image handling
const beforeImageUpload = (file) => {
  const isImage = file.type.startsWith('image/')
  if (!isImage) {
    message.error('Chỉ có thể tải lên file ảnh!')
    return false
  }
  
  const isLt2M = file.size / 1024 / 1024 < 2
  if (!isLt2M) {
    message.error('Ảnh phải nhỏ hơn 2MB!')
    return false
  }
  
  const reader = new FileReader()
  reader.onload = (e) => {
    newWish.content.images.push(e.target.result)
  }
  reader.readAsDataURL(file)
  
  return false
}

const removeImage = (file) => {
  const index = imageFileList.value.indexOf(file)
  if (index > -1) {
    newWish.content.images.splice(index, 1)
  }
}

const previewImage = (imageUrl) => {
  previewImageUrl.value = imageUrl
  imagePreviewVisible.value = true
}

// 🎵 Audio recording
const startRecording = async () => {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true })
    mediaRecorder.value = new MediaRecorder(stream)
    const chunks = []
    
    mediaRecorder.value.ondataavailable = (e) => {
      chunks.push(e.data)
    }
    
    mediaRecorder.value.onstop = () => {
      audioBlob.value = new Blob(chunks, { type: 'audio/wav' })
      audioUrl.value = URL.createObjectURL(audioBlob.value)
      stream.getTracks().forEach(track => track.stop())
    }
    
    mediaRecorder.value.start()
    isRecording.value = true
    recordingTime.value = 0
    
    recordingTimer.value = setInterval(() => {
      recordingTime.value++
    }, 1000)
    
  } catch (error) {
    message.error('Không thể truy cập microphone!')
  }
}

const stopRecording = () => {
  if (mediaRecorder.value && isRecording.value) {
    mediaRecorder.value.stop()
    isRecording.value = false
    clearInterval(recordingTimer.value)
  }
}

const removeAudio = () => {
  audioBlob.value = null
  audioUrl.value = ''
  recordingTime.value = 0
  if (recordingTimer.value) {
    clearInterval(recordingTimer.value)
  }
}

// 🔧 Utility functions
const copyCodeToClipboard = () => {
  navigator.clipboard.writeText(generatedCode.value).then(() => {
    message.success('Đã sao chép mã code!')
  }).catch(() => {
    message.error('Không thể sao chép mã code!')
  })
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatShortDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit'
  })
}

const truncateText = (text, length) => {
  return text.length > length ? text.substring(0, length) + '...' : text
}

const hasMedia = (wish) => {
  return (wish.content.images && wish.content.images.length > 0) || wish.content.audio
}

// 📊 Debug methods (for development)
const showStoreStats = () => {
  const stats = wishStore.getStats
  console.log('📊 Store Stats:', stats)
  message.info(`Total: ${stats.total}, Today: ${stats.today}, With Images: ${stats.withImages}`)
}

// 🚀 Lifecycle
// yearbook.vue - script setup
onMounted(async () => {
  console.log('📱 Yearbook page mounted')
  console.log(`👤 Current user: ${wishStore.currentUser}`)
  console.log(`📅 Current time: ${new Date().toLocaleString('vi-VN')}`)
  console.log(`🌐 API base URL: ${import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000'}`)
  
  // Initialize form with current user
  resetForm()
  
  // Initialize store and load wishes
  const result = await wishStore.initialize()
  
  if (result.success) {
    if (result.fromCache) {
      console.log('📋 Loaded wishes from cache')
      message.success('Đã tải dữ liệu từ cache')
    } else {
      console.log(`✅ Loaded ${result.count} wishes from API`)
      message.success(`Đã tải ${result.count} lời chúc`)
    }
  } else {
    console.error('❌ Failed to initialize store')
    message.error('Không thể tải dữ liệu ban đầu')
  }
})

onBeforeUnmount(() => {
  // Cleanup
  if (recordingTimer.value) {
    clearInterval(recordingTimer.value)
  }
  document.removeEventListener('mousemove', onMouseMove)
  document.removeEventListener('mouseup', onMouseUp)
  
  console.log('📱 Yearbook page unmounted')
})
</script>

<style scoped>
/* Loading state */
.canvas-loading {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color: #666;
  z-index: 100;
}

.canvas-loading p {
  margin-top: 16px;
  font-size: 1rem;
  color: #999;
}

/* Enhanced canvas board with notebook-style grid and nature decorations */
.canvas-board {
  position: relative;
  background: 
    /* Notebook paper style */
    linear-gradient(#f8f9fa 0%, #f1f3f4 100%);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  min-height: 150vh;
  overflow: hidden;
  box-shadow: 
    0 20px 40px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  cursor: default;
  border: 3px solid #e8eaed;
}

/* Enhanced grid pattern - notebook style */
.canvas-grid {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    /* Horizontal lines */
    linear-gradient(rgba(79, 172, 254, 0.3) 1px, transparent 1px),
    /* Vertical lines */
    linear-gradient(90deg, rgba(79, 172, 254, 0.3) 1px, transparent 1px),
    /* Heavier grid every 5 lines */
    linear-gradient(rgba(52, 144, 220, 0.5) 1px, transparent 1px),
    linear-gradient(90deg, rgba(52, 144, 220, 0.5) 1px, transparent 1px);
  background-size: 
    25px 25px,
    25px 25px,
    125px 125px,
    125px 125px;
  opacity: 0.6;
}

/* Nature decorations around canvas */
.canvas-container {
  max-width: 100%;
  margin: 0 auto;
  position: relative;
}

.canvas-container::before,
.canvas-container::after {
  content: '';
  position: absolute;
  pointer-events: none;
  z-index: 1;
}

/* Top left corner decoration */
.canvas-container::before {
  top: -10px;
  left: -10px;
  width: 120px;
  height: 120px;
  background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><g fill="%23228B22" opacity="0.7"><path d="M20,60 Q30,40 40,60 Q50,80 60,60 Q70,40 80,60 Q90,80 100,60"/><circle cx="25" cy="50" r="8" fill="%23FF69B4"/><circle cx="45" cy="70" r="6" fill="%23FFB6C1"/><circle cx="75" cy="45" r="7" fill="%23FF1493"/><circle cx="95" cy="65" r="5" fill="%23FFC0CB"/><path d="M15,70 Q25,50 35,70 L35,90 Q25,85 15,90 Z" fill="%2332CD32"/><path d="M65,35 Q75,15 85,35 L85,55 Q75,50 65,55 Z" fill="%2332CD32"/></g></svg>') no-repeat;
  background-size: contain;
  opacity: 0.6;
}

/* Bottom right corner decoration */
.canvas-container::after {
  bottom: -10px;
  right: -10px;
  width: 100px;
  height: 100px;
  background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><g fill="%23228B22" opacity="0.7"><path d="M10,40 Q20,20 30,40 Q40,60 50,40 Q60,20 70,40 Q80,60 90,40"/><circle cx="20" cy="30" r="6" fill="%23FF4500"/><circle cx="40" cy="50" r="5" fill="%23FFD700"/><circle cx="70" cy="25" r="6" fill="%23FF6347"/><circle cx="85" cy="45" r="4" fill="%23FFA500"/><path d="M5,50 Q15,30 25,50 L25,70 Q15,65 5,70 Z" fill="%2300FF7F"/><path d="M75,15 Q85,0 95,15 L95,35 Q85,30 75,35 Z" fill="%2300FF7F"/></g></svg>') no-repeat;
  background-size: contain;
  opacity: 0.6;
  transform: rotate(45deg);
}

/* Additional floating nature elements */
.canvas-section {
  padding: 20px;
  min-height: 70vh;
  position: relative;
  overflow: hidden;
}

.canvas-section::before {
  content: '';
  position: absolute;
  top: 20%;
  left: -50px;
  width: 80px;
  height: 200px;
  background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 200"><g fill="%23228B22" opacity="0.4"><path d="M40,190 Q30,170 40,150 Q50,130 40,110 Q30,90 40,70 Q50,50 40,30 Q30,10 40,0"/><circle cx="25" cy="25" r="8" fill="%23FF69B4"/><circle cx="60" cy="50" r="6" fill="%23FFB6C1"/><circle cx="20" cy="85" r="7" fill="%23FF1493"/><circle cx="65" cy="110" r="5" fill="%23FFC0CB"/><circle cx="30" cy="140" r="6" fill="%23FF69B4"/><circle cx="55" cy="170" r="7" fill="%23FFB6C1"/></g></svg>') no-repeat;
  background-size: contain;
  opacity: 0.5;
  z-index: 0;
}

.canvas-section::after {
  content: '';
  position: absolute;
  top: 40%;
  right: -50px;
  width: 70px;
  height: 150px;
  background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 150"><g fill="%23228B22" opacity="0.4"><path d="M35,140 Q25,120 35,100 Q45,80 35,60 Q25,40 35,20 Q45,5 35,0"/><circle cx="50" cy="20" r="6" fill="%23FF4500"/><circle cx="15" cy="45" r="5" fill="%23FFD700"/><circle cx="55" cy="70" r="6" fill="%23FF6347"/><circle cx="10" cy="95" r="4" fill="%23FFA500"/><circle cx="45" cy="120" r="5" fill="%23FF4500"/></g></svg>') no-repeat;
  background-size: contain;
  opacity: 0.5;
  z-index: 0;
  transform: scaleX(-1);
}

/* Enhanced sticky note appearance on grid */
.sticky-note {
  position: absolute;
  width: 250px;
  min-height: 200px;
  padding: 20px 15px 15px;
  border-radius: 0 0 8px 8px;
  cursor: pointer;
  user-select: none;
  box-shadow: 
    0 5px 15px rgba(0, 0, 0, 0.15),
    0 2px 4px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.5);
  border-top: 3px solid rgba(0, 0, 0, 0.1);
  animation: noteAppear 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  z-index: 10;
  filter: drop-shadow(2px 2px 4px rgba(34, 139, 34, 0.1));
}

/* Enhanced note colors for better contrast on grid */
.note-color-1 { 
  background: linear-gradient(135deg, #FFF176, #FFEB3B);
  border-left: 4px solid #FBC02D;
}
.note-color-2 { 
  background: linear-gradient(135deg, #FFAB91, #FF8A65);
  border-left: 4px solid #FF5722;
}
.note-color-3 { 
  background: linear-gradient(135deg, #A5D6A7, #81C784);
  border-left: 4px solid #4CAF50;
}
.note-color-4 { 
  background: linear-gradient(135deg, #90CAF9, #64B5F6);
  border-left: 4px solid #2196F3;
}
.note-color-5 { 
  background: linear-gradient(135deg, #CE93D8, #BA68C8);
  border-left: 4px solid #9C27B0;
}
.note-color-6 { 
  background: linear-gradient(135deg, #FFCC80, #FFB74D);
  border-left: 4px solid #FF9800;
}

/* Enhanced hover effect for notes on grid */
.sticky-note:hover:not(.dragging) {
  transform: scale(1.05) !important;
  box-shadow: 
    0 15px 35px rgba(0, 0, 0, 0.25),
    0 8px 15px rgba(0, 0, 0, 0.15),
    inset 0 1px 0 rgba(255, 255, 255, 0.7);
  z-index: 1000 !important;
  filter: drop-shadow(4px 4px 8px rgba(34, 139, 34, 0.2));
}

/* Floating petals animation */
@keyframes floatingPetals {
  0% {
    transform: translateY(0px) rotate(0deg);
    opacity: 1;
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
    opacity: 0.7;
  }
  100% {
    transform: translateY(0px) rotate(360deg);
    opacity: 1;
  }
}

.canvas-board::before {
  content: '';
  position: absolute;
  top: 10%;
  left: 80%;
  width: 20px;
  height: 20px;
  background: radial-gradient(circle, #FF69B4, #FFB6C1);
  border-radius: 50% 0;
  opacity: 0.6;
  animation: floatingPetals 6s ease-in-out infinite;
  z-index: 1;
}

.canvas-board::after {
  content: '';
  position: absolute;
  top: 60%;
  left: 10%;
  width: 15px;
  height: 15px;
  background: radial-gradient(circle, #FF4500, #FFD700);
  border-radius: 50% 0;
  opacity: 0.6;
  animation: floatingPetals 8s ease-in-out infinite reverse;
  z-index: 1;
}

/* Vintage paper texture */
.canvas-board {
  background-image:
    radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.03) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.03) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(120, 200, 120, 0.03) 0%, transparent 50%);
}

/* Improved grid visibility when hovering */
.canvas-board:hover .canvas-grid {
  opacity: 0.8;
  transition: opacity 0.3s ease;
}

/* Improved dragging experience */
.sticky-note.dragging {
  cursor: grabbing !important;
  transform-origin: center;
  z-index: 999 !important;
  filter: drop-shadow(6px 6px 12px rgba(34, 139, 34, 0.3));
}

.sticky-note:not(.dragging) {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sticky-note:hover {
  cursor: grab;
}

.sticky-note:active {
  cursor: grabbing;
}

/* Note ID styling */
.note-id {
  background: rgba(0, 0, 0, 0.1);
  color: rgba(0, 0, 0, 0.7);
  padding: 2px 8px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.75rem;
}

/* Success modal styles */
.success-modal .ant-modal-content {
  border-radius: 15px;
  overflow: hidden;
}

.success-content {
  text-align: center;
  padding: 20px;
}

.success-icon {
  font-size: 4rem;
  color: #52c41a;
  margin-bottom: 20px;
}

.success-content h3 {
  color: #333;
  font-size: 1.5rem;
  margin-bottom: 15px;
}

.success-content p {
  color: #666;
  margin-bottom: 15px;
}

.wish-code-display {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin: 20px 0;
  padding: 15px;
  background: linear-gradient(-45deg, #003a69 0%, #4392ba 28%, #28b6de 67%, #00eeff 100%);
  border-radius: 10px;
}

.code-text {
  color: white;
  font-family: 'Courier New', monospace;
  font-size: 1.3rem;
  font-weight: bold;
  letter-spacing: 1px;
}

.copy-btn {
  color: white !important;
  padding: 5px 10px;
  border-radius: 5px;
}

.copy-btn:hover {
  background: rgba(255, 255, 255, 0.1) !important;
}

.code-note {
  font-size: 0.9rem;
  color: #999;
  font-style: italic;
}

.close-success-btn {
  background: linear-gradient(45deg, #52c41a, #389e0d);
  border: none;
  border-radius: 25px;
  padding: 8px 25px;
  font-weight: 600;
  margin-top: 20px;
}

/* Wish detail header improvements */
.wish-detail-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 25px;
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}

.wish-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 8px;
}

.wish-id-badge {
  background: linear-gradient(45deg, #667eea, #764ba2);
  color: white;
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: bold;
}

.wish-detail-date {
  color: #999;
  font-size: 0.9rem;
}

/* Form improvements */
.wish-form .ant-form-item-label > label {
  font-weight: 600;
  color: #333;
}

.wish-form .ant-form-item-label > label::after {
  content: '';
}

.wish-form .ant-form-item-required .ant-form-item-label > label::before {
  content: '* ';
  color: #ff4d4f;
  font-family: inherit;
}

/* Note animation improvements */
@keyframes noteAppear {
  0% {
    opacity: 0;
    transform: scale(0.3) rotate(0deg) translateY(-50px);
  }
  50% {
    opacity: 0.8;
    transform: scale(1.1) rotate(var(--rotation, 0deg)) translateY(-10px);
  }
  100% {
    opacity: 1;
    transform: scale(1) rotate(var(--rotation, 0deg)) translateY(0);
  }
}

/* Prevent text selection while dragging */
.canvas-board * {
  user-select: none;
}

.sticky-note.dragging * {
  pointer-events: none;
}

/* Smooth transitions for better UX */
.sticky-note {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
              box-shadow 0.3s ease,
              filter 0.3s ease,
              z-index 0s;
}

/* Main page styles */
.wishes-page {
  min-height: 100vh;
  background: linear-gradient(-45deg, #316c9d 0%, #4392ba 28%, #28b6de 67%, #00eeff 100%);
  background-size: 400% 400%;
  animation: gradientShift 5s ease infinite;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Header */
.page-header {
  padding: 60px 0 40px;
  text-align: center;
  color: white;
}

.page-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1rem;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.page-title i {
  color: #ff6b9d;
  margin-right: 1rem;
}

.page-subtitle {
  font-size: 1.2rem;
  opacity: 0.9;
  margin: 0;
}

/* Search Section */
.search-section {
  padding: 20px 0;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

.search-input {
  border-radius: 25px;
}

.search-input .ant-input {
  border-radius: 25px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  background: rgba(255, 255, 255, 0.9);
}

.add-wish-btn {
  background: linear-gradient(45deg, #ff6b9d, #c44569);
  border: none;
  border-radius: 25px;
  font-weight: 600;
  box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
  transition: all 0.3s ease;
}

.add-wish-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
}

/* Note Pin */
.note-pin {
  position: absolute;
  top: -8px;
  left: 50%;
  transform: translateX(-50%);
  width: 16px;
  height: 16px;
  background: radial-gradient(circle, #ff4757, #c44569);
  border-radius: 50%;
  box-shadow: 
    0 2px 4px rgba(0, 0, 0, 0.3),
    inset 0 1px 2px rgba(255, 255, 255, 0.3);
  border: 2px solid rgba(255, 255, 255, 0.8);
}

/* Note Fold Effect */
.note-fold {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 20px;
  height: 20px;
  background: linear-gradient(-45deg, transparent 46%, rgba(0, 0, 0, 0.1) 50%, rgba(0, 0, 0, 0.05) 54%, transparent);
  border-radius: 0 0 8px 0;
}

/* Note Content */
.note-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  font-size: 0.85rem;
}

.note-date {
  color: rgba(0, 0, 0, 0.6);
  font-size: 0.75rem;
}

.note-sender {
  font-size: 1.1rem;
  font-weight: 600;
  color: rgba(0, 0, 0, 0.8);
  margin-bottom: 10px;
  line-height: 1.3;
}

.note-text {
  color: rgba(0, 0, 0, 0.7);
  font-size: 0.9rem;
  line-height: 1.4;
  margin-bottom: 12px;
  word-wrap: break-word;
}

.note-media {
  display: flex;
  gap: 8px;
  margin-top: 10px;
}

.media-badge {
  background: rgba(0, 0, 0, 0.1);
  color: rgba(0, 0, 0, 0.7);
  padding: 3px 8px;
  border-radius: 8px;
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  gap: 4px;
}

/* Canvas Empty State */
.canvas-empty-state {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color: #666;
  z-index: 100;
}

.empty-note {
  background: rgba(255, 255, 255, 0.95);
  padding: 40px;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  border: 2px solid #e8eaed;
}

.empty-note i {
  font-size: 4rem;
  color: #ddd;
  margin-bottom: 20px;
}

.empty-note h3 {
  color: #333;
  margin-bottom: 10px;
  font-size: 1.5rem;
}

.empty-note p {
  margin-bottom: 20px;
  color: #666;
}

.add-first-note-btn {
  background: linear-gradient(45deg, #667eea, #764ba2);
  border: none;
  border-radius: 25px;
  font-weight: 600;
}

/* Modal Styles */
.add-wish-modal .ant-modal-content,
.view-wish-modal .ant-modal-content {
  border-radius: 15px;
  overflow: hidden;
}

.content-tabs .ant-tabs-tab {
  font-weight: 500;
}

.content-tabs .ant-tabs-tab i {
  margin-right: 8px;
}

/* Image Upload */
.image-upload-section {
  text-align: center;
}

.upload-tip {
  color: #999;
  font-size: 0.85rem;
  margin-top: 10px;
}

/* Audio Recording */
.audio-section {
  text-align: center;
  padding: 20px;
}

.record-start p {
  color: #666;
  margin-top: 10px;
}

.record-btn {
  background: linear-gradient(45deg, #ff6b9d, #c44569);
  border: none;
  border-radius: 50px;
  padding: 15px 30px;
  font-size: 1.1rem;
}

.recording-active {
  padding: 20px;
}

.recording-indicator {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin-bottom: 20px;
  font-size: 1.1rem;
  color: #ff4757;
}

.recording-dot {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}

.stop-btn {
  border-radius: 50px;
  padding: 12px 25px;
}

.audio-preview {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
}

.audio-player {
  width: 100%;
  max-width: 400px;
}

.audio-actions {
  display: flex;
  gap: 10px;
}

/* Wish Detail Modal */
.wish-detail-content > div {
  margin-bottom: 25px;
}

.wish-detail-content h4 {
  color: #667eea;
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.text-content p {
  color: #333;
  line-height: 1.7;
  font-size: 1rem;
}

.images-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
}

.gallery-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 10px;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.gallery-image:hover {
  transform: scale(1.05);
}

/* Image Preview Modal */
.image-preview-modal .ant-modal-content {
  background: transparent;
  box-shadow: none;
}

.preview-image {
  width: 100%;
  height: auto;
  border-radius: 10px;
}

/* Form Actions */
.form-actions {
  margin-top: 30px;
  text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
  .page-title {
    font-size: 2.2rem;
  }
  
  .page-subtitle {
    font-size: 1rem;
  }
  
  .search-section .row {
    flex-direction: column;
    gap: 15px;
  }
  
  .search-section .col-lg-4 {
    text-align: center !important;
  }
  
  .canvas-board {
    min-height: 60vh;
    margin: 0 10px;
  }
  
  .sticky-note {
    width: 200px;
    min-height: 180px;
    padding: 15px 12px 12px;
  }
  
  .note-sender {
    font-size: 1rem;
  }
  
  .note-text {
    font-size: 0.85rem;
  }
  
  .add-wish-modal .ant-modal,
  .view-wish-modal .ant-modal {
    margin: 10px;
    max-width: calc(100vw - 20px);
  }
  
  .images-gallery {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .gallery-image {
    height: 120px;
  }
  
  .wish-detail-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .wish-meta {
    align-items: flex-start;
    flex-direction: row;
    gap: 15px;
  }
  
  .success-content {
    padding: 15px;
  }
  
  .wish-code-display {
    flex-direction: column;
    gap: 15px;
    padding: 20px 15px;
  }
  
  .code-text {
    font-size: 1.1rem;
  }

  /* Hide nature decorations on mobile */
  .canvas-container::before,
  .canvas-container::after,
  .canvas-section::before,
  .canvas-section::after {
    display: none;
  }
}

@media (max-width: 576px) {
  .page-header {
    padding: 40px 0 30px;
  }
  
  .page-title {
    font-size: 1.8rem;
  }
  
  .sticky-note {
    width: 180px;
    min-height: 160px;
    padding: 12px 10px 10px;
  }
  
  .note-sender {
    font-size: 0.95rem;
    margin-bottom: 8px;
  }
  
  .note-text {
    font-size: 0.8rem;
    line-height: 1.3;
  }
  
  .note-header {
    margin-bottom: 8px;
  }
  
  .images-gallery {
    grid-template-columns: 1fr;
  }
  
  .audio-actions {
    flex-direction: column;
    gap: 10px;
  }
  
  .record-btn {
    padding: 12px 20px;
    font-size: 1rem;
  }
  
  .canvas-board {
    border-radius: 15px;
  }

  /* Smaller grid on mobile */
  .canvas-grid {
    background-size: 
      20px 20px,
      20px 20px,
      100px 100px,
      100px 100px;
  }
}
</style>