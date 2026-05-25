<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
})

const errorMessage = ref('')

const handleLogin = async () => {
  errorMessage.value = ''

  try {
    await authStore.login(form.value)

    router.push('/dashboard')
  } catch (error) {
    errorMessage.value = 'Credenciales incorrectas'
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

      <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">
        Clínica Dermatológica
      </h1>

      <form @submit.prevent="handleLogin" class="space-y-4">

        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700">
            Email
          </label>

          <input
            v-model="form.email"
            type="email"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="correo@ejemplo.com"
          />
        </div>

        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700">
            Contraseña
          </label>

          <input
            v-model="form.password"
            type="password"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="********"
          />
        </div>

        <div
          v-if="errorMessage"
          class="bg-red-100 text-red-700 p-3 rounded-lg text-sm"
        >
          {{ errorMessage }}
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition"
        >
          Iniciar Sesión
        </button>

      </form>

    </div>

  </div>
</template>