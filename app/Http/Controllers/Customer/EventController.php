<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $events = Event::with(['package', 'vendors'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('event_date')
            ->paginate(12);

        return view('customers.events.index', compact('events'));
    }


    public function create(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $packages = \App\Models\Package::with(['vendors' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)->orderBy('name')->get();


        $vendors = \App\Models\Vendor::where('is_active', true)->orderBy('name')->get();

        return view('customers.events.create', compact('packages', 'vendors'));
    }



    public function store(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'package_id'  => ['required', 'exists:packages,id'],
            'event_date'  => ['required', 'date', 'after:today'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:120'],
            'budget'       => ['nullable', 'numeric', 'min:0'],
            'guest_count'  => ['nullable', 'integer', 'min:1'],
            'notes'        => ['nullable', 'string', 'max:2000'],
            'vendors'      => ['sometimes', 'array'],
            'vendors.*'    => ['integer', 'exists:vendors,id'],
        ]);

        DB::transaction(function () use ($customer, $data) {
            $event = Event::create([
                'customer_id'  => $customer->id,
                'package_id'   => $data['package_id'],
                'name'         => $data['name'],
                'event_date'   => $data['event_date'],
                'venue'        => $data['venue'] ?? null,
                'theme'        => $data['theme'] ?? null,
                'budget'       => $data['budget'] ?? null,
                'guest_count'  => $data['guest_count'] ?? null,
                'status'       => 'requested',
                'notes'        => $data['notes'] ?? null,
            ]);

            $event->vendors()->sync($data['vendors'] ?? []);
        });

        return redirect()->route('customer.events.index')->with('success', 'Your event request was submitted.');
    }
    public function show(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $event->load(['package.vendors', 'vendors']);
        return view('customers.events.show', compact('event'));
    }

    public function edit(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $packages = \App\Models\Package::with(['vendors' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)->orderBy('name')->get();

        $vendors  = \App\Models\Vendor::where('is_active', true)->orderBy('name')->get();

        $event->load(['package', 'vendors']);
        $selectedVendorIds = $event->vendors->pluck('id')->all();

        return view('customers.events.edit', compact('event', 'packages', 'vendors', 'selectedVendorIds'));
    }

    public function update(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'package_id'   => ['required', 'exists:packages,id'],
            'event_date'   => ['required', 'date', 'after:today'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:120'],
            'budget'       => ['nullable', 'numeric', 'min:0'],
            'guest_count'  => ['nullable', 'integer', 'min:1'],
            'notes'        => ['nullable', 'string', 'max:2000'],
            'vendors'      => ['sometimes', 'array'],
            'vendors.*'    => ['integer', 'exists:vendors,id'],
        ]);

        DB::transaction(function () use ($event, $data) {
            $event->update([
                'package_id'  => $data['package_id'],
                'name'        => $data['name'],
                'event_date'  => $data['event_date'],
                'venue'       => $data['venue'] ?? null,
                'theme'       => $data['theme'] ?? null,
                'budget'      => $data['budget'] ?? null,
                'guest_count' => $data['guest_count'] ?? null,
                'notes'       => $data['notes'] ?? null,
            ]);

            $event->vendors()->sync($data['vendors'] ?? []);
        });

        return redirect()->route('customers.event.show', $event)->with('success', 'Event updated.');
    }
}
