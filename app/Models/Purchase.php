<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Purchase extends Model
{
    use HasFactory, Searchable;

    protected $table = 'purchases';

    protected $fillable = [
        'description',
        'price',
        'weight',
        'user_id',
    ];

    protected array $searchableFields = ['*'];

    public function customer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
