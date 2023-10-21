<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $settings =  $this->settings_key;
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list(Request $request)
    {
        $settings = Setting::whereRaw(true);
        $this->response = $this->listData($request, $settings);
        return response()->json($this->response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'key' => ['required', 'integer'],
                'name' => ['required', 'string'],
                'value' => ['nullable', 'string'],
            ]);
            $setting = Setting::create($validate);

            $this->response_type = 'success';
            $this->message = 'Se ha creado la configuracion';
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
        }
        return redirect()->back()->with($this->response_type, $this->message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $setting = Setting::find($id);
        return response()->json($setting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validate = $request->validate([
                'key' => ['required'],
                'name' => ['required', 'string'],
                'value' => ['required', 'string'],
            ]);
            $setting = Setting::find($id);
            $setting->update($validate);
            $this->response_type = 'success';
            $this->message = 'Se ha actualizado la configuracion';
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
        }
        return redirect()->back()->with($this->response_type, $this->message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $setting = Setting::find($id);
            if ($setting) {
                $setting->delete();
                $this->message = 'Configuracion eliminada';
            } else {
                $this->message = 'La configuracion no existe';
            }
            $this->status_code = 200;

        } catch (Exception) {
            $this->message = 'La configuracion no se puede eliminar';
        } finally {
            $response = [
                'message' => $this->message
            ];
        }
        return response()->json($response, $this->status_code);
    }
}
