<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SaleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sale = Sale::find($request->sale_id);

        $file = $sale->bill()->first();
        if ($file) {
            $url = Storage::disk('public')->url($file->url);
            $sale->url = $url;
        }

        $response = [];
        foreach ($sale->sale_details as $detail)
        {
            $response[] = [
                'product'   => $detail->product->name,
                'amount'    => $detail->amount,
                'price'     => $detail->product->price,
                'subtotal'  => $detail->product->price * $detail->amount,
            ];
        }
        $this->response = [
            ''    => count($response),
            'recordsFiltered' => count($response),
            'data'            => $response,
            'sale'            => $sale
        ];
        return response()->json($this->response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
