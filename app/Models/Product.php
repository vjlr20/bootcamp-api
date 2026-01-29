<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    // Campos que se pueden modificar o asignar
    protected $fillable = [
        'slug',
        'name',
        'description',
        'product_image',
        'price',
        'stock',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
