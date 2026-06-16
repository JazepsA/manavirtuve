<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use App\Models\Ingredient;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Ņem pirmo lietotāju
        $user = User::first();

        $categoryZupas = Category::where('name', 'Zupas')->first();
        $categoryGala = Category::where('name', 'Gaļas ēdieni')->first();

        // 1. recepte - Tomātu zupa
        $recipe1 = Recipe::create([
            'title' => 'Tomātu zupa',
            'description' => 'Garda un sātīga tomātu zupas recepte',
            'instructions' => "1. Sagriež sīpolus un ķiplokus.\n2. Apcep tos eļļā.\n3. Pievieno tomātus un buljonu.\n4. Vāra 20 minūtes.\n5. Sablendē un pasniedz ar krējumu.",
            'prep_time' => 15,
            'cook_time' => 30,
            'servings' => 4,
            'user_id' => $user->id,
            'category_id' => $categoryZupas->id,
        ]);

        // Pievieno sastāvdaļas Tomātu zupai
        $ingredient1 = Ingredient::where('name', 'Sīpoli')->first();
        $ingredient2 = Ingredient::where('name', 'Ķiploki')->first();
        $ingredient3 = Ingredient::where('name', 'Tomāti')->first();

        if ($ingredient1) $recipe1->ingredients()->attach($ingredient1->id, ['amount' => '2 gab.']);
        if ($ingredient2) $recipe1->ingredients()->attach($ingredient2->id, ['amount' => '3 daiviņas']);
        if ($ingredient3) $recipe1->ingredients()->attach($ingredient3->id, ['amount' => '500g']);

        // 2. recepte - Vistas fileja ar dārzeņiem
        $recipe2 = Recipe::create([
            'title' => 'Vistas fileja ar dārzeņiem',
            'description' => 'Veselīgs un garšīgs vistas ēdiens',
            'instructions' => "1. Sagriež vistas fileju gabaliņos.\n2. Apcep eļļā līdz zeltainai.\n3. Pievieno sagrieztus dārzeņus.\n4. Sautē 15 minūtes.\n5. Pasniedz ar rīsiem.",
            'prep_time' => 20,
            'cook_time' => 25,
            'servings' => 2,
            'user_id' => $user->id,
            'category_id' => $categoryGala->id,
        ]);

        // Pievieno sastāvdaļas Vistas filejai
        $ingredient4 = Ingredient::where('name', 'Vistas gaļa')->first();
        $ingredient5 = Ingredient::where('name', 'Eļļa')->first();
        $ingredient6 = Ingredient::where('name', 'Sīpoli')->first();
        $ingredient7 = Ingredient::where('name', 'Pipari')->first();
        $ingredient8 = Ingredient::where('name', 'Rīsi')->first();

        if ($ingredient4) $recipe2->ingredients()->attach($ingredient4->id, ['amount' => '400g']);
        if ($ingredient5) $recipe2->ingredients()->attach($ingredient5->id, ['amount' => '2 ēd.k.']);
        if ($ingredient6) $recipe2->ingredients()->attach($ingredient6->id, ['amount' => '1 gab.']);
        if ($ingredient7) $recipe2->ingredients()->attach($ingredient7->id, ['amount' => '1 gab.']);
        if ($ingredient8) $recipe2->ingredients()->attach($ingredient8->id, ['amount' => '200g']);
    }
}