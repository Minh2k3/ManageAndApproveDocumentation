const approver = [
    {
        path: '/approver',
        name: 'approver',
        redirect: '/approver/dashboard',
        component: () => import('@/layouts/approver.vue'),
        children: [
            // Quản lý Dashboard
            {
                path: 'dashboard',
                name: 'approver-dashboard',
                component: () => import('@/pages/approver/dashboard/index.vue'),
            },
            {
                path: 'notification',
                name: 'approver-notification',
                component: () => import('@/pages/approver/dashboard/notification.vue'),
            },

            // Quản lý Settings
            {
                path: "settings",
                name: "approver-settings",
                component: () => import('@/pages/approver/settings/index.vue')
            },

            // Quản lý Documents
            {
                path: "documents",
                name: "approver-documents",
                component: () => import('@/pages/approver/documents/index.vue')
            }, 
            {
                path: "documents/create",
                name: "approver-documents-create",
                component: () => import('@/pages/approver/documents/create.vue')
            },
            {
                path: "documents/:id",
                name: "approver-documents-detail",
                component: () => import('@/pages/approver/documents/detail.vue')
            },
           {
                path: "documents/:id/edit",
                name: "approver-documents-edit",
                component: () => import('@/pages/approver/documents/edit.vue')
            },
            {
                path: "documents/template",
                name: "approver-documents-template",
                component: () => import('@/pages/approver/documents/template.vue')
            },
            {
                path: "documents/approve",
                name: "approver-documents-approve",
                component: () => import('@/pages/approver/documents/approve.vue')
            },
            // Quản lý Departments
            {
                path: "departments",
                name: "approver-departments",
                component: () => import('@/pages/approver/departments/index.vue')
            },

            // Quản lý Signatures
            {
                path: "signatures",
                name: "approver-signatures",
                component: () => import('@/pages/approver/signatures/index.vue')
            },
                
        ]
    }
];

export default approver;