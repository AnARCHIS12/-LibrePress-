@extends('layouts.admin')

@section('admin')
    <h1>Tableau de bord</h1>
    <div class="grid">
        <div class="card"><h2>{{ $pages }}</h2><p class="muted">Pages</p></div>
        <div class="card"><h2>{{ $posts }}</h2><p class="muted">Articles</p></div>
        <div class="card"><h2>{{ $drafts }}</h2><p class="muted">Brouillons</p></div>
    </div>
@endsection

