<?php

namespace App\Services;

class DataParserService
{
    public function parseIngredients(array $rawData)
    {
        $ingredients = [];
        $headers = array_shift($rawData);
        foreach ($rawData as $row) {
            if (count($row) < 5) {   // 5  [name-prot-fat-car-cal/...]
                continue;
            }
            $ingredient = collect($headers)
                ->combine(collect($row)->pad(count($headers), ''))
                ->toArray();
                $ingredient['nutrion100'] = collect($ingredient)->only(['protein', 'fat', 'carb', 'calories'])->toArray();
            $ingredients[] = $ingredient;
        }
        return $ingredients;
    }

    public function parseRecipes(array $rawData)
    {
        $recipes = [];
        $headers = array_shift($rawData);
        foreach ($rawData as $row) {
            // if (count($row) < 3) { // name, ingredients, nutrients
            //     continue;
            // }
            $recipe = collect($headers)
                ->combine(collect($row)->pad(count($headers), ''))
                ->toArray();
            $recipes[] = $recipe;
        }
        return $recipes;
    }

    public function parseFoodLog(array $rawData)
    {
        $foodItems = [];
        $headers = array_shift($rawData);
        foreach ($rawData as $row) {
            $foodItem = collect($headers)
                ->combine(collect($row)->pad(count($headers), ''))
                ->toArray();
            $foodEntry = [
                'date' => $this->parseDate($row[0] ?? ''),
                'food_no' => $foodItem['no'],
                'food_record' => $foodItem['food_record'],
                'food_items' => $this->parseFoodEntryItems($foodItem['food_record']),
                'json' => '...',
                'nutrition' => '...',
            ];
            $foodItems[] = $foodEntry;
        }
        return $foodItems;
    }

    private function parseAliases(string $aliasString)
    {
        return array_map('trim', explode(',', $aliasString));
    }

    private function parseRecipeIngredients(string $ingredientsString)
    {
        $ingredients = [];
        $items = array_map('trim', explode(',', $ingredientsString));
        foreach ($items as $item) {
            $parts = explode('.', $item);
            if (count($parts) >= 2) {
                $ingredients[] = [
                    'name' => trim($parts[0]),
                    'weight' => (float)trim($parts[1])
                ];
            }
        }
        return $ingredients;
    }

    public function parseFoodEntryItems(string $rawEntry)
    {
        $items = [];
        $entries = array_map('trim', explode('-', $rawEntry));
        foreach ($entries as $entry) {
            $parts = explode('.', $entry);
            if (count($parts) >= 2) {
                $items[] = [
                    'name' => trim($parts[0]),
                    'weight' => (float)trim($parts[1])
                ];
            }
        }
        return $items;
    }

    private function parseDate(string $dateString)
    {
        return date('Y-m-d', strtotime($dateString));
    }
} 