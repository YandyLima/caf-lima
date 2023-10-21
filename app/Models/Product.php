<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'stock',
        'price',
        'weight',
        'active',
    ];

    protected $searchableFields = ['*'];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function sale_details(): HasMany
    {
        return $this->hasMany(SaleDetail::class, 'product_id', 'id');
    }
}
