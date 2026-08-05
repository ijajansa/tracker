<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Opening Balance', 'type' => 'income', 'icon' => '💰'],
            ['name' => 'Salary', 'type' => 'income', 'icon' => '🧾'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💼'],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => '🎁'],
            ['name' => 'Refund', 'type' => 'income', 'icon' => '↩️'],
            ['name' => 'Other', 'type' => 'income', 'icon' => '✨'],
            ['name' => 'Food', 'type' => 'expense', 'icon' => '🍔'],
            ['name' => 'Travel', 'type' => 'expense', 'icon' => '✈️'],
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => '🛍️'],
            ['name' => 'Bills', 'type' => 'expense', 'icon' => '🧾'],
            ['name' => 'Rent', 'type' => 'expense', 'icon' => '🏠'],
            ['name' => 'Health', 'type' => 'expense', 'icon' => '🩺'],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => '🎬'],
            ['name' => 'Family', 'type' => 'expense', 'icon' => '👨‍👩‍👧‍👦'],
            ['name' => 'EMI', 'type' => 'expense', 'icon' => '📉'],
            ['name' => 'Other', 'type' => 'expense', 'icon' => '✨'],
        ];

        foreach ($categories as $category) {
            Category::query()->firstOrCreate(
                ['type' => $category['type'], 'name' => $category['name']],
                [
                    'icon' => $category['icon'],
                    'is_default' => true,
                    'status' => 'active',
                ]
            );
        }
    }
}
