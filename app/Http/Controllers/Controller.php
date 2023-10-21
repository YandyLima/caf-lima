<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public int $status_code;
    public string $message;
    public array $response;
    public string $response_type;
    public array $settings_key;
    public array $payment_types;
    public function __construct()
    {
        $this->message = 'Ha ocurrido un error';
        $this->status_code = 400;
        $this->response_type = 'error';
        $this->response = [];
        $this->settings_key = [
            1 => 'Facebook',
            2 => 'Instagram',
            3 => 'Twitter',
            4 => 'Tiktok',
            5 => 'Email',
            6 => 'Telefono',
            7 => 'Nit',
            8 => 'Direccion',
        ];
        $this->payment_types = [
            1 => '<i class="fa-solid fa-money-bill-1-wave"></i> Depósito Bancario',
            2 => '<i class="fa-solid fa-money-check-dollar"></i> Transferencia',
            3 => '<i class="fa-solid fa-credit-card"></i> Tarjeta',
            4 => '<i class="fa-solid fa-credit-card"></i> Transferencia',
        ];
    }

    /**
     * Return data of model.
     */
    public function listData($request, $data) {
        $server_side = [
            'search'    => $request->search['value'] ?? '',
            'limit_val' => $request->length,
            'start_val' => $request->start,
            'order_val' => $request->columns[$request->order[0]['column']]['data'],
            'dir_val'   => $request->order[0]['dir'],
        ];

        $recordsTotal = $data->count();
        $filtered = $data->search($server_side['search'])->orderBy($server_side['order_val'], $server_side['dir_val']);
        $recordsFiltered = $filtered->count();
        $filtered_data = $filtered->offset($server_side['start_val'])->limit($server_side['limit_val'])->get();

        return [
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $filtered_data,
        ];
    }

    public function saveImage($model, $request, $name) {
        $model->create([
            'url'   => $request->file('file')->storeAs('images', $name, 'public'),
            'type'  => $request->type
        ]);
    }

    public function statuses()
    {
        return [
            0 => '<i class="fa-solid fa-upload"></i> Generado',
            1 => '<i class="fa-regular fa-clock"></i> Espera confirmación',
            2 => '<i class="fa-solid fa-dollar-sign"></i> Pago confirmado',
            3 => '<i class="fa-solid fa-spinner"></i> En proceso',
            4 => '<i class="fa-solid fa-truck"></i> Enviado',
            5 => '<i class="fa-solid fa-house-circle-check"></i> Entregado',
            6 => '<i class="fa-solid fa-circle-arrow-left"></i> Recibido Devolución'
        ];
    }

    public function updateStatus(object $sale, int $status)
    {
        $status_log = collect(json_decode($sale->tracking));
        $status_log[$status] = date('Y-m-d H:i:s');
        $sale->status = $status;
        $sale->tracking = json_encode($status_log);
        $sale->save();
        return response(null);
    }
}
