<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Recipe $recipe)
    {
        // Validācija
        $validated = $request->validate([
            'content' => 'required|string|min:2|max:1000',
        ]);

        // Izveido komentāru
        Comment::create([
            'content' => $validated['content'],
            'commented_at' => now(),
            'user_id' => Auth::id(),
            'recipe_id' => $recipe->id,
        ]);

        return redirect()->route('recipes.show', $recipe)->with('success', 'Komentārs pievienots!');
    }
}