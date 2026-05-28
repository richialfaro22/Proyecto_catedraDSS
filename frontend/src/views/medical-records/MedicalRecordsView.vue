<script setup>

import { ref, onMounted } from 'vue'

import {
  getMedicalRecords,
  createMedicalRecord,
  updateMedicalRecord,
  deleteMedicalRecord
} from '../../services/medicalRecordService'

const records = ref([])

const showModal = ref(false)

const form = ref({
  id: null,
  patient_id: '',
  doctor_id: '',
  appointment_id: '',
  diagnosis: '',
  symptoms: '',
  observations: '',
  skin_condition: '',
})

const loadRecords = async () => {

  try {

    const response = await getMedicalRecords()

    console.log(response)

    records.value = response.data.data

  } catch (error) {

    console.error(error)

  }

}

const openCreateModal = () => {

  form.value = {
    id: null,
    patient_id: '',
    doctor_id: '',
    appointment_id: '',
    diagnosis: '',
    symptoms: '',
    observations: '',
    skin_condition: '',
  }

  showModal.value = true

}

const openEditModal = (record) => {

  form.value = {
    id: record.id,
    patient_id: record.patient_id,
    doctor_id: record.doctor_id,
    appointment_id: record.appointment_id,
    diagnosis: record.diagnosis,
    symptoms: record.symptoms,
    observations: record.observations,
    skin_condition: record.skin_condition,
  }

  showModal.value = true

}

const saveRecord = async () => {

  try {

    if (form.value.id) {

      await updateMedicalRecord(
        form.value.id,
        form.value
      )

    } else {

      await createMedicalRecord(form.value)

    }

    showModal.value = false

    await loadRecords()

  } catch (error) {

    console.error(error)

    console.log(error.response.data)

  }

}

const removeRecord = async (id) => {

  const confirmDelete = confirm(
    '¿Eliminar historial clínico?'
  )

  if (!confirmDelete) return

  try {

    await deleteMedicalRecord(id)

    loadRecords()

  } catch (error) {

    console.error(error)

  }

}

onMounted(() => {

  loadRecords()

})

</script>

<template>
  <div class="page">

    <!-- Header -->
    <div class="header">
      <div class="header-text">
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="header-icon">
            <i class="ti ti-clipboard-text" aria-hidden="true"></i>
          </div>
          <h1>Historial Clínico</h1>
        </div>
        <p>Gestión de expedientes médicos</p>
      </div>

      <button @click="openCreateModal" class="btn-nuevo">
        <i class="ti ti-plus" aria-hidden="true"></i>
        Nuevo Registro
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
            <th>Diagnóstico</th>
            <th>Condición</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in records" :key="record.id">
            <td class="id-cell">{{ record.id }}</td>
            <td>
              <span class="avatar">{{ (record.patient?.user?.name || 'SP').slice(0,2).toUpperCase() }}</span>
              {{ record.patient?.user?.name }}
            </td>
            <td class="muted-cell">{{ record.doctor?.user?.name }}</td>
            <td class="muted-cell">{{ record.diagnosis }}</td>
            <td>
              <span class="badge-condition">{{ record.skin_condition }}</span>
            </td>
            <td>
              <div class="actions-cell">
                <button @click="openEditModal(record)" class="btn-editar">
                  <i class="ti ti-edit" aria-hidden="true"></i> Editar
                </button>
                <button @click="removeRecord(record.id)" class="btn-eliminar">
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
          <i class="ti ti-file-plus" aria-hidden="true"></i>
          {{ form.id ? 'Editar Registro' : 'Nuevo Registro' }}
        </h2>

        <div class="modal-grid">
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
            v-model.number="form.appointment_id"
            type="number"
            placeholder="ID Cita"
            class="modal-input"
          />
          <input
            v-model="form.skin_condition"
            type="text"
            placeholder="Condición Cutánea"
            class="modal-input"
          />
        </div>

        <textarea v-model="form.diagnosis"    rows="3" placeholder="Diagnóstico"   class="modal-textarea"></textarea>
        <textarea v-model="form.symptoms"     rows="3" placeholder="Síntomas"      class="modal-textarea"></textarea>
        <textarea v-model="form.observations" rows="3" placeholder="Observaciones" class="modal-textarea"></textarea>

        <div class="modal-footer">
          <button @click="showModal = false" class="btn-cancel">Cancelar</button>
          <button @click="saveRecord" class="btn-save">
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

.badge-condition {
  display: inline-block;
  background: #E8F4FB;
  color: #1E5A80;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

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