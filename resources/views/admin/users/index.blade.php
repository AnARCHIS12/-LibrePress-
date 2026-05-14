@extends('layouts.admin')

@section('admin')
    <h1>Utilisateurs et roles</h1>

    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Roles</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <form method="post" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('put')
                        <td><input name="name" value="{{ $user->name }}" @disabled(auth()->id() === $user->id)></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <select name="status" @disabled(auth()->id() === $user->id)>
                                <option value="active" @selected($user->status === 'active')>Actif</option>
                                <option value="disabled" @selected($user->status === 'disabled')>Desactive</option>
                            </select>
                        </td>
                        <td>
                            @foreach ($roles as $role)
                                <label style="display: flex; grid-template-columns: auto 1fr; align-items: center; margin-bottom: 4px">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked($user->hasRole($role->name)) @disabled(auth()->id() === $user->id) style="width: auto">
                                    {{ $role->name }}
                                </label>
                            @endforeach
                        </td>
                        <td>
                            @if (auth()->id() !== $user->id)
                                <button type="submit">Enregistrer</button>
                            @endif
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px">{{ $users->links() }}</div>
@endsection

