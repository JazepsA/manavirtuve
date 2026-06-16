<x-layout>
    <x-slot name="title">{{ $recipe->title }}</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $recipe->title }}</h1>
        <div>
            @can('update', $recipe)
                <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-warning">Rediģēt</a>
            @endcan
            @can('delete', $recipe)
                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Vai tiešām vēlaties dzēst šo recepti?')">Dzēst</button>
                </form>
            @endcan
            <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Atpakaļ</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="text-muted">Kategorija: {{ $recipe->category->name ?? 'Nav' }}</h5>
            <hr>
            
            <p><strong>Apraksts:</strong></p>
            <p>{{ $recipe->description ?? 'Nav apraksta.' }}</p>
            
            <hr>
            
            <p><strong>Autors:</strong> {{ $recipe->user->name ?? 'Nezināms' }}</p>
            
            <p><strong>Sagatavošana:</strong> {{ $recipe->prep_time }} min</p>
            <p><strong>Gatavošana:</strong> {{ $recipe->cook_time }} min</p>
            <p><strong>Porcijas:</strong> {{ $recipe->servings }}</p>
            
            <hr>
            
            <h5>Sastāvdaļas:</h5>
            <ul>
                @foreach($recipe->ingredients as $ingredient)
                    <li>{{ $ingredient->pivot->amount }} {{ $ingredient->name }}</li>
                @endforeach
            </ul>
            
            <hr>
            
            <h5>Pagatavošana:</h5>
            <p>{{ nl2br($recipe->instructions) }}</p>
            
            <hr>
            
            <h5>Komentāri:</h5>
            @if($recipe->comments->count())
                @foreach($recipe->comments as $comment)
                    <div class="border-bottom mb-2 pb-2">
                        <strong>{{ $comment->user->name }}</strong>
                        <small class="text-muted">{{ $comment->commented_at->format('Y-m-d H:i') }}</small>
                        <p>{{ $comment->content }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Nav komentāru.</p>
            @endif

            @auth
                <div class="mt-4">
                    <h6>Pievienot komentāru:</h6>
                    <form action="{{ route('comments.store', $recipe) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="Uzraksti savu komentāru..." required></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Pievienot komentāru</button>
                    </form>
                </div>
            @else
                <p class="mt-3">
                    <a href="{{ route('login') }}">Pieslēdzies</a>, lai pievienotu komentāru!
                </p>
            @endauth
        </div>
    </div>
</x-layout>