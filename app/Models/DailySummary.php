<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DailySummary extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'daily_summaries';

    protected $fillable = [
        'date',
        'meals',
        'total_calories',
        'total_protein',
        'total_fat',
        'total_carbs'
    ];

    protected $casts = [
        'meals' => 'array',
        'total_calories' => 'float',
        'total_protein' => 'float',
        'total_fat' => 'float',
        'total_carbs' => 'float'
    ];
} 