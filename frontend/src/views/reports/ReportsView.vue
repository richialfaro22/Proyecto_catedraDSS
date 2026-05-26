<script setup>

import { ref, onMounted } from 'vue'

import {
  getDashboardReport,
  getAppointmentsByDoctor,
  getFrequentDiagnoses
} from '../../services/reportService'

const dashboard = ref(null)

const doctors = ref([])

const diagnoses = ref([])

const loadReports = async () => {

  try {

    const dashboardResponse = await getDashboardReport()

    const doctorsResponse = await getAppointmentsByDoctor()

    const diagnosesResponse = await getFrequentDiagnoses()

    dashboard.value = dashboardResponse.data

    doctors.value = doctorsResponse.data

    diagnoses.value = diagnosesResponse.data

    console.log(dashboard.value)

  } catch (error) {

    console.error(error)

  }

}

onMounted(() => {

  loadReports()

})

</script>

<template>

  <div>

    <!-- Header -->

    <div class="mb-8">

      <h1 class="text-4xl font-bold text-gray-800">
        Reportes
      </h1>

      <p class="text-gray-500 mt-2">
        Estadísticas del sistema dermatológico
      </p>

    </div>

    <!-- Cards -->

    <div
      v-if="dashboard"
      class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10"
    >

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Pacientes
        </p>

        <h2 class="text-4xl font-bold mt-2 text-blue-600">
          {{ dashboard.total_patients }}
        </h2>

      </div>

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Doctores
        </p>

        <h2 class="text-4xl font-bold mt-2 text-green-600">
          {{ dashboard.total_doctors }}
        </h2>

      </div>

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Citas
        </p>

        <h2 class="text-4xl font-bold mt-2 text-purple-600">
          {{ dashboard.total_appointments }}
        </h2>

      </div>

      <div class="bg-white rounded-2xl shadow p-6">

        <p class="text-gray-500">
          Citas Hoy
        </p>

        <h2 class="text-4xl font-bold mt-2 text-red-600">
          {{ dashboard.appointments_today }}
        </h2>

      </div>

    </div>

    <!-- Citas por Doctor -->

    <div class="bg-white rounded-2xl shadow p-6 mb-8">

      <h2 class="text-2xl font-bold mb-6">
        Citas por Doctor
      </h2>

      <table class="w-full">

        <thead class="bg-gray-100">

          <tr>

            <th class="text-left p-4">
              Doctor
            </th>

            <th class="text-left p-4">
              Especialidad
            </th>

            <th class="text-left p-4">
              Total Citas
            </th>

          </tr>

        </thead>

        <tbody>

          <tr
            v-for="doctor in doctors"
            :key="doctor.doctor"
            class="border-t"
          >

            <td class="p-4">
              {{ doctor.doctor }}
            </td>

            <td class="p-4">
              {{ doctor.specialty }}
            </td>

            <td class="p-4 font-bold text-blue-600">
              {{ doctor.total_appointments }}
            </td>

          </tr>

        </tbody>

      </table>

    </div>

    <!-- Diagnósticos -->

    <div class="bg-white rounded-2xl shadow p-6">

      <h2 class="text-2xl font-bold mb-6">
        Diagnósticos Frecuentes
      </h2>

      <table class="w-full">

        <thead class="bg-gray-100">

          <tr>

            <th class="text-left p-4">
              Diagnóstico
            </th>

            <th class="text-left p-4">
              Total
            </th>

          </tr>

        </thead>

        <tbody>

          <tr
            v-for="diagnosis in diagnoses"
            :key="diagnosis.skin_condition"
            class="border-t"
          >

            <td class="p-4">
              {{ diagnosis.skin_condition }}
            </td>

            <td class="p-4 font-bold text-green-600">
              {{ diagnosis.total }}
            </td>

          </tr>

        </tbody>

      </table>

    </div>

  </div>

</template>