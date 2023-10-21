<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Purchase;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('type', 3)->get(); //Usuarios proveedores
        return view('admin.purchases.index', compact('users'));
    }

    public function list(Request $request)
    {
        $purchases = Purchase::whereRaw(true);
        $this->response = $this->listData($request, $purchases);

        $purchases = $this->response['data'];
        $data = [];
        foreach ($purchases as $purchase)
        {
            $data[] = [
                'id'          => $purchase->id,
                'description' => $purchase->description,
                'price'       => $purchase->price,
                'weight'      => $purchase->weight,
                'user_id'     => $purchase->customer->name
            ];
        }

        $response = [
            'recordsTotal'    => $this->response['recordsTotal'],
            'recordsFiltered' => $this->response['recordsFiltered'],
            'data'            => $data,
        ];
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'description' => 'required|string',
                'price' => 'required|numeric',
                'weight' => 'required|numeric',
                'user_id' => 'required',
            ]);
            Purchase::create($validate);
            $this->response_type = 'success';
            $this->message = 'Se ha creado el registro';
        } catch (Exception $exception) {
            $this->message = $exception->getMessage();
        }
        return redirect()->back()->with($this->response_type, $this->message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::with('image')->find($id);
        return response()->json($purchase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validate = $request->validate([
                'description' => 'required|string',
                'price' => 'required|numeric',
                'weight' => 'required|numeric',
                'user_id' => 'required',
            ]);
            $purchase = Purchase::find($id);
            $purchase->update($validate);
            $this->response_type = 'success';
            $this->message = 'Se ha actualizado el registro';
        } catch (Exception $exception) {
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
            $purchase = Purchase::find($id);
            if ($purchase) {
                $image = $purchase->image;
                $purchase->delete();
                if ($image) {
                    Storage::disk('public')->delete($image->url);
                    $image->delete();
                }
                $this->status_code = 200;
                $this->message = 'Registro eliminado';
            } else {
                $this->message = 'El registro no existe';
            }
        } catch (Exception) {
            $this->message = 'El registro no se puede eliminar';
        } finally {
            $this->response = [
                'message' => $this->message
            ];
        }
        return response()->json($this->response, $this->status_code);
    }

    public function image(Request $request, Purchase $purchase)
    {
        try {
            $name = $request->file('file')->getClientOriginalName();
            $this->message = 'Imagen guardada';
            $this->status_code = 200;

            $image = $purchase->image()->where('type', 1)->first();
            if (!$image) {
                $this->saveImage($purchase->image(), $request, $name);
            } else {
                $this->message = 'Elimina la imagen actual para agregar otra';
                $this->status_code = 400;
            }

        } catch (Exception $exception) {
            $this->message = $exception->getMessage();
        }
        return response()->json($this->message, $this->status_code);
    }

    public function deleteImage(Request $request)
    {
        try {
            $url = Image::where('url', 'images/' . $request->name)->get();
            if (count($url) == 1) {
                Storage::disk('public')->delete('images/' . $request->name);
            }
            $purchase = Purchase::where('id', $request->id)->first();
            $image = $purchase->image()->where('url', 'images/' . $request->name)->first();
            $image->delete();
            return response()->json('Éxito');
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function showImage(Purchase $id): JsonResponse
    {
        $image = $id->image()->where('type', 1)->first();
        if ($image) {
            $contents = [
                'name' => basename($image->url),
                'size' => Storage::disk('public')->size($image->url),
                'route' => asset(Storage::url($image->url)),
            ];
            return response()->json($contents);
        } else {
            return response()->json([], 500);
        }
    }
}

