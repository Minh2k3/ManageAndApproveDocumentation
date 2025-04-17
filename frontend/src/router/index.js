import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin.js";
import dashboard from "./dashboard.js";
import login from "./login.js";
import register from "./register.js";

const redirectRoot = [{
    path: '/',
    redirect: {name: 'dashboard'}
}];

const routes = [...redirectRoot, ...admin, ...dashboard, ...login, ...register];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

export default router;