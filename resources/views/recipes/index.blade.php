<x-layout>
    <x-slot name="title">Receptes</x-slot>

    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <form action="{{ route('recipes.search') }}" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Meklēt receptes pēc nosaukuma, sastāvdaļām, kategorijas..." value="{{ request('query') }}">
                <button type="submit" class="btn btn-primary">Meklēt</button>
                @if(request('query'))
                    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary ms-2">Notīrīt</a>
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
        <h1>Visas receptes</h1>
        
        @auth
            @can('create', App\Models\Recipe::class)
                <a href="{{ url('/pievienot') }}" class="btn btn-primary">Pievienot recepti</a>
            @endcan
        @endauth
    </div>

    @if($recipes->count())
        <div class="row">
            @foreach($recipes as $recipe)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">{{ $recipe->title }}</h5>
                                <h6 class="card-subtitle text-muted">
                                    {{ $recipe->category->name ?? 'Bez kategorijas' }}
                                </h6>
                                <p class="card-text text-muted small mt-2">
                                    {{ Str::limit($recipe->description, 150) }}
                                </p>
                                <small class="text-muted">
                                    Sastāvdaļas: {{ $recipe->ingredients->count() }} | 
                                    Autors: {{ $recipe->user->name ?? 'Nezināms' }}
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-primary">Skatīt</a>
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
                <a href="{{ url('/pievienot') }}">Pievieno pirmo!</a>
            @else
                <a href="{{ route('login') }}">Pieslēdzies, lai pievienotu!</a>
            @endauth
        </div>
    @endif
</x-layout>