<?php

namespace App\Jobs;

use App\Mail\BillingEmail;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MailBillingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public object $data;

    /**
     * Create a new job instance.
     */
    public function __construct(object $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $sale = $this->data;

            $settings = Setting::whereBetween('key', [6, 8])->get();
            // Agrupar los resultados por clave 'key'
            $groupedSettings = $settings->groupBy('key');
            // Asignar los valores a las variables correspondientes
            $phone = $groupedSettings[6][0]->value ?? '';
            $nit = $groupedSettings[7][0]->value ?? '';
            $address = $groupedSettings[8][0]->value ?? '';

            //Generar y almacenar PDF
            $pdf = Pdf::loadView('admin.sales.bill', compact('sale', 'phone', 'nit', 'address'));
            $name = "$sale->authorization_number.pdf";
            $pdf->save(storage_path("app/public/bills/$name"));
            $sale->bill()->create([
                'url' => "bills/$name",
                'type' => 1
            ]);

            //URL para descarga en boton
            $file = $sale->bill()->first();
            $url = asset(Storage::url($file->url));
            $sale->url = $url;

            //Enviar correo
            $fileName = "storage/bills/$name";
            $mail = new BillingEmail($sale, $fileName);
            Mail::to($sale->customer->email)->send($mail);
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
    }
}
