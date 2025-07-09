<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Http\Requests\TaskStatusRequest;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp(); // нужно иначе RefreshDatabase может не сработать

        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        TaskStatus::factory()->count(5)->create();

        $response = $this->get(route('task_statuses.index'));

        $response->assertStatus(200);
        $response->assertViewIs('task_status.index');
        $response->assertViewHas('taskStatuses', fn($task_statuses) => $task_statuses->count() === 5);
        $response->assertViewHas('taskStatusModel', fn($model) => $model instanceof TaskStatus);
    }

    public function testCreateIsRestrictedForUnauthenticatedUser()
    {
        $response = $this->get(route('task_statuses.create'));

        $response->assertStatus(403);
    }

    public function testCreateDisplaysFormForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('task_statuses.create'));

        $response->assertStatus(200);
        $response->assertViewIs('task_status.create');
        $response->assertViewHas('taskStatus', fn($taskStatus) => $taskStatus instanceof TaskStatus);
    }

    public function testStoreForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $data = [
            'name' => 'TaskStatus',
        ];

        $this->mock(
            TaskStatusRequest::class,
            fn($mock) => $mock->shouldReceive('validated')->andReturn($data)
        );

        $response = $this->post(route('task_statuses.store'), $data);

        $response->assertRedirect(route('task_statuses.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.task_status.create_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseHas('task_statuses', ['name' => 'TaskStatus']);
    }

    public function testStoreFailsForUnauthenticatedUser()
    {
        $response = $this->post(route('task_statuses.store'), ['name' => 'TaskStatus']);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', ['name' => 'TaskStatus']);
    }

    /*
    public function testShowNotDefined()
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.show', $taskStatus));

        $response->assertStatus(404);
    }
    */
    public function testCreateAndStoreTaskStatus(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(200);

        $data = [
            'name' => 'TaskStatus create'
        ];

        $response = $this->post(route('task_statuses.store'), $data);
        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testUpdateIsRestrictedForUnauthenticatedUser()
    {
        $taskStatus = TaskStatus::factory()->create();
        $data = ['name' => 'Updated TaskStatus'];

        $response = $this->put(route('task_statuses.update', $taskStatus), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus->getKey(), 'name' => 'Updated TaskStatus']);
    }

    public function testEditIsRestrictedForUnauthenticatedUser()
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.edit', $taskStatus));

        $response->assertStatus(403);
    }

    public function testEditDisplaysFormForAuthenticatedUser()
    {
        $this->actingAs($this->user);
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.edit', $taskStatus));

        $response->assertStatus(200);
        $response->assertViewIs('task_status.edit');
        $response->assertViewHas('taskStatus', fn($viewTaskStatus) => $viewTaskStatus->is($taskStatus));
    }

    public function testUpdateFailsForUnauthenticatedUser()
    {
        $taskStatus = TaskStatus::factory()->create();
        $data = ['name' => 'Updated TaskStatus'];

        $response = $this->put(route('task_statuses.update', $taskStatus), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus->getKey(), 'name' => 'Updated TaskStatus']);
    }

    public function testUpdateForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $taskStatus = TaskStatus::factory()->create();
        $data = [
            'name' => 'Updated TaskStatus'
        ];

        $this->mock(
            TaskStatusRequest::class,
            fn($mock) => $mock->shouldReceive('validated')->andReturn($data)
        );

        $response = $this->put(route('task_statuses.update', $taskStatus), $data);

        $response->assertRedirect(route('task_statuses.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.task_status.update_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseHas('task_statuses', ['id' => $taskStatus->getKey(), 'name' => 'Updated TaskStatus']);
    }

    public function testDestroyFailsForUnauthenticatedUser()
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertStatus(403);
        $this->assertDatabaseHas('task_statuses', ['id' => $taskStatus->getKey()]);
    }

    public function testDestroyDeletesTaskStatusWithoutTasks()
    {
        $this->actingAs($this->user);

        $taskStatus = TaskStatus::factory()->create();

        $response = $this->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('task_statuses.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.task_status.delete_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus->getKey()]);
    }

    public function testDestroyFailsForTaskStatusWithTasks()
    {
        $this->actingAs($this->user);

        $taskStatus = TaskStatus::factory()->create();
        Task::factory()->create(['status_id' => $taskStatus->getKey()]);

        $response = $this->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('task_statuses.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.task_status.delete_failed'), $message->message);
        $this->assertEquals('danger', $message->level);

        $this->assertDatabaseHas('task_statuses', ['id' => $taskStatus->getKey()]);
    }
}
