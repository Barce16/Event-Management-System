<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $vendors = Vendor::query()
            ->when($q, function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('contact_person', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.vendors.index', compact('vendors', 'q'));
    }

    public function create()
    {
        $vendor = new Vendor(['is_active' => true]);
        return view('admin.vendors.create', compact('vendor'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:150', Rule::unique('vendors', 'name')->whereNull('deleted_at')],
            'category'       => ['required', 'string', 'max:100'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email'          => ['nullable', 'email', 'max:150'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'price'          => ['required', 'numeric', 'min:0'],
            'address'        => ['nullable', 'string', 'max:500'],
            'notes'          => ['nullable', 'string', 'max:2000'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Vendor::create($data);

        return redirect()->route('admin.management.vendors.index')
            ->with('success', 'Vendor created.');
    }
    public function show(Vendor $vendor)
    {
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name'           => [
                'required',
                'string',
                'max:150',
                Rule::unique('vendors', 'name')->ignore($vendor->id)->whereNull('deleted_at')
            ],
            'category'       => ['required', 'string', 'max:100'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email'          => ['nullable', 'email', 'max:150'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'price'          => ['required', 'numeric', 'min:0'],
            'address'        => ['nullable', 'string', 'max:500'],
            'notes'          => ['nullable', 'string', 'max:2000'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', $vendor->is_active);

        $vendor->update($data);

        return redirect()->route('admin.management.vendors.show', $vendor)
            ->with('success', 'Vendor updated.');
    }
}
