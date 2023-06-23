<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    public static function getProductsWithCustomerPrices(int $customerId)
    {
        $query = "SELECT p.id, p.name, p.stock,
            COALESCE(cpp.price, p.price) AS `price`,
            COALESCE(cpp.foc_below_quantity, p.foc_below_quantity) AS `foc_below_quantity`,
            COALESCE(cpp.free_per_quantity_order, p.free_per_quantity_order) AS `free_per_quantity_order`,
            COALESCE(cpp.free_quantity, p.free_quantity) AS `free_quantity`
            FROM products p
            LEFT JOIN customer_product_prices cpp
            ON cpp.product_id = p.id
            AND cpp.customer_id = :customer_id
            GROUP BY p.id";

        $products = DB::select($query, ['customer_id' => $customerId]);

        return $products;
    }
}
