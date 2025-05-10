<?php

namespace App\Services;

class DataParserService
{
    public function parseIngredients(array $rawData)
    {
        $ingredients = [];
        $headers = array_shift($rawData);
        foreach ($rawData as $row) {
            if (count($row) < count($headers)) {
                continue;
            }
            $ingredient = [
                'name' => $row[0] ?? '',
                'aliases' => $this->parseAliases($row[1] ?? ''),
                'calories' => (float)($row[2] ?? 0),
                'protein' => (float)($row[3] ?? 0),
                'fat' => (float)($row[4] ?? 0),
                'carbs' => (float)($row[5] ?? 0)
            ];
            $ingredients[] = $ingredient;
        }
        return $ingredients;
    }

    public function parseRecipes(array $rawData)
    {
        $recipes = [];
        $headers = array_shift($rawData);
        foreach ($rawData as $row) {
            if (count($row) < count($headers)) {
                continue;
            }
            $recipe = [
                'name' => $row[0] ?? '',
                'aliases' => $this->parseAliases($row[1] ?? ''),
                'ingredients' => $this->parseRecipeIngredients($row[2] ?? ''),
                'total_weight' => 0,
                'calories' => 0,
                'protein' => 0,
                'fat' => 0,
                'carbs' => 0
            ];
            $recipes[] = $recipe;
        }
        return $recipes;
    }

    public function parseFoodLog(array $rawData)
    {
        $foodEntries = [];
        $headers = array_shift($rawData);
        foreach ($rawData as $row) {
            if (count($row) < count($headers)) {
                continue;
            }
            $date = $this->parseDate($row[0] ?? '');
            $mealNumber = (int)($row[1] ?? 0);
            $rawEntry = $row[2] ?? '';
            $foodEntry = [
                'date' => $date,
                'meal_number' => $mealNumber,
                'raw_entry' => $rawEntry,
                'parsed_items' => [],
                'calories' => 0,
                'protein' => 0,
                'fat' => 0,
                'carbs' => 0
            ];
            $foodEntries[] = $foodEntry;
        }
        return $foodEntries;
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