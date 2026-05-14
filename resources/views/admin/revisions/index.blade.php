@extends('layouts.admin')

@section('admin')
    <h1>Revisions: {{ $content->title }}</h1>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Auteur</th>
                <th>Titre</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revisions as $revision)
                <tr>
                    <td>{{ $revision->created_at }}</td>
                    <td>{{ $revision->user?->name ?? 'Systeme' }}</td>
                    <td>{{ $revision->title }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.pages.revisions.restore', [$content, $revision]) }}">
                            @csrf
                            <button type="submit">Restaurer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px">{{ $revisions->links() }}</div>
@endsection
