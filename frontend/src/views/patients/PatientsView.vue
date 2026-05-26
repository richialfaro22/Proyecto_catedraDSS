<script setup>
import { ref, onMounted } from 'vue'

import {
  getPatients,
  createPatient,
  updatePatient,
  deletePatient
} from '../../services/patientService'

const patients = ref([])

const showModal = ref(false)

const isEditing = ref(false)

const selectedPatientId = ref(null)

const form = ref({
  id: null,
  user_id: '',
  name: '',
  email: '',
  phone: '',
  address: '',
  birth_date: '',
})

const loadPatients = async () => {
  try {

    const response = await getPatients()

    console.log(response.data)

    patients.value = response.data.data

  } catch (error) {

    console.error(error)

  }
}

const openCreateModal = () => {
  isEditing.value = false

  form.value = {
    name: '',
    email: '',
    phone: '',
    address: '',
    birth_date: '',
  }

  showModal.value = true
}

const onlyNumbers = (event) => {

  if (
    ['e', 'E', '+', '-', '.'].includes(event.key)
  ) {
    event.preventDefault()
  }

}

const openEditModal = (patient) => {
  form.value = {

    id: patient.id,

    user_id: patient.user_id,

    name: patient.user?.name || '',

    email: patient.user?.email || '',

    phone: patient.phone || '',

    birth_date: patient.birth_date
      ? patient.birth_date.split('T')[0]
      : '',

    gender: patient.gender || '',

    address: patient.address || '',

    emergency_contact: patient.emergency_contact || '',

    emergency_phone: patient.emergency_phone || '',

    allergies: patient.allergies || '',

  }

  showModal.value = true

}

const savePatient = async () => {
    const resetForm = () => {

    form.value = {

      id: null,

      user_id: '',

      name: '',

      email: '',

      phone: '',

      birth_date: '',

      gender: '',

      address: '',

      emergency_contact: '',

      emergency_phone: '',

      allergies: '',

    }

  }
   try {

    if (form.value.id) {

      await updatePatient(form.value.id, form.value)

    } else {

      await createPatient(form.value)

    }

    showModal.value = false

    resetForm()

    await loadPatients()

  } catch (error) {

    console.error(error)

  }
}

const removePatient = async (id) => {

  const confirmDelete = confirm('¿Eliminar paciente?')

  if (!confirmDelete) return

  try {
    await deletePatient(id)

    loadPatients()

  } catch (error) {
    console.error(error)
  }
}

onMounted(() => {
  loadPatients()
})
</script>

<template>

  <div>

    <div class="flex items-center justify-between mb-8">

      <h1 class="text-4xl font-bold text-gray-800">
        Pacientes
      </h1>

      <button
        @click="openCreateModal"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg"
      >
        Nuevo Paciente
      </button>

    </div>

    <!-- Tabla -->

    <div class="bg-white rounded-2xl shadow overflow-hidden">

      <table class="w-full">

        <thead class="bg-gray-100">

          <tr>
            <th class="text-left p-4">User ID</th>
            <th class="text-left p-4">Nombre</th>
            <th class="text-left p-4">Email</th>
            <th class="text-left p-4">Teléfono</th>
            <th class="text-left p-4">Acciones</th>

          </tr>

        </thead>

        <tbody>

          <tr
            v-for="patient in patients"
            :key="patient.id"
            class="border-t"
          >

            <td class="p-4">
              {{ patient.user_id }}
            </td>

            <td class="p-4">
              {{ patient.user?.name || 'Sin usuario' }}
            </td>

            <td class="p-4">
              {{ patient.user?.email || 'Sin correo' }}
            </td>

            <td class="p-4">
              {{ patient.phone }}
            </td>

            <td class="p-4 flex gap-2">

              <button
                @click="openEditModal(patient)"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
              >
                Editar
              </button>

              <button
                @click="removePatient(patient.id)"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
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
      class="fixed inset-0 bg-black/50 flex items-center justify-center"
    >

      <div class="bg-white p-8 rounded-2xl w-full max-w-lg">

        <h2 class="text-2xl font-bold mb-6">

          {{ isEditing ? 'Editar Paciente' : 'Nuevo Paciente' }}

        </h2>

        <div class="space-y-4">

          <input
            v-model.number="form.user_id"
            type="number"
            min="1"
            placeholder="ID Usuario"
            class="w-full border rounded-lg px-4 py-2"
          />

          <input
            v-model="form.name"
            type="text"
            placeholder="Nombre"
            class="w-full border rounded-lg px-4 py-2"
          />

          <input
            v-model="form.email"
            type="email"
            placeholder="Email"
            class="w-full border rounded-lg px-4 py-2"
          />

          <input
            v-model="form.phone"
            type="text"
            placeholder="Teléfono"
            class="w-full border rounded-lg px-4 py-2"
          />

          <input
            v-model="form.address"
            type="text"
            placeholder="Dirección"
            class="w-full border rounded-lg px-4 py-2"
          />

          <input
            v-model="form.birth_date"
            type="date"
            class="w-full border rounded-lg px-4 py-2"
          />

        </div>

        <div class="flex justify-end gap-3 mt-6">

          <button
            @click="showModal = false"
            class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg"
          >
            Cancelar
          </button>

          <button
            @click="savePatient"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
          >
            Guardar
          </button>

        </div>

      </div>

    </div>

  </div>

</template>