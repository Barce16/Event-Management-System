<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
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

        $packages = \App\Models\Package::with([
            'vendors:id,name,category,price',
            'inclusions'
        ])->where('is_active', true)->orderBy('price')->get();


        $vendors = \App\Models\Vendor::where('is_active', true)->orderBy('name')->get();

        return view('customers.events.create', compact('packages', 'vendors'));
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'event_date'  => ['required', 'date'],
            'package_id'  => ['nullable', 'exists:packages,id'],
            'venue'       => ['nullable', 'string', 'max:255'],
            'theme'       => ['nullable', 'string', 'max:255'],
            'budget'      => ['nullable', 'numeric', 'min:0'],
            'notes'       => ['nullable', 'string'],

            'vendors'     => ['nullable', 'array'],
            'vendors.*'   => ['integer', 'exists:vendors,id'],

            'guests'                  => ['nullable', 'array'],
            'guests.*.name'           => ['required', 'string', 'max:255'],
            'guests.*.email'          => ['nullable', 'email', 'max:255'],
            'guests.*.contact_number' => ['nullable', 'string', 'max:50'],
            'guests.*.party_size'     => ['nullable', 'integer', 'min:1'],
        ]);

        $user = $request->user();
        $customerId = optional($user->customer)->id;

        DB::transaction(function () use ($validated, $customerId, &$event) {
            // 1) Create the event
            $event = Event::create([
                'name'        => $validated['name'],
                'event_date'  => $validated['event_date'],
                'package_id'  => $validated['package_id'] ?? null,
                'customer_id' => $customerId,
                'venue'       => $validated['venue'] ?? null,
                'theme'       => $validated['theme'] ?? null,
                'budget'      => $validated['budget'] ?? null,
                'notes'       => $validated['notes'] ?? null,
                'status'      => 'requested', // or whatever your default is
            ]);

            // 2) Attach vendors if any
            if (!empty($validated['vendors'])) {
                // if you have price overrides you’d handle pivot fields here
                $event->vendors()->sync($validated['vendors']);
            }

            // 3) Insert guests (bulk)
            if (!empty($validated['guests'])) {
                $rows = collect($validated['guests'])->map(function ($g) use ($event) {
                    return [
                        'event_id'       => $event->id,
                        'name'           => $g['name'],
                        'email'          => $g['email'] ?? null,
                        'contact_number' => $g['contact_number'] ?? null,
                        'party_size'     => (int)($g['party_size'] ?? 1),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];
                })->all();

                Guest::insert($rows);
            }
        });

        return redirect()->route('customer.events.index')
            ->with('success', 'Event request submitted.');
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
            'name' => [
                'required',
                'string',
                'max:150',
                'regex:/^[A-Za-z0-9 .-]+$/',
            ],
            'package_id'   => ['required', 'exists:packages,id'],
            'event_date'   => ['required', 'date', 'after:today'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:120'],
            'budget' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d+)?$/',
            ],
            'guest_count' => [
                'nullable',
                'integer',
                'min:1',
            ],
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

        return redirect()->route('customer.events.show', $event)->with('success', 'Event updated.');
    }
}
