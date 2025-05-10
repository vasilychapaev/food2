<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodTrackerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tracker', [FoodTrackerController::class, 'index']);
Route::get('/tracker/{date}', [FoodTrackerController::class, 'show']);
