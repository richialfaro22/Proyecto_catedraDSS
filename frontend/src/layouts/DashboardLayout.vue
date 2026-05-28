<script setup>

import { ref } from 'vue'

import {
  RouterLink,
  RouterView,
  useRoute,
  useRouter
} from 'vue-router'

const route = useRoute()

const router = useRouter()

const sidebarOpen = ref(false)

const logout = () => {

  localStorage.removeItem('token')

  router.push('/login')

}

const isActive = (path) => {

  return route.path === path

}

</script>

<template>
  <div class="layout">

    <!-- Overlay Mobile -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="mobile-overlay"
    ></div>

    <!-- Sidebar -->
    <aside
      class="sidebar"
      :class="sidebarOpen ? 'sidebar-open' : 'sidebar-closed'"
    >

      <!-- Logo -->
      <div class="sidebar-logo">
        <div class="logo-icon">
          <i class="ti ti-heart-rate-monitor" aria-hidden="true"></i>
        </div>
        <div>
          <h1 class="logo-title">Clinica Ciruderma</h1>
          <p class="logo-sub">Panel Administrativo</p>
        </div>
      </div>

      <!-- Menú -->
      <nav class="sidebar-nav">

        <RouterLink
          to="/dashboard"
          class="nav-link"
          :class="isActive('/dashboard') ? 'nav-link-active' : ''"
        >
          <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
          Dashboard
        </RouterLink>

        <RouterLink
          to="/patients"
          class="nav-link"
          :class="isActive('/patients') ? 'nav-link-active' : ''"
        >
          <i class="ti ti-users" aria-hidden="true"></i>
          Pacientes
        </RouterLink>

        <RouterLink
          to="/doctors"
          class="nav-link"
          :class="isActive('/doctors') ? 'nav-link-active' : ''"
        >
          <i class="ti ti-stethoscope" aria-hidden="true"></i>
          Doctores
        </RouterLink>

        <RouterLink
          to="/appointments"
          class="nav-link"
          :class="isActive('/appointments') ? 'nav-link-active' : ''"
        >
          <i class="ti ti-calendar" aria-hidden="true"></i>
          Citas
        </RouterLink>

        <RouterLink
          to="/medical-records"
          class="nav-link"
          :class="isActive('/medical-records') ? 'nav-link-active' : ''"
        >
          <i class="ti ti-clipboard-text" aria-hidden="true"></i>
          Historial Clínico
        </RouterLink>

        <RouterLink to="/prescriptions" class="nav-link">
          <i class="ti ti-prescription" aria-hidden="true"></i>
          Recetas
        </RouterLink>

        <RouterLink to="/treatments" class="nav-link">
          <i class="ti ti-needle" aria-hidden="true"></i>
          Tratamientos
        </RouterLink>

      </nav>

      <!-- Footer Sidebar -->
      <div class="sidebar-footer">
        <button @click="logout" class="btn-logout">
          <i class="ti ti-logout" aria-hidden="true"></i>
          Cerrar Sesión
        </button>
      </div>

    </aside>

    <!-- Main -->
    <main class="main">

      <!-- Navbar -->
      <header class="navbar">

        <div class="navbar-left">
          <button @click="sidebarOpen = true" class="btn-mobile-menu">
            <i class="ti ti-menu-2" aria-hidden="true"></i>
          </button>
          <div>
            <h2 class="navbar-title">Panel Administrativo</h2>
            <p class="navbar-sub">Buen día, bienvenido de nuevo!</p>
          </div>
        </div>

        <div class="navbar-right">
          <div class="navbar-user-info">
            <p class="user-name">Administrador</p>
            <p class="user-email">admin@clinica.com</p>
          </div>
          <div class="user-avatar">A</div>
        </div>

      </header>

      <!-- Contenido -->
      <div class="content">
        <RouterView />
      </div>

    </main>

  </div>
</template>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

