<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    // Recepšu saraksts
    public function index()
    {
        $recipes = Recipe::with(['category', 'user', 'ingredients'])->get();
        return view('recipes.index', compact('recipes'));
    }

    public function search(Request $request)
    {

    $query = $request->input('query');
        
        // Meklē pēc nosaukuma, apraksta, sastāvdaļām vai kategorijas
        $recipes = Recipe::with(['category', 'user', 'ingredients'])
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('instructions', 'LIKE', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('ingredients', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->get();
        
        return view('recipes.index', compact('recipes'));
    }

    // Receptes detalizētais skats
    public function show(Recipe $recipe)
    {
        return view('recipes.show', compact('recipe'));
    }

    // Forma jaunas receptes pievienošanai
    public function create()
    {
        $categories = Category::all();
        $ingredients = Ingredient::all();
        return view('recipes.create', compact('categories', 'ingredients'));
    }

    // Saglabā jaunu recepti
    public function store(Request $request)
    {
        // Validācija
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'exists:ingredients,id',
            'amounts' => 'nullable|array',
            'amounts.*' => 'nullable|string|max:50',
        ]);

        // Izveido recepti
        $recipe = Recipe::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructions' => $validated['instructions'],
            'prep_time' => $validated['prep_time'],
            'cook_time' => $validated['cook_time'],
            'servings' => $validated['servings'] ?? 4,
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
        ]);

        // Pievieno sastāvdaļas
        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $index => $ingredientId) {
                $amount = $validated['amounts'][$index] ?? '';
                $recipe->ingredients()->attach($ingredientId, ['amount' => $amount]);
            }
        }

        return redirect()->route('recipes.show', $recipe)->with('success', 'Recepte veiksmīgi pievienota!');
    }

    // Forma receptes rediģēšanai
    public function edit(Recipe $recipe)
    {
        // Pārbauda, vai lietotājs ir receptes autors vai admin
        if (Auth::user()->id !== $recipe->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Nav atļauts rediģēt šo recepti.');
        }

        $categories = Category::all();
        $ingredients = Ingredient::all();
        return view('recipes.edit', compact('recipe', 'categories', 'ingredients'));
    }

    // Atjauno recepti
    public function update(Request $request, Recipe $recipe)
    {
        
        // Pārbauda, vai lietotājs ir receptes autors vai admin
        if (Auth::user()->id !== $recipe->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Nav atļauts rediģēt šo recepti.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'exists:ingredients,id',
            'amounts' => 'nullable|array',
            'amounts.*' => 'nullable|string|max:50',
        ]);

        // Atjauno recepti
        $recipe->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructions' => $validated['instructions'],
            'prep_time' => $validated['prep_time'],
            'cook_time' => $validated['cook_time'],
            'servings' => $validated['servings'] ?? 4,
            'category_id' => $validated['category_id'],
        ]);

        // Atjauno sastāvdaļas (noņem vecās un pievieno jaunās)
        $recipe->ingredients()->detach();
        
        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $index => $ingredientId) {
                $amount = $validated['amounts'][$index] ?? '';
                $recipe->ingredients()->attach($ingredientId, ['amount' => $amount]);
            }
        }

        return redirect()->route('recipes.show', $recipe)->with('success', 'Recepte veiksmīgi atjaunota!');
    }

    // Dzēš recepti
    public function destroy(Recipe $recipe)
    {
        // Pārbauda, vai lietotājs ir receptes autors vai admin
        if (Auth::user()->id !== $recipe->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Nav atļauts dzēst šo recepti.');
        }

        $recipe->delete();

        return redirect()->route('recipes.index')->with('success', 'Recepte veiksmīgi dzēsta!');
    }

}