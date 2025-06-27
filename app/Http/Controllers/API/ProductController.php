<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /* GET /api/v1/products */
    public function index(Request $request)
    {
        $perPage  = $request->input('per_page', 10);
        $search   = $request->input('search');

        $products = Product::query()
            ->when($search, fn ($q) =>
                $q->whereFullText(['name','description'], $search)
            )
            ->latest()
            ->paginate($perPage);

        return response()->json(['success' => true, 'data' => $products]);
    }

    /* GET /api/v1/products/{id} */
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data'    => Product::findOrFail($id),
        ]);
    }

    /* POST /api/v1/products   (Admin only) */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product created.',
            'data'    => $product,
        ], 201);
    }

    /* PUT /api/v1/products/{id}  (Admin only) */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product updated.',
            'data'    => $product->refresh(),
        ]);
    }

    /* DELETE /api/v1/products/{id}  (Soft‑delete) */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted.'], 204);
    }
}
