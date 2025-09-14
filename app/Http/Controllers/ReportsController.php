<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // ----- Events -----
    public function eventsByMonth(Request $request)
    {
        $rows = DB::table('events')
            ->selectRaw("DATE_FORMAT(event_date, '%Y-%m') AS month, COUNT(*) AS total_events")
            ->groupBy('month')
            ->orderByDesc('month')
            ->get();

        return view('reports.table', [
            'title' => 'Events by Month',
            'headers' => ['Month', 'Total Events'],
            'rows' => $rows->map(fn($r) => [$r->month, $r->total_events]),
        ]);
    }

    public function eventsByStatus()
    {
        $rows = DB::table('events')
            ->select('status', DB::raw('COUNT(*) AS total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        return view('reports.table', [
            'title' => 'Events by Status',
            'headers' => ['Status', 'Total'],
            'rows' => $rows->map(fn($r) => [ucfirst($r->status), $r->total]),
        ]);
    }

    public function upcoming(Request $request)
    {
        $days = (int)($request->input('days', 30));
        $rows = DB::table('events')
            ->select('name', 'event_date', 'venue', 'status')
            ->whereBetween('event_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->orderBy('event_date')
            ->get();

        return view('reports.table', [
            'title' => "Upcoming Events (next {$days} days)",
            'headers' => ['Event', 'Date', 'Venue', 'Status'],
            'rows' => $rows->map(fn($r) => [$r->name, $r->event_date, $r->venue, ucfirst($r->status)]),
            'filters' => [
                'days' => $days,
            ],
        ]);
    }

    // ----- Customers -----
    public function customersByMonth()
    {
        $rows = DB::table('customers')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total_customers")
            ->groupBy('month')
            ->orderByDesc('month')
            ->get();

        return view('reports.table', [
            'title' => 'Customers by Month',
            'headers' => ['Month', 'Total Customers'],
            'rows' => $rows->map(fn($r) => [$r->month, $r->total_customers]),
        ]);
    }

    public function topCustomers()
    {
        $rows = DB::table('customers AS c')
            ->join('events AS e', 'c.id', '=', 'e.customer_id')
            ->select('c.customer_name', DB::raw('COUNT(e.id) AS event_count'))
            ->groupBy('c.customer_name')
            ->orderByDesc('event_count')
            ->limit(10)
            ->get();

        return view('reports.table', [
            'title' => 'Top Customers (by number of events)',
            'headers' => ['Customer', 'Event Count'],
            'rows' => $rows->map(fn($r) => [$r->customer_name, $r->event_count]),
        ]);
    }

    // ----- Vendors & Packages -----
    public function topVendors()
    {
        $rows = DB::table('vendors AS v')
            ->join('event_vendor AS ev', 'v.id', '=', 'ev.vendor_id')
            ->select('v.name', DB::raw('COUNT(ev.event_id) AS times_used'))
            ->groupBy('v.name')
            ->orderByDesc('times_used')
            ->limit(10)
            ->get();

        return view('reports.table', [
            'title' => 'Top Vendors (used in events)',
            'headers' => ['Vendor', 'Times Used'],
            'rows' => $rows->map(fn($r) => [$r->name, $r->times_used]),
        ]);
    }

    public function packageUsage()
    {
        $rows = DB::table('packages AS p')
            ->leftJoin('events AS e', 'p.id', '=', 'e.package_id')
            ->select('p.name', DB::raw('COUNT(e.id) AS used_in_events'))
            ->groupBy('p.name')
            ->orderByDesc('used_in_events')
            ->get();

        return view('reports.table', [
            'title'   => 'Package Usage',
            'headers' => ['Package', 'Used in Events'],
            'rows'    => $rows->map(fn($r) => [$r->name, $r->used_in_events]),
        ]);
    }

    // ----- Staff -----
    public function staffWorkload()
    {
        $rows = DB::table('staffs AS s')
            ->join('users AS u', 'u.id', '=', 's.user_id')
            ->leftJoin('event_staff AS es', 'es.staff_id', '=', 's.id')
            ->select('u.name', DB::raw('COUNT(es.event_id) AS total_events'))
            ->groupBy('u.name')
            ->orderByDesc('total_events')
            ->get();

        return view('reports.table', [
            'title'   => 'Staff Workload (assigned events)',
            'headers' => ['Staff', 'Total Events'],
            'rows'    => $rows->map(fn($r) => [$r->name, $r->total_events]),
        ]);
    }

    // ----- Optional CSV -----
    public function export(Request $request): StreamedResponse
    {

        $title   = $request->string('title', 'report')->toString();
        $headers = $request->input('headers', []);
        $rows    = json_decode($request->input('rows', '[]'), true);

        $filename = Str::slug($title) . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            if (!empty($headers)) fputcsv($out, $headers);
            foreach ($rows as $r) fputcsv($out, $r);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
