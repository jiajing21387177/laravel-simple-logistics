<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber()
        ];
    }

    public function randomDriver()
    {
        $driversId = User::where('type', 'driver')->get()->modelKeys();

        return $this->state(function () use ($driversId) {
            return [
                'user_id' => $this->faker->randomElement($driversId)
            ];
        });
    }
}
