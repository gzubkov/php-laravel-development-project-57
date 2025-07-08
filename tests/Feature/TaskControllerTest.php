<?php

namespace Tests\Feature;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndexDisplaysTasksWithPagination()
    {
        Task::factory()->count(20)->create();

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200)
            ->assertViewIs('task.index')
            ->assertViewHas('tasks', function ($tasks) {
                return $tasks->count() === 15;
            });
    }

    public function testCreateIsRestrictedForUnauthenticatedUser()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertStatus(403);
    }

    public function testCreateDisplaysFormForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertViewIs('task.create');
        $response->assertViewHas('task', fn($task) => $task instanceof Task);
    }
    
    public function testStoreFailsForUnauthenticatedUser()
    {
        $response = $this->post(route('tasks.store'), ['name' => 'Test Task']);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['name' => 'Test Task']);
    }

    public function testStoreForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $data = [
            'name' => 'Test Task',
            'description' => 'This is a test task description',
            'status_id' => TaskStatus::factory()->create()->getKey(),
        ];

        $this->mock(TaskStoreRequest::class, fn($mock) => $mock->shouldReceive('validated')->andReturn($data));

        $response = $this->post(route('tasks.store'), $data);

        $response->assertRedirect(route('tasks.index'));
        
        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.task.create_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseHas('tasks', $data);
    }

    public function testShowDisplaysTask()
    {
        $task = Task::factory()->create(['name' => 'Test Task']);

        $response = $this->get(route('tasks.show', $task));

        $response->assertStatus(200)
            ->assertViewIs('task.show')
            ->assertViewHas('task', function ($viewTask) use ($task) {
                return $viewTask->id === $task->getKey();
            });
    }

    public function testEditIsRestrictedForUnauthenticatedUser()
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.edit', $task));

        $response->assertStatus(403);
    }

    public function testEditDisplaysFormForAuthenticatedUser()
    {
        $this->actingAs($this->user);
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.edit', $task));

        $response->assertStatus(200);
        $response->assertViewIs('task.edit');
        $response->assertViewHas('task', fn($viewTask) => $viewTask->is($task));
    }

    public function testUpdateForAuthenticatedUser()
    {
        $this->actingAs($this->user);
        $task = Task::factory()->create();
        
        $data = [
            'name' => 'Updated Task',
            'status_id' => TaskStatus::factory()->create()->getKey(),
        ];

        $this->mock(TaskUpdateRequest::class, fn($mock) => $mock->shouldReceive('validated')->andReturn($data));

        $response = $this->put(route('tasks.update', $task), $data);

        $response->assertRedirect(route('tasks.index'));
        
        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.task.update_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseHas('tasks', ['id' => $task->getKey(), 'name' => 'Updated Task']);
    }

    public function testUpdateFailsForUnauthenticatedUser()
    {
        $task = Task::factory()->create();
        $data = ['name' => 'Updated Task'];

        $response = $this->put(route('tasks.update', $task), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['id' => $task->getKey(), 'name' => 'Updated Task']);
    }

    public function testDestroy()
    {
        $this->actingAs($this->user);
        $task = Task::factory()->create([
            'name' => 'Test Task',
            'created_by_id' => $this->user->id,
        ]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.task.delete_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseMissing('tasks', ['id' => $task->getKey()]);
    }

    public function testDestroyFailsForUnauthenticatedUser()
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->getKey()]);
    }

    public function testDestroyFailsForNonCreator()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $otherUser->getKey()]);

        $this->actingAs($this->user);
        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->getKey()]);
    } 
}