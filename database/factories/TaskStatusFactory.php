<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskStatus>
 */
class TaskStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }

    public function predefined()
    {
        return $this->sequence(
            [
                'name' => 'новая',
            ],
            [
                'name' => 'завершена',
            ],
            [
                'name' => 'выполняется',
            ],
            [
                'name' => 'в архиве',
            ]
        );
    }
}
