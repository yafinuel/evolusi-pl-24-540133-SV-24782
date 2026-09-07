<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit To-Do - mytodoweb</title>
    <style>
        :root {
            --bg-color: #ffffff;
            --text-main: #09090b;
            --text-muted: #71717a;
            --border-color: #e4e4e7;
            --border-dark: #09090b;
            --accent-bg: #09090b;
            --accent-text: #ffffff;
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

        .card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
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
            min-height: 100px;
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

        .actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>mytodoweb</h1>
            <p>Edit To-Do Item</p>
        </header>

        <div class="card">
            <div class="card-title">Form Edit To-Do</div>
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Judul Tugas *</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                    @error('title')
                        <div style="color: #000; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control">{{ old('description', $task->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="due_date">Tenggat Waktu / Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-control" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="is_completed" name="is_completed" value="1" {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}>
                    <label for="is_completed">Tandai sebagai Selesai</label>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
