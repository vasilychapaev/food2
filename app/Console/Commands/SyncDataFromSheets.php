<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataSyncService;

class SyncDataFromSheets extends Command
{
    protected $signature = 'sync:sheets';
    protected $description = 'Синхронизировать данные из Google Sheets в MongoDB';

    public function handle(DataSyncService $syncService)
    {
        $this->info('Старт синхронизации...');
        $syncService->syncAll();
        $this->info('Синхронизация завершена!');
        return 0;
    }
} 