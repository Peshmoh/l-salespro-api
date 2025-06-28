<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private CustomerService $svc;

    public function __construct(CustomerService $svc)
    {
        $this->svc = $svc;
    }

    /* ─────────────────────────────────────────
     | GET /api/v1/customers
     | Optional filters: search, category
     *────────────────────────────────────────*/
    public function index(Request $request)
    {
        $customers = $this->svc->getAllCustomers(
            $request->only('search', 'category'),
            $request->integer('per_page', 10)
        );

        return CustomerResource::collection($customers)
               ->additional(['success' => true]);
    }

    /* ─────────────────────────────────────────
     | GET /api/v1/customers/{id}
     *────────────────────────────────────────*/
    public function show(int $id)
    {
        return new CustomerResource($this->svc->getCustomerById($id));
    }

    /* ─────────────────────────────────────────
     | POST /api/v1/customers
     *────────────────────────────────────────*/
    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->svc->createCustomer($request->validated());

        return (new CustomerResource($customer))
               ->additional(['success' => true, 'message' => 'Customer created.'])
               ->response()->setStatusCode(201);
    }

    /* ─────────────────────────────────────────
     | PUT /api/v1/customers/{id}
     *────────────────────────────────────────*/
    public function update(UpdateCustomerRequest $request, int $id)
    {
        $customer = $this->svc->updateCustomer($id, $request->validated());

        return (new CustomerResource($customer))
               ->additional(['success' => true, 'message' => 'Customer updated.']);
    }

    /* ─────────────────────────────────────────
     | DELETE /api/v1/customers/{id}
     | Soft delete
     *────────────────────────────────────────*/
    public function destroy(int $id)
    {
        $this->svc->deleteCustomer($id);

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted.'
        ], 204);
    }

    /* ─────────────────────────────────────────
     | GET /api/v1/customers/{id}/orders
     *────────────────────────────────────────*/
    public function orderHistory(int $id)
    {
        $orders = $this->svc->getCustomerOrders($id);

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    /* ─────────────────────────────────────────
     | GET /api/v1/customers/{id}/credit-status
     *────────────────────────────────────────*/
    public function creditStatus(int $id)
    {
        $status = $this->svc->getCreditStatus($id);

        return response()->json([
            'success' => true,
            'data'    => $status,
        ]);
    }

    /* ─────────────────────────────────────────
     | GET /api/v1/customers/map-data
     *────────────────────────────────────────*/
    public function mapData()
    {
        $points = $this->svc->getMapData();

        return response()->json([
            'success' => true,
            'data'    => $points,
        ]);
    }
}
