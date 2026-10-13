<script setup>
import { ref, computed, onMounted } from 'vue'
import { formatDate } from '../utils/taskUtils'

// Alamat API diambil dari VITE_API_URL sesuai instruksi soal
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
// States
const tasks = ref([])
const loading = ref(false)
const submitting = ref(false)
const notification = ref({ show: false, message: '', type: 'success' })

// Form Tambah Task
const newTask = ref({
  title: '',
  description: '',
  due_date: '',
})
const newTitleError = ref('')

// Modal Edit Task
const isEditModalOpen = ref(false)
const editingTask = ref({
  id: null,
  title: '',
  description: '',
  due_date: '',
  is_completed: false,
})
const editTitleError = ref('')

// Statistik Tugas
const totalCount = computed(() => tasks.value.length)
const completedCount = computed(() => tasks.value.filter((t) => t.is_completed).length)

// Flash Notification Helper
const notify = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 4000)
}
// Ambil data To-Do dari endpoint Laravel
const fetchTasks = async () => {
  loading.value = true
  try {
    const res = await fetch(`${API_URL}/tasks`)
    if (!res.ok) throw new Error('Gagal memuat daftar to-do dari server')
    const data = await res.json()
    tasks.value = data
  } catch (err) {
    notify(err.message, 'error')
  } finally {
    loading.value = false
  }
}

// Tambah To-Do baru ke Laravel
const handleCreateTask = async () => {
  newTitleError.value = ''
  if (!newTask.value.title.trim()) {
    newTitleError.value = 'Judul tugas wajib diisi.'
    return
  }

  submitting.value = true
  try {
    const res = await fetch(`${API_URL}/tasks`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        title: newTask.value.title.trim(),
        description: newTask.value.description ? newTask.value.description.trim() : null,
        due_date: newTask.value.due_date || null,
      }),
    })

    const data = await res.json()
    if (!res.ok) {
      if (data.errors && data.errors.title) {
        newTitleError.value = data.errors.title[0]
      }
      throw new Error(data.message || 'Gagal menambahkan to-do')
    }

    notify(data.message || 'To-Do berhasil ditambahkan!')
    newTask.value = { title: '', description: '', due_date: '' }
    await fetchTasks()
  } catch (err) {
    notify(err.message, 'error')
  } finally {
    submitting.value = false
  }
}

// Toggle status selesai
const handleToggleTask = async (task) => {
  try {
    const res = await fetch(`${API_URL}/tasks/${task.id}/toggle`, {
      method: 'PATCH',
      headers: {
        Accept: 'application/json',
      },
    })
    const data = await res.json()
    if (!res.ok) throw new Error(data.message || 'Gagal mengubah status to-do')

    notify(data.message || 'Status To-Do berhasil diperbarui!')
    task.is_completed = !task.is_completed
    await fetchTasks()
  } catch (err) {
    notify(err.message, 'error')
  }
}

// Buka Modal Edit
const openEditModal = (task) => {
  editTitleError.value = ''
  let formattedDueDate = ''
  if (task.due_date) {
    formattedDueDate = task.due_date.substring(0, 10)
  }

  editingTask.value = {
    id: task.id,
    title: task.title,
    description: task.description || '',
    due_date: formattedDueDate,
    is_completed: Boolean(task.is_completed),
  }
  isEditModalOpen.value = true
}

// Tutup Modal Edit
const closeEditModal = () => {
  isEditModalOpen.value = false
  editingTask.value = {
    id: null,
    title: '',
    description: '',
    due_date: '',
    is_completed: false,
  }
  editTitleError.value = ''
}

