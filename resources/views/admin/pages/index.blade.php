@extends('layouts.admin')

@section('admin')
    <div class="form-actions" style="justify-content: space-between; margin-bottom: 16px">
        <h1>Contenus</h1>
        <a class="button primary" href="{{ route('admin.pages.create') }}">Nouveau</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Slug</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents as $content)
                <tr>
                    <td>{{ $content->title }}</td>
                    <td>{{ $content->type }}</td>
                    <td>{{ $content->status }}</td>
                    <td>{{ $content->slug }}</td>
                    <td>
                        <a class="button" href="{{ route('admin.pages.edit', $content) }}">Editer</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px">{{ $contents->links() }}</div>
@endsection

