<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mytodoweb - To-Do List Dashboard</title>
    <!-- Simple Black & White Light Theme Styling for mytodoweb -->
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
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
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

        .alert-success {
            background: #ffffff;
            color: var(--text-main);
            border: 1px solid var(--border-dark);
            padding: 0.75rem 1rem;
            border-radius: 4px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
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

        .btn-outline {
            background: #ffffff;
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-outline:hover {
            border-color: var(--border-dark);
            background: #fafafa;
        }

        .btn-danger {
            background: #ffffff;
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-danger:hover {
            border-color: var(--border-dark);
            background: #f4f4f5;
        }

        .btn-sm {
            padding: 0.25rem 0.625rem;
            font-size: 0.75rem;
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

        .toggle-form {
            margin-top: 0.2rem;
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

        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            border: 1px dashed var(--border-color);
            border-radius: 6px;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1>mytodoweb</h1>
            <p>Kelola daftar tugas harian Anda secara sederhana dan efisien.</p>
        </header>

        <!-- Flash Success Alert -->
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Tambah To-Do -->
        <div class="card">
            <div class="card-title">Tambah To-Do Baru</div>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Judul Tugas *</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Menyelesaikan laporan tugas" required>
                    @error('title')
                        <div style="color: #000; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi (Opsional)</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Tambahkan rincian atau catatan tugas..."></textarea>
                </div>

                <div class="form-group">
                    <label for="due_date">Tenggat Waktu / Due Date (Opsional)</label>
                    <input type="date" id="due_date" name="due_date" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">+ Tambah Task</button>
            </form>
        </div>

        <!-- Daftar To-Do List -->
        <div class="card-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <span>Daftar To-Do</span>
            <span style="font-size: 0.75rem; font-weight: normal; color: var(--text-muted);">
                Total: {{ $tasks->count() }} | Selesai: {{ $tasks->where('is_completed', true)->count() }}
            </span>
        </div>

        @if ($tasks->isEmpty())
            <div class="empty-state">
                Belum ada tugas. Buat tugas baru di atas!
            </div>
        @else
            <ul class="task-list">
                @foreach ($tasks as $task)
                    <li class="task-item {{ $task->is_completed ? 'completed' : '' }}">
                        <div class="task-left">
                            <!-- Toggle Complete Form -->
                            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="toggle-form">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="checkbox-btn {{ $task->is_completed ? 'checked' : '' }}" title="{{ $task->is_completed ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                    @if ($task->is_completed)
                                        ✓
                                    @endif
                                </button>
                            </form>

                            <!-- Content -->
                            <div class="task-content">
                                <div class="task-title">{{ $task->title }}</div>
                                @if ($task->description)
                                    <div class="task-desc">{{ $task->description }}</div>
                                @endif
                                @if ($task->due_date)
                                    <div class="task-meta">
                                        <span class="badge-due">📅 {{ $task->due_date->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="task-actions">
                            <!-- Redirect to edit form page -->
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline btn-sm" title="Edit To-Do">
                                Edit
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus to-do ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus To-Do">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</body>
</html>
