<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orderStatus = $this->faker->optional(0.1, 'confirmed')->randomElement(['draft', 'void']);

        if ($orderStatus !== 'confirmed') {
            $deliveryStatus = null;
        } else {
            $deliveryStatus = $this->faker->optional(0.1, 'delivered')->randomElement(['pending', 'failed']);
        }

        $deliveredAt = $deliveryStatus === 'delivered' ? $this->faker->dateTimeThisYear() : null;

        return [
            'customer_id' => $this->faker->randomElement(Customer::all()->modelKeys()),
            'total_price' => 0,
            'total_paid' => 0,
            'order_status' => $orderStatus,
            'delivery_status' => $deliveryStatus,
            'delivered_at' => $deliveredAt,
            'remark' => $this->faker->sentence(rand(1, 10)),
            'receipt' => null,
        ];
    }

    public function randomDriver()
    {
        $drivers = User::where('type', 'driver')->get()->modelKeys();

        return $this->state(function () use ($drivers) {
            return [
                'user_id' => $this->faker->randomElement($drivers)
            ];
        });
    }

    public function randomAdmin()
    {
        $admins = User::where('type', 'admin')->get()->modelKeys();

        return $this->state(function ($attrs) use ($admins) {
            return [
                'void_by' => $attrs['order_status'] === 'void' ? $this->faker->randomElement($admins) : null,
                'created_by' => $this->faker->randomElement($admins),
            ];
        });
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        $orderItems = [];
        $productsCount = Product::count();

        return $this->afterCreating(function (Order $order) use (&$orderItems, $productsCount) {
            // Create order items
            $orderItems = OrderItem::factory()
                ->count(rand(1, $productsCount))
                ->randomProduct($order->customer_id)
                ->create([
                    'order_id' => $order->id,
                ]);

            // Calculate the orders price
            $order->total_price = $orderItems->sum('total_price');

            // Get credit from customer (money owed)
            $customer = Customer::find($order->customer_id);

            $order->total_paid = $this->faker->randomFloat(2, 0, $order->total_price + $customer->credit);

            $order->save();
        });
    }
}
