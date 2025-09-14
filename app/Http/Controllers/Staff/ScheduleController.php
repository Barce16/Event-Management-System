<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Staff;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $staff = Staff::where('user_id', $user->id)->firstOrFail();

        $q = $staff->events()->with(['customer', 'package'])->orderBy('event_date');

        if ($status = $request->string('status')->toString()) {
            $q->where('status', $status);
        }
        if ($from = $request->date('from')) {
            $q->whereDate('event_date', '>=', $from);
        }
        if ($to = $request->date('to')) {
            $q->whereDate('event_date', '<=', $to);
        }

        if (!$request->has('from') && !$request->has('to')) {
            $q->whereDate('event_date', '>=', now()->toDateString());
        }

        $events = $q->paginate(10);

        return view('staff.schedule.index', compact('events', 'staff'));
    }

    public function show(Request $request, Event $event)
    {
        $staff = Staff::where('user_id', $request->user()->id)->firstOrFail();

        if (!$event->staffs->contains('id', $staff->id)) {
            abort(403, 'You are not assigned to this event.');
        }


        $event->load(['customer', 'package', 'vendors', 'staffs.user']);

        return view('staff.schedule.show', compact('event', 'staff'));
    }
}
