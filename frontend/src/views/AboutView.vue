<script setup>
const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
</script>

<template>
  <div class="about-container">
    <div class="card">
      <div class="card-title">Tentang mytodoweb</div>
      <p class="desc">
        <strong>mytodoweb</strong> adalah aplikasi manajemen tugas harian modern yang dibangun dengan arsitektur decoupled (pemisahan frontend dan backend):
      </p>

      <div class="info-group">
        <h3>Arsitektur Sistem</h3>
        <ul>
          <li><strong>Frontend:</strong> Vue 3 (Composition API) + Vite + Vue Router</li>
          <li><strong>Backend:</strong> Laravel RESTful API</li>
          <li><strong>Database:</strong> SQLite</li>
          <li><strong>Konfigurasi API:</strong> Terhubung melalui <code>VITE_API_URL</code> (Nilai saat ini: <code>{{ apiUrl }}</code>)</li>
        </ul>
      </div>

      <div class="info-group">
        <h3>Fitur Utama</h3>
        <ul>
          <li>Melihat daftar tugas dari server Laravel secara asinkron.</li>
          <li>Menambah tugas baru dengan judul, rincian deskripsi, dan tenggat waktu.</li>
          <li>Menandai status selesai tugas secara instan.</li>
          <li>Mengedit rincian tugas melalui modal interaktif tanpa reload.</li>
          <li>Menghapus tugas dengan perlindungan konfirmasi.</li>
        </ul>
      </div>

      <div class="actions">
        <router-link to="/" class="btn btn-primary">
          &larr; Kembali ke Daftar Tugas
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.about-container {
  margin-top: 1rem;
}

.card {
  background: #ffffff;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 1.5rem;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 1rem;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 0.75rem;
}

.desc {
  font-size: 0.9375rem;
  color: var(--text-main);
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.info-group {
  margin-bottom: 1.5rem;
}

.info-group h3 {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--text-main);
}

.info-group ul {
  list-style: disc;
  padding-left: 1.25rem;
  color: var(--text-muted);
  font-size: 0.875rem;
  line-height: 1.7;
}

.info-group code {
  background: #f4f4f5;
  padding: 0.125rem 0.375rem;
  border-radius: 4px;
  font-size: 0.8125rem;
  color: #09090b;
}

.actions {
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid var(--border-color);
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.625rem 1.25rem;
  font-size: 0.875rem;
  font-weight: 600;
  border-radius: 4px;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.15s ease;
  border: 1px solid var(--border-dark);
}

.btn-primary {
  background: var(--accent-bg);
  color: var(--accent-text);
}

.btn-primary:hover {
  background: #27272a;
}
</style>
