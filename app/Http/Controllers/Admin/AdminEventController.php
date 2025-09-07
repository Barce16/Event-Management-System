<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Package;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $q         = (string) $request->query('q', '');
        $status    = (string) $request->query('status', '');
        $dateFrom  = $request->date('from');
        $dateTo    = $request->date('to');
        $packageId = $request->integer('package_id');

        // --- Sanitize search query ($q) ---
        $q = trim($q);
        $q = preg_replace('/[<>]/', '', $q);
        $q = mb_substr($q, 0, 120);

        $packages = Package::orderBy('name')->get(['id', 'name']);

        $events = Event::query()
            ->with(['customer:id,customer_name,email', 'package:id,name'])
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('venue', 'like', "%{$q}%")
                        ->orWhereHas('customer', function ($c) use ($q) {
                            $c->where('customer_name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->when($status !== '', fn($s) => $s->where('status', $status))
            ->when($packageId, fn($s) => $s->where('package_id', $packageId))
            ->when($dateFrom, fn($s) => $s->whereDate('event_date', '>=', $dateFrom))
            ->when($dateTo,   fn($s) => $s->whereDate('event_date', '<=', $dateTo))
            ->orderByDesc('event_date')
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.events.index',
            compact('events', 'packages', 'q', 'status', 'packageId', 'dateFrom', 'dateTo')
        );
    }

    public function show(Event $event)
    {
        $event->load(['customer', 'package', 'vendors']);
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
