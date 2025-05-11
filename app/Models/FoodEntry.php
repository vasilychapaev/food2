<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FoodEntry extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'food_entries';

    protected $fillable = [
        'date',
        'food_no',
        'food_record',
        'food_items',
        'json',
        'nutrition',
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