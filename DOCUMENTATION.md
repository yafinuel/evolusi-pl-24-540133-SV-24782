# mytodoweb - Documentation & Architecture Overview

Dokumentasi ini dibuat untuk menjelaskan struktur, arsitektur, dan alur kerja proyek **mytodoweb** agar mudah dipahami oleh pengembang manusia maupun AI agent lainnya.

---

## 🚀 Proyek: mytodoweb

**mytodoweb** adalah aplikasi To-Do List sederhana dengan arsitektur Laravel berbasis basis data **SQLite** dan desain antarmuka minimalis terang (hitam-putih).

---

## 🗄️ Database Schema & Migration

Database yang digunakan adalah **SQLite** (lokasi file: `database/database.sqlite`).

### Tabel: `tasks`
Migrasi berada di `database/migrations/2026_09_07_000000_create_tasks_table.php`.

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `bigIncrements` | Primary Key, Auto Increment |
| `title` | `string` | Judul tugas (Required) |
| `description` | `text` | Deskripsi atau catatan tugas (Nullable) |
| `is_completed` | `boolean` | Status penyelesaian tugas (Default: `false`) |
| `due_date` | `date` | Tanggal tenggat waktu penyelesaian (Nullable) |
| `created_at` | `timestamp` | Waktu pembuatan record |
| `updated_at` | `timestamp` | Waktu pembaruan record terakhir |

---

## 🧩 Model & Controller

### 1. Model: `App\Models\Task` (`app/Models/Task.php`)
- `$table = 'tasks'`
- Fillable fields: `title`, `description`, `is_completed`, `due_date`
- Attribute casting:
  - `'is_completed' => 'boolean'`
  - `'due_date' => 'date'`

### 2. Controller: `App\Http\Controllers\TaskController` (`app/Http/Controllers/TaskController.php`)
Menangani aksi RESTful API CRUD dan interaksi status data to-do:
- `index()`: Mengembalikan respon JSON berisi daftar to-do yang diurutkan (belum selesai terlebih dahulu, terbaru lebih dulu).
- `store(Request $request)`: Validasi dan menyimpan data to-do baru, mengembalikan HTTP 201 dengan pesan sukses & objek task.
- `show(Task $task)`: Mengembalikan data detail satu to-do (JSON).
- `update(Request $request, Task $task)`: Memperbarui data to-do dan mengembalikan data terbaru (JSON).
- `toggle(Task $task)`: Beralih status `is_completed` langsung dan mengembalikan status terbaru (JSON).
- `destroy(Task $task)`: Menghapus data to-do dari database dan mengembalikan pesan sukses (JSON).

---

## 🌐 Rute URL (Routes)

Didefinisikan di `routes/api.php` (prefix `/api`):

| HTTP Method | URI | Route Name | Action | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/api/tasks` | `api.tasks.index` | `TaskController@index` | Mengambil seluruh daftar to-do (JSON) |
| `POST` | `/api/tasks` | `api.tasks.store` | `TaskController@store` | Menyimpan to-do baru (JSON) |
| `GET` | `/api/tasks/{task}` | `api.tasks.show` | `TaskController@show` | Mengambil detail to-do spesifik (JSON) |
| `PUT` | `/api/tasks/{task}` | `api.tasks.update` | `TaskController@update` | Memperbarui to-do (JSON) |
| `PATCH` | `/api/tasks/{task}/toggle` | `api.tasks.toggle` | `TaskController@toggle` | Toggle status selesai/belum selesai (JSON) |
| `DELETE` | `/api/tasks/{task}` | `api.tasks.destroy` | `TaskController@destroy` | Menghapus to-do (JSON) |

Rute Web di `routes/web.php`:
| HTTP Method | URI | Description |
| :--- | :--- | :--- |
| `GET` | `/` | Redirect ke `/api/tasks` |

---

## 🎨 Frontend: Vue.js (`frontend/`)

Antarmuka frontend menggunakan **Vue 3** berbasis **Vite** yang terletak di direktori `frontend/`:

1. **`frontend/src/App.vue`**:
   - Komponen utama dashboard **mytodoweb**.
   - Skema warna: Terang / Minimalis Hitam-Putih (`#ffffff` background, font dan border tegas `#09090b`).
   - Fitur utama:
     - Form tambah tugas cepat (Judul wajib, deskripsi dan tenggat waktu opsional).
     - Indikator ringkasan statistik (Total tugas, Tugas selesai).
     - Daftar tugas dengan checkbox toggle cepat.
     - Modal dialog edit interaktif tanpa reload halaman.
     - Konfirmasi penghapusan tugas.
     - Notifikasi flash alert responsif.
2. **`frontend/vite.config.js`**:
   - Dilengkapi server reverse proxy dev ke backend Laravel (`http://127.0.0.1:8000/api`).

---

## 🧪 Pengujian (Testing) & CI

Proyek dilengkapi dengan pengujian terotomatisasi (Unit & Feature Tests) serta workflow CI (GitHub Actions).

### 1. Pengujian Terotomatisasi (PHPUnit)
Jalankan pengujian menggunakan command:
```bash
php artisan test
```

Unit & Feature Test Suites:
- **`tests/Feature/TaskTest.php`**:
  - `test_can_display_task_dashboard`: Memastikan dashboard utama (GET `/tasks`) dapat diakses.
  - `test_can_create_a_new_task`: Memastikan pembuatan to-do berhasil disimpan ke database SQLite.
  - `test_requires_title_when_creating_task`: Memastikan validasi judul tidak boleh kosong.
  - `test_can_display_edit_task_page`: Memastikan form halaman edit (GET `/tasks/{task}/edit`) dirender dengan benar.
  - `test_can_update_a_task`: Memastikan update data to-do (PUT `/tasks/{task}`) sukses.
  - `test_can_toggle_task_completion`: Memastikan status penyelesaian (PATCH `/tasks/{task}/toggle`) bisa beralih dari belum selesai ke selesai dan sebaliknya.
  - `test_can_delete_a_task`: Memastikan to-do berhasil dihapus dari database (DELETE `/tasks/{task}`).
- **`tests/Unit/TaskTest.php`**:
  - Memastikan atribut casting model `Task` (`is_completed` ke boolean, `due_date` ke Carbon) dan nilai default (`is_completed => false`).

### 2. Pemeriksaan Gaya Kode (PSR-12 Linting)
```bash
php vendor/bin/phpcs --standard=PSR12 app/
```

---

## 🛠️ Cara Menjalankan Aplikasi

1. **Jalankan Migrasi Database SQLite**:
   ```bash
   php artisan migrate
   ```

2. **Jalankan Pengujian (Testing)**:
   ```bash
   php artisan test
   ```

3. **Jalankan Server Backend Laravel**:
   ```bash
   php artisan serve
   ```
   Server backend berjalan di `http://127.0.0.1:8000` dan menyediakan REST API di `/api/tasks`.

4. **Jalankan Server Frontend Vue.js**:
   Buka terminal baru di folder `frontend`:
   ```bash
   cd frontend
   npm install
   npm run dev
   ```
   Aplikasi frontend dapat diakses melalui peramban di `http://localhost:5173`.

