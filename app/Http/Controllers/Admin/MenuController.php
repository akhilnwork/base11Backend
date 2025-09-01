<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::with(['parent', 'children'])
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        $parentMenus = Menu::whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.menus.create', compact('parentMenus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
            'url' => 'nullable|string|max:500',
            'target' => 'required|in:_self,_blank',
            'type' => 'required|in:custom,page,category,module',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'meta' => 'nullable|json',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Menu::create($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function show(Menu $menu): View
    {
        $menu->load(['parent', 'children.children']);
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu): View
    {
        $parentMenus = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('sort_order')
            ->get();

        return view('admin.menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
            'url' => 'nullable|string|max:500',
            'target' => 'required|in:_self,_blank',
            'type' => 'required|in:custom,page,category,module',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'meta' => 'nullable|json',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $menu->update($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        // Check if menu has children
        if ($menu->children()->exists()) {
            return redirect()
                ->route('admin.menus.index')
                ->with('error', 'Cannot delete menu item that has child items.');
        }

        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Reorder menu items
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            Menu::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu order updated successfully.');
    }
}