<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code','name','type','address','manager_email',
        'phone','capacity','latitude','longitude',
    ];

    /* Relationships */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function stockReservations()
    {
        return $this->hasMany(StockReservation::class);
    }
}
