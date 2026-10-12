<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test retrieving the task list via API.
     */
    public function test_can_get_task_list(): void
    {
        Task::create([
            'title' => 'Task Satu',
        ]);
        Task::create([
            'title' => 'Task Dua',
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['title' => 'Task Satu']);
        $response->assertJsonFragment(['title' => 'Task Dua']);
    }

    /**
     * Test creating a new task via API.
     */
    public function test_can_create_a_new_task(): void
    {
        $payload = [
            'title' => 'Belajar Software Evolution',
            'description' => 'Mengerjakan tugas konstruksi evolusi perangkat lunak.',
            'due_date' => '2026-09-15',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => 'To-Do berhasil ditambahkan!',
            'title' => 'Belajar Software Evolution',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Belajar Software Evolution',
            'is_completed' => false,
        ]);
    }

    /**
     * Test title validation when creating a task.
     */
    public function test_requires_title_when_creating_task(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => '',
            'description' => 'Tanpa judul',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Test showing a single task.
     */
    public function test_can_show_a_task(): void
    {
        $task = Task::create([
            'title' => 'Detail Task',
            'description' => 'Detail deskripsi',
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $task->id,
            'title' => 'Detail Task',
            'description' => 'Detail deskripsi',
        ]);
    }

    /**
     * Test updating a task via API.
     */
    public function test_can_update_a_task(): void
    {
        $task = Task::create([
            'title' => 'Judul Awal',
            'description' => 'Deskripsi Awal',
            'is_completed' => false,
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Judul Terupdate',
            'description' => 'Deskripsi Terupdate',
            'due_date' => '2026-09-20',
            'is_completed' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'To-Do berhasil diperbarui!',
            'title' => 'Judul Terupdate',
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Judul Terupdate',
            'description' => 'Deskripsi Terupdate',
            'is_completed' => true,
        ]);
    }

    /**
     * Test toggling completion status via API.
     */
    public function test_can_toggle_task_completion(): void
    {
        $task = Task::create([
            'title' => 'Task Toggle',
            'is_completed' => false,
        ]);

        // Toggle to completed
        $response1 = $this->patchJson("/api/tasks/{$task->id}/toggle");
        $response1->assertStatus(200);
        $response1->assertJsonFragment([
            'message' => 'To-Do ditandai selesai!',
        ]);
        $this->assertTrue($task->fresh()->is_completed);

        // Toggle back to incomplete
        $response2 = $this->patchJson("/api/tasks/{$task->id}/toggle");
        $response2->assertStatus(200);
        $response2->assertJsonFragment([
            'message' => 'To-Do ditandai belum selesai!',
        ]);
        $this->assertFalse($task->fresh()->is_completed);
    }

    /**
     * Test deleting a task via API.
     */
    public function test_can_delete_a_task(): void
    {
        $task = Task::create([
            'title' => 'Task Yang Akan Dihapus',
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'To-Do berhasil dihapus!',
        ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
