<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\MailBillingJob;
use App\Mail\BillingEmail;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mail;
use Str;

class SaleController extends Controller
{
    public $sale;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = $this->statuses();
        return view('admin.sales.index', compact('statuses'));
    }

    public function listSales(Request $request)
    {
        $sales = Sale::whereRaw(true);
        $this->response = $this->listData($request, $sales);
        $sales = $this->response['data'];
        $data = [];
        foreach ($sales as $sale) {
            $data[] = [
                'id' => $sale->id,
                'customer' => $sale->customer->name,
                'amount' => 'Q.' . $sale->amount_paid,
                'status' => $this->statuses()[$sale->status] ?? 'Indefinido',
                'paid_type' => $this->payment_types[$sale->payment_type] ?? 'Indefinido',
//                'transaction_number' => $sale->transaction_number,
                'date' => Carbon::parse($sale->created_at)->format('d/m/Y h:i A'),
            ];
        }
        $response = [
            'recordsTotal' => $this->response['recordsTotal'],
            'recordsFiltered' => $this->response['recordsFiltered'],
            'data' => $data,
        ];
        return response()->json($response);
    }

    public function tracking(Request $request)
    {
        $sale = Sale::find($request->sale_id);
        $data = [];
        foreach (json_decode($sale->tracking) as $key => $tracking) {
            $data[] = [
                'status' => $key,
                'date' => Carbon::parse($tracking)->format('d/m/y  H:i A'),
                'icon' => $this->statuses()[$key] ?? 'Indefinido',
            ];
        }
        $this->response = [
            'sale_id' => $sale->id,
            'status' => $sale->status,
            'tracking' => $data,
        ];
        return response()->json($this->response);
    }

    public function updateTracking(Sale $sale, Request $request)
    {
        if ($request->status == $sale->status + 1) {
            $this->updateStatus($sale, $request->status);
            $this->response_type = 'success';
            $this->message = 'Estado actualizado';
        } else {
            $this->response_type = 'error';
            $this->message = 'No se puede saltar estados';
        }

        return back()->with($this->response_type, $this->message);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'items' => ['required', 'array'],
                'date_generated' => ['required', 'date_format:Y-m-d H:i:s'],
                'payment_type' => ['required'] // Agrega validación para payment_type
            ]);
            DB::beginTransaction();
            $validate['status'] = 1;
            $validate['amount_paid'] = 0;
            $validate['tracking'] = json_encode([$validate['date_generated']]);
            $sale = Sale::create($validate);
            $this->updateStatus($sale, 1);
            $amount_paid = 0;
            foreach ($validate['items'] as $item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $sale->sale_details()->create([
                        'product_id' => $item['id'],
                        'amount' => $item['amount'],
                    ]);
                    $amount_paid += $product->price * $item['amount'];
                    $product->stock = $product->stock - $item['amount'];
                    $product->save();
                }
            }
            $sale->amount_paid = $amount_paid;
            // Asigna el valor de payment_type al modelo Sale
            $sale->payment_type = $validate['payment_type'];
            $sale->save();
            $this->sale = $sale->id;
            DB::commit();
            $this->message = 'Se ha generado tu pedido correctamente';
            $this->status_code = 200;
        } catch (Exception $exception) {
            $this->sale = null;
            DB::rollBack();
            $this->message = $exception->getMessage();
            $this->status_code = 500;
        }
        $this->response = [
            'message' => $this->message,
            'sale_id' => $this->sale
        ];
        return response()->json($this->response, $this->status_code);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

    /**
     * Generar factura y enviar correo
     */
    public function billing(Sale $sale)
    {
        try {
            if ($sale->authorization_number == null) {
                DB::beginTransaction();
                $sale->authorization_number = strtoupper(Str::random(15));
                $sale->save();
                MailBillingJob::dispatch($sale);
                DB::commit();
                return back()->with('success', 'Se enviará la factura');
            } else {
                return back()->with('error', 'La factura ya fue generada');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'No fue posible generar la factura');
        }
    }
}
