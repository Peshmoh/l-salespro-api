<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    protected $fillable = [
        'product_id','warehouse_id','order_id',
        'quantity','reserved_at','expires_at',
    ];

    protected $dates = ['reserved_at','expires_at'];

    /* Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
