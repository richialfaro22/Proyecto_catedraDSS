<script setup>

import { ref, onMounted } from 'vue'

import {
  getTreatments,
  createTreatment,
  updateTreatment,
  deleteTreatment
} from '../../services/treatmentService'

const treatments = ref([])

const showModal = ref(false)

const form = ref({
  id: null,
  appointment_id: '',
  doctor_id: '',
  patient_id: '',
  name: '',
  description: '',
  start_date: '',
  end_date: '',
  instructions: '',
  status: 'active',
})

const loadTreatments = async () => {

  try {

    const response = await getTreatments()

    treatments.value = response.data.data

  } catch (error) {

    console.error(error)

  }

}

const openCreateModal = () => {

  form.value = {
    id: null,
    appointment_id: '',
    doctor_id: '',
    patient_id: '',
    name: '',
    description: '',
    start_date: '',
    end_date: '',
    instructions: '',
    status: 'active',
  }

  showModal.value = true

}

const openEditModal = (treatment) => {

  form.value = {
    id: treatment.id,
    appointment_id: treatment.appointment_id,
    doctor_id: treatment.doctor_id,
    patient_id: treatment.patient_id,
    name: treatment.name,
    description: treatment.description,
    start_date: treatment.start_date,
    end_date: treatment.end_date,
    instructions: treatment.instructions,
    status: treatment.status,
  }

  showModal.value = true

}

const saveTreatment = async () => {

  try {

    if (form.value.id) {

      await updateTreatment(form.value.id, form.value)

    } else {

      await createTreatment(form.value)

    }

    showModal.value = false

    await loadTreatments()

  } catch (error) {

    console.error(error)

    console.log(error.response.data)

  }

}

const removeTreatment = async (id) => {

  const confirmDelete = confirm('¿Eliminar tratamiento?')

  if (!confirmDelete) return

  try {

    await deleteTreatment(id)

    loadTreatments()

  } catch (error) {

    console.error(error)

  }

}

onMounted(() => {

  loadTreatments()

})

</script>

<template>
  <div class="page">

    <!-- Header -->
    <div class="header">
      <div class="header-text">
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="header-icon">
            <i class="ti ti-needle" aria-hidden="true"></i>
          </div>
          <h1>Tratamientos</h1>
        </div>
        <p>Gestión de tratamientos dermatológicos</p>
      </div>

      <button @click="openCreateModal" class="btn-nuevo">
        <i class="ti ti-plus" aria-hidden="true"></i>
        Nuevo Tratamiento
      </button>
    </div>

    <!-- Tabla -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>Doctor</th>
            <th>Tratamiento</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="treatment in treatments" :key="treatment.id">
            <td class="id-cell">{{ treatment.id }}</td>
            <td>
              <span class="avatar">{{ (treatment.patient?.user?.name || 'SP').slice(0,2).toUpperCase() }}</span>
              {{ treatment.patient?.user?.name }}
            </td>
            <td class="muted-cell">{{ treatment.doctor?.user?.name }}</td>
            <td>
              <span class="badge-treatment">{{ treatment.name }}</span>
            </td>
            <td class="date-cell">{{ treatment.start_date }}</td>
            <td class="date-cell">{{ treatment.end_date || '—' }}</td>
            <td>
              <span
                class="badge-status"
                :class="{
                  'badge-active':    treatment.status === 'active',
                  'badge-completed': treatment.status === 'completed',
                  'badge-cancelled': treatment.status === 'cancelled'
                }"
              >
                {{ treatment.status }}
              </span>
            </td>
            <td>
              <div class="actions-cell">
                <button @click="openEditModal(treatment)" class="btn-editar">
                  <i class="ti ti-edit" aria-hidden="true"></i> Editar
                </button>
                <button @click="removeTreatment(treatment.id)" class="btn-eliminar">
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
          <i class="ti ti-activity" aria-hidden="true"></i>
          {{ form.id ? 'Editar Tratamiento' : 'Nuevo Tratamiento' }}
        </h2>

        <div class="modal-grid">
          <input
            v-model.number="form.appointment_id"
            type="number"
            placeholder="ID Cita"
            class="modal-input"
          />
          <input
            v-model.number="form.patient_id"
            type="number"
            placeholder="ID Paciente"
            class="modal-input"
          />
          <input
            v-model.number="form.doctor_id"
            type="number"
            placeholder="ID Doctor"
            class="modal-input"
          />
          <input
            v-model="form.name"
            type="text"
            placeholder="Nombre del tratamiento"
            class="modal-input"
          />
          <input
            v-model="form.start_date"
            type="date"
            class="modal-input"
          />
          <input
            v-model="form.end_date"
            type="date"
            class="modal-input"
          />
          <select v-model="form.status" class="modal-input">
            <option value="active">Activo</option>
            <option value="completed">Completado</option>
            <option value="cancelled">Cancelado</option>
          </select>
        </div>

        <textarea
          v-model="form.description"
          rows="3"
          placeholder="Descripción"
          class="modal-textarea"
        ></textarea>

        <textarea
          v-model="form.instructions"
          rows="3"
          placeholder="Instrucciones"
          class="modal-textarea"
        ></textarea>

        <div class="modal-footer">
          <button @click="showModal = false" class="btn-cancel">Cancelar</button>
          <button @click="saveTreatment" class="btn-save">
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
  color: #2A6A8A;
  letter-spacing: -0.3px;
}
.header-text p {
  font-size: 13px;
  color: #5A90AA;
  margin-top: 6px;
  margin-left: 46px;
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
.muted-cell { color: #777; font-size: 13px; }
.date-cell  { color: #777; font-size: 12px; font-family: monospace; }

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

.badge-treatment {
  display: inline-block;
  background: #EDF6FB;
  color: #1E5A80;
  border: 0.5px solid #B8D8EA;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

/* Badges estado */
.badge-status {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
  text-transform: capitalize;
}
.badge-active    { background: #E8F6EF; color: #2D7A54; border: 0.5px solid #A8DFC0; }
.badge-completed { background: #EDF6FB; color: #1E5A80; border: 0.5px solid #B8D8EA; }
.badge-cancelled { background: #FDF0F2; color: #A0303F; border: 0.5px solid #F0C0C8; }

/* Acciones */
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
  max-width: 640px;
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

.modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.modal-input {
  border: 0.5px solid #A8CDE0;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 13.5px;
  background: #F4FAFD;
  width: 100%;
  outline: none;
  transition: border-color 0.15s;
  color: inherit;
  font-family: inherit;
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
  margin-top: 12px;
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
  transition: background 0.15s;
}
.btn-save:hover { background: #1E5070; }
.btn-save i { font-size: 14px; }
</style>