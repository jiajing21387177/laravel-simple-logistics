<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_quantity' => rand(1, 100),
        ];
    }

    public function randomProduct(int $customerId)
    {
        $products = Product::getProductsWithCustomerPrices($customerId);

        $faker = \Faker\Factory::create();

        return $this->state(function ($attrs) use ($products, $faker) {

            $product = $faker->unique()->randomElement($products);

            // Calculate how much the customer has to pay
            $quantityToPay = $attrs['order_quantity'] - $product->foc_below_quantity;
            $amountToPay = (float) $product->price * $quantityToPay;

            // Calculate how many free item the customer get
            $freeQuantity = 0;
            if ($product->free_per_quantity_order > 0) {
                $freeQuantity = floor($attrs['order_quantity'] / $product->free_per_quantity_order) * $product->free_quantity;
            }

            return [
                'product_id' => $product->id,
                'unit_price' => $product->price,
                'free_quantity' => $freeQuantity,
                'total_quantity' => $attrs['order_quantity'] + $freeQuantity,
                'total_price' => $amountToPay > 0 ? $amountToPay : 0,
                'customer_product_prices_foc_below_quantity' => $product->foc_below_quantity,
                'customer_product_prices_free_per_quantity_order' => $product->free_per_quantity_order,
                'customer_product_prices_free_quantity' => $product->free_quantity,
            ];
        });
    }
}
