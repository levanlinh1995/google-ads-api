
import { createRouter, createWebHistory } from 'vue-router';

const Home = () => import('@/pages/dashboard/Home.vue')
const Login = () => import('@/pages/auth/Login.vue')
const CallBackGoogle = () => import('@/pages/auth/CallbackGoogle.vue')

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
        path: '/auth/login',
        name: 'login',
        component: Login
    },
    {
        path: '/auth/callback/google',
        name: 'callback_google',
        component: CallBackGoogle
    },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;