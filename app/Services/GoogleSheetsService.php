<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    private $client;
    private $service;
    private $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google_sheets.spreadsheet_id');
        $this->client = new Client();
        $this->client->setApplicationName('Food Tracker');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(storage_path('credentials.json'));
        $this->service = new Sheets($this->client);
    }

    public function getIngredientsSheet()
    {
        return $this->getSheetData('Ingredients');
    }

    public function getRecipesSheet()
    {
        return $this->getSheetData('Recipes');
    }

    public function getFoodLogSheet()
    {
        return $this->getSheetData('FoodLog');
    }

    private function getSheetData($sheetName)
    {
        $range = $sheetName;
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return $response->getValues();
    }
} 