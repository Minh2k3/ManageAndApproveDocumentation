const creator = [
    {
        path: '/creator',
        name: 'creator',
        redirect: '/creator/dashboard',
        component: () => import('@/layouts/creator.vue'),
        children: [
            // Quản lý Dashboard
            {
                path: 'dashboard',
                name: 'creator-dashboard',
                component: () => import('@/pages/creator/dashboard/index.vue'),
            },
            {
                path: 'notification',
                name: 'creator-notification',
                component: () => import('@/pages/creator/dashboard/notification.vue'),
            },

            // Quản lý Settings
            {
                path: "settings",
                name: "creator-settings",
                component: () => import('@/pages/creator/settings/index.vue')
            },

            // Quản lý Documents
            {
                path: "documents",
                name: "creator-documents",
                component: () => import('@/pages/creator/documents/index.vue')
            }, 
            {
                path: "documents/create",
                name: "creator-documents-create",
                component: () => import('@/pages/creator/documents/create.vue')
            },
            {
                path: "documents/history",
                name: "creator-documents-history",
                component: () => import('@/pages/creator/documents/history.vue')
            },
            {
                path: "documents/:id",
                name: "creator-documents-detail",
                component: () => import('@/pages/creator/documents/detail.vue')
            },
            {
                path: "documents/template",
                name: "creator-documents-template",
                component: () => import('@/pages/creator/documents/template.vue')
            },
            {
                path: "documents/:id/edit",
                name: "creator-documents-edit",
                component: () => import('@/pages/creator/documents/edit.vue')
            },

            // Quản lý Signatures
            {
                path: "signatures",
                name: "creator-signatures",
                component: () => import('@/pages/creator/signatures/index.vue')
            },

            // Quản lý Departments
            {
                path: "departments",
                name: "creator-departments",
                component: () => import('@/pages/creator/departments/index.vue')
            },
                
        ]
    }
];

export default creator;