<?php

namespace Database\Factories;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = TaskStatus::inRandomOrder()->first() ?? TaskStatus::factory()->create();
        $creator = User::factory()->create();
        $contractor = $this->faker->boolean(50) ? User::factory()->create() : null;

        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->sentences(3, true),
            'status_id' => $status->id,
            'created_by_id' => $creator->id,
            'assigned_to_id' => $contractor ? $contractor : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Task $task) {
            $labels = Label::inRandomOrder()->take($this->faker->numberBetween(0, 4))->pluck('id');
            $task->labels()->attach($labels);
        });
    }
}
