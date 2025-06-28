<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'type'            => $this->type,
            'category'        => $this->category,
            'contact_person'  => $this->contact_person,
            'phone'           => $this->phone,
            'email'           => $this->email,
            'tax_id'          => $this->tax_id,
            'payment_terms'   => $this->payment_terms,
            'credit_limit'    => $this->credit_limit,
            'current_balance' => $this->current_balance,
            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'address'         => $this->address,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
