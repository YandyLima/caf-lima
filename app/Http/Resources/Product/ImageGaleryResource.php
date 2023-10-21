<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageGaleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product_image_gallery',
            'id' => $this->resource->getRouteKey(),
            'attributes' => [
                'product_id' => $this->resource->imageable_id,
                'url' => Storage::disk('public')->url($this->resource->url)
            ]
        ];
    }
}
