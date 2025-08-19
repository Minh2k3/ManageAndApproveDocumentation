import axios from "axios";

const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Lấy CSRF token từ cookie nếu có
axiosInstance.interceptors.request.use(config => {
    const csrfToken = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    if (csrfToken) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(csrfToken[1]);
    }
    return config;
}, error => Promise.reject(error));

export default axiosInstance;