<script setup>

import { ref, onMounted } from 'vue'

import {
  getAppointments,
  createAppointment,
  updateAppointment,
  deleteAppointment
} from '../../services/appointmentService'

const appointments = ref([])

const showModal = ref(false)
const editingAppointment = ref(null)

const form = ref({
  patient_id: '',
  doctor_id: '',
  appointment_date: '',
  status: 'pending',
  reason: '',
  notes: '',
  cost: '',
  is_paid: false,
})

const loadAppointments = async () => {

  try {

    const response = await getAppointments()

    console.log(response)

    appointments.value = response.data.data

  } catch (error) {

    console.error(error)

  }

}

const editAppointment = (appointment) => {

  editingAppointment.value = appointment

  form.value = {
    patient_id: appointment.patient_id,
    doctor_id: appointment.doctor_id,

    appointment_date: appointment.appointment_date
      ? appointment.appointment_date.slice(0,16)
      : '',

    status: appointment.status,
    reason: appointment.reason,
    notes: appointment.notes,
    cost: appointment.cost,
    is_paid: appointment.is_paid,
  }

  showModal.value = true

}

const saveAppointment = async () => {

  try {

    if (editingAppointment.value) {

      await updateAppointment(
        editingAppointment.value.id,
        form.value
      )

    } else {

      await createAppointment(form.value)

    }

    showModal.value = false

    resetForm()

    editingAppointment.value = null

    await loadAppointments()

  } catch (error) {

    console.error(error)

    console.log(error.response.data)

  }

}

const resetForm = () => {

  form.value = {
    patient_id: '',
    doctor_id: '',
    appointment_date: '',
    status: 'pending',
    reason: '',
    notes: '',
    cost: '',
    is_paid: false,
  }

  editingAppointment.value = null

}

const deleteAppointmentAction = async (id) => {

  const confirmed = confirm('¿Eliminar esta cita?')

  if (!confirmed) return

  try {

    await deleteAppointment(id)

    await loadAppointments()

  } catch (error) {

    console.error(error)

  }

}

onMounted(() => {

  loadAppointments()

})

</script>

<template>

  <div>

    <!-- Header -->

    <div class="flex justify-between items-center mb-8">

      <div>

        <h1 class="text-4xl font-bold text-gray-800">
          Citas
        </h1>

        <p class="text-gray-500 mt-2">
          Gestión de citas dermatológicas
        </p>

      </div>

      <button
        @click="showModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition"
      >
        {{ editingAppointment ? 'Editar Cita' : 'Nueva Cita' }}
      </button>

    </div>

    <!-- Tabla -->

    <div class="bg-white rounded-2xl shadow overflow-hidden">

      <table class="w-full">

        <thead class="bg-gray-100">

          <tr>

            <th class="text-left p-4">
              ID
            </th>

            <th class="text-left p-4">
              Paciente
            </th>

            <th class="text-left p-4">
              Doctor
            </th>

            <th class="text-left p-4">
              Fecha
            </th>

            <th class="text-left p-4">
              Estado
            </th>

            <th class="text-left p-4">
              Motivo
            </th>

            <th class="text-left p-4">
              Costo
            </th>

            <th class="text-left p-4">
              Acciones
            </th>

          </tr>

        </thead>

        <tbody>

          <tr
            v-for="appointment in appointments"
            :key="appointment.id"
            class="border-t"
          >

            <td class="p-4">
              {{ appointment.id }}
            </td>

            <td class="p-4">
              {{ appointment.patient?.user?.name || 'Sin paciente' }}
            </td>

            <td class="p-4">
              {{ appointment.doctor?.user?.name || 'Sin doctor' }}
            </td>

            <td class="p-4">
              {{ appointment.appointment_date }}
            </td>

            <td class="p-4">

              <span
                class="px-3 py-1 rounded-full text-sm"
                :class="{
                  'bg-yellow-100 text-yellow-700': appointment.status === 'pending',
                  'bg-green-100 text-green-700': appointment.status === 'confirmed',
                  'bg-red-100 text-red-700': appointment.status === 'cancelled'
                }"
              >
                {{ appointment.status }}
              </span>

            </td>

            <td class="p-4">
              {{ appointment.reason }}
            </td>

            <td class="p-4">
              ${{ appointment.cost }}
            </td>

            <td class="p-4 flex gap-2">

            <button
              @click="editAppointment(appointment)"
              class="bg-yellow-500 text-white px-3 py-1 rounded"
            >
              Editar
            </button>

            <button
              @click="deleteAppointmentAction(appointment.id)"
              class="bg-red-600 text-white px-3 py-1 rounded"
            >
              Eliminar
            </button>

          </td>

          </tr>

        </tbody>

      </table>

    </div>

    <!-- Modal -->

    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
    >

      <div class="bg-white rounded-2xl p-8 w-full max-w-3xl">

        <h2 class="text-2xl font-bold mb-6">
          Nueva Cita
        </h2>

        <div class="grid grid-cols-2 gap-4">

          <input
            v-model.number="form.patient_id"
            type="number"
            min="1"
            placeholder="ID Paciente"
            class="border rounded-xl px-4 py-3"
          />

          <input
            v-model.number="form.doctor_id"
            type="number"
            min="1"
            placeholder="ID Doctor"
            class="border rounded-xl px-4 py-3"
          />

          <input
            v-model="form.appointment_date"
            type="datetime-local"
            class="border rounded-xl px-4 py-3"
          />

          <select
            v-model="form.status"
            class="border rounded-xl px-4 py-3"
          >

            <option value="pending">
              Pendiente
            </option>

            <option value="confirmed">
              Confirmada
            </option>

            <option value="cancelled">
              Cancelada
            </option>

          </select>

          <input
            v-model="form.cost"
            type="number"
            min="0"
            step="0.01"
            placeholder="Costo"
            class="border rounded-xl px-4 py-3"
          />
          

          <select
            v-model="form.is_paid"
            class="border rounded-xl px-4 py-3"
          >

            <option :value="true">
              Pagada
            </option>

            <option :value="false">
              Pendiente Pago
            </option>

          </select>

        </div>

        <textarea
          v-model="form.reason"
          placeholder="Motivo de consulta"
          rows="3"
          class="border rounded-xl px-4 py-3 w-full mt-4"
        ></textarea>

        <textarea
          v-model="form.notes"
          placeholder="Notas adicionales"
          rows="3"
          class="border rounded-xl px-4 py-3 w-full mt-4"
        ></textarea>

        <div class="flex justify-end gap-4 mt-6">

          <button
            @click="showModal = false"
            class="px-6 py-3 rounded-xl bg-gray-200"
          >
            Cancelar
          </button>

          <button
            @click="saveAppointment"
            class="px-6 py-3 rounded-xl bg-blue-600 text-white"
          >
            {{ editingAppointment ? 'Actualizar' : 'Guardar' }}
          </button>

        </div>

      </div>

    </div>

  </div>

</template>