import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin.js";
import dashboard from "./dashboard.js";
import login from "./login.js";
import register from "./register.js";
import creator from "./creator.js";
import approver from "./approver.js";
import { useAuth } from "@/stores/use-auth.js";

const redirectRoot = [{
    path: '/',
    redirect: {name: 'dashboard'}
}];

const routes = [...redirectRoot, ...admin, ...dashboard, ...login, ...register, ...creator, ...approver];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});


router.beforeEach(async (to, from, next) => {
    const authStore = useAuth();
    if (!authStore.isAuthenticated) {
        await authStore.initAuth();
    }

    if (!authStore.isAuthenticated) {
        if (to.path !== '/login' && to.path !== '/register' && to.path !== '/dashboard' && to.path !== '/reset-password') {
            return next('/login');
        }
    }

    if ((to.path === '/login' || to.path === '/register' || to.path === '/dashboard') && authStore.isAuthenticated) {
        if (authStore.user.role_id === 3) {
            return next('/approver');
        } else if (authStore.user.role_id === 2) {
            return next('/creator');
        } else {
            return next('/admin');
        }
    }

    const user = authStore.user;
    if (to.path.startsWith('/admin') && user.role_id !== 1) {
        return next('/login');
    }

    if (to.path.startsWith('/creator') && user.role_id !== 2) {
        return next('/login');
    }

    if (to.path.startsWith('/approver') && user.role_id !== 3) {
        return next('/login');
    }

    next();
});

export default router;