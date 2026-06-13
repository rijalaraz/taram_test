<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * @property int $id
 * @property int $user_id
 * @property string $order_number
 * @property \Illuminate\Support\Carbon $order_date
 * @property float $total_amount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

#[Fillable(['user_id', 'order_number', 'order_date', 'total_amount', 'status'])]
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'order_items',
            'order_id',
            'product_id'
        )->withPivot(
            'quantity',
            'unit_price',
            'total_price'
        );
    }
}
