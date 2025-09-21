<?php

// app/Http/Controllers/Admin/PackageController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inclusion;
use App\Models\Package;
use App\Models\Vendor;
use App\Models\Event;
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
        $inclusions = Inclusion::where('is_active', true)->orderBy('name')->get();
        return view('admin.packages.create', compact('vendors', 'inclusions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                'unique:packages,name',
                'regex:/^[A-Za-z0-9 \-]+$/',
            ],
            'description' => ['nullable', 'string'],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d+)?$/',
            ],
            'is_active'   => ['sometimes', 'boolean'],
            'vendors'     => ['sometimes', 'array'],
            'vendors.*'   => ['integer', 'exists:vendors,id'],
            'inclusions'            => ['nullable', 'array'],
            'inclusions.*.id'       => ['integer', 'exists:inclusions,id'],
            'inclusions.*.notes'    => ['nullable', 'string', 'max:5000'],
            'event_styling_text' => ['nullable', 'string', 'max:10000'],
            'coordination'       => ['nullable', 'string', 'max:5000'],
        ]);

        $eventStylingArray = collect(preg_split('/\r\n|\r|\n/', $data['event_styling_text'] ?? ''))
            ->map(fn($s) => trim($s))
            ->filter()
            ->values()
            ->all();

        $package = Package::create([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'is_active'   => $request->boolean('is_active', true),
            'event_styling' => $eventStylingArray,
            'coordination'  => $data['coordination'] ?? null,
        ]);

        $incoming = $request->input('inclusions', []);
        $sync = [];
        foreach ($incoming as $row) {
            if (!empty($row['id'])) {
                $sync[(int)$row['id']] = ['notes' => $row['notes'] ?? null];
            }
        }

        $package->inclusions()->sync($sync);

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
        $eventsUsingPackage = Event::with(['customer'])
            ->where('package_id', $package->id)
            ->orderByDesc('event_date')
            ->paginate(10);

        return view('admin.packages.show', compact('package', 'eventsUsingPackage'));
    }

    public function edit(Package $package)
    {
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $inclusions = Inclusion::where('is_active', true)->orderBy('name')->get();
        $package->load('vendors');
        return view('admin.packages.edit', compact('package', 'vendors', 'inclusions'));
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
            'inclusions'            => ['nullable', 'array'],
            'inclusions.*.id'       => ['integer', 'exists:inclusions,id'],
            'inclusions.*.notes'    => ['nullable', 'string', 'max:5000'],
            'event_styling_text' => ['nullable', 'string', 'max:10000'],
            'coordination'       => ['nullable', 'string', 'max:5000'],
        ]);

        $eventStylingArray = collect(preg_split('/\r\n|\r|\n/', $data['event_styling_text'] ?? ''))
            ->map(fn($s) => trim($s))
            ->filter()
            ->values()
            ->all();

        $package->update([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'is_active'   => $request->boolean('is_active', $package->is_active),
            'event_styling' => $eventStylingArray,
            'coordination'  => $data['coordination'] ?? null,
        ]);

        // -- Sync vendors
        $package->vendors()->sync($data['vendors'] ?? []);

        // -- Sync inclusions
        $incoming = $request->input('inclusions', []);
        $sync = [];
        foreach ($incoming as $row) {
            if (!empty($row['id'])) {
                $sync[(int)$row['id']] = ['notes' => $row['notes'] ?? null];
            }
        }
        $package->inclusions()->sync($sync);


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
