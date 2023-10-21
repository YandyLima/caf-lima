<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ImageGaleryResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display the view of the resource.
     */
    public function index()
    {
        return view('admin.products.index');
    }

    /**
     * Return a listing of the resource.
     */
    public function list(Request $request)
    {
        $products = Product::whereRaw(true);
        $this->response = $this->listData($request, $products);
        return response()->json($this->response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $validate = $request->validated();
            Product::create($validate);
            $this->response_type = 'success';
            $this->message = 'Se ha creado el producto';
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
        $product = Product::with('images')->find($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try {
            $validate = $request->validated();
            $product = Product::find($id);
            $product->update($validate);
            $this->response_type = 'success';
            $this->message = 'Se ha actualizado el producto';
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
            $product = Product::find($id);
            if ($product) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->url);
                    $image->delete();
                }

                foreach ($product->sale_details as $detail) {
                    $detail->delete();
                }

                $product->delete();

                $this->status_code = 200;
                $this->message = 'Se ha eliminado el producto y sus registros asociados.';
            } else {
                $this->message = 'El producto no existe';
            }
        } catch (\Exception $e) {
            $this->message = 'No se ha podido eliminar el producto';
        } finally {
            $this->response = [
                'message' => $this->message
            ];
        }
        return response()->json($this->response, $this->status_code);
    }

    public function imageGallery(Product $product)
    {
        return ImageGaleryResource::collection($product->images->where('type', 2));
    }

    public function images(Request $request, Product $product)
    {
        try{
            $name = $request->file('file')->getClientOriginalName();
            $this->message = 'Imagen guardada';
            $this->status_code = 200;
            if ($request->type == 1) {
                $image = $product->images()->where('type', 1)->first();
                if (!$image) {
                    $this->saveImage($product->images(), $request, $name);
                } else {
                    $this->message = 'Elimina la imagen actual para agreagar otra';
                    $this->status_code = 400;
                }
            } else {
                $this->saveImage($product->images(), $request, $name);
            }
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
        }
        return response()->json($this->message, $this->status_code);
    }

    public function deleteImages(Request $request)
    {
        try{
            $total_images = Image::where('url', 'images/'.$request->name)->count();
            if ($total_images == 1) {
                Storage::disk('public')->delete('images/'.$request->name);
            }
            $product = Product::where('id', $request->id)->first();
            $image = $product->images()->where('url', 'images/'.$request->name)
                ->where('type', $request->type)
                ->first();
            $image->delete();
            return response()->json('Éxito');
        }catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
    public function showImages(Request $request, Product $id): JsonResponse
    {
        $images = $id->images()->where('type', $request->type)->get();
        $contents = [];
        if (count($images) > 0) {
            foreach ($images as $image) {
                $contents[] = [
                    'name'  => basename($image->url),
                    'size'  => Storage::disk('public')->size($image->url),
                    'route' => asset(Storage::url($image->url)),
                ];
            }
            return response()->json($contents);
        } else {
            return response()->json($contents, 500);
        }
    }
}
