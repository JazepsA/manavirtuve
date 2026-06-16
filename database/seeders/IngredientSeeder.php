<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            'Milti',
            'Cukurs',
            'Sāls',
            'Olas',
            'Piens',
            'Sviests',
            'Eļļa',
            'Ķiploki',
            'Sīpoli',
            'Kartupeļi',
            'Vistas gaļa',
            'Cūkgaļa',
            'Liellopa gaļa',
            'Zivs fileja',
            'Rīsi',
            'Makaroni',
            'Tomāti',
            'Gurķi',
            'Redīsi',
            'Pipari'
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create(['name' => $ingredient]);
        }
    }
}