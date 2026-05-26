import api from '../api/axios'

export const getDoctors = async () => {

  const response = await api.get('/doctors')

  return response.data

}

export const createDoctor = async (data) => {

  const response = await api.post('/doctors', data)

  return response.data

}

export const updateDoctor = async (id, data) => {

  const response = await api.put(`/doctors/${id}`, data)

  return response.data

}

export const deleteDoctorService = async (id) => {

  const response = await api.delete(`/doctors/${id}`)

  return response.data

}