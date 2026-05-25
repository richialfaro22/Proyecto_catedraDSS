import { createRouter, createWebHistory } from 'vue-router'

import LoginView from '../views/auth/LoginView.vue'
import DashboardView from '../views/dashboard/DashboardView.vue'

const routes = [
  {
    path: '/',
    redirect: '/login',
  },

  {
    path: '/login',
    name: 'login',
    component: LoginView,
  },

  {
    path: '/dashboard',
    name: 'dashboard',
    component: DashboardView,
    meta: { requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    next('/login')
  } else {
    next()
  }
})

export default router