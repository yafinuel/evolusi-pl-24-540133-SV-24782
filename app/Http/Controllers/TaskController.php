<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class TaskController
 *
 * Handles CRUD actions and status toggles for To-Do items in mytodoweb.
 */
class TaskController extends Controller
{
    /**
     * Display the mytodoweb main dashboard with all to-do tasks.
     *
     * @return View
     */
    public function index(): View
    {
        // Retrieve tasks ordered by completion status (uncompleted first) and creation date (newest first)
        $tasks = Task::orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('task.index', compact('tasks'));
    }

    /**
     * Store a newly created task from the dashboard form.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'To-Do berhasil ditambahkan!');
    }

    /**
     * Display the form for editing the specified task.
     *
     * @param Task $task
     * @return View
     */
    public function edit(Task $task): View
    {
        return view('task.edit', compact('task'));
    }

    /**
     * Update the specified task in storage and redirect to dashboard.
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'is_completed' => 'nullable|boolean',
        ]);

        $validated['is_completed'] = $request->has('is_completed');

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'To-Do berhasil diperbarui!');
    }

    /**
     * Toggle completion status of a task directly from dashboard.
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function toggle(Task $task): RedirectResponse
    {
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        $message = $task->is_completed ? 'To-Do ditandai selesai!' : 'To-Do ditandai belum selesai!';

        return redirect()->route('tasks.index')->with('success', $message);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'To-Do berhasil dihapus!');
    }
}
