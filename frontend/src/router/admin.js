const admin = [
    {
        path: '/admin',
        name: 'admin',
        redirect: '/admin/dashboard',
        component: () => import('@/layouts/admin.vue'),
        children: [
            // Quản lý Dashboard
            {
                path: 'dashboard',
                name: 'admin-dashboard',
                component: () => import('@/pages/admin/dashboard/index.vue'),
            },
            {
                path: 'notification',
                name: 'admin-notification',
                component: () => import('@/pages/admin/dashboard/notification.vue'),
            },
            // Quản lý Users
            {
                path: 'users',
                name: 'admin-users',
                component: () => import('@/pages/admin/users/index.vue'),
            },
            {
                path: 'users/create',
                name: 'admin-users-create',
                component: () => import('@/pages/admin/users/add.vue'),
            },
            {
                path: 'users/dep-and-roll',
                name: 'admin-users-departments-and-rolls',
                component: () => import('@/pages/admin/users/departments_and_rolls.vue')
            },

            // Quản lý Roles
            {
                path: "roles",
                name: "admin-roles",
                component: () => import('@/pages/admin/roles/index.vue')
            },
            {
                path: "roles/permissions",
                name: "admin-roles-permissions",
                component: () => import('@/pages/admin/roles/permission.vue')
            },
            {
                path: "roles/make_permission",
                name: "admin-roles-make_permission",
                component: () => import('@/pages/admin/roles/make_permission.vue')
            },

            // Quản lý Settings
            {
                path: "settings",
                name: "admin-settings",
                component: () => import('@/pages/admin/settings/index.vue')
            },

            // Quản lý Documents
            {
                path: "documents",
                name: "admin-documents",
                component: () => import('@/pages/admin/documents/index.vue')
            }, 
            {
                path: "documents/template",
                name: "admin-documents-template",
                component: () => import('@/pages/admin/documents/template.vue')
            },
            {
                path: "documents/history",
                name: "admin-documents-history",
                component: () => import('@/pages/admin/documents/history.vue')
            },
            {
                path: "documents/type",
                name: "admin-documents-type",
                component: () => import('@/pages/admin/documents/type.vue')
            },
            {
                path: "documents/:id",
                name: "admin-documents-detail",
                component: () => import('@/pages/admin/documents/detail.vue')
            },

            // Quản lý Approval-Flows
            {
                path: "approval-flows",
                name: "admin-approval-flows",
                component: () => import('@/pages/admin/approval-flows/index.vue')
            },
            {
                path: "approval-flows/:id",
                name: "admin-approval-flows-detail",
                component: () => import('@/pages/admin/approval-flows/detail.vue')
            },
            {
                path: "approval-flows/create",
                name: "admin-approval-flows-create",
                component: () => import('@/pages/admin/approval-flows/create.vue')
            },

            // Quản lý Chữ ký số
            {
                path: "signatures",
                name: "admin-signatures",
                component: () => import('@/pages/admin/signatures/index.vue')
            },
            {
                path: "signatures/detail",
                name: "admin-signatures-detail",
                component: () => import('@/pages/admin/signatures/detail.vue')
            },
                
        ]
    }
];

export default admin;