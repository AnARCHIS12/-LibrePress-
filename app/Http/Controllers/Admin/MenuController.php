<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

final readonly class MenuController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Menu::class);

        return view('admin.menus.index', [
            'menus' => Menu::query()->with('items')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Menu::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:menus,slug'],
            'location' => ['required', 'string', 'max:80'],
        ]);

        $menu = Menu::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'location' => $validated['location'],
        ]);
        $this->audit->log($request, 'menu.created', $menu);

        return back()->with('status', 'Menu cree.');
    }

    public function storeItem(Request $request, Menu $menu): RedirectResponse
    {
        Gate::authorize('update', $menu);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'url' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'new_tab' => ['nullable', 'boolean'],
        ]);

        $item = MenuItem::query()->create([
            'menu_id' => $menu->id,
            'label' => $validated['label'],
            'url' => $validated['url'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'new_tab' => $request->boolean('new_tab'),
        ]);
        $this->audit->log($request, 'menu_item.created', $item, ['menu' => $menu->slug]);

        return back()->with('status', 'Element ajoute.');
    }
}

