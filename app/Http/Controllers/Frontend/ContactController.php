<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactEmail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        /*$settings = Setting::select('key', 'name', 'value')->get();

        $groupedSettings = $settings->groupBy('key');

        $collection = $groupedSettings->map(function ($group, $key) {
            $type = '';

            switch ($key) {
                case '1':
                    $type = 'Facebook';
                    $icon = '<i class="text-center fs-1 bi bi-facebook"></i>';
                    break;
                case '2':
                    $type = 'Instagram';
                    $icon = '<i class="text-center fs-1 bi bi-instagram"></i>';
                    break;
                case '3':
                    $type = 'Twitter';
                    $icon = '<i class="text-center fs-1 bi bi-twitter"></i>';
                    break;
                case '4':
                    $type = 'Tiktok';
                    $icon = '<i class="text-center fs-1 bi bi-tiktok"></i>';
                    break;
                case '5':
                    $type = 'Email';
                    $icon = '<i class="text-center fs-1 bi bi-envelope"></i>';
                    break;
                case '6':
                    $type = 'Teléfono';
                    $icon = '<i class="text-center fs-1 bi bi-telephone"></i>';
                    break;
            }

            $links = $group->map(function ($item, $key) {
                return [
                    'name' => $item->name,
                    'url' => $item->value,
                ];
            });

            return [
                'type' => $type,
                'links' => $links,
                'icon' => $icon,
            ];
        });*/

        $settings = Setting::select('key', 'name', 'value')->whereNotIn('key', [7])->get();

        $groupedSettings = $settings->groupBy('key');

        $collection = $groupedSettings->map(function ($group, $key) {
            $type = '';

            switch ($key) {
                case '1':
                    $type = 'Facebook';
                    $icon = '<i class="text-center fs-1 bi bi-facebook text-primary"></i>';
                    break;
                case '2':
                    $type = 'Instagram';
                    $icon = '<i class="text-center fs-1 bi bi-instagram text-danger"></i>';
                    break;
                case '3':
                    $type = 'Twitter';
                    $icon = '<i class="text-center fs-1 bi bi-twitter text-info"></i>';
                    break;
                case '4':
                    $type = 'Tiktok';
                    $icon = '<i class="text-center fs-1 bi bi-tiktok"></i>';
                    break;
                case '5':
                    $type = 'Email';
                    $icon = '<i class="text-center fs-1 bi bi-envelope"></i>';
                    break;
                case '6':
                    $type = 'Teléfono';
                    $icon = '<i class="text-center fs-1 bi bi-telephone"></i>';
                    break;
                case '8':
                    $type = 'Dirección';
                    $icon = '<i class="text-center fs-1 bi bi-geo-alt-fill text-danger"></i>';
                    break;
            }

            $links = $group->map(function ($item, $key) {
                return [
                    'name' => $item->name,
                    'url' => $item->value,
                ];
            });

            return (object)[
                'type' => $type,
                'links' => $links,
                'icon' => $icon ?? '',
            ];
        });

        return view('frontend.pages.contact', [
            'settings' => $collection
        ]);
    }

    public function sendEmail(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        try {
            $email = new ContactEmail($validate);
            Mail::to($request->email)->send($email);
            return response()->json('¡Gracias por contactarnos!');
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

}
