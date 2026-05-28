<script setup>
import { ref, onMounted } from 'vue'
import Swal from 'sweetalert2'

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

const loading = ref(false)

const tableLoading = ref(false)

const form = ref({
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

  loading.value = true
  tableLoading.value = true

   try {

    if (form.value.id) {

      await updatePatient(form.value.id, form.value)

    } else {

      await createPatient(form.value)

    }

    showModal.value = false

    resetForm()

    await loadPatients()
    Swal.fire({
      icon: 'success',
      title: 'Éxito',
      text: form.value.id
        ? 'Paciente actualizado correctamente'
        : 'Paciente creado correctamente',
      timer: 2000,
      showConfirmButton: false,
    })

  } catch (error) {

    console.error(error)
    /*
    console.log(error.response.data)*/
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text:
        error.response?.data?.message ||
        'Ocurrió un error',
    })

  }finally {

    loading.value = false
    tableLoading.value = false

  }
}

const removePatient = async (id) => {

  const confirmDelete = await Swal.fire({

    title: '¿Eliminar paciente?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  })

  if (!confirmDelete.isConfirmed) return

  try {

    await deletePatient(id)

    await loadPatients()

    Swal.fire({
      icon: 'success',
      title: 'Eliminado',
      text: 'Paciente eliminado correctamente',
      timer: 2000,
      showConfirmButton: false,
    })

  } catch (error) {

    console.error(error)

    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No se pudo eliminar el paciente',
    })

  }
}
onMounted(() => {
  loadPatients()
  
})
</script>

