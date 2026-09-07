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
Menangani aksi CRUD dan interaksi pengguna:
- `index()`: Menampilkan dashboard utama (`task.index`) berisi seluruh daftar to-do.
- `store(Request $request)`: Validasi dan menyimpan data to-do baru.
- `toggle(Task $task)`: Beralih status `is_completed` langsung dari dashboard tanpa berpindah halaman.
- `edit(Task $task)`: Menampilkan halaman form pengisian edit to-do (`task.edit`).
- `update(Request $request, Task $task)`: Memperbarui data to-do lalu mengarahkan kembali ke dashboard (`tasks.index`).
- `destroy(Task $task)`: Menghapus data to-do dari database.

---

## 🌐 Rute URL (Routes)

Didefinisikan di `routes/web.php`:

| HTTP Method | URI | Route Name | Action | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | - | Closure | Redirect ke `/tasks` |
| `GET` | `/tasks` | `tasks.index` | `TaskController@index` | Dashboard To-Do List |
| `POST` | `/tasks` | `tasks.store` | `TaskController@store` | Simpan To-Do baru |
| `GET` | `/tasks/{task}/edit` | `tasks.edit` | `TaskController@edit` | Halaman form edit To-Do |
| `PUT` | `/tasks/{task}` | `tasks.update` | `TaskController@update` | Simpan perubahan edit To-Do |
| `PATCH` | `/tasks/{task}/toggle` | `tasks.toggle` | `TaskController@toggle` | Toggle status selesai/belum selesai |
| `DELETE` | `/tasks/{task}` | `tasks.destroy` | `TaskController@destroy` | Hapus item To-Do |

---

## 🎨 Layout & Tampilan Views

1. **`resources/views/task/index.blade.php`**:
   - Tampilan Dashboard Utama **mytodoweb**.
   - Skema warna: Terang / Minimalis Hitam-Putih (`#ffffff` primary background, font dan border tegas `#09090b`).
   - Fitur utama: Form tambah tugas cepat, daftar tugas, badge tanggal, tombol toggle cepat, tombol edit (redirect ke form edit), dan tombol hapus.

2. **`resources/views/task/edit.blade.php`**:
   - Tampilan Halaman Edit Form To-Do.
   - Menggunakan tema hitam-putih yang konsisten.
   - Berisi input judul, deskripsi, due date, dan checkbox status selesai.

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

3. **Jalankan Server Lokal Laravel**:
   ```bash
   php artisan serve
   ```

4. Buka peramban di `http://127.0.0.1:8000/` untuk mengakses **mytodoweb**.

