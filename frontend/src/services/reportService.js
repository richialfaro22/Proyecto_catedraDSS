import api from '../api/axios'

export const getDashboardReport = async () => {

  const response = await api.get('/reports/dashboard')

  return response.data

}

export const getAppointmentsByDoctor = async () => {

  const response = await api.get('/reports/appointments-by-doctor')

  return response.data

}

export const getFrequentDiagnoses = async () => {

  const response = await api.get('/reports/frequent-diagnoses')

  return response.data

}