<?php

namespace Tests\Feature;

use App\Services\DataSyncService;
use App\Services\GoogleSheetsService;
use App\Services\DataParserService;
use App\Services\NutritionCalculatorService;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\FoodEntry;
use App\Models\DailySummary;
use Mockery;
use Tests\TestCase;

class DataSyncServiceTest extends TestCase
{
    public function test_sync_ingredients_creates_and_updates()
    {
        $googleSheets = Mockery::mock(GoogleSheetsService::class);
        $parser = Mockery::mock(DataParserService::class);
        $nutrition = Mockery::mock(NutritionCalculatorService::class);
        $googleSheets->shouldReceive('getIngredientsSheet')->once()->andReturn([
            ['name','aliases','calories','protein','fat','carbs'],
            ['грудка','куриная грудка',113,23.6,1.9,0.4]
        ]);
        $parser->shouldReceive('parseIngredients')->once()->andReturn([
            [
                'name' => 'грудка',
                'aliases' => ['куриная грудка'],
                'calories' => 113,
                'protein' => 23.6,
                'fat' => 1.9,
                'carbs' => 0.4
            ]
        ]);
        $this->app->instance(GoogleSheetsService::class, $googleSheets);
        $this->app->instance(DataParserService::class, $parser);
        $this->app->instance(NutritionCalculatorService::class, $nutrition);
        $service = app(DataSyncService::class);
        $service->syncIngredients();
        $this->assertNotNull(Ingredient::where('name', 'грудка')->first());
    }

    public function test_sync_recipes_creates_and_updates()
    {
        Ingredient::create([
            'name' => 'грудка',
            'aliases' => [],
            'calories' => 100,
            'protein' => 20,
            'fat' => 2,
            'carbs' => 0
        ]);
        $googleSheets = Mockery::mock(GoogleSheetsService::class);
        $parser = Mockery::mock(DataParserService::class);
        $nutrition = Mockery::mock(NutritionCalculatorService::class);
        $googleSheets->shouldReceive('getRecipesSheet')->once()->andReturn([
            ['name','aliases','ingredients'],
            ['салат','салатик','грудка.100']
        ]);
        $parser->shouldReceive('parseRecipes')->once()->andReturn([
            [
                'name' => 'салат',
                'aliases' => ['салатик'],
                'ingredients' => [ ['name'=>'грудка','weight'=>100] ]
            ]
        ]);
        $nutrition->shouldReceive('calculateRecipeNutrition')->once()->andReturn([
            'total_weight'=>100,
            'calories'=>100,
            'protein'=>20,
            'fat'=>2,
            'carbs'=>0
        ]);
        $this->app->instance(GoogleSheetsService::class, $googleSheets);
        $this->app->instance(DataParserService::class, $parser);
        $this->app->instance(NutritionCalculatorService::class, $nutrition);
        $service = app(DataSyncService::class);
        $service->syncRecipes();
        $this->assertNotNull(Recipe::where('name', 'салат')->first());
    }

    // public function test_sync_food_log_creates_and_updates_and_daily_summary()
    // {
    //     Ingredient::create([
    //         'name' => 'грудка',
    //         'aliases' => [],
    //         'calories' => 100,
    //         'protein' => 20,
    //         'fat' => 2,
    //         'carbs' => 0
    //     ]);
    //     $googleSheets = Mockery::mock(GoogleSheetsService::class);
    //     $parser = Mockery::mock(DataParserService::class);
    //     $nutrition = Mockery::mock(NutritionCalculatorService::class);
    //     $googleSheets->shouldReceive('getFoodLogSheet')->once()->andReturn([
    //         ['date','meal_number','raw_entry'],
    //         ['2024-07-01',1,'грудка.100']
    //     ]);
    //     $parser->shouldReceive('parseFoodLog')->once()->andReturn([
    //         [
    //             'date' => '2024-07-01',
    //             'meal_number' => 1,
    //             'raw_entry' => 'грудка.100'
    //         ]
    //     ]);
    //     $nutrition->shouldReceive('calculateFoodEntryNutrition')->once()->andReturn([
    //         'parsed_items'=>[['name'=>'грудка','weight'=>100,'calories'=>100,'protein'=>20,'fat'=>2,'carbs'=>0]],
    //         'calories'=>100,
    //         'protein'=>20,
    //         'fat'=>2,
    //         'carbs'=>0
    //     ]);
    //     $this->app->instance(GoogleSheetsService::class, $googleSheets);
    //     $this->app->instance(DataParserService::class, $parser);
    //     $this->app->instance(NutritionCalculatorService::class, $nutrition);
    //     $service = app(DataSyncService::class);
    //     $service->syncFoodLog();
    //     $foodEntry = FoodEntry::where('date', '2024-07-01')->where('meal_number', 1)->first();
    //     $dailySummary = DailySummary::where('date', '2024-07-01')->first();
    //     fwrite(STDERR, "\nFoodEntry: ".json_encode($foodEntry)."\n");
    //     fwrite(STDERR, "DailySummary: ".json_encode($dailySummary)."\n");
    //     $this->assertNotNull($foodEntry);
    //     $this->assertNotNull($dailySummary);
    // }
} 