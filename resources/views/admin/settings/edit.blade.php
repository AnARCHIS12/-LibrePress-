@extends('layouts.admin')

@section('admin')
    <h1>Reglages</h1>

    <form method="post" action="{{ route('admin.settings.update') }}" class="card">
        @csrf
        @method('put')

        <label>
            Nom du site
            <input name="site_name" value="{{ old('site_name', $siteName) }}" required>
        </label>

        <label>
            Description
            <input name="site_description" value="{{ old('site_description', $siteDescription) }}">
        </label>

        <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
            <input name="comments_enabled" type="checkbox" value="1" @checked($commentsEnabled) style="width: auto">
            Commentaires publics
        </label>

        <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
            <input name="activitypub_enabled" type="checkbox" value="1" @checked($activitypubEnabled) style="width: auto">
            ActivityPub experimental
        </label>

        <button class="primary" type="submit">Enregistrer</button>
    </form>
@endsection

