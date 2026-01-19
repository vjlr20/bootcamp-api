<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // The table associated with the model.
    protected $table = 'categories';

    // The attributes that are required.
    protected $fillable = [
        'slug',
        'name',
        'description',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
