import { createRouter, createWebHistory } from 'vue-router'

import LoginView from '../views/auth/LoginView.vue'

import DashboardLayout from '../layouts/DashboardLayout.vue'

import DashboardView from '../views/dashboard/DashboardView.vue'
import PatientsView from '../views/patients/PatientsView.vue'
import DoctorsView from '../views/doctors/DoctorsView.vue'
import AppointmentsView from '../views/appointments/AppointmentsView.vue'
import ReportsView from '../views/reports/ReportsView.vue'

const routes = [
  {
    path: '/',
    redirect: '/login',
  },

  {
    path: '/login',
    component: LoginView,
  },

  {
    path: '/',
    component: DashboardLayout,
    meta: { requiresAuth: true },

    children: [
      {
        path: 'dashboard',
        component: DashboardView,
      },

      {
        path: 'patients',
        component: PatientsView,
      },

      {
        path: 'doctors',
        component: DoctorsView,
      },

      {
        path: 'appointments',
        component: AppointmentsView,
      },

      {
        path: 'reports',
        component: ReportsView,
      },
    ],
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