<template>
  <div class="page">

    <div class="header">
      <div class="header-text">
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="header-icon">
            <i class="ti ti-users" aria-hidden="true"></i>
          </div>
          <h1>Pacientes</h1>
        </div>
      </div>

      <button @click="openCreateModal" class="btn-nuevo">
        <i class="ti ti-plus" aria-hidden="true"></i>
        Nuevo Paciente
      </button>
    </div>

    <!-- Tabla -->
    <div class="table-card">

      <div v-if="tableLoading" class="loading-wrap">
        <div class="spinner"></div>
      </div>

      <table v-if="!tableLoading">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Género</th>
            <th>Dirección</th>
            <th>Emergencia</th>
            <th>Alergias</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="patient in patients" :key="patient.id">
            <td class="id-cell">{{ patient.user_id }}</td>
            <td>
              <span class="avatar">{{ (patient.user?.name || 'SU').slice(0,2).toUpperCase() }}</span>
              {{ patient.user?.name || 'Sin usuario' }}
            </td>
            <td class="email-cell">{{ patient.user?.email || 'Sin correo' }}</td>
            <td class="muted-cell">{{ patient.phone }}</td>
            <td>
              <span class="badge-gender">{{ patient.gender }}</span>
            </td>
            <td class="muted-cell">{{ patient.address }}</td>
            <td class="muted-cell">
              {{ patient.emergency_contact }}<br>
              <span class="license-cell">{{ patient.emergency_phone }}</span>
            </td>
            <td class="muted-cell">{{ patient.allergies }}</td>
            <td>
              <div class="actions-cell">
                <button @click="openEditModal(patient)" class="btn-editar">
                  <i class="ti ti-edit" aria-hidden="true"></i> Editar
                </button>
                <button @click="removePatient(patient.id)" class="btn-eliminar">
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
          {{ isEditing ? 'Editar Paciente' : 'Nuevo Paciente' }}
        </h2>

        <div class="modal-fields">
          <input v-model.number="form.user_id"         type="number" min="1"  placeholder="ID Usuario"               class="modal-input" />
          <input v-model="form.name"                   type="text"             placeholder="Nombre"                   class="modal-input" />
          <input v-model="form.email"                  type="email"            placeholder="Email"                    class="modal-input" />
          <input v-model="form.phone"                  type="text"             placeholder="Teléfono"                 class="modal-input" />
          <input v-model="form.address"                type="text"             placeholder="Dirección"                class="modal-input" />
          <input v-model="form.birth_date"             type="date"                                                    class="modal-input" />
          <select v-model="form.gender"                                                                               class="modal-input">
            <option value="">Seleccione género</option>
            <option value="male">Masculino</option>
            <option value="female">Femenino</option>
            <option value="other">Otro</option>
          </select>
          <input v-model="form.emergency_contact"      type="text"             placeholder="Contacto de emergencia"   class="modal-input" />
          <input v-model="form.emergency_phone"        type="text"             placeholder="Teléfono de emergencia"   class="modal-input" />
          <textarea v-model="form.allergies"                                   placeholder="Alergias"                 class="modal-textarea"></textarea>
        </div>

        <div class="modal-footer">
          <button @click="showModal = false" class="btn-cancel">Cancelar</button>
          <button @click="savePatient" :disabled="loading" class="btn-save" :class="{ 'btn-save-disabled': loading }">
            <span v-if="loading">Guardando...</span>
            <span v-else><i class="ti ti-check" aria-hidden="true"></i> Guardar</span>
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
  color: #2A6A8A;
  letter-spacing: -0.3px;
}
.header-icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  background: #D6EEF8;
  display: flex; align-items: center; justify-content: center;
}
.header-icon i { font-size: 18px; color: #2A6A8A; }

.btn-nuevo {
  background: #2A6A8A;
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
.btn-nuevo:hover { background: #1E5070; }
.btn-nuevo i { font-size: 15px; }

/* Tabla */
.table-card {
  background: white;
  border-radius: 14px;
  border: 0.5px solid #B8D8EA;
  overflow: hidden;
}

.loading-wrap {
  display: flex; justify-content: center; align-items: center;
  padding: 2.5rem 0;
}
.spinner {
  width: 40px; height: 40px;
  border-radius: 50%;
  border: 3px solid #D6EEF8;
  border-bottom-color: #2A6A8A;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

table { width: 100%; border-collapse: collapse; }

thead tr { background: #EDF6FB; }
thead th {
  text-align: left;
  padding: 13px 16px;
  font-size: 11px;
  font-weight: 500;
  color: #5A90AA;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  border-bottom: 0.5px solid #B8D8EA;
}

tbody tr { border-top: 0.5px solid #E2F0F7; transition: background 0.12s; }
tbody tr:hover { background: #F5FBFE; }
tbody td { padding: 13px 16px; font-size: 13.5px; vertical-align: middle; }

.id-cell    { color: #5A90AA; font-size: 12px; font-weight: 500; }
.email-cell { color: #7A8FA6; }
.muted-cell { color: #888; font-size: 13px; }
.license-cell { font-family: monospace; font-size: 12px; color: #888; }

.avatar {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px;
  border-radius: 50%;
  background: #D6EEF8;
  color: #2A6A8A;
  font-size: 11px; font-weight: 500;
  margin-right: 8px;
  vertical-align: middle;
}

.badge-gender {
  background: #E8F4FB;
  color: #1E5A80;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
  text-transform: capitalize;
}

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
  background: rgba(20, 50, 70, 0.38);
  display: flex; align-items: center; justify-content: center;
  z-index: 50;
}

.modal-box {
  background: white;
  border-radius: 16px;
  border: 0.5px solid #B8D8EA;
  padding: 2rem;
  width: 100%;
  max-width: 520px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-box h2 {
  font-size: 18px;
  font-weight: 500;
  color: #2A6A8A;
  margin-bottom: 1.5rem;
  display: flex; align-items: center; gap: 8px;
}
.modal-box h2 i { font-size: 20px; color: #7ABCD8; }

.modal-fields { display: flex; flex-direction: column; gap: 12px; }

.modal-input,
.modal-input select {
  border: 0.5px solid #A8CDE0;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 13.5px;
  background: #F4FAFD;
  width: 100%;
  outline: none;
  transition: border-color 0.15s;
  color: inherit;
}
.modal-input:focus { border-color: #2A6A8A; }
.modal-input::placeholder { color: #90BAD0; }

.modal-textarea {
  border: 0.5px solid #A8CDE0;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 13.5px;
  background: #F4FAFD;
  width: 100%;
  resize: vertical;
  outline: none;
  transition: border-color 0.15s;
  font-family: inherit;
}
.modal-textarea:focus { border-color: #2A6A8A; }
.modal-textarea::placeholder { color: #90BAD0; }

.modal-footer {
  display: flex; justify-content: flex-end; gap: 12px;
  margin-top: 1.5rem;
}

.btn-cancel {
  background: #EBF4F9;
  color: #3A7090;
  border: 0.5px solid #B8D8EA;
  padding: 10px 22px;
  border-radius: 10px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.15s;
}
.btn-cancel:hover { background: #D6EEF8; }

.btn-save {
  background: #2A6A8A;
  color: #fff;
  border: none;
  padding: 10px 24px;
  border-radius: 10px;
  font-size: 14px; font-weight: 500;
  cursor: pointer;
  display: flex; align-items: center; gap: 6px;
  min-width: 140px; justify-content: center;
  transition: background 0.15s;
}
.btn-save:hover { background: #1E5070; }
.btn-save-disabled { background: #8BBDD4 !important; cursor: not-allowed; }
.btn-save i { font-size: 14px; }
</style>