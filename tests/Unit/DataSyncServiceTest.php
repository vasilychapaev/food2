<?php

namespace Tests\Unit;

use App\Services\DataSyncService;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\FoodEntry;
use App\Models\DailySummary;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;
use Mockery;

class DataSyncServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_sync_ingredients_creates_and_updates()
    {
        $googleSheets = Mockery::mock('App\Services\GoogleSheetsService');
        $parser = Mockery::mock('App\Services\DataParserService');
        $nutrition = Mockery::mock('App\Services\NutritionCalculatorService');
        $googleSheets->shouldReceive('getIngredientsSheet')->once()->andReturn([['name','aliases','calories','protein','fat','carbs'],['грудка','куриная грудка',113,23.6,1.9,0.4]]);
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
        Ingredient::shouldReceive('updateOrCreate')->once()->with(['name'=>'грудка'], Mockery::any());
        $service = new DataSyncService($googleSheets, $parser, $nutrition);
        $service->syncIngredients();
    }

    public function test_sync_recipes_creates_and_updates()
    {
        $googleSheets = Mockery::mock('App\Services\GoogleSheetsService');
        $parser = Mockery::mock('App\Services\DataParserService');
        $nutrition = Mockery::mock('App\Services\NutritionCalculatorService');
        $googleSheets->shouldReceive('getRecipesSheet')->once()->andReturn([['name','aliases','ingredients'],['салат','салатик','грудка.100']]);
        $parser->shouldReceive('parseRecipes')->once()->andReturn([
            [
                'name' => 'салат',
                'aliases' => ['салатик'],
                'ingredients' => [ ['name'=>'грудка','weight'=>100] ]
            ]
        ]);
        Ingredient::shouldReceive('all')->once()->andReturn(collect([['name'=>'грудка','aliases'=>[],'calories'=>100,'protein'=>20,'fat'=>2,'carbs'=>0]]));
        $nutrition->shouldReceive('calculateRecipeNutrition')->once()->andReturn([
            'total_weight'=>100,
            'calories'=>100,
            'protein'=>20,
            'fat'=>2,
            'carbs'=>0
        ]);
        Recipe::shouldReceive('updateOrCreate')->once()->with(['name'=>'салат'], Mockery::any());
        $service = new DataSyncService($googleSheets, $parser, $nutrition);
        $service->syncRecipes();
    }

    public function test_sync_food_log_creates_and_updates_and_daily_summary()
    {
        $googleSheets = Mockery::mock('App\Services\GoogleSheetsService');
        $parser = Mockery::mock('App\Services\DataParserService');
        $nutrition = Mockery::mock('App\Services\NutritionCalculatorService');
        $googleSheets->shouldReceive('getFoodLogSheet')->once()->andReturn([['date','meal_number','raw_entry'],['2024-07-01',1,'грудка.100']]);
        $parser->shouldReceive('parseFoodLog')->once()->andReturn([
            [
                'date' => '2024-07-01',
                'meal_number' => 1,
                'raw_entry' => 'грудка.100'
            ]
        ]);
        Ingredient::shouldReceive('all')->once()->andReturn(collect([['name'=>'грудка','aliases'=>[],'calories'=>100,'protein'=>20,'fat'=>2,'carbs'=>0]]));
        Recipe::shouldReceive('all')->once()->andReturn(collect([]));
        $nutrition->shouldReceive('calculateFoodEntryNutrition')->once()->andReturn([
            'parsed_items'=>[['name'=>'грудка','weight'=>100,'calories'=>100,'protein'=>20,'fat'=>2,'carbs'=>0]],
            'calories'=>100,
            'protein'=>20,
            'fat'=>2,
            'carbs'=>0
        ]);
        FoodEntry::shouldReceive('updateOrCreate')->once()->with(
            ['date'=>'2024-07-01','meal_number'=>1,'raw_entry'=>'грудка.100'],
            Mockery::any()
        );
        // DailySummary update
        FoodEntry::shouldReceive('where')->once()->with('date','2024-07-01')->andReturnSelf();
        FoodEntry::shouldReceive('get')->once()->andReturn(collect([
            (object)[
                'meal_number'=>1,
                'raw_entry'=>'грудка.100',
                'parsed_items'=>[['name'=>'грудка','weight'=>100,'calories'=>100,'protein'=>20,'fat'=>2,'carbs'=>0]],
                'calories'=>100,
                'protein'=>20,
                'fat'=>2,
                'carbs'=>0
            ]
        ]));
        DailySummary::shouldReceive('updateOrCreate')->once()->with(
            ['date'=>'2024-07-01'],
            Mockery::on(function($data) {
                return $data['total_calories'] === 100 && $data['total_protein'] === 20;
            })
        );
        $service = new DataSyncService($googleSheets, $parser, $nutrition);
        $service->syncFoodLog();
    }
} 