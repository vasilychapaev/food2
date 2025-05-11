<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataSyncService;
use Illuminate\Support\Facades\Log;

class SyncWebhookController extends Controller
{
    public function sync(Request $request, DataSyncService $syncService)
    {
        try {
            $syncService->syncAll();
            Log::info('Webhook sync успешно выполнен');
            return response()->json(['status' => 'ok', 'message' => 'Синхронизация запущена']);
        } catch (\Throwable $e) {
            Log::error('Ошибка webhook sync: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
} 