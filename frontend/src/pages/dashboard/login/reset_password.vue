<template>
  <div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="row w-100">
      <div class="col-md-8 col-lg-6 mx-auto">
        <div class="card shadow-lg border-0" style="border-radius: 15px;">
          <div class="card-body p-5">
            <!-- Header -->
            <div class="text-center mb-4">
              <h2 class="fw-bold text-primary mb-2">Đặt lại mật khẩu</h2>
              <p class="text-muted">Nhập mật khẩu mới cho tài khoản của bạn</p>
            </div>

            <!-- Loading Token Verification -->
            <div v-if="verifyingToken" class="text-center py-5">
              <a-spin size="large" />
              <p class="mt-3 text-muted">Đang xác thực token...</p>
            </div>

            <!-- Invalid Token -->
            <div v-else-if="tokenInvalid" class="text-center py-4">
              <a-result
                status="error"
                title="Token không hợp lệ"
                sub-title="Token đã hết hạn hoặc không tồn tại. Vui lòng yêu cầu link mới."
              >
                <template #extra>
                  <a-button type="primary" @click="handleRequestNewForgotPassword">
                    Yêu cầu link mới
                  </a-button>
                </template>
              </a-result>
            </div>

            <!-- Reset Form -->
            <div v-else>
              <!-- Alerts -->
              <a-alert
                v-if="successMessage"
                :message="successMessage"
                type="success"
                show-icon
                closable
                class="mb-3"
                @close="successMessage = ''"
              />

              <a-alert
                v-if="errorMessage"
                :message="errorMessage"
                type="error"
                show-icon
                closable
                class="mb-3"
                @close="errorMessage = ''"
              />

              <a-form
                :model="form"
                :rules="rules"
                @finish="handleSubmit"
                layout="vertical"
              >
                <!-- Email (readonly) -->
                <a-form-item label="Email" class="mb-3">
                  <a-input
                    :value="email"
                    size="large"
                    readonly
                    disabled
                  >
                    <template #prefix>
                      <MailOutlined class="text-muted" />
                    </template>
                  </a-input>
                </a-form-item>

                <!-- New Password -->
                <a-form-item
                  label="Mật khẩu mới"
                  name="password"
                  class="mb-3"
                >
                  <a-input-password
                    v-model:value="form.password"
                    size="large"
                    placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)"
                    :disabled="loading"
                  >
                    <template #prefix>
                      <LockOutlined class="text-muted" />
                    </template>
                  </a-input-password>
                </a-form-item>

                <!-- Confirm Password -->
                <a-form-item
                  label="Xác nhận mật khẩu"
                  name="password_confirmation"
                  class="mb-4"
                >
                  <a-input-password
                    v-model:value="form.password_confirmation"
                    size="large"
                    placeholder="Nhập lại mật khẩu mới"
                    :disabled="loading"
                  >
                    <template #prefix>
                      <LockOutlined class="text-muted" />
                    </template>
                  </a-input-password>
                </a-form-item>

                <!-- Submit Button -->
                <a-form-item class="mb-3">
                  <a-button
                    type="primary"
                    html-type="submit"
                    size="large"
                    block
                    :loading="loading"
                    class="fw-bold"
                  >
                    {{ loading ? 'Đang cập nhật...' : 'Cập nhật mật khẩu' }}
                  </a-button>
                </a-form-item>
              </a-form>

              <!-- Back to Login -->
              <div class="text-center">
                <a-button
                  type="link"
                  @click="goToLogin"
                  :disabled="loading"
                >
                  <span>
                    <i class="bi bi-arrow-left me-1"></i>Quay lại đăng nhập
                  </span> 
                </a-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, ref, onMounted } from 'vue'
import { MailOutlined, LockOutlined, ArrowLeftOutlined } from '@ant-design/icons-vue'
import { message } from 'ant-design-vue'
import axiosInstance from '@/lib/axios'
import { useRouter } from "vue-router";

export default {
  name: 'ResetPassword',
  components: {
    MailOutlined,
    LockOutlined,
    ArrowLeftOutlined
  },
  setup() {
    const router = useRouter()
    const loading = ref(false)
    const verifyingToken = ref(true)
    const tokenInvalid = ref(false)
    const successMessage = ref('')
    const errorMessage = ref('')
    const email = ref('')
    const token = ref('')

    const form = reactive({
      password: '',
      password_confirmation: ''
    })

    const rules = {
      password: [
        { required: true, message: 'Vui lòng nhập mật khẩu mới!' },
        { min: 8, message: 'Mật khẩu phải có ít nhất 8 ký tự!' }
      ],
      password_confirmation: [
        { required: true, message: 'Vui lòng xác nhận mật khẩu!' },
        {
          validator: (_, value) => {
            if (!value || form.password === value) {
              return Promise.resolve()
            }
            return Promise.reject(new Error('Mật khẩu xác nhận không khớp!'))
          }
        }
      ]
    }

    // Lấy token và email từ URL
    const getUrlParams = () => {
      const urlParams = new URLSearchParams(window.location.search)
      email.value = urlParams.get('email') || ''
      token.value = urlParams.get('token') || ''
    }

    // Xác thực token
    const verifyToken = async () => {
      if (!token.value || !email.value) {
        tokenInvalid.value = true
        verifyingToken.value = false
        return
      }

      await axiosInstance.get("/sanctum/csrf-cookie");
      try {
        const response = await axiosInstance.post('/verify-reset-token', {
          token: token.value,
          email: email.value
        })
        if (response.data.valid) {
          tokenInvalid.value = false
            verifyingToken.value = false
        } else {
          tokenInvalid.value = true
            verifyingToken.value = false
        }
      } catch (error) {
        console.error('Token verification failed:', error)
        tokenInvalid.value = true
        verifyingToken.value = false
      }
      console.log('token invalid: ' + tokenInvalid.value)
      console.log('token verifying: ' + verifyingToken.value)
    }

    // Xử lý submit form
    const handleSubmit = async (values) => {
      loading.value = true
      errorMessage.value = ''
      successMessage.value = ''

      try {
        const response = await axiosInstance.post('/api/reset-password', {
          token: token.value,
          email: email.value,
          password: values.password,
          password_confirmation: values.password_confirmation
        })

        successMessage.value = response.data.message || 'Mật khẩu đã được cập nhật thành công!'
        message.success('Đặt lại mật khẩu thành công!')
        
        // Reset form
        form.password = ''
        form.password_confirmation = ''
        
        // Chuyển về trang login sau 2 giây
        setTimeout(() => {
          goToLogin()
        }, 2000)

      } catch (error) {
        console.error('Reset password error:', error)
        errorMessage.value = error.response?.data?.message || 'Có lỗi xảy ra, vui lòng thử lại!'
        message.error('Cập nhật mật khẩu thất bại!')
      } finally {
        loading.value = false
      }
    }

    const goToLogin = () => {
      // Thay đổi theo route của bạn
    //   window.location.href = '/login'
      router.push('/login')
    }

    const handleRequestNewForgotPassword = async () => {
        try {
                // Gửi yêu cầu quên mật khẩu
                const response = await axiosInstance.post("/api/forgot-password", { email: email.value });
                message.success(response.data.message || "Yêu cầu quên mật khẩu đã được gửi thành công. Vui lòng kiểm tra email của bạn.");
                modalForgotPassword.value = false;
            } catch (error) {
                message.error(error.response?.data?.message || "Có lỗi xảy ra khi gửi yêu cầu quên mật khẩu. Vui lòng thử lại sau.");
            }
    }

    onMounted(() => {
      getUrlParams()
      verifyToken()
    })

    return {
      form,
      rules,
      loading,
      verifyingToken,
      tokenInvalid,
      successMessage,
      errorMessage,
      email,
      handleSubmit,
      goToLogin,
        handleRequestNewForgotPassword
    }
  }
}
</script>

<style scoped>
.text-primary {
  color: #1890ff !important;
}

.card {
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .card-body {
    padding: 2rem !important;
  }
}
</style>