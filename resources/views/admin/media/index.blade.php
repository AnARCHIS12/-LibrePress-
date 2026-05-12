@extends('layouts.admin')

@section('admin')
    <h1>Medias</h1>
    <form class="card" method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" style="margin-bottom: 20px">
        @csrf
        <label>
            Fichier
            <input name="file" type="file" required>
        </label>
        <label>
            Texte alternatif
            <input name="alt">
        </label>
        <button class="primary" type="submit">Ajouter</button>
    </form>

    <div class="grid">
        @foreach ($media as $item)
            <div class="card">
                @if (str_starts_with($item->mime_type, 'image/'))
                    <img src="{{ $item->url() }}" alt="{{ $item->alt }}">
                @endif
                <p>{{ $item->path }}</p>
                <form method="post" action="{{ route('admin.media.destroy', $item) }}">
                    @csrf
                    @method('delete')
                    <button class="danger" type="submit">Supprimer</button>
                </form>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 24px">{{ $media->links() }}</div>
@endsection

