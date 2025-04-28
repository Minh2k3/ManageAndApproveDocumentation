import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router/index.js'
import axios from 'axios'

window.axios = axios;

// import './style.css'
import App from './App.vue'
import { 
    Select,
    Checkbox,
    Form,
    Input,
    Menu, 
    List, 
    Drawer, 
    Button, 
    message, 
    Card, 
    Table, 
    Avatar,
    Pagination } from 'ant-design-vue'

import './static/fontawesome-free-6.7.2-web/css/all.min.css'
import 'ant-design-vue/dist/reset.css';
import 'bootstrap/dist/css/bootstrap-grid.min.css'
import 'bootstrap/dist/css/bootstrap-utilities.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'


const app = createApp(App)
const pinia = createPinia()
app.use(pinia)
app.use(router)
app.use(Button)
app.use(Checkbox)
app.use(Table)
app.use(Drawer)
app.use(List)
app.use(Menu)
app.use(Card)
app.use(Avatar)
app.use(Select)
app.use(Form)
app.use(Input)
app.use(Pagination)
app.mount('#app')

app.config.globalProperties.$message = message