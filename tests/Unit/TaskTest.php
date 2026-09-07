<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test task attributes and casting.
     */
    public function test_task_attributes_and_casting(): void
    {
        $task = Task::create([
            'title' => 'Unit Test Task',
            'description' => 'Testing model casting',
            'is_completed' => 1,
            'due_date' => '2026-10-01',
        ]);

        $this->assertIsBool($task->is_completed);
        $this->assertTrue($task->is_completed);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $task->due_date);
        $this->assertEquals('2026-10-01', $task->due_date->format('Y-m-d'));
    }

    /**
     * Test default completion status is false.
     */
    public function test_default_is_completed_is_false(): void
    {
        $task = Task::create([
            'title' => 'Default Status Task',
        ]);

        $this->assertFalse($task->is_completed);
    }
}
