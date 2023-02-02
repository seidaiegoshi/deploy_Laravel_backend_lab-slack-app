<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'channel_id' => \App\Models\Channel::factory(),
            'user_id' => \App\Models\User::factory(),
            'content' => fake()->text($maxNbChars = 100),
        ];
    }
}
