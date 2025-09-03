<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $q       = $request->string('q')->toString();
        $status  = $request->string('status')->toString();
        $dateFrom = $request->date('from');
        $dateTo   = $request->date('to');

        $events = Event::query()
            ->with(['customer:id,customer_name,email', 'eventType:id,name'])
            ->when($q, function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('venue', 'like', "%{$q}%")
                        ->orWhereHas('customer', fn($c) => $c->where('customer_name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%"));
                });
            })
            ->when($status, fn($s) => $s->where('status', $status))

            ->when($dateFrom, fn($s) => $s->whereDate('event_date', '>=', $dateFrom))
            ->when($dateTo, fn($s) => $s->whereDate('event_date', '<=', $dateTo))
            ->orderByDesc('event_date')
            ->paginate(15)
            ->withQueryString();


        return view('admin.events.index', compact('events', 'types', 'q', 'status', 'typeId', 'dateFrom', 'dateTo'));
    }

    public function show(Event $event)
    {
        $event->load(['customer', 'eventType', 'services']);
        return view('admin.events.show', compact('event'));
    }

    public function updateStatus(Request $request, Event $event)
    {
        $data = $request->validate([
            'status' => ['required', 'in:requested,approved,scheduled,completed,cancelled'],
        ]);

        $event->update(['status' => $data['status']]);

        return back()->with('success', 'Event status updated.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }
}
