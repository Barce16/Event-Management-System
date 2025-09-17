<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignEventStaffRequest;
use App\Models\Event;
use App\Models\Staff;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $assignedRoles = $event->staffs->pluck('pivot.assignment_role', 'id');

        $allStaff = Staff::with('user')
            ->where('is_active', true)
            ->orderBy(
                \App\Models\User::select('name')
                    ->whereColumn('users.id', 'staffs.user_id')
                    ->limit(1)
            )
            ->get();
        return view('admin.events.show', compact('event', 'allStaff', 'assignedRoles'));
    }

    public function updateStatus(Request $request, Event $event)
    {
        $data = $request->validate([
            'status' => ['required', 'in:requested,approved,scheduled,completed,cancelled'],
        ]);

        $event->update(['status' => $data['status']]);

        return back()->with('success', 'Event status updated.');
    }

    public function assignStaff(Request $request, Event $event)
    {
        $validated = $request->validate([
            'staff_ids'     => ['array'],
            'staff_ids.*'   => ['integer', 'exists:staffs,id'],
            'roles'         => ['array'],
            'rates'         => ['array'],
        ]);

        $staffIds = collect($validated['staff_ids'] ?? []);

        $syncData = [];
        foreach ($staffIds as $sid) {
            $role = $validated['roles'][$sid] ?? null;
            $rate = $validated['rates'][$sid] ?? null;

            $existsConflict = Event::whereDate('event_date', $event->event_date)
                ->whereHas('staffs', fn($q) => $q->where('staff_id', $sid))
                ->where('events.id', '!=', $event->id)
                ->exists();

            if ($existsConflict) {
                return back()->withErrors([
                    'staff_ids' => "Selected staff has another event on {$event->event_date}.",
                ])->withInput();
            }

            if ($rate === null) {
                $defaultRate = Staff::find($sid)?->rate;
                $rate = $defaultRate ?? 0;
            }

            $syncData[$sid] = [
                'assignment_role' => $role,
                'pay_rate'        => is_numeric($rate) ? (float)$rate : 0,
                'pay_status'      => $event->staffs->contains('id', $sid)
                    ? ($event->staffs->firstWhere('id', $sid)->pivot->pay_status ?? 'pending')
                    : 'pending',
            ];
        }

        $event->staffs()->sync($syncData);

        return back()->with('success', 'Staff assignments updated (rates saved).');
    }


    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }
}
