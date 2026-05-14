@extends('layouts.app')

@section('body')
    <main class="shell content">
        <h1>Recherche</h1>
        <form method="get" action="{{ route('front.search') }}" class="card" style="margin-bottom: 20px">
            <label>
                Terme
                <input name="q" value="{{ $query }}" autofocus>
            </label>
            <button class="primary" type="submit">Rechercher</button>
        </form>

        <div class="grid">
            @foreach ($results as $result)
                <article class="card">
                    <h2><a href="{{ route('front.show', data_get($result->meta, 'slug')) }}">{{ $result->title }}</a></h2>
                    <p class="muted">{{ $result->excerpt }}</p>
                </article>
            @endforeach
        </div>
    </main>
@endsection
