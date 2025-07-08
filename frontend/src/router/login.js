const login = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/pages/dashboard/login/index.vue'),
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: () => import('@/pages/dashboard/login/reset_password.vue'),
    }
];

export default login;