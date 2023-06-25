
import { createRouter, createWebHistory } from 'vue-router';

const Home = () => import('@/pages/dashboard/Home.vue')
const Login = () => import('@/pages/auth/Login.vue')

const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/home',
        name: 'home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;