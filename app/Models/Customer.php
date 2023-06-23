<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function confirmedOrders()
    {
        return $this->hasMany(Order::class)
            ->where('order_status', 'confirmed');
    }

    /**
     * Get the amount of customer owe.
     *
     * @return float
     */
    public function getCreditAttribute(): float
    {
        $totalPrices = $this->confirmedOrders->sum('total_price');
        $totalPaid = $this->confirmedOrders->sum('total_paid');
        return $totalPrices - $totalPaid;
    }
}
