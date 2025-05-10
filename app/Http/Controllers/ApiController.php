<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\FoodEntry;
use App\Models\DailySummary;

class ApiController extends Controller
{
    public function ingredients()
    {
        return response()->json(Ingredient::all());
    }

    public function recipes()
    {
        return response()->json(Recipe::all());
    }

    public function foodLog(Request $request)
    {
        $date = $request->query('date');
        $query = FoodEntry::query();
        if ($date) {
            $query->where('date', $date);
        }
        return response()->json($query->get());
    }

    public function dailySummary(Request $request)
    {
        $date = $request->query('date');
        $query = DailySummary::query();
        if ($date) {
            $query->where('date', $date);
        }
        return response()->json($query->get());
    }
} 