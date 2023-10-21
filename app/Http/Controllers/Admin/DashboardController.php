<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $statuses = $this->statuses();
        return view('admin.dashboard.dashboard', compact('statuses'));
    }

    public function dashboard(Request $request)
    {
        $validate = $request->validate([
            'type' => ['required', 'integer', 'between:1,3']
        ]);

        if ($validate['type'] == 2) {
            $date = [
                'begin' => date('Y-m-01 00:00:00'),
                'end' => date('Y-m-t 23:59:59'),
            ];
        } elseif ($validate['type'] == 3) {
            $date = [
                'begin' => date('Y-01-01 00:00:00'),
                'end' => date('Y-12-t 23:59:59'),
            ];
        } else {
            $date = [
                'begin' => date('Y-m-d 00:00:00'),
                'end' => date('Y-m-d 23:59:59'),
            ];
        }

        // Resumen
        $sales = Sale::whereBetween('created_at', [$date['begin'], $date['end']]);
        $collect_sales = collect($sales->with('customer')->get());
        $sales_count = $sales->count();
        $sales_money = "Q" . round($sales->sum('amount_paid'), 2);
        $customers = $sales->distinct()->get(['user_id']);

        // Gráfica de ventas por producto
        $products = Product::get();
        $sales_by_product_products = [];
        $sales_by_product_values = [];
        foreach ($products as $product) {
            $product_sale_details = $product->sale_details()
                ->whereBetween('created_at', [$date['begin'], $date['end']])->get();
            $sales_by_product_products[] = $product->name;
            $sales_by_product_values[] = $product_sale_details->sum('amount');
        }

        // Grafica de ventas
        $purchases = Purchase::select('description', 'price')
            ->whereBetween('created_at', [$date['begin'], $date['end']])
            ->orderBy('id', 'DESC')->take('10')->get();

        // Grafica de ventas
        $money_sales = [];
        if ($validate['type'] == 2) {
            $parameters = [
                '01 -  07',
                '08 -  14',
                '15 -  21',
                '22 -  28',
                '28 -  ->',
            ];
            $money_sales[] = $this->addValue(date('Y-m-01 00:00:00'), date('Y-m-07 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-08 00:00:00'), date('Y-m-14 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-15 00:00:00'), date('Y-m-21 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-22 00:00:00'), date('Y-m-28 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-29 00:00:00'), date('Y-m-31 23:59:59'), $collect_sales);
        } elseif ($validate['type'] == 3) {
            $parameters = [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Septiembre',
                'Octubre',
                'Noviembre',
                'Diciembre',
            ];
            $money_sales[] = $this->addValue(date('Y-01-01 00:00:00'), date('Y-01-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-02-01 00:00:00'), date('Y-02-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-03-01 00:00:00'), date('Y-03-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-04-01 00:00:00'), date('Y-04-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-05-01 00:00:00'), date('Y-05-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-06-01 00:00:00'), date('Y-06-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-07-01 00:00:00'), date('Y-07-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-08-01 00:00:00'), date('Y-08-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-09-01 00:00:00'), date('Y-09-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-10-01 00:00:00'), date('Y-10-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-11-01 00:00:00'), date('Y-11-t 23:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-12-01 00:00:00'), date('Y-12-t 23:59:59'), $collect_sales);
        } else {
            $parameters = [
                '00:00 -  02:59',
                '03:00 -  05:59',
                '06:00 -  08:59',
                '09:00 -  11:59',
                '12:00 -  14:59',
                '15:00 -  17:59',
                '18:00 -  20:59',
                '21:00 -  23:59',
            ];
            $money_sales[] = $this->addValue(date('Y-m-d 00:00:00'), date('Y-m-d 02:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-d 03:00:00'), date('Y-m-d 05:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-d 06:00:00'), date('Y-m-d 08:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-d 09:00:00'), date('Y-m-d 11:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-d 12:00:00'), date('Y-m-d 14:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-d 15:00:00'), date('Y-m-d 17:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-d 18:00:00'), date('Y-m-d 20:59:59'), $collect_sales);
            $money_sales[] = $this->addValue(date('Y-m-d 21:00:00'), date('Y-m-d 23:59:59'), $collect_sales);
        }

        $this->response = [
            'sales' => $sales_count,
            'sales_money' => $sales_money,
            'customers' => $customers->count(),
            'graphics' => [
                'sales' => [
                    'labels' => $parameters,
                    'values' => $money_sales,
                ],
                'sales_by_product' => [
                    'labels' => $sales_by_product_products,
                    'values' => $sales_by_product_values
                ],
                'purchases' => $purchases,
                'sales_table' => $collect_sales
            ]
        ];
        return response()->json($this->response);
    }

    public function addValue($date_begin, $date_end, $sales)
    {
        return round($sales->whereBetween('created_at', [$date_begin, $date_end])->sum('amount_paid'), 2);
    }
}
