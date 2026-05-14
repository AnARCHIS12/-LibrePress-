<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

final readonly class UserController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('admin.users.index', [
            'users' => User::query()->with('roles')->latest()->paginate(30),
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'status' => ['required', 'in:active,disabled'],
            'roles' => ['array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'status' => $validated['status'],
        ]);
        $user->syncRoles($validated['roles'] ?? []);
        $this->audit->log($request, 'user.updated', $user, ['roles' => $validated['roles'] ?? []]);

        return back()->with('status', 'Utilisateur mis a jour.');
    }
}

