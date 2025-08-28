<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $events = Event::with(['eventType'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('event_date')
            ->paginate(12);

        return view('customers.events.index', compact('events'));
    }

    public function create(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $eventTypes = EventType::where('is_active', true)->orderBy('name')->get();
        $services   = Service::where('is_active', true)->orderBy('name')->get();

        return view('customers.events.create', compact('eventTypes', 'services'));
    }

    public function store(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:150'],
            'event_type_id' => ['required', 'exists:event_types,id'],
            'event_date'    => ['required', 'date', 'after:today'],
            'venue'         => ['nullable', 'string', 'max:255'],
            'theme'         => ['nullable', 'string', 'max:120'],
            'budget'        => ['nullable', 'numeric', 'min:0'],
            'guest_count'   => ['nullable', 'integer', 'min:1'],
            'notes'         => ['nullable', 'string', 'max:2000'],

            // add-ons as simple IDs
            'services'      => ['sometimes', 'array'],
            'services.*'    => ['integer', 'exists:services,id'],
        ]);

        DB::transaction(function () use ($customer, $data) {
            $event = Event::create([
                'customer_id'   => $customer->id,
                'event_type_id' => $data['event_type_id'],
                'name'          => $data['name'],
                'event_date'    => $data['event_date'],
                'venue'         => $data['venue'] ?? null,
                'theme'         => $data['theme'] ?? null,
                'budget'        => $data['budget'] ?? null,
                'guest_count'   => $data['guest_count'] ?? null,
                'status'        => 'requested',
                'notes'         => $data['notes'] ?? null,
            ]);

            // attach selected services 
            $event->services()->sync($data['services'] ?? []);
        });


        return redirect()->route('customer.events.index')
            ->with('success', 'Your event request was submitted.');
    }
    public function show(Event $event, Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $event->load(['eventType', 'services']);
        return view('customers.events.show', compact('event'));
    }
}
