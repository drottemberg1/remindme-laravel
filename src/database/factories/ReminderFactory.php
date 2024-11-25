<?php

namespace Database\Factories;

use App\Models\Reminder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReminderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reminder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'remind_at' => $this->faker->dateTimeBetween('+1 day', '+7 days'),
            'event_at' => $this->faker->dateTimeBetween('+1 day', '+7 days'),
            'type' => 'work', // or any default type
            'status' => 1,
            'user_id' => \App\Models\User::factory(), // Assuming reminders belong to users
        ];
    }
}
