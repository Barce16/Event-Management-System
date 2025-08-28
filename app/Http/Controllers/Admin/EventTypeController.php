<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\EventType;

class EventTypeController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $types = EventType::when($q, fn($s) =>
        $s->where('name', 'like', "%$q%")
            ->orWhere('description', 'like', "%$q%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.event_types.index', compact('types', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:event_types,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        EventType::create([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
        ]);


        return redirect()->route('admin.management.event_types.index')->with('success', 'Event type created.');
    }

    public function edit(EventType $event_type)
    {
        return view('admin.event_types.edit', compact('event_type'));
    }

    public function create()
    {
        return view('admin.event_types.create');
    }

    public function update(Request $request, EventType $event_type)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:event_types,name,' . $event_type->id],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $event_type->update([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'is_active'   => $request->boolean('is_active', $event_type->is_active),
        ]);

        return redirect()->route('admin.event-types.index')->with('success', 'Event type updated.');
    }

    public function destroy(EventType $event_type)
    {
        $event_type->delete();
        return back()->with('success', 'Event type deleted.');
    }

    public function toggle(EventType $event_type)
    {
        $event_type->update(['is_active' => ! $event_type->is_active]);
        return back()->with('success', 'Event type status updated.');
    }
}
