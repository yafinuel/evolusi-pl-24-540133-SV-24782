<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test displaying the dashboard.
     */
    public function test_can_display_task_dashboard(): void
    {
        $response = $this->get('/tasks');

        $response->assertStatus(200);
        $response->assertSee('mytodoweb');
        $response->assertSee('Daftar To-Do');
    }

    /**
     * Test creating a new task.
     */
    public function test_can_create_a_new_task(): void
    {
        $payload = [
            'title' => 'Belajar Software Evolution',
            'description' => 'Mengerjakan tugas konstruksi evolusi perangkat lunak.',
            'due_date' => '2026-09-15',
        ];

        $response = $this->post('/tasks', $payload);

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'To-Do berhasil ditambahkan!');

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
        $response = $this->post('/tasks', [
            'title' => '',
            'description' => 'Tanpa judul',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    /**
     * Test displaying edit task page.
     */
    public function test_can_display_edit_task_page(): void
    {
        $task = Task::create([
            'title' => 'Task Lama',
        ]);

        $response = $this->get("/tasks/{$task->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Form Edit To-Do');
        $response->assertSee('Task Lama');
    }

    /**
     * Test updating a task.
     */
    public function test_can_update_a_task(): void
    {
        $task = Task::create([
            'title' => 'Judul Awal',
            'description' => 'Deskripsi Awal',
            'is_completed' => false,
        ]);

        $response = $this->put("/tasks/{$task->id}", [
            'title' => 'Judul Terupdate',
            'description' => 'Deskripsi Terupdate',
            'due_date' => '2026-09-20',
            'is_completed' => '1',
        ]);

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'To-Do berhasil diperbarui!');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Judul Terupdate',
            'description' => 'Deskripsi Terupdate',
            'is_completed' => true,
        ]);
    }

    /**
     * Test toggling completion status directly from dashboard.
     */
    public function test_can_toggle_task_completion(): void
    {
        $task = Task::create([
            'title' => 'Task Toggle',
            'is_completed' => false,
        ]);

        // Toggle to completed
        $response1 = $this->patch("/tasks/{$task->id}/toggle");
        $response1->assertRedirect('/tasks');
        $this->assertTrue($task->fresh()->is_completed);

        // Toggle back to incomplete
        $response2 = $this->patch("/tasks/{$task->id}/toggle");
        $response2->assertRedirect('/tasks');
        $this->assertFalse($task->fresh()->is_completed);
    }

    /**
     * Test deleting a task.
     */
    public function test_can_delete_a_task(): void
    {
        $task = Task::create([
            'title' => 'Task Yang Akan Dihapus',
        ]);

        $response = $this->delete("/tasks/{$task->id}");

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'To-Do berhasil dihapus!');

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
