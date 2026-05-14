@extends('layouts.admin')

@section('admin')
    <h1>{{ $content->exists ? 'Editer' : 'Nouveau contenu' }}</h1>
    <form method="post" action="{{ $content->exists ? route('admin.pages.update', $content) : route('admin.pages.store') }}">
        @csrf
        @if ($content->exists)
            @method('put')
        @endif

        <div class="grid">
            <label>
                Type
                <select name="type">
                    <option value="page" @selected(old('type', $content->type) === 'page')>Page</option>
                    <option value="post" @selected(old('type', $content->type) === 'post')>Article</option>
                </select>
            </label>
            <label>
                Statut
                <select name="status">
                    @foreach (['draft' => 'Brouillon', 'review' => 'Relecture', 'scheduled' => 'Programme', 'published' => 'Publie', 'archived' => 'Archive'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $content->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Publication planifiee
                <input name="scheduled_at" type="datetime-local" value="{{ old('scheduled_at', optional($content->scheduled_at)->format('Y-m-d\TH:i')) }}">
            </label>
            <label>
                Langue
                <input name="locale" value="{{ old('locale', $content->locale ?? 'fr') }}" required>
            </label>
        </div>

        <label>
            Titre
            <input name="title" value="{{ old('title', $content->title) }}" required>
        </label>
        <label>
            Slug
            <input name="slug" value="{{ old('slug', $content->slug) }}">
        </label>
        <label>
            Extrait
            <input name="excerpt" value="{{ old('excerpt', $content->excerpt) }}">
        </label>

        @if ($terms->isNotEmpty())
            <div class="card" style="margin-bottom: 14px">
                <h2>Taxonomies</h2>
                @foreach ($terms as $term)
                    <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
                        <input name="terms[]" type="checkbox" value="{{ $term->id }}" @checked(in_array($term->id, old('terms', $selectedTerms), true)) style="width: auto">
                        {{ $term->taxonomy->name }}: {{ $term->name }}
                    </label>
                @endforeach
            </div>
        @endif

        <div class="card" style="margin-bottom: 14px">
            <h2>SEO</h2>
            <label>
                Titre SEO
                <input name="meta_title" value="{{ old('meta_title', data_get($content->meta, 'seo.title')) }}">
            </label>
            <label>
                Description SEO
                <input name="meta_description" value="{{ old('meta_description', data_get($content->meta, 'seo.description')) }}">
            </label>
            <label>
                URL canonique
                <input name="canonical_url" value="{{ old('canonical_url', data_get($content->meta, 'seo.canonical_url')) }}">
            </label>
            <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
                <input name="noindex" type="checkbox" value="1" @checked(old('noindex', data_get($content->meta, 'seo.noindex'))) style="width: auto">
                Ne pas indexer
            </label>
        </div>

        <label>
            Editeur bloc Markdown
            <textarea name="body_markdown">{{ old('body_markdown', data_get($content->body_json, 'blocks.0.props.text')) }}</textarea>
        </label>
        <label>
            Document blocs JSON avance
            <textarea name="body_blocks_json" style="min-height: 180px">{{ old('body_blocks_json', $content->exists ? json_encode($content->body_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
        </label>

        <div class="form-actions">
            <button class="primary" type="submit">Enregistrer</button>
            <a class="button" href="{{ route('admin.pages.index') }}">Retour</a>
            @if ($content->exists)
                <a class="button" href="{{ route('admin.pages.preview', $content) }}">Previsualiser</a>
                <a class="button" href="{{ route('admin.pages.revisions', $content) }}">Revisions</a>
            @endif
        </div>
    </form>

    @if ($content->exists)
        <form method="post" action="{{ route('admin.pages.destroy', $content) }}" style="margin-top: 14px">
            @csrf
            @method('delete')
            <button class="danger" type="submit">Supprimer</button>
        </form>

        <form method="post" action="{{ route('admin.pages.translations.store', $content) }}" class="card" style="margin-top: 14px">
            @csrf
            <h2>Traduction</h2>
            <label>
                Nouvelle locale
                <input name="locale" placeholder="en" required>
            </label>
            <button type="submit">Creer une traduction</button>
        </form>
    @endif
@endsection
