<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceived;

class ShoppingCartController extends Controller
{
    public function index()
    {
        return view('frontend.pages.shopping-cart.shopping-cart');
    }

    public function pay()
    {
        $user = auth()->user();
        return view('frontend.pages.shopping-cart.pay', [
            'user' => $user
        ]);
    }

    public function endOfOrder(Sale $sale)
    {
        Mail::to($sale->user->email)->send(new OrderReceived($sale));

        return view('frontend.pages.shopping-cart.end-of-order', [
            'sale' => $sale
        ]);
    }
}
