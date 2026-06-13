<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * @property int $id
 * @property string $sku
 * @property float $price
 * @property string|null $image_url
 * @property bool $is_active
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

#[Fillable(['sku', 'name', 'description', 'price', 'image_url', 'is_active', 'category_id'])]
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_items',
            'product_id',
            'order_id'
        )->withPivot(
            'quantity',
            'unit_price',
            'total_price'
        );
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
