@extends('layouts.app')

@section('body')
    <main class="shell content">
        <h1>Blog</h1>
        <div class="grid">
            @foreach ($posts as $post)
                <article class="card">
                    <h2><a href="{{ route('front.show', $post->slug) }}">{{ $post->title }}</a></h2>
                    <p class="muted">{{ $post->excerpt }}</p>
                </article>
            @endforeach
        </div>
        <div style="margin-top: 24px">{{ $posts->links() }}</div>
    </main>
@endsection

