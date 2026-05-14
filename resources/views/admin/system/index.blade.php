@extends('layouts.admin')

@section('admin')
    <h1>Sante systeme</h1>

    <div class="grid">
        @foreach ($checks as $name => $check)
            <article class="card">
                <h2>{{ $name }}</h2>
                <p>Statut: {{ $check['status'] }}</p>
                <p class="muted">{{ $check['detail'] }}</p>
            </article>
        @endforeach
    </div>

    <form method="post" action="{{ route('admin.system.cache.clear') }}" class="card" style="margin-top: 20px">
        @csrf
        <h2>Cache</h2>
        <p class="muted">Vide les caches applicatifs et publics.</p>
        <button class="primary" type="submit">Vider le cache</button>
    </form>
@endsection
