<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="page-wrapper">
    <div class="container">
      <!-- Header & Navbar -->
      <header>
        <div class="header-top">
          <div>
            <h1>mytodoweb</h1>
            <p>Kelola daftar tugas harian Anda secara sederhana dan efisien.</p>
          </div>
          <!-- Navigasi Router -->
          <nav class="nav-links">
            <RouterLink to="/" class="nav-link" active-class="active">
              Daftar Tugas
            </RouterLink>
            <RouterLink to="/about" class="nav-link" active-class="active">
              Tentang
            </RouterLink>
          </nav>
        </div>
      </header>

      <!-- Router View Halaman -->
      <main>
        <RouterView />
      </main>
    </div>
  </div>
</template>

<style>
:root {
  --bg-color: #ffffff;
  --text-main: #09090b;
  --text-muted: #71717a;
  --border-color: #e4e4e7;
  --border-dark: #09090b;
  --accent-bg: #09090b;
  --accent-text: #ffffff;
  --completed-bg: #fafafa;
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

body {
  background-color: var(--bg-color);
  color: var(--text-main);
  line-height: 1.5;
}
</style>

<style scoped>
.page-wrapper {
  display: flex;
  justify-content: center;
  padding: 2rem 1rem;
  min-height: 100vh;
}

.container {
  width: 100%;
  max-width: 640px;
}

header {
  margin-bottom: 2rem;
  border-bottom: 2px solid var(--border-dark);
  padding-bottom: 1rem;
}

.header-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  flex-wrap: wrap;
  gap: 1rem;
}

header h1 {
  font-size: 1.75rem;
  font-weight: 800;
  letter-spacing: -0.025em;
  text-transform: lowercase;
}

header p {
  color: var(--text-muted);
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.nav-links {
  display: flex;
  gap: 0.5rem;
}

.nav-link {
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text-muted);
  padding: 0.375rem 0.75rem;
  border-radius: 4px;
  border: 1px solid transparent;
  transition: all 0.15s ease;
}

.nav-link:hover {
  color: var(--text-main);
  border-color: var(--border-color);
}

.nav-link.active {
  color: var(--accent-text);
  background-color: var(--accent-bg);
  border-color: var(--border-dark);
}
</style>
