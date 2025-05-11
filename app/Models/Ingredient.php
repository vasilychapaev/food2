<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Ingredient extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ingredients';

    protected $fillable = [
        'name',
        'protein',
        'fat',
        'carbs',
        'calories',
        'nutrion100',
        'fatsecret_id',
        'comment',
        'name_full',
        'name_original',
        'comment',
    ];

    protected $casts = [
        'calories' => 'float',
        'protein' => 'float',
        'fat' => 'float',
        'carbs' => 'float'
    ];
} 