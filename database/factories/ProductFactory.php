<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerProductPrice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'stock' => $this->faker->numberBetween(0, 1000),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        $customers_id = Customer::all()->modelKeys();

        return $this->afterCreating(function (Product $product) use ($customers_id) {
            CustomerProductPrice::factory(count($customers_id))
                ->uniqueCustomer($customers_id)
                ->create(['product_id' => $product->id]);
        });
    }
}
