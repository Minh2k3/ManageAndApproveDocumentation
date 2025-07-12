const register = [
    {
        path: '/register',
        name: 'register',
        component: () => import('@/pages/dashboard/register/index.vue'),
    },
    {
        path: '/term',
        name: 'term',
        component: () => import('@/pages/dashboard/register/term.vue'),
    }
];

export default register;