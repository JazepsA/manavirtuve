<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    // Ikviens var skatīt receptes
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Recipe $recipe): bool
    {
        return true;
    }

    // Tikai autentificēti lietotāji var veidot
    public function create(User $user): bool
    {
        return true;
    }

    // Tikai autors vai admin var rediģēt
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id || $user->isAdmin();
    }

    // Tikai autors vai admin var dzēst
    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id || $user->isAdmin();
    }

    // Admin var visu
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }



    
    public function restore(User $user, Recipe $recipe): bool
    {
        return false;
    }

    public function forceDelete(User $user, Recipe $recipe): bool
    {
        return false;
    }
}