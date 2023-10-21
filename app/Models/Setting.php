<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'settings';
    protected $fillable = [
        'key',
        'name',
        'value',
    ];
}
