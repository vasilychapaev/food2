<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;
use App\Services\DataParserService;

class TestSheets extends Command
{
    protected $signature = 'test:sheets';
    protected $description = 'Тест Google Sheets API и парсера';

    public function handle(GoogleSheetsService $sheets, DataParserService $parser)
    {
        $this->info('Получаю данные из Google Sheets...');
        $ingredientsRaw = $sheets->getIngredientsSheet();
        $recipesRaw = $sheets->getRecipesSheet();
        $foodLogRaw = $sheets->getFoodLogSheet();

        $this->info('Парсю данные...');
        $ingredients = $parser->parseIngredients($ingredientsRaw);
        $recipes = $parser->parseRecipes($recipesRaw);
        $foodLog = $parser->parseFoodLog($foodLogRaw);

        $this->info('Пример ингредиента:');
        dump($ingredients[0] ?? 'нет данных');

        $this->info('Пример рецепта:');
        dump($recipes[0] ?? 'нет данных');

        $this->info('Пример food log:');
        dump($foodLog[0] ?? 'нет данных');
    }
}