/* Layout */
.layout {
  display: flex;
  height: 100vh;
  background: #EDF6FB;
  overflow: hidden;
  font-family: inherit;
}

/* Overlay mobile */
.mobile-overlay {
  position: fixed;
  inset: 0;
  background: rgba(20, 50, 70, 0.45);
  z-index: 40;
}

@media (min-width: 1024px) {
  .mobile-overlay { display: none; }
}

/* Sidebar */
.sidebar {
  position: fixed;
  top: 0; left: 0;
  height: 100%;
  width: 240px;
  background: #0F3447;
  display: flex;
  flex-direction: column;
  z-index: 50;
  transition: transform 0.3s ease;
}

@media (min-width: 1024px) {
  .sidebar {
    position: static;
    transform: translateX(0) !important;
  }
}

.sidebar-open  { transform: translateX(0); }
.sidebar-closed { transform: translateX(-100%); }

/* Logo */
.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 1.5rem 1.25rem;
  border-bottom: 0.5px solid rgba(255,255,255,0.08);
}

.logo-icon {
  width: 38px; height: 38px;
  border-radius: 10px;
  background: rgba(122, 188, 216, 0.2);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.logo-icon i { font-size: 20px; color: #7ABCD8; }

.logo-title {
  font-size: 14px;
  font-weight: 500;
  color: #E8F4FB;
  line-height: 1.3;
}
.logo-sub {
  font-size: 11px;
  color: #5A90AA;
  margin-top: 2px;
}

/* Nav */
.sidebar-nav {
  flex: 1;
  padding: 1rem 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 13.5px;
  color: #8BBDD4;
  text-decoration: none;
  transition: background 0.15s, color 0.15s;
}
.nav-link i { font-size: 17px; flex-shrink: 0; }
.nav-link:hover {
  background: rgba(122, 188, 216, 0.1);
  color: #D6EEF8;
}
.nav-link-active {
  background: #2A6A8A;
  color: #ffffff !important;
}
.nav-link-active i { color: #fff; }

/* Sidebar footer */
.sidebar-footer {
  padding: 1rem 0.75rem;
  border-top: 0.5px solid rgba(255,255,255,0.08);
}

.btn-logout {
  width: 100%;
  background: rgba(160, 48, 63, 0.15);
  color: #F0A0A8;
  border: 0.5px solid rgba(160, 48, 63, 0.3);
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 13.5px;
  cursor: pointer;
  display: flex; align-items: center; gap: 8px;
  transition: background 0.15s;
}
.btn-logout:hover {
  background: rgba(160, 48, 63, 0.28);
  color: #F8C0C8;
}
.btn-logout i { font-size: 17px; }

/* Main */
.main {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* Navbar */
.navbar {
  background: #ffffff;
  border-bottom: 0.5px solid #B8D8EA;
  padding: 0.9rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
}

.navbar-left { display: flex; align-items: center; gap: 14px; }

.btn-mobile-menu {
  background: #D6EEF8;
  border: none;
  width: 36px; height: 36px;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: background 0.15s;
}
.btn-mobile-menu:hover { background: #B8D8EA; }
.btn-mobile-menu i { font-size: 18px; color: #2A6A8A; }

@media (min-width: 1024px) {
  .btn-mobile-menu { display: none; }
}

.navbar-title {
  font-size: 17px;
  font-weight: 500;
  color: #1A4A62;
}
.navbar-sub {
  font-size: 12px;
  color: #7ABCD8;
  margin-top: 2px;
}

.navbar-right { display: flex; align-items: center; gap: 12px; }

.navbar-user-info { text-align: right; }
.user-name  { font-size: 13.5px; font-weight: 500; color: #1A4A62; }
.user-email { font-size: 12px; color: #7ABCD8; margin-top: 1px; }

@media (max-width: 640px) {
  .navbar-user-info { display: none; }
}

.user-avatar {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: #2A6A8A;
  color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px;
  font-weight: 500;
}

/* Content */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}
</style>