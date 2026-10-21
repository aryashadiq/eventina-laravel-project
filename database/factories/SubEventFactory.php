<?php

namespace Database\Factories;

use App\Models\SubEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubEventFactory extends Factory
{
    protected $model = SubEvent::class;

    public function definition()
    {
        return [
            'event_id' => 1,
            'title' => $this->faker->sentence,
            'start_at' => now(),
            'end_at' => now()->addHours(2),
        ];
    }
}
