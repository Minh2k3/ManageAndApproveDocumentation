const admin = [
    {
        path: '/admin',
        name: 'admin',
        redirect: '/admin/dashboard',
        component: () => import('../layouts/admin.vue'),
        children: [
            // Quản lý Dashboard
            {
                path: 'dashboard',
                name: 'admin-dashboard',
                component: () => import('../pages/admin/dashboard/index.vue'),
            },
            // Quản lý Users
            {
                path: 'users',
                name: 'admin-users',
                component: () => import('../pages/admin/users/index.vue'),
            },
            {
                path: 'users/create',
                name: 'admin-users-create',
                component: () => import('../pages/admin/users/add.vue'),
            },

            // Quản lý Roles
            {
                path: "roles",
                name: "admin-roles",
                component: () => import('../pages/admin/roles/index.vue')
            },

            // Quản lý Settings
            {
                path: "settings",
                name: "admin-settings",
                component: () => import('../pages/admin/settings/index.vue')
            },

            // Quản lý Documents
            {
                path: "documents",
                name: "admin-documents",
                component: () => import('../pages/admin/documents/index.vue')
            }
        ]
    }
];

export default admin;