@extends('layouts.admin')

@section('admin')
    <h1>Themes</h1>

    <div class="grid">
        @foreach ($themes as $theme)
            <article class="card">
                <h2>{{ $theme['name'] }}</h2>
                <p class="muted">{{ $theme['description'] ?? 'Theme local' }}</p>
                <p>Version {{ $theme['version'] }}</p>
                <p>Checksum {{ $theme['checksum'] }}</p>
                <p>Compatibilite: {{ $theme['compatible'] ? 'ok' : 'incompatible' }}</p>
                <p>Statut: {{ $theme['record']?->enabled ? 'actif' : 'inactif' }}</p>
                <p><a class="button" href="{{ route('admin.themes.preview', $theme['slug']) }}">Previsualiser</a></p>

                @unless ($theme['record']?->enabled)
                    <form method="post" action="{{ route('admin.themes.activate', $theme['slug']) }}">
                        @csrf
                        <button class="primary" type="submit" @disabled(! $theme['compatible'])>Activer</button>
                    </form>
                @endunless
            </article>
        @endforeach
    </div>
@endsection
