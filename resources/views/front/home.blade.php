@extends('layouts.app')

@section('body')
    <main class="shell">
        <section class="hero">
            <h1>{{ $content?->title ?? 'LibrePress' }}</h1>
            <p>{{ $content?->excerpt ?? 'CMS Laravel libre, modulaire, leger et auto-hebergeable.' }}</p>
            <a class="button primary" href="{{ route('front.blog') }}">Lire le blog</a>
        </section>

        <section class="content prose">
            {!! $renderedBlocks !!}
        </section>

        <section class="content">
            <h2>Derniers articles</h2>
            <div class="grid">
                @forelse ($posts as $post)
                    <article class="card">
                        <h3><a href="{{ route('front.show', $post->slug) }}">{{ $post->title }}</a></h3>
                        <p class="muted">{{ $post->excerpt }}</p>
                    </article>
                @empty
                    <p class="muted">Aucun article publie.</p>
                @endforelse
            </div>
        </section>
    </main>
@endsection

