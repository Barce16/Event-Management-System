<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('name')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // See if a service with the same name exists, even if soft-deleted
        $existing = Service::withTrashed()
            ->where('name', $data['name'])
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                // Bring it back and update the fields
                $existing->restore();
                $existing->update([
                    'description' => $data['description'] ?? null,
                    'base_price'  => $data['base_price'],
                    'is_active'   => $data['is_active'] ?? true,
                ]);

                return redirect()
                    ->route('admin.management.services.index')
                    ->with('success', 'Service restored and updated.');
            }

            // Exists and is active => block
            return back()
                ->withErrors(['name' => 'A service with this name already exists.'])
                ->withInput();
        }

        // No existing record — create new
        Service::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'base_price'  => $data['base_price'],
            'is_active'   => $data['is_active'] ?? true,
        ]);

        return redirect()
            ->route('admin.management.services.index')
            ->with('success', 'Service created.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());
        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }

    // quick enable/disable
    public function toggle(Service $service): RedirectResponse
    {
        $service->update(['is_active' => ! $service->is_active]);
        return back()->with('success', 'Service status updated.');
    }
}
