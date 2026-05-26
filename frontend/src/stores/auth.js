import { defineStore } from 'pinia'
import api from '../api/axios'

export const useAuthStore = defineStore('auth', {

  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  actions: {

    async login(credentials) {

      try {

        const response = await api.post('/auth/login', credentials)

        this.token = response.data.data.token
        this.user = response.data.data.user

        localStorage.setItem('token', response.data.data.token)

        return response.data

      } catch (error) {
        throw error
      }

    },

    logout() {

      this.user = null
      this.token = null

      localStorage.removeItem('token')

    },

  },

})