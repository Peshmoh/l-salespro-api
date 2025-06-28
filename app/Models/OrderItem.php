<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'unit_price',
        'discount',
        'tax',
        'line_total',            // ✅ uses line_total
    ];

    protected $casts = [
        'unit_price'  => 'decimal:2',
        'discount'    => 'decimal:2',
        'tax'         => 'decimal:2',
        'line_total'  => 'decimal:2',   // ✅ cast line_total
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
