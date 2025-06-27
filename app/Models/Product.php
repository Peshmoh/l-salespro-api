<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku','name','category','subcategory','description',
        'price','tax_rate','unit','packaging',
        'min_order_quantity','reorder_level',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    /* Relationships */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
