const retrieve = [
    {
        path: '/retrieve',
        name: 'retrieve',
        component: () => import('@/pages/dashboard/retrieve/index.vue'),
    },
    {
        path: '/hoi-dong',
        name: 'hoidong',
        component: () => import('@/pages/dashboard/retrieve/hoidong.vue'),
    },
    {
        path: '/testweb',
        name: 'test',
        component: () => import('@/pages/dashboard/retrieve/test.vue'),
    },
];

export default retrieve;