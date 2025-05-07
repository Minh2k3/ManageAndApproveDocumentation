import axios from "axios";

const axiosInstance = axios.create({
    baseURL: "http://localhost:8000",
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

axiosInstance.interceptors.request.use(config => {
    const token = document.cookie.replace(/(?:(?:^|.*;\s*)XSRF-TOKEN\s*\=\s*([^;]*).*$)|^.*$/, '$1')
    if (token) {
      config.headers['X-XSRF-TOKEN'] = token
    }
    return config
  })

export default axiosInstance;