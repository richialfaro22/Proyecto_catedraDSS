import api from '../api/axios'

export const getTreatments = async () => {

  const response = await api.get('/treatments')

  return response.data

}

export const createTreatment = async (data) => {

  const response = await api.post('/treatments', data)

  return response.data

}

export const updateTreatment = async (id, data) => {

  const response = await api.put(`/treatments/${id}`, data)

  return response.data

}

export const deleteTreatment = async (id) => {

  const response = await api.delete(`/treatments/${id}`)

  return response.data

}