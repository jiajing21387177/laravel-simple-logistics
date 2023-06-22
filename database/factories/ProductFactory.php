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
        $freePerQuantityOrder = $this->faker->optional(0.5, 0)->numberBetween(10, 50);

        $name = $this->faker->words(2, true);
        return [
            'name' => $name,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'foc_below_quantity' => $this->faker->numberBetween(0, 100),
            'free_per_quantity_order' => $freePerQuantityOrder,
            'free_quantity' => $freePerQuantityOrder === 0 ? 0 : $this->faker->numberBetween(1, 10),
            'stock' => $this->faker->numberBetween(0, 1000),
            'image' => $this->faker->image(storage_path('app/public/images/products'), 640, 480, 'Product: ', false, true, $name)
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
            CustomerProductPrice::factory(rand(1, count($customers_id)))
                ->uniqueCustomer($customers_id)
                ->create(['product_id' => $product->id]);
        });
    }
}
