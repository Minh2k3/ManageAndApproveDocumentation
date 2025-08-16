const graduation = [
    {
        path: '/graduation',
        name: 'graduation',
        component: () => import('@/pages/dashboard/graduation/index.vue'),
    },
    {
        path: '/graduation-yearbook',
        name: 'graduation-yearbook',
        component: () => import('@/pages/dashboard/graduation/yearbook.vue'),
    }
];

export default graduation;