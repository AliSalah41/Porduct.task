<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\PorductResource;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::paginate(3);

        if($products->isEmpty())
        {
            return response()->json([
                "success" => false,
                "products" => null,
                "message" => "No products were found",
            ],404);
        }

        return response()->json([
            "success" => true,
            "message" => "products returned successfully",
            "products" => PorductResource::collection($products)
        ],200);
    }

    public function store(ProductRequest $request)
    {
        $userId = Auth::id();

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'owner_id' => $userId ?? null,
            'quantity' => $request->quantity,
        ]);

        if ($product) {
            return response()->json([
                "success" => true,
                "message" => "Successfully created"
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Failed to create product"
            ], 500);
        }
    }


}
