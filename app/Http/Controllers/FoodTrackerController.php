<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailySummary;

class FoodTrackerController extends Controller
{
    public function index()
    {
        $summaries = DailySummary::orderBy('date', 'desc')->get();
        return view('tracker.index', compact('summaries'));
    }

    public function show($date)
    {
        $summary = DailySummary::where('date', $date)->firstOrFail();
        return view('tracker.show', compact('summary'));
    }
} 