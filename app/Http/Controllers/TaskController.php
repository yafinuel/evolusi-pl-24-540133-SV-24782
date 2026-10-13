<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TaskController
 *
 * Handles RESTful API actions and status toggles for To-Do items in mytodoweb.
 */
class TaskController extends Controller
{
    /**
     * Retrieve a list of all to-do tasks.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Task::orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tasks);
    }

    /**
     * Store a newly created task from request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create($validated);

        return response()->json([
            'message' => 'To-Do berhasil ditambahkan!',
            'task' => $task,
        ], 201);
    }

    /**
     * Display the specified task details.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'is_completed' => 'nullable|boolean',
        ]);

        if ($request->has('is_completed')) {
            $validated['is_completed'] = $request->boolean('is_completed');
        }

        $task->update($validated);

        return response()->json([
            'message' => 'To-Do berhasil diperbarui!',
            'task' => $task,
        ]);
    }

    /**
     * Toggle completion status of the specified task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function toggle(Task $task): JsonResponse
    {
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        $message = $task->is_completed ? 'To-Do ditandai selesai!' : 'To-Do ditandai belum selesai!';

        return response()->json([
            'message' => $message,
            'task' => $task,
        ]);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'To-Do berhasil dihapus!',
        ]);
    }
}
