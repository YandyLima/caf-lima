<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('frontend.pages.shopping', [
            'products' => Product::where('active', 1)->get(),
        ]);
    }

    public function productCalculation(Request $request)
    {
        $productsQuery = Product::get();
        $productCalculation = collect([
            'products' => collect()
        ]);
        $products = collect($request->products)->map(function ($item) {
            return (object)$item;
        });

        $products->each(function ($product) use ($productsQuery, $productCalculation) {
            $productQuery = $productsQuery->find($product->productId);
            $productCalculation->get('products')->push((object)[
                'productId' => $product->productId,
                'name' => $productQuery->name,
                'url' => Storage::disk('public')->url($productQuery->images->where('type', 1)->first()->url ?? ''),
                'amount' => $product->amount,
                'price' => $productQuery->price,
                'total' => $product->amount * $productQuery->price
            ]);
        });
        $productCalculation->put('total', $productCalculation->get('products')->sum('total'));

        return response()->json($productCalculation);
    }
}
