<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $freePerQuantityOrder = $this->faker->optional(0.5, 0)->numberBetween(10, 50);

        return [
            'price' => $this->faker->randomFloat(2, 1, 100),
            'foc_below_quantity' => $this->faker->numberBetween(0, 100),
            'free_per_quantity_order' => $freePerQuantityOrder,
            'free_quantity' => $freePerQuantityOrder === 0 ? 0 : $this->faker->numberBetween(1, 10),
        ];
    }

    public function uniqueCustomer(array $customers_id)
    {
        $faker = \Faker\Factory::create();
        return $this->state(function () use ($customers_id, $faker) {
            return [
                'customer_id' => $faker->unique()->randomElement($customers_id)
            ];
        });
    }
}
