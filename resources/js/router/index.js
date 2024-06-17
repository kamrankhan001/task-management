import { createWebHistory, createRouter } from 'vue-router'

import useAuthGuard from '../composables/guards/auth.js';
import useAdminGuard from '../composables/guards/admin.js';

const routes = [

    // Employee Routes
    {
        path: '/employees/dashboard',
        name: 'employeeDashboard',
        component: () => import('../pages/employees/DashboardView.vue'),
        meta: {layout: 'user', title: 'The OOP - Dashboard'},
        beforeEnter: useAuthGuard,
    },
    {
        path: '/employees/tasks',
        name: 'employeeTask',
        component: () => import('../pages/employees/TasksView.vue'),
        meta: {layout: 'user', title: 'The OOP - Tasks'},
        beforeEnter: useAuthGuard,
    },
    {
        path: '/employees/groups',
        name: 'employeeGroups',
        component: () => import('../pages/employees/GroupsView.vue'),
        meta: {layout: 'user', title: 'The OOP - Groups'},
        beforeEnter: useAuthGuard,
    },

    // Admin Routes
    {
        path: '/admin/dashboard',
        name: 'adminDashboard',
        component: () => import('../pages/admin/DashboardView.vue'),
        beforeEnter: [useAuthGuard, useAdminGuard],
        meta: {layout: 'admin', title: 'The OOP - Dashboard'}
    },
    {
        path: '/admin/employees',
        name: 'adminUsers',
        component: () => import('../pages/admin/EmpolyeesView.vue'),
        beforeEnter: [useAuthGuard, useAdminGuard],
        meta: {layout: 'admin', title: 'The OOP - Employees'}
    },
    {
        path: '/admin/categories',
        name: 'adminCategories',
        component: () => import('../pages/admin/CategoryView.vue'),
        beforeEnter: [useAuthGuard, useAdminGuard],
        meta: {layout: 'admin', title: 'The OOP - Categories'}
    },
    {
        path: '/admin/tasks',
        name: 'adminTasks',
        component: () => import('../pages/admin/TaskView.vue'),
        beforeEnter: [useAuthGuard, useAdminGuard],
        meta: {layout: 'admin', title: 'The OOP - Tasks'}
    },
    {
        path: '/admin/groups',
        name: 'adminGroup',
        component: () => import('../pages/admin/GroupView.vue'),
        beforeEnter: [useAuthGuard, useAdminGuard],
        meta: {layout: 'admin', title: 'The OOP - Group'}
    },
    {
        path: '/admin/profile/:id',
        name: 'adminProfile',
        component: () => import('../pages/admin/ProfileView.vue'),
        beforeEnter: [useAuthGuard, useAdminGuard],
        meta: {layout: 'admin', title: 'The OOP - Profile'}
    },

    // Auth Routes
    {
        path: '/',
        name: 'login',
        component: () => import('../pages/auth/LoginView.vue'),
        meta: {layout: 'default', title: 'Login'}
    },
    {
        path: '/logout',
        name: 'logout',
        component: () => import('../pages/auth/LogoutView.vue'),
        meta: {layout: 'default'},
        beforeEnter: useAuthGuard,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router;
