@extends('layouts.admin')

@section('admin')
    <h1>Commentaires</h1>

    <table>
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Contenu</th>
                <th>Statut</th>
                <th>Signalements</th>
                <th>Commentaire</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
                <tr>
                    <td>{{ $comment->author_name }}</td>
                    <td>{{ $comment->content?->title }}</td>
                    <td>{{ $comment->status }}</td>
                    <td>{{ $comment->reports_count }} @if($comment->is_spam)<span class="muted">spam: {{ $comment->moderation_reason }}</span>@endif</td>
                    <td>{{ $comment->body }}</td>
                    <td>
                        <div class="form-actions">
                            <form method="post" action="{{ route('admin.comments.approve', $comment) }}">
                                @csrf
                                @method('patch')
                                <button type="submit">Approuver</button>
                            </form>
                            <form method="post" action="{{ route('admin.comments.reject', $comment) }}">
                                @csrf
                                @method('patch')
                                <button type="submit">Rejeter</button>
                            </form>
                            <form method="post" action="{{ route('admin.comments.destroy', $comment) }}">
                                @csrf
                                @method('delete')
                                <button class="danger" type="submit">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px">{{ $comments->links() }}</div>
@endsection
