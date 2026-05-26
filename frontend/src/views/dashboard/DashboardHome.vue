<script setup>

import { ref, onMounted } from 'vue'

import { getDashboardData } from '../../services/dashboardService'

const dashboard = ref({
  total_patients: 0,
  total_doctors: 0,
  total_appointments: 0,
  appointments_today: 0,
})

const loadDashboard = async () => {

  try {

    const response = await getDashboardData()

    console.log(response)

    dashboard.value = response.data

  } catch (error) {

    console.error(error)

  }

}

onMounted(() => {

  loadDashboard()

})

</script>

<template>

  <div>

    <h1 class="text-4xl font-bold text-gray-800 mb-8">
      Dashboard
    </h1>

    <!-- Cards -->

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

      <!-- Pacientes -->

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Pacientes
        </p>

        <h2 class="text-4xl font-bold mt-2">
          {{ dashboard.total_patients }}
        </h2>

      </div>

      <!-- Doctores -->

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Doctores
        </p>

        <h2 class="text-4xl font-bold mt-2">
          {{ dashboard.total_doctors }}
        </h2>

      </div>

      <!-- Citas -->

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Total Citas
        </p>

        <h2 class="text-4xl font-bold mt-2">
          {{ dashboard.total_appointments }}
        </h2>

      </div>

      <!-- Citas Hoy -->

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Citas Hoy
        </p>

        <h2 class="text-4xl font-bold mt-2">
          {{ dashboard.appointments_today }}
        </h2>

      </div>

    </div>

  </div>

</template>