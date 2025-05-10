<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Recipe extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'recipes';

    protected $fillable = [
        'name',
        'aliases',
        'ingredients',
        'total_weight',
        'calories',
        'protein',
        'fat',
        'carbs'
    ];

    protected $casts = [
        'aliases' => 'array',
        'ingredients' => 'array',
        'total_weight' => 'float',
        'calories' => 'float',
        'protein' => 'float',
        'fat' => 'float',
        'carbs' => 'float'
    ];
} 