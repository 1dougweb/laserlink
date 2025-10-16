<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Changelog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChangelogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $changelogs = Changelog::with('user')
            ->ordered()
            ->paginate(20);

        return view('admin.changelogs.index', compact('changelogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.changelogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'improvements' => 'nullable|array',
            'improvements.*' => 'nullable|string',
            'fixes' => 'nullable|array',
            'fixes.*' => 'nullable|string',
            'release_date' => 'required|date',
            'is_published' => 'boolean',
        ]);

        // Filtrar arrays vazios e garantir que seja array
        $validated['features'] = array_values(array_filter($validated['features'] ?? [], fn($item) => !empty($item)));
        $validated['improvements'] = array_values(array_filter($validated['improvements'] ?? [], fn($item) => !empty($item)));
        $validated['fixes'] = array_values(array_filter($validated['fixes'] ?? [], fn($item) => !empty($item)));
        
        $validated['user_id'] = auth()->id();

        Changelog::create($validated);

        return redirect()->route('admin.changelogs.index')
            ->with('success', 'Atualização criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Changelog $changelog): View
    {
        $changelog->load('user');
        return view('admin.changelogs.show', compact('changelog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Changelog $changelog): View
    {
        return view('admin.changelogs.edit', compact('changelog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Changelog $changelog): RedirectResponse
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'improvements' => 'nullable|array',
            'improvements.*' => 'nullable|string',
            'fixes' => 'nullable|array',
            'fixes.*' => 'nullable|string',
            'release_date' => 'required|date',
            'is_published' => 'boolean',
        ]);

        // Filtrar arrays vazios e garantir que seja array
        $validated['features'] = array_values(array_filter($validated['features'] ?? [], fn($item) => !empty($item)));
        $validated['improvements'] = array_values(array_filter($validated['improvements'] ?? [], fn($item) => !empty($item)));
        $validated['fixes'] = array_values(array_filter($validated['fixes'] ?? [], fn($item) => !empty($item)));

        $changelog->update($validated);

        return redirect()->route('admin.changelogs.index')
            ->with('success', 'Atualização editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Changelog $changelog): RedirectResponse
    {
        $changelog->delete();

        return redirect()->route('admin.changelogs.index')
            ->with('success', 'Atualização excluída com sucesso!');
    }
}
