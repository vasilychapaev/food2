<?php

namespace Tests\Unit;

use App\Services\NutritionCalculatorService;
use App\Services\DataParserService;
use PHPUnit\Framework\TestCase;

class NutritionCalculatorServiceTest extends TestCase
{
    private NutritionCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NutritionCalculatorService(new DataParserService());
    }

    public function test_calculate_recipe_nutrition_basic()
    {
        $ingredients = [
            [
                'name' => 'грудка',
                'aliases' => ['куриная грудка'],
                'calories' => 113,
                'protein' => 23.6,
                'fat' => 1.9,
                'carbs' => 0.4
            ],
            [
                'name' => 'гречка',
                'aliases' => [],
                'calories' => 313,
                'protein' => 12.6,
                'fat' => 3.3,
                'carbs' => 62.1
            ]
        ];
        $recipe = [
            'name' => 'гречка с грудкой',
            'aliases' => [],
            'ingredients' => [
                ['name' => 'грудка', 'weight' => 100],
                ['name' => 'гречка', 'weight' => 50],
            ]
        ];
        $result = $this->service->calculateRecipeNutrition($recipe, $ingredients);
        $this->assertEquals(100, $result['total_weight']);
        $this->assertEqualsWithDelta(113 + 313 * 0.5, $result['calories'], 0.01);
        $this->assertEqualsWithDelta(23.6 + 12.6 * 0.5, $result['protein'], 0.01);
        $this->assertEqualsWithDelta(1.9 + 3.3 * 0.5, $result['fat'], 0.01);
        $this->assertEqualsWithDelta(0.4 + 62.1 * 0.5, $result['carbs'], 0.01);
    }

    public function test_calculate_recipe_nutrition_skips_unknown()
    {
        $ingredients = [
            [
                'name' => 'грудка',
                'aliases' => [],
                'calories' => 100,
                'protein' => 20,
                'fat' => 2,
                'carbs' => 0
            ]
        ];
        $recipe = [
            'name' => 'микс',
            'aliases' => [],
            'ingredients' => [
                ['name' => 'грудка', 'weight' => 100],
                ['name' => 'неизвестно', 'weight' => 50],
            ]
        ];
        $result = $this->service->calculateRecipeNutrition($recipe, $ingredients);
        $this->assertEquals(100, $result['total_weight']);
        $this->assertEqualsWithDelta(100, $result['calories'], 0.01);
    }

    public function test_calculate_food_entry_nutrition_ingredients_only()
    {
        $ingredients = [
            [
                'name' => 'грудка',
                'aliases' => ['куриная грудка'],
                'calories' => 113,
                'protein' => 23.6,
                'fat' => 1.9,
                'carbs' => 0.4
            ]
        ];
        $recipes = [];
        $rawEntry = 'грудка.200';
        $result = $this->service->calculateFoodEntryNutrition($rawEntry, $ingredients, $recipes);
        $this->assertEqualsWithDelta(113 * 2, $result['calories'], 0.01);
        $this->assertEqualsWithDelta(23.6 * 2, $result['protein'], 0.01);
    }

    public function test_calculate_food_entry_nutrition_with_recipe()
    {
        $ingredients = [
            [
                'name' => 'грудка',
                'aliases' => [],
                'calories' => 100,
                'protein' => 20,
                'fat' => 2,
                'carbs' => 0
            ]
        ];
        $recipes = [
            [
                'name' => 'салат',
                'aliases' => ['салатик'],
                'total_weight' => 100,
                'calories' => 200,
                'protein' => 10,
                'fat' => 5,
                'carbs' => 30
            ]
        ];
        $rawEntry = 'салат.50';
        $result = $this->service->calculateFoodEntryNutrition($rawEntry, $ingredients, $recipes);
        $this->assertEqualsWithDelta(100, $result['calories'], 0.01);
        $this->assertEqualsWithDelta(5, $result['protein'], 0.01);
        $this->assertEqualsWithDelta(2.5, $result['fat'], 0.01);
        $this->assertEqualsWithDelta(15, $result['carbs'], 0.01);
    }

    public function test_calculate_food_entry_nutrition_mixed()
    {
        $ingredients = [
            [
                'name' => 'грудка',
                'aliases' => [],
                'calories' => 100,
                'protein' => 20,
                'fat' => 2,
                'carbs' => 0
            ]
        ];
        $recipes = [
            [
                'name' => 'салат',
                'aliases' => [],
                'total_weight' => 100,
                'calories' => 200,
                'protein' => 10,
                'fat' => 5,
                'carbs' => 30
            ]
        ];
        $rawEntry = 'грудка.100 - салат.50';
        $result = $this->service->calculateFoodEntryNutrition($rawEntry, $ingredients, $recipes);
        $this->assertEqualsWithDelta(100 + 100, $result['calories'], 0.01);
    }

    public function test_find_ingredient_by_alias()
    {
        $ingredients = [
            [
                'name' => 'грудка',
                'aliases' => ['куриная грудка', 'курица'],
                'calories' => 100,
                'protein' => 20,
                'fat' => 2,
                'carbs' => 0
            ]
        ];
        $method = new \ReflectionMethod($this->service, 'findIngredientByName');
        $method->setAccessible(true);
        $found = $method->invoke($this->service, 'курица', $ingredients);
        $this->assertNotNull($found);
        $this->assertEquals('грудка', $found['name']);
    }

    public function test_find_recipe_by_alias()
    {
        $recipes = [
            [
                'name' => 'салат',
                'aliases' => ['салатик', 'овощной'],
                'total_weight' => 100,
                'calories' => 200,
                'protein' => 10,
                'fat' => 5,
                'carbs' => 30
            ]
        ];
        $method = new \ReflectionMethod($this->service, 'findRecipeByName');
        $method->setAccessible(true);
        $found = $method->invoke($this->service, 'овощной', $recipes);
        $this->assertNotNull($found);
        $this->assertEquals('салат', $found['name']);
    }
} 