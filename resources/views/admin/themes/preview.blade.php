@extends('layouts.admin')

@section('admin')
    <h1>Preview theme: {{ $theme['name'] }}</h1>

    <div class="card">
        <p>Slug: {{ $theme['slug'] }}</p>
        <p>Version: {{ $theme['version'] }}</p>
        <p>Compatibilite: {{ $compatible ? 'ok' : 'incompatible' }}</p>
        <p>Checksum: {{ $checksum }}</p>

        <h2>Regions</h2>
        <ul>
            @foreach (($theme['regions'] ?? []) as $region => $label)
                <li>{{ $region }}: {{ $label }}</li>
            @endforeach
        </ul>
    </div>
@endsection
