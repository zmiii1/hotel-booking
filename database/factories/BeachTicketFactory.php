<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BeachTicket>
 */
class BeachTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Lalassa Beach Club', 'Bodur Beach']),
            'type' => $this->faker->randomElement(['Regular Ticket', 'Bundling Ticket']),
            'price' => 100000,
            'image_path' => 'frontend/assets/img/' . $this->faker->randomElement(['lalassa-beach.jpg', 'bodur-beach.jpeg']),
            'benefits' => [
                'Ticket Entrance',
                'Valid for 1 Person',
                'Voucher Meal',
                'Voucher Drink'
            ]
        ];
    }
}
