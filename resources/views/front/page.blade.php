@extends('layouts.app')

@section('body')
    <main class="shell content">
        <article class="prose">
            <h1>{{ $content->title }}</h1>
            {!! $renderedBlocks !!}
        </article>

        <section class="content prose">
            <h2>Commentaires</h2>
            @forelse ($content->comments as $comment)
                <div class="card" style="margin-bottom: 12px">
                    <strong>{{ $comment->author_name }}</strong>
                    <p>{{ $comment->body }}</p>
                </div>
            @empty
                <p class="muted">Aucun commentaire pour le moment.</p>
            @endforelse

            <form method="post" action="{{ route('comments.store', $content) }}" class="card">
                @csrf
                @guest
                    <label>
                        Nom
                        <input name="author_name" required>
                    </label>
                    <label>
                        Email
                        <input name="author_email" type="email">
                    </label>
                @endguest
                <label>
                    Commentaire
                    <textarea name="body" required style="min-height: 120px"></textarea>
                </label>
                <button class="primary" type="submit">Publier</button>
            </form>
        </section>
    </main>
@endsection
