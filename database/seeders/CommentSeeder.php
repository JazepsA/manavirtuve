<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Recipe;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        // Atrodam lietotājus
        $user1 = User::where('email', 'admin@example.com')->first();
        $user2 = User::where('email', 'user@example.com')->first();
        
        // Atrodam receptes
        $recipe1 = Recipe::where('title', 'Tomātu zupa')->first();
        $recipe2 = Recipe::where('title', 'Vistas fileja ar dārzeņiem')->first();

        // Komentāri Tomātu zupai
        if ($recipe1 && $user1) {
            Comment::create([
                'content' => 'Lieliska recepte! Pagatavoju un ģimenei ļoti garšoja.',
                'commented_at' => now(),
                'user_id' => $user1->id,
                'recipe_id' => $recipe1->id,
            ]);
        }

        if ($recipe1 && $user2) {
            Comment::create([
                'content' => 'Vai var pievienot krējumu?',
                'commented_at' => now(),
                'user_id' => $user2->id,
                'recipe_id' => $recipe1->id,
            ]);
        }

        // Komentāri Vistas filejai
        if ($recipe2 && $user2) {
            Comment::create([
                'content' => 'Ļoti garšīgi! Viegli pagatavot.',
                'commented_at' => now(),
                'user_id' => $user2->id,
                'recipe_id' => $recipe2->id,
            ]);
        }

        if ($recipe2 && $user1) {
            Comment::create([
                'content' => 'Ieteiktu pievienot vairāk garšvielu.',
                'commented_at' => now(),
                'user_id' => $user1->id,
                'recipe_id' => $recipe2->id,
            ]);
        }
    }
}