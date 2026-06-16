<x-layout>
    <x-slot name="title">Receptes</x-slot>

    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <form action="{{ route('recipes.search') }}" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Meklēt receptes pēc nosaukuma, sastāvdaļām, kategorijas..." value="{{ request('query') }}">
                <button type="submit" class="btn btn-primary">Meklēt</button>
                @if(request('query'))
                    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary ms-2">✕ Notīrīt</a>
                @endif
            </form>
        </div>
    </div>

    @if(request('query'))
        <div class="alert alert-info">
            Meklēšanas rezultāti priekš: <strong>"{{ request('query') }}"</strong> 
            (Atrastas {{ $recipes->count() }} receptes)
        </div>
    @endif

    @if(request('query') && $recipes->count() == 0)
    <div class="alert alert-warning">
        Nav atrasta neviena recepte pēc meklēšanas frāzes: <strong>"{{ request('query') }}"</strong>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🍽️ Visas receptes</h1>
        
        @auth
            @can('create', App\Models\Recipe::class)
                <a href="{{ url('/pievienot') }}" class="btn btn-primary">➕ Pievienot recepti</a>
            @endcan
        @endauth
    </div>

    @if($recipes->count())
        <div class="row">
            @foreach($recipes as $recipe)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $recipe->title }}</h5>
                            
                            <h6 class="card-subtitle mb-2 text-muted">
                                {{ $recipe->category->name ?? 'Bez kategorijas' }}
                            </h6>
                            
                            <p class="card-text text-muted small">
                                {{ Str::limit($recipe->description, 100) }}
                            </p>
                            
                            <div class="mb-2">
                                <strong>Sastāvdaļas:</strong>
                                <ul class="list-unstyled small">
                                    @foreach($recipe->ingredients->take(3) as $ingredient)
                                        <li>• {{ $ingredient->pivot->amount }} {{ $ingredient->name }}</li>
                                    @endforeach
                                    @if($recipe->ingredients->count() > 3)
                                        <li class="text-muted">... un vēl {{ $recipe->ingredients->count() - 3 }}</li>
                                    @endif
                                </ul>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    👤 {{ $recipe->user->name ?? 'Nezināms' }}
                                </small>
                                <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-outline-primary btn-sm">Skatīt →</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
             Nav nevienas receptes.
            @auth
                <a href="{{ route('recipes.create') }}">Pievieno pirmo!</a>
            @else
                <a href="{{ route('login') }}">Pieslēdzies, lai pievienotu!</a>
            @endauth
        </div>
    @endif
</x-layout>