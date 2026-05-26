<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>

  <div class="flex h-screen bg-gray-100">

    <!-- Sidebar -->

    <aside class="w-64 bg-gray-900 text-white flex flex-col">

      <div class="p-6 border-b border-gray-800">

        <h1 class="text-2xl font-bold">
          DermaSys
        </h1>

        <p class="text-sm text-gray-400 mt-1">
          Sistema Dermatológico
        </p>

      </div>

      <nav class="flex-1 p-4 space-y-2">

        <RouterLink
          to="/dashboard"
          class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition"
        >
          Dashboard
        </RouterLink>

        <RouterLink
          to="/patients"
          class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition"
        >
          Pacientes
        </RouterLink>

        <RouterLink
          to="/appointments"
          class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition"
        >
          Citas
        </RouterLink>

        <RouterLink
          to="/doctors"
          class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition"
        >
          Doctores
        </RouterLink>

      </nav>

    </aside>

    <!-- Contenido -->

    <main class="flex-1 overflow-y-auto">

      <!-- Navbar -->

      <header class="bg-white shadow px-8 py-4 flex justify-between items-center">

        <h2 class="text-2xl font-semibold text-gray-800">
          Panel Administrativo
        </h2>

        <div class="flex items-center gap-4">

          <div class="text-right">
            <p class="font-semibold">
              Administrador
            </p>
            <p class="text-sm text-gray-500">
              admin@clinica.com
            </p>
          </div>

          <div class="w-10 h-10 rounded-full bg-blue-600"></div>

        </div>

      </header>

      <!-- Vista -->

      <div class="p-8">

        <RouterView />

      </div>

    </main>

  </div>

</template>