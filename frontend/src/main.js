import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router/index.js'
import axios from 'axios'
import '@/lib/echo';
import { useThemeStore } from '@/stores/use-theme.js';

window.axios = axios;

// import './style.css'
import App from './App.vue'
import { 
    Button, 
    Checkbox,
    Radio,
    RadioGroup,
    RadioButton,
    Form,
    Input,
    DatePicker,
    TimePicker,
    Tooltip,
    Table, 
    Select,
    Menu, 
    List, 
    Drawer, 
    message, 
    Upload,
    Card, 
    Avatar,
    Pagination,
    Modal,
    Descriptions,
    TabPane,
    Tabs,
    Comment,
    FloatButton,
    Steps,
    Empty,
    Carousel,
    Divider,
} from 'ant-design-vue'

import './static/fontawesome-free-6.7.2-web/css/all.min.css'
import 'ant-design-vue/dist/reset.css';
import 'bootstrap/dist/css/bootstrap-grid.min.css'
import 'bootstrap/dist/css/bootstrap-utilities.min.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

const app = createApp(App)
const pinia = createPinia()

app.config.globalProperties.$message = message
app.use(pinia)
app.use(router)
app.use(Button)
app.use(Checkbox)
app.use(Radio)
app.use(RadioGroup)
app.use(RadioButton)
app.use(Table)
app.use(Drawer)
app.use(List)
app.use(Tooltip)
app.use(Menu)
app.use(Card)
app.use(Avatar)
app.use(Select)
app.use(Form)
app.use(Upload)
app.use(Input)
app.use(Pagination)
app.use(DatePicker)
app.use(TimePicker)
app.use(Modal)
app.use(message)
app.use(Descriptions)
app.use(TabPane)
app.use(Tabs)
app.use(Comment)
app.use(FloatButton)
app.use(Steps)
app.use(Empty)
app.use(Carousel)
app.use(Divider)

const themeStore = useThemeStore();
themeStore.applyTheme();
themeStore.listenToSystemChange();

app.mount('#app')
