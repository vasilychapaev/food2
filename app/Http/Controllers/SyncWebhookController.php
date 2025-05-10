<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataSyncService;

class SyncWebhookController extends Controller
{
    public function sync(Request $request, DataSyncService $syncService)
    {
        $syncService->syncAll();
        return response()->json(['status' => 'ok', 'message' => 'Синхронизация запущена']);
    }
} 