@extends('layouts.admin')

@section('admin')
    <h1>Sauvegardes et exports</h1>

    <div class="grid">
        <form method="post" action="{{ route('admin.backups.create') }}" class="card">
            @csrf
            <h2>Sauvegarde</h2>
            <p class="muted">Cree une sauvegarde locale legere de la base SQLite lorsque ce moteur est actif.</p>
            <button class="primary" type="submit">Lancer la sauvegarde</button>
        </form>

        <form method="post" action="{{ route('admin.exports.create') }}" class="card">
            @csrf
            <h2>Export portable</h2>
            <p class="muted">Exporte contenus et metadonnees medias en JSON portable.</p>
            <button class="primary" type="submit">Creer un export</button>
        </form>
    </div>
@endsection
