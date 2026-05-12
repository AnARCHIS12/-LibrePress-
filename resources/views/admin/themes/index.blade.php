@extends('layouts.admin')

@section('admin')
    <h1>Themes</h1>

    <div class="grid">
        @foreach ($themes as $theme)
            <article class="card">
                <h2>{{ $theme['name'] }}</h2>
                <p class="muted">{{ $theme['description'] ?? 'Theme local' }}</p>
                <p>Version {{ $theme['version'] }}</p>
                <p>Statut: {{ $theme['record']?->enabled ? 'actif' : 'inactif' }}</p>

                @unless ($theme['record']?->enabled)
                    <form method="post" action="{{ route('admin.themes.activate', $theme['slug']) }}">
                        @csrf
                        <button class="primary" type="submit">Activer</button>
                    </form>
                @endunless
            </article>
        @endforeach
    </div>
@endsection

