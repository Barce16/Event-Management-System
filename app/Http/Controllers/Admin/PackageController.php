<?php

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
        $packages = Package::when($q, fn($s) => $s->where('name', 'like', "%$q%"))
            ->orderBy('name')->paginate(12)->withQueryString();

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
            'name'        => ['required', 'string', 'max:120', 'unique:packages,name'],
            'description' => ['nullable', 'string'],
            'base_price'  => ['required', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
            'vendor_ids'  => ['sometimes', 'array'],
            'vendor_ids.*' => ['integer', 'exists:vendors,id'],
        ]);

        $package = Package::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'base_price' => $data['base_price'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!empty($data['vendor_ids'])) {
            $package->vendors()->sync($data['vendor_ids']);
        }

        return redirect()->route('admin.management.packages.index')->with('success', 'Package created.');
    }

    public function edit(Package $package)
    {
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $current = $package->vendors()->pluck('vendor_id')->all();
        return view('admin.packages.edit', compact('package', 'vendors', 'current'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120', 'unique:packages,name,' . $package->id],
            'description' => ['nullable', 'string'],
            'base_price'  => ['required', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
            'vendor_ids'  => ['sometimes', 'array'],
            'vendor_ids.*' => ['integer', 'exists:vendors,id'],
        ]);

        $package->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'base_price' => $data['base_price'],
            'is_active' => $request->boolean('is_active', $package->is_active),
        ]);

        $package->vendors()->sync($data['vendor_ids'] ?? []);

        return redirect()->route('admin.management.packages.index')->with('success', 'Package updated.');
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
