<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{

    public function index(Request $request)
    {
        $from   = $request->date('from') ?? now()->startOfMonth()->toDateString();
        $to     = $request->date('to')   ?? now()->endOfMonth()->toDateString();
        $status = $request->string('status')->toString();
        $q = DB::table('event_staff AS es')
            ->join('events AS e', 'e.id', '=', 'es.event_id')
            ->join('staffs AS s', 's.id', '=', 'es.staff_id')
            ->join('users AS u', 'u.id', '=', 's.user_id')
            ->whereBetween('e.event_date', [$from, $to])
            ->select(
                's.id AS staff_id',
                'u.name',
                DB::raw('COUNT(es.event_id) AS events_count'),
                DB::raw('COALESCE(SUM(es.pay_rate),0) AS total_pay')
            );

        if ($status) {
            $q->where('es.pay_status', $status);
        }

        $rows = $q->groupBy('s.id', 'u.name')
            ->orderBy('u.name')
            ->get();

        return view('payroll.index', compact('rows', 'from', 'to', 'status'));
    }

    public function lines(Request $request)
    {
        $from    = $request->date('from') ?? now()->startOfMonth()->toDateString();
        $to      = $request->date('to')   ?? now()->endOfMonth()->toDateString();
        $status  = $request->string('status')->toString();
        $staffId = $request->integer('staff_id');

        $q = DB::table('event_staff AS es')
            ->join('events AS e', 'e.id', '=', 'es.event_id')
            ->join('staffs AS s', 's.id', '=', 'es.staff_id')
            ->join('users AS u', 'u.id', '=', 's.user_id')
            ->select(
                'es.id',
                'es.staff_id',
                'u.name',
                'es.pay_rate',
                'es.pay_status',
                'e.id AS event_id',
                'e.name AS event_name',
                'e.event_date'
            )
            ->whereBetween('e.event_date', [$from, $to]);

        if ($status)  $q->where('es.pay_status', $status);
        if ($staffId) $q->where('es.staff_id', $staffId);

        $lines = $q->orderBy('e.event_date')->get();

        return view('payroll.lines', compact('lines', 'from', 'to', 'status', 'staffId'));
    }

    public function mark(Request $request)
    {
        $data = $request->validate([
            'ids'    => ['required', 'array'],
            'ids.*'  => ['integer'],
            'status' => ['required', 'in:pending,approved,paid'],
        ]);

        DB::table('event_staff')
            ->whereIn('id', $data['ids'])
            ->update(['pay_status' => $data['status']]);

        return back()->with('success', 'Payroll lines updated.');
    }
}
