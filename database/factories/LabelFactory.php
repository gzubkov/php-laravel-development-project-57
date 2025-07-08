<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Label>
 */
class LabelFactory extends Factory
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
            'description' => $this->faker->sentence(),
        ];
    }

    public function predefined()
    {
        return $this->sequence(
            [
                'name' => 'ошибка',
                'description' => 'Какая-то ошибка в коде или проблема с функциональностью',
            ],
            [
                'name' => 'документация',
                'description' => 'Задача которая касается документации',
            ],
            [
                'name' => 'дубликат',
                'description' => 'Повтор другой задачи',
            ],
            [
                'name' => 'доработка',
                'description' => 'Новая фича, которую нужно запилить',
            ]
        );
    }
}
