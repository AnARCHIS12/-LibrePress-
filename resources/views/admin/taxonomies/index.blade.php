@extends('layouts.admin')

@section('admin')
    <h1>Taxonomies</h1>

    <form method="post" action="{{ route('admin.taxonomies.store') }}" class="card" style="margin-bottom: 20px">
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
        </div>
        <button class="primary" type="submit">Creer</button>
    </form>

    <div class="grid">
        @foreach ($taxonomies as $taxonomy)
            <article class="card">
                <h2>{{ $taxonomy->name }}</h2>
                <p class="muted">{{ $taxonomy->slug }} · {{ $taxonomy->terms_count }} termes</p>

                <form method="post" action="{{ route('admin.terms.store', $taxonomy) }}">
                    @csrf
                    <label>
                        Nouveau terme
                        <input name="name" required>
                    </label>
                    <label>
                        Slug
                        <input name="slug">
                    </label>
                    <button type="submit">Ajouter</button>
                </form>

                @if ($taxonomy->terms->isNotEmpty())
                    <ul>
                        @foreach ($taxonomy->terms as $term)
                            <li>{{ $term->name }} <span class="muted">/{{ $term->slug }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </article>
        @endforeach
    </div>
@endsection
