@extends('layouts.admin')

@section('admin')
    <h1>Menus</h1>

    <form method="post" action="{{ route('admin.menus.store') }}" class="card" style="margin-bottom: 20px">
        @csrf
        <div class="grid">
            <label>
                Nom
                <input name="name" required>
            </label>
            <label>
                Slug
                <input name="slug">
            </label>
            <label>
                Emplacement
                <input name="location" value="primary" required>
            </label>
        </div>
        <button class="primary" type="submit">Creer un menu</button>
    </form>

    <div class="grid">
        @foreach ($menus as $menu)
            <article class="card">
                <h2>{{ $menu->name }}</h2>
                <p class="muted">{{ $menu->location }} · {{ $menu->slug }}</p>

                <form method="post" action="{{ route('admin.menus.items.store', $menu) }}">
                    @csrf
                    <label>
                        Libelle
                        <input name="label" required>
                    </label>
                    <label>
                        URL
                        <input name="url" required>
                    </label>
                    <label>
                        Ordre
                        <input name="sort_order" type="number" min="0" value="0">
                    </label>
                    <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
                        <input name="new_tab" type="checkbox" value="1" style="width: auto">
                        Nouvel onglet
                    </label>
                    <button type="submit">Ajouter</button>
                </form>

                @if ($menu->items->isNotEmpty())
                    <ul>
                        @foreach ($menu->items as $item)
                            <li>{{ $item->label }} <span class="muted">{{ $item->url }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </article>
        @endforeach
    </div>
@endsection
