import { createRouter, createWebHistory } from 'vue-router';
import Settings from './views/Settings.vue';
import Reviews from './views/Reviews.vue';

const routes = [
    {
        path: '/',
        redirect: '/settings'
    },

    {
        path: '/settings',
        name: 'Settings',
        component: Settings
    },

    {
        path: '/reviews',
        name: 'Reviews',
        component: Reviews
    }
];

const router = createRouter({
    history: createWebHistory('/app'),
    routes
});

export default router;
