<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;
use App\Services\DataParserService;

class TestSheets extends Command
{
    protected $signature = 'sheets';
    protected $description = 'Тест Google Sheets API и парсера';

    public function handle(GoogleSheetsService $sheets, DataParserService $parser)
    {
        $this->info('Получаю данные из Google Sheets...');
        $ingredientsRaw = $sheets->getIngredientsSheet();
        // dd($ingredientsRaw);
        $recipesRaw = $sheets->getRecipesSheet();
        // dd($recipesRaw);
        $foodLogRaw = $sheets->getFoodLogSheet();
        // dd($foodLogRaw);

        $this->info('Парсю данные...');
        $ingredients = $parser->parseIngredients($ingredientsRaw);
        // dd($ingredients);
        $recipes = $parser->parseRecipes($recipesRaw);
        $foodLog = $parser->parseFoodLog($foodLogRaw);

        $this->info('Пример ингредиента:');
        dump($ingredientsRaw[0] ?? 'нет данных');
        dump($ingredients[0] ?? 'нет данных');

        $this->info('Пример рецепта:');
        dump($recipesRaw[0] ?? 'нет данных');
        dump($recipes[0] ?? 'нет данных');

        $this->info('Пример food log:');
        dump($foodLogRaw[0] ?? 'нет данных');
        dump($foodLog[0] ?? 'нет данных');
    }
}