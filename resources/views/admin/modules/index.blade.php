@extends('layouts.admin')

@section('admin')
    <h1>Modules</h1>

    <div class="grid">
        @foreach ($modules as $module)
            <article class="card">
                <h2>{{ $module['name'] }}</h2>
                <p class="muted">{{ $module['description'] ?? 'Module local' }}</p>
                <p>Version {{ $module['version'] }}</p>
                <p>Checksum {{ $module['checksum'] }}</p>
                <p>Compatibilite: {{ $module['compatible'] ? 'ok' : 'incompatible' }}</p>
                <p>Statut: {{ $module['record']?->enabled ? 'actif' : 'inactif' }}</p>

                @if ($module['record']?->enabled)
                    <form method="post" action="{{ route('admin.modules.disable', $module['slug']) }}">
                        @csrf
                        <button type="submit">Desactiver</button>
                    </form>
                @else
                    <form method="post" action="{{ route('admin.modules.enable', $module['slug']) }}">
                        @csrf
                        <button class="primary" type="submit" @disabled(! $module['compatible'])>Activer</button>
                    </form>
                    @if ($module['record'])
                        <form method="post" action="{{ route('admin.modules.uninstall', $module['slug']) }}" style="margin-top: 8px">
                            @csrf
                            @method('delete')
                            <button class="danger" type="submit">Desinstaller</button>
                        </form>
                    @endif
                @endif
            </article>
        @endforeach
    </div>
@endsection
