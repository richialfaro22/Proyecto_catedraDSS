import { createRouter, createWebHistory } from 'vue-router'

import LoginView from '../views/auth/LoginView.vue'

import DashboardLayout from '../layouts/DashboardLayout.vue'

import DashboardHome from '../views/dashboard/DashboardHome.vue'
import PatientsView from '../views/patients/PatientsView.vue'
import DoctorsView from '../views/doctors/DoctorsView.vue'
import AppointmentsView from '../views/appointments/AppointmentsView.vue'
import ReportsView from '../views/reports/ReportsView.vue'
import MedicalRecordsView from '../views/medical-records/MedicalRecordsView.vue'
import PrescriptionsView from '../views/prescriptions/PrescriptionsView.vue'
import TreatmentsView from '../views/treatments/TreatmentsView.vue'

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
        component: DashboardHome,
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
      {
        path: 'medical-records',
        component: MedicalRecordsView,
      },
      {
        path: 'prescriptions',
        component: PrescriptionsView,
      },
      {
        path: 'treatments',
        component: TreatmentsView,
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {

  const token = localStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    return '/login'
  }

})

export default router