// Simpan Perubahan Edit Task
const handleUpdateTask = async () => {
  editTitleError.value = ''
  if (!editingTask.value.title.trim()) {
    editTitleError.value = 'Judul tugas wajib diisi.'
    return
  }

  submitting.value = true
  try {
    const res = await fetch(`${API_URL}/tasks/${editingTask.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        title: editingTask.value.title.trim(),
        description: editingTask.value.description ? editingTask.value.description.trim() : null,
        due_date: editingTask.value.due_date || null,
        is_completed: editingTask.value.is_completed,
      }),
    })

    const data = await res.json()
    if (!res.ok) {
      if (data.errors && data.errors.title) {
        editTitleError.value = data.errors.title[0]
      }
      throw new Error(data.message || 'Gagal memperbarui to-do')
    }

    notify(data.message || 'To-Do berhasil diperbarui!')
    closeEditModal()
    await fetchTasks()
  } catch (err) {
    notify(err.message, 'error')
  } finally {
    submitting.value = false
  }
}

// Hapus Task
const handleDeleteTask = async (task) => {
  const confirmed = window.confirm(`Apakah Anda yakin ingin menghapus to-do "${task.title}"?`)
  if (!confirmed) return

  try {
    const res = await fetch(`${API_URL}/tasks/${task.id}`, {
      method: 'DELETE',
      headers: {
        Accept: 'application/json',
      },
    })
    const data = await res.json()
    if (!res.ok) throw new Error(data.message || 'Gagal menghapus to-do')

    notify(data.message || 'To-Do berhasil dihapus!')
    await fetchTasks()
  } catch (err) {
    notify(err.message, 'error')
  }
}

onMounted(() => {
  fetchTasks()
})
</script>

<template>
  <div>
    <!-- Alert Notifikasi -->
    <transition name="fade">
      <div
        v-if="notification.show"
        :class="['alert', notification.type === 'error' ? 'alert-error' : 'alert-success']"
      >
        <span>{{ notification.message }}</span>
        <button type="button" class="alert-close" @click="notification.show = false">&times;</button>
      </div>
    </transition>

    <!-- Form Tambah To-Do -->
    <div class="card">
      <div class="card-title">Tambah To-Do Baru</div>
      <form @submit.prevent="handleCreateTask">
        <div class="form-group">
          <label for="new_title">Judul Tugas *</label>
          <input
            id="new_title"
            v-model="newTask.title"
            type="text"
            class="form-control"
            placeholder="Contoh: Menyelesaikan laporan tugas"
            required
          />
          <div v-if="newTitleError" class="field-error">{{ newTitleError }}</div>
        </div>

        <div class="form-group">
          <label for="new_description">Deskripsi (Opsional)</label>
          <textarea
            id="new_description"
            v-model="newTask.description"
            class="form-control"
            placeholder="Tambahkan rincian atau catatan tugas..."
          ></textarea>
        </div>

        <div class="form-group">
          <label for="new_due_date">Tenggat Waktu / Due Date (Opsional)</label>
          <input
            id="new_due_date"
            v-model="newTask.due_date"
            type="date"
            class="form-control"
          />
        </div>

        <button
          type="submit"
          class="btn btn-primary btn-block"
          :disabled="submitting"
        >
          {{ submitting ? 'Menyimpan...' : '+ Tambah Task' }}
        </button>
      </form>
    </div>

    <!-- Section Daftar To-Do -->
    <div class="section-header">
      <span class="section-title">Daftar To-Do</span>
      <span class="stats-text">
        Total: {{ totalCount }} | Selesai: {{ completedCount }}
      </span>
    </div>

    <!-- Indikator Loading -->
    <div v-if="loading && tasks.length === 0" class="loading-state">
      Memuat data to-do dari Laravel...
    </div>

    <!-- Empty State -->
    <div v-else-if="tasks.length === 0" class="empty-state">
      Belum ada tugas. Buat tugas baru di atas!
    </div>

    <!-- Daftar List Tasks -->
    <ul v-else class="task-list">
      <li
        v-for="task in tasks"
        :key="task.id"
        :class="['task-item', { completed: task.is_completed }]"
      >
        <div class="task-left">
          <button
            type="button"
            :class="['checkbox-btn', { checked: task.is_completed }]"
            :title="task.is_completed ? 'Tandai belum selesai' : 'Tandai selesai'"
            @click="handleToggleTask(task)"
          >
            <span v-if="task.is_completed">✓</span>
          </button>

          <div class="task-content">
            <div class="task-title">{{ task.title }}</div>
            <div v-if="task.description" class="task-desc">{{ task.description }}</div>
            <div v-if="task.due_date" class="task-meta">
              <span class="badge-due">📅 {{ formatDate(task.due_date) }}</span>
            </div>
          </div>
        </div>

        <div class="task-actions">
          <button
            type="button"
            class="btn btn-outline btn-sm"
            title="Edit To-Do"
            @click="openEditModal(task)"
          >
            Edit
          </button>
          <button
            type="button"
            class="btn btn-danger btn-sm"
            title="Hapus To-Do"
            @click="handleDeleteTask(task)"
          >
            Hapus
          </button>
        </div>
      </li>
    </ul>

    <!-- Modal Dialog Edit To-Do -->
    <div v-if="isEditModalOpen" class="modal-backdrop" @click.self="closeEditModal">
      <div class="modal-card">
        <div class="card-title">Form Edit To-Do</div>
        <form @submit.prevent="handleUpdateTask">
          <div class="form-group">
            <label for="edit_title">Judul Tugas *</label>
            <input
              id="edit_title"
              v-model="editingTask.title"
              type="text"
              class="form-control"
              required
            />
            <div v-if="editTitleError" class="field-error">{{ editTitleError }}</div>
          </div>

          <div class="form-group">
            <label for="edit_description">Deskripsi</label>
            <textarea
              id="edit_description"
              v-model="editingTask.description"
              class="form-control"
            ></textarea>
          </div>

          <div class="form-group">
            <label for="edit_due_date">Tenggat Waktu / Due Date</label>
            <input
              id="edit_due_date"
              v-model="editingTask.due_date"
              type="date"
              class="form-control"
            />
          </div>

          <div class="form-group checkbox-group">
            <input
              id="edit_is_completed"
              v-model="editingTask.is_completed"
              type="checkbox"
            />
            <label for="edit_is_completed">Tandai sebagai Selesai</label>
          </div>

          <div class="modal-actions">
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="submitting"
            >
              {{ submitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
            <button
              type="button"
              class="btn btn-outline"
              @click="closeEditModal"
            >
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.alert {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid var(--border-dark);
  padding: 0.75rem 1rem;
  border-radius: 4px;
  font-size: 0.875rem;
  margin-bottom: 1.5rem;
  font-weight: 500;
}

.alert-success {
  background: #ffffff;
  color: var(--text-main);
}

.alert-error {
  background: #fff1f2;
  border-color: #e11d48;
  color: #9f1239;
}

.alert-close {
  background: transparent;
  border: none;
  font-size: 1.25rem;
  line-height: 1;
  cursor: pointer;
  color: inherit;
  margin-left: 0.5rem;
}

.card {
  background: #ffffff;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 1.25rem;
  margin-bottom: 2rem;
}

.card-title {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-size: 0.8125rem;
  font-weight: 600;
  margin-bottom: 0.375rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.form-control {
  width: 100%;
  padding: 0.625rem 0.75rem;
  font-size: 0.875rem;
  border: 1px solid var(--border-color);
  border-radius: 4px;
  background: #ffffff;
  color: var(--text-main);
  outline: none;
  transition: border-color 0.15s ease;
}

.form-control:focus {
  border-color: var(--border-dark);
}

textarea.form-control {
  resize: vertical;
  min-height: 80px;
}

.field-error {
  color: #e11d48;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.checkbox-group input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: var(--border-dark);
}

.checkbox-group label {
  text-transform: none;
  font-weight: 500;
  margin-bottom: 0;
  cursor: pointer;
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

.btn-block {
  width: 100%;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background: var(--accent-bg);
  color: var(--accent-text);
}

.btn-primary:hover:not(:disabled) {
  background: #27272a;
}

.btn-outline {
  background: #ffffff;
  color: var(--text-main);
  border-color: var(--border-color);
}

.btn-outline:hover:not(:disabled) {
  border-color: var(--border-dark);
  background: #fafafa;
}

.btn-danger {
  background: #ffffff;
  color: var(--text-main);
  border-color: var(--border-color);
}

.btn-danger:hover:not(:disabled) {
  border-color: var(--border-dark);
  background: #f4f4f5;
}

.btn-sm {
  padding: 0.25rem 0.625rem;
  font-size: 0.75rem;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
}

.stats-text {
  font-size: 0.75rem;
  color: var(--text-muted);
}

.loading-state,
.empty-state {
  text-align: center;
  padding: 2.5rem 1rem;
  border: 1px dashed var(--border-color);
  border-radius: 6px;
  color: var(--text-muted);
  font-size: 0.875rem;
}

.task-list {
  list-style: none;
}

.task-item {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 1rem;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  margin-bottom: 0.75rem;
  background: #ffffff;
  gap: 1rem;
  transition: background-color 0.15s ease;
}

.task-item.completed {
  background: var(--completed-bg);
  border-color: #f4f4f5;
}

.task-item.completed .task-title {
  text-decoration: line-through;
  color: var(--text-muted);
}

.task-item.completed .task-desc {
  color: #a1a1aa;
}

.task-left {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  flex: 1;
}

.checkbox-btn {
  background: none;
  border: 1px solid var(--border-dark);
  width: 20px;
  height: 20px;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  margin-top: 0.2rem;
  font-size: 0.75rem;
}

.checkbox-btn.checked {
  background: var(--accent-bg);
  color: var(--accent-text);
}

.task-content {
  flex: 1;
}

.task-title {
  font-size: 0.9375rem;
  font-weight: 600;
  color: var(--text-main);
  word-break: break-word;
}

.task-desc {
  font-size: 0.8125rem;
  color: var(--text-muted);
  margin-top: 0.25rem;
  white-space: pre-line;
  word-break: break-word;
}

.task-meta {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
  font-size: 0.75rem;
  color: var(--text-muted);
}

.badge-due {
  border: 1px solid var(--border-color);
  padding: 0.125rem 0.375rem;
  border-radius: 3px;
  font-weight: 500;
}

.task-actions {
  display: flex;
  align-items: center;
  gap: 0.375rem;
}

/* Modal */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background-color: rgba(9, 9, 11, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  z-index: 1000;
}

.modal-card {
  background: #ffffff;
  border: 1px solid var(--border-dark);
  border-radius: 6px;
  padding: 1.5rem;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-top: 1.5rem;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
