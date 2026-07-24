<?php

namespace App\Http\Controllers;

use App\Models\ApiIntegration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApiIntegrationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $escaped = $search ? str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) : null;
        $integrations = ApiIntegration::when($escaped, fn ($q) =>
                $q->where('nama_aplikasi', 'like', "%{$escaped}%")
                  ->orWhere('deskripsi', 'like', "%{$escaped}%")
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('integrations.index', compact('integrations', 'search'));
    }

    public function create(): View
    {
        return view('integrations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_aplikasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ]);

        ApiIntegration::create([
            'nama_aplikasi' => $request->nama_aplikasi,
            'deskripsi' => $request->deskripsi,
            'api_key' => 'RTHAPI-' . strtoupper(Str::random(32)),
            'is_active' => true,
        ]);

        return redirect()->route('integrations.index')
            ->with('success', "Integrasi \"{$request->nama_aplikasi}\" berhasil ditambahkan. Salin API Key sebelum meninggalkan halaman.");
    }

    public function edit(ApiIntegration $integration): View
    {
        return view('integrations.edit', compact('integration'));
    }

    public function update(Request $request, ApiIntegration $integration): RedirectResponse
    {
        $request->validate([
            'nama_aplikasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', 'boolean'],
        ]);

        $integration->update([
            'nama_aplikasi' => $request->nama_aplikasi,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('integrations.index')
            ->with('success', "Integrasi \"{$integration->nama_aplikasi}\" berhasil diperbarui.");
    }

    public function regenerate(ApiIntegration $integration): RedirectResponse
    {
        $newKey = 'RTHAPI-' . strtoupper(Str::random(32));
        $integration->update(['api_key' => $newKey]);

        return redirect()->route('integrations.index')
            ->with('success', "API Key untuk \"{$integration->nama_aplikasi}\" berhasil di-regenerate. Salin key baru sekarang!");
    }

    public function destroy(ApiIntegration $integration): RedirectResponse
    {
        $name = $integration->nama_aplikasi;
        $integration->delete();

        return redirect()->route('integrations.index')
            ->with('success', "Integrasi \"{$name}\" berhasil dihapus.");
    }
}
