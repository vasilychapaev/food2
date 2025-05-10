<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Recipe;

class NutritionCalculatorService
{
    private $dataParserService;

    public function __construct(DataParserService $dataParserService)
    {
        $this->dataParserService = $dataParserService;
    }

    public function calculateRecipeNutrition(array $recipe, array $ingredients)
    {
        $totalWeight = 0;
        $totalCalories = 0;
        $totalProtein = 0;
        $totalFat = 0;
        $totalCarbs = 0;

        foreach ($recipe['ingredients'] as $recipeIngredient) {
            $ingredient = $this->findIngredientByName($recipeIngredient['name'], $ingredients);
            if (!$ingredient) {
                continue;
            }
            $weight = $recipeIngredient['weight'];
            $totalWeight += $weight;
            $totalCalories += ($ingredient['calories'] * $weight / 100);
            $totalProtein += ($ingredient['protein'] * $weight / 100);
            $totalFat += ($ingredient['fat'] * $weight / 100);
            $totalCarbs += ($ingredient['carbs'] * $weight / 100);
        }

        return [
            'total_weight' => $totalWeight,
            'calories' => $totalCalories,
            'protein' => $totalProtein,
            'fat' => $totalFat,
            'carbs' => $totalCarbs
        ];
    }

    public function calculateFoodEntryNutrition(string $rawEntry, array $ingredients, array $recipes)
    {
        $parsedItems = $this->dataParserService->parseFoodEntryItems($rawEntry);
        $totalCalories = 0;
        $totalProtein = 0;
        $totalFat = 0;
        $totalCarbs = 0;
        $processedItems = [];

        foreach ($parsedItems as $item) {
            $itemName = $item['name'];
            $weight = $item['weight'];

            $recipe = $this->findRecipeByName($itemName, $recipes);
            if ($recipe && $recipe['total_weight'] > 0) {
                $ratio = $weight / $recipe['total_weight'];
                $calories = $recipe['calories'] * $ratio;
                $protein = $recipe['protein'] * $ratio;
                $fat = $recipe['fat'] * $ratio;
                $carbs = $recipe['carbs'] * $ratio;
                $processedItems[] = [
                    'type' => 'recipe',
                    'name' => $recipe['name'],
                    'weight' => $weight,
                    'calories' => $calories,
                    'protein' => $protein,
                    'fat' => $fat,
                    'carbs' => $carbs
                ];
            } else {
                $ingredient = $this->findIngredientByName($itemName, $ingredients);
                if ($ingredient) {
                    $calories = $ingredient['calories'] * $weight / 100;
                    $protein = $ingredient['protein'] * $weight / 100;
                    $fat = $ingredient['fat'] * $weight / 100;
                    $carbs = $ingredient['carbs'] * $weight / 100;
                    $processedItems[] = [
                        'type' => 'ingredient',
                        'name' => $ingredient['name'],
                        'weight' => $weight,
                        'calories' => $calories,
                        'protein' => $protein,
                        'fat' => $fat,
                        'carbs' => $carbs
                    ];
                } else {
                    $processedItems[] = [
                        'type' => 'unknown',
                        'name' => $itemName,
                        'weight' => $weight,
                        'calories' => 0,
                        'protein' => 0,
                        'fat' => 0,
                        'carbs' => 0
                    ];
                }
            }
            $totalCalories += $processedItems[count($processedItems) - 1]['calories'];
            $totalProtein += $processedItems[count($processedItems) - 1]['protein'];
            $totalFat += $processedItems[count($processedItems) - 1]['fat'];
            $totalCarbs += $processedItems[count($processedItems) - 1]['carbs'];
        }

        return [
            'parsed_items' => $processedItems,
            'calories' => $totalCalories,
            'protein' => $totalProtein,
            'fat' => $totalFat,
            'carbs' => $totalCarbs
        ];
    }

    private function findIngredientByName(string $name, array $ingredients)
    {
        $name = strtolower(trim($name));
        foreach ($ingredients as $ingredient) {
            if (strtolower($ingredient['name']) === $name) {
                return $ingredient;
            }
            foreach ($ingredient['aliases'] as $alias) {
                if (strtolower(trim($alias)) === $name) {
                    return $ingredient;
                }
            }
        }
        return null;
    }

    private function findRecipeByName(string $name, array $recipes)
    {
        $name = strtolower(trim($name));
        foreach ($recipes as $recipe) {
            if (strtolower($recipe['name']) === $name) {
                return $recipe;
            }
            foreach ($recipe['aliases'] as $alias) {
                if (strtolower(trim($alias)) === $name) {
                    return $recipe;
                }
            }
        }
        return null;
    }
} 