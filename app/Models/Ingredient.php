<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Ingredient extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ingredients';

    protected $fillable = [
        'name',
        'aliases',
        'calories',
        'protein',
        'fat',
        'carbs'
    ];

    protected $casts = [
        'aliases' => 'array',
        'calories' => 'float',
        'protein' => 'float',
        'fat' => 'float',
        'carbs' => 'float'
    ];
} 