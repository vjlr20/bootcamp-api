<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'price',
        'stock',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
