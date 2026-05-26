<script setup>

import { ref, onMounted } from 'vue'

import {
  getDoctors,
  createDoctor,
  updateDoctor,
  deleteDoctorService
} from '../../services/doctorService'

const doctors = ref([])

const showModal = ref(false)

const form = ref({
  user_id: '',
  specialty: '',
  phone: '',
  license_number: '',
  bio: '',
  is_active: true,
})

const loadDoctors = async () => {

  try {

    const response = await getDoctors()

    console.log(response)

    doctors.value = response.data.data

  } catch (error) {

    console.error(error)

  }

}

const saveDoctor = async () => {

  try {

    if (form.value.id) {

      await updateDoctor(form.value.id, form.value)

    } else {

      await createDoctor(form.value)

    }

    showModal.value = false

    resetForm()

    loadDoctors()

  } catch (error) {

    console.error(error)

    console.log(error.response.data)

  }

}


const resetForm = () => {

  form.value = {
    id: null,
    user_id: '',
    name: '',
    email: '',
    specialty: '',
    phone: '',
    license_number: '',
    bio: '',
    is_active: true,
  }

}

const editDoctor = (doctor) => {

  form.value = {

    id: doctor.id,

    user_id: doctor.user_id,

    name: doctor.user?.name || '',

    email: doctor.user?.email || '',

    specialty: doctor.specialty || '',

    phone: doctor.phone || '',

    license_number: doctor.license_number || '',

    bio: doctor.bio || '',

    is_active: doctor.is_active,

  }

  showModal.value = true

}

const deleteDoctor = async (id) => {

  const confirmDelete = confirm('¿Deseas eliminar este doctor?')

  if (!confirmDelete) return

  try {

    await deleteDoctorService(id)

    loadDoctors()

  } catch (error) {

    console.error(error)

  }

}

onMounted(() => {

  loadDoctors()

})

</script>

<template>

  <div>

    <!-- Header -->

    <div class="flex justify-between items-center mb-8">

      <div>

        <h1 class="text-4xl font-bold text-gray-800">
          Doctores
        </h1>

        <p class="text-gray-500 mt-2">
          Gestión de doctores del sistema
        </p>

      </div>

      <button
        @click="showModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition"
      >
        Nuevo Doctor
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
              Nombre
            </th>

            <th class="text-left p-4">
              Correo
            </th>

            <th class="text-left p-4">
              Especialidad
            </th>

            <th class="text-left p-4">
              Teléfono
            </th>

            <th class="text-left p-4">
              Licencia
            </th>

            <th class="text-left p-4">
              Estado
            </th>

            

          </tr>
          

        </thead>

        <tbody>

          <tr
            v-for="doctor in doctors"
            :key="doctor.id"
            class="border-t"
          >

            <td class="p-4">
              {{ doctor.id }}
            </td>

            <td class="p-4">
              {{ doctor.user?.name || 'Sin usuario' }}
            </td>

            <td class="p-4">
              {{ doctor.user?.email || 'Sin correo' }}
            </td>

            <td class="p-4">
              {{ doctor.specialty }}
            </td>

            <td class="p-4">
              {{ doctor.phone }}
            </td>

            <td class="p-4">
              {{ doctor.license_number }}
            </td>

            <td class="p-4">

              <span
                class="px-3 py-1 rounded-full text-sm"
                :class="doctor.is_active
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'
                "
              >
                {{ doctor.is_active ? 'Activo' : 'Inactivo' }}
              </span>

            </td>
            <td class="px-4 py-2 flex gap-2">

            <button
              @click="editDoctor(doctor)"
              class="bg-yellow-500 text-white px-3 py-1 rounded"
            >
              Editar
            </button>

            <button
              @click="deleteDoctor(doctor.id)"
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

      <div class="bg-white rounded-2xl p-8 w-full max-w-2xl">

        <h2 class="text-2xl font-bold mb-6">
          Nuevo Doctor
        </h2>

        <div class="grid grid-cols-2 gap-4">

          <input
            v-model="form.name"
            type="text"
            placeholder="Nombre"
            class="border rounded-xl px-4 py-3"
          />

          <input
            v-model="form.email"
            type="email"
            placeholder="Correo"
            class="border rounded-xl px-4 py-3"
          />

          <input
            v-model.number="form.user_id"
            type="number"
            min="1"
            placeholder="ID Usuario"
            class="border rounded-xl px-4 py-3"
          />

          <input
            v-model="form.specialty"
            type="text"
            placeholder="Especialidad"
            class="border rounded-xl px-4 py-3"
          />

          <input
            v-model="form.phone"
            type="text"
            placeholder="Teléfono"
            class="border rounded-xl px-4 py-3"
          />

          <input
            v-model="form.license_number"
            type="text"
            placeholder="Número Licencia"
            class="border rounded-xl px-4 py-3"
          />

        </div>

        <textarea
          v-model="form.bio"
          placeholder="Biografía"
          class="border rounded-xl px-4 py-3 w-full mt-4"
          rows="4"
        ></textarea>

        <div class="flex justify-end gap-4 mt-6">

          <button
            @click="showModal = false"
            class="px-6 py-3 rounded-xl bg-gray-200"
          >
            Cancelar
          </button>

          <button
            @click="saveDoctor"
            class="px-6 py-3 rounded-xl bg-blue-600 text-white"
          >
            Guardar
          </button>

        </div>

      </div>

    </div>

  </div>

</template>