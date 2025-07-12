import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router/index.js'
import axios from 'axios'
import '@/lib/echo';
import { useThemeStore } from '@/stores/use-theme.js';  
import { useAuth } from '@/stores/use-auth.js';

window.axios = axios;

// import './style.css'
import App from './App.vue'
import {
    Affix,
    Avatar,
    Badge,
    Button,
    Card,
    Carousel,
    Checkbox,
    Col,
    Comment,
    DatePicker,
    Descriptions,
    Divider,
    Drawer,
    Empty,
    FloatButton,
    Form,
    Input,
    List,
    Menu,
    Modal,
    Pagination,
    Popconfirm,
    Radio,
    RadioButton,
    RadioGroup,
    Result,
    Row,
    Select,
    Space,
    Statistic,
    Steps,
    Switch,
    Table,
    TabPane,
    Tabs,
    Tag,
    TimePicker,
    Tooltip,
    Upload,
    message,
} from 'ant-design-vue'

import './static/fontawesome-free-6.7.2-web/css/all.min.css'
import 'ant-design-vue/dist/reset.css';
import 'bootstrap/dist/css/bootstrap-grid.min.css'
import 'bootstrap/dist/css/bootstrap-utilities.min.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'
// import 'ant-design-vue/dist/antd.css';

const app = createApp(App)
const pinia = createPinia()

app.config.globalProperties.$message = message
app.use(pinia)
app.use(router)

// Ant Design Vue Components - Alphabetical Order
app.use(Affix)
app.use(Avatar)
app.use(Badge)
app.use(Button)
app.use(Card)
app.use(Carousel)
app.use(Checkbox)
app.use(Col)
app.use(Comment)
app.use(DatePicker)
app.use(Descriptions)
app.use(Divider)
app.use(Drawer)
app.use(Empty)
app.use(FloatButton)
app.use(Form)
app.use(Input)
app.use(List)
app.use(Menu)
app.use(Modal)
app.use(Pagination)
app.use(Popconfirm)
app.use(Radio)
app.use(RadioButton)
app.use(RadioGroup)
app.use(Result)
app.use(Row)
app.use(Select)
app.use(Space)
app.use(Statistic)
app.use(Steps)
app.use(Switch)
app.use(Table)
app.use(TabPane)
app.use(Tabs)
app.use(Tag)
app.use(TimePicker)
app.use(Tooltip)
app.use(Upload)

const themeStore = useThemeStore();
themeStore.applyTheme();
themeStore.listenToSystemChange();

const authStore = useAuth();
authStore.setupAuthListener();

authStore.initAuth().then(() => {
    console.log('App initialized with auth state:', authStore.isLoggedIn);
    
    // Mount app sau khi auth đã được khởi tạo
    app.mount('#app')
}).catch((error) => {
    console.error('Auth initialization failed:', error);
    
    // Vẫn mount app ngay cả khi auth init failed
    app.mount('#app')
})