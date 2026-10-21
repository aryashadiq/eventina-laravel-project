<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'sub_event_id' => 1,
            'code' => 'T' . $this->faker->unique()->numberBetween(1000, 9999),
            'currency' => 'IDR',
            'price' => $this->faker->numberBetween(10000, 100000),
            'tax' => 0,
        ];
    }
}
