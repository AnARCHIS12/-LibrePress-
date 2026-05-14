@extends('layouts.admin')

@section('admin')
    <h1>Redirections</h1>

    <form method="post" action="{{ route('admin.redirects.store') }}" class="card" style="margin-bottom: 20px">
        @csrf
        <div class="grid">
            <label>
                Ancien chemin
                <input name="source_path" placeholder="/ancien-slug" required>
            </label>
            <label>
                Nouvelle cible
                <input name="target_path" placeholder="/nouveau-slug" required>
            </label>
            <label>
                Code
                <select name="status_code">
                    <option value="301">301 permanent</option>
                    <option value="302">302 temporaire</option>
                    <option value="307">307 temporaire</option>
                    <option value="308">308 permanent</option>
                </select>
            </label>
        </div>
        <button class="primary" type="submit">Ajouter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Source</th>
                <th>Cible</th>
                <th>Code</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($redirects as $redirect)
                <tr>
                    <td>{{ $redirect->source_path }}</td>
                    <td>{{ $redirect->target_path }}</td>
                    <td>{{ $redirect->status_code }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.redirects.destroy', $redirect) }}">
                            @csrf
                            @method('delete')
                            <button class="danger" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px">{{ $redirects->links() }}</div>
@endsection

