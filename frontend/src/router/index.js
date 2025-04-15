import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin.js";
import login from "./login.js";
import register from "./register.js";

const redirectRoot = [{
    path: '/',
    redirect: {name: 'login'}
}];

const routes = [...redirectRoot, ...admin, ...login, ...register]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

export default router;