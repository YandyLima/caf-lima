<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    public function list(Request $request)
    {
        $users = User::whereRaw(true);
        $this->response = $this->listData($request, $users);
        return response()->json($this->response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name'     => 'required|string',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required',
                'address'  => 'required|string',
                'phone'    => 'required',
                'type'     => 'required',
                'account_number'  => 'nullable|numeric',
                'nit'      => 'nullable|numeric|min_digits:6|max_digits:9',
            ]);
            $validate['password'] = Hash::make($request->password);
            User::create($validate);
            $this->response_type = 'success';
            $this->message = 'Se ha creado el usuario';
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
        $user = User::with('images')->find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validate = $request->validate([
                'name'     => 'required|string',
                'email'    => 'required|email|unique:users,email,'.$id,
                'address'  => 'required|string',
                'phone'    => 'required',
                'account_number'  => 'nullable|numeric',
                'nit'      => 'nullable|numeric|min_digits:6|max_digits:9',
            ]);
            if ($request->password) {
                $validate['password'] = Hash::make($request->password);
            }
            $user = User::find($id);
            $user->update($validate);
            $this->response_type = 'success';
            $this->message = 'Se ha actualizado el usuario';
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
            $user = User::find($id);
            if ($user) {
                foreach ($user->sales as $sale) {
                    foreach ($sale->sale_details as $detail) {
                        $detail->delete();
                    }
                    if ($sale->bill) {
                        Storage::disk('public')->delete($sale->bill->url);
                        $sale->bill->delete();
                    }
                    $sale->delete();
                }

                foreach ($user->images as $image) {
                    Storage::disk('public')->delete($image->url);
                    $image->delete();
                }

                foreach ($user->purchases as $purchase) {
                    $purchase->delete();
                }

                $user->delete();

                $this->status_code = 200;
                $this->message = 'Usuario eliminado junto con sus registros asociados.';
            } else {
                $this->message = 'El usuario no existe';
            }
        } catch (\Exception $e) {
            $this->message = 'El usuario no se puede eliminar';
        } finally {
            $this->response = [
                'message' => $this->message
            ];
        }
        return response()->json($this->response, $this->status_code);
    }

    public function image(Request $request, User $user)
    {
        try{
            $name = $request->file('file')->getClientOriginalName();
            $this->message = 'Imagen guardada';
            $this->status_code = 200;

            $image = $user->images()->where('type', 1)->first();
            if (!$image) {
                $this->saveImage($user->images(), $request, $name);
            } else {
                $this->message = 'Elimina la imagen actual para agregar otra';
                $this->status_code = 400;
            }

        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
        }
        return response()->json($this->message, $this->status_code);
    }

    public function deleteImage(Request $request)
    {
        try {
            $url = Image::where('url', 'images/'.$request->name)->get();
            if (count($url) == 1) {
                Storage::disk('public')->delete('images/'.$request->name);
            }
            $user = User::where('id', $request->id)->first();
            $image = $user->images()->where('url', 'images/'.$request->name)->first();
            $image->delete();
            return response()->json('Éxito');
        }catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
    public function showImage(User $id): JsonResponse
    {
        $image = $id->images()->where('type', 1)->first();
        if ($image) {
            $contents = [
                'name'  => basename($image->url),
                'size'  => Storage::disk('public')->size($image->url),
                'route' => asset(Storage::url($image->url)),
            ];
            return response()->json($contents);
        } else {
            return response()->json([], 500);
        }
    }
}
