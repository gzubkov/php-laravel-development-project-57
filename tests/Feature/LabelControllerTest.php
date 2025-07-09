<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Http\Requests\LabelRequest;

class LabelControllerTest extends TestCase
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
        Label::factory()->count(10)->create();

        $response = $this->get(route('labels.index'));

        $response->assertStatus(200);
        $response->assertViewIs('label.index');
        $response->assertViewHas('labels', fn($labels) => $labels->count() === 10);
        $response->assertViewHas('labelModel', fn($labelModel) => $labelModel instanceof Label);
    }

    public function testCreateIsRestrictedForUnauthenticatedUser()
    {
        $response = $this->get(route('labels.create'));

        $response->assertStatus(403);
    }

    public function testCreateDisplaysFormForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('labels.create'));

        $response->assertStatus(200);
        $response->assertViewIs('label.create');
        $response->assertViewHas('label', fn($label) => $label instanceof Label);
    }

    public function testStoreForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $data = [
            'name' => 'Label',
            'description' => 'Description'
        ];

        $this->mock(
            LabelRequest::class,
            fn($mock) => $mock->shouldReceive('validated')->andReturn($data)
        );

        $response = $this->post(route('labels.store'), $data);

        $response->assertRedirect(route('labels.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.label.create_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseHas('labels', ['name' => 'Label']);
    }

    public function testStoreFailsForUnauthenticatedUser()
    {
        $response = $this->post(route('labels.store'), ['name' => 'Label']);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', ['name' => 'Label']);
    }

    public function testCreateAndStoreLabel(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('labels.create'));
        $response->assertStatus(200);

        $data = [
            'name' => 'Label create'
        ];

        $response = $this->post(route('labels.store'), $data);
        $response->assertRedirect(route('labels.index'));

        $this->assertDatabaseHas('labels', $data);
    }

    public function testUpdateIsRestrictedForUnauthenticatedUser()
    {
        $label = Label::factory()->create();
        $data = ['name' => 'Updated Label'];

        $response = $this->put(route('labels.update', $label), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', ['id' => $label->getKey(), 'name' => 'Updated Label']);
    }

    public function testEditIsRestrictedForUnauthenticatedUser()
    {
        $label = Label::factory()->create();

        $response = $this->get(route('labels.edit', $label));

        $response->assertStatus(403);
    }

    public function testEditDisplaysFormForAuthenticatedUser()
    {
        $this->actingAs($this->user);
        $label = Label::factory()->create();

        $response = $this->get(route('labels.edit', $label));

        $response->assertStatus(200);
        $response->assertViewIs('label.edit');
        $response->assertViewHas('label', fn($viewLabel) => $viewLabel->is($label));
    }

    public function testUpdateFailsForUnauthenticatedUser()
    {
        $label = Label::factory()->create();
        $data = ['name' => 'Updated Label'];

        $response = $this->put(route('labels.update', $label), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', ['id' => $label->getKey(), 'name' => 'Updated Label']);
    }

    public function testUpdateForAuthenticatedUser()
    {
        $this->actingAs($this->user);

        $label = Label::factory()->create();
        $data = ['name' => 'Updated Label', 'description' => 'Updated Description'];

        $this->mock(
            LabelRequest::class,
            fn($mock) => $mock->shouldReceive('validated')->andReturn($data)
        );

        $response = $this->put(route('labels.update', $label), $data);

        $response->assertRedirect(route('labels.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.label.update_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseHas('labels', ['id' => $label->getKey(), 'name' => 'Updated Label']);
    }

    public function testDestroyFailsForUnauthenticatedUser()
    {
        $label = Label::factory()->create();

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertStatus(403);
        $this->assertDatabaseHas('labels', ['id' => $label->getKey()]);
    }

    public function testDestroyDeletesLabelWithoutTasks()
    {
        $this->actingAs($this->user);

        $label = Label::factory()->create();

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.label.delete_success'), $message->message);
        $this->assertEquals('success', $message->level);

        $this->assertDatabaseMissing('labels', ['id' => $label->getKey()]);
    }

    public function testDestroyFailsForLabelWithTasks()
    {
        $this->actingAs($this->user);
        $label = Label::factory()->create();

        $task = Task::factory()->create();
        $task->labels()->attach($label->getKey());

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));

        $message = session('flash_notification')[0];

        $this->assertEquals(__('app.messages.label.delete_failed'), $message->message);
        $this->assertEquals('danger', $message->level);

        $this->assertDatabaseHas('labels', ['id' => $label->getKey()]);
    }
}
