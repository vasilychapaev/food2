<?php

use Illuminate\Support\Facades\Route;

// Здесь будут API endpoints для калорий/БЖУК 

Route::get('/ingredients', [\App\Http\Controllers\ApiController::class, 'ingredients']);
Route::get('/recipes', [\App\Http\Controllers\ApiController::class, 'recipes']);
Route::get('/food-log', [\App\Http\Controllers\ApiController::class, 'foodLog']);
Route::get('/daily-summary', [\App\Http\Controllers\ApiController::class, 'dailySummary']); 