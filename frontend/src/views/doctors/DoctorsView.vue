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
  <div class="page">

    <!-- Header -->
    <div class="header">
      <div class="header-text">
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="header-icon">
            <i class="ti ti-stethoscope" aria-hidden="true"></i>
          </div>
          <h1>Doctores</h1>
        </div>
        <p>Gestión de doctores del sistema</p>
      </div>

      <button @click="showModal = true" class="btn-nuevo">
        <i class="ti ti-plus" aria-hidden="true"></i>
        Nuevo Doctor
      </button>
    </div>

    <!-- Tabla -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th class="text-left p-4">ID</th>
            <th class="text-left p-4">Nombre</th>
            <th class="text-left p-4">Correo</th>
            <th class="text-left p-4">Especialidad</th>
            <th class="text-left p-4">Teléfono</th>
            <th class="text-left p-4">Licencia</th>
            <th class="text-left p-4">Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="doctor in doctors" :key="doctor.id">
            <td class="p-4 id-cell">{{ doctor.id }}</td>
            <td class="p-4">
              <span class="avatar">{{ (doctor.user?.name || 'SU').slice(0,2).toUpperCase() }}</span>
              {{ doctor.user?.name || 'Sin usuario' }}
            </td>
            <td class="p-4 email-cell">{{ doctor.user?.email || 'Sin correo' }}</td>
            <td class="p-4">
              <span class="badge-specialty">{{ doctor.specialty }}</span>
            </td>
            <td class="p-4 muted-cell">{{ doctor.phone }}</td>
            <td class="p-4 license-cell">{{ doctor.license_number }}</td>
            <td class="p-4">
              <span
                class="badge-status"
                :class="doctor.is_active ? 'badge-active' : 'badge-inactive'"
              >
                {{ doctor.is_active ? 'Activo' : 'Inactivo' }}
              </span>
            </td>
            <td class="p-4">
              <div class="actions-cell">
                <button @click="editDoctor(doctor)" class="btn-editar">
                  <i class="ti ti-edit" aria-hidden="true"></i> Editar
                </button>
                <button @click="deleteDoctor(doctor.id)" class="btn-eliminar">
                  <i class="ti ti-trash" aria-hidden="true"></i> Eliminar
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-backdrop">
      <div class="modal-box">
        <h2>
          <i class="ti ti-user-plus" aria-hidden="true"></i>
          Nuevo Doctor
        </h2>

        <div class="modal-grid">
          <input v-model="form.name"         type="text"   placeholder="Nombre"          class="modal-input" />
          <input v-model="form.email"        type="email"  placeholder="Correo"           class="modal-input" />
          <input v-model.number="form.user_id" type="number" min="1" placeholder="ID Usuario" class="modal-input" />
          <input v-model="form.specialty"    type="text"   placeholder="Especialidad"     class="modal-input" />
          <input v-model="form.phone"        type="text"   placeholder="Teléfono"         class="modal-input" />
          <input v-model="form.license_number" type="text" placeholder="Número Licencia"  class="modal-input" />
        </div>

        <textarea
          v-model="form.bio"
          placeholder="Biografía"
          class="modal-textarea"
          rows="4"
        ></textarea>

        <div class="modal-footer">
          <button @click="showModal = false" class="btn-cancel">Cancelar</button>
          <button @click="saveDoctor" class="btn-save">
            <i class="ti ti-check" aria-hidden="true"></i>
            Guardar
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
* { box-sizing: border-box; }

.page { padding: 2rem; }

/* Header */
.header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; }

.header-text h1 {
  font-size: 26px;
  font-weight: 500;
  color: #6B3A52;
  letter-spacing: -0.3px;
}
.header-text p {
  font-size: 13px;
  color: #A0738A;
  margin-top: 6px;
  margin-left: 46px;
}
.header-icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  background: #F2DCE6;
  display: flex; align-items: center; justify-content: center;
}
.header-icon i { font-size: 18px; color: #6B3A52; }

.btn-nuevo {
  background: #6B3A52;
  color: #fff;
  border: none;
  padding: 10px 22px;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  display: flex; align-items: center; gap: 7px;
  transition: background 0.18s;
}
.btn-nuevo:hover { background: #522C3E; }
.btn-nuevo i { font-size: 15px; }

/* Tabla */
.table-card {
  background: white;
  border-radius: 14px;
  border: 0.5px solid #E4C8D4;
  overflow: hidden;
}

table { width: 100%; border-collapse: collapse; }

thead tr { background: #FDF0F4; }
thead th {
  text-align: left;
  padding: 13px 16px;
  font-size: 11px;
  font-weight: 500;
  color: #A0738A;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  border-bottom: 0.5px solid #E4C8D4;
}

tbody tr { border-top: 0.5px solid #F2E2EA; transition: background 0.12s; }
tbody tr:hover { background: #FDF6F9; }
tbody td { padding: 13px 16px; font-size: 13.5px; vertical-align: middle; }

.id-cell    { color: #A0738A; font-size: 12px; font-weight: 500; }
.email-cell { color: #7A8FA6; }
.muted-cell { color: #888; }
.license-cell { font-family: monospace; font-size: 12px; color: #888; }

.avatar {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px;
  border-radius: 50%;
  background: #F2DCE6;
  color: #6B3A52;
  font-size: 11px; font-weight: 500;
  margin-right: 8px;
  vertical-align: middle;
}

.badge-specialty {
  background: #F0EAF8;
  color: #5B3A8A;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.badge-status {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}
.badge-active   { background: #E8F6EF; color: #2D7A54; }
.badge-inactive { background: #FDEDF0; color: #A0303F; }

.actions-cell { display: flex; gap: 8px; align-items: center; }

.btn-editar {
  background: #FFF4E8;
  color: #9A6300;
  border: 0.5px solid #F5D49A;
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 12px; font-weight: 500;
  cursor: pointer;
  display: flex; align-items: center; gap: 5px;
  transition: background 0.15s;
}
.btn-editar:hover { background: #FFE9C8; }

.btn-eliminar {
  background: #FDF0F2;
  color: #A0303F;
  border: 0.5px solid #F0C0C8;
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 12px; font-weight: 500;
  cursor: pointer;
  display: flex; align-items: center; gap: 5px;
  transition: background 0.15s;
}
.btn-eliminar:hover { background: #F8DADE; }

/* Modal */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(60, 20, 36, 0.38);
  display: flex; align-items: center; justify-content: center;
  z-index: 50;
}

.modal-box {
  background: white;
  border-radius: 16px;
  border: 0.5px solid #E4C8D4;
  padding: 2rem;
  width: 100%;
  max-width: 580px;
}

.modal-box h2 {
  font-size: 18px;
  font-weight: 500;
  color: #6B3A52;
  margin-bottom: 1.5rem;
  display: flex; align-items: center; gap: 8px;
}
.modal-box h2 i { font-size: 20px; color: #D4A0B8; }

.modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.modal-input {
  border: 0.5px solid #D9B8C8;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 13.5px;
  background: #FDF8FA;
  width: 100%;
  outline: none;
  transition: border-color 0.15s;
}
.modal-input:focus { border-color: #6B3A52; }
.modal-input::placeholder { color: #C4A0B2; }

.modal-textarea {
  border: 0.5px solid #D9B8C8;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 13.5px;
  background: #FDF8FA;
  width: 100%;
  margin-top: 12px;
  resize: vertical;
  outline: none;
  transition: border-color 0.15s;
}
.modal-textarea:focus { border-color: #6B3A52; }
.modal-textarea::placeholder { color: #C4A0B2; }

.modal-footer {
  display: flex; justify-content: flex-end; gap: 12px;
  margin-top: 1.5rem;
}

.btn-cancel {
  background: #F5EEF1;
  color: #7A5267;
  border: 0.5px solid #DFC8D4;
  padding: 10px 22px;
  border-radius: 10px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.15s;
}
.btn-cancel:hover { background: #EFE2E9; }

.btn-save {
  background: #6B3A52;
  color: #fff;
  border: none;
  padding: 10px 24px;
  border-radius: 10px;
  font-size: 14px; font-weight: 500;
  cursor: pointer;
  display: flex; align-items: center; gap: 6px;
  transition: background 0.15s;
}
.btn-save:hover { background: #522C3E; }
.btn-save i { font-size: 14px; }
</style>