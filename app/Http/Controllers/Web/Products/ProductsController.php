<?php

namespace App\Http\Controllers\Web\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index()
    {
        $products = Product::paginate(5);

        return view('tables',compact('products'));
    }

    public function show($id)
    {
        $product = Product::find($id);

        if ($product) {
            return view('products.show', compact('product'));
        } else {
            return redirect()->back()->with('error', 'Product not found');
        }
    }

    public function create()
    {
        $categories = Category::get();
        return view('create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $userId = Auth::id();

        try {
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'owner_id' => $userId,
                'quantity' => $request->quantity,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getGraterPrice()
    {
        $product = Product::orderBy('price', 'desc')->first();
        if ($product) 
        {
            dd($product);
        }
        return "NO available products"; 
    }

    public function getProductsAbovePrice($amount)
    {
        $products = Product::where('price', '>', $amount)->get();

        if ($products->isNotEmpty())
        {
            dd($products);
        }

        return "NO available products"; 

        // return view('products.index', compact('products'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->all());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index');
    }
}
