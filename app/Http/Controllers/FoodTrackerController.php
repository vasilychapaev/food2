<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailySummary;
use Illuminate\Support\Facades\Log;

class FoodTrackerController extends Controller
{
    public function index()
    {
        Log::info('Открыт список дневных отчётов');
        $summaries = DailySummary::orderBy('date', 'desc')->get();
        return view('tracker.index', compact('summaries'));
    }

    public function show($date)
    {
        Log::info('Открыта деталка по дню', ['date' => $date]);
        $summary = DailySummary::where('date', $date)->firstOrFail();
        return view('tracker.show', compact('summary'));
    }
} 