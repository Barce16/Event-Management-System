<?php

// app/Http/Controllers/Admin/PackageController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $packages = Package::query()
            ->when(
                $q,
                fn($s) =>
                $s->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
            )
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.packages.index', compact('packages', 'q'));
    }

    public function create()
    {
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        return view('admin.packages.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150', 'unique:packages,name'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
            'vendors'     => ['sometimes', 'array'],
            'vendors.*'   => ['integer', 'exists:vendors,id'],
        ]);

        $package = Package::create([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'is_active'   => $request->boolean('is_active', true),
        ]);

        // Attach selected vendors
        if (!empty($data['vendors'])) {
            $package->vendors()->attach($data['vendors']);
        }

        return redirect()->route('admin.management.packages.index')
            ->with('success', 'Package created.');
    }

    public function show(Package $package)
    {
        $package->load('vendors');
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $package->load('vendors');
        return view('admin.packages.edit', compact('package', 'vendors'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150', 'unique:packages,name,' . $package->id],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
            'vendors'     => ['sometimes', 'array'],
            'vendors.*'   => ['integer', 'exists:vendors,id'],
        ]);

        $package->update([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'is_active'   => $request->boolean('is_active', $package->is_active),
        ]);

        // Sync vendors
        $package->vendors()->sync($data['vendors'] ?? []);

        return redirect()->route('admin.management.packages.index')
            ->with('success', 'Package updated.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return back()->with('success', 'Package deleted.');
    }

    public function toggle(Package $package)
    {
        $package->update(['is_active' => ! $package->is_active]);
        return back()->with('success', 'Package status updated.');
    }
}
