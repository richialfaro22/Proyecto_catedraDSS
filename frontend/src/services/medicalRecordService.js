import api from '../api/axios'

export const getMedicalRecords = async () => {

  const response = await api.get('/medical-records')

  return response.data

}

export const createMedicalRecord = async (data) => {

  const response = await api.post(
    '/medical-records',
    data
  )

  return response.data

}

export const updateMedicalRecord = async (id, data) => {

  const response = await api.put(
    `/medical-records/${id}`,
    data
  )

  return response.data

}

export const deleteMedicalRecord = async (id) => {

  const response = await api.delete(
    `/medical-records/${id}`
  )

  return response.data

}