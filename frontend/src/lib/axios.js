// src/lib/axios.js
import axios from 'axios'

// Create axios instance
const axiosInstance = axios.create({
  baseURL: 'http://127.0.0.1:8000',
  withCredentials: true, // Keep for sessions
  withXSRFToken: true,
  timeout: 30000,
  headers: {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
})

// ✅ Simplified request interceptor (no CSRF handling needed)
axiosInstance.interceptors.request.use(
  (config) => {
    console.log(`📤 ${config.method?.toUpperCase()} ${config.url}`)
    return config
  },
  (error) => {
    console.error('❌ Request error:', error)
    return Promise.reject(error)
  }
)

// ✅ Simplified response interceptor
axiosInstance.interceptors.response.use(
  (response) => {
    console.log(`✅ ${response.status} ${response.config.url}`)
    return response
  },
  (error) => {
    console.error(`❌ ${error.response?.status || 'Network'} error:`, error.message)
    return Promise.reject(error)
  }
)

export default axiosInstance