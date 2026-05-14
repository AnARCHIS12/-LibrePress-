@extends('layouts.app')

@section('body')
    <div class="admin-layout">
        <aside class="sidebar">
            <strong>Administration</strong>
            <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
            <a href="{{ route('admin.pages.index') }}">Contenus</a>
            <a href="{{ route('admin.media.index') }}">Medias</a>
            <a href="{{ route('admin.comments.index') }}">Commentaires</a>
            <a href="{{ route('admin.taxonomies.index') }}">Taxonomies</a>
            <a href="{{ route('admin.menus.index') }}">Menus</a>
            <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
            <a href="{{ route('admin.modules.index') }}">Modules</a>
            <a href="{{ route('admin.themes.index') }}">Themes</a>
            <a href="{{ route('admin.settings.edit') }}">Reglages</a>
            <a href="{{ route('admin.redirects.index') }}">Redirections</a>
            <a href="{{ route('admin.backups.index') }}">Sauvegardes</a>
            <a href="{{ route('front.home') }}">Voir le site</a>
        </aside>
        <main class="admin-main">
            @if (session('status'))
                <p class="notice">{{ session('status') }}</p>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="notice">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('admin')
        </main>
    </div>
@endsection
