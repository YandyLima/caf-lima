<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Sale extends Model
{
    use HasFactory, Searchable;

    protected $table = 'sales';

    protected $fillable = [
        'status',
        'tracking',
        'amount_paid',
//        'transaction_number',
        'payment_type',
        'authorization_number',
        'user_id',
    ];

    protected $searchableFields = ['*'];

    public function sale_details(): HasMany
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'id');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function bill(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
