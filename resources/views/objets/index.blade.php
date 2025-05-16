<form method="GET" action="{{ route('objets.index') }}" class="filter-form">
    <div class="form-group">
        <label for="search">Mot-clé</label>
        <input type="text" name="search" id="search" placeholder="Rechercher..." 
               value="{{ request('search') }}" class="form-control">
    </div>
    
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" name="date" id="date" 
               value="{{ request('date') }}" class="form-control">
    </div>
    
    <div class="form-group">
        <label for="lieu">Lieu</label>
        <select name="lieu" id="lieu" class="form-control">
            <option value="">Tous les lieux</option>
            @foreach($lieux as $lieu)
                <option value="{{ $lieu }}" {{ request('lieu') == $lieu ? 'selected' : '' }}>
                    {{ $lieu }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="{{ route('objets.index') }}" class="btn btn-secondary">Réinitialiser</a>
    </div>
</form>