<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Recipe extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'recipes';

    protected $fillable = [
        'name',
        'ingredients',
        'comments',
        'nutrients',
    ];

    protected $casts = [
        'name' => 'string',
        'ingredients' => 'string',
        'comments' => 'string',
        'nutrients' => 'string',
    ];
} 