@extends('layouts.app')

@section('body')
    <main class="shell content">
        <div class="card" style="max-width: 460px">
            <h1>Connexion</h1>
            <form method="post" action="{{ route('login') }}">
                @csrf
                <label>
                    Email
                    <input name="email" type="email" value="{{ old('email') }}" required autofocus>
                </label>
                <label>
                    Mot de passe
                    <input name="password" type="password" required>
                </label>
                <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
                    <input name="remember" type="checkbox" value="1" style="width: auto">
                    Se souvenir de moi
                </label>
                <button class="primary" type="submit">Entrer</button>
            </form>
        </div>
    </main>
@endsection

