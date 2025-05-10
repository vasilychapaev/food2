<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FoodEntry extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'food_entries';

    protected $fillable = [
        'date',
        'meal_number',
        'raw_entry',
        'parsed_items',
        'calories',
        'protein',
        'fat',
        'carbs'
    ];

    protected $casts = [
        'meal_number' => 'integer',
        'parsed_items' => 'array',
        'calories' => 'float',
        'protein' => 'float',
        'fat' => 'float',
        'carbs' => 'float'
    ];
} 