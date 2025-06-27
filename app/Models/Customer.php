<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','type','category','contact_person','phone','email',
        'tax_id','payment_terms','credit_limit','current_balance',
        'latitude','longitude','address',
    ];

    protected $casts = [
        'credit_limit'    => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    /* Relationships */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
