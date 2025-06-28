<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function getAllCustomers()
    {
        return Customer::latest()->paginate(20);
    }

    public function getCustomerById($id)
    {
        return Customer::findOrFail($id);
    }

    public function createCustomer(array $data)
    {
        return Customer::create($data);
    }

    public function updateCustomer($id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return true;
    }

    public function getCustomerOrders($id)
    {
        return Customer::findOrFail($id)->orders()->with('items')->latest()->get();
    }

    public function getCreditStatus($id)
    {
        $customer = Customer::findOrFail($id);
        return [
            'credit_limit'    => $customer->credit_limit,
            'current_balance' => $customer->current_balance,
            'available_credit'=> $customer->credit_limit - $customer->current_balance
        ];
    }

    public function getMapData()
    {
        return Customer::select('id', 'name', 'latitude', 'longitude')
                       ->whereNotNull('latitude')
                       ->whereNotNull('longitude')
                       ->get();
    }
}
