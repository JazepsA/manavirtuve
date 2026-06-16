<x-layout>
    <x-slot name="title">Dzēstās receptes</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🗑️ Dzēstās receptes</h1>
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Atpakaļ</a>
    </div>

    @if($recipes->count())
        <div class="row">
            @foreach($recipes as $recipe)
                <div class="col-12 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>{{ $recipe->title }}</h5>
                                <small class="text-muted">
                                    Kategorija: {{ $recipe->category->name ?? 'Bez kategorijas' }} | 
                                    Autors: {{ $recipe->user->name ?? 'Nezināms' }} |
                                    Dzēsts: {{ $recipe->deleted_at->format('Y-m-d H:i') }}
                                </small>
                            </div>
                            <div>
                                <form action="{{ route('recipes.restore', $recipe->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Atjaunot šo recepti?')">↩ Atjaunot</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">Nav dzēstu recepšu.</div>
    @endif
</x-layout>