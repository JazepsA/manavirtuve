<x-layout>
    <x-slot name="title">Pievienot jaunu recepti</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🍳 Pievienot jaunu recepti</h1>
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary">⬅ Atpakaļ</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('recipes.store') }}">
                @csrf

                <!-- Nosaukums -->
                <div class="mb-3">
                    <label for="title" class="form-label">Nosaukums *</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Kategorija -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategorija *</label>
                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">Izvēlies kategoriju...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Apraksts -->
                <div class="mb-3">
                    <label for="description" class="form-label">Īss apraksts</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Pagatavošanas instrukcijas -->
                <div class="mb-3">
                    <label for="instructions" class="form-label">Pagatavošanas soļi *</label>
                    <textarea name="instructions" id="instructions" class="form-control @error('instructions') is-invalid @enderror" rows="5" required>{{ old('instructions') }}</textarea>
                    @error('instructions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="prep_time" class="form-label">Sagatavošana (min)</label>
                        <input type="number" name="prep_time" id="prep_time" class="form-control @error('prep_time') is-invalid @enderror" value="{{ old('prep_time') }}" min="0">
                        @error('prep_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="cook_time" class="form-label">Gatavošana (min)</label>
                        <input type="number" name="cook_time" id="cook_time" class="form-control @error('cook_time') is-invalid @enderror" value="{{ old('cook_time') }}" min="0">
                        @error('cook_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="servings" class="form-label">Porcijas</label>
                        <input type="number" name="servings" id="servings" class="form-control @error('servings') is-invalid @enderror" value="{{ old('servings', 4) }}" min="1">
                        @error('servings') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Sastāvdaļas -->
                <div class="mb-3">
                    <label class="form-label">Sastāvdaļas</label>
                    <div id="ingredients-container">
                        <div class="row g-2 ingredient-row mb-2">
                            <div class="col-md-7">
                                <select name="ingredients[]" class="form-control">
                                    <option value="">Izvēlies sastāvdaļu...</option>
                                    @foreach($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="amounts[]" class="form-control" placeholder="Daudzums (piem. 200g)">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-ingredient" style="display:none;">✕</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-ingredient" class="btn btn-outline-primary btn-sm mt-2">➕ Pievienot sastāvdaļu</button>
                </div>

                <button type="submit" class="btn btn-primary">✅ Pievienot recepti</button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('ingredients-container');
            const addBtn = document.getElementById('add-ingredient');

            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.ingredient-row');
                rows.forEach((row, index) => {
                    const removeBtn = row.querySelector('.remove-ingredient');
                    if (rows.length > 1) {
                        removeBtn.style.display = 'inline-block';
                    } else {
                        removeBtn.style.display = 'none';
                    }
                });
            }

            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    const firstRow = container.querySelector('.ingredient-row');
                    if (firstRow) {
                        const newRow = firstRow.cloneNode(true);
                        newRow.querySelectorAll('select, input').forEach(el => el.value = '');
                        container.appendChild(newRow);
                        updateRemoveButtons();
                    }
                });
            }

            if (container) {
                container.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-ingredient')) {
                        const row = e.target.closest('.ingredient-row');
                        if (container.querySelectorAll('.ingredient-row').length > 1) {
                            row.remove();
                            updateRemoveButtons();
                        }
                    }
                });
            }

            updateRemoveButtons();
        });
    </script>
    @endpush
</x-